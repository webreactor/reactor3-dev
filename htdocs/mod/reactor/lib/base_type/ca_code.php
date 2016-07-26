<?php

namespace reactor\base_type;

class ca_code extends base_type
{
    public function fromForm(&$value)
    {
        if ($value == '') {
            $value = $this->get('default');
            
            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    public function toForm($value)
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}
