<?php

namespace reactor\base_type;

class ca_bbcode extends base_type
{
    private $bbcode_in
        = array(
            '/\[b\]/'              => '<b>',
            '/\[\/b\]/'            => '</b>',
            '/\[i\]/'              => '<i>',
            '/\[\/i\]/'            => '</i>',
            '/\[u\]/'              => '<u>',
            '/\[\/u\]/'            => '</u>',
            '/\[color ([#\w]+)\]/' => '<font color="\1">',
            '/\[\/color\]/'        => '</font>',
        );
    private $bbcode_out
        = array(
            '/<b>/'                     => '[b]',
            '/<\/b>/'                   => '[/b]',
            '/<i>/'                     => '[i]',
            '/<\/i>/'                   => '[/i]',
            '/<u>/'                     => '[i]',
            '/<\/u>/'                   => '[/u]',
            '/<font color="([#\w]+)">/' => '[color \1]',
            '/<\/font>/'                => '[/color]',
        );

    public function fromForm(&$value)
    {
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = str_replace("\n", '<br>', $value);

        $value = preg_replace(array_keys($this->bbcode_in), array_values($this->bbcode_in), $value);

        return 1;
    }

    public function toForm($value)
    {
        $value = preg_replace(array_keys($this->bbcode_out), array_values($this->bbcode_out), $value);
        $value = str_replace('<br>', "\n", $value);

        return $value;
    }
}
