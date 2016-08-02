<?php

function initRequest()
{
    global $_languages, $_reactor;

    header('Content-Type: text/html; charset=utf-8');

    if (get_magic_quotes_gpc()) {
        $_GET = arrayMapRecursive('stripslashes', $_GET);
    }

    setlocale(LC_CTYPE, $_languages[$_reactor['language']] . '.UTF8');

    mb_internal_encoding('UTF-8');
}

function initRequestIndex()
{
    $url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    $url = str_replace('//', '/', $url);
    $url = urldecode($url);

    if (substr($url, -1) == '/') {
        $url = substr($url, 1, -1);
    }

    if (substr($url, -5) == '.html') {
        $url = substr($url, 1, -5);
    }

    $_RGET = $_GET + parseUrl($url);

    if (isset($_RGET['lng'])) {
        $_reactor['language']     = $_RGET['lng'];
        $_reactor['language_url'] = $_RGET['lng'] . '/';
    }

    if (isset($_RGET['_ref'])) {
        $_SERVER['HTTP_REFERER'] = $_RGET['_ref'];
    }

//    global $_reactor;
//
//    $mtime = $_reactor['show']['modified'];
//
//    if ($mtime != 'none') {
//        httpModified($mtime);
//    }

    return $_RGET;
}

function httpModified($mtime)
{
    global $_log;

    if (headers_sent()) {
        return 0;
    }

    $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

    header('Cache-Control: must-revalidate');
    header('Last-Modified: ' . $gmdate_mod);

    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        if ($gmdate_mod == $_SERVER['HTTP_IF_MODIFIED_SINCE']) {
            header('HTTP/1.0 304 Not Modified');

            $_log .= ' chache OK ';

            die();
        }
    }

    return 1;
}
