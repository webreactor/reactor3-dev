<!--box input-->
<div style="width:100%;text-align:right;padding-bottom:5px;white-space:5px;" class="system">
Visio editing
<span style="word-spacing:10px;">
:
<a style="border-bottom:1px dashed black;" class="system" href="javascript:toggleEditor('{$item.name}_{$_form_id}_{$_input_id}')" id="{$item.name}_{$_form_id}_{$_input_id}_d">Enable</a>
<a style="border-bottom:1px dashed black;" class="red system" href="javascript:toggleEditor('{$item.name}_{$_form_id}_{$_input_id}')" id="{$item.name}_{$_form_id}_{$_input_id}_e">Disable</a>
</span></div>
<!--if ! $item.base_type_param.input.height.isset()-->
<!--set $item.base_type_param.input.height ='400px'-->
<!--/if-->
<textarea name="{$item.name}" style="height:{$item.base_type_param.input.height};"  class="wild_textarea" id="{$item.name}_{$_form_id}_{$_input_id}" title="{$item.description}">{$data.$item&name}</textarea>
<script>
textareaHeighter("{$item.name}_{$_form_id}_{$_input_id}");
</script>

<!--/box-->