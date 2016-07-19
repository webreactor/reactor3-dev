<tr><td height="10"></td></tr>
<tr><td id="formflag{$ukey}" class="form_tick" valign="top">{$input_name_rus}</td>
<td>
<div class="input_htmlarea">
<!--set $pre_t = $item.3 .",'". str_replace("'","\'",@implode(';',array_keys( $form->object->enum.$input_name ))) ."','". $form_id ."')"-->
<!--set  $t = 'check_form('. $ukey .",document.getElementById('formitem". $ukey ."').value,'type',". $pre_t-->
<!--set  $tt = 'check_form('. $ukey .",editors['formitem". $ukey ."'].getHTML(),'type',". $pre_t-->


<textarea id="formitem{$ukey}" name="{$input_name}" onkeyup="{$t}">
{$input_value}
</textarea>


</div>
</td></tr>
<script>
form_flags['{$form_id}']['{$ukey}']=0;
{$t}

setTimeout(function() {
create_htmlarea('formitem{$ukey}');
        }, 500);

function _check_{$ukey}() {
{$tt};

setTimeout("_check_{$ukey}()",1000);
}
setTimeout("_check_{$ukey}()",1000);
</script>
<!--set $ukey ++-->