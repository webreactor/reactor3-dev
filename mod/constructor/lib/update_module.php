<?php
include_once LIB_DIR.'config_write.php';

function update_module($pk_module)
{	global $_db,$_languages,$_reactor,$_log;

	$_db->sql('select name from '.T_REACTOR_MODULE.' where pk_module='.$pk_module);
	$module_item=$_db->line();
	if($module_item==0)return 0;


	$ca=new content_adapter();
	initModule($module_item['name']);
	if(!is_file($_reactor['module']['dir'].'module.rmp'))
	{
	uninitModule();
	return 0;
	}

	$module=unserialize(file_get_contents($_reactor['module']['dir'].'module.rmp'));
	$module['item']['pk_module']=$pk_module;
	$_db->replace(T_REACTOR_MODULE,$module['item']);


	$_db->sql('SELECT a.pk_action,concat(i.name ,">",a.name) as act
	FROM '.T_REACTOR_INTERFACE_ACTION.' a, '.T_REACTOR_INTERFACE.' i, '.T_REACTOR_MODULE.'
	WHERE a.fk_interface=pk_interface and fk_module=pk_module and pk_module='.$pk_module);
	$old_act=$_db->matr('act');



	$_db->sql('select * from '.T_REACTOR_INTERFACE.' where fk_module='.$pk_module);
	$interfaces=$_db->matr('pk_interface','pk_interface');
	$interfaces[]=0;

	$_db->sql('select * from `'.T_REACTOR_TABLE.'` where fk_module='.$pk_module);
	$tables=$_db->matr();

	$_db->sql('delete from `'.T_REACTOR_INTERFACE_DEFINE.'` where fk_interface in('.implode(',',$interfaces).')');
	$_db->sql('delete from `'.T_REACTOR_INTERFACE_ACTION.'` where fk_interface in('.implode(',',$interfaces).')');
	$_db->sql('delete from `'.T_REACTOR_INTERFACE.'` where fk_module='.$pk_module);
	$_db->sql('delete from `'.T_REACTOR_TABLE.'` where fk_module='.$pk_module);
	$_db->sql('delete from `'.T_REACTOR_RESOURCE.'` where fk_module='.$pk_module);
	$_db->sql('delete from `'.T_REACTOR_BASE_TYPE.'` where fk_module='.$pk_module);
	$_db->sql('delete from `'.T_REACTOR_CONFIG.'` where fk_module='.$pk_module);

	$_db->sql('show tables');

	while($t=$_db->line())
		$current_tables[current($t)]=1;

	foreach($module['table']['create'] as $k=>$item)
		if(!isset($current_tables[$k]))
			$_db->sql($item);

	$interfaces=array();
	$db_link=new basic_object(T_REACTOR_INTERFACE,'pk_interface');
	foreach($module['interface']['item'] as $item)
	{
		$t=$item['pk_interface'];
		unset($item['pk_interface']);
		$item['fk_module']=$pk_module;
		$interfaces[$t]=$db_link->insert($ca->toDB($item));
	}

	$db_link=new basic_object(T_REACTOR_INTERFACE_DEFINE,'pk_define');
	foreach($module['interface']['define'] as $item)
	{
		$item['fk_interface']=$interfaces[$item['fk_interface']];
		unset($item['pk_define']);
		$db_link->insert($ca->toDB($item));
	}

	$db_link=new basic_object(T_REACTOR_INTERFACE_ACTION,'pk_action');

	foreach($module['interface']['action'] as $item)
	{
		unset($item['pk_action']);
		$item['fk_action']=0;
		$t=$module['interface']['item'][$item['fk_interface']]['name'].'>'.$item['name'];
		$item['fk_interface']=$interfaces[$item['fk_interface']];

		if(isset($old_act[$t]))
			$item['pk_action']=$old_act[$t];

		$db_link->insert($ca->toDB($item));
	}

	$_db->sql('SELECT m.pk_action,m.name as fm_action,i.name as fm_interface FROM `'.T_REACTOR_INTERFACE_ACTION.'` m, `'.T_REACTOR_INTERFACE.'` i where m.fk_interface=i.pk_interface');
	$named_actions=array();
	while($t=$_db->line())
	$named_actions[$t['fm_interface'].'_'.$t['fm_action']]=$t['pk_action'];

	foreach($module['interface']['action_link'] as $k=>$item)
	{
	$t=array('fk_action'=>$named_actions[$item]);
	$db_link->update($ca->toDB($t),$named_actions[$k]);
	}

	$db_link=new basic_object(T_REACTOR_TABLE,'name');
	foreach($module['table']['item'] as $item)
	{
		unset($item['pk_table']);
		$item['fk_module']=$pk_module;
		$db_link->insert($ca->toDB($item));
	}

	$db_link=new basic_object(T_REACTOR_RESOURCE,'pk_resource');
	foreach($module['resource'] as $item)
	{
		unset($item['pk_resource']);
		$item['fk_module']=$pk_module;
		$db_link->insert($ca->toDB($item));
	}

	$db_link=new basic_object(T_REACTOR_CONFIG,'pk_config');
	foreach($module['config'] as $item)
	{
		unset($item['pk_config']);
		$item['fk_module']=$pk_module;
		$db_link->insert($ca->toDB($item));
	}

	$db_link=new basic_object(T_REACTOR_BASE_TYPE,'pk_base_type');
	foreach($module['base_type'] as $item)
	{
		unset($item['pk_base_type']);
		$item['fk_module']=$pk_module;
		$db_link->insert($ca->toDB($item));
	}


	uninitModule();


	tablesCompile();
	interfacesCompile();
	baseTypeCompile();
	autoexecCompile();
	configCompile();
	resourceCompile();
	guestUserCompile();
	siteTreeCompile();

return 1;}

?>