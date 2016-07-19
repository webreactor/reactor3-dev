<!--box input_block-->
	<!--if $data.$item&name.count() ==0-->
	<input name="{$item.name}" type="hidden" value="">
	<!--/if-->
	<div class="img_line">
		<table border="0" cellpadding="0" cellspacing="0" width="400">
			<tr height="5px"><td width="130px"></td><td></td></tr>
			<tr>
				<td class="system">Добавить картинку:</td>
				<td><input name="_files_add_{$item.name}" type="file"></td>
			</tr>
			<tr height="5px"><td></td><td></td></tr>
			<tr>
				<td class="system">Описание:</td>
				<td><input name="_files_add_desc_{$item.name}" type="text" value=""  class="wild_text"></td>
			</tr>
			<tr height="5px"><td></td><td></td></tr>
			<tr>
				<td></td>
				<td align="right"><input type="submit" value="Сохранить" style="width:100px;"></td>
			</tr>
			<tr height="5px"><td></td><td></td></tr>
		</table>
		<hr>
		<table>
			<tr height="10px"><td width="200px"></td><td></td><td></td></tr>
		<!--foreach from=$data.$item&name item=$it key=$key-->
			<tr>
				<td><img src="!{@ FILE_URL , $it.file}_240" border="0" style="max-width:200px;"></td>
				<td width="10"></td>
				<td valign="bottom" width="190">
				<label class="system"><input name="{$item.name}[{$key}][delete]" type="checkbox" value="1" style="width: 10px;" class="bit_down" > удалить</label><br><br>
					<input name="{$item.name}[{$key}][desc]" type="text" value="{$it.desc}"  class="wild_text">
					<input name="{$item.name}[{$key}][real]" type="hidden" value="{$it.real}">
					<input name="{$item.name}[{$key}][file]" type="hidden" value="{$it.file}">
				</td>
			</tr>
			<tr height="20px"><td width="130px"></td><td></td></tr>
		<!--/foreach-->
		</table>
	</div>
<!--/box-->