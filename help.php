<?php
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
?>
<html>
<head>
<title>Help</title>
<?php 
include_once("css.php");
?>
</head>
<body>
<?php
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
require ("help_".$_SESSION['userLang'].".php");
?>
</body>
</html>
