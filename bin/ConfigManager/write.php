<?php

include __dir__ . '/lib/bootstrap.php';

$writer = new WriteManager($_db, MOD_DIR);
$writer->write();
