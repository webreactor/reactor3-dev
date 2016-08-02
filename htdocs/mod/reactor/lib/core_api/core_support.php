<?php

use reactor\config_write;

function initModule($mod_name)
{
    global $_reactor, $Gekkon;

    reactor_trace('initModule ' . $mod_name);

    array_push($_reactor['module']['stack'], $_reactor['module']['name']);

    $_reactor['module']['name'] = $mod_name;
    $_reactor['module']['url']  = MOD_URL . $mod_name . '/';
    $_reactor['module']['dir']  = MOD_DIR . $mod_name . '/';

    $Gekkon->template_path = $mod_name . '/tpl/';
}

function uninitModule()
{
    global $_reactor, $Gekkon;

    reactor_trace('uninitModule ' . $_reactor['module']['name']);

    $mod_name = array_pop($_reactor['module']['stack']);

    $_reactor['module']['name'] = $mod_name;
    $_reactor['module']['url']  = MOD_URL . $mod_name . '/';
    $_reactor['module']['dir']  = MOD_DIR . $mod_name . '/';

    $Gekkon->template_path = $mod_name . '/tpl/';

    return $mod_name;
}

function reactor_ini_set($module, $name, $value)
{
    if (constant(strtoupper($module . '_' . $name)) == $value) {
        return;
    }

    global $_db;

    $_db->sql(
        'UPDATE
            ' . T_REACTOR_CONFIG . ' c,
            ' . T_REACTOR_MODULE . ' m
        SET c.`value` = "' . $value . '"
        WHERE c.fk_module = m.pk_module
        AND m.`name` = "' . $module . '"
        AND c.`name` = "' . $name . '"'
    );

    config_write::configCompile();
}

function reactor_ini_set_array($array)
{
    global $_db;

    $up = 0;

    foreach ($array as $ini) {
        if (constant(strtoupper($ini['module'] . '_' . $ini['name'])) == $ini['value']) {
            continue;
        }

        $up = 1;

        $_db->sql(
            'UPDATE
                ' . T_REACTOR_CONFIG . ' c,
                ' . T_REACTOR_MODULE . ' m
            SET c.`value` = "' . $ini['value'] . '"
            WHERE c.fk_module = m.pk_module
            AND m.`name` = "' . $ini['module'] . '"
            AND c.`name` = "' . $ini['name'] . '"'
        );
    }

    if ($up == 0) {
        return;
    }

    config_write::configCompile();
}

function stop($msg = '')
{
    global $Gekkon, $_log;

    reactor_trace($_log .= ' stop ' . $msg);

    initModule('site');

    if ($msg != '') {
        global $_reactor;

        $_reactor['show']['interface'] = '';
        $_reactor['show']['action']    = '';
        $_reactor['show']['module']    = 'site';

        if (substr($msg, 0, 3) == '404') {
            $_reactor['show']['template'] = '404.tpl';

            if (!headers_sent()) {
                header('HTTP/1.1 404 Not Found');
            }
        } else {
            $_reactor['show']['template'] = 'message.tpl';
        }

        $Gekkon->register('msg', $msg);
        $Gekkon->display('index.tpl');
    } else {
        header('Location: ' . SITE_URL);
    }

    die();
}

function reactor_trace($msg)
{
    global $_mctime, $_user;

    if (isset($_GET['debug']) && $_user['ugroup']['name'] == 'root') {
        echo microtime(true) - $_mctime, ' - ', $msg, "\n";

        if (isset($_SERVER['REQUEST_METHOD'])) {
            echo '<br>';
        }
    }
}

function reactor_error($msg)
{
    error_log($msg);
}
