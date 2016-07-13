<!--box input-->

<select name="{$item.name}" id="{$item.name}_{$_form_id}_{$_input_id}" class="wild_select">
<!--foreach from=$item.enum item=$v key=$k-->
<option value="{$k}" <!--if $k == $data.$item&name-->selected<!--/if-->>{$v}</option>
<!--/foreach-->
  </select>
<!--/box-->