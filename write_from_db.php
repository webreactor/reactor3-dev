<?php

$_SERVER['REQUEST_URI'] = '/';
include 'bin/load_core.php';

include_once LIB_DIR . 'config_write.php';

tablesCompile();
interfacesCompile();
baseTypeCompile();
autoexecCompile();
configCompile();
resourceCompile();
guestUserCompile();
siteTreeCompile();

?>Ok