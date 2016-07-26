<?php

namespace reactor\base_type;

class ca_enum extends base_type
{
    public function fromForm(&$value)
    {
        if ($value == '') {
            $value = $this->get('default');

            if ($this->get('necessary') == 1) {
                return 0;
            }

            return 1;
        }

        if (substr($value, 0, 5) != "//php") {
            $rez = array();
            $t   = explode("\n", $value);
            $tt  = 0;

            foreach ($t as $item) {
                if (($t = strpos($item, '=')) !== false) {
                    $key       = trim(substr($item, 0, $t));
                    $rez[$key] = trim(substr($item, $t + 1));
                } else {
                    $item       = trim($item);
                    $rez[$item] = $item;
                }
            }

            $value = '$data=' . var_export($rez, true) . ';';
        }

        return 1;
    }

    public function toForm($value)
    {
        $rez  = '';
        $data = array();

        if (substr($value, 0, 5) != "//php") {
            eval($value);

            foreach ($data as $k => $v) {
                if ($k == $v) {
                    $rez .= $v . "\n";
                } else {
                    $rez .= $k . ' = ' . $v . "\n";
                }
            }
            
            $value = $rez;
        }

        return $value;
    }
}
