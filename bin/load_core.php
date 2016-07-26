<?php

//error_log('['.$_SERVER['REMOTE_ADDR'].']'.$_SERVER['REQUEST_URI']);
if (empty($_COOKIE['PHPSESSID'])) {
    unset($_COOKIE['PHPSESSID']);
} // fix empty sid value error

define('SITE_URL', '/');
define('SITE_DIR', $_SERVER['DOCUMENT_ROOT'] . SITE_URL);

require SITE_DIR . 'etc/base_config.php';
include ETC_DIR . 'config.php';
require LIB_DIR . 'basic_api.php';
require LIB_DIR . 'core_api.php';

$_reactor = array(
    'language'     => REACTOR_DEF_LANGUAGE,
    'language_url' => '',
    'module'       => array('name' => 'null', 'stack' => array()),
);

$_site_tree = resourceRestore('reactor_site_tree');

require LIB_DIR . 'Gekkon/Gekkon.php';

$Gekkon                   = new Gekkon();
$Gekkon->data['_SERVER']  = &$_SERVER;
$Gekkon->data['_reactor'] = &$_reactor;
$Gekkon->data['SITE_URL'] = SITE_URL;
$Gekkon->data['FILE_URL'] = FILE_URL;
$Gekkon->data['_user']    = &$_user;

initRequest();
$_RGET = $_GET;
$_SGET = array();

//require LIB_DIR . 'basic_object.php';
//require LIB_DIR . 'basic_tree.php';
//require LIB_DIR . 'content_adapter.php';
//require LIB_DIR . 'reactor_interface.php';
require LIB_DIR . 'local_user.php';
include ETC_DIR . 'tables.php';

$_db = new \Reactor\Database\PDO\Connection(
    sprintf(
        'mysql:dbname=%s;host=%s',
        DB_NAME,
        DB_HOST
    ),
    DB_USER,
    DB_PASS
);

$_db->sql('SET NAMES "utf8"');

$_resource        = array();
$_user            = restoreUser();
$_base_types      = resourceRestore('reactor_base_types');
$_interfaces      = resourceRestore('reactor_interfaces_' . $_user['ugroup']['name']);
$GLOBALS['_pool'] = array();

$Gekkon->data['_interfaces'] = &$_interfaces;
$Gekkon->data['_SGET']       = &$_SGET;

if (!isset($_user['ip_allowed'])) {
    $_user = restoreUser();
}

include ETC_DIR . 'autoexec.php';

if ($_user['login'] == 'root') {
    ini_set('error_log', SITE_DIR . '../root_php.log');
    error_log('[' . $_SERVER['REMOTE_ADDR'] . ']' . $_SERVER['REQUEST_URI']);
    $GLOBALS['DEBUG_SQL'] = 1;
}
