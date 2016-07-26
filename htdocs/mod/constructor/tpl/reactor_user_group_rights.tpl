<!--set initModule('cp')-->
<script>
function select_checkers_from_module(matr,val)
{
for(i in rights_array[matr])
{
select_checkers_from_interface(matr,i,val);
document.getElementById('input_checker_'+matr+'_'+i).checked=val;
}
}

function select_checkers_from_interface(matr,i,val)
{
for(j in rights_array[matr][i])
document.getElementById(rights_array[matr][i][j]).checked=val;
}

var rights_array=new Array();
</script>
<!--box form-->
<!--set $mcnt =0-->
<!--foreach from=$data.stucture item=$module-->
<div style="padding:10px 30px;">
<label for="input_checker_{$mcnt}" style="font-size:30px;"><input name="input_checker_{$mcnt}" type="checkbox" style="width:20px !important;" value="{$module.pk_module}" id="input_checker_{$mcnt}" onChange="select_checkers_from_module({$mcnt},this.checked)"/>{$module.name}</label>
</div>
<script>
rights_array[{$mcnt}]=new Array();
</script>

<!--set $icnt =0-->
<!--set $cleen_fl_mod =1-->
<!--if $module.interfaces.isset()-->
	<!--foreach from=$module.interfaces item=$interface-->
	<div style="padding:10px 60px;">
	<label for="input_checker_{$mcnt}_{$icnt}" style="font-size:20px;"><input name="input_checker_{$mcnt}_{$icnt}" type="checkbox" style="width:20px !important;" value="{$interface.pk_interface}" id="input_checker_{$mcnt}_{$icnt}" onChange="select_checkers_from_interface({$mcnt},{$icnt},this.checked)"/>{$interface.name}</label>
	</div>
	<script>
	rights_array[{$mcnt}][{$icnt}]=new Array();
	</script>
		<!--set $actcnt =0-->
		<!--set $cleen_fl =1-->
		<!--if $interface.actions.isset()-->
		<!--foreach from=$interface.actions item=$action-->

		<script>
		rights_array[{$mcnt}][{$icnt}][{$actcnt}]='input_{$action.pk_action}';
		</script>
		<div style="padding:10px 100px;">
		<label for="input_{$action.pk_action}"><input name="rights[{$action.pk_action}]" type="checkbox" style="width:20px !important;" value="{$action.pk_action}" id="input_{$action.pk_action}" <!--if $data.rights.$action&pk_action.isset()-->checked<!--else--><!--set $cleen_fl =0--><!--/if-->/>{$action.name} () //{$action.call}</label>
		</div>
		<!--set $actcnt ++-->
		<!--/foreach-->
<!--/if-->
<!--if $cleen_fl ==1-->
	<script>
document.getElementById('input_checker_{$mcnt}_{$icnt}').checked=1;
	</script>
<!--else-->
<!--set $cleen_fl_mod =0-->
<!--/if-->

	<!--set $icnt ++-->
	<!--/foreach-->
<!--/if-->
<!--if $cleen_fl_mod ==1-->
	<script>
document.getElementById('input_checker_{$mcnt}').checked=1;
	</script>
<!--/if-->
<!--set $mcnt ++-->
<!--/foreach-->
<!--/box-->
<!--set uninitModule()-->