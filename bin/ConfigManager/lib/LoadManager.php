<?php

class LoadManager
{
    protected $_db;
    protected $mod_dir;
    protected $group_rights_file;
    
    public function __construct($_db, $mod_dir)
    {
        $this->_db               = $_db;
        $this->mod_dir           = $mod_dir;
        $this->site_tree         = new SiteTreeLoader($_db);
        $this->modules           = new ModulesLoader($_db);
        $this->rollback          = new RollbackTableManager($_db, VAR_DIR);
        $this->group_rights      = new GroupRights($_db);
        $this->group_rights_file = $mod_dir . 'user_group_rights.php';
    }
    
    public function load()
    {
        $rights = $this->group_rights->getConfig();
        $this->rollback->resetAllModules();
        $this->loadModules($this->mod_dir);
        $this->group_rights->load($rights);
        $this->loadTree();
    }
    
    protected function loadTree()
    {
        $tree_config_file = $this->mod_dir . 'site/site_tree_config_dump.php';
        if (is_file($tree_config_file)) {
            $data = $this->loadFile($tree_config_file);
            $this->site_tree->load($data);
        }
    }
    
    protected function loadModules($mod_dir)
    {
        $mod_action_relations   = array();
        $modules                = $this->getModulesList($mod_dir);
        $mod_action_relations[] = $this->loadModule($mod_dir . 'reactor/module_config_dump.php');
        foreach ($modules as $mod_name) {
            if ($mod_name != 'reactor') {
                $mod_action_relations[] = $this->loadModule($mod_dir . $mod_name . '/module_config_dump.php');
            }
        }
        foreach ($mod_action_relations as $action_relations) {
            $this->modules->loadActionRelations($action_relations);
        }
    }
    
    protected function loadModule($file)
    {
        $config = $this->loadFile($file);
        $this->modules->load($config);
        
        return $config['action_relations'];
    }
    
    protected function getModulesList($dir)
    {
        $rez = array();
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file[0] != '.' && is_dir($this->mod_dir . $file)) {
                    $rez[] = $file;
                }
            }
            closedir($dh);
        }
        
        return $rez;
    }
    
    public function loadFile($file)
    {
        echo "Loading file $file\n";
        
        return require $file;
    }
}
