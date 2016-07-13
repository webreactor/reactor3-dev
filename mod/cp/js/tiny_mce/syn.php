<?php
/*******************************************************************************
simple file synchronizator
version 3.0
Maxim Popov | mail@ecto.ru | http://ecto.ru/
*******************************************************************************/

define('_SYN_PASS','12345');

/*******************************************************************************
Using as lib:

include 'syn.php';
$param=$__SYN;
...here is changing $param
$x=new syn_create($param); //or $x=new syn_extract($param);
if($x->error) {echo 'Done with some errors';}

*******************************************************************************/

define('_SYN_NULL',pack('x'));

$__SYN=array(
'dir'=>dirname(__FILE__).'/',
'include'=>'*',
'exclude'=>'',
'file_chmod'=>'777',
'dir_chmod'=>'644',
'verbose'=>0, //1, 0
'file'=>'archive.tar'
);

if(isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF'])==basename(__FILE__))
web_interface();

class syn_file
{
	function syn_file($file_name,$t){$this->fh=fopen($file_name,$t);}
	function write($data){fwrite($this->fh,$data);}
	function read($len){return fread($this->fh,$len);}
	function gets(){return fgets($this->fh);}
	function close(){fclose($this->fh);}
	function seek($offset=false,$type=SEEK_CUR ){if($offset===false)return ftell($this->fh); return fseek($this->fh,$offset,$type);}//SEEK_SET
}

class syn_extract extends syn_file
{

	function syn_extract($settings='none')
	{	global $__SYN;
		$this->error=0;
		if($settings=='none')
		$this->settings=$__SYN;
		else
		$this->settings=array_merge($__SYN,$settings);
		$file=$this->settings['dir'].$this->settings['file'];
		if(!is_file($file)||!is_readable($file))
		{			$this->msg("\nImpossible to read ".$file);
			$this->error=1;
		}
		else
		{
		syn_file::syn_file($file,'r');
		$this->tar_read();
		}
	}

	function header_parce()
	{
		$rez=array();
		$raw=$this->read(512);
		if(!$raw)return false;
		if($raw[0]==_SYN_NULL)return false;
		if(strlen($raw)!=512)return false;

		$rez['name']=rtrim(substr($raw,0,100));
		$rez['mode']=rtrim(substr($raw,100,8));
		$rez['uid']=rtrim(substr($raw,108,8));
		$rez['gid']=rtrim(substr($raw,116,8));
		$rez['size']=octdec(rtrim(substr($raw,124,12)));
		$rez['mtime']=date('r',octdec(rtrim(substr($raw,136,12))));
		$rez['chksum']=octdec(rtrim(substr($raw,149,6)));
		$rez['typeflag']=rtrim(substr($raw,156,1));
		if($rez['typeflag']=='')$rez['typeflag']=0;
		$rez['linkname']=rtrim(substr($raw,157,100));
		$rez['magic']=rtrim(substr($raw,257,6));
		$rez['version']=rtrim(substr($raw,263,2));
		$rez['uname']=rtrim(substr($raw,265,32));
		$rez['gname']=rtrim(substr($raw,297,32));
		$rez['devmajor']=rtrim(substr($raw,329,8));
		$rez['devminor']=rtrim(substr($raw,337,8));
		$rez['prefix']=rtrim(substr($raw,345,155));

		$check=substr($raw,0,148).'        '.substr($raw,156);
		$crc=0;
		for ($i = 0; $i < 512; $i++)
		$crc+=ord($check[$i]);
		if($crc!=$rez['chksum'])return false;
		return $rez;
	}

	function file_handle($header)
	{
		$file=$this->settings['dir'].$header['prefix'].$header['name'];
		$this->msg("\n".$file."\t".syn_format_size($header['size']));
		if(is_file($file)&&!is_writable($file))
		{			$this->msg(' Permission denied!');
			$this->error=1;
			$this->seek($header['size']);}
		else
		{
			$f=fopen($file,'w');
			$rest=$header['size'];
			while($rest>0)
			{
				$rest=$rest-100000;

				if($rest>0)
				fwrite($f,$this->read(100000));
				else if($rest<0)
				fwrite($f,$this->read($rest+100000));
			}
			fclose($f);

			if(!isset($_SERVER['WINDIR']))
			@chmod ($file, $this->settings['file_chmod']);
			@touch($file, $header['mtime']);
		}

		$t=$this->seek()%512;
		if($t>0)$this->seek(512-$t);
	}

	function dir_handle($header)
	{
		$dir=$this->settings['dir'].$header['prefix'].$header['name'];
		$this->msg("\n[DIR] ".$dir);
		if(!is_dir($dir))
		{			if(!is_writable(dirname($dir)))
			{
			$this->msg(' Permission denied!');
			$this->error=1;
			}
			else			mkdir($dir,$this->settings['dir_chmod']);
		}
	}

	function tar_read()
	{
		while($header=$this->header_parce())
		{
			if($header['typeflag']==0 || $header['typeflag']==1)// file
			$this->file_handle($header);
			if($header['typeflag']==5)// dir
			$this->dir_handle($header);
		}
		$this->close();
	}

	function msg($mgs)
	{
		if($this->settings['verbose']==1) echo $mgs;
	}

}//end of syn_extract

//------------------------------------------------------------------------------

class syn_create extends syn_file{
	function syn_create($settings='none')
	{		$this->error=0;
		global $__SYN;
		if($settings=='none')
		$this->settings=$__SYN;
		else
		$this->settings=array_merge($__SYN,$settings);
		$file=$this->settings['dir'].$this->settings['file'];
		if(is_file($file)&&!is_writable($file))
		{			$this->msg("\nImmposible to write ".$file);
			$this->error=1;
		}
		else
		{
			syn_file::syn_file($file,'w');

			$this->header_def=array(
			'name'=>'',
			'mode'=>'',
			'uid'=>str_repeat(_SYN_NULL,8),
			'gid'=>str_repeat(_SYN_NULL,8),
			'size'=>'',
			'mtime'=>'',
			'chksum'=>'        ',
			'typeflag'=>'',//0 - file, 5 - dir
			'linkname'=>str_repeat(_SYN_NULL,100),
			'magic'=>'ustar ',
			'version'=>' '._SYN_NULL,
			'uname'=>str_repeat(_SYN_NULL,32),
			'gname'=>str_repeat(_SYN_NULL,32),
			'devmajor'=>str_repeat(_SYN_NULL,8),
			'devminor'=>str_repeat(_SYN_NULL,8),
			'prefix'=>str_pad('', 155, _SYN_NULL, STR_PAD_RIGHT),
			'pad'=>str_repeat(_SYN_NULL,12)
			);

			$this->tar_create_r('');
			$this->write(str_pad('', 512, _SYN_NULL, STR_PAD_RIGHT));
		}
	}

	function header_encode($header)
	{

		if(strlen($header['name'])>100)
		{
			$header['prefix']=substr($header['name'],100);
			$header['name']=substr($header['name'],0,0);
			if(strlen($header['prefix'])>155)$header['prefix']=substr($header['prefix'],0,155);
			$header['prefix']=str_pad($header['prefix'], 155, _SYN_NULL, STR_PAD_RIGHT);
		}
		else
		$header['prefix']=$this->header_def['prefix'];


		$rez_header=array_merge($this->header_def,array(
		'name'=>str_pad($header['name'], 100, _SYN_NULL, STR_PAD_RIGHT),
		'mode'=>str_pad($header['mode'], 7, '0', STR_PAD_LEFT). _SYN_NULL,
		'size'=>str_pad($header['size'], 11, '0', STR_PAD_LEFT). _SYN_NULL,
		'mtime'=>str_pad($header['mtime'], 11, '0', STR_PAD_LEFT). _SYN_NULL,
		'typeflag'=>$header['typeflag'],
		'prefix'=>$header['prefix'],
		));

		$check=implode('',$rez_header);
		$crc=0;
		for ($i = 0; $i < 512; $i++)
		$crc+=ord($check[$i]);

		$rez_header['chksum']=str_pad(decoct($crc), 6, '0', STR_PAD_LEFT). _SYN_NULL.' ';
		return implode('',$rez_header);
	}

	function file_handle($file)
	{
		if($file==$this->settings['file'])return;
		$this->msg("\n".$file."\t");
		if(!is_readable($file))
		{			$this->msg(' Permission denied!');
			$this->error=1;

		}
		else
		{
			$f=fopen($this->settings['dir'].$file,'r');
			if(!$f)return;
			$finfo=fstat($f);

			$header = $this->header_encode(array(
				'name'=>$file,
				'mode'=>$this->settings['file_chmod'],
				'size'=>decoct($finfo['size']),
				'mtime'=>decoct($finfo['mtime']),
				'typeflag'=>'0',
				));

			$this->write($header);

			while(!feof($f))
			{
				$t=fread($f,100000);
				$this->write($t);
			}
			$t=$finfo['size']%512;

			if($t>0)$this->write(str_pad('', 512-$t, _SYN_NULL, STR_PAD_RIGHT));
			fclose($f);
			$this->msg(syn_format_size($finfo['size']));
		}
	}

	function dir_handle($dir)
	{
		$this->msg("\n[DIR] ".$dir);

		$header = $this->header_encode(array(
			'name'=>$dir.'/',
			'mode'=>$this->settings['dir_chmod'],
			'size'=>0,
			'mtime'=>decoct(filemtime($this->settings['dir'].$dir)),
			'typeflag'=>'5',
			));

		$this->write($header);
	}



	function tar_create_r($path)
	{
	    if ($dh=opendir($this->settings['dir'].$path))
	    {
	        while (($file = readdir($dh)) !== false)
	        {
				if($file!='.' && $file!='..')
				{
					$file=$path.$file;
					if(is_dir($file))
					{
						$this->dir_handle($file);
						$this->tar_create_r($file.'/');
					}
					else
						$this->file_handle($file);
				}
	        }
	        closedir($dh);
	    }
	    else
	    {$this->msg("\nPermission denied! ".$this->settings['dir'].$path); $this->error=1;}
	}


	function msg($mgs)
	{
		if($this->settings['verbose']==1) echo $mgs;
	}

}//end of syn_create

//------------------------------------------------------------------------------

function web_interface()
{
	session_start();
	if(!isset($_SESSION['login'])||$_SESSION['login']==0)web_login();
	if(!isset($_GET['action']))$_GET['action']='main';
	$actions=array('main','create_h','extract_h','diff_h','logout');
	if(!in_array($_GET['action'],$actions));
	$run='web_'.$_GET['action'];
	$run();
}


function web_login()
{
	if(isset($_POST['pass']))
	{
		if($_POST['pass']==_SYN_PASS)
		{
		$_SESSION['login']=1;
		header('Location: syn.php');
		}
		else
		echo 'Wrong password.<br>';
	}
	echo '<form action="syn.php" method="POST"><input name="pass" type="passowd"><input type="submit" value="Login"></form>';
	die();
}


function web_logout()
{
	$_SESSION['login']=0; header('Location: syn.php'); die();
}

function web_main()
{
	web_tpl_head();
	echo '<h2>Creating archive</h2><form action="syn.php?action=create" method="POST"><table>
	 <tr><td width="85">Archve name</td><td><input name="file" type="text" value="archive.tar" size="40"></td></tr>
	 <tr><td>Include</td><td><input name="include" type="text" value="*" size="40" disabled></td></tr>
	 <tr><td>Exclude</td><td><input name="exclude" type="text" value="*.tar" size="40" disabled></td></tr>
	 <tr><td>Verbose</td><td><input name="verbose" type="checkbox" value="1" size="40"></td></tr>
	 <tr><td></td><td align="right"><input type="submit" value="&nbsp;Create!&nbsp;"></td></tr>
	 </table></form>';
	web_arch_list();
	web_tpl_bottom();
}


function web_create()
{
	global $__SYN;
	$param=$__SYN;
	if(isset($_POST['file']))$param['file']=$_POST['file'];
	if(isset($_POST['include']))$param['include']=$_POST['include'];
	if(isset($_POST['exclude']))$param['exclude']=$_POST['exclude'];
	if(isset($_POST['verbose']))$param['verbose']=1;
	web_tpl_head();
	echo '<h2>Creating archive...</h2><div><table>
	<tr><td>Archve name</td><td>'.$param['file'].'</td></tr>
	<tr><td>Include</td><td>'.$param['include'].'</td></tr>
	<tr><td>Exclude</td><td>'.$param['exclude'].'</td></tr>
	</table></div><pre>';
	flush();
	$x=new syn_create($param);
	if($x->error==0)
	echo '</pre><div><b>Done!</b>';
	else
	echo '</pre><div><b>Done with errors!</b>';
	if(is_file($param['dir'].$param['file']))
	echo '<br>Download <a href="'.$param['file'].'">'.$param['file'].'</a> <small>'.syn_format_size(filesize($param['dir'].$param['file'])).'</small>
	<br><a href="syn.php">Back</a><br></div>';
}


function syn_format_size($size){
    switch (true){
    case ($size > 1099511627776):
        $size /= 1099511627776;
        $suffix = 'TB';
    break;
    case ($size > 1073741824):
        $size /= 1073741824;
        $suffix = 'GB';
    break;
    case ($size > 1048576):
        $size /= 1048576;
        $suffix = 'MB';
    break;
    case ($size > 1024):
        $size /= 1024;
        $suffix = 'KB';
        break;
    default:
        $suffix = 'B';
    }
    return round($size, 2).$suffix;
}

function web_arch_list()
{
	global $__SYN;
	echo '<br><br><h2>Extracting archive</h2><form action="syn.php?action=extract" method="POST"><table>';
	$flag='disabled';
	if($dh=opendir($__SYN['dir']))
	{
		while (($file = readdir($dh)) !== false)
		if($file!='..'&&$file!='.')
		{
			$ext=pathinfo($__SYN['dir'].$file,PATHINFO_EXTENSION);
			if(strtolower($ext)=='tar')
			{
				$finfo=stat($__SYN['dir'].$file);
				echo '<tr><td><input name="file" type="radio" value="'.$file.'" checked></td><td><a href="'.$file.'">'.$file.'</a></td><td><small>'.syn_format_size($finfo['size']).'</small></td><td><small>'.date('d.m.Y H:m',$finfo['mtime']).'</small></td></tr>';
				$flag='';
			}
		}
		echo '</table><table>
		 <tr><td width="85">Include</td><td><input name="include" type="text" value="*" size="40" disabled></td></tr>
		 <tr><td>Exclude</td><td><input name="exclude" type="text" value="" size="40" disabled></td></tr>
		 <tr><td>Verbose</td><td><input name="verbose" type="checkbox" value="1" size="40"></td></tr>
		 <tr><td></td><td align="right"><input type="submit" value="&nbsp;Extract!&nbsp;" '.$flag.'></td></tr>
		 </table></form>';
	}
}

function web_extract()
{
	global $__SYN;
	$param=$__SYN;
	if(isset($_POST['file']))$param['file']=$_POST['file'];
	if(isset($_POST['include']))$param['include']=$_POST['include'];
	if(isset($_POST['exclude']))$param['exclude']=$_POST['exclude'];
	if(isset($_POST['verbose']))$param['verbose']=1;
	web_tpl_head();
	echo '<h2>Exctracting archive...</h2><div><table>
	<tr><td>Archve name</td><td>'.$param['file'].'</td></tr>
	<tr><td>Include</td><td>'.$param['include'].'</td></tr>
	<tr><td>Exclude</td><td>'.$param['exclude'].'</td></tr>
	</table></div><pre>';
	flush();
	$x=new syn_extract($param);
	if($x->error==0)
	echo '</pre><div><b>Done!</b>';
	else
	echo '</pre><div><b>Done with errors!</b>';
	echo '<br><a href="syn.php">Back</a><br></div>';
}

function web_tpl_head()
{
	echo '<html><head><title>Syn 3.0</title><style>
	h2 {font:bold 14px Verdana;}
	td {font:normal 12px Verdana; padding:5px 5px 5px 10px;}
	input{margin-top:2px;}
	p {font:normal 12px Verdana;}
	</style></head><body><p><a href="syn.php?action=logout">Logout</a></p>';
}

function web_tpl_bottom()
{
	echo '</body></html>';
}
?>