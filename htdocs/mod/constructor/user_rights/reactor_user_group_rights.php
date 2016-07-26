<?php

namespace mod\constructor\user_rights;

use reactor\basic_object;
use reactor\config_write;

class reactor_user_group_rights extends basic_object
{
    public $fk_value;

    public function getOne($fk)
    {
        $this->fk_value = $fk;

        global $_db;

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_MODULE . ' ORDER BY NAME');

        $r = $query->matr('pk_module');

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_INTERFACE . ' ORDER BY NAME');

        $tr = array();

        $cnt = 0;

        while ($t = $query->line()) {
            $r[$t['fk_module']]['interfaces'][$cnt] = $t;

            $tr[$t['pk_interface']] = &$r[$t['fk_module']]['interfaces'][$cnt];

            $cnt++;
        }

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_INTERFACE_ACTION . ' ORDER BY `CALL`');

        while ($t = $query->line()) {
            $tr[$t['fk_interface']]['actions'][] = $t;
        }

        $query = $_db->select(T_REACTOR_UGROUP_ACTION, array('fk_ugroup', $fk));

        return array(
            'stucture' => $r,
            'rights'   => $query->matr('fk_action', 'fk_ugroup'),
        );
    }

    public function store($form)
    {
        $this->_db->delete(T_REACTOR_UGROUP_ACTION, array('fk_ugroup', $this->fk_value));

        $data = $form->toDb();

        foreach ($data['rights'] as $item) {
            $this->_db->insert(
                T_REACTOR_UGROUP_ACTION,
                array(
                    'fk_ugroup' => $this->fk_value,
                    'fk_action' => $item,
                )
            );
        }

        config_write::guestUserCompile();
        config_write::interfacesCompile();

        return 1;
    }
}
