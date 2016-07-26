<?php

namespace reactor;

class config_write
{
    public static function tablesCompile()
    {
        reactor_trace('tablesCompile');

        global $_db, $_languages;

        $query = $_db->select(T_REACTOR_MODULE);

        $module = array();

        while ($t = $query->line()) {
            $module[$t['pk_module']] = $t;
        }

        $fw = fopen(ETC_DIR . 'tables.php', 'w');

        fputs($fw, "<?php\n");

        $buff = '// Last compilation ' . date("Y-m-d H:i:s") . "\n";
        fputs($fw, $buff);

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_TABLE . ' ORDER BY fk_module');

        while ($t = $query->line()) {
            fputs(
                $fw,
                "define('T_" . strtoupper($module[$t['fk_module']]['name'] . '_' . $t['name']) . "','" . $t['db_name']
            );
            if ($t['mlng'] == 1) {
                fputs($fw, "_'.\$_reactor['language']);\n");
                foreach ($_languages as $k => $v) {
                    fputs(
                        $fw,
                        "define('T_" . strtoupper(
                            $module[$t['fk_module']]['name'] . '_' . $t['name']
                        ) . '_' . strtoupper(
                            $k
                        ) . "','" . $t['db_name'] . '_' . strtolower($k) . "');\n"
                    );
                }
            } else {
                $buff = "');\n";
                fputs($fw, $buff);
            }
        }

        fputs($fw, "\n?>");
        fclose($fw);
    }

    public static function groupInterfacesCompile($ugroup)
    {
        reactor_trace('groupInterfacesCompile');
        global $_db;

        $rez        = array();
        $interfaces = array();

        $query = $_db->select(T_REACTOR_UGROUP_ACTION, array('fk_ugroup' => $ugroup['pk_ugroup']));

        $perm = $query->matr('fk_action', 'fk_action');

        $query = $_db->sql(
            'SELECT
            i.pk_interface,
            i.name,
            i.configurators,
            i.class,
            i.source,
            i.pkey,
            i.constructor,
            m.name AS module
        FROM
            ' . T_REACTOR_INTERFACE . ' i,
            ' . T_REACTOR_MODULE . ' m
        WHERE i.fk_module = m.pk_module'
        );

        while ($t = $query->line()) {
            $interfaces[$t['pk_interface']] = $t['name'];

            unset($t['pk_interface']);

            $rez[$t['name']]           = $t;
            $rez[$t['name']]['define'] = array();
            $rez[$t['name']]['action'] = array();

            if ($t['configurators'] != '') {
                $t['configurators'] = str_replace(' ', '', $t['configurators']);

                $rez[$t['name']]['configurators'] = explode(',', $t['configurators']);
            } else {
                $rez[$t['name']]['configurators'] = array();
            }
        }

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_INTERFACE_DEFINE . ' ORDER BY sort');

        while ($t = $query->line()) {
            $tt = $interfaces[$t['fk_interface']];

            unset($t['pk_define']);
            unset($t['fk_interface']);
            unset($t['sort']);

            $rez[$tt]['define'][$t['name']] = $t;
        }

        $query = $_db->sql('SELECT * FROM ' . T_REACTOR_INTERFACE_ACTION . ' ORDER BY sort');

        while ($t = $query->line()) {
            if (isset($perm[$t['pk_action']]) || $ugroup['name'] == 'root') {
                $tt = $interfaces[$t['fk_interface']];

                unset($t['fk_interface']);
                unset($t['pk_action']);
                unset($t['fk_action']);
                unset($t['sort']);

                $rez[$tt]['action'][$t['name']] = $t;
            }
        }

        resourceStore('reactor_interfaces_' . $ugroup['name'], $rez);
    }

    public static function interfacesCompile()
    {
        global $_db;

        $query = $_db->select(T_REACTOR_UGROUP);

        $ugroups = $query->matr();

        foreach ($ugroups as $t) {
            self::groupInterfacesCompile($t);
        }
    }

    public static function autoexecCompile()
    {
        reactor_trace('autoexecCompile');

        global $_db;

        $query = $_db->sql('select * from ' . T_REACTOR_MODULE);

        $buff = '// Last compilation ' . date("Y-m-d H:i:s") . "\n";

        while ($t = $query->line()) {
            if ($t['to_core'] != '') {
                $buff .= '// Module ' . $t['name'] . "\n";
                $buff .= $t['to_core'] . "\n";
            }
        }

        $fw = fopen(ETC_DIR . 'autoexec.php', 'w');

        fputs($fw, "<?php\n" . $buff . "\n?>");

        fclose($fw);
    }

    public static function configCompile()
    {
        reactor_trace('configCompile');

        global $_db;

        $query = $_db->select(T_REACTOR_MODULE);

        $module = array();

        while ($t = $query->line()) {
            $module[$t['pk_module']] = $t['name'];
        }

        $query = $_db->sql('SELECT *  FROM ' . T_REACTOR_CONFIG . ' ORDER BY `group`');

        $t = $query->matr();

        $r = array();

        foreach ($t as $item) {
            $r[$item['fk_module']][$item['group']][] = $item;
        }

        $f = fopen(ETC_DIR . 'config.php', 'w');

        fwrite($f, "<?php\n// Last compilation " . date("Y-m-d H:i:s") . "\n");

        foreach ($r as $fk_module => $m) {
            fwrite(
                $f,
                "\n//--------------------------------------------------------------\n//Module " . $module[$fk_module] . "\n"
            );

            foreach ($m as $key => $g) {
                fwrite($f, "\n//Property group $key\n");

                foreach ($g as $l) {
                    if ($l['descrip'] != '') {
                        $l['descrip'] = '//' . $l['descrip'];
                    }

                    fwrite(
                        $f,
                        'define(\'' . strtoupper(
                            $module[$fk_module] . '_' . $l['name']
                        ) . '\',' . $l['value'] . ');' . $l['descrip'] . "\n"
                    );
                }
            }
        }

        fwrite($f, '?>');
        fclose($f);
    }

    public static function resourceCompile()
    {
        reactor_trace('resourceCompile');
        global $_db;

        $query = $_db->select(T_REACTOR_MODULE);

        $module = array();

        while ($t = $query->line()) {
            $module[$t['pk_module']] = $t['name'];
        }

        $query = $_db->select(T_REACTOR_RESOURCE);

        $rez = array();

        while ($t = $query->line()) {
            $rez[$module[$t['fk_module']] . '_' . $t['name']] = $t;
        }

        resourceStore('reactor_resource', $rez);
    }

    public static function baseTypeCompile()
    {
        reactor_trace('baseTypeCompile');

        global $_db;

        $rez = array();

        $query = $_db->sql(
            'SELECT
            t.*,
            m.name AS mod_name
        FROM
            ' . T_REACTOR_BASE_TYPE . ' t,
            ' . T_REACTOR_MODULE . ' m
        WHERE t.fk_module = m.pk_module'
        );

        while ($t = $query->line()) {
            unset($t['pk_base_type']);
            unset($t['fk_module']);
            unset($t['call']);

            $ttt = $t['name'];

            unset($t['name']);

            $rez[$ttt] = $t;
        }

        resourceStore('reactor_base_types', $rez);
    }

    public static function guestUserCompile()
    {
        reactor_trace('guestUserCompile');

        global $_user;

        $_user_save = $_user;

        $login = 'guest';

        userLogin($login, $login, $login);

        resourceStore('reactor_guest_user', $_user);

        $_user = $_user_save;
    }

    public static function siteTreeCompile()
    {
        global $_db;

        $query = $_db->sql('SELECT * FROM ' . T_SITE_TREE . ' ORDER BY sort');

        $_tree = $query->matr();

        $_site_tree_param = array();
        $_site_nodes      = array();

        $_site_tree = self::siteTreeCompile_r($_tree, 0, '', $_site_tree_param, $_site_nodes);

        $_site_tree['param'] = $_site_tree_param;
        $_site_tree['nodes'] = $_site_nodes;

        resourceStore('reactor_site_tree', $_site_tree);
    }

    public static function siteTreeCompile_r(&$t, $fk, $path, &$root, &$nodes)
    {
        $r = array('#key' => $fk);

        $ct = count($t);

        for ($i = 0; $i < $ct; $i++) {
            $item = &$t[$i];

            if ($item['fk_site_tree'] == $fk) {
                $param = '';
                $p_cnt = 0;

                if ($item['param'] != '') {
                    $param = explode(';', $item['param']);
                    $p_cnt = count($param);
                }

                unset($item['param']);
                unset($item['sort']);

                if ($item['name'] != 'index') {
                    $t_path = $path . $item['name'] . '/';
                } else {
                    $t_path = '/';
                }

                if (!isset($root[$t_path])) {
                    $root[$t_path]['max']    = $p_cnt;
                    $root[$t_path]['min']    = $p_cnt;
                    $root[$t_path]['minkey'] = $item['pk_site_tree'];
                    $root[$t_path]['maxkey'] = $item['pk_site_tree'];
                }

                $root[$t_path][$p_cnt]        = $param;
                $root[$t_path][$p_cnt]['key'] = $item['pk_site_tree'];

                if ($root[$t_path]['max'] < $p_cnt) {
                    $root[$t_path]['max']    = $p_cnt;
                    $root[$t_path]['maxkey'] = $item['pk_site_tree'];
                }

                //remove $r[$item['name']]['#key']=$item['pk_site_tree']; path strongly by nodes with min param

                if ($root[$t_path]['min'] > $p_cnt) {
                    $root[$t_path]['min']     = $p_cnt;
                    $root[$t_path]['minkey']  = $item['pk_site_tree'];
                    $r[$item['name']]['#key'] = $item['pk_site_tree'];
                }

                $nodes[$item['pk_site_tree']] = $item;

                if (!isset($r[$item['name']])) {
                    $r[$item['name']] = array();
                }

                $r[$item['name']] += self::siteTreeCompile_r($t, $item['pk_site_tree'], $t_path, $root, $nodes);
            }
        }

        return $r;
    }

    public static function siteModified($where = '1')
    {
        global $_db;

        $_db->sql('UPDATE ' . T_SITE_TREE . ' SET modified = ' . time() . ' WHERE modified != "none" AND ' . $where);

        self::siteTreeCompile();
    }
}
