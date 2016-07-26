<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\config_write;

class reactor_config extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);

        config_write::configCompile();

        return $t;
    }
}
