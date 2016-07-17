<!--box input_block-->
<br>
<!--set $t = $data.$item&name.array_flip()-->
<!--foreach from=$item.enum item=$v key=$k-->
<div style="width:140px;height:100px;float:left;text-align:center;" class="system">
<img src="!{$FILE_URL}{$v.file}" border="0"><br>
<input name="{$item.name}[]" type="checkbox" value="{$k}" <!--if $t.$k.isset()-->checked<!--/if--> style="width:15px;height:15px;display:inline;"><br>
{$v.call}
</div>
<!--/foreach-->

<!--/box-->