<?php

namespace reactor\base_type;

class ca_url_key extends base_type
{
    public function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');

        $ca = &pool_get($this->_pool_id, 'object');

        if (is_array($prop['to-url'])) {
            foreach ($prop['to-url'] as $t) {
                $value[] = $ca->data[$t];
            }

            $value = implode('_', $value);
        } else {
            $value = $ca->data[$prop['to-url']];
        }

        $value = strToUrl($value);

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
