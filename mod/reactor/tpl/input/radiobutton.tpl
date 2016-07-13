<tr><td height="10"></td></tr>
<tr><td id="formflag{$ukey}" class="form_tick_bad" valign="top">{$input_name_rus}</td>
<td>
<div class=''>

<!--set  $t = 'check_form('. $ukey .",this.value,'type',". $item.3 .",'". str_replace("'","\'",@implode(';',array_keys( $form->object->enum.$input_name ))) ."','". $form_id ."')"-->


<table cellpadding="0" cellspacing="0">
<tr><td height="7"></td></tr>
<!--set $tt = ''-->
<!--foreach from=$form->object->enum.$input_name item=$v key=$k-->

<tr>
<td><input name="{$input_name}" type="radio" id="radio_{$input_name}_{$k}" value="{$k}" <!--if $k == $input_value-->checked<!--set $tt = $k--><!--/if--> onClick="{$t}"></td><td width="20"></td><td onClick="t=document.getElementById('radio_{$input_name}_{$k}');if(t.checked)t.checked=false; else t.checked=true">{$v}</td>
</tr>
<tr><td height="10"></td></tr>
<!--/foreach-->
<tr><td height="10"></td></tr>
</table>


</div>
</td></tr>
<script>
<!--if $tt ==''-->
document.getElementById('formflag{$ukey}').className='form_tick_bad';
form_flags['{$form_id}']['{$ukey}']=0;
<!--else-->
document.getElementById('formflag{$ukey}').className='form_tick';
form_flags['{$form_id}']['{$ukey}']=1;
<!--/if-->
</script>
<!--set $ukey ++-->