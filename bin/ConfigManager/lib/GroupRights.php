<?php

use \Reactor\Database\PDO\Connection;

class GroupRights
{
    protected $_db;

    public function __construct(Connection $_db = null)
    {
        $this->_db = $_db;
    }

    public function getConfig()
    {
        $query = $this->_db->sql(
            'SELECT
                fk_ugroup,
                i.name AS `interface`,
                a.name as `action`
            FROM reactor_ugroup_action r
            JOIN reactor_interface_action a ON (r.fk_action = a.pk_action)
            JOIN reactor_interface i ON (a.fk_interface = pk_interface)'
        );

        return $query->matr();
    }

    public function load($data)
    {
        $this->_db->sql('TRUNCATE TABLE reactor_ugroup_action');

        foreach ($data as $line) {
            $pk_action = $this->getActionId($line['interface'], $line['action']);

            if ($pk_action) {
                $this->_db->insert(
                    'reactor_ugroup_action',
                    array(
                        'fk_ugroup' => $line['fk_ugroup'],
                        'fk_action' => $pk_action,
                    )
                );
            }
        }
    }

    protected function getActionId($interface, $action)
    {
        if (isset($this->cache[$interface]) && isset($this->cache[$interface][$action])) {
            return $this->cache[$interface][$action];
        }

        $query = $this->_db->sql(
            'SELECT pk_action
            FROM reactor_interface i
            JOIN reactor_interface_action a ON (i.pk_interface = a.fk_interface)
            WHERE i.name = :interface AND a.name = :action',
            array(
                ':interface' => $interface,
                ':action'    => $action,
            )
        );

        $pk = $query->line();

        if (!empty($pk)) {
            $pk = $pk['pk_action'];
        }

        $this->cache[$interface][$action] = $pk;

        return $pk;
    }
}