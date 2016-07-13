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

<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<tr class="list_first_line">
<td width="7%" height="34"></td>
<!--set $line =array()-->
<!--foreach from=$exec_pool_id.pool_get(define) item=$item key=$key-->
<!--if $item.inlist ==1-->
<!--set $line.$key = $item.base_type-->
<td class="system"><b>{$item.call}</b></td>
<td width="7%"></td>

<!--/if-->
<!--/foreach-->

</tr>
<tr><td colspan="{$line.count() *2+2}" height="2" bgcolor="black"></td></tr>

<!--set $ca = $exec_pool_id.pool_create_content_adapter()-->
<!--set $exec_data.data = $ca->arrayToHTML( $exec_data.data  )-->

<!--foreach from=$exec_data.data item=$item-->
<tr class="table_line line{$ticker =1- $ticker}" valign="top" onclick="show_list_menu('{$pkey}={$item.$pkey}',this)">
<td width="7%">

</td>
<!--foreach from=$line item=$bt key=$key-->
<!--if $bt !='image'-->
<td>{$item.$key}</td>
<!--else-->
<td><img src="!{$FILE_URL}{$item.$key}" border="0"></td>
<!--/if-->
<td width="7%"></td>
<!--/foreach-->
</tr>


<!--set $uid ++-->
<!--/foreach-->

<tr><td colspan="{$line.count() *2+2}" height="2" bgcolor="black"></td></tr>
</table>
<div class="navigation"><!--navigation $exec_data--> <span>({$exec_data.total_rows_count})</span></div>