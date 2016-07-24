<?php

//version 2.2.5

class content_adapter
{
    var $define;
    var $data;
    var $error;
    var $handler;
    var $_take;
    var $_drop;
    var $stored_id;
    var $form_session;
    var $action;
    var $return_url;
    var $forvard_url;
    var $cancel_url;
    var $_pool_id;

    function content_adapter($_name_pool_so = '')
    {
        global $_interfaces;
        $this->define  = array();
        $this->handler = array();
        $this->data    = array();
        $this->error   = array();
        $this->_take   = array();
        $this->_drop   = array();

        $this->_pool_id     = 0;
        $this->form_session = uniqid('', true);

        if ($_name_pool_so != '') {
            if (isset($_interfaces[$_name_pool_so])) {
                $this->configure($_interfaces[$_name_pool_so]['define']);

                return;
            }
            if (isset($_SESSION['_stored_interface'][$_name_pool_so])) {
                $this->restore($_SESSION['_stored_interface'][$_name_pool_so]['define']);

                return;
            }
            if (isset($GLOBALS['_pool'][$_name_pool_so]['define'])) {
                $this->configure($GLOBALS['_pool'][$_name_pool_so]['define']);
            } else {
                reactor_error('what is ' . $_name_pool_so);
            }
        }
    }

    function testField($class, $value)
    {
        $test = new $class;

        return $test->fromForm($value);
    }

    function drop($fields = 'none')
    {
        $this->_drop = $fields;
        if ($fields == 'none') {
            $this->_drop = array();
        }
    }

    function take($fields = 'none')
    {
        $this->_take = $fields;
        if ($fields == 'none') {
            $this->_take = array();
        }
    }

    function configure($define)
    {
        global $_base_types;
        reactor_trace('content_adapter->configure');

        if ($this->_pool_id == 0) {
            $this->_pool_id                              = pool_new();
            $GLOBALS['_pool'][$this->_pool_id]           = array();
            $GLOBALS['_pool'][$this->_pool_id]['object'] =& $this;
        }

        foreach ($define as $key => $item) {
            $this->define[$key] = $item;

            $data = '';
            if ($item['default'] != '') {
                eval($item['default']);
            }
            $this->define[$key]['default'] = $data;

            if (!isset($this->data[$key])) {
                $this->data[$key] = $data;
            }

            $data = '';
            if ($item['enum'] != '') {
                eval($item['enum']);
            }
            $this->define[$key]['enum'] = $data;

            $data = array();
            if ($item['base_type_param'] != '') {
                eval($item['base_type_param']);
            }

            $this->define[$key]['base_type_param'] = $data;

            $this->handler[$key] = new $_base_types[$item['base_type']]['type']($key);
        }
        $this->onRestore();
    }

    function onRestore()
    {
        foreach ($this->handler as $k => $v) {
            $this->handler[$k]->_pool_id = $this->_pool_id;
        }
    }

    function fromForm($src)
    {
        global $_base_types;

        $this->error = array();
        foreach ($this->define as $item) {
            if ($_base_types[$item['base_type']]['handle'] == 0) {
                if (isset($src[$item['name']])) {
                    $this->data[$item['name']] = $this->validate($src[$item['name']], $item);
                } else {
                    $this->data[$item['name']] = $item['default'];
                    if ($item['necessary'] == 1) {
                        $this->error[$item['name']] = 'none';
                    }
                }
            }

            if ($_base_types[$item['base_type']]['handle'] == 1) {
                unset($this->data[$item['name']]);
            }

            //if($_base_types[$item['base_type']]['handle']==2)
            //do nothing for label

        }

        foreach ($this->define as $item) {
            if ($_base_types[$item['base_type']]['handle'] == 3) {
                $this->data[$item['name']] = $this->validate($item['default'], $item);
            }
        }
    }

    function validate(&$value, &$item)
    {
        global $_base_types;
        $type =& $_base_types[$item['base_type']];
        if (is_array($value) && $type['check_array'] == 1) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->validate($v, $item);
            }
        } else {

            $value = arrayMapRecursive('trim', $value);

            if (is_array($value)) {
                if ($type['check_array'] == 0) {
                    $value                      = $item['default'];
                    $this->error[$item['name']] = 'check_array';

                    return $value;
                }
            }

            if ($this->_necessary_r($value, $item['default']) > 0 && $item['necessary'] == 1) {
                $this->error[$item['name']] = 'necessary';

                return $value;
            }

            if ($item['enum'] != '' && $type['check_enum'] == 1) {
                if ($this->_enum_check_r($value, $item['default'], $item['enum']) > 0) {
                    $this->error[$item['name']] = 'check_enum';

                    return $value;
                }
            }

            if (!$this->handler[$item['name']]->fromForm($value)) {
                $this->error[$item['name']] = 'base_type';
                $value                      = $item['default'];

                return $value;
            }
        }

        return $value;
    }

    function _necessary_r(&$value, &$def)
    {
        $r = 0;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $r += $this->_necessary_r($value[$k], $def);
            }
        } else {
            if ($value == '') {
                $r     = 1;
                $value = $def;
            }
        }

        return $r;
    }

    function _enum_check_r(&$value, &$def, &$enum)
    {
        $r = 0;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $r += $this->_enum_check_r($value[$k], $def, $enum);
            }
        } else {
            if (!isset($enum[$value])) {
                $r     = 1;
                $value = $def;
            }
        }

        return $r;
    }

    function toDB($data = 'none')
    {

        if ($data != 'none') {
            return arrayMapRecursive('addslashes', $data);
        }

        return $this->convertTo('DB');
    }

    function convertTo($to, $data = 'none')
    {
        reactor_trace('content_adapter->convertTo' . $to);
        $to = 'to' . $to;
        if ($data == 'none') {
            $data = $this->data;
        }

        if (count($this->_take) > 0) {
            foreach ($data as $key => $item) {
                if (!in_array($key, $this->_take)) {
                    unset($data[$key]);
                } else {
                    if (isset($this->define[$key])) {
                        $data[$key] = $this->convertTo_r($to, $this->handler[$key], $this->define[$key], $data[$key]);
                    }
                }
            }
        } else {
            foreach ($this->define as $key => $item) {
                if (isset($data[$key])) {
                    if (in_array($key, $this->_drop)) {
                        unset($data[$key]);
                    } else {
                        $data[$key] = $this->convertTo_r($to, $this->handler[$key], $item, $data[$key]);
                    }
                }
            }
        }

        return $data;
    }

    function convertTo_r($to, &$obj, &$item, $value)
    {
        global $_base_types;
        if (is_array($value) && $_base_types[$item['base_type']]['check_array'] == 1) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->convertTo_r($to, $obj, $item, $v);
            }
        } else {
            $value = $obj->$to($value);
        }

        return $value;
    }

    function toForm($data = 'none')
    {
        ;

        return $this->convertTo('Form', $data);
    }

    function arrayToHTML(&$data)
    {
        foreach ($data as $k => $v) {
            $data[$k] = $this->toHTML($v);
        }

        return $data;
    }

    function toHTML($data = 'none')
    {
        $data = $this->convertTo('HTML', $data);

        foreach ($this->define as $key => $item) {
            if ($item['enum'] != '' && isset($data[$key])) {
                $data[$key . '_save'] = $data[$key];
                @$data[$key] = $item['enum'][$data[$key]];
            }
        }

        return $data;
    }
}
