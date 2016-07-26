<?php

namespace reactor\base_type;

class ca_flags extends base_type
{
    public function fromForm(&$value)
    {
        $value = serialize($value);

        return 1;
    }

    public function toForm($value)
    {
        if ($value == '') {
            $value = array();
        } else {
            $value = unserialize($value);
        }

        return $value;
    }

    public function toHTML($value)
    {
        if ($value == '') {
            $value = array();
        } else {
            $value = unserialize($value);
        }

        return $value;
    }
}
