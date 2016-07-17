<!--box input-->
<!--set $t = $data.$item&name.array_flip()-->
<!--foreach from=$item.enum item=$v key=$k-->
<p><label style="font-size:20px;">
<input class="checkbox" style='width:100px;' name="{$item.name}[]" type="checkbox" value="{$k}" title="{$item.description}" <!--if $t.$k.isset()-->checked<!--/if-->>
{$v}
</label></p>
<!--/foreach-->

<!--/box-->