<?php

$_SERVER['REQUEST_URI'] = '/';

require 'vendor/autoload.php';
require 'bin/load_core.php';

use reactor\config_write;

config_write::tablesCompile();
config_write::interfacesCompile();
config_write::baseTypeCompile();
config_write::autoexecCompile();
config_write::configCompile();
config_write::resourceCompile();
config_write::guestUserCompile();
config_write::siteTreeCompile();

?>Ok