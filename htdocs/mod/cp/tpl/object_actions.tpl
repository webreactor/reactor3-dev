<!--set $actions = $exec_pool_id.pool_get('action')-->
<!--foreach from=$actions item=$act key=$key-->
<!--if $act.public ==2-->
<!--if $act.confirm ==1-->
<a href="javascript:if(confirm('Вы уверенны?'))document.location='{$SITE_URL}{$_reactor.language_url}cp/{$exec_pool_id.pool_get(name)}/{$key}/?{$configurators}_ref={$_SERVER.REQUEST_URI.urlencode().urlencode()}'" class="<!--if $key == $_SGET.action -->l1 act<!--else-->l1<!--/if-->">{$act.call}</a>
<!--else-->
<a href="!{$SITE_URL}{$_reactor.language_url}cp/{$exec_pool_id.pool_get(name)}/{$key}/?{$configurators}" class="<!--if $key == $_SGET.action -->l1 act<!--else-->l1<!--/if-->">{$act.call}</a>
<!--/if--><!--/if-->
<!--/foreach-->