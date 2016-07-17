<?php

$_SERVER['REQUEST_URI'] = '/';
require 'bin/load_core.php';

require_once LIB_DIR . 'config_write.php';

tablesCompile();
interfacesCompile();
baseTypeCompile();
autoexecCompile();
configCompile();
resourceCompile();
guestUserCompile();
siteTreeCompile();

?>Ok