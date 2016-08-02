<?php

use reactor\basic_object;
use reactor\content_adapter;
use reactor\reactor_interface;

function execute($name_or_pool_id = '', $action_name = '', $template = '', $tpl_module = '')
{
    global $Gekkon;

    reactor_trace('start execute ' . $name_or_pool_id . '->' . $action_name . ' to ' . $tpl_module . '/' . $template);

    $data = 0;

    $_save_data    = null;
    $_save_pool_id = null;

    if ($name_or_pool_id != '') {
        $object = new reactor_interface($name_or_pool_id);

        if ($action_name != '') {
            $null = '';

            $_obj = &$object->get('action');

            if (!isset($_obj[$action_name])) {
                reactor_trace('undefined action');

                return 0;
            }

            $data = $object->action($action_name, $null);

            @$_save_data = $Gekkon->data['exec_data'];
            @$_save_pool_id = $Gekkon->data['exec_pool_id'];

            $Gekkon->data['exec_data']    = $data;
            $Gekkon->data['exec_pool_id'] = $object->_pool_id;
        }
    }

    if ($tpl_module != '') {
        initModule($tpl_module);
    }

    if ($template != '') {
        $Gekkon->display($template);
    }

    if ($tpl_module != '') {
        uninitModule();
    }

    if ($name_or_pool_id != '' && $action_name != '') {
        $Gekkon->data['exec_data']    = $_save_data;
        $Gekkon->data['exec_pool_id'] = $_save_pool_id;
    }

    reactor_trace('end execute');

    return $data;
}

function pool_new()
{
    static $_global_pool_cnt = 0;

    $_global_pool_cnt++;

    $GLOBALS['_pool'][$_global_pool_cnt] = '';

    return $_global_pool_cnt;
}

function &pool_get($_pool_id, $name = '')
{
    if (!isset($GLOBALS['_pool'][$_pool_id])) {
        reactor_error('undefined _pool_id ' . $_pool_id . ' in pool_get');
    }

    if ($name == '') {
        return $GLOBALS['_pool'][$_pool_id];
    }

    return $GLOBALS['_pool'][$_pool_id][$name];
}

/**
 * @param $_pool_id
 *
 * @return content_adapter
 */
function &pool_create_content_adapter($_pool_id)
{
    if (!isset($GLOBALS['_pool'][$_pool_id])) {
        reactor_error('undefined _pool_id ' . $_pool_id . ' in pool_get_content_adapter');
    }

    $i_ca = new reactor_interface('content_adapter');

    /**
     * @var $ca basic_object
     */
    $ca = &$GLOBALS['_pool'][$i_ca->_pool_id]['object'];

    $ca->configure($GLOBALS['_pool'][$_pool_id]['define']);

    return $ca;
}
