<?php 
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 


// Includes
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");

import_request_variables("gpc");

// Session


// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

// Searching for cart
$sql = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();


echo "$itens";

?>


