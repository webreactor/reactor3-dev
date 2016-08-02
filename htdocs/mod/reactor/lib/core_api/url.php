<?php

function urlHeaderEnc($data)
{
    return str_replace('&', '_!_', $data[0]);
}

function urlHeader($str)
{
    if (substr($str[2], 0, 11) == 'javascript:') {
        return $str[0];
    }

    if (substr($str[2], 0, 7) == 'http://') {
        return $str[0];
    }

    if (substr($str[2], 0, 7) == 'mailto:') {
        return $str[0];
    }

    if ($str[2][0] == '/') {
        return $str[0];
    }

    if ($str[2][0] == '#') {
        return $str[0];
    }

    $r = $str[1];

    $t = strpos($str[2], '?');

    if ($t !== false) {
        $str[2] = preg_replace_callback('/{[^}]+}/Uis', 'urlHeaderEnc', $str[2]);

        parse_str(substr($str[2], $t + 1), $m);

        if ($t != 0) {
            $m['show'] = substr($str[2], 0, $t);
        }

        foreach ($m as $k => $v) {
            $m[$k] = str_replace('_!_', '&', $v);
        }
    } else {
        $m = array('show' => $str[2]);
    }

    if (substr($m['show'], -1, 1) != '/') {
        $m['show'] .= '/';
    }

    if (!isset($m['lng'])) {
        $m['lng'] = '{$_reactor.language_url}';
    }

    $url = compileUrl($m);

    if ($str[3] == '"' || $str[3] == "'") {
        $r .= '"' . $url . '"';
    } else {
        $r .= '"' . $url . '"' . $str[3];
    }

    return $r;
}

function arrToUrl($a)
{
    global $_RGET;

    $r = array_merge($_RGET, $a);

    return compileUrl($r);
}

function compileUrl($data)
{
    return compileAurl($data);
}

function parseUrl($str)
{
    return parseAurl($str);
}

function parseAurl($str)
{
    global $_languages, $_site_tree, $_reactor;

    $r = array();

    $tree_i = &$_site_tree['index'];

    $_reactor['path']     = array();
    $_reactor['path'][]   = $_site_tree['nodes'][$tree_i['#key']];
    $_reactor['path_url'] = '';

    if ($str == '' || $str == 'index') {
        $_reactor['show'] = $_site_tree['nodes'][$tree_i['#key']];

        return $r;
    }

    $i = 0;

    $str = explode('/', $str);

    if (isset($_languages[$str[0]])) {
        $r['lng'] = $str[0];

        $i = 1;
    }

    $j = 0;

    $c = count($str);

    for (; $i < $c; $i++) {
        if (isset($tree_i[$str[$i]])) {
            $j = 0;

            $tree_i = &$tree_i[$str[$i]];

            $_reactor['path_url'] .= $str[$i] . '/';

            /**
             * did #key points on node with minimal parameters set or what?
             */
            $_reactor['path'][] = $_site_tree['nodes'][$tree_i['#key']];
        } else {
            $param_pool = &$_site_tree['param']['/' . $_reactor['path_url']];

            $param = $param_pool[$param_pool['max']];
            $cnt   = $param_pool['max'];

            if ($j >= $cnt) {
                $_reactor['show'] = $_site_tree['nodes'][$_site_tree['index']['404']['#key']];

                stop('404');
            }

            $r[$param[$j]] = $str[$i];

            if ($param[$j][0] == '_') {
                $r[$param[$j]] = array();

                for (; $i < $c; $i++) {
                    if ($str[$i][0] != '_') {
                        $r[$param[$j]][] = $str[$i];
                    } else {
//                        $str[$i] = substr($str[$i], 1);
//                        $i--;

                        break;
                    }
                }
            }
            $j++;
        }
    }

    $r['show'] = $_reactor['path_url'];

    $param_pool = &$_site_tree['param']['/' . $_reactor['path_url']];

    while (!isset($param_pool[$j]) && $j < $param_pool['max']) {
        $j++;
    }

    if (!isset($param_pool[$j])) {
        $j = $param_pool['min'];
    }

    $_reactor['show']   = $_site_tree['nodes'][$param_pool[$j]['key']];
    $_reactor['path'][] = $_reactor['show'];

    return $r;
}

function compileAurl($data)
{
    global $_reactor, $_site_tree;

    $url = SITE_URL;

    if (isset($data['lng'])) {
        if ($data['lng'] == '{$_reactor.language_url}') {
            $url .= $data['lng'];
        } else {
            $url .= $data['lng'] . '/';
        }

        unset($data['lng']);
    } else {
        $url .= $_reactor['language_url'];
    }

    if (isset($data['show'])) {
        if ($data['show'] == 'index/') {
            $data['show'] = '';
        }

        $data['show'] = str_replace('%2F', '/', $data['show']);

        $url .= $data['show'];

        $show = '/' . $data['show'];

        unset($data['show']);
    } else {
        $url .= $_reactor['path_url'];

        $show = '/' . $_reactor['path_url'];
    }

    if (!isset($_site_tree['param'][$show])) {
        reactor_error('undefined show [' . $show . '] in site_tree');
    }

    $param_pool = &$_site_tree['param'][$show];

    $param = $param_pool[$param_pool['max']];

    foreach ($param as $item) {
        if (isset($data[$item])) {
            if ($item[0] == '_' && is_array($data[$item])) {
                $url .= str_replace('<', '%3C', implode('/', $data[$item])) . '/_';
            } else {
                $url .= rawurlencode($data[$item]) . '/';
            }

            unset($data[$item]);
        }
    }

    $url .= '?';
    
    foreach ($data as $k => $v) {
        /**
         * handle array in GET param
         */
        if (is_array($v)) {
            foreach ($data[$k] as $z => $a) {
                if (is_array($a)) {
                    foreach ($a as $t => $w) {
                        if ($w . '' != '') {
                            $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D%5B' . rawurlencode(
                                    $t
                                ) . '%5D=' . rawurlencode($w) . '&';
                        } else {
                            $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D%5B' . rawurlencode($t) . '%5D&';
                        }
                    }
                } elseif ($a . '' != '') {
                    $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D=' . rawurlencode($a) . '&';
                } else {
                    $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D' . '&';
                }
            }
        } elseif ($v . '' != '') {
            $url .= rawurlencode($k) . '=' . rawurlencode($v) . '&';
        } else {
            $url .= rawurlencode($k) . '&';
        }
    }
    $url = substr($url, 0, -1);

//    $url .= rawurlencode_array($data);

    return $url;
}

function compileSurl($data)
{
    $url = SITE_URL . 'index.php?';

    foreach ($data as $k => $v) {
        if ($v != '') {
            $url .= $k . '=' . $v . '&';
        }
    }

    return substr($url, 0, -1);
}
