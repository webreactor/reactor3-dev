<?php

use \Reactor\Database\PDO\Connection;

class SiteTreeConfig
{
    public $_db;

    public function __construct(Connection $_db = null)
    {
        $this->_db = $_db;
    }

    public function getConfig()
    {
        $query = $this->_db->sql(
            'SELECT *
            FROM site_tree
            ORDER BY
                fk_site_tree,
                pk_site_tree'
        );

        return $this->buildTreeConfig($query->matr());
    }

    public function buildTreeConfig($data, $fk_tree = 0)
    {
        $rez = array();
        foreach ($data as $node) {
            if ($node['fk_site_tree'] == $fk_tree) {
                $pk_tree = $node['pk_site_tree'];
                unset($node['pk_site_tree']);
                unset($node['fk_site_tree']);
                $node['in'] = $this->buildTreeConfig($data, $pk_tree);
                $rez[]      = $node;
            }
        }

        return $rez;
    }
}
