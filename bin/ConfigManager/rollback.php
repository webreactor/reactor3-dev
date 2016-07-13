<?php

include __dir__.'/lib/bootstrap.php';

$rollback = new RollbackTableManager($_db, VAR_DIR);
$rollback->loadAllTables();
