<!--box input-->


<input name="{$item.name}" id="{$item.name}_{$_form_id}_{$_input_id}" title="{$item.description}" type="text" value="{$data.$item&name}" style="width:80px;" <!--if $item.base_type_param.input.disabled.isset()-->disabled<!--/if-->>
<!--if ! $item.base_type_param.input.disabled.isset()-->
<a href="#" type="button" id="{$item.name}_{$_form_id}_{$_input_id}_2" style="border-bottom:1px dashed black;" class="system">Выбрать дату</a>
<script type="text/javascript">
Calendar.setup({
	inputField    : "{$item.name}_{$_form_id}_{$_input_id}",
	button        : "{$item.name}_{$_form_id}_{$_input_id}_2",
	ifFormat     : "%d.%m.%Y",
	daFormat     : "%d.%m.%Y"
});
</script>
<!--/if-->
<!--/box-->