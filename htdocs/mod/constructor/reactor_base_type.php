<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\config_write;

class reactor_base_type extends basic_object
{
    function store($form)
    {
        $r = basic_object::store($form);

        config_write::baseTypeCompile();

        return $r;
    }
}
