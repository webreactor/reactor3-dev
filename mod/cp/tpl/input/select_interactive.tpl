<!--box input-->
<a id="{$item.name}_{$_form_id}_{$_input_id}_text" class="ajax_link">Загружается...</a>
<input name="{$item.name}" type="hidden" value="{$data.$item&name}" id="{$item.name}_{$_form_id}_{$_input_id}">

<script type="text/javascript">
var sel_int{$_input_id}=new sel_int('#{$item.name}_{$_form_id}_{$_input_id}','#{$item.name}_{$_form_id}_{$_input_id}_text','{$data.$item&name}',{$item.base_type_param.input.json_encode()});

</script>
<!--/box-->

<!--
$data=array(
'input'=>array(
'intSelect'=>array('interface'=>'catalog_good','action'=>'getSelect')
)
);

-->