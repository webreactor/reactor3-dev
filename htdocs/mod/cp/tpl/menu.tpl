<table cellspacing=0 cellpadding=0>
<tr><td valign="top">
<div class="menu_top"></div>
<!--foreach from=$data item=$item-->
<a href="{$SITE_URL}cp/{$item.class}/{$item.action}" class="<!--if $item.class == $object->class_name-->menu_selected<!--else-->menu_item<!--/if-->"><span>{$item.name}</span></a>
<!--/foreach-->
</td></tr></table>
