<?php

//global variables
$_log='';
$_log_t=$_SERVER['REMOTE_ADDR'].' | '.$_SERVER['REQUEST_URI'];
$_mctime=microtime(true);

$_trace='';
$_error='';
//------------------------------------------------------------------------------
//shutdown function

function close()
{
        global $_log,$_mctime,$_log_t;

        if(strpos($_log_t,'/jsonp_request/?interface=basket&action=handler&operation=refresh&') !== false) return;
        if(strpos($_log_t,'/jsonp_request/?interface=catalog_desc_comment&action=get_comment_data&') !== false) return;
        if(strpos($_log_t,'/jsonp_request/?interface=catalog_desc_compare&action=getList&') !== false) return;
        if(strpos($_log_t,'/crossdomain/?t=') !== false) return;

        error_log($_log_t.' | '.(microtime(true)-$_mctime).$_log);
}
//register_shutdown_function('close');

//------------------------------------------------------------------------------
//time functions



function tsToDate($time_stamp)
{
return date(REACTOR_FORMAT_DATE,$time_stamp);
}

function tsToDateTime($time_stamp)
{
return date(REACTOR_FORMAT_DATETIME,$time_stamp);
}

function arrayKeyFilter(&$data,$filter)
{
	$filter=array_flip($filter);
	$rez=array_intersect_key($data,$filter);
	$data=array_diff_key($data,$filter);
	return $rez;
}

function arrayMapRecursive($handle,$data)
{
if(is_array($data))
{
foreach($data as $k=>$v)
$data[$k]=arrayMapRecursive($handle,$v);
}
else
$data=$handle($data);

return $data;
}

function html_entity_decode_full($txt)
{
	return html_entity_decode($txt,ENT_QUOTES);
}


//------------------------------------------------------------------------------
//Sting functions

include LIB_DIR.'ext/typo.php';

function strToUrl($str)
{
$_str_translit=array(
'а' => 'a',
'б' => 'b',
'в' => 'v',
'г' => 'g',
'д' => 'd',
'е' => 'e',
'ё' => 'e',
'ж' => 'zh',
'з' => 'z',
'и' => 'i',
'й' => 'y',
'к' => 'k',
'л' => 'l',
'м' => 'm',
'н' => 'n',
'о' => 'o',
'п' => 'p',
'р' => 'r',
'с' => 's',
'т' => 't',
'у' => 'y',
'ф' => 'f',
'х' => 'h',
'ц' => 'ts',
'ч' => 'ch',
'ш' => 'sh',
'щ' => 'sch',
'ы' => 'y',
'э' => 'e',
'ю' => 'yu',
'я' => 'ya',
'А' => 'a',
'Б' => 'b',
'В' => 'v',
'Г' => 'g',
'Д' => 'd',
'Е' => 'e',
'Ё' => 'e',
'Ж' => 'zh',
'З' => 'z',
'И' => 'i',
'Й' => 'y',
'К' => 'k',
'Л' => 'l',
'М' => 'm',
'Н' => 'n',
'О' => 'o',
'П' => 'p',
'Р' => 'r',
'С' => 's',
'Т' => 't',
'У' => 'y',
'Ф' => 'f',
'Х' => 'h',
'Ц' => 'ts',
'Ч' => 'ch',
'Ш' => 'sh',
'Щ' => 'sch',
'Ы' => 'y',
'Э' => 'e',
'Ю' => 'yu',
'Я' => 'ya',
'ь'=>'',
'ъ'=>'',
'Ь'=>'',
'Ъ'=>'',
);


$str=str_replace(' ','_',html_entity_decode($str));
$str=preg_replace('/[^a-z0-9_\-]/','',strtolower(strtr($str,$_str_translit)));
return $str;
}

//------------------------------------------------------------------------------

function arrayColumnSplit($data, $column_count)
{
	$result = array(); $portion = ceil( count($data) / $column_count );
	if(!empty($data))
	{
		for ($i=0; $i < $column_count; $i++)
		{ 
			$result[ $i ] = array_slice($data, $i*$portion, $portion, true );
		}
	}

	return $result;
}


//------------------------------------------------------------------------------
// Database-style sorting array
// !! NOTE: keys are not preserved
function array_orderby()
{
	$args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field)
	{
		if( is_string($field) )
		{
			$tmp = array();
			foreach ($data as $key => $row)
			{
				$tmp[ $key ] = $row[ $field ];
			}
			$args[ $n ] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array( 'array_multisort', $args );
	return array_pop( $args );
}


//------------------------------------------------------------------------------



function varDumpJavaScript($data,$in=0)
{
	$in++;
	if($in>7)$data="".$data;
	if(!is_array($data))
	{
		if(floatval($data).''===$data.'')
			return $data;
		else
			return '"'.addcslashes(str_replace("\t", '', $data),'"\\').'"';
			//return '"'.str_replace('"', '\"', $data).'"';
	}

	$r=array();
	foreach($data as $k=>$v)
	{
		$k = addcslashes($k,'"\\');
		if(!is_array($v))
		{
			if(floatval($v).''===$v.'')
				$r[]='"'.$k.'":'.$v;
			else
				$r[]='"'.$k.'":"'.addcslashes(str_replace("\t", '', $v),'"\\').'"';
				//$r[]='"'.$k.'":"'.str_replace('"', '\"', $v).'"';
		}
		else
			$r[]='"'.$k.'":'.varDumpJavaScript($v,$in);
	}
	return '{'.implode(',',$r).'}';
}



/*==============define http-chunked-decode if not installed =================*/

if (!function_exists('http-chunked-decode')) {
    /**
     * dechunk an http 'transfer-encoding: chunked' message
     *
     * @param string $chunk the encoded message
     * @return string the decoded message.  If $chunk wasn't encoded properly it will be returned unmodified.
     */
    function http_chunked_decode($chunk) {
        $pos = 0;
        $len = strlen($chunk);
        $dechunk = null;

        while(($pos < $len)
            && ($chunkLenHex = substr($chunk,$pos, ($newlineAt = strpos($chunk,"\n",$pos+1))-$pos)))
        {
            if (! is_hex($chunkLenHex)) {
                trigger_error('Value is not properly chunk encoded', E_USER_WARNING);
                return $chunk;
            }

            $pos = $newlineAt + 1;
            $chunkLen = hexdec(rtrim($chunkLenHex,"\r\n"));
            $dechunk .= substr($chunk, $pos, $chunkLen);
            $pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
        }
        return $dechunk;
    }
}

/**
 * determine if a string can represent a number in hexadecimal
 *
 * @param string $hex
 * @return boolean true if the string is a hex, otherwise false
 */
function is_hex($hex) {
    // regex is for weenies
    $hex = strtolower(trim(ltrim($hex,"0")));
    if (empty($hex)) { $hex = 0; };
    $dec = hexdec($hex);
    return ($hex == dechex($dec));
}



/*
|| request data from other host via get-request
*/
function get_request($host,$uri)
{
	$data=array();
	$h = fsockopen($host,80);
	if($h)
	{
		$req = 'GET '.$uri.' HTTP/1.1'."\r\n".
				'Host: '.$host."\r\n".
				'Content-Type: application/x-www-form-urlencoded'."\r\n".
				'Connection: close'."\r\n".
				"\r\n";
		$ans = '';
		fwrite($h,$req);
		while(!feof($h)) $ans.=fgets($h,1024);
		fclose($h);

		$ans=explode("\r\n\r\n",$ans);
		if(stripos($ans[0],'chunked')!==false)$ans[1]=http_chunked_decode($ans[1]);

		$data = unserialize($ans[1]);
	}
	return $data;
}

?>