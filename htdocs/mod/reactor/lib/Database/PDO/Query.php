<?php

namespace Reactor\Database\PDO;

use Reactor\Database\Interfaces\QueryInterface;
use Reactor\Database\Exceptions as Exceptions;

/**
 * Class Query
 *
 * @package Reactor\Database\PDO
 */
class Query implements QueryInterface
{
    protected $stats = [];
    
    protected $statement;
    
    protected $line = null;
    
    protected $iterator_key = 0;
    
    /**
     * Query constructor.
     *
     * @param $statement \PDOStatement
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
    }
    
    /**
     * @param array $parameters
     *
     * @return $this
     * @throws \Reactor\Database\Exceptions\DatabaseException
     */
    public function exec($parameters = [])
    {
        $this->statement->closeCursor();
        
        $parameters = (array) $parameters;
        
        $this->stats['parameters'] = $parameters;
        
        $execution_time = microtime(true);
        
        try {
            $this->statement->execute($parameters);
        } catch (\PDOException $exception) {
            throw new Exceptions\DatabaseException($exception->getMessage(), $this);
        }
        
        $this->stats['execution_time'] = microtime(true) - $execution_time;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function __destruct()
    {
        $this->statement->closeCursor();
    }
    
    /**
     * @param string $row
     *
     * @return mixed|null
     */
    public function line($row = '*')
    {
        $line = $this->statement->fetch(\PDO::FETCH_ASSOC);
        
        if ($line) {
            if ($row == '*') {
                return $line;
            }
            
            return $line[$row];
        }
        
        return null;
    }
    
    /**
     * @return bool
     */
    public function free()
    {
        $this->statement->closeCursor();
    }
    
    /**
     * @param null   $key
     * @param string $row
     *
     * @return array
     */
    public function matr($key = null, $row = '*')
    {
        $data = [];
        
        if ($key === null) {
            if ($row == '*') {
                $data = $this->statement->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                while ($line = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $line[$row];
                }
            }
        } else {
            if ($row == '*') {
                while ($line = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[$line[$key]] = $line;
                }
            } else {
                while ($line = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[$line[$key]] = $line[$row];
                }
            }
        }
        
        return $data;
    }
    
    /**
     * @return int
     */
    public function count()
    {
        return $this->statement->rowCount();
    }
    
    /**
     * @return array
     */
    public function getStats()
    {
        $this->stats['query'] = $this->statement->queryString;
        
        return $this->stats;
    }
    
    /**
     * Hackish method if advanced PDO features are requred
     *
     * @return \PDOStatement
     */
    public function getStatement()
    {
        return $this->statement;
    }
    
    /**
     * @return mixed|null
     */
    public function current()
    {
        if (!$this->line) {
            $this->next();
        }
        
        return $this->line;
    }
    
    /**
     * @return int
     */
    public function key()
    {
        return $this->iterator_key;
    }
    
    /**
     * @return void
     */
    public function next()
    {
        $this->line = $this->line();
        
        $this->iterator_key++;
        
        if (!$this->line) {
            $this->iterator_key = false;
        }
    }
    
    /**
     * @return int
     */
    public function rewind()
    {
        return $this->iterator_key = 0;
    }
    
    /**
     * @return bool
     */
    public function valid()
    {
        return $this->iterator_key !== false;
    }
}
