<?php

class ModulesConfig {
    protected $_db;

    function __construct($_db) {
        $this->_db = $_db;
    }

    function getConfig($pk_module) {
        $config = array();
        $this->_db->sql('select * from reactor_module where pk_module = ' . $pk_module);
        $module = $this->_db->line();
        unset($module['pk_module']);
        $module['depend'] = '';
        $config['module'] = $module;
        $config['interfaces'] = $this->interfaces($pk_module);
        $config['tables'] = $this->tables($pk_module);
        $config['config'] = $this->moduleConfig($pk_module);
        $config['resources'] = $this->resources($pk_module);
        $config['base_types'] = $this->baseTypes($pk_module);
        $config['action_relations'] = $this->actionRelations($pk_module);

        return $config;
    }

    function actionRelations($pk_module) {
        $rez = array();
        $this->_db->sql('
            SELECT
                pi.name parent_interface, pa.name as parent_action, 
                i.name as interface, a.name as `action`
            FROM
                reactor_interface_action a,
                reactor_interface i,
                reactor_interface_action pa,
                reactor_interface pi
            WHERE 
                pa.pk_action = a.fk_action
                and i.pk_interface = a.fk_interface
                and pi.pk_interface = pa.fk_interface
                and a.public = 3
                and i.fk_module = ' . $pk_module
        );
        $data = $this->_db->matr();
        foreach ($data as $rel) {
            $rez[$rel['parent_interface'] . ';' . $rel['parent_action']][] = $rel['interface'] . ';' . $rel['action'];
        }

        return $rez;
    }

    function baseTypes($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_base_type where fk_module = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_base_type');
        foreach ($collection as $pk => $item) {
            unset($item['pk_base_type']);
            unset($item['fk_module']);
            $rez[] = $item;
        }

        return $rez;
    }

    function tables($pk_parent) {
        global $_languages;
        $rez = array();
        $this->_db->sql('select * from reactor_table where fk_module = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_table');
        foreach ($collection as $pk => $item) {
            unset($item['pk_table']);
            unset($item['fk_module']);
            $item['creates'] = array();
            if ($item['mlng']) {
                $item['creates'] = $this->tableCreateMLng($item['db_name'], keys($_languages));
            } else {
                $sql = $this->tableCreate($item['db_name']);
                if (!empty($sql)) {
                    $item['creates'][$item['db_name']] = $this->tableCreate($item['db_name']);
                }
            }
            $rez[] = $item;
        }

        return $rez;
    }

    protected function isTable($name) {
        $this->_db->sql('show tables like "' . $name . '"');

        return (bool) $this->_db->line();
    }

    function tableCreate($db_name) {
        if ($this->isTable($db_name)) {
            $this->_db->sql('show create table `' . $db_name . '`');
            $create_sql = $this->_db->line();

            return $create_sql['Create Table'];
        }

        return null;
    }

    function tableCreateMLng($db_name, $languages) {
        $rez = array();
        foreach ($languages as $lng) {
            $real_name = $db_name . '_' . $lng;
            $sql = $this->tableCreate($real_name);
            if (!empty($sql)) {
                $rez[$real_name] = $sql;
            }
        }

        return $rez;
    }

    function resources($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_resource where fk_module = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_resource');
        foreach ($collection as $pk => $item) {
            unset($item['pk_resource']);
            unset($item['fk_module']);
            $rez[] = $item;
        }

        return $rez;
    }

    function moduleConfig($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_config where fk_module = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_config');
        foreach ($collection as $pk => $item) {
            unset($item['pk_config']);
            unset($item['fk_module']);
            $rez[] = $item;
        }

        return $rez;
    }

    function interfaces($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_interface where fk_module = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_interface');
        foreach ($collection as $pk => $item) {
            unset($item['pk_interface']);
            unset($item['fk_interface']);
            unset($item['fk_module']);
            $item['_define'] = $this->interfaceDefine($pk);
            $item['_actions'] = $this->interfaceActions($pk);
            $rez[] = $item;
        }

        return $rez;
    }

    function interfaceDefine($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_interface_define where fk_interface = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_define');
        foreach ($collection as $pk => $property) {
            unset($property['fk_interface']);
            unset($property['pk_define']);
            $rez[] = $property;
        }

        return $rez;
    }

    function interfaceActions($pk_parent) {
        $rez = array();
        $this->_db->sql('select * from reactor_interface_action where fk_interface = ' . $pk_parent . ' order by name');
        $collection = $this->_db->matr('pk_action');
        foreach ($collection as $pk => $action) {
            unset($action['fk_interface']);
            unset($action['fk_action']);
            unset($action['pk_action']);
            $rez[] = $action;
        }

        return $rez;
    }
}
