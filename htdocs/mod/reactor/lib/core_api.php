<?php

//namespace reactor;

use mod\reactor\lib\UploadHandler\FileUploadHandler;

require_once('UploadHandler/UploadHandler.php');
require_once('UploadHandler/FileUploadHandler.php');
/*
version 1.2.6
reactor core api library
*/

//----------------------------------------------------------------------------------------
//core support functions

function initModule($mod_name)
{
    global $_reactor, $Gekkon;
    reactor_trace('initModule ' . $mod_name);
    
    array_push($_reactor['module']['stack'], $_reactor['module']['name']);
    $_reactor['module']['name'] = $mod_name;
    $_reactor['module']['url']  = MOD_URL . $mod_name . '/';
    $_reactor['module']['dir']  = MOD_DIR . $mod_name . '/';
    $Gekkon->template_path      = $mod_name . '/tpl/';
}

function uninitModule()
{
    global $_reactor, $Gekkon;
    reactor_trace('uninitModule ' . $_reactor['module']['name']);
    
    $mod_name                   = array_pop($_reactor['module']['stack']);
    $_reactor['module']['name'] = $mod_name;
    $_reactor['module']['url']  = MOD_URL . $mod_name . '/';
    $_reactor['module']['dir']  = MOD_DIR . $mod_name . '/';
    $Gekkon->template_path      = $mod_name . '/tpl/';
    
    return $mod_name;
}

function reactor_ini_set($module, $name, $value)
{
    if (constant(strtoupper($module . '_' . $name)) == $value) {
        return;
    }
    global $_db;
    $_db->sql(
        'update ' . T_REACTOR_CONFIG . ' c,' . T_REACTOR_MODULE . ' m set c.`value`="' . $value . '" where c.fk_module=m.pk_module and m.`name`="' . $module . '" and c.`name`="' . $name . '"'
    );
    
    require_once LIB_DIR . 'config_write.php';

    configCompile();
}

function reactor_ini_set_array($array)
{
    global $_db;
    $up = 0;
    foreach ($array as $ini) {
        if (constant(strtoupper($ini['module'] . '_' . $ini['name'])) == $ini['value']) {
            continue;
        }
        $up = 1;
        $_db->sql(
            'update ' . T_REACTOR_CONFIG . ' c,' . T_REACTOR_MODULE . ' m set c.`value`="' . $ini['value'] . '" where c.fk_module=m.pk_module and m.`name`="' . $ini['module'] . '" and c.`name`="' . $ini['name'] . '"'
        );
    }
    if ($up == 0) {
        return;
    }
    require_once LIB_DIR . 'config_write.php';

    configCompile();
}

function stop($msg = '')
{
    global $Gekkon, $_log;
    reactor_trace($_log .= ' stop ' . $msg);
    
    initModule('site');
    if ($msg != '') {
        global $_reactor;
        $_reactor['show']['interface'] = '';
        $_reactor['show']['action']    = '';
        $_reactor['show']['module']    = 'site';
        
        if (substr($msg, 0, 3) == '404') {
            $_reactor['show']['template'] = '404.tpl';
            
            if (!headers_sent()) {
                header('HTTP/1.1 404 Not Found');
            }
        } else {
            $_reactor['show']['template'] = 'message.tpl';
        }
        
        $Gekkon->register('msg', $msg);
        $Gekkon->display('index.tpl');
    } else {
        header('Location: ' . SITE_URL);
    }
    
    die();
}

function reactor_trace($msg)
{
    global $_mctime, $_user;
    if (isset($_GET['debug']) && $_user['ugroup']['name'] == 'root') {
        echo microtime(true) - $_mctime, ' - ', $msg, "\n";
        if (isset($_SERVER['REQUEST_METHOD'])) {
            echo '<br>';
        }
    }
}

function reactor_error($msg, $lvl = 0)
{
    global $_user;
    error_log($msg);
    //die();
}

function admin_error($v)
{
}

//----------------------------------------------------------------------------------------
//interface support functions

function execute($name_or_pool_id = '', $action_name = '', $template = '', $tpl_module = '')
{
    global $Gekkon;
    
    reactor_trace('start execute ' . $name_or_pool_id . '->' . $action_name . ' to ' . $tpl_module . '/' . $template);
    $data = 0;
    if ($name_or_pool_id != '') {
        $object = new reactor_interface($name_or_pool_id);
        
        if ($action_name != '') {
            $null = '';
            $_obj =& $object->get('action');
            if (!isset($_obj[$action_name])) {
                reactor_trace('undefined action');
                
                return 0;
            }
            $data = $object->action($action_name, $null);
            @$_save_data = $Gekkon->data['exec_data'];
            @$_save_pool_id = $Gekkon->data['exec_pool_id'];
            $Gekkon->data['exec_data']    = $data;
            $Gekkon->data['exec_pool_id'] = $object->_pool_id;
        }
    }
    
    if ($tpl_module != '') {
        initModule($tpl_module);
    }
    if ($template != '') {
        $Gekkon->display($template);
    }
    if ($tpl_module != '') {
        uninitModule();
    }
    
    if ($name_or_pool_id != '' && $action_name != '') {
        $Gekkon->data['exec_data']    = $_save_data;
        $Gekkon->data['exec_pool_id'] = $_save_pool_id;
    }
    
    reactor_trace('end execute');
    
    return $data;
}

function pool_new()
{
    static $_global_pool_cnt = 0;
    $_global_pool_cnt++;
    $GLOBALS['_pool'][$_global_pool_cnt] = '';
    
    return $_global_pool_cnt;
}

function &pool_get($_pool_id, $name = '')
{
    if (!isset($GLOBALS['_pool'][$_pool_id])) {
        reactor_error('undefined _pool_id ' . $_pool_id . ' in pool_get');
    }
    if ($name == '') {
        return $GLOBALS['_pool'][$_pool_id];
    }
    
    return $GLOBALS['_pool'][$_pool_id][$name];
}

/**
 * @param $_pool_id
 *
 * @return \content_adapter
 */
function &pool_create_content_adapter($_pool_id)
{
    if (!isset($GLOBALS['_pool'][$_pool_id])) {
        reactor_error('undefined _pool_id ' . $_pool_id . ' in pool_get_content_adapter');
    }
    $i_ca = new reactor_interface('content_adapter');
    $ca   =& $GLOBALS['_pool'][$i_ca->_pool_id]['object'];
    $ca->configure($GLOBALS['_pool'][$_pool_id]['define']);
    
    return $ca;
}

function &tryGetForm($_so)
{
    $form_container = new reactor_interface();
    $form           = 0;
    if ($form_container->isStored($_so)) {
        $form_container->restore($_so);
        $form =& $form_container->get('object');
        $form_container->store($form->form_session);
    }
    
    return $form;
}

function handleForm($_so = '', $interface = 'none', $action = 'none')
{
    global $Gekkon, $_RGET, $_reactor;
    $object         = 'none';
    $form_container = new reactor_interface();
    if ($form_container->isStored($_so)) {
        $form_container->restore($_so);
    } else {
        $object         = new reactor_interface($interface);
        $form_container = new reactor_interface($object->action($action, $_reactor));
    }
    
    $form =& $form_container->get('object');
    
    if (get_magic_quotes_gpc() && isset($_POST)) {
        $data = arrayMapRecursive('stripslashes', $_POST);
    } else {
        $data = $_POST;
    }
    
    /*$data=preg_replace('/[\x00-\x10\x0B\x0C\x0E-\x1F]/', '', $data);
    $data=preg_replace('/[\xA0]/', ' ', $data);*/
    
    $form->fromForm($data);
    
    if (count($form->error) == 0) {
        if ($object == 'none') {
            $object = new reactor_interface();
            $object->restore($form->stored_id);
        }
        
        if (!$object->action($form->action, $form)) {
            $form->error['_error_on_action'] = 1;
        }
        
        if (count($form->error) == 0) {
            if (is_array($form->forvard_url)) {
                header('Location: ' . compileUrl($form->forvard_url));
            } else {
                header('Location: ' . $form->forvard_url);
            }
            die();
        } else {
            $object->store($form->stored_id);
        }
    }
    
    $fs = $form_container->store($form->form_session);
    
    if (is_array($form->return_url)) {
        $ret        = $form->return_url;
        $ret['_so'] = $fs;
        header('Location: ' . compileUrl($ret));
    } else {
        if (strstr($form->return_url, '?') !== false) {
            header('Location: ' . $form->return_url . '&_so=' . $fs);
        } else {
            header('Location: ' . $form->return_url . '?_so=' . $fs);
        }
    }
    
    die();
}

function createForm($pool_id, $action, $callback, $param = array())
{
    global $_RGET, $_reactor;
    $container = new reactor_interface($pool_id);
    
    $form = new reactor_interface('content_adapter');
    
    $fo =& $form->get('object');
    
    $fo->action = $action;
    
    $fo->cancel_url  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];
    $fo->return_url  = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['HTTP_REFERER']) || $_reactor['show']['name'] == 'ajax_request') ? $_SERVER['HTTP_REFERER'] : $_RGET;
    $fo->forvard_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];
    
    $form_data = $container->action($callback, $param);
    
    if ($form_data != 0) {
        $fo->data = $form_data;
    }
    
    $fo->configure(pool_get($pool_id, 'define'));
    
    $fo->stored_id = $container->store();
    $form->store($fo->form_session);
    
    return $form->_pool_id;
}

function isForm($pool_id, $form_session = 'none', $callback, $param = array())
{
    $container = new reactor_interface($pool_id);
    
    $form = new reactor_interface();
    if ($form_session != 'none' && $form->isStored($form_session)) {
        $form->restore($form_session);
        $form->store($form_session);
        
        return $form->_pool_id;
    }
    
    return $container->action($callback, $param);
}

//----------------------------------------------------------------------------------------
//User functions

function &restoreUser()
{
    global $_user, $_RGET, $_reactor;
    if (session_id() == '') {
        session_start();
        if (isset($_SESSION['_user'])) {
            $_user = $_SESSION['_user'];
        }
        $_SESSION['_user'] =& $_user;
        
        if ($_user['system'] != SITE_URL) {
            $_RGET['logout'] = 1;
        }
        if ($_user['ip'] != $_SERVER['REMOTE_ADDR']) {
            $_RGET['logout'] = 1;
        }
    } else {
        if (isset($_COOKIE['c_uid'])) {
            $_RGET['logout'] = 1;
        } else {
            $_user = resourceRestore('reactor_guest_user');
        }
    }
    
    if (isset($_POST['logout']) || isset($_RGET['logout'])) {
        if (!isset($_POST['cookie'])) {
            $_POST['cookie'] = 0;
        }
        if (!isset($_POST['login'])) {
            $_POST['login'] = '';
        }
        if (!isset($_POST['password'])) {
            $_POST['password'] = '';
        }
        userLogin($_POST['login'], $_POST['password'], $_POST['cookie']);
        
        if (!headers_sent()) {
            if ($_user['login'] != 'guest') {
                if (session_id() == '') {
                    session_start();
                }
                
                $_SESSION['_user'] =& $_user;
            }
            
            if (!isset($_RGET['logout'])) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
                die();
            }
        }
    }
    
    unset($_RGET['logout']);
    
    return $_user;
}

//----------------------------------------------------------------------------------------
//Resource functions
$_resource = array();

function &resource($name)
{
    global $_reactor, $_db, $Gekkon, $_user, $_resource;
    clearstatcache();
    if (!isset($_resource[$name])) {
        if (file_exists(RES_DIR . $name)) {
            $_resource[$name] = resourceRestore($name);
        } else {
            
            $resources = resourceRestore('reactor_resource');
            
            eval($resources[$name]['source']);

            $data = null;
            
            if ($resources[$name]['store']) {
                resourceStore($name, $data);
            }
            
            $_resource[$name] = $data;
        }
    }
    
    return $_resource[$name];
}

function resourceStore($name, &$data)
{
    reactor_trace('resourceStore - ' . $name);
    $f = fopen(RES_DIR . $name, 'w');
    fwrite($f, serialize($data));
    fclose($f);
}

function resourceRestore($name)
{
    reactor_trace('resourceRestore - ' . $name);
    
    return unserialize(file_get_contents(RES_DIR . $name));
}

function resourceClear($name)
{
    reactor_trace('resourceClear - ' . $name);
    @unlink(RES_DIR . $name);
}

//----------------------------------------------------------------------------------------
//IO functions
function getStrChars($str)
{
    $str = preg_replace('/[^\w\pP\pL\s\$]/uis', ' ', $str);
    
    return htmlspecialchars($str, ENT_QUOTES);
}

function inputGetStr($name, $def = false, $stop = '')
{
    global $_RGET, $_SGET;
    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }
    if (isset($_RGET[$name])) {
        $test = trim($_RGET[$name]);
    } else {
        $test = '';
    }
    
    if ($test == '') {
        if ($def !== false) {
            $test = $def;
        } else {
            stop($stop);
        }
    }
    
    $_SGET[$name] = getStrChars($test);
    
    return $_SGET[$name];
}

function inputGetNum($name, $def = '', $stop = '')
{
    global $_RGET, $_SGET;
    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }
    if (isset($_RGET[$name])) {
        $test = trim($_RGET[$name]);
    } else {
        $test = '';
    }
    
    if ($test == '') {
        if ($def !== false) {
            $test = $def;
        } else {
            stop($stop);
        }
    }
    if (!is_numeric($test)) {
        stop($stop);
    }
    
    return $_SGET[$name] = intval($test);
}

function inputGetPath($name, $def = array())
{
    global $_RGET, $_SGET;
    if (isset($_SGET[$name])) {
        return $_SGET[$name];
    }
    if (isset($_RGET[$name])) {
        $test = $_RGET[$name];
    } else {
        $test = $def;
    }
    
    return $_SGET[$name] = array_map('getStrChars', $test);
}

function handleUploadedFile($_file, $_newname = '')
{
    if (!isset($_FILES[$_file])) {
        return 0;
    }
    if ($_FILES[$_file]['size'] == 0) {
        return 0;
    }
    
    return saveFile($_FILES[$_file]['tmp_name'], true);
}

//----------------------------------------------------------------------------------------
//Handle request functions

function initRequest()
{
    global $_languages, $_reactor;
    header('Content-Type: text/html; charset=utf-8');
    
    if (get_magic_quotes_gpc()) {
        $_GET = arrayMapRecursive('stripslashes', $_GET);
    }
    
    setlocale(LC_CTYPE, $_languages[$_reactor['language']] . '.UTF8');
    mb_internal_encoding('UTF-8');
}

function initRequestIndex()
{
    $url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    $url = str_replace('//', '/', $url);
    $url = urldecode($url);
    if (substr($url, -1) == '/') {
        $url = substr($url, 1, -1);
    }
    if (substr($url, -5) == '.html') {
        $url = substr($url, 1, -5);
    }
    
    $_RGET = $_GET + parseUrl($url);
    
    if (isset($_RGET['lng'])) {
        $_reactor['language']     = $_RGET['lng'];
        $_reactor['language_url'] = $_RGET['lng'] . '/';
    }
    
    if (isset($_RGET['_ref'])) {
        $_SERVER['HTTP_REFERER'] = $_RGET['_ref'];
    }
    
    //$mtime=$_reactor['show']['modified'];
    //if($mtime!='none')
    //httpModified($mtime);
    
    return $_RGET;
}

function httpModified($mtime)
{
    global $_log;
    if (headers_sent()) {
        return 0;
    }
    $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
    header('Cache-Control: must-revalidate');
    header('Last-Modified: ' . $gmdate_mod);
    
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        if ($gmdate_mod == $_SERVER['HTTP_IF_MODIFIED_SINCE']) {
            header('HTTP/1.0 304 Not Modified');
            $_log .= ' chache OK ';
            die();
        }
    }
}

//----------------------------------------------------------------------------------------
//URL functions

function urlHeaderEnc($data)
{
    return str_replace('&', '_!_', $data[0]);
}

function urlHeader($str)
{
    
    if (substr($str[2], 0, 11) == 'javascript:') {
        return $str[0];
    }
    
    if (substr($str[2], 0, 7) == 'http://') {
        return $str[0];
    }
    
    if (substr($str[2], 0, 7) == 'mailto:') {
        return $str[0];
    }
    
    if ($str[2][0] == '/') {
        return $str[0];
    }
    
    if ($str[2][0] == '#') {
        return $str[0];
    }
    
    $r = $str[1];
    
    $t = strpos($str[2], '?');
    
    if ($t !== false) {
        $str[2] = preg_replace_callback('/{[^}]+}/Uis', 'urlHeaderEnc', $str[2]);
        parse_str(substr($str[2], $t + 1), $m);
        if ($t != 0) {
            $m['show'] = substr($str[2], 0, $t);
        }
        foreach ($m as $k => $v) {
            $m[$k] = str_replace('_!_', '&', $v);
        }
    } else {
        $m = array('show' => $str[2]);
    }
    
    if (substr($m['show'], -1, 1) != '/') {
        $m['show'] .= '/';
    }
    
    if (!isset($m['lng'])) {
        $m['lng'] = '{$_reactor.language_url}';
    }
    
    $url = compileUrl($m);
    if ($str[3] == '"' || $str[3] == "'") {
        $r .= '"' . $url . '"';
    } else {
        $r .= '"' . $url . '"' . $str[3];
    }
    
    return $r;
}

function arrToUrl($a)
{
    global $_RGET;
    $r = array_merge($_RGET, $a);
    
    return compileUrl($r);
}

function compileUrl($data)
{
    return compileAurl($data);
}

function parseUrl($str)
{
    return parseAurl($str);
}

function parseAurl($str)
{
    global $_languages, $_site_tree, $_reactor;
    
    $r                    = array();
    $tree_i               = &$_site_tree['index'];
    $_reactor['path']     = array();
    $_reactor['path'][]   = $_site_tree['nodes'][$tree_i['#key']];
    $_reactor['path_url'] = '';
    
    if ($str == '' || $str == 'index') {
        $_reactor['show'] = $_site_tree['nodes'][$tree_i['#key']];
        
        return $r;
    }
    
    $i   = 0;
    $str = explode('/', $str);
    
    if (isset($_languages[$str[0]])) {
        $r['lng'] = $str[0];
        $i        = 1;
    }
    
    $j = 0;
    $c = count($str);
    for (; $i < $c; $i++) {
        if (isset($tree_i[$str[$i]])) {
            $j = 0;
            
            $tree_i = &$tree_i[$str[$i]];
            $_reactor['path_url'] .= $str[$i] . '/';
            $_reactor['path'][] = $_site_tree['nodes'][$tree_i['#key']]; //did #key points on node with minimal parameters set or what?
        } else {
            $param_pool = &$_site_tree['param']['/' . $_reactor['path_url']];
            
            $param = $param_pool[$param_pool['max']];
            $cnt   = $param_pool['max'];
            
            if ($j >= $cnt) {
                $_reactor['show'] = $_site_tree['nodes'][$_site_tree['index']['404']['#key']];
                stop('404');
            }
            
            $r[$param[$j]] = $str[$i];
            if ($param[$j][0] == '_') {
                $r[$param[$j]] = array();
                for (; $i < $c; $i++) {
                    if ($str[$i][0] != '_') {
                        $r[$param[$j]][] = $str[$i];
                    } else {
                        //$str[$i] = substr($str[$i],1);
                        //$i--;
                        break;
                    }
                }
            }
            $j++;
        }
    }
    
    $r['show'] = $_reactor['path_url'];
    
    $param_pool = &$_site_tree['param']['/' . $_reactor['path_url']];
    
    while (!isset($param_pool[$j]) && $j < $param_pool['max']) {
        $j++;
    }
    if (!isset($param_pool[$j])) {
        $j = $param_pool['min'];
    }
    
    $_reactor['show']   = $_site_tree['nodes'][$param_pool[$j]['key']];
    $_reactor['path'][] = $_reactor['show'];
    
    return $r;
}

function compileAurl($data)
{
    global $_reactor, $_site_tree;
    $url = SITE_URL;
    
    if (isset($data['lng'])) {
        if ($data['lng'] == '{$_reactor.language_url}') {
            $url .= $data['lng'];
        } else {
            $url .= $data['lng'] . '/';
        }
        unset($data['lng']);
    } else {
        $url .= $_reactor['language_url'];
    }
    
    if (isset($data['show'])) {
        if ($data['show'] == 'index/') {
            $data['show'] = '';
        }
        $data['show'] = str_replace('%2F', '/', $data['show']);
        $url .= $data['show'];
        $show = '/' . $data['show'];
        unset($data['show']);
    } else {
        $url .= $_reactor['path_url'];
        $show = '/' . $_reactor['path_url'];
    }
    
    if (!isset($_site_tree['param'][$show])) {
        reactor_error('undefined show [' . $show . '] in site_tree');
    }
    $param_pool =& $_site_tree['param'][$show];
    $param      = $param_pool[$param_pool['max']];
    
    foreach ($param as $item) {
        if (isset($data[$item])) {
            if ($item[0] == '_' && is_array($data[$item])) {
                $url .= str_replace('<', '%3C', implode('/', $data[$item])) . '/_';
            } else {
                $url .= rawurlencode($data[$item]) . '/';
            }
            unset($data[$item]);
        }
    }
    
    $url .= '?';
    foreach ($data as $k => $v) {
        if (is_array($v)) //handle array in GET param
        {
            foreach ($data[$k] as $z => $a) {
                if (is_array($a)) {
                    foreach ($a as $t => $w) {
                        if ($w . '' != '') {
                            $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D%5B' . rawurlencode(
                                    $t
                                ) . '%5D=' . rawurlencode($w) . '&';
                        } else {
                            $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D%5B' . rawurlencode($t) . '%5D&';
                        }
                    }
                } else {
                    if ($a . '' != '') {
                        $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D=' . rawurlencode($a) . '&';
                    } else {
                        $url .= rawurlencode($k) . '%5B' . rawurlencode($z) . '%5D' . '&';
                    }
                }
            }
        } else {
            if ($v . '' != '') {
                $url .= rawurlencode($k) . '=' . rawurlencode($v) . '&';
            } else {
                $url .= rawurlencode($k) . '&';
            }
        }
    }
    $url = substr($url, 0, -1);
    
    //$url.=rawurlencode_array($data);
    return $url;
}

function compileSurl($data)
{
    $url = SITE_URL . 'index.php?';
    
    foreach ($data as $k => $v) {
        if ($v != '') {
            $url .= $k . '=' . $v . '&';
        }
    }
    
    return substr($url, 0, -1);
}

function saveFile($file_path, $with_dir)
{
    $new_file      = getNewFileName();
    $new_file_path = FILE_DIR . $new_file['path'];
    $tmp_dir       = ini_get('upload_tmp_dir');
    if (empty($tmp_dir)) {
        $tmp_dir = sys_get_temp_dir();
    }
    if (strncmp($file_path, $tmp_dir, strlen($tmp_dir)) === 0) {
        $result = move_uploaded_file($file_path, $new_file_path);
    } else {
        $result = rename($file_path, $new_file_path);
    }
    if (!$result) {
        return 0;
    }
    
    return ($with_dir ? $new_file['path'] : $new_file['name']);
}

function getRelativePath($filename, $encode = true)
{
    $encoded_file_name = $encode ? rawurlencode($filename) : $filename;
    
    return getDirForFileName($filename) . '/' . $encoded_file_name;
}

function getDirForFileName($filename)
{
    if (strlen($filename) < 11) {
        return '';
    }
    
    $dir = $filename[7] . $filename[8];
    if (!is_dir(FILE_DIR . $dir)) {
        mkdir(FILE_DIR . $dir);
    }
    $dir .= '/' . $filename[9] . $filename[10];
    if (!is_dir(FILE_DIR . $dir)) {
        mkdir(FILE_DIR . $dir);
    }
    
    return $dir;
}

function getNewFileName()
{
    $_newname = str_replace('.', '', uniqid('', true));
    $dir      = getDirForFileName($_newname);
    
    return array('path' => $dir . '/' . $_newname, 'name' => $_newname);
}
