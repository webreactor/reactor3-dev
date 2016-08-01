<?php

function getStrChars($str)
{
    $str = preg_replace('/[^\w\pP\pL\s\$]/uis', ' ', $str);

    return htmlspecialchars($str, ENT_QUOTES);
}

function inputGetStr($name, $def = false, $stop = '')
{
    global $_RGET, $_SGET;

    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }

    if (isset($_RGET[$name])) {
        $test = trim($_RGET[$name]);
    } else {
        $test = '';
    }

    if ($test == '') {
        if ($def !== false) {
            $test = $def;
        } else {
            stop($stop);
        }
    }

    $_SGET[$name] = getStrChars($test);

    return $_SGET[$name];
}

function inputGetNum($name, $def = '', $stop = '')
{
    global $_RGET, $_SGET;

    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }

    if (isset($_RGET[$name])) {
        $test = trim($_RGET[$name]);
    } else {
        $test = '';
    }

    if ($test == '') {
        if ($def !== false) {
            $test = $def;
        } else {
            stop($stop);
        }
    }

    if (!is_numeric($test)) {
        stop($stop);
    }

    return $_SGET[$name] = intval($test);
}

function inputGetPath($name, $def = array())
{
    global $_RGET, $_SGET;

    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }

    if (isset($_RGET[$name])) {
        $test = $_RGET[$name];
    } else {
        $test = $def;
    }

    return $_SGET[$name] = array_map('getStrChars', $test);
}
