<?php

use reactor\reactor_interface;

function &tryGetForm($_so)
{
    $form_container = new reactor_interface();

    $form = 0;

    if ($form_container->isStored($_so)) {
        $form_container->restore($_so);

        $form = &$form_container->get('object');
        
        $form_container->store($form->form_session);
    }

    return $form;
}

function handleForm($_so = '', $interface = 'none', $action = 'none')
{
    global $_reactor;

    $object = 'none';

    $form_container = new reactor_interface();
    if ($form_container->isStored($_so)) {
        $form_container->restore($_so);
    } else {
        $object = new reactor_interface($interface);

        $form_container = new reactor_interface($object->action($action, $_reactor));
    }

    $form = &$form_container->get('object');

    if (get_magic_quotes_gpc() && isset($_POST)) {
        $data = arrayMapRecursive('stripslashes', $_POST);
    } else {
        $data = $_POST;
    }

//    $data = preg_replace('/[\x00-\x10\x0B\x0C\x0E-\x1F]/', '', $data);
//    $data = preg_replace('/[\xA0]/', ' ', $data);

    $form->fromForm($data);

    if (count($form->error) == 0) {
        if ($object == 'none') {
            $object = new reactor_interface();
            $object->restore($form->stored_id);
        }

        if (!$object->action($form->action, $form)) {
            $form->error['_error_on_action'] = 1;
        }

        if (count($form->error) == 0) {
            if (is_array($form->forvard_url)) {
                header('Location: ' . compileUrl($form->forvard_url));
            } else {
                header('Location: ' . $form->forvard_url);
            }

            die();
        } else {
            $object->store($form->stored_id);
        }
    }

    $fs = $form_container->store($form->form_session);

    if (is_array($form->return_url)) {
        $ret = $form->return_url;

        $ret['_so'] = $fs;

        header('Location: ' . compileUrl($ret));
    } else {
        if (strstr($form->return_url, '?') !== false) {
            header('Location: ' . $form->return_url . '&_so=' . $fs);
        } else {
            header('Location: ' . $form->return_url . '?_so=' . $fs);
        }
    }

    die();
}

function createForm($pool_id, $action, $callback, $param = array())
{
    global $_RGET, $_reactor;

    $container = new reactor_interface($pool_id);

    $form = new reactor_interface('content_adapter');

    $fo = &$form->get('object');

    $fo->action = $action;

    $fo->cancel_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];

    $fo->return_url = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['HTTP_REFERER']) || $_reactor['show']['name'] == 'ajax_request') ? $_SERVER['HTTP_REFERER'] : $_RGET;

    $fo->forvard_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];

    $form_data = $container->action($callback, $param);

    if ($form_data != 0) {
        $fo->data = $form_data;
    }

    $fo->configure(pool_get($pool_id, 'define'));

    $fo->stored_id = $container->store();

    $form->store($fo->form_session);

    return $form->_pool_id;
}

function isForm($pool_id, $form_session = 'none', $callback, $param = array())
{
    $container = new reactor_interface($pool_id);

    $form = new reactor_interface();

    if ($form_session != 'none' && $form->isStored($form_session)) {
        $form->restore($form_session);
        $form->store($form_session);

        return $form->_pool_id;
    }

    return $container->action($callback, $param);
}
