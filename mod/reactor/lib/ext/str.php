<?php
//----------------------------------------------------------------------------------------
//string functions

function generatePwd($length)
{
    $vowel = array('a', 'e', 'i', 'o', 'u');
    $other = array_diff(range('a', 'z'), $vowel);
    $letters = array($vowel, $other);
    $ret = '';
    for ($i = 0; $i < $length; $i++) {
        $ret .= $letters[$i % 2][array_rand($letters[$i % 2])];
    }

    return $ret;
}

function arrayToCSV($ar)
{

    $r = '';
    foreach ($ar as $k => $item) {
        $str = str_replace("\n", " ", $ar[$k]);
        $str = str_replace("\r", "", $str);
        $ar[$k] = '"' . str_replace('"', '""', $str) . '"';
    }

    return implode(';', $ar) . "\n";
}

function cp1251toUTF8($str)
{
    return iconv('cp1251', 'UTF-8', $str);
}

function UTF8tocp1251($str)
{
    return mb_convert_encoding($str, 'cp1251', 'UTF-8');//iconv('cp1251','cp1251',$str);
}

function xlsBOF()
{
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);

    return;
}

function xlsEOF()
{
    echo pack("ss", 0x0A, 0x00);

    return;
}

function xlsWriteNumber($Row, $Col, $Value)
{
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);

    return;
}

function xlsWriteLabel($Row, $Col, $Value)
{
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;

    return;
}
