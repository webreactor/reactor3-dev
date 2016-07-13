<tr><td height="10"></td></tr>
<tr><td id="formflag{$ukey}" class="form_tick" valign="top">{$input_name_rus}</td>
<td>
<div class="input_text">

<!--set  $t = 'check_form('. $ukey .",document.getElementById('formitem". $ukey ."').value,'type',". $item.3 .",'". str_replace("'","\'",@implode(';',array_keys( $form->object->enum.$input_name ))) ."','". $form_id ."')"-->

<select name="{$input_name}" id="formitem{$ukey}" onmouseup="{$t}">
<!--foreach from=$form->object->enum.$input_name item=$v key=$k-->
<option value="{$k}" <!--if $k == $input_value-->selected<!--/if-->>{$v}</option>
<!--/foreach-->
  </select>
</div>
</td></tr>
<script>
form_flags['{$form_id}']['{$ukey}']=0;
{$t}
</script>
<!--set $ukey ++-->