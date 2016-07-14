<?php

class SiteTreeConfig
{
    public $_db;

    function __construct($_db)
    {
        $this->_db = $_db;
    }

    function getConfig()
    {
        $this->_db->sql('select * from site_tree order by fk_site_tree, pk_site_tree');

        return $this->buildTreeConfig($this->_db->matr());
    }

    function buildTreeConfig($data, $fk_tree = 0)
    {
        $rez = array();
        foreach ($data as $node) {
            if ($node['fk_site_tree'] == $fk_tree) {
                $pk_tree = $node['pk_site_tree'];
                unset($node['pk_site_tree']);
                unset($node['fk_site_tree']);
                $node['in'] = $this->buildTreeConfig($data, $pk_tree);
                $rez[] = $node;
            }
        }

        return $rez;
    }
}
