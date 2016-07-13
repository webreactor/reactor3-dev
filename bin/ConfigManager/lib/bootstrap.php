<?php

if (isset($_SERVER['DOCUMENT_ROOT'])) {
    //die();
}

define('MANAGER_LIB', __dir__.'/');

define('MANAGED_APP_DIR', dirname(dirname(dirname(MANAGER_LIB))).'/');


function configManagerAutoload($class_name) {
    $file = MANAGER_LIB . $class_name . '.php';
    if (is_file($file)) {
        include $file;
    }
}

spl_autoload_register('configManagerAutoload');

define('SITE_DIR', MANAGED_APP_DIR);
include MANAGED_APP_DIR.'etc/base_config.php';
include LIB_DIR.'db/mysql.php';

$_db = new db_mysql(DB_USER, DB_PASS, DB_HOST, DB_BAZA);
if (!$_db->link) {
    die('database error');
}
$_db->sql('set NAMES "utf8"');

function reactor_error($msg) {
    echo $msg."\n";
}