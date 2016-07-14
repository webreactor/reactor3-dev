<?php

include_once LIB_DIR . 'config_write.php';

class reactor_interface_define extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);
        
        interfacesCompile();
        
        return $t;
    }
    
    function back()
    {
        global $_db;
        $_db->sql('select fk_module from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $t = $_db->line();
        
        return $t['fk_module'];
    }
}
