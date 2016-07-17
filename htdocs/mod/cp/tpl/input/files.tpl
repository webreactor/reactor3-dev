<!--box input_block-->
<!--if $data.$item&name.count() ==0-->
<input name="{$item.name}" type="hidden" value="">
<!--/if-->
<!--foreach from=$data.$item&name item=$it key=$key-->
	<div class="img_line"><a href="!{$FILE_URL}{$it.file}name/{$it.real}">{$it.real}</a>
	<input name="{$item.name}[{$key}][delete]" type="checkbox" value="1" style="width: 20px;">(удалить)
	<input name="{$item.name}[{$key}][desc]" type="text" value="{$it.desc}"></div>
	<input name="{$item.name}[{$key}][real]" type="hidden" value="{$it.real}">
	<input name="{$item.name}[{$key}][file]" type="hidden" value="{$it.file}">
<!--/foreach-->

<div class="img_line">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="150px">Добавить файл:</td>
<td style="position:relative;left:-15px;"><input name="_files_add_{$item.name}" type="file"></td>
</tr>
<tr>
<td>Описание:</td>
<td style="position:relative;left:-15px;"><input name="_files_add_desc_{$item.name}" type="text" value=""></td>
</tr>
<tr>
<td></td>
<td style="position:relative;left:-15px;"><input type="submit" value="Добавить" style="width:20%;"></td>
</tr>
</table>

</div>


<!--/box-->