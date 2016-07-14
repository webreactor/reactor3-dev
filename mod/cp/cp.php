<?php

function configuratorsUrl($configurators)
{
    global $_RGET;
    $r = '';
//print_r($configurators);die();
    foreach ($configurators as $item) {
        if (isset($_RGET[$item])) {
            $r .= $item . '=' . $_RGET[$item] . '&';
        }
    }
    if (isset($_RGET['cp_wild'])) {
        $r .= 'cp_wild=1&';
    }
    
    return $r;
}

class cp
{
    var $action;
    var $interface_name;
    
    function show($interface_name, $action_name)
    {
        global $_user;
        if ($_user['login'] != 'root') {
            ini_set('error_log', SITE_DIR . '../cp.log');
            error_log($_SERVER['REMOTE_ADDR'] . ' [' . $_user['login'] . '] > ' . $_SERVER['REQUEST_URI']);
        }
        
        $this->interface_name = $interface_name;
        $this->action         = $action_name;
        global $Gekkon, $_interfaces, $_reactor;
        $object = new reactor_interface($interface_name);
        
        $_obj_data = $object->get();
        
        if (!isset($_obj_data['action'][$action_name])) {
            $Gekkon->display('login.tpl');
            die();
        }
        $_action =& $_obj_data['action'][$action_name];
        
        $configurators                 = configuratorsUrl($object->get('configurators'));
        $Gekkon->data['configurators'] =& $configurators;
        $Gekkon->data['cp_pool_id']    = $this->_pool_id;
        $Gekkon->data['exec_pool_id']  = $object->_pool_id;
        $Gekkon->data['main_pool_id']  = $object->_pool_id;
        $Gekkon->data['action']        = $_action;
        $Gekkon->data['help']          = $this->help($interface_name, $action_name);
        
        $data = 0;
        if ($_action['method'] != '') {
            $data = $object->action($action_name, $null);
        }
        
        if ($_action['tpl_param'] != '') {
            eval('$_action["tpl_param"]= ' . $_action['tpl_param'] . ';');
        }
        
        if ($_action['handler'] > 0) {
            if ($_action['tpl_param'] != '') {
                $configurators .= $_action['tpl_param'] . '&';
            }
            
            if ($_action['handler'] == 1) {
                if ($_action['cptpl'] != '') {
                    header(
                        'Location: ' . SITE_URL . $_reactor['language_url'] . 'cp/' . $_action['cptpl'] . '/' . $_action['cptpl_mod'] . '/?' . $configurators
                    );
                } else {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                die();
            }
            
            if ($_action['handler'] == 2) {
                $stored_id = $object->store();
                if ($_action['cptpl'] != '') {
                    header(
                        'Location: ' . SITE_URL . $_reactor['language_url'] . 'cp/' . $_action['cptpl'] . '/' . $_action['cptpl_mod'] . '/?_so=' . $stored_id . '&' . $configurators
                    );
                } else {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                die();
            }
        }
        
        return $data;
    }
    
    function menu()
    {
        global $_db, $_user;
        if ($_user['ugroup']['name'] != 'root') {
            $_db->sql(
                'select a.* from ' . T_REACTOR_INTERFACE_ACTION . ' a,' . T_REACTOR_UGROUP_ACTION . ' g where a.public=3 and g.fk_action=a.pk_action and g.fk_ugroup=' . $_user['fk_ugroup'] . ' order by a.sort'
            );
        } else {
            $_db->sql('select * from ' . T_REACTOR_INTERFACE_ACTION . ' where public=3 order by sort');
        }
        
        $tree      = new basic_tree(T_REACTOR_INTERFACE_ACTION, 'pk_action', 'fk_action', 'sort');
        $tree->img = $_db->matr('pk_action');
        
        return $tree;
    }
    
    function path()
    {
        global $Gekkon, $_db;
        
        $_db->sql(
            'select a.pk_action,i.name as interface_name from ' . T_REACTOR_INTERFACE_ACTION . ' as a, ' . T_REACTOR_INTERFACE . ' as i where a.fk_interface=i.pk_interface and a.name="' . $this->action . '" and i.name="' . $this->interface_name . '"'
        );
        $t = $_db->line();
        
        $method_tree = new basic_tree(T_REACTOR_INTERFACE_ACTION, 'pk_action', 'fk_action', 'sort');
        $method_tree->createImage('', '`call`');

//index.php?show={$classes.$item&fk_class}&action={$item.name}
        
        return $method_tree->pathToNode($t['pk_action']);
    }
    
    function description($key)
    {
        global $_db;
        $_db->sql('select description from ' . T_REACTOR_INTERFACE_ACTION . ' where pk_action=' . $key);
        $t = $_db->line();
        if ($t == 0) {
            return '';
        }
        
        return $t['description'];
    }
    
    function help($interface, $action)
    {
        global $_db;
        $_db->sql(
            'select pk_help from ' . T_REACTOR_HELP . ' where interface="' . $interface . '" and action="' . $action . '"'
        );
        $t = $_db->line();
        if ($t == 0) {
            return '';
        }
        
        return $t['pk_help'];
    }
}
