<?php

function tablesCompile()
{
reactor_trace('tablesCompile');
global $_db, $_languages;

$_db->sql('select * from '.T_REACTOR_MODULE);
$module=array();

while($t=$_db->line())
$module[$t['pk_module']]=$t;

$fw=fopen(ETC_DIR.'tables.php', 'w');

fputs($fw,"<?php\n");

$buff='// Last compilation '.date("Y-m-d H:i:s")."\n";
fputs($fw,$buff);

$_db->sql('select * from '.T_REACTOR_TABLE.' order by fk_module');
while($t=$_db->line())
{
   fputs($fw,"define('T_".strtoupper($module[$t['fk_module']]['name'].'_'.$t['name'])."','".$t['db_name']);
   if($t['mlng']==1)
   {
      fputs($fw,"_'.\$_reactor['language']);\n");
      foreach($_languages as $k=>$v)
      fputs($fw,"define('T_".strtoupper($module[$t['fk_module']]['name'].'_'.$t['name']).'_'.strtoupper($k)."','".$t['db_name'].'_'.strtolower($k)."');\n");
   }
   else
   {
   $buff="');\n";
   fputs($fw,$buff);
   }
}

fputs($fw, "\n?>");
fclose($fw);

}
//----------------------------------------------
function groupInterfacesCompile($ugroup)
{
reactor_trace('groupInterfacesCompile');
global $_db;


$rez=array();
$interfaces=array();
$_db->sql('select fk_action from '.T_REACTOR_UGROUP_ACTION.' where fk_ugroup='.$ugroup['pk_ugroup']);
$perm=$_db->matr('fk_action','fk_action');

$_db->sql('select i.pk_interface,i.name,i.configurators,i.class,i.source,i.pkey,i.constructor,m.name as module from '.T_REACTOR_INTERFACE.' i,'.T_REACTOR_MODULE.' m where i.fk_module=m.pk_module');

while($t=$_db->line())
{

	$interfaces[$t['pk_interface']]=$t['name'];
	unset($t['pk_interface']);

	$rez[$t['name']]=$t;
	$rez[$t['name']]['define']=array();
	$rez[$t['name']]['action']=array();
	if($t['configurators']!='')
	{
	$t['configurators']=str_replace(' ','',$t['configurators']);
	$rez[$t['name']]['configurators']=explode(',',$t['configurators']);
	}
	else
	$rez[$t['name']]['configurators']=array();
}




$_db->sql('select * from '.T_REACTOR_INTERFACE_DEFINE.' order by sort');
while($t=$_db->line())
{
unset($t['pk_define']);
$tt=$interfaces[$t['fk_interface']];
unset($t['fk_interface']);
unset($t['sort']);
$rez[$tt]['define'][$t['name']]=$t;
}



$_db->sql('select * from '.T_REACTOR_INTERFACE_ACTION.' order by sort');
while($t=$_db->line())
if(isset($perm[$t['pk_action']])||$ugroup['name']=='root')
{
$tt=$interfaces[$t['fk_interface']];
unset($t['fk_interface']);
unset($t['pk_action']);
unset($t['fk_action']);
unset($t['sort']);
$rez[$tt]['action'][$t['name']]=$t;
}

resourceStore('reactor_interfaces_'.$ugroup['name'],$rez);
}
//----------------------------------------------------------


function interfacesCompile()
{
global $_db;
$_db->sql('select * from '.T_REACTOR_UGROUP);
$ugroups=$_db->matr();

foreach($ugroups as $t)
groupInterfacesCompile($t);
}

function autoexecCompile()
{
reactor_trace('autoexecCompile');
global $_db;
$_db->sql('select * from '.T_REACTOR_MODULE);

$buff='// Last compilation '.date("Y-m-d H:i:s")."\n";

while($t=$_db->line())
if($t['to_core']!='')
{
$buff.='// Module '.$t['name']."\n";
$buff.=$t['to_core']."\n";
}

$fw=fopen(ETC_DIR.'autoexec.php', 'w');
fputs($fw, "<?php\n".$buff."\n?>");
fclose($fw);

}

function configCompile()
{
reactor_trace('configCompile');
global $_db;
$_db->sql('select pk_module,name from '.T_REACTOR_MODULE);
$module=array();
while($t=$_db->line())
$module[$t['pk_module']]=$t['name'];

$_db->sql('select *  from '.T_REACTOR_CONFIG.' order by `group`');
$t=$_db->matr();
$r=array();
foreach($t as $item)
$r[$item['fk_module']][$item['group']][]=$item;

$f=fopen(ETC_DIR.'config.php','w');
fwrite($f,"<?php\n// Last compilation ".date("Y-m-d H:i:s")."\n");

	foreach($r as $fk_module=>$m)
	{
	fwrite($f,"\n//--------------------------------------------------------------\n//Module ".$module[$fk_module]."\n");
		foreach($m as $key=>$g)
		{
		fwrite($f,"\n//Property group $key\n");

			foreach($g as $l)
			{
			if($l['descrip']!='')$l['descrip']='//'.$l['descrip'];
			fwrite($f,'define(\''.strtoupper($module[$fk_module].'_'.$l['name']).'\','.$l['value'].');'.$l['descrip']."\n");
			}
		}
	}


fwrite($f,'?>');
fclose($f);

}

function resourceCompile()
{
reactor_trace('resourceCompile');
global $_db;

$_db->sql('select pk_module,name from '.T_REACTOR_MODULE);
$module=array();
while($t=$_db->line())
$module[$t['pk_module']]=$t['name'];


$_db->sql('select * from '.T_REACTOR_RESOURCE);

$rez=array();
while($t=$_db->line())
$rez[$module[$t['fk_module']].'_'.$t['name']]=$t;

resourceStore('reactor_resource',$rez);
}

function baseTypeCompile()
{
reactor_trace('baseTypeCompile');
global $_db;
$rez=array();

$_db->sql('select t.*, m.name as mod_name from '.T_REACTOR_BASE_TYPE.' t, '.T_REACTOR_MODULE.' m where t.fk_module=m.pk_module');

while($t=$_db->line())
{
unset($t['pk_base_type']);
unset($t['fk_module']);
unset($t['call']);
$ttt=$t['name'];
unset($t['name']);
$rez[$ttt]=$t;
}


resourceStore('reactor_base_types',$rez);

}

function guestUserCompile()
{
reactor_trace('guestUserCompile');
global $_user;
$_user_save=$_user;

$login='guest';
userLogin($login,$login,$login);


resourceStore('reactor_guest_user',$_user);

$_user=$_user_save;
}

function siteTreeCompile()
{
global $_db;
$_db->sql('select * from '.T_SITE_TREE.' order by sort');
$_tree=$_db->matr();
$_site_tree_param=array();
$_site_nodes=array();
$_site_tree=siteTreeCompile_r($_tree,0,'',$_site_tree_param,$_site_nodes);
$_site_tree['param']=$_site_tree_param;
$_site_tree['nodes']=$_site_nodes;

resourceStore('reactor_site_tree',$_site_tree);

}


function siteTreeCompile_r(&$t,$fk,$path,&$root,&$nodes)
{
$r=array('#key'=>$fk);
$ct=count($t);
for($i=0;$i<$ct;$i++)
{
	$item=&$t[$i];
	if($item['fk_site_tree']==$fk)
	{
		$param='';
		$p_cnt=0;
		if($item['param']!='')
		{
		$param=explode(';',$item['param']);
		$p_cnt=count($param);
		}
		unset($item['param']);
		unset($item['sort']);

		if($item['name']!='index')
		$t_path=$path.$item['name'].'/';
		else
		$t_path='/';

		if(!isset($root[$t_path]))
		{			$root[$t_path]['max']=$p_cnt;
			$root[$t_path]['min']=$p_cnt;
			$root[$t_path]['minkey']=$item['pk_site_tree'];
			$root[$t_path]['maxkey']=$item['pk_site_tree'];
		}
		$root[$t_path][$p_cnt]=$param;
		$root[$t_path][$p_cnt]['key']=$item['pk_site_tree'];

		if($root[$t_path]['max']<$p_cnt){$root[$t_path]['max']=$p_cnt; $root[$t_path]['maxkey']=$item['pk_site_tree'];}
		//remove $r[$item['name']]['#key']=$item['pk_site_tree']; path strongly by nodes with min param
		if($root[$t_path]['min']>$p_cnt){$root[$t_path]['min']=$p_cnt; $root[$t_path]['minkey']=$item['pk_site_tree'];$r[$item['name']]['#key']=$item['pk_site_tree'];}

		$nodes[$item['pk_site_tree']]=$item;
		if(!isset($r[$item['name']]))$r[$item['name']]=array();
		$r[$item['name']]+=siteTreeCompile_r($t,$item['pk_site_tree'],$t_path,$root,$nodes);
	}
}
return $r;
}

function siteModified($where='1')
{
	global $_db;
	$_db->sql('update '.T_SITE_TREE.' set modified='.time().' where modified!="none" and '.$where);
	siteTreeCompile();
}



?>