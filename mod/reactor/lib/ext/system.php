<?php

function execFork($cmd)
{
    if (isset($_SERVER['WINDIR'])) {
        $WshShell = new COM('WScript.Shell');
        $oExec    = $WshShell->Run($cmd, 0, false);
        $WshShell->Release();
    } else {
        exec($cmd . ' &');
    }
}
