<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\config_write;

class reactor_interface_define extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);

        config_write::interfacesCompile();

        return $t;
    }

    function back()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $t = $query->line();

        return $t['fk_module'];
    }
}
