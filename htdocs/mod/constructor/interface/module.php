<?php

require_once LIB_DIR . 'config_write.php';

class reactor_module extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);
        
        interfacesCompile();
        
        return $t;
    }
}
