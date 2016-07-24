<?php

require_once LIB_DIR . 'config_write.php';

class reactor_config extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);

        configCompile();

        return $t;
    }
}
