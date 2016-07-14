<?php
//version 2.0.1
include_once LIB_DIR . 'reactor_ver.php';
include_once LIB_DIR . 'config_write.php';

function install_rmp($mod_name)
{
    global $_db, $_languages, $_reactor, $_log;
    $ca = new content_adapter();
    initModule($mod_name);
    if (!is_file($_reactor['module']['dir'] . 'module.rmp')) {
        uninitModule();

        return 0;
    }

    $module = unserialize(file_get_contents($_reactor['module']['dir'] . 'module.rmp'));
    uninitModule();

    if ($module['item']['depend'] != 'none') {
        $r = reactor_ver_depend($module['item']['depend'], $t);
        $_log .= var_export($t, true);

//if(!$r)
//return 0;
    }

    $_db->sql('show tables');
    while ($t = $_db->line()) {
        $current_tables[current($t)] = 1;
    }

    if (!isset($current_tables[T_REACTOR_MODULE]) && $mod_name != 'reactor') {
        return 0;
    }

    if (isset($current_tables[T_REACTOR_MODULE])) {
        $_db->sql('select * from `' . T_REACTOR_MODULE . '` where name="' . $module['item']['name'] . '"');
        if ($_db->line() != 0) {
            return 0;
        }
    }

    foreach ($module['table']['create'] as $item) {
        $_db->sql($item);
    }

    foreach ($module['table']['data'] as $table_name => $data) {
        $db_link = new basic_object($table_name, '');
        foreach ($data as $item) {
            $db_link->insert($ca->toDB($item));
        }
    }

    $db_link = new basic_object(T_REACTOR_MODULE, 'pk_module');
    unset($module['item']['pk_module']);
    $pk_module = $db_link->insert($ca->toDB($module['item']));

    $interfaces = array();

    $db_link = new basic_object(T_REACTOR_INTERFACE, 'pk_interface');
    foreach ($module['interface']['item'] as $item) {
        $t = $item['pk_interface'];
        unset($item['pk_interface']);
        $item['fk_module'] = $pk_module;
        $interfaces[$t] = $db_link->insert($ca->toDB($item));
    }

    $db_link = new basic_object(T_REACTOR_INTERFACE_DEFINE, 'pk_define');
    foreach ($module['interface']['define'] as $item) {
        $item['fk_interface'] = $interfaces[$item['fk_interface']];
        unset($item['pk_define']);
        $db_link->insert($ca->toDB($item));
    }

    $db_link = new basic_object(T_REACTOR_INTERFACE_ACTION, 'pk_action');

    foreach ($module['interface']['action'] as $item) {
        unset($item['pk_action']);
        $item['fk_action'] = 0;
        $item['fk_interface'] = $interfaces[$item['fk_interface']];
        $t = $db_link->insert($ca->toDB($item));
    }

    $_db->sql('SELECT m.pk_action,m.name as fm_action,i.name as fm_interface FROM `' . T_REACTOR_INTERFACE_ACTION . '` m, `' . T_REACTOR_INTERFACE . '` i where m.fk_interface=i.pk_interface');
    $named_actions = array();
    while ($t = $_db->line()) {
        $named_actions[$t['fm_interface'] . '_' . $t['fm_action']] = $t['pk_action'];
    }

    foreach ($module['interface']['action_link'] as $k => $item) {
        $t = array('fk_action' => $named_actions[$item]);
        $db_link->update($ca->toDB($t), $named_actions[$k]);
    }

    $db_link = new basic_object(T_REACTOR_TABLE, 'name');
    foreach ($module['table']['item'] as $item) {
        unset($item['pk_table']);
        $item['fk_module'] = $pk_module;
        $db_link->insert($ca->toDB($item));
    }

    $db_link = new basic_object(T_REACTOR_RESOURCE, 'pk_resource');
    foreach ($module['resource'] as $item) {
        unset($item['pk_resource']);
        $item['fk_module'] = $pk_module;
        $db_link->insert($ca->toDB($item));
    }

    $db_link = new basic_object(T_REACTOR_CONFIG, 'pk_config');
    foreach ($module['config'] as $item) {
        unset($item['pk_config']);
        $item['fk_module'] = $pk_module;
        $db_link->insert($ca->toDB($item));
    }

    $db_link = new basic_object(T_REACTOR_BASE_TYPE, 'pk_base_type');
    foreach ($module['base_type'] as $item) {
        unset($item['pk_base_type']);
        $item['fk_module'] = $pk_module;
        $db_link->insert($ca->toDB($item));
    }

    initModule($mod_name);
    if ($module['item']['install'] != '') {
        eval($module['item']['install']);
    }
    uninitModule();

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
