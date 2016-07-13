<!--version 2.0.2-->
<script language="JavaScript">

popup_menu_content=[
<!--foreach from=$exec_pool_id.pool_get(action) item=$act key=$key-->
<!--if $act.public ==1-->
['{$SITE_URL}{$_reactor.language_url}cp/{$exec_pool_id.pool_get(name)}/{$key}/?{$configurators}','{$act.call}',{$act.confirm}],
<!--/if-->
<!--/foreach-->
0
];

</script>

<!--set $pkey = $exec_pool_id.pool_get(pkey)-->
<!--set $uid =0-->
<!--set $ticker =0-->
<!--set $tree_level =0-->
<!--set $level_ended =array()-->
<script>
var tree_lavels_array=new Array();
</script>


<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<tr>
<td width="7%" height="34" class="tree_closed" onclick="tree_oc(this)"></td>
<td class="system">
<!--set $line =array()-->
<!--foreach from=$exec_pool_id.pool_get(define) item=$item-->
<!--if $item.inlist ==1-->
<!--set $line.$item&name =1-->
<b>{$item.call} - </b>
<!--/if-->
<!--/foreach-->
</td>
<td width="7%"></td>
</tr>
<tr><td colspan="3" height="2" bgcolor="black"></td></tr>
</table>

<!--set $ca = $exec_pool_id.pool_create_content_adapter()-->

<!--set $data_save =& $exec_data-->
<!--include tree_r.tpl-->
<!--set $exec_data =& $data_save-->

<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<tr><td colspan="3" height="2" bgcolor="black"></td></tr>
</table>