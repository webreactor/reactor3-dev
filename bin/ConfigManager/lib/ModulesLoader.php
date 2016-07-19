<?php

class ModulesLoader
{
    protected $_db;
    
    public function __construct($_db)
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
        $query = $this->_db->sql('show tables like "' . $name . '"');
        
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
            $this->_db->insert('reactor_table', $record);
        }
    }
    
    protected function loadBaseTypes($pk_module, $config)
    {
        foreach ($config as $base_type) {
            $base_type['fk_module'] = $pk_module;
            $this->_db->insert('reactor_base_type', $base_type);
        }
    }
    
    protected function loadModuleConfig($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;
            $this->_db->insert('reactor_config', $line);
        }
    }
    
    protected function loadResources($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;
            $this->_db->insert('reactor_resource', $line);
        }
    }
    
    protected function loadInterfaces($pk_module, $config)
    {
        foreach ($config as $line) {
            $line['fk_module'] = $pk_module;
            $actions           = $line['_actions'];
            unset($line['_actions']);
            $defines = $line['_define'];
            unset($line['_define']);
            $line['fk_module'] = $pk_module;
            $pk_interface      = $this->_db->insert('reactor_interface', $line);
            $this->loadActions($pk_interface, $actions);
            $this->loadDefines($pk_interface, $defines);
        }
    }
    
    protected function loadActions($pk_interface, $config)
    {
        foreach ($config as $line) {
            $line['fk_interface'] = $pk_interface;
            $this->_db->insert('reactor_interface_action', $line);
        }
    }
    
    protected function loadDefines($pk_interface, $config)
    {
        foreach ($config as $line) {
            $line['fk_interface'] = $pk_interface;
            $this->_db->insert('reactor_interface_define', $line);
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
                            'reactor_interface_action',
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
        $data      = explode(';', $str);
        $interface = $data[0];
        $action    = $data[1];
        $query = $this->_db->sql(
            'SELECT a.pk_action FROM reactor_interface i, reactor_interface_action a
            where i.pk_interface=a.fk_interface
            and i.name = "' . $interface . '" and a.name="' . $action . '"'
        );
        $rez = $query->line();
        if ($rez) {
            return $rez['pk_action'];
        }
        
        return null;
    }
}
