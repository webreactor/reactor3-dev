<?php

namespace reactor\base_type;

class ca_date_time extends base_type
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
        $value = str_replace(array(' ', ',', '-', '/', ':'), '.', $value);

        $value = explode('.', $value);

        if (count($value) < 3) {
            return false;
        }

        $value[] = 0;
        $value[] = 0;
        $value[] = 0;

        return mktime($value[3], $value[4], $value[5], $value[1], $value[0], $value[2]);
    }

    public function toForm($value)
    {
        if ($value == 0) {
            return '--';
        }
        
        if (@!$value = date('d.m.Y H:i:s', $value)) {
            $value = date('d.m.Y H:i:s');
        }

        return $value;
    }

    public function toHTML($value)
    {
        if ($value == 0) {
            return '--';
        }

        return date('d.m.Y H:i:s', $value);
    }
}
