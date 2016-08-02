<?php

namespace reactor;

class basic_object
{
    public $table;
    public $pkey;
    public $pkey_value;
    public $fkey;
    public $fkey_value;
    public $order;
    /**
     * @var $_db \Reactor\Database\PDO\Connection
     */
    public $_db;
    public $_pool_id;

    public function __construct($table = '', $pkey = '', $order = '')
    {
        $this->pkey     = $pkey;
        $this->table    = $table;
        $this->fkey     = '';
        $this->order    = $order;
        $this->_pool_id = 0;
        $this->onRestore();
    }

    public function onRestore()
    {
        global $_db;

        $this->_db = &$_db;
    }

    public function configure($table, $order = '', $fk_value = 0)
    {
        $_container       = &pool_get($this->_pool_id);
        $this->pkey       = $_container['pkey'];
        $this->table      = $table;
        $this->pkey_value = 0;
        if (count($_container['configurators']) > 0) {
            $this->fkey       = $_container['configurators'][0];
            $this->fkey_value = $fk_value;
        }
        $this->order = $order;
    }

    public function getList($page, $per_page, $where = '')
    {
        $where_arr        = array(1);
        $where_parameters = array();

        if ($this->fkey != '') {
            $where_arr[] = sprintf('%s = :fkey_value', $this->fkey);

            $where_parameters[':fkey_value'] = $this->fkey_value;
        }

        if ($where != '') {
            $where_arr[] = $where;
        }

        $order = $this->order != '' ? sprintf('ORDER BY %s', $this->order) : '';

        $pages = $this->_db->pages(
            sprintf(
                'SELECT *
                FROM %s
                WHERE %s
                %s',
                $this->table,
                implode(' AND ', $where_arr),
                $order
            ),
            $where_parameters,
            $page,
            $per_page
        );

        return array(
            'all'              => $pages['total_pages'],
            'total_rows_count' => $pages['total_rows'],
            'page'             => pool_get($this->_pool_id, 'name') . '_page',
            'per_page'         => $per_page,
            'now'              => $page,
            'data'             => $pages['data'],
        );
    }

    public function getOne($pk = 0, $row = '*')
    {
        $query = $this->_db->select($this->table, array($this->pkey => $pk));

        $rez = $query->line($row);

        $this->pkey_value = $pk;

        if ($rez == 0) {
            $this->pkey_value = 0;
        }

        return $rez;
    }

    public function get($where = '', $rows = '*')
    {
        if ($where != '') {
            $where = 'WHERE ' . $where;
        }

        $query = $this->_db->sql('SELECT ' . $rows . ' FROM ' . $this->table . ' ' . $where);

        return $query->matr();
    }

    public function getSelect($row, $filter, $forceOne = 0, $forceFkey = 1)
    {
        if ($forceOne == 1) {
            return $this->getOne($filter, $row);
        }

        $where_rez = array();

        $filter = explode(' ', $filter);

        foreach ($filter as $word) {
            $where_rez[] = '`' . $row . '` LIKE "%' . $word . '%"';
        }

        $where_rez = ' WHERE (' . implode(' AND ', $where_rez) . ')';

        if ($this->fkey != '' && $forceFkey == 1) {
            $where_rez .= ' AND `' . $this->fkey . '` = "' . $this->fkey_value . '"';
        }

        $query = $this->_db->sql(
            'SELECT
                ' . $this->pkey . ',
                ' . $row . '
            FROM ' . $this->table . '
            ' . $where_rez . '
            LIMIT 25'
        );

        return $query->matr($this->pkey, $row);
    }

    public function delete($rule, $isStream = 0)
    {
        $this->key = 0;

        if ($isStream == 0) {
            return $this->_db->delete($this->table, array($this->pkey => $rule));
        } else {
            return $this->_db->delete($this->table, array(), $rule);
        }
    }

    public function insert($data = 1)
    {
        if ($data == 1) {
            $data = array();
        }

        $lastId = $this->_db->insert($this->table, $data);

        return isset($data[$this->pkey]) ? $data[$this->pkey] : $lastId;
    }

    public function update($data, $rule, $isStream = 0)
    {
        if ($isStream == 0) {
            return $this->_db->update($this->table, $data, array($this->pkey => $rule));
        } else {
            return $this->_db->update($this->table, $data, array(), $rule);
        }
    }

    public function replace($data = 1, $pk)
    {
        if ($data == 1) {
            $data = array();
        }

        return $this->_db->replace($this->table, $data) ? $pk : 0;
    }

    public function setFkey($value)
    {
        $this->fkey_value = $value;
    }

    public function store($form)
    {
        $data = $form->toDb();

        if ($this->fkey != '' && !isset($data[$this->fkey])) {
            $data[$this->fkey] = $this->fkey_value;
        }

        if ($this->pkey_value == 0) {
            $this->pkey_value = $this->insert($data);
        } else {
            $this->update($data, $this->pkey_value);
        }

        return $this->pkey_value;
    }

    public function moveUp($node_key)
    {
        if ($this->order == '') {
            return 0;
        }

        $order = str_ireplace('desc', '', $this->order);

        $t = array();

        if ($this->fkey != '') {
            $query = $this->_db->select($this->table, array($this->pkey => $node_key));

            $t = $query->line();

            if ($t == 0) {
                return 0;
            }
        }

        $direct = '-3';

        if ($order != $this->order) {
            $direct = '+3';
        }

        $this->_db->sql(
            'UPDATE ' . $this->table . '
            SET ' . $order . ' = ' . $order . $direct . '
            WHERE `' . $this->pkey . '` = "' . $node_key . '"'
        );

        $this->_db->sql('set @a:=0');

        $where = '';

        if ($this->fkey != '') {
            $where = 'WHERE `' . $this->fkey . '` = "' . $t[$this->fkey] . '"';
        }

        $this->_db->sql(
            'UPDATE ' . $this->table . '
            SET ' . $order . ' = (@a:=@a+2)
            ' . $where . '
            ORDER BY ' . $order
        );

        return 1;
    }

    public function moveDown($node_key)
    {
        if ($this->order == '') {
            return 0;
        }

        $order = str_ireplace('desc', '', $this->order);

        $t = array();

        if ($this->fkey != '') {
            $query = $this->_db->select($this->table, array($this->pkey => $node_key));

            $t = $query->line();

            if ($t == 0) {
                return 0;
            }
        }

        $direct = '+3';

        if ($order != $this->order) {
            $direct = '-3';
        }

        $this->_db->sql(
            'UPDATE ' . $this->table . '
            SET ' . $order . ' = ' . $order . $direct . '
            WHERE `' . $this->pkey . '` = "' . $node_key . '"'
        );

        $this->_db->sql('set @a:=0');

        $where = '';

        if ($this->fkey != '') {
            $where = 'WHERE `' . $this->fkey . '` = "' . $t[$this->fkey] . '"';
        }

        $this->_db->sql(
            'UPDATE ' . $this->table . '
            SET ' . $order . ' = (@a:=@a+2)
            ' . $where . '
            ORDER BY ' . $order
        );

        return 1;
    }

    /**
     * Because we cannot serialize or unserialize PDO instances
     *
     * @return array
     */
    public function __sleep()
    {
        unset($this->_db);

        return array_keys((array) $this);
    }

    public function __wakeup()
    {
        global $_db;

        $this->_db = &$_db;
    }
}
