<?php

class SiteTreeLoader
{
    public $_db;
    public $data;

    function __construct($_db) {
        $this->_db = $_db;
    }

    function load($data) {
        $this->data = $data;
        $this->_db->sql("TRUNCATE TABLE site_tree");
        $this->load_r($this->data);
    }

    function load_r($data, $fk_site_tree = 0) {
        foreach ($data as $node) {
            $in = $node['in'];
            unset($node['in']);
            $node['fk_site_tree'] = $fk_site_tree;
            $pk_site_tree = $this->_db->insert('site_tree', $node);
            $this->load_r($in, $pk_site_tree);
        }
    }

}
