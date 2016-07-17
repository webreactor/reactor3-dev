<!--set @data = $data_r-->
<!--set $level ++-->
<!--foreach from=@data item=$item-->
<a href="/cp/{$interfaces.$item&value&fk_interface}/{$item.value.name}/" class="l{$level} <!--if $_path.$item&value&pk_action.isset()--> act<!--/if-->">{$item.value.call}</a>
<!--if $item.in.count() >0-->
<!--set $data_r = $item.in-->
<!--include menu_left_r.tpl-->
<!--/if-->
<!--/foreach-->
<div class="level_end"></div>
<!--set $level -- -->