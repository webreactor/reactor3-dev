<?php

//vers ion 1
function keys_implode(&$values, &$keys)
{
    $where_rez = array();
    foreach ($keys as $key) {
        if (isset($values[$key])) {
            $where_rez[$key] = '`' . $key . '`="' . $values[$key] . '"';
        }
    }
    $values = $where_rez;
    
    return ' ' . implode(' and ', $where_rez) . ' ';
}

class ex_object
{
    var $table;
    var $pkeys;
    var $pkeys_value;
    var $fkeys;
    var $fkeys_value;
    var $order;
    var $_db;
    var $_pool_id;
    
    function ex_object($table = '', $pkey = '', $order = '')
    {
        $this->table = '`' . $table . '`';
        
        if ($pkey != '') {
            $this->pkeys = array($pkey);
        } else {
            $this->pkeys = array();
        }
        
        $this->pkeys_value = array();
        $this->fkeys       = array();
        $this->fkeys_value = array();
        $this->order       = $order;
        $this->_pool_id    = 'none';
        $this->onRestore();
    }
    
    function onRestore()
    {
        global $_db;
        $this->_db =& $_db;
    }
    
    function configure($table, $order = '', $fk_value = 0)
    {
        $_container  =& pool_get($this->_pool_id);
        $this->pkeys = array($_container['pkey']);
        $this->fkeys = $_container['configurators'];
        $this->table = '`' . $table . '`';
        $this->order = $order;
        if ($fk_value != 0) {
            $this->fkeys_value = $fk_value;
        }
    }
    
    function getList($page, $per_page, $where = '')
    {
        $where_arr = array(keys_implode($this->fkeys_value, $this->fkeys));
        $where_parameters = array();

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
                T_REACTOR_USER,
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
    
    function getOne($pk)
    {
        $query = $this->_db->sql('select * from ' . $this->table . ' where ' . keys_implode($pk, $this->pkeys));
        $this->pkey_value = $pk;
        
        return $query->line();
    }
    
    function getPage($where = '')
    {
        $where_rez = keys_implode($this->fkeys_value, $this->fkeys);
        
        if ($where != '') {
            $where_rez .= $where;
        }
        if ($this->order != '') {
            $where_rez .= ' order by ' . $this->order;
        }

        $query = $this->_db->sql('select * from ' . $this->table . ' ' . $where_rez);
        
        return $query->matr();
    }
    
    function delete($pk, $isStream = 0)
    {
        
        if ($isStream == 0) {
            if ($pk == 0) {
                $where_rez = keys_implode($this->pkeys_value, $this->pkeys);
            } else {
                $where_rez = keys_implode($pk, $this->pkeys);
            }
            if ($this->order != '') {
                $where_rez .= ' order by ' . $this->order;
            }
        } else {
            $where_rez = $pk;
        }

        $query = $this->_db->sql('delete from ' . $this->table . ' where ' . $where_rez);
        
        return $query->count();
    }
    
    function insert($data)
    {
        $rows   = array();
        $values = array();
        
        if ($this->_db->sql(
            'insert into ' . $this->table . ' (`' . implode(
                '`, `',
                array_keys($data)
            ) . '`) values ("' . implode('", "', $data) . '")'
        )
        ) {
            $pk = array();
            foreach ($this->pkey as $item) {
                if (isset($data[$item])) {
                    $pk[$item] = $data[$item];
                }
            }
            
            if (count($pk) > 0) {
                return $pk;
            } else {
                return $this->_db->lastId();
            }
        } else {
            return 0;
        }
    }
    
    function update($data, $pk = 0, $isStream = 0)
    {
        if ($isStream == 0) {
            if ($pk == 0) {
                $where_rez = keys_implode($this->pkeys_value, $this->pkeys);
            } else {
                $where_rez = keys_implode($pk, $this->pkeys);
            }
            if ($this->order != '') {
                $where_rez .= ' order by ' . $this->order;
            }
        } else {
            $where_rez = $pk;
        }
        
        $t = '';
        foreach ($data as $k => $v) {
            $t .= '`' . $k . '`="' . $v . '",';
        }
        $t = substr($t, 0, -1);
        $query = $this->_db->sql('update ' . $this->table . ' set ' . $t . ' where ' . $where_rez);
        
        return $query->count();
    }
    
    function replace($data, $pk = 0)
    {
        if ($pk == 0) {
            $pk = keys_implode($this->pkeys_value, $this->pkeys);
        }
        $rows   = array_keys($data) + array_keys($pk) + array_keys($this->fkeys_values);
        $values = $data + $pk + $this->fkeys_values;
        if ($this->_db->sql(
            'replace into ' . $this->table . ' (`' . implode(
                '`, `',
                $rows
            ) . '`) values ("' . implode('", "', $rows) . '")'
        )
        ) {
            return $pk;
        } else {
            return 0;
        }
    }
    
    function store($form)
    {
        $data = $form->toDb();
        
        $data += $this->fkeys_value;
        
        if (count($this->pkeys_value) == 0) {
            $this->pkey_value = $this->insert($data);
        } else {
            $this->update($data, $this->pkey_value);
        }
        
        return $this->pkey_value;
    }
    
    function moveUp($node_key)
    {
        if ($this->order == '') {
            return 0;
        }
        $order = str_replace('desc', '', $this->order);
        
        if (count($this->fkeys) > 0) {
            $query = $this->_db->sql(
                'select `' . $this->fkey . '` from ' . $this->table . ' where ' . keys_implode(
                    $pk,
                    $this->pkeys
                )
            );
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
            'update ' . $this->table . ' set ' . $order . '=' . $order . $direct . ' where ' . keys_implode(
                $pk,
                $this->pkeys
            )
        );
        
        $this->_db->sql('set @a:=0');
        $where = '';
        if (count($this->fkeys) > 0) {
            $where = 'where ' . keys_implode($t, $this->fkeys);
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=(@a:=@a+2) ' . $where . ' order by ' . $order);
        
        return 1;
    }
    
    function moveDown($pk)
    {
        if ($this->order == '') {
            return 0;
        }
        $order = str_replace('desc', '', $this->order);
        
        if (count($this->fkeys) > 0) {
            $query = $this->_db->sql(
                'select `' . $this->fkey . '` from ' . $this->table . ' where ' . keys_implode(
                    $pk,
                    $this->pkeys
                )
            );
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
            'update ' . $this->table . ' set ' . $order . '=' . $order . $direct . ' where ' . keys_implode(
                $pk,
                $this->pkeys
            )
        );
        
        $this->_db->sql('set @a:=0');
        $where = '';
        if (count($this->fkeys) > 0) {
            $where = 'where ' . keys_implode($t, $this->fkeys);
        }
        $this->_db->sql('update ' . $this->table . ' set ' . $order . '=(@a:=@a+2) ' . $where . ' order by ' . $order);
        
        return 1;
    }
}
