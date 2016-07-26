<?php

namespace reactor\base_type;

function ca_text_url_handler($m)
{
    $url  = $m[1];
    $name = $url;
    if (strlen($name) > 70) {
        $t = strpos($name, '/', 8) + 1;
        if ($t === false) {
            $t = 50;
        }
        $rest = substr($name, $t);
        $name = substr($name, 0, $t) . '...';
        if (preg_match_all('/[^a-zA-Z0-9]+[a-zA-Z0-9]+/u', $rest, $rest_a)) {
            $rest_a    = array_reverse($rest_a[0]);
            $rest_str  = array();
            $restr_cnt = 0;
            foreach ($rest_a as $item) {
                $t = strlen($item);
                if ($restr_cnt + $t > 60) {
                    if ($restr_cnt < 15) {
                        $rest_str[] = substr($item, $restr_cnt - 60);
                    }
                    break;
                }
                $restr_cnt += $t;
                $rest_str[] = $item;
            }
            $name .= implode('', array_reverse($rest_str));
        } else {
            $name .= substr($rest, $t - 70);
        }
    }

    return '<a href="' . $url . '">' . $name . '</a>';
}

class ca_text extends base_type
{
    public function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');

        if (!isset($prop['typo'])) {
            $prop['typo'] = array();
        }

        if (!isset($prop['no-typo'])) {
            $value = typo($value, $prop['typo']);
        }

        $value = htmlspecialchars($value, ENT_QUOTES);

        if (!isset($prop['no-br'])) {
            $value = str_replace(array("\r", "\n"), array('', '<br />'), $value);
        }

//        if (!isset($prop['no-links'])) {
//            $value = trim(
//                preg_replace_callback(
//                    '/(http:\/\/[^\s<]+[^\s\.,\!\?:;)<\]])/u',
//                    'ca_text_url_handler',
//                    ' ' . $value . ' '
//                )
//            );
//        }

        return 1;
    }

    public function toForm($value)
    {
        $prop = $this->get('base_type_param');

        if (!isset($prop['no-br'])) {
            $value = str_replace('<br />', "\n", $value);
        }
        
        if (!isset($prop['no-links'])) {
            $value = preg_replace('/<a href="([^"]+)">[^<]+<\/a>/u', '\1', $value);
        }

        return $value;
    }
}
