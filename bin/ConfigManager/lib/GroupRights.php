<?php

class GroupRights {
    protected $_db;

    public function __construct($_db) {
        $this->_db = $_db;
    }

    public function getConfig() {
        $this->_db->sql('
            SELECT fk_ugroup, i.name as `interface`, a.name as `action`
            FROM
            reactor_ugroup_action r
            join reactor_interface_action a on (r.fk_action = a.pk_action)
            join reactor_interface i on (a.fk_interface = pk_interface)
        ');

        return $this->_db->matr();
    }

    public function load($data) {
        $this->_db->sql('truncate table reactor_ugroup_action');
        foreach ($data as $line) {
            $pk_action = $this->getActionId($line['interface'], $line['action']);
            if ($pk_action) {
                $this->_db->insert('reactor_ugroup_action', array(
                    'fk_ugroup' => $line['fk_ugroup'],
                    'fk_action' => $pk_action,
                ));
            }
        }
    }

    protected function getActionId($interface, $action) {
        if (isset($this->cache[$interface]) && isset($this->cache[$interface][$action])) {
            return $this->cache[$interface][$action];
        }
        $this->_db->sql('select pk_action from reactor_interface i
            join reactor_interface_action a on (i.pk_interface = a.fk_interface)
            where i.name = "' . $interface . '" and a.name = "' . $action . '"'
        );
        $pk = $this->_db->line();
        if (!empty($pk)) {
            $pk = $pk['pk_action'];
        }
        $this->cache[$interface][$action] = $pk;

        return $pk;
    }
}