<?php
use mod\rest_api\rest_client;

function userLogin($u, $p, $c)
{
    global $_log,$_user,$_db,$_reactor;
    $_user=0;
    $_SESSION['_stored_interface']=array();
    $_reactor['logining']=1;

    if($u!=''&&$p!='')
    {
        $u=htmlspecialchars(trim($u),ENT_QUOTES);
        $p=htmlspecialchars(trim($p),ENT_QUOTES);
        global $_rest_config;
        $rest_client = new rest_client( $_rest_config['user']['host'], $u, $p );
        $profile = $rest_client->get('profile');
        if ($profile['status'] == "success") {
            $_db->sql('select * from ' . T_REACTOR_USER . ' where `pk_user`=' . $profile['data']['fk_user']);
            $_user = $_db->line();
            if (empty($_user)) {
                $_db->sql('select `pk_ugroup` from '.T_REACTOR_UGROUP.' where name="user"');
                $group = $_db->line('pk_ugroup');
                $_db->insert(T_REACTOR_USER, array('pk_user' => $profile['data']['fk_user'],
                                                   'fk_ugroup' => $group,
                                                   'cookie' => '',
                                                   'active' => 1,
                                                   'email' => $profile['data']['email'],
                                                   'login' => $profile['data']['email'],
                                                   'name' => $profile['data']['name'],
                ));
                $_db->insert(T_USER_PROFILE, array('fk_user' => $profile['data']['fk_user'],
                                                   'registered' => time(),
                                                   'nickname' => ''));
                $_db->insert(T_USER_RIGHTS, array('fk_user'=> $profile['data']['fk_user']));
                $_user = $profile['data'];
                $_user['login'] = $_user['email'];
                $_user['ip_allowed'] = '';
                $_user['fk_ugroup'] = $group;
                $_user['pk_user'] = $_user['fk_user'];
                $_user['active'] = 1;
            }
        }
    }
    else
    {
        if(isset($_COOKIE['c_uid']))
        {
            $c_uid=htmlspecialchars(trim($_COOKIE['c_uid']),ENT_QUOTES);
            if(! empty($c_uid))
            {
                $_db->sql('select * from `'.T_REACTOR_USER.'` where `cookie`="'.$c_uid.'" and `active`=1');
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

    if($_user==0 || empty($_user['active']))
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

    $_db->sql('select * from '.T_USER_PROFILE.' where fk_user='.$_user['pk_user']);
    $_user['profile']=$_db->line();

    $_user['system']=SITE_URL;
    $_user['ip']=$_SERVER['REMOTE_ADDR'];

    $_log.=' login:'.$_user['email'];
}

function userLogin_light($pk_user)
{
    global $_user,$_interfaces,$_db;

    $_db->sql('select * from `' . T_REACTOR_USER . '` where pk_user='.$pk_user.' and `active`=1');
    $_user = $_db->line();

    if( empty($_user) )
    {
        $_user = resourceRestore('reactor_guest_user');
        return false;
    }

    $_db->sql('select * from '.T_REACTOR_UGROUP.' where pk_ugroup='.$_user['fk_ugroup']);
    $_user['ugroup'] = $_db->line();

    $_db->sql('select * from '.T_USER_PROFILE.' where fk_user='.$_user['pk_user']);
    $_user['profile']=$_db->line();

    $_interfaces = resourceRestore( 'reactor_interfaces_'.$_user['ugroup']['name'] );
    $_user['system']=SITE_URL;
    $_user['ip'] = $_SERVER['REMOTE_ADDR'];

    return true;
}