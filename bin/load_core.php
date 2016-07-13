<?php
//error_log('['.$_SERVER['REMOTE_ADDR'].']'.$_SERVER['REQUEST_URI']);
if (empty($_COOKIE['PHPSESSID'])) {
    unset($_COOKIE['PHPSESSID']);
} // fix empty sid value error

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
$Gekkon->data['_SERVER'] =& $_SERVER;
$Gekkon->data['_reactor'] =& $_reactor;
$Gekkon->data['SITE_URL'] = SITE_URL;
$Gekkon->data['FILE_URL'] = FILE_URL;
$Gekkon->data['_user'] =& $_user;

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

require SITE_DIR . 'vendor/autoload.php';
$_rmq = new \toecto\AMQPSimpleWrapper\AMQPSimpleWrapper(RMQ_USR, RMQ_PWD, RMQ_VHOST, RMQ_HOST);

$_resource = array();
$_user = restoreUser();
$_base_types = resourceRestore('reactor_base_types');
$_interfaces = resourceRestore('reactor_interfaces_' . $_user['ugroup']['name']);
$GLOBALS['_pool'] = array();

$Gekkon->data['_interfaces'] =& $_interfaces;
$Gekkon->data['_SGET'] =& $_SGET;

if (!isset($_user['ip_allowed'])) {
    $_user = restoreUser();
}

include ETC_DIR . 'autoexec.php';

$_ab = new \Reactor\ABDriver\ABDriver($_rmq, 'ab_tests');
$_ab->initUtm($_RGET);

if (!isset($_ab->common_factors['city'])) {
    $city = ipToCityData($_ab->common_factors['ip']);
    $_ab->common_factors['city'] = $city['name'];
}

$_ab->startTest('buy_button_text', array('Купить', 'В корзину'));
$_ab->startTest('generic', array('no'));
$Gekkon->data['_ab'] = $_ab;

if ($_user['login'] == 'root') {
    ini_set('error_log', SITE_DIR . '../root_php.log');
    error_log('[' . $_SERVER['REMOTE_ADDR'] . ']' . $_SERVER['REQUEST_URI']);
    $GLOBALS['DEBUG_SQL'] = 1;
}

?>
