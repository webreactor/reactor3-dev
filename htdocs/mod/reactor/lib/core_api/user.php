<?php

function &restoreUser()
{
    global $_user, $_RGET;

    if (session_id() == '') {
        session_start();

        if (isset($_SESSION['_user'])) {
            $_user = $_SESSION['_user'];
        }

        $_SESSION['_user'] = &$_user;

        if ($_user['system'] != SITE_URL) {
            $_RGET['logout'] = 1;
        }

        if ($_user['ip'] != $_SERVER['REMOTE_ADDR']) {
            $_RGET['logout'] = 1;
        }
    } else {
        if (isset($_COOKIE['c_uid'])) {
            $_RGET['logout'] = 1;
        } else {
            $_user = resourceRestore('reactor_guest_user');
        }
    }

    if (isset($_POST['logout']) || isset($_RGET['logout'])) {
        if (!isset($_POST['cookie'])) {
            $_POST['cookie'] = 0;
        }

        if (!isset($_POST['login'])) {
            $_POST['login'] = '';
        }

        if (!isset($_POST['password'])) {
            $_POST['password'] = '';
        }

        userLogin($_POST['login'], $_POST['password'], $_POST['cookie']);

        if (!headers_sent()) {
            if ($_user['login'] != 'guest') {
                if (session_id() == '') {
                    session_start();
                }

                $_SESSION['_user'] = &$_user;
            }

            if (!isset($_RGET['logout'])) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
                die();
            }
        }
    }

    unset($_RGET['logout']);

    return $_user;
}
