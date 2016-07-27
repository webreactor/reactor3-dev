<?php

use \Reactor\Database\PDO\Connection;

class ModulesLoader
{
    protected $_db;

    public function __construct(Connection $_db = null)
    {
        $this->_db = $_db;
    }

    public function load($config)
    {
        $pk_module = $this->_db->insert('reactor_module', $config['module']);
        $this->loadTables($pk_module, $config['tables']);
        $this->loadBaseTypes($pk_module, $config['base_types']);
        $this->loadModuleConfig($pk_module, $config['config']);
        $this->loadInterfaces($pk_module, $config['interfaces']);
        $this->loadResources($pk_module, $config['resources']);

        return $pk_module;
    }

    protected function isTable($name)
    {
        $query = $this->_db->sql('SHOW TABLES LIKE :name', array(':name' => $name));

        return (bool) $query->line();
    }

    protected function loadTables($pk_module, $config)
    {
        foreach ($config as $record) {
            $record['fk_module'] = $pk_module;

            foreach ($record['creates'] as $table => $sql) {
                if (!$this->isTable($table)) {
                    $this->_db->sql($sql);
                }
            }

            unset($record['creates']);

            $this->_db->insert(T_REACTOR_TABLE, $record);
        }
    }

    protected function loadBaseTypes($pk_module, $config)
    {
        foreach ($config as $base_type) {
            $base_type['fk_module'] = $pk_module;

            $this->_db->insert(T_REACTOR_BASE_TYPE, $base_type);
        }
    }

    protected function loadModuleConfig($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;

            $this->_db->insert(T_REACTOR_CONFIG, $line);
        }
    }

    protected function loadResources($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;

            $this->_db->insert(T_REACTOR_RESOURCE, $line);
        }
    }

    protected function loadInterfaces($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;

            $actions = $line['_actions'];

            unset($line['_actions']);

            $defines = $line['_define'];

            unset($line['_define']);

            $line['fk_module'] = $pk_module;

            $pk_interface = $this->_db->insert(T_REACTOR_INTERFACE, $line);

            $this->loadActions($pk_interface, $actions);
            $this->loadDefines($pk_interface, $defines);
        }
    }

    protected function loadActions($pk_interface, $config)
    {
        foreach ($config as $line) {
            $line['fk_interface'] = $pk_interface;

            $this->_db->insert(T_REACTOR_INTERFACE_ACTION, $line);
        }
    }

    protected function loadDefines($pk_interface, $config)
    {
        foreach ($config as $line) {
            $line['fk_interface'] = $pk_interface;

            $this->_db->insert(T_REACTOR_INTERFACE_DEFINE, $line);
        }
    }

    public function loadActionRelations($relations)
    {
        foreach ($relations as $parent => $children) {
            $parent_action = $this->getActionId($parent);
            if ($parent_action) {
                foreach ($children as $line) {
                    $current_action = $this->getActionId($line);
                    if ($current_action) {
                        $this->_db->update(
                            T_REACTOR_INTERFACE_ACTION,
                            array('fk_action' => $parent_action),
                            array('pk_action' => $current_action)
                        );
                    }
                }
            }
        }
    }

    protected function getActionId($str)
    {
        $data = explode(';', $str);

        $interface = $data[0];
        $action    = $data[1];

        $query = $this->_db->sql(
            'SELECT a.pk_action
            FROM
                reactor_interface i,
                reactor_interface_action a
            WHERE i.pk_interface = a.fk_interface
            AND i.name = :interface
            AND a.name = :action',
            array(
                ':interface' => $interface,
                ':action'    => $action,
            )
        );

        $rez = $query->line();

        if ($rez) {
            return $rez['pk_action'];
        }

        return null;
    }
}
