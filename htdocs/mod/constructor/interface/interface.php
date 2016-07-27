<?php

require_once LIB_DIR . 'config_write.php';

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

            $_db->sql(
                'DELETE ' . T_REACTOR_INTERFACE . '
                FROM ' . T_REACTOR_INTERFACE . '
                LEFT JOIN ' . T_REACTOR_MODULE . ' i 
                ON ' . T_REACTOR_INTERFACE . '.fk_module = i.pk_module
                WHERE pk_module IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_TABLE . ' 
                FROM ' . T_REACTOR_TABLE . ' 
                LEFT JOIN ' . T_REACTOR_MODULE . ' i 
                ON ' . T_REACTOR_TABLE . '.fk_module = i.pk_module 
                WHERE pk_module IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_CONFIG . ' 
                FROM ' . T_REACTOR_CONFIG . '
                LEFT JOIN ' . T_REACTOR_MODULE . ' i
                ON ' . T_REACTOR_CONFIG . '.fk_module = i.pk_module
                WHERE pk_module IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_RESOURCE . '
                FROM ' . T_REACTOR_RESOURCE . '
                LEFT JOIN ' . T_REACTOR_MODULE . ' i
                ON ' . T_REACTOR_RESOURCE . '.fk_module = i.pk_module
                WHERE pk_module IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_BASE_TYPE . '
                FROM ' . T_REACTOR_BASE_TYPE . '
                LEFT JOIN ' . T_REACTOR_MODULE . ' i
                ON ' . T_REACTOR_BASE_TYPE . ' .fk_module = i.pk_module
                WHERE pk_module IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_INTERFACE_ACTION . '
                FROM ' . T_REACTOR_INTERFACE_ACTION . '
                LEFT JOIN ' . T_REACTOR_INTERFACE . ' i
                ON ' . T_REACTOR_INTERFACE_ACTION . '.fk_interface = i.pk_interface
                WHERE pk_interface IS NULL'
            );

            $_db->sql(
                'DELETE ' . T_REACTOR_INTERFACE_DEFINE . '
                FROM ' . T_REACTOR_INTERFACE_DEFINE . '
                LEFT JOIN ' . T_REACTOR_INTERFACE . ' i
                ON ' . T_REACTOR_INTERFACE_DEFINE . '.fk_interface = i.pk_interface
                WHERE pk_interface IS NULL'
            );
        }
    }
}
