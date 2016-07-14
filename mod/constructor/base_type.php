<?php
include_once LIB_DIR . 'config_write.php';

class reactor_base_type extends basic_object
{
    function store(&$form)
    {
        $r = basic_object::store($form);
        baseTypeCompile();

        return $r;
    }
}
