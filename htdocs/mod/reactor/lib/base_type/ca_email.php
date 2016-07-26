<?php

namespace reactor\base_type;

class ca_email extends ca_text
{
    public function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);

        if ($value == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }

            return 1;
        }

        if (!preg_match('/^[a-z0-9]{1}[a-z0-9._-]*\@[a-z0-9._-]+\.[a-z]{1,5}$/ui', $value, $match)) {
            $value = $this->get('default');

            return 0;
        }

        $value = $match[0];

        return $r;
    }
}
