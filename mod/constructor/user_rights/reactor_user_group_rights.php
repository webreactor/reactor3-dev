<?php

include_once LIB_DIR.'config_write.php';

class reactor_user_group_rights extends basic_object
{

function getOne($fk)
{	$this->fk_value=$fk;global $_db;
$r=array();

$_db->sql('select * from '.T_REACTOR_MODULE.' order by name');
$r=$_db->matr('pk_module');

$_db->sql('select * from '.T_REACTOR_INTERFACE.' order by name');
$tr=array();
$cnt=0;
while($t=$_db->line())
{	$r[$t['fk_module']]['interfaces'][$cnt]=$t;
	$tr[$t['pk_interface']]=&$r[$t['fk_module']]['interfaces'][$cnt];
	$cnt++;
}
$_db->sql('select * from '.T_REACTOR_INTERFACE_ACTION.' order by `call`');
while($t=$_db->line())
	$tr[$t['fk_interface']]['actions'][]=$t;

$_db->sql('select * from '.T_REACTOR_UGROUP_ACTION.' where fk_ugroup='.$fk);

return array('stucture'=>$r,'rights'=>$_db->matr('fk_action','fk_ugroup'));
}

function store(&$form)
{global $_db;
$_db->sql('delete from '.T_REACTOR_UGROUP_ACTION.' where fk_ugroup='.$this->fk_value);
$data=$form->toDb();

foreach($data['rights'] as $item)
$_db->sql('insert into '.T_REACTOR_UGROUP_ACTION.' (fk_ugroup,fk_action) values ('.$this->fk_value.','.$item.')');

guestUserCompile();
interfacesCompile();

return 1;}


}

?>