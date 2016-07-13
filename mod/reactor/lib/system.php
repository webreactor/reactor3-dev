<?php

function execFork($cmd) {
    if (isset($_SERVER['WINDIR'])) {
        $WshShell = new COM('WScript.Shell');
        error_log('execFork: ' . $cmd);
        $oExec = $WshShell->Run($cmd, 0, false);
    } else {
        sysexec('nohup ' . $cmd . ' &');
    }
}

?>