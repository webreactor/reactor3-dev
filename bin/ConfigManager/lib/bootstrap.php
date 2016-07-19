<?php

if (isset($_SERVER['DOCUMENT_ROOT'])) {
    //die();
}

define('MANAGER_LIB', __dir__ . '/');

define('MANAGED_APP_DIR', dirname(dirname(dirname(MANAGER_LIB))) . '/');

function configManagerAutoload($class_name)
{
    $file = MANAGER_LIB . $class_name . '.php';
    if (is_file($file)) {
        require $file;
    }
}

spl_autoload_register('configManagerAutoload');

define('SITE_DIR', MANAGED_APP_DIR);
include MANAGED_APP_DIR . 'etc/base_config.php';
require SITE_DIR . 'vendor/autoload.php';

/**
 * @var $_db \Reactor\Database\Interfaces\ConnectionInterface
 */
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

function reactor_error($msg)
{
    echo $msg . "\n";
}