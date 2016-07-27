<?php

use \Reactor\Database\PDO\Connection;

class ModulesConfig
{
    protected $_db;

    public function __construct(Connection $_db = null)
    {
        $this->_db = $_db;
    }

    public function getConfig($pk_module)
    {
        $config = array();

        $query = $this->_db->select(
            T_REACTOR_MODULE,
            array('pk_module' => $pk_module)
        );

        $module = $query->line();

        unset($module['pk_module']);

        $module['depend'] = '';

        $config['module']           = $module;
        $config['interfaces']       = $this->interfaces($pk_module);
        $config['tables']           = $this->tables($pk_module);
        $config['config']           = $this->moduleConfig($pk_module);
        $config['resources']        = $this->resources($pk_module);
        $config['base_types']       = $this->baseTypes($pk_module);
        $config['action_relations'] = $this->actionRelations($pk_module);

        return $config;
    }

    public function actionRelations($pk_module)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT
                pi.name parent_interface,
                pa.name AS parent_action, 
                i.name AS interface,
                a.name AS action
            FROM
                reactor_interface_action a,
                reactor_interface i,
                reactor_interface_action pa,
                reactor_interface pi
            WHERE pa.pk_action = a.fk_action
            AND i.pk_interface = a.fk_interface
            AND pi.pk_interface = pa.fk_interface
            AND a.public = 3
            AND i.fk_module = :fk_module',
            array(':fk_module' => $pk_module)
        );

        $data = $query->matr();

        foreach ($data as $rel) {
            $rez[$rel['parent_interface'] . ';' . $rel['parent_action']][] = $rel['interface'] . ';' . $rel['action'];
        }

        return $rez;
    }

    public function baseTypes($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_base_type
            WHERE fk_module = :fk_module
            ORDER BY name',
            array(':fk_module' => $pk_parent)
        );

        $collection = $query->matr('pk_base_type');

        foreach ($collection as $pk => $item) {
            unset($item['pk_base_type']);
            unset($item['fk_module']);

            $rez[] = $item;
        }

        return $rez;
    }

    public function tables($pk_parent)
    {
        global $_languages;

        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_table
            WHERE fk_module = :fk_module
            ORDER BY name',
            array(':fk_module' => $pk_parent)
        );

        $collection = $query->matr('pk_table');

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

    protected function isTable($name)
    {
        $query = $this->_db->sql('SHOW TABLES LIKE :name', array(':name' => $name));

        return (bool) $query->line();
    }

    public function tableCreate($db_name)
    {
        if ($this->isTable($db_name)) {
            $query = $this->_db->sql(sprintf('SHOW CREATE TABLE %s', $db_name));

            $create_sql = $query->line();

            return $create_sql['Create Table'];
        }

        return null;
    }

    public function tableCreateMLng($db_name, $languages)
    {
        $rez = array();
        foreach ($languages as $lng) {
            $real_name = $db_name . '_' . $lng;
            $sql       = $this->tableCreate($real_name);
            if (!empty($sql)) {
                $rez[$real_name] = $sql;
            }
        }

        return $rez;
    }

    public function resources($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_resource
            WHERE fk_module = :fk_module
            ORDER BY name',
            array(':fk_module' => $pk_parent)
        );

        $collection = $query->matr('pk_resource');

        foreach ($collection as $pk => $item) {
            unset($item['pk_resource']);
            unset($item['fk_module']);

            $rez[] = $item;
        }

        return $rez;
    }

    public function moduleConfig($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_config
            WHERE fk_module = :fk_module
            ORDER BY name',
            array(':fk_module' => $pk_parent)
        );

        $collection = $query->matr('pk_config');

        foreach ($collection as $pk => $item) {
            unset($item['pk_config']);
            unset($item['fk_module']);

            $rez[] = $item;
        }

        return $rez;
    }

    public function interfaces($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_interface
            WHERE fk_module = :fk_module
            ORDER BY name',
            array(':fk_module' => $pk_parent)
        );

        $collection = $query->matr('pk_interface');

        foreach ($collection as $pk => $item) {
            unset($item['pk_interface']);
            unset($item['fk_interface']);
            unset($item['fk_module']);

            $item['_define']  = $this->interfaceDefine($pk);
            $item['_actions'] = $this->interfaceActions($pk);

            $rez[] = $item;
        }

        return $rez;
    }

    public function interfaceDefine($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_interface_define
            WHERE fk_interface = :fk_interface
            ORDER BY name',
            array(':fk_interface' => $pk_parent)
        );

        $collection = $query->matr('pk_define');

        foreach ($collection as $pk => $property) {
            unset($property['fk_interface']);
            unset($property['pk_define']);

            $rez[] = $property;
        }

        return $rez;
    }

    public function interfaceActions($pk_parent)
    {
        $rez = array();

        $query = $this->_db->sql(
            'SELECT *
            FROM reactor_interface_action
            WHERE fk_interface = :fk_interface
            ORDER BY name',
            array(':fk_interface' => $pk_parent)
        );

        $collection = $query->matr('pk_action');

        foreach ($collection as $pk => $action) {
            unset($action['fk_interface']);
            unset($action['fk_action']);
            unset($action['pk_action']);

            $rez[] = $action;
        }

        return $rez;
    }
}
