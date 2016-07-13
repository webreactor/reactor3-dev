<?php
//version 1.4.2

class db_mysql
{
var $link;
var $rez;
var $sql;

function db_mysql($user,$pass,$host,$baza)
{
$this->link='none';
$this->user=$user;
$this->pass=$pass;
$this->host=$host;
$this->baza=$baza;
}

function connect()
{
	$this->link = mysql_pconnect($this->host,$this->user,$this->pass);
	if(!$this->link){
		error_log('mysql: down');
		$this->link = mysql_pconnect($this->host,$this->user,$this->pass);
		if(!$this->link){error_log('mysql: down2!'); }
	}
	mysql_select_db($this->baza,$this->link);

return $this->link;
}



function sql($z)
{
if($this->link=='none')$this->connect();

$t=microtime(true);
$this->rez=mysql_query($z,$this->link);
$t=microtime(true)-$t;
$this->sql=$z;

//error_log($t.' '.$z);
if(!$this->rez)
reactor_error($z.' | '.mysql_error($this->link));

if(isset($GLOBALS['DEBUG_SQL']))
error_log($z);

if(REACTOR_TRACE_SQL==1)
reactor_trace('sql '.$t.': <b>'.$z.'</b>');

return $this->rez;
}

function line($row='*')
{
if($l = @mysql_fetch_array($this->rez,MYSQL_ASSOC))
{	if($row=='*')return $l;
	return $l[$row];
}
else
{
@mysql_free_result($this->rez);
return 0;
}
}

function matr($key='',$row='*')
{
$r=array();
if($key=='')
{
	if($row=='*')
	{
	while ($t = @mysql_fetch_array($this->rez,1))
	$r[]=$t;
	}
	else
	{
	while ($t = @mysql_fetch_array($this->rez,1))
	$r[]=$t[$row];
	}
}
else
{
	if($row=='*')
	{
	while ($t = @mysql_fetch_array($this->rez,1))
	$r[$t[$key]]=$t;
	}
	else
	{
	while ($t = @mysql_fetch_array($this->rez,1))
	$r[$t[$key]]=$t[$row];
	}
}

@mysql_free_result($this->rez);
if(!isset($r))$r=0;
return $r;
}


function close()
{
mysql_close($this->link);
}

function last_id()
{
return mysql_insert_id($this->link);
}

function affected_rows()
{
return mysql_affected_rows($this->link);
}

function insert($table,$data,$addslashes=true, $flags='')
{
	if($addslashes)
	$data=array_map('addslashes',$data);
	$this->sql('insert '.$flags.' into `'.$table.'` (`'.implode('`,`',array_keys($data)).'`) values ("'.implode('","',$data).'")');
	return $this->last_id();
}

function replace($table,$data,$addslashes=true)
{	if($addslashes)
	$data=array_map('addslashes',$data);
	$this->sql('replace into `'.$table.'` (`'.implode('`,`',array_keys($data)).'`) values ("'.implode('","',$data).'")');
	return $this->last_id();
}

function update($table,$data,$where='',$addslashes=true)
{	if($addslashes)
	$data=array_map('addslashes',$data);
	$t='';
	foreach($data as $k => $v)
	$t.='`'.$k.'`="'.$v.'",';
	$t=substr($t,0,-1);
	if($where!='')$where=' where '.$where;
	$this->sql('update `'.$table.'` set '.$t.$where);
	return $this->affected_rows();
}

function pages($sql,$p,$by,&$all,&$total_rows)
{
$sql='select SQL_CALC_FOUND_ROWS'.substr($sql,6);
if($p==0)
$this->sql($sql);
else
{
$from=($p-1)*$by;
$this->sql($sql.' limit '.$from.','.$by);
}

$rez=$this->matr();
$this->sql('SELECT FOUND_ROWS() as count');
$all=$this->line();
$total_rows=$all['count'];
$all=ceil($all['count']/$by);
return $rez;
}

function pagess($sql,$p,$by,&$all,&$total_rows)
{
if($p==0)
$this->sql($sql);
else
{
$from=($p-1)*$by;
$this->sql($sql.' limit '.$from.','.$by);
}
$t=stripos($sql,'from');
$sql=substr($sql,$t);


$t=stripos($sql,'order by');
if($t!==false)
$sql=substr($sql,0,$t);

$rez=$this->matr();
$this->sql('SELECT count(*) as `count` '.$sql);
$all=$this->line();
$total_rows=$all['count'];
$all=ceil($all['count']/$by);
return $rez;
}




}//class end

?>