<!--box input_block-->
<table border="0" cellpadding="0" cellspacing="0" witdh="100%">
<!--foreach from=$item.enum item=$v key=$k-->
<tr valign="center">
<td><input name="{$item.name}" type="radio" value="{$k}" <!--if $k == $data.$item&name-->checked<!--/if--> style="width:15px;"></td>
<td align="center">
<img src="!{$FILE_URL}{$v.file}" width="400" border="0"><br>
{$v.call}
</td>
</tr>
<tr valign="center">
<td height="10px"></td>
</tr>
<!--/foreach-->
</table>
<!--/box-->