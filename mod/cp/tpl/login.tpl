<?php
global $_interfaces;
if(isset($_interfaces['cp']['action']['show']))
{header('Location: '.SITE_URL.'cp/');
die();}
?>
<html>

<head>
  <title>Logining</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script language="JavaScript" src="reactor/js/basic_api.js"></script>
  <link rel="Stylesheet" href="cp/style/default.css" type="text/css" />
</head>

<body onload="document.getElementById('loginpanel_login').focus()">
<!--include login_panel.tpl-->
<script language="javascript">
document.getElementById('login_panel').style.display='block';
</script>

</body>
</html>