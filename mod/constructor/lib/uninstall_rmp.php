<?php

include_once LIB_DIR . 'config_write.php';

function uninstall_rmp($mod_name)
{
    global $_db, $_languages, $_reactor;
    
    $_db->sql('select * from `' . T_REACTOR_MODULE . '` where name="' . $mod_name . '"');
    $module = $_db->line();
    if ($module == 0) {
        return 101;
    }
    
    initModule($mod_name);
    if ($module['uninstall'] != '') {
        eval($module['uninstall']);
    }
    uninitModule();
    
    $pk_module = $module['pk_module'];
    
    $interfaces = array(0);
    $_db->sql('select * from ' . T_REACTOR_INTERFACE . ' where fk_module=' . $pk_module);
    while ($t = $_db->line()) {
        $interfaces[] = $t['pk_interface'];
    }
    
    $_db->sql('select * from `' . T_REACTOR_TABLE . '` where fk_module=' . $pk_module);
    $tables = $_db->matr();
    
    $_db->sql(
        'delete from `' . T_REACTOR_INTERFACE_DEFINE . '` where fk_interface in(' . implode(
            ',',
            $interfaces
        ) . ')'
    );
    $_db->sql(
        'delete from `' . T_REACTOR_INTERFACE_ACTION . '` where fk_interface in(' . implode(
            ',',
            $interfaces
        ) . ')'
    );
    $_db->sql('delete from `' . T_REACTOR_INTERFACE . '` where fk_module=' . $pk_module);
    $_db->sql('delete from `' . T_REACTOR_TABLE . '` where fk_module=' . $pk_module);
    $_db->sql('delete from `' . T_REACTOR_RESOURCE . '` where fk_module=' . $pk_module);
    $_db->sql('delete from `' . T_REACTOR_BASE_TYPE . '` where fk_module=' . $pk_module);
    $_db->sql('delete from `' . T_REACTOR_CONFIG . '` where fk_module=' . $pk_module);
    
    foreach ($tables as $item) {
        if ($item['mlng'] == 1) {
            foreach ($_languages as $lng => $v) {
                $_db->sql('drop table `' . $item['db_name'] . '_' . $lng . '`');
            }
        } else {
            $_db->sql('drop table `' . $item['db_name'] . '`');
        }
    }
    
    $_db->sql('delete from `' . T_REACTOR_MODULE . '` where name="' . $mod_name . '"');
    
    tablesCompile();
    interfacesCompile();
    baseTypeCompile();
    autoexecCompile();
    configCompile();
    resourceCompile();
    guestUserCompile();
    siteTreeCompile();
    
    return 1;
}
