<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<tr class="list_first_line">
<td width="7%" height="34"></td>
<td>
<form action='' method='GET'>
<!--set $filter = '' -->
<!--if $_SGET.filter.isset() -->
<!--set $filter = $_SGET.filter -->
<!--/if-->
<select name='fk_ugroup'>
<option value="0">all</option>
<!--foreach from=$exec_data.fk_ugroup_enum item=$item key=$key -->
<option value="{$key}"<!--if $_SGET.fk_ugroup.isset() && $_SGET.fk_ugroup == $key--> selected<!--/if-->>{$item}</option>
<!--/foreach-->
</select>
<input name='filter' value='{$filter}' style='width:185px;' />
<input type='submit' value='Искать' />
</form>
</td>
</tr>
</table>
<!--include list.tpl -->