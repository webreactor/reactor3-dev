<!--box input-->


<table cellpadding="0" cellspacing="0">
<tr><td height="7"></td></tr>
<!--foreach from=$item.enum item=$v key=$k-->

<tr>
<td><input name="{$input_name}" type="radio" value="{$k}" <!--if $k == $input_value-->checked<!--/if-->></td><td width="20"></td><td onClick="t=document.getElementById('radio_{$input_name}_{$k}');if(t.checked)t.checked=false; else t.checked=true">{$v}</td>
</tr>
<tr><td height="10"></td></tr>
<!--/foreach-->
<tr><td height="10"></td></tr>
</table>

<!--/box-->