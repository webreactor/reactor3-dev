<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\config_write;

class reactor_table extends basic_object
{
    function getList($page, $per_page, $where = '')
    {
        $query = $this->_db->select(T_REACTOR_MODULE);

        $modules = $query->matr('pk_module', 'name');

        $r = basic_object::getList($page, $per_page, $where);

        foreach ($r['data'] as $k => $v) {
            $r['data'][$k]['name'] = 'T_' . strtoupper($modules[$v['fk_module']]) . '_' . strtoupper($v['name']);
        }

        return $r;
    }

    function store($form)
    {
        $r = basic_object::store($form);

        config_write::tablesCompile();

        return $r;
    }
}
