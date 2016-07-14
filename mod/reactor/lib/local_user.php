<?php

function userLogin($u,$p,$c)
{
    global $_log,$_user,$_db,$_reactor;
    $_user=0;
    $_SESSION['_stored_interface']=array();
    $_reactor['logining']=1;

    if($u!=''&&$p!='')
    {
        $u=htmlspecialchars(trim($u),ENT_QUOTES);
        $p=htmlspecialchars(trim($p),ENT_QUOTES);
        $_db->sql('select * from '.T_REACTOR_USER.' where login="'.$u.'" and pass="'.$p.'" and active=1');
        $_user=$_db->line();
    }
    else
    {
        if(isset($_COOKIE['c_uid']))
        {
            $c_uid=htmlspecialchars(trim($_COOKIE['c_uid']),ENT_QUOTES);
            if(! empty($c_uid))
            {
                $_db->sql('select * from '.T_REACTOR_USER.' where cookie="'.$c_uid.'" and active=1');
                $_user=$_db->line();
            }
        }
    }

    if(! empty($_user['ip_allowed']))
    {
        if($_user['ip_allowed'] != $_SERVER['REMOTE_ADDR'])
        {
            $_log.=' || login denied from this ip';
            $_user=0;
        }
    }

    if($_user==0)
    {
        $_reactor['login_error']=1;
        $_db->sql('select * from '.T_REACTOR_USER.' where login="guest"');
        $_user=$_db->line();
        if (!headers_sent())
            setcookie('c_uid',0,REACTOR_COOKIE_LIVE,SITE_URL);
    }
    else
    {
        $cook=md5(uniqid(rand(), true));
        $_db->sql('update '.T_REACTOR_USER.' set visited='.time().', cookie="'.$cook.'" where pk_user='.$_user['pk_user']);
        if((isset($c)||isset($_COOKIE['c_uid']))&&!headers_sent())
            setcookie('c_uid',$cook,REACTOR_COOKIE_LIVE,SITE_URL);
    }

    $_db->sql('select * from '.T_REACTOR_UGROUP.' where pk_ugroup='.$_user['fk_ugroup']);
    $_user['ugroup']=$_db->line();

    $_user['system']=SITE_URL;
    $_user['ip']=$_SERVER['REMOTE_ADDR'];

    $_log.=' login:'.$_user['login'];
}

function userLogin_light($pk_user)
{
    global $_user,$_interfaces,$_db;

    $_db->sql('select * from '.T_REACTOR_USER.' where pk_user='.$pk_user.' and active=1');
    $_user = $_db->line();

    if( empty($_user) )
    {
        $_user = resourceRestore('reactor_guest_user');
        return false;
    }

    $_db->sql('select * from '.T_REACTOR_UGROUP.' where pk_ugroup='.$_user['fk_ugroup']);
    $_user['ugroup'] = $_db->line();

    $_interfaces = resourceRestore( 'reactor_interfaces_'.$_user['ugroup']['name'] );

    $_user['ip'] = $_SERVER['REMOTE_ADDR'];

    return true;
}