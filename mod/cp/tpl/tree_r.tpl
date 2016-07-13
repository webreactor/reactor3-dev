<!--set @data = & $exec_data-->
<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<!--set @count =0-->
<!--set @count_total =count( @data )-->
<!--set @ticker_level = $ticker-->
<!--set $level_ended.$tree_level.unset()-->
<!--foreach from=@data item=$item-->
<!--set $item.value  = $ca->toHTML( $item.value  )-->

<!--set @count ++-->
<!--set $uid ++-->



<tr class="table_line line{$ticker =1- $ticker}" valign="top">
	<!--for from=0 to=$tree_level key=$i-->
	<!--if isset( $level_ended.$i )-->
	   <td width="7%"></td>
	<!--else-->
	   <td width="7%" class="tree_level"></td>
	<!--/if-->
	<!--/for-->



<!--if $item.in.count() >0-->
	<!--if @count  != @count_total -->
<td width="7%" class="tree_level_closed" onClick="tree_content_oc('tree_{$uid}')" id="tree_{$uid}_b">
	<!--else-->
   <!--set $level_ended.$tree_level =1-->
<td width="7%" class="tree_level_closed_end" onClick="tree_content_oc('tree_{$uid}')" id="tree_{$uid}_b">
	<!--/if-->
<!--else-->
	<!--if @count  != @count_total -->
	<td width="7%" class="tree_level_node">
	<!--else-->
   <!--set $level_ended.$tree_level =1-->
	<td width="7%" class="tree_level_end">
	<!--/if-->
<!--/if-->


</td>
<td onclick="show_list_menu('{$pkey}={$item.value.$pkey}',this.parentNode)">
<!--foreach from=$line item=$t key=$key-->

{$item.value.$key} -
<!--/foreach--></td>
<td width="7%"></td>
</tr>

<!--if $item.in.count() >0-->
<tr class="tree_content">
<td colspan="{$tree_level +3}">
<script>
tree_lavels_array.push('tree_{$uid}');
</script>

<div id="tree_{$uid}_c" class="hidden">
<!--set $tree_level ++-->
<!--set $exec_data = & $item.in-->
<!--include tree_r.tpl-->
<!--set $tree_level ---->
</div>
</td>
</tr>
<!--/if-->


<!--/foreach-->


<!--if @ticker_level != $ticker-->
   <tr class="line{$ticker =1- $ticker}" valign="top">
<!--for from=0 to=$tree_level key=$i-->
<!--if isset( $level_ended.$i )-->
   <td width="7%"></td>
<!--else-->
   <td width="7%" class="tree_level"></td>
<!--/if-->
<!--/for-->
   <td width="7%"></td>
   <td>&nbsp;</td>
   <td width="7%"></td>
   </tr>
<!--/if-->

</table>