<div class="pathr">
/	{$_reactor.language}

<!--set $interfaces =resource('cp_reactor_interfaces')-->
<!--set $_path =array()-->
<!--foreach from=$exec_data item=$item-->
/ <span>{$item.call}</span>
<!--set $_path [ $item.pk_action ]=1-->
<!--/foreach-->

<!--set $act_description = mod\cp\cp::description( $item.pk_action )-->
</div>