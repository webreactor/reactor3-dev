<?php

namespace mod\constructor;

use reactor\basic_object;
use reactor\basic_tree;
use reactor\config_write;

class site_tree extends basic_tree
{
    function store($form)
    {
        $r = basic_object::store($form);

        config_write::siteTreeCompile();

        return $r;
    }
}
