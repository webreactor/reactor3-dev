<?php

function userLogin($u, $p, $c)
{
    global $_log, $_user, $_db, $_reactor;

    $_SESSION['_stored_interface'] = array();

    $_user = 0;

    $_reactor['logining'] = 1;

    if ($u != '' && $p != '') {
        $query = $_db->select(
            T_REACTOR_USER,
            array(
                'login'  => htmlspecialchars(trim($u), ENT_QUOTES),
                'pass'   => htmlspecialchars(trim($p), ENT_QUOTES),
                'active' => 1,
            )
        );

        $_user = $query->line();
    } else {
        if (isset($_COOKIE['c_uid'])) {
            if (!empty($c_uid)) {
                $query = $_db->select(
                    T_REACTOR_USER,
                    array(
                        'cookie' => htmlspecialchars(trim($_COOKIE['c_uid']), ENT_QUOTES),
                        'active' => 1,
                    )
                );

                $_user = $query->line();
            }
        }
    }

    if (!empty($_user['ip_allowed'])) {
        if ($_user['ip_allowed'] != $_SERVER['REMOTE_ADDR']) {
            $_log .= ' || login denied from this ip';
            $_user = 0;
        }
    }

    if ($_user == 0) {
        $_reactor['login_error'] = 1;

        $query = $_db->select(
            T_REACTOR_USER,
            array('login' => 'guest')
        );

        $_user = $query->line();

        if (!headers_sent()) {
            setcookie('c_uid', 0, REACTOR_COOKIE_LIVE, SITE_URL);
        }
    } else {
        $cook = md5(uniqid(rand(), true));

        $_db->update(
            T_REACTOR_USER,
            array(
                'visited' => time(),
                'cookie'  => $cook,
            ),
            array(
                'pk_user' => $_user['pk_user'],
            )
        );

        if ((isset($c) || isset($_COOKIE['c_uid'])) && !headers_sent()) {
            setcookie('c_uid', $cook, REACTOR_COOKIE_LIVE, SITE_URL);
        }
    }

    $query = $_db->select(T_REACTOR_UGROUP, array('pk_ugroup' => $_user['fk_ugroup']));

    $_user['ugroup'] = $query->line();
    $_user['system'] = SITE_URL;
    $_user['ip']     = $_SERVER['REMOTE_ADDR'];

    $_log .= ' login:' . $_user['login'];
}

function userLogin_light($pk_user)
{
    global $_user, $_interfaces, $_db;

    $query = $_db->select(
        T_REACTOR_USER,
        array(
            'pk_user' => $pk_user,
            'active'  => 1,
        )
    );

    $_user = $query->line();

    if (empty($_user)) {
        $_user = resourceRestore('reactor_guest_user');

        return false;
    }

    $query = $_db->select(
        T_REACTOR_UGROUP,
        array('pk_ugroup' => $_user['fk_ugroup'])
    );

    $_user['ugroup'] = $query->line();

    $_interfaces = resourceRestore('reactor_interfaces_' . $_user['ugroup']['name']);

    $_user['ip'] = $_SERVER['REMOTE_ADDR'];

    return true;
}
