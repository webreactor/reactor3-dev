<?php

//version 2.1.4

class basic_object {
    var $table;
    var $pkey;
    var $pkey_value;
    var $fkey;
    var $fkey_value;
    var $order;
    var $_db;
    var $_pool_id;

    function basic_object($table = '', $pkey = '', $order = '') {
        $this->pkey = $pkey;
        $this->table = '`' . $table . '`';
        $this->fkey = '';
        $this->order = $order;
        $this->_pool_id = 0;
        $this->onRestore();
    }

    function onRestore() {
        global $_db;
        $this->_db =& $_db;
    }

    function configure($table, $order = '', $fk_value = 0) {
        $_container =& pool_get($this->_pool_id);
        $this->pkey = $_container['pkey'];
        $this->table = '`' . $table . '`';
        $this->pkey_value = 0;
        if (count($_container['configurators']) > 0) {
            $this->fkey = $_container['configurators'][0];
            $this->fkey_value = $fk_value;
        }
        $this->order = $order;
    }

    function getList($page, $per_page, $where = '') {
        $all = 0;
        $where_rez = ' where 1';
        if ($this->fkey != '') {
            $where_rez .= ' and `' . $this->fkey . '`="' . $this->fkey_value . '"';
        }
        if ($where != '') {
            $where_rez .= ' and ' . $where;
        }
        if ($this->order != '') {
            $where_rez .= ' order by ' . $this->order;
        }
        $data = $this->_db->pages('select * from ' . $this->table . $where_rez, $page, $per_page, $all,
            $total_rows_count);

        return array(
            'all'              => $all,
            'total_rows_count' => $total_rows_count,
            'page'             => pool_get($this->_pool_id, 'name') . '_page',
            'per_page'         => $per_page,
            'now'              => $page,
            'data'             => $data,
        );
    }

    function getOne($pk = 0, $row = '*') {
        $this->_db->sql('select ' . $row . ' from ' . $this->table . ' where `' . $this->pkey . '` = "' . $pk . '"');
        $rez = $this->_db->line($row);
        $this->pkey_value = $pk;
        if ($rez == 0) {
            $this->pkey_value = 0;
        }

        return $rez;
    }

    function get($where = '', $rows = '*') {
        if ($where != '') {
            $where = 'where ' . $where;
        }
        $this->_db->sql('select ' . $rows . ' from ' . $this->table . ' ' . $where);

        return $this->_db->matr();
    }

    function getSelect($row, $filter, $forceOne = 0, $forceFkey = 1) {
        if ($forceOne == 1) {
            return $this->getOne($filter, $row);
        }
        $where_rez = array();
        $filter = explode(' ', $filter);
        foreach ($filter as $word) {
            $where_rez[] = '`' . $row . '` like "%' . $word . '%"';
        }

        $where_rez = ' where (' . implode(' and ', $where_rez) . ')';

        if ($this->fkey != '' && $forceFkey == 1) {
            $where_rez .= ' and `' . $this->fkey . '`="' . $this->fkey_value . '"';
        }
        $this->_db->sql('select ' . $this->pkey . ', ' . $row . ' from ' . $this->table . $where_rez . ' limit 25');

        return $this->_db->matr($this->pkey, $row);
    }

    function delete($rule, $isStream = 0) {
        if ($isStream == 0) {
            $where_exp = '`' . $this->pkey . '`="' . $rule . '"';
        } else {
            $where_exp = $rule;
        }

        $this->_db->sql('delete from ' . $this->table . ' where ' . $where_exp);
        $this->key = 0;

        return $this->_db->affected_rows();
    }

    function insert($data = 1) {
        if ($data == 1) {
            $data = array();
        }

        $rows = '';
        $values = '';
        foreach ($data as $k => $v) {
            $rows .= '`' . $k . '`,';
            $values .= '"' . $v . '",';
        }
        $values = substr($values, 0, -1);
        $rows = substr($rows, 0, -1);

        if ($this->_db->sql('insert into ' . $this->table . ' (' . $rows . ') values (' . $values . ')')) {
            if (isset($data[$this->pkey])) {
                return $data[$this->pkey];
            } else {
                return $this->_db->last_id();
            }
        } else {
            return 0;
        }
    }

    function update($data, $rule, $isStream = 0) {
        if ($isStream == 0) {
            $where_exp = '`' . $this->pkey . '`="' . $rule . '"';
        } else {
            $where_exp = $rule;
        }
        $t = '';

        foreach ($data as $k => $v) {
            $t .= '`' . $k . '`="' . $v . '",';
        }

        $t = substr($t, 0, -1);

        $this->_db->sql('update ' . $this->table . ' set ' . $t . ' where ' . $where_exp);

        return $this->_db->affected_rows();
    }

    function replace($data = 1, $pk) {
        if ($data == 1) {
            $data = array();
        }

        $rows = '';
        $values = '';
        foreach ($data as $k => $v) {
            $rows .= '`' . $k . '`,';
            $values .= '"' . $v . '",';
        }
        $values = substr($values, 0, -1);
        $rows = substr($rows, 0, -1);
        if ($this->_db->sql('replace into ' . $this->table . ' (`' . $this->pkey . '`,' . $rows . ') values ("' . $pk . '",' . $values . ')')) {
            return $pk;
        } else {
            return 0;
        }
    }

    function setFkey($value) {
        $this->fkey_value = $value;
    }

    function store(&$form) {
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

    function moveUp($node_key) {
        if ($this->order == '') {
            return 0;
        }
        $order = str_replace('desc', '', $this->order);
//$order=$this->order;

        if ($this->fkey != '') {
            $this->_db->sql('select `' . $this->fkey . '` from ' . $this->table . ' where `' . $this->pkey . '`="' . $node_key . '"');
            $t = $this->_db->line();
            if ($t == 0) {
                return 0;
            }
        }
        $direct = '-3';
        if ($order != $this->order) {
            $direct = '+3';
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=' . $order . $direct . ' where `' . $this->pkey . '`="' . $node_key . '"');

        $this->_db->sql('set @a:=0');
        $where = '';
        if ($this->fkey != '') {
            $where = 'where `' . $this->fkey . '`="' . $t[$this->fkey] . '"';
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=(@a:=@a+2) ' . $where . ' order by ' . $order);

        return 1;
    }

    function moveDown($node_key) {
        if ($this->order == '') {
            return 0;
        }
        $order = str_replace('desc', '', $this->order);
//$order=$this->order;
        if ($this->fkey != '') {
            $this->_db->sql('select `' . $this->fkey . '` from ' . $this->table . ' where `' . $this->pkey . '`="' . $node_key . '"');
            $t = $this->_db->line();
            if ($t == 0) {
                return 0;
            }
        }
        $direct = '+3';
        if ($order != $this->order) {
            $direct = '-3';
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=' . $order . $direct . ' where `' . $this->pkey . '`="' . $node_key . '"');

        $this->_db->sql('set @a:=0');
        $where = '';
        if ($this->fkey != '') {
            $where = 'where `' . $this->fkey . '`="' . $t[$this->fkey] . '"';
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=(@a:=@a+2) ' . $where . ' order by ' . $order);

        return 1;
    }
}//end of class
?>