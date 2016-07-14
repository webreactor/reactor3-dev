<?php

class reactor_user extends basic_object
{
    function getList($page, $per_page, $filter = '', $fk_ugroup = 0)
    {
        $_ca =& pool_create_content_adapter($this->_pool_id);

        $all = 0;
        $where_rez = ' where 1 ';
        if ($filter != '') {
            $where_rez .= ' and login like "%' . addslashes($filter) . '%" ';
        }
        if ($fk_ugroup != 0) {
            $where_rez .= ' and fk_ugroup=' . $fk_ugroup . ' ';
        }
        $data = $this->_db->pagess('select * from ' . T_REACTOR_USER . ' ' . $where_rez . ' order by login', $page,
            $per_page, $all, $total_rows_count);

        return array(
            'all'              => $all,
            'total_rows_count' => $total_rows_count,
            'page'             => pool_get($this->_pool_id, 'name') . '_page',
            'per_page'         => $per_page,
            'now'              => $page,
            'data'             => $data,
            'fk_ugroup_enum'   => $_ca->define['fk_ugroup']['enum'],
        );
    }

    function getSelectGroup($row, $filter, $fk_ugroup = 1, $forceOne = 0, $forceFkey = 1)
    {
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

        if ($fk_ugroup != 0) {
            $this->_db->sql('select ' . $this->pkey . ', ' . $row . ' from ' . $this->table . $where_rez . ' and `fk_ugroup`="' . $fk_ugroup . '" limit 25');
        } else {
            $this->_db->sql('select ' . $this->pkey . ', ' . $row . ' from ' . $this->table . $where_rez . ' limit 25');
        }

        return $this->_db->matr($this->pkey, $row);
    }
}
