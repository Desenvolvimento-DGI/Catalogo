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

$sql = "DELETE FROM sessions WHERE sesskey = '$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);

 
if ($userLogged)
{
	if (version_compare(PHP_VERSION, '4.3.3') == -1) setcookie(session_name(), session_id());
 	$lang = $_SESSION['userLang'];
	$_SESSION["userId"] = "";  // remove user name from banner


	/*
	$sql = "DELETE FROM Cart WHERE sesskey = '$PHPSESSID'";
	$dbcat->query($sql) or $dbcat->error ($sql);
	
	$sql = "DELETE FROM sessions WHERE sesskey = '$PHPSESSID'";
	$dbcat->query($sql) or $dbcat->error ($sql);
  	*/
	$_SESSION = array();
	//session_destroy();
	//session_regenerate_id(); 
 
#	if (version_compare(PHP_VERSION, '4.3.3') == -1)
#	setcookie(session_name(), session_id());
	/*session_set_save_handler(
		"sess_open",
		"sess_close",
		"sess_read",
		"sess_write",
		"sess_destroy",
		"sess_gc"); */
			
}

header("Location: /catalogo/");

?>
