<!--box input-->
	<div align="center">
	<!--if  is_file( FILE_DIR. $data.$item&name ) -->
	<img src="!{@ FILE_URL , $data.$item&name}" border="0" style="max-width:500px;">
	<!--else-->
	<div class="system">No image</div>
	<!--/if--></div>

Заменить картинку	<input title="{$item.description}" name="_file_{$item.name}" type="file">
	<input name="{$item.name}" type="hidden" value="{$data.$item&name !=''? $data.$item&name :'none'}">
<!--/box-->