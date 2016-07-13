<html>
<head>
	<title>Control panel</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script language="JavaScript" src="cp/js/basic_api.js"></script>
	<script type="text/javascript" language="Javascript">var _rtConsole = true;</script>
	<script language="JavaScript" src="reactor/js/rt.2.0.js"></script>
	<script language="JavaScript" src="cp/js/list.js"></script>
	<script language="JavaScript" src="cp/js/city_collation.js"></script>
	<script language="JavaScript">
	_rt().requestUrl('{$SITE_URL}ajax_request/?');
	preloadImage('{$_reactor.module.url}images/sb_bottom.png');
	preloadImage('{$_reactor.module.url}images/sb_bottom_left.png');
	preloadImage('{$_reactor.module.url}images/sb_bottom_right.png');
	preloadImage('{$_reactor.module.url}images/sb_top.png');
	preloadImage('{$_reactor.module.url}images/sb_top_left.png');
	preloadImage('{$_reactor.module.url}images/sb_top_right.png');
	preloadImage('{$_reactor.module.url}images/sb_right.png');
	preloadImage('{$_reactor.module.url}images/sb_left.png');


	preloadImage('{$_reactor.module.url}images/wb_bottom_left.gif');
	preloadImage('{$_reactor.module.url}images/wb_bottom_right.gif');
	preloadImage('{$_reactor.module.url}images/wb_top_left.gif');
	preloadImage('{$_reactor.module.url}images/wb_top_right.gif');

	_rt(document).bind('mousemove',updateMousePosition);

	setInterval(function(){ var t=new Date(); _rt().request({'t':t.valueOf()});},500*1000);
	</script>
	<link rel="Stylesheet" href="cp/style/default.css" type="text/css" />
	<style>
	img {BEHAVIOR: url('{$SITE_URL}mod/cp/style/png.htc')}
	</style>

</head>
<body>
<!--include login_panel.tpl-->



<div id="popup_layer" onclick='hide_list_menu()'></div>
<div id="popup_frame" class="popUpMenu"></div>

         <div id="shadowBox"><table border="0" cellpadding="0" cellspacing="0" class="shadowBox">
<tr class="top"><td class="left"></td><td></td><td class="right"></td></tr>
<tr><td class="left"></td><td id="shadowBoxContent"></td><td class="right"></td></tr>
<tr class="bottom"><td class="left"></td><td></td><td class="right"></td></tr>
</table></div>

<div id="whiteBox"><table border="0" cellpadding="0" cellspacing="0" class="whiteBox">
<tr class="top"><td class="left"></td><td id="whiteBoxContentWidth"></td><td class="right"></td></tr>
<tr><td id="whiteBoxContent" colspan="3"></td></tr>
<tr class="bottom"><td class="left"></td><td></td><td class="right"></td></tr>
</table></div>




<table border="0" cellpadding="0" cellspasing="0" cellpadding=0 cellspacing=0 width="100%" height="100%">
<tr id="head">
<!--set $switch =$_RGET-->
<!--if $switch.cp_wild.isset()-->
<!--set $cp_wild =1-->
<!--set $switch.cp_wild.unset()-->
<!--else-->
<!--set $cp_wild =0-->
<!--set $switch.cp_wild =1-->
<!--/if-->
<!--set  $switch = $switch.compileURL()-->
<td width="5%" class="red_bg" onclick="document.location='{$switch}'"></td>
<td width="120" class="left_col" onclick="document.location='{$switch}'"><h1>Reactor</h1></td>
<td width="5%" class="red_bg" onclick="document.location='{$switch}'"><small>v{@ $null ,REACTOR_VERSION}</small></td>
<td width="70%">
	<table border="0" cellpadding="0" cellspasing="0" width="100%">
	<tr>
	<td width="7%">
	</td>
	<td>
	<!--execute interface=$cp_pool_id action=path template=path.tpl-->
	</td>
	<td width="7%">
	</td>
	</tr>
	</table>
</td>
<td width="5%"></td>
<td width="120" class="right_col">
<a href="#" onclick="show('login_panel');document.getElementById('loginpanel_login').focus();return false;">{$_user.login}</a>
</td>
<td width="5%"></td>
</tr>

<tr id="content">
<!--if $cp_wild ==0-->
<td colspan="3" valign="top" id="left_menu">
<!--execute interface=$cp_pool_id action=menu template=menu_left.tpl-->
</td>
<!--/if-->
<td valign="top" id="center" <!--if $cp_wild ==1-->colspan="7"<!--/if-->>
<!--if $help !=''--><div align="right"><a href="/cp/?interface=reactor_help&action=show&pk_help={$help}" class="help_button">Справка</a></div><!--/if-->
<!--include description.tpl-->
<!--execute template=$action.cptpl module=$action.cptpl_mod-->
</td>
<!--if $cp_wild ==0-->
<td colspan="3" valign="top" id="right_menu">
<!--include object_actions.tpl-->
</td>
<!--/if-->
</tr>
<tr><td colspan="7" bgcolor=white height=50></td></tr>
<tr><td colspan="7" bgcolor=black height=2></td></tr>
<tr><td colspan="7" bgcolor="#dcdcdc" height=40>
<!--php-->
global $_languages;
$this->data['_languages']=$_languages;
<!--/php-->
<p style="margin:0 20px;text-align:right;">Language: <select size="1" name="Name" onChange="document.location=this.value" <!--if $action.method =='_isForm'-->disabled<!--/if-->>
<!--foreach from=$_languages item=$item key=$k-->
  <option value="<!--url var=lng value=$k-->" <!--if $_reactor.language == $k-->selected<!--/if-->>&nbsp;&nbsp;{$k}&nbsp;&nbsp;&nbsp;</option>
<!--/foreach-->
</select></p>

</td></tr>
</table>
</body>
</html>