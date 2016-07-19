<?php

namespace Reactor\Database\PDO;

use Reactor\Database\Exceptions as Exceptions;
use Reactor\Database\Interfaces\ConnectionInterface;

/**
 * Class Connection
 *
 * @package Reactor\Database\PDOs
 */
class Connection implements ConnectionInterface
{
    protected $connection = null;
    
    protected $connection_string;
    
    protected $user;
    
    protected $pass;
    
    /**
     * Connection constructor.
     *
     * @param      $connection_string
     * @param null $user
     * @param null $pass
     */
    public function __construct($connection_string, $user = null, $pass = null)
    {
        $this->connection_string = $connection_string;
        $this->user = $user;
        $this->pass = $pass;
    }
    
    /**
     * @return null|\PDO
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            try {
                $this->connection = new \PDO($this->connection_string, $this->user, $this->pass);
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $exception) {
                throw new Exceptions\DatabaseException($exception->getMessage(), $this);
            }
        }
        
        return $this->connection;
    }
    
    /**
     * @param       $func
     * @param array $param
     *
     * @return bool
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function transaction($func, $param = [])
    {
        if (!is_callable($func) || !is_array($param)) {
            return false;
        }
        
        try {
            $this->beginTransaction();
            
            call_user_func_array($func, $param);
            
            return $this->commit();
        } catch (\Exception $exception) {
            $this->rollBack();
            
            throw new Exceptions\DatabaseException('Transaction failed - ' . $exception->getMessage(), $this);
        }
    }
    
    /**
     * @return bool
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function beginTransaction()
    {
        try {
            return $this->getConnection()->beginTransaction();
        } catch (\Exception $exception) {
            throw new Exceptions\DatabaseException($exception->getMessage(), $this);
        }
    }
    
    /**
     * @return bool
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function commit()
    {
        try {
            return $this->getConnection()->commit();
        } catch (\Exception $exception) {
            throw new Exceptions\DatabaseException($exception->getMessage(), $this);
        }
    }
    
    /**
     * @return bool
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function rollBack()
    {
        try {
            return $this->getConnection()->rollBack();
        } catch (\Exception $exception) {
            throw new Exceptions\DatabaseException($exception->getMessage(), $this);
        }
    }
    
    /**
     * @param       $query
     * @param array $arguments
     *
     * @return $this|\Reactor\Database\PDO\Query
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function sql($query, $arguments = [])
    {
//        echo "\n$query ".json_encode($arguments)."<br>";
        
        $statement = $this->getConnection()->prepare($query);
        
        if (!$statement) {
            throw new Exceptions\DatabaseException($this->getConnection()->errorInfo()[2], $this);
        }
        
        $query = new Query($statement);
        
        if ($arguments === null) {
            return $query;
        }
        
        return $query->exec($arguments);
    }
    
    /**
     * @param null $name
     *
     * @return string
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function lastId($name = null)
    {
        return $this->getConnection()->lastInsertId($name);
    }
    
    /**
     * @param $where
     *
     * @return string
     */
    protected function wrapWhere($where)
    {
        if (trim($where) == '') {
            return ' ';
        }
        
        return ' WHERE ' . $where;
    }
    
    /**
     * @param        $table
     * @param array  $where_data
     * @param string $where
     *
     * @return \Reactor\Database\PDO\Connection|\Reactor\Database\PDO\Query
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function select($table, $where_data = [], $where = '')
    {
        if ($where === '') {
            $where = $this->buildPairs(array_keys($where_data), 'and');
        }
        
        return $this->sql(
            'SELECT * FROM `' . $table . '`'
            . $this->wrapWhere($where),
            $where_data
        );
    }
    
    /**
     * @param $table
     * @param $data
     *
     * @return string
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function insert($table, $data)
    {
        $keys = array_keys($data);
        
        $this->sql(
            'INSERT INTO `' . $table . '` (`' . implode('`, `', $keys) . '`)
            VALUES (:' . implode(', :', $keys) . ')',
            $data
        );
        
        return $this->lastId();
    }
    
    /**
     * @param $table
     * @param $data
     *
     * @return string
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function replace($table, $data)
    {
        $keys = array_keys($data);
        
        $this->sql(
            'REPLACE INTO `' . $table . '` (`' . implode('`, `', $keys) . '`)
            VALUES (:' . implode(', :', $keys) . ')',
            $data
        );
        
        return $this->lastId();
    }
    
    /**
     * @param        $keys
     * @param string $delimeter
     *
     * @return string
     */
    public function buildPairs($keys, $delimeter = ',')
    {
        $pairs = [];
        
        foreach ($keys as $k) {
            $pairs[] = '`' . $k . '`= :' . $k;
        }
        
        return implode(' ' . $delimeter . ' ', $pairs);
    }
    
    /**
     * @param        $table
     * @param        $data
     * @param array  $where_data
     * @param string $where
     *
     * @return int
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function update($table, $data, $where_data = [], $where = '')
    {
        if ($where === '') {
            $where = $this->buildPairs(array_keys($where_data), 'AND');
        }
        $query = $this->sql(
            'UPDATE `' . $table . '` SET '
            . $this->buildPairs(array_keys($data))
            . $this->wrapWhere($where),
            array_merge($data, $where_data)
        );
        
        return $query->count();
    }
    
    /**
     * @param        $table
     * @param array  $where_data
     * @param string $where
     *
     * @return int
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function delete($table, $where_data = [], $where = '')
    {
        if ($where === '') {
            $where = $this->buildPairs(array_keys($where_data), 'AND');
        }
        
        $query = $this->sql(
            'DELETE FROM `' . $table . '` '
            . $this->wrapWhere($where),
            $where_data
        );
        
        return $query->count();
    }
    
    /**
     * @param      $query
     * @param      $parameters
     * @param      $page
     * @param      $per_page
     * @param null $total_rows
     *
     * @return array
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function pages($query, $parameters, $page, $per_page, $total_rows = null)
    {
        $per_page = (int) $per_page;
        $page = (int) $page;
        
        if ($page == 0) {
            $data = $this->sql($query, $parameters)->matr();
        } else {
            
            $from = ($page - 1) * $per_page;
            $data = $this->sql($query . ' limit ' . $from . ', ' . $per_page, $parameters)->matr();
        }
        
        if ($total_rows === null) {
            $cnt_query = stristr('from', $query);
            
            $t = strripos($cnt_query, 'order by');
            if ($t !== false) {
                $cnt_query = substr($cnt_query, 0, $t);
            }
            
            $total_rows = $this->sql('SELECT count(*) as `count` ' . $cnt_query)->line('count');
        }
        
        $total_pages = ceil($total_rows / $per_page);
        
        return [
            'data'        => $data,
            'total_rows'  => $total_rows,
            'total_pages' => $total_pages,
            'page'        => $page,
            'per_page'    => $per_page,
        ];
    }
}
