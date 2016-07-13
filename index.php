<?php

$_https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') ? 's' : '';

if($_SERVER['REQUEST_URI']=='/index')
{
	header('Location: /',true,301);
	die();
}

if(substr($_SERVER['REQUEST_URI'], 0, 10) == '/var/files' && (false !== $filename_pos = strpos($_SERVER['REQUEST_URI'], 'name/')))
{
	header('Location: /var/files/'.substr($_SERVER['REQUEST_URI'], 12, $filename_pos));
	die();
}

$url = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
if($url[strlen($url)-1] != '/')
{
	if(substr($url, -5) != '.html')
	{
		$url .= '/';
		if(! empty($_SERVER['QUERY_STRING'])) $url .= '?' . $_SERVER['QUERY_STRING'];
		header('Location: '.$url);
		die();
	}
}


header('Access-Control-Allow-Origin: http://m.xcom-shop.ru');

include 'bin/load_core.php';
$_RGET=initRequestIndex();


if(strpos($_SERVER['REQUEST_URI'],'index.php')!==false)
{
	header('Location: /',true,301);
	die();
}



//print_r($_interfaces);
//print_r($_user);

//print_r($_reactor);
//print_r($_site_tree);
//print_r($_RGET);
//print_r($_GET);
//echo $_SERVER['REQUEST_URI'];
//print_r( execute('catalog_tree_site','getArm'));
//die();
//echo '<div align="left"><b>',compileUrl($_RGET),'</b></div>';
/*

include LIB_DIR.'config_write.php';
siteModified();
siteTreeCompile();
die();

/**/
if($_reactor['show']['interface']!='')
{
	$object=new reactor_interface();

	if(isset($_RGET['_so'])&&$_reactor['show']['interface']=='null')
	$object->restore($_RGET['_so']);
	else
	$object->configure($_reactor['show']['interface']);

	$Gekkon->register('exec_pool_id',$object->_pool_id);
	$Gekkon->register('main_pool_id',$object->_pool_id);

    if($_reactor['show']['action']!='')
    {
    $_action=&$object->get('action');
    if(!isset($_action[$_reactor['show']['action']])){initModule('cp');$Gekkon->display('login.tpl');die();}

    $data=$object->action($_reactor['show']['action'],$null);
    $Gekkon->registers('exec_data',$data);
	$Gekkon->register('main_data',$data);
    }

}


switch($_reactor['show']['handle'])
{
	case 0:
		initModule('site');
		$Gekkon->display('index.tpl');
		uninitModule();
	break;

	case 1:
		if($_reactor['show']['module']!='')
		{
			initModule($_reactor['show']['module']);
			$Gekkon->display($_reactor['show']['template']);
			uninitModule();
		}
		else
		{
			header('Location: '.$_SERVER['HTTP_REFERER']);
			die();
		}
	break;
}


?>
