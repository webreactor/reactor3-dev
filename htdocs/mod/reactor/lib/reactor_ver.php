<?php

function reactor_ver()
{
    $dirs = array(
        'reactor/lib/',
        'reactor/lib/Gekkon/',
        'reactor/lib/db/',
        'constructor/lib/',
    );
    $rez  = '';
    foreach ($dirs as $c_dir) {
        if (is_dir(MOD_DIR . $c_dir)) {
            if ($dh = opendir(MOD_DIR . $c_dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir(MOD_DIR . $c_dir . $file)) {
                        if ($ver = reactor_item_ver(MOD_DIR . $c_dir . $file)) {
                            $rez .= $c_dir . $file . ' = ' . $ver . "\n";
                        }
                    }
                }
                closedir($dh);
            }
        }
    }

    return trim($rez);
}

function reactor_item_ver($file)
{
    $f   = fopen($file, 'r');
    $str = fread($f, 100);
    fclose($f);
    if (preg_match('/version\s+(\d+(\.\d+)*)/uis', $str, $match)) {
        return $match[1];
    }

    return '';
}

function reactor_ver_depend($check, &$log)
{
    if ($check == '') {
        return 1;
    }
    preg_match_all('/\s*([^=\s]+)\s*=\s*(\d+(\.\d+)*)/is', $check, $t);
    $check_match = array();
    foreach ($t[1] as $k => $item) {
        $check_match[$t[1][$k]] = $t[2][$k];
    }

    preg_match_all('/\s*([^=\s]+)\s*=\s*(\d+(\.\d+)*)/is', reactor_ver(), $t);

    $match = array();
    foreach ($t[1] as $k => $item) {
        $match[$t[1][$k]] = $t[2][$k];
    }

    $r   = 1;
    $log = array();
    foreach ($check_match as $k => $item) {
        $log[$k]['need'] = $item;
        if (isset($match[$k])) {
            $log[$k]['now'] = $match[$k];
            if (cleen_ver($match[$k]) == cleen_ver($item)) {
                $log[$k]['status'] = 'OK';
            } else {
                $log[$k]['status'] = 'Error';
                $r                 = 0;
            }
        } else {
            $log[$k]['status'] = 'Error';
            $r                 = 0;
        }
    }

    return $r;
}

function cleen_ver($str)
{
    $t   = explode('.', $str);
    $str = $t[0];
    if (isset($t[1])) {
        $str .= $t[1];
    }

    return $str;
}
