<?php

include_once LIB_DIR . 'config_write.php';
include $_reactor['module']['dir'] . 'lib/create_rmp.php';
include $_reactor['module']['dir'] . 'lib/install_rmp.php';
include $_reactor['module']['dir'] . 'lib/uninstall_rmp.php';

class reactor_module extends basic_object {
    function store($form) {
        $t = 1;
        if ($this->pkey_value == 0) {
            if (!install_rmp($form->data['name'])) {
                $t = basic_object::store($form);
            }
        } else {
            $t = basic_object::store($form);
        }

        interfacesCompile();

        return $t;
    }

    function uninstall_rmp($key) {
        global $_db;
        $_db->sql('select name from ' . T_REACTOR_MODULE . ' where pk_module=' . $key);
        $t = $_db->line();
        uninstall_rmp($t['name']);
    }

    function create_rmp($key) {
        global $_db;
        $_db->sql('select name from ' . T_REACTOR_MODULE . ' where pk_module=' . $key);
        $t = $_db->line();
        create_rmp($t['name']);
    }
}//end of class

?>