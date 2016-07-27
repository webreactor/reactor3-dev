<?php

//version 2.0

class reactor_interface
{
    var $_pool_id;

    function reactor_interface($_name_pool_so = '')
    {
        global $_interfaces;
        reactor_trace('creating interface ' . $_name_pool_so);
        $this->_pool_id = 0;
        if ($_name_pool_so != '') {
            if (isset($GLOBALS['_pool'][$_name_pool_so])) {
                $this->_pool_id = $_name_pool_so;

                return;
            }
            if (isset($_interfaces[$_name_pool_so])) {
                $this->configure($_name_pool_so);

                return;
            }
            if (isset($_SESSION['_stored_interface'][$_name_pool_so])) {
                $this->restore($_name_pool_so);

                return;
            }
            reactor_error('what is ' . $_name_pool_so);
        }
    }

    function &get($name = '')
    {
        if ($name == '') {
            return $GLOBALS['_pool'][$this->_pool_id];
        }

        return $GLOBALS['_pool'][$this->_pool_id][$name];
    }

    function see_at($_pool_id)
    {
        if (!isset($GLOBALS['_pool'][$_pool_id])) {
            reactor_error('undefined pool_id ' . $interface_name);
        }
        $this->_pool_id = $_pool_id;
    }

    function configure($interface_name)
    {
        global $_interfaces, $_reactor;
        if ($this->_pool_id == 0) {
            $this->_pool_id = pool_new();
        }

        if (!isset($_interfaces[$interface_name])) {
            reactor_error('undefined interface ' . $interface_name);
        }

        $_data =& $GLOBALS['_pool'][$this->_pool_id];
        $_data = $_interfaces[$interface_name];

        reactor_trace('configure ' . $interface_name);

        initModule($_data['module']);

        if ($_data['source'] != '') {
            require_once $_reactor['module']['dir'] . $_data['source'];
        }
        $eCode = '$_data["object"]=new ' . $_data['class'] . '(' . $_data['constructor'] . ');';

        reactor_trace($eCode);
        eval($eCode);
        $_data['object']->_pool_id = $this->_pool_id;

        if (isset($_data['action'][$interface_name])) {
            $this->action($interface_name, $null);
        }

        uninitModule();
    }

    function isStored($id)
    {
        return isset($_SESSION['_stored_interface'][$id]);
    }

    function restore($id)
    {
        global $_interfaces, $_reactor;

        if (!isset($_SESSION['_stored_interface'][$id])) {
            reactor_error('can not find stored interface ' . $id);
        }

        $cls =& $_interfaces[$_SESSION['_stored_interface'][$id]['name']];

        reactor_trace($_SESSION['_stored_interface'][$id]['name'] . '->restore ' . $id);

        if ($cls['source'] != '') {
            initModule($cls['module']);
            require_once $_reactor['module']['dir'] . $cls['source'];
            uninitModule($cls['module']);
        }

        if ($this->_pool_id == 0) {
            $this->_pool_id = pool_new();
        }
        $_data                     =& $GLOBALS['_pool'][$this->_pool_id];
        $_data                     = unserialize($_SESSION['_stored_interface'][$id]['data']);
        $_data['object']->_pool_id = $this->_pool_id;
        unset($_SESSION['_stored_interface'][$id]);

        if (isset($_data['action']['onRestore'])) {
            $this->action('onRestore', $this->_pool_id);
        }

        return $this->_pool_id;
    }

    function store($id = 'none')
    {
        $_data =& $GLOBALS['_pool'][$this->_pool_id];

        if ($id == 'none') {
            $id = uniqid('', true);
        }

        reactor_trace($_data['name'] . '->store ' . $id);

        if (isset($_data['action']['onStore'])) {
            $this->action('onStore', $null);
        }

        if (session_id() == '' && !headers_sent()) {
            session_start();
        }

        $_SESSION['_stored_interface'][$id]['name'] = $_data['name'];
        $_SESSION['_stored_interface'][$id]['data'] = serialize($_data);

        return $id;
    }

    function action($action_name, &$param)
    {
        global $_RGET, $_SGET, $_db, $_reactor, $_user;
        $_data =& $GLOBALS['_pool'][$this->_pool_id];

        reactor_trace('start action ' . $_data['name'] . '->' . $action_name);

        if (!isset($_data['action'][$action_name])) {
            reactor_error('undefined action ' . $action_name . ' in ' . $_data['name']);
            die();
        }

        $action =& $_data['action'][$action_name];

        if ($action['method'] == '') {
            return array();
        }

        $ret = null;

        if ($action['method'][0] == '_') {
            $eCode = '$ret=' . substr($action['method'], 1) . '(' . $action['param'] . ');';
        } else {
            $eCode = '$ret=$_data["object"]->' . $action['method'] . '(' . $action['param'] . ');';
        }

        initModule($_data['module']);
        reactor_trace('do: ' . $eCode);
        eval($eCode);
        uninitModule();

        reactor_trace('end action');

        return $ret;
    }
}
