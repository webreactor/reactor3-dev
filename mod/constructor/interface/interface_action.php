<?php

include_once LIB_DIR . 'config_write.php';

class reactor_interface_action extends basic_object {
    function store($form) {
        $t = basic_object::store($form);
        interfacesCompile();

        return $t;
    }

    function back() {
        global $_db;
        $_db->sql('select fk_module from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $t = $_db->line();

        return $t['fk_module'];
    }

    function addStandartActions() {
        global $_db;
        $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $robj = $_db->line();
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"getOne","Один элемент","getOne","inputGetNum(\'' . $robj['pkey'] . '\',0)","","",0,0,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"!getList","Список","getList","inputGetNum(\'' . $robj['name'] . '_page\',1),20/*,\'where\'*/","list.tpl","cp",0,0,"",0)');
        $fk_action = $_db->last_id();
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $fk_action . ',' . $this->fkey_value . ',"edit","Редактировать","_isForm","$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'","form.tpl","cp",1,0,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"delete","Удалить","delete","inputGetNum(\'' . $robj['pkey'] . '\')","","",1,1,"",1)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $fk_action . ',' . $this->fkey_value . ',"add","Добавить","_isForm","$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'","form.tpl","cp",2,0,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"store","Сохранение","store","$param","","",0,0,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"createForm","Создание формы","_createForm","$this->_pool_id,\'store\',\'getOne\'","","",0,0,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,0,' . $this->fkey_value . ',"onRestore","onRestore","onRestore","","","",0,0,"",0)');
    }

    function addUpDownActions() {
        global $_db;
        $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $robj = $_db->line();
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"moveUp","Вверх","moveUp","inputGetNum(\'' . $robj['pkey'] . '\')","","",1,1,"",0)');
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"moveDown","Вниз","moveDown","inputGetNum(\'' . $robj['pkey'] . '\')","","",1,1,"",0)');
        interfacesCompile();
    }

    function addStandartJump() {
        global $_db;
        $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $robj = $_db->line();
        $fkey = 'f' . substr($robj['pkey'], 1);
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"!jump","Перейти к ...","","","target interface","target action",1,1,"\'' . $fkey . '=\'.inputGetNum(\'' . $robj['pkey'] . '\')",0)');
    }

    function addBasicObjConfigure() {
        global $_db;
        $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $robj = $_db->line();
        if ($robj['configurators'] == '') {
            $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"!' . $robj['name'] . '","","configure","_T_TABLE_NAME,\'_order_by\'","","",0,0,"",0)');
        } else {
            $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"!' . $robj['name'] . '","","configure","_T_TABLE_NAME,\'_order_by\',inputGetNum(\'' . $robj['configurators'] . '\',0)","","",0,0,"",0)');
        }
    }

    function addIntSelect() {
        global $_db;
        $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where pk_interface=' . $this->fkey_value);
        $robj = $_db->line();
        $_db->sql('insert into ' . T_REACTOR_INTERFACE_ACTION . ' (`pk_action`,`fk_interface`,`name`,`call`,`method`,`param`,`cptpl`,`cptpl_mod`,`public`,`handler`,`tpl_param`,`confirm`) values  (null,' . $this->fkey_value . ',"!getSelect","Интерактивный select","getSelect","_ROW,inputGetStr(\'filter\',\'\'),inputGetNum(\'getOne\',0)","","",0,0,"",0)');
    }
}//end of class

?>