<?php

namespace reactor\base_type;

class base_type
{
    public $_pool_id;
    public $item;

    public function __construct($item)
    {
        $this->_pool_id = 0;

        $this->item = $item;
    }

    public function fromForm(&$value)
    {
        return 1;
    }

    public function toForm($value)
    {
        return $value;
    }

    public function toHTML($value)
    {
        return $value;
    }

    public function toDB($value)
    {
        return $value;
    }

    public function get($name)
    {
        $ca = &pool_get($this->_pool_id, 'object');

        return $ca->define[$this->item][$name];
    }

    public function set($name, $value)
    {
        $ca              = &pool_get($this->_pool_id, 'object');
        $ca->data[$name] = $value;
    }
}
