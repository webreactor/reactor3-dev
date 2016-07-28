<?php

require __DIR__ . '/../ext/typo.php';

function strToUrl($str)
{
    $_str_translit = array(
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'y',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ы' => 'y',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'А' => 'a',
        'Б' => 'b',
        'В' => 'v',
        'Г' => 'g',
        'Д' => 'd',
        'Е' => 'e',
        'Ё' => 'e',
        'Ж' => 'zh',
        'З' => 'z',
        'И' => 'i',
        'Й' => 'y',
        'К' => 'k',
        'Л' => 'l',
        'М' => 'm',
        'Н' => 'n',
        'О' => 'o',
        'П' => 'p',
        'Р' => 'r',
        'С' => 's',
        'Т' => 't',
        'У' => 'y',
        'Ф' => 'f',
        'Х' => 'h',
        'Ц' => 'ts',
        'Ч' => 'ch',
        'Ш' => 'sh',
        'Щ' => 'sch',
        'Ы' => 'y',
        'Э' => 'e',
        'Ю' => 'yu',
        'Я' => 'ya',
        'ь' => '',
        'ъ' => '',
        'Ь' => '',
        'Ъ' => '',
    );

    $str = str_replace(' ', '_', html_entity_decode($str));
    $str = preg_replace('/[^a-z0-9_\-]/', '', strtolower(strtr($str, $_str_translit)));

    return $str;
}

/**
 * determine if a string can represent a number in hexadecimal
 *
 * @param string $hex
 *
 * @return boolean true if the string is a hex, otherwise false
 */
function is_hex($hex)
{
    /**
     * regex is for weenies
     */
    $hex = strtolower(trim(ltrim($hex, "0")));

    if (empty($hex)) {
        $hex = 0;
    }

    $dec = hexdec($hex);

    return $hex == dechex($dec);
}
