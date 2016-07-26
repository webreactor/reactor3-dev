<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\config_write;

class reactor_module extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);

        config_write::interfacesCompile();

        return $t;
    }
}
