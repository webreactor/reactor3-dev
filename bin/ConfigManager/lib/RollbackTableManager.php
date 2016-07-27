<?php

use \Reactor\Database\PDO\Connection;

class RollbackTableManager
{
    protected $_db;
    protected $tables;
    protected $writer;
    protected $dump_file;

    public function __construct(Connection $_db = null, $dump_dir = null)
    {
        $this->_db       = $_db;
        $this->writer    = new WriteManager();
        $this->dump_file = $dump_dir . 'config_rollback.php';
        $this->tables    = array(
            'reactor_base_type',
            'reactor_config',
            'reactor_interface',
            'reactor_interface_action',
            'reactor_interface_define',
            'reactor_module',
            'reactor_resource',
            'reactor_table',
            'reactor_ugroup_action',
            'site_tree',
        );
    }

    public function resetAllModules()
    {
        $this->dumpAllTables();
        foreach ($this->tables as $table) {
            $this->clearTable($table);
        }
    }

    public function dumpAllTables()
    {
        $rez = array();
        foreach ($this->tables as $table) {
            $rez[] = $this->getTableDump($table);
        }
        echo "Creating rollback file " . $this->dump_file . "\n";
        $this->writer->writeToFile($this->dump_file, $rez);
    }

    public function loadAllTables()
    {
        if (!is_file($this->dump_file)) {
            die("Unable to locate " . $this->dump_file);
        }
        $data = require $this->dump_file;
        foreach ($data as $table) {
            echo "Loading table " . $table['name'] . "\n";
            $this->loadTableDump($table);
            echo "Loaded " . count($table['data']) . " records\n";
        }
    }

    protected function clearTable($name)
    {
        if ($this->isTable($name)) {
            $this->_db->sql(sprintf('TRUNCATE TABLE %s', $name));
        }
    }

    public function getTableDump($table_name)
    {
        if (!$this->isTable($table_name)) {
            die("Table $table_name does not exist");
        }

        return array(
            'name'   => $table_name,
            'create' => $this->tableCreate($table_name),
            'data'   => $this->tableData($table_name),
        );
    }

    public function loadTableDump($table)
    {
        $this->_db->sql('DROP TABLE IF EXISTS :name', array(':name' => $table['name']));

        $this->_db->sql($table['create']);

        foreach ($table['data'] as $line) {
            $this->_db->insert($table['name'], $line);
        }
    }

    public function isTable($table_name)
    {
        $query = $this->_db->sql('SHOW TABLES LIKE :name', array(':name' => $table_name));

        return (bool) $query->line();
    }

    public function tableCreate($table_name)
    {
        if ($this->isTable($table_name)) {
            $query = $this->_db->sql(sprintf('SHOW CREATE TABLE %s', $table_name));

            $create_sql = $query->line();

            return $create_sql['Create Table'];
        }

        return null;
    }

    public function tableData($table_name)
    {
        $query = $this->_db->select($table_name);

        return $query->matr();
    }
}
