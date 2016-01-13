<?php
// Globals
include("globals.php");

// Sessions Function
include("session_mysql.php");
sess_gc(get_cfg_var("session.gc_maxlifetime"));
if(!($id = session_id()))
{
   session_start();
   $id = session_id();
}
//sess_gc(get_cfg_var("session.gc_maxlifetime"));
//echo "ID Ses = "  . $id;
//echo " Operador = " . $_SESSION['operatorId'] . " Ip = " .  $_SESSION['operIP'];
?>
<html>
<head>
<title>Cat&aacute;logo de Imagens</title>
</head>
<frameset name="geral" rows="55,*" frameborder="0">
  <frame name="banner" scrolling="no" noresize target="mosaico" src="banner.php">
  <frameset cols="26%,*">
    <frame name="panel" scrolling="yes" target="mosaico" src="panel.php">
	<frame name="mosaico" src="help.php">
</frameset>
<noframes>
<body>
<p>This page uses frames, but your browser doesn't support them.</p>
</body>
</noframes>
</frameset>
</html>
