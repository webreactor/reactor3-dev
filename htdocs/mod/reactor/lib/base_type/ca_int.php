<?php

namespace reactor\base_type;

class ca_int extends ca_text
{
    public function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);

        if (!is_numeric($value)) {
            $value = $this->get('default');

            return 0;
        }

        return $r;
    }
}
