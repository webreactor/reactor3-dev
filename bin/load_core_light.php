<?php

define('SITE_URL', '/');
define('SITE_DIR', $_SERVER['DOCUMENT_ROOT'] . SITE_URL);
include SITE_DIR . 'etc/base_config.php';
include ETC_DIR . 'config.php';
include LIB_DIR . 'basic_api.php';
include LIB_DIR . 'core_api.php';

$_reactor = array(
    'language'     => REACTOR_DEF_LANGUAGE,
    'language_url' => '',
    'module'       => array('name' => 'null', 'stack' => array()),
);

$_site_tree = resourceRestore('reactor_site_tree');

include LIB_DIR . 'Gekkon/Gekkon.php';
$Gekkon = new Gekkon();

initRequest();
$_RGET = $_GET;
$_SGET = array();

include LIB_DIR . 'db/mysql.php';
include LIB_DIR . 'basic_object.php';
include LIB_DIR . 'basic_tree.php';
include LIB_DIR . 'content_adapter.php';
include LIB_DIR . 'reactor_interface.php';
include ETC_DIR . 'tables.php';

$_db = new db_mysql(DB_USER, DB_PASS, DB_HOST, DB_BAZA);
if (!$_db->link) {
    die('database error');
}//!!
$_db->sql('set NAMES "utf8"');

$_resource        = array();
$_user            = resourceRestore('reactor_guest_user');
$_base_types      = resourceRestore('reactor_base_types');
$_interfaces      = resourceRestore('reactor_interfaces_' . $_user['ugroup']['name']);
$GLOBALS['_pool'] = array();

include ETC_DIR . 'autoexec.php';

ini_set('error_log', SITE_DIR . '../rest.log');
