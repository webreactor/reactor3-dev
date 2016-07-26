<?php

namespace reactor\base_type;

class ca_html extends base_type
{
    public function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');

        $t = trim(str_replace('&nbsp;', '', strip_tags($value, '<img><object><iframe>')));

        if ($t == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

//        if (!isset($prop['no-br'])) {
//            $value = str_replace("\n", '<br />', $value);
//        }

        if (!isset($prop['typo'])) {
            $prop['typo'] = array();
        }
        if (!isset($prop['no-typo'])) {
            $value = typo($value, $prop['typo']);
        }

        return 1;
    }

    public function toForm($value)
    {
        $prop = $this->get('base_type_param');

//        if (!isset($prop['no-br'])) {
//            $value = str_replace('<br />', "\n", $value);
//        }

        return htmlspecialchars($value, ENT_QUOTES);
    }
}
