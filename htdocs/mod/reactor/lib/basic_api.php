<?php

require __DIR__ . '/basic_api/global.php';
require __DIR__ . '/basic_api/http_chunked_decode.php';
require __DIR__ . '/basic_api/shutdown.php';
require __DIR__ . '/basic_api/string.php';
require __DIR__ . '/basic_api/time.php';

function arrayKeyFilter(&$data, $filter)
{
    $filter = array_flip($filter);
    $rez    = array_intersect_key($data, $filter);
    $data   = array_diff_key($data, $filter);

    return $rez;
}

function arrayMapRecursive($handle, $data)
{
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = arrayMapRecursive($handle, $v);
        }
    } else {
        $data = $handle($data);
    }

    return $data;
}

function html_entity_decode_full($txt)
{
    return html_entity_decode($txt, ENT_QUOTES);
}

function arrayColumnSplit($data, $column_count)
{
    $result  = array();
    $portion = ceil(count($data) / $column_count);
    if (!empty($data)) {
        for ($i = 0; $i < $column_count; $i++) {
            $result[$i] = array_slice($data, $i * $portion, $portion, true);
        }
    }

    return $result;
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row) {
                $tmp[$key] = $row[$field];
            }
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);

    return array_pop($args);
}

function get_request($host, $uri)
{
    $data = array();
    $h    = fsockopen($host, 80);
    if ($h) {
        $req = 'GET ' . $uri . ' HTTP/1.1' . "\r\n" .
            'Host: ' . $host . "\r\n" .
            'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
            'Connection: close' . "\r\n" .
            "\r\n";
        $ans = '';
        fwrite($h, $req);
        while (!feof($h)) {
            $ans .= fgets($h, 1024);
        }
        fclose($h);

        $ans = explode("\r\n\r\n", $ans);
        if (stripos($ans[0], 'chunked') !== false) {
            $ans[1] = http_chunked_decode($ans[1]);
        }

        $data = unserialize($ans[1]);
    }

    return $data;
}
