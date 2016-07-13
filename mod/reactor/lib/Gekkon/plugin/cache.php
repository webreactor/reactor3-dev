<?php

if (isset($tag['arg']['stream'])) {
    $bin_open = '<?php
if(isset(' . $tag['arg']['stream'] . '))
$stream=' . $tag['arg']['stream'] . ';
else
$stream="";
$cid=$this->cache_stream($template_name,$stream);
';
    if (isset($tag['arg']['time'])) {
        if ($tag['arg']['time'][0] == '"') {
            $tag['arg']['time'] = substr($tag['arg']['time'], 1, -1);
        }
        $bin_open .= '
if(time() >' . (time() + $tag['arg']['time'] * 60) . ')
{
$this->compile_file($template_name);
//$this->clear_cache($template_name,$stream);
}
';
    }
    if (isset($tag['arg']['if'])) {
        $bin_open .= '
if(isset(' . $tag['arg']['if'] . ')&&' . $tag['arg']['if'] . '==1)
{
$this->clear_cache($template_name,$stream);
}
';
    }

    $bin_open .= '
$cache_dir=dirname($bin_name)."/".$cid[1].$cid[2];
$cache_name=$cache_dir."/".$cid;
if(!is_file($cache_name))
{
if(!is_dir($cache_dir))mkdir($cache_dir);
ob_start();
//ob_clean();
?>
';
    $bin_close = '
<?php
$t=ob_get_contents();

//$f=fopen($cache_name,"w");
//fwrite($f,$t);
//fclose($f);
if($t!="")
	file_put_contents($cache_name,$t,LOCK_EX);

ob_end_clean();
echo $t;
}
else
readfile ($cache_name);
?>';
} else {
    $bin_open = '';
    if (isset($tag['arg']['time'])) {
        if ($tag['arg']['time'][0] == '"') {
            $tag['arg']['time'] = substr($tag['arg']['time'], 1, -1);
        }

        $bin_open = '<?php
if(time() >' . (time() + $tag['arg']['time'] * 60) . ')
{
$this->compile_file($template_name);
} ?>';
    }
    if (isset($tag['arg']['if'])) {
        $bin_open .= '<?php
if(isset(' . $tag['arg']['if'] . '))
if(' . $tag['arg']['if'] . '==1)
{
' . $tag['arg']['if'] . '=0;
$this->compile_file($template_name);
} ?>';
    }

    $tag['inner'] = $this->compile($tag['inner']);
    ob_start();
    ob_clean();

    eval(' global $Gekkon,$_db,$_user,$_reactor; ?>' . $tag['inner'] . '<?php ');
    $tag['inner'] = ob_get_contents();

    ob_end_clean();
}
?>