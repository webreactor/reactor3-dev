<!--version 2.0-->
<!--php-->
global $_base_types;
$this->data['_base_types']=&$_base_types;
<!--/php-->
<!--box form-->
	<!--foreach from=$form.object->define item=$item-->
	<!--set $type = $_base_types.$item&base_type-->
	<!--input type=$type-->
	<!--/foreach-->
<!--/box-->