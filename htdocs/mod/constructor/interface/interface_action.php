<?php

require_once LIB_DIR . 'config_write.php';

class reactor_interface_action extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);

        interfacesCompile();

        return $t;
    }

    function back()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $t = $query->line();

        return $t['fk_module'];
    }

    function addStandartActions()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $robj = $query->line();

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => 'getOne',
                'call'         => 'Один элемент',
                'description'  => '',
                'method'       => 'getOne',
                'param'        => sprintf("inputGetNum('%s',0)", $robj['pkey']),
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => '!getList',
                'call'         => 'Список',
                'description'  => '',
                'method'       => 'getList',
                'param'        => sprintf("inputGetNum('%s_page',1),20/*,'where'*/", $robj['pkey']),
                'cptpl'        => 'list.tpl',
                'cptpl_mod'    => 'cp',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $fk_action = $_db->lastId();

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => $fk_action,
                'fk_interface' => $this->fkey_value,
                'name'         => 'edit',
                'call'         => 'Редактировать',
                'description'  => '',
                'method'       => '_isForm',
                'param'        => '$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'',
                'cptpl'        => 'form.tpl',
                'cptpl_mod'    => 'cp',
                'public'       => 1,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => 'delete',
                'call'         => 'Удалить',
                'description'  => '',
                'method'       => 'delete',
                'param'        => sprintf("inputGetNum('%s')", $robj['pkey']),
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 1,
                'handler'      => 1,
                'tpl_param'    => '',
                'confirm'      => 1,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => $fk_action,
                'fk_interface' => $this->fkey_value,
                'name'         => 'add',
                'call'         => 'Добавить',
                'description'  => '',
                'method'       => '_isForm',
                'param'        => '$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'',
                'cptpl'        => 'form.tpl',
                'cptpl_mod'    => 'cp',
                'public'       => 2,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => 'store',
                'call'         => 'Сохранение',
                'description'  => '',
                'method'       => 'store',
                'param'        => '$param',
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => 'createForm',
                'call'         => 'Создание формы',
                'description'  => '',
                'method'       => '_createForm',
                'param'        => '$this->_pool_id,\'store\',\'getOne\'',
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_action'    => 0,
                'fk_interface' => $this->fkey_value,
                'name'         => 'onRestore',
                'call'         => 'onRestore',
                'description'  => '',
                'method'       => 'onRestore',
                'param'        => '',
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );
    }

    function addUpDownActions()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $robj = $query->line();

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_interface' => $this->fkey_value,
                'name'         => 'moveUp',
                'call'         => 'Вверх',
                'description'  => '',
                'method'       => 'moveUp',
                'param'        => sprintf("inputGetNum('%s')", $robj['pkey']),
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 1,
                'handler'      => 1,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_interface' => $this->fkey_value,
                'name'         => 'moveDown',
                'call'         => 'Вниз',
                'description'  => '',
                'method'       => 'moveDown',
                'param'        => sprintf("inputGetNum('%s')", $robj['pkey']),
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 1,
                'handler'      => 1,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );

        interfacesCompile();
    }

    function addStandartJump()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $robj = $query->line();

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_interface' => $this->fkey_value,
                'name'         => '!jump',
                'call'         => 'Перейти к ...',
                'description'  => '',
                'method'       => '',
                'param'        => '',
                'cptpl'        => 'target interface',
                'cptpl_mod'    => 'target action',
                'public'       => 1,
                'handler'      => 1,
                'tpl_param'    => sprintf("f%s=inputGetNum('%s')", substr($robj['pkey'], 1), $robj['pkey']),
                'confirm'      => 0,
            )
        );
    }

    function addBasicObjConfigure()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE, array('pk_interface' => $this->fkey_value));

        $robj = $query->line();

        if ($robj['configurators'] == '') {
            $_db->insert(
                T_REACTOR_INTERFACE_ACTION,
                array(
                    'fk_interface' => $this->fkey_value,
                    'name'         => sprintf('!%s', $robj['name']),
                    'call'         => '',
                    'description'  => '',
                    'method'       => 'configure',
                    'param'        => '_T_TABLE_NAME,\'_order_by\'',
                    'cptpl'        => '',
                    'cptpl_mod'    => '',
                    'public'       => 0,
                    'handler'      => 0,
                    'tpl_param'    => '',
                    'confirm'      => 0,
                )
            );
        } else {
            $_db->insert(
                T_REACTOR_INTERFACE_ACTION,
                array(
                    'fk_interface' => $this->fkey_value,
                    'name'         => sprintf('!%s', $robj['name']),
                    'call'         => '',
                    'description'  => '',
                    'method'       => 'configure',
                    'param'        => sprintf(
                        '_T_TABLE_NAME,\'_order_by\',inputGetNum(\'%s\',0)',
                        $robj['configurators']
                    ),
                    'cptpl'        => '',
                    'cptpl_mod'    => '',
                    'public'       => 0,
                    'handler'      => 0,
                    'tpl_param'    => '',
                    'confirm'      => 0,
                )
            );
        }
    }

    function addIntSelect()
    {
        global $_db;

        $_db->insert(
            T_REACTOR_INTERFACE_ACTION,
            array(
                'fk_interface' => $this->fkey_value,
                'name'         => '!getSelect',
                'call'         => 'Интерактивный select',
                'description'  => '',
                'method'       => 'getSelect',
                'param'        => '_ROW,inputGetStr(\'filter\',\'\'),inputGetNum(\'getOne\',0)',
                'cptpl'        => '',
                'cptpl_mod'    => '',
                'public'       => 0,
                'handler'      => 0,
                'tpl_param'    => '',
                'confirm'      => 0,
            )
        );
    }
}
