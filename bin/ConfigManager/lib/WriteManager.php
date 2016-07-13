<?php

class WriteManager {
    protected $_db;
    protected $mod_dir;

    public function __construct($_db, $mod_dir) {
        $this->_db = $_db;
        $this->mod_dir = $mod_dir;
        $this->site_tree = new SiteTreeConfig($_db);
        $this->modules = new ModulesConfig($_db);
    }

    public function write() {
        $this->writeToFile($this->mod_dir.'site/site_tree_config_dump.php', $this->site_tree->getConfig());
        $this->writeModules();
    }

    protected function writeModules() {
        $this->_db->sql('select pk_module, name from reactor_module order by name');
        $modules = $this->_db->matr('pk_module', 'name');
        foreach ($modules as $pk_module => $module_name) {
            echo "Created config dump for {$module_name}\n";
            $this->writeToFile($this->mod_dir.$module_name.'/module_config_dump.php', $this->modules->getConfig($pk_module));
        }
    }

    public function writeToFile($file, $data) {
        if (!is_writable($file)) {
            if (!is_writable(dirname($file))) {
                die($file.' unable to write');    
            }
        }
        file_put_contents($file, "<?php\nreturn ".$this->dataExport($data).";");
    }

    public function dataExport($data, $level = 0) {
        $tab1 = str_repeat(' ', $level * 4);
        $tab2 = str_repeat(' ', ($level + 1) * 4);

        $rez = "array(\n";

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $val_dumped = $this->dataExport($value, $level + 1);
            } else {
                $val_dumped = var_export($value, true);
            }
            
            if (is_numeric($key)) {
                $rez .= "{$tab2}{$val_dumped},\n";
            } else {
                $rez .= "{$tab2}'{$key}' => {$val_dumped},\n";
            }
        }

        return $rez."{$tab1})";
    }


}