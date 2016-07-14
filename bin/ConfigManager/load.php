<?php

include __dir__ . '/lib/bootstrap.php';

$loader = new LoadManager($_db, MOD_DIR);
$loader->load();
