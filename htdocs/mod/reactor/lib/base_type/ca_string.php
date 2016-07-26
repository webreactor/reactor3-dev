<?php

namespace reactor\base_type;

class ca_string extends ca_text
{
    public function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);

        if (strpos($value, "<br />") !== false) {
            $value = $this->get('default');

            return 0;
        }

        return $r;
    }
}
