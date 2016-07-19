<!--box input-->
<input title="{$item.description}" name="_file_{$item.name}" type="file">
<input name="{$item.name}" type="hidden" value="{$data.$item&name}" id="input_id_{$input_id}_1_h">
<!--if ! is_file(FILE_DIR. $data.$item&name )-->
<div class="system">No file</div>
<!--else-->
<div class="system">File uploaded {$null .tsToDate(filemtime(FILE_DIR. $data.$item&name ))}</div>
<!--/if-->

<!--/box-->