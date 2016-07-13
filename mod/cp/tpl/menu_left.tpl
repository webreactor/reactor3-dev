<!--set $level =1-->
<!--set $interfaces =resource('cp_reactor_interfaces')-->
<!--set $interface_name =& $main_pool_id.pool_get('name')-->
<!--foreach from=$exec_data->realTree() item=$item-->
<a href="/cp/{$interfaces.$item&value&fk_interface}/{$item.value.name}/" class="l{$level} <!--if $_path.$item&value&pk_action.isset()--> act<!--/if-->">{$item.value.call}</a>
<!--if $item.in.count() >0-->
<!--set $data_r = $item.in-->
<!--include menu_left_r.tpl-->
<!--/if-->
<!--/foreach-->