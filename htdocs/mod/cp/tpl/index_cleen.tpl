<html>
<head>
  <title>Control panel</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script language="JavaScript" src="reactor/js/basic_api.js"></script>
  <link rel="Stylesheet" href="cp/style/default.css" type="text/css" />
<style>
img {BEHAVIOR: url('{$SITE_URL}mod/cp/style/png.htc')}
</style>

</head>
<body>
<!--include login_panel.tpl-->

<table border="0" cellpadding="0" cellspasing="0" cellpadding=0 cellspacing=0 width="100%">
<tr id="head">
<td width="5%" class="red_bg"></td>
<td width="120" class="left_col"><h1>Reactor</h1></td>
<td width="5%" class="red_bg"><small>v{$null ,REACTOR_VERSION}</small></td>
<td width="70%">
	<table border="0" cellpadding="0" cellspasing="0" width="100%">
	<tr>
	<td width="7%">
	</td>
	<td>


	<!--execute object=$cp action=path template=path.tpl-->

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
</table>


<table border="0" cellpadding="0" cellspasing="0" cellpadding=0 cellspacing=0 width="100%">
<tr id="content">
<td valign="top" id="center">
<!--include description.tpl-->



<!--execute object=$object template=$object->method.$action.cptpl module=$object->method.$action.cptpl_mod-->

</td>
</tr>
</table>



<table border="0" cellpadding="0" cellspasing="0" cellpadding=0 cellspacing=0 width="100%">
<tr><td colspan="7" bgcolor=white height=50></td></tr>
<tr><td colspan="7" bgcolor=black height=2></td></tr>
<tr><td colspan="7" bgcolor="#dcdcdc" height=40>
<!--php-->
global $_languages;
$this->data['_languages']=$_languages;
<!--/php-->
<p style="margin:0 20px;text-align:right;">Language: <select size="1" name="Name" onChange="document.location=this.value" <!--if $object->method.$action.method =='_isForm'-->disabled<!--/if-->>
<!--foreach from=$_languages item=$item key=$k-->
  <option value="<!--url var=lng value=$k-->" <!--if $_reactor.language == $k-->selected<!--/if-->>&nbsp;&nbsp;{$k}&nbsp;&nbsp;&nbsp;</option>
<!--/foreach-->
</select></p>

</td></tr>
</table>







</body>

</html>