<?php
include_once LIB_DIR.'config_write.php';

class site_tree extends basic_tree{
function store(&$form)
{
$r=basic_object::store($form);
siteTreeCompile();
return $r;}
}//end of class


?>