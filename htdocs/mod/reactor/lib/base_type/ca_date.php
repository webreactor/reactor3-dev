<?php

namespace reactor\base_type;

class ca_date extends base_type
{
    public function fromForm(&$value)
    {
        if ($value == '--' || $value == '-') {
            $value = 0;

            return 1;
        }
        if (!$value = $this->parseDate($value)) {
            $value = $this->get('default');

            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    public function parseDate($value)
    {
        $value = str_replace(array(' ', ',', '-', '/'), '.', $value);
        $value = explode('.', $value);
        if (count($value) < 3) {
            return false;
        }

        return mktime(0, 0, 0, $value[1], $value[0], $value[2]);
    }

    public function toForm($value)
    {
        if ($value == 0) {
            return '--';
        }
        if (@!$value = date('d.m.Y', $value)) {
            $value = date('d.m.Y');
        }

        return $value;
    }

    public function toHTML($value)
    {
        if ($value == 0) {
            return '--';
        }

        return tsToDate($value);
    }
}
