<?php
//version 2.0.1
include_once LIB_DIR . 'reactor_ver.php';
function create_rmp($mod_name)
{
    global $_db, $_languages, $_reactor;
    $_db->sql('select * from `' . T_REACTOR_MODULE . '` where name="' . $mod_name . '"');
    $module = array();
    $t = $_db->line();
    if ($t == 0) {
        return 101;
    }

    if ($t['depend'] == '') {
        $t['depend'] = reactor_ver();
    }

    $module['item'] = $t;
    $pk_module = $module['item']['pk_module'];

    $_db->sql('select * from `' . T_REACTOR_INTERFACE . '` where fk_module=' . $pk_module);
    $module['interface']['item'] = $_db->matr('pk_interface');

    $keys = array_keys($module['interface']['item']);
    $keys[] = 0;

    $_db->sql('select * from `' . T_REACTOR_INTERFACE . '`');
    $interface_dict = $_db->matr('pk_interface', 'name');

    $_db->sql('select * from `' . T_REACTOR_INTERFACE_DEFINE . '` where fk_interface in(' . implode(',', $keys) . ')');
    $module['interface']['define'] = $_db->matr();

    $_db->sql('select * from `' . T_REACTOR_INTERFACE_ACTION . '` where fk_interface in(' . implode(',',
            $keys) . ') order by fk_action');
    $module['interface']['action'] = $_db->matr();

    $_db->sql('
select m.fk_interface as interface ,m.name as action,fm.fk_interface as fm_interface,fm.name as fm_action from
`' . T_REACTOR_INTERFACE_ACTION . '` m,`' . T_REACTOR_INTERFACE_ACTION . '` fm
where
m.fk_action=fm.pk_action
and m.fk_interface in(' . implode(',', $keys) . ')');
    $module['interface']['action_link'] = array();
    while ($t = $_db->line()) {
        $module['interface']['action_link'][$interface_dict[$t['interface']] . '_' . $t['action']] = $interface_dict[$t['fm_interface']] . '_' . $t['fm_action'];
    }

    $_db->sql('select * from `' . T_REACTOR_TABLE . '` where fk_module=' . $pk_module);

    $module['table']['item'] = $_db->matr();
    $module['table']['data'] = array();
    $module['table']['create'] = array();
    foreach ($module['table']['item'] as $item) {
        if ($item['mlng'] == 1) {
            foreach ($_languages as $lng => $v) {
                $_db->sql('show create table `' . $item['db_name'] . '_' . $lng . '`');
                $t = $_db->line();
                if ($t != 0) {
                    $module['table']['create'][$item['db_name'] . '_' . $lng] = preg_replace('/AUTO_INCREMENT=[\w]+/i',
                        '', $t['Create Table']);
                }
                if ($item['rmp_data'] == 1) {
                    $_db->sql('select * from `' . $item['db_name'] . '_' . $lng . '`');
                    $module['table']['data'][$item['db_name'] . '_' . $lng] = $_db->matr();
                }
            }
        } else {
            $_db->sql('show create table `' . $item['db_name'] . '`');
            $t = $_db->line();
            $module['table']['create'][$item['db_name']] = preg_replace('/AUTO_INCREMENT=[\w]+/i', '',
                $t['Create Table']);
            if ($item['rmp_data'] == 1) {
                $_db->sql('select * from `' . $item['db_name'] . '`');
                $module['table']['data'][$item['db_name']] = $_db->matr();
            }
        }
    }

    $_db->sql('select * from `' . T_REACTOR_RESOURCE . '` where fk_module=' . $pk_module);
    $module['resource'] = $_db->matr();

    $_db->sql('select * from `' . T_REACTOR_CONFIG . '` where fk_module=' . $pk_module);
    $module['config'] = $_db->matr();

    $_db->sql('select * from `' . T_REACTOR_BASE_TYPE . '` where fk_module=' . $pk_module);
    $module['base_type'] = $_db->matr();

    initModule($mod_name);

    $f = fopen($_reactor['module']['dir'] . 'module.rmp', 'w');
    fwrite($f, serialize($module));
    fclose($f);
//print_r($module);
    uninitModule();
}//end of function create_rmp
?>