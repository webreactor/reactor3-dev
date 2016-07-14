<?php

include_once LIB_DIR . 'config_write.php';

class reactor_interface_edit extends basic_object
{
    function store($form)
    {
        $t = basic_object::store($form);
        interfacesCompile();

        return $t;
    }

    function delete($rule, $isStream = 0)
    {
        global $_db;
        $r = $this->getOne($rule);
        if ($r != 0) {
            basic_object::delete($rule, $isStream);
            $_db->sql('delete `' . T_REACTOR_INTERFACE . '`  FROM `' . T_REACTOR_INTERFACE . '` left join `' . T_REACTOR_MODULE . '` i on `' . T_REACTOR_INTERFACE . '`.fk_module=i.pk_module where pk_module is null;');
            $_db->sql('delete `' . T_REACTOR_TABLE . '`  FROM `' . T_REACTOR_TABLE . '` left join `' . T_REACTOR_MODULE . '` i on `' . T_REACTOR_TABLE . '`.fk_module=i.pk_module where pk_module is null;');
            $_db->sql('delete `' . T_REACTOR_CONFIG . '`  FROM `' . T_REACTOR_CONFIG . '`  left join `' . T_REACTOR_MODULE . '` i on `' . T_REACTOR_CONFIG . '`.fk_module=i.pk_module where pk_module is null;');
            $_db->sql('delete `' . T_REACTOR_RESOURCE . '`  FROM `' . T_REACTOR_RESOURCE . '`  left join `' . T_REACTOR_MODULE . '` i on `' . T_REACTOR_RESOURCE . '`.fk_module=i.pk_module where pk_module is null;');
            $_db->sql('delete `' . T_REACTOR_BASE_TYPE . '`  FROM `' . T_REACTOR_BASE_TYPE . '`  left join `' . T_REACTOR_MODULE . '` i on `' . T_REACTOR_BASE_TYPE . '` .fk_module=i.pk_module where pk_module is null;');
            $_db->sql('delete `' . T_REACTOR_INTERFACE_ACTION . '`  FROM `' . T_REACTOR_INTERFACE_ACTION . '` left join `' . T_REACTOR_INTERFACE . '` i on `' . T_REACTOR_INTERFACE_ACTION . '`.fk_interface=i.pk_interface where pk_interface is null;');
            $_db->sql('delete `' . T_REACTOR_INTERFACE_DEFINE . '`  FROM `' . T_REACTOR_INTERFACE_DEFINE . '` left join `' . T_REACTOR_INTERFACE . '` i on `' . T_REACTOR_INTERFACE_DEFINE . '`.fk_interface=i.pk_interface where pk_interface is null;');
        }
    }
}//end of class

?>