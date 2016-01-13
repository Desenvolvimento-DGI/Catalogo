<?php
include("session_mysql.php"); 
include ("globals.php");
session_start();

//import_request_variables("gpc");

$uflag = false;
$pflag = false;
$aflag = false;
$oflag = false;
$userLogged = isset($_SESSION['userId']) ? true : false;

#
# Delete all possible remaining records from table Cart
#
$dbcat = $GLOBALS["dbcatalog"];
$sql = "DELETE FROM Cart WHERE sesskey = '$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);

$_SESSION['userId']="";
 
if ($userLogged)
{
  if (version_compare(PHP_VERSION, '4.3.3') == -1) setcookie(session_name(), session_id());
	if ($_SESSION['userLang'] == 'PT') echo "<h3 align=center> Sess&atilde;o encerrada, " . $userId . "." . "</h3>"; 
	else echo "<h3 align=center> End session, " . $userId . "." . "</h3>";
	
	$_SESSION["userId"] = "";  // remove user name from banner
?> <script>parent.frames["banner"].location.href ="banner.php?SESSION_LANGUAGE=<?=$_SESSION['userLang']?>"</script> <? // remove user name from the banner

  
	$_SESSION = array();
	session_destroy();
	echo "<meta HTTP-EQUIV='Refresh' CONTENT='0; URL=loginP.php' >";
	//session_regenerate_id(); 
 
#	if (version_compare(PHP_VERSION, '4.3.3') == -1)
#	setcookie(session_name(), session_id());
	session_set_save_handler(
		"sess_open",
		"sess_close",
		"sess_read",
		"sess_write",
		"sess_destroy",
		"sess_gc");
  

}
?>
<html>
<head>
<title>Logout</title>
<?php
include_once 'css.php';
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body,td,th {
	color: #FFFFFF;
}
body {
	background-color: #2E3E3E;
}
-->
</style></head>
<body>
<div>
</div>
</body>
</html>