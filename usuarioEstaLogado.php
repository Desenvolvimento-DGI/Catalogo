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



if ( $userLogged ) echo "LOGADO";
else echo "NAOLOGADO";

		
?>

		