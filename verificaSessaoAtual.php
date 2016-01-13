<?php
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
import_request_variables("gpc");

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
//$sql = "DELETE FROM Cart WHERE sesskey = '$PHPSESSID'";
//$dbcat->query($sql) or $dbcat->error ($sql);


 
if ($userLogged)
{
	if (version_compare(PHP_VERSION, '4.3.3') == -1) setcookie(session_name(), session_id());
 	$lang = $_SESSION['userLang'];
	$_SESSION["userId"] = "";  // remove user name from banner

  
  
	$sql = "DELETE FROM sessions WHERE sesskey = '$PHPSESSID'";
	$dbcat->query($sql) or $dbcat->error ($sql);
  
	$_SESSION = array();
	session_destroy();
	session_regenerate_id(); 
 
#	if (version_compare(PHP_VERSION, '4.3.3') == -1)
#	setcookie(session_name(), session_id());
/*
	session_set_save_handler(
		"sess_open",
		"sess_close",
		"sess_read",
		"sess_write",
		"sess_destroy",
		"sess_gc");*/
}

echo "OK";

?>
