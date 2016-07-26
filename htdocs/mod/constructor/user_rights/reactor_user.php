<?php

namespace mod\constructor\user_rights;

use reactor\basic_object;

class reactor_user extends basic_object
{
    function getList($page, $per_page, $filter = '', $fk_ugroup = 0)
    {
        $_ca = &pool_create_content_adapter($this->_pool_id);

        $where_arr        = array(1);
        $where_parameters = array();

        if ($filter != '') {
            $where_arr[]                = 'login LIKE "%:login%"';
            $where_parameters[':login'] = addslashes($filter);
        }

        if ($fk_ugroup != 0) {
            $where_arr[]                    = 'fk_ugroup = :fk_ugroup';
            $where_parameters[':fk_ugroup'] = $fk_ugroup;
        }

        $pages = $this->_db->pages(
            sprintf(
                'SELECT *
                FROM %s
                WHERE %s
                ORDER BY login',
                T_REACTOR_USER,
                implode(' AND ', $where_arr)
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
            $where_rez[] = '`' . $row . '` LIKE "%' . $word . '%"';
        }

        $where_rez = ' WHERE (' . implode(' AND ', $where_rez) . ')';

        if ($this->fkey != '' && $forceFkey == 1) {
            $where_rez .= ' AND `' . $this->fkey . '` = "' . $this->fkey_value . '"';
        }

        if ($fk_ugroup != 0) {
            $query = $this->_db->sql(
                'SELECT
                    ' . $this->pkey . ',
                    ' . $row . '
                FROM ' . $this->table . '
                ' . $where_rez . '
                AND `fk_ugroup` = :fk_ugroup
                LIMIT 25',
                array(':fk_ugroup' => $fk_ugroup)
            );
        } else {
            $query = $this->_db->sql(
                'SELECT
                    ' . $this->pkey . ',
                    ' . $row . '
                FROM ' . $this->table . '
                ' . $where_rez . '
                LIMIT 25'
            );
        }

        return $query->matr($this->pkey, $row);
    }
}
