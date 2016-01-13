<?php


if (!isset($GLOBALS["dbcatalog"]))
{
  include_once("globals.inc.php");
  include_once("stationDB.class.php");  

	if (!isset($GLOBALS["dbcatalog"]))
	$GLOBALS["dbcatalog"] = new stationDB($GLOBALS["dbhostname"], $GLOBALS["dbport"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"] , $GLOBALS["dbcatname"]);
}

$SESS_LIFE = get_cfg_var("session.gc_maxlifetime");



$ano='2014';
$mes='08';
$dia='04';

$data_amd="$ano-$mes-$dia";
$comando="echo $(date +'%j' -d $data_amd)";		

$ano_juliano=substr($ano,2,2);		
$dia_juliano=shell_exec($comando);

$adia_juliano = substr($dia_juliano,0,strlen($dia_juliano)-1);	
$adia_juliano = rtrim($dia_juliano);

echo "<pre>\n";
echo "\n$data_amd  .\n";
echo "\n$comando  .\n";
echo "\n$dia_juliano  .\n";
echo "\n$adia_juliano  .\n";

echo "</pre>\n";

echo "<br><br>\n";
echo "<pre>\n - ";
echo "\nUNIX_TIMESTAMP() = " . " UNIX_TIMESTAMP() ";
echo "<br><br>\n";
echo "</pre>\n";


$GLOBALS["dbcatalog"]->query("SELECT * FROM sessions WHERE expiry < UNIX_TIMESTAMP()");
//$qry = "SELECT value FROM sessions WHERE sesskey = '$key' AND expiry > " . time();
//$GLOBALS["dbcatalog"]->query($qry);

list($value) = $GLOBALS["dbcatalog"]->fetchRow();

echo "<br><br>\n";
echo "<pre>\n UNIX_TIMESTAMP";
var_dump ( $value );
echo "<br><br>\n";
echo "</pre>\n";




$GLOBALS["dbcatalog"]->query("SELECT * FROM sessions WHERE expiry <  " . time());
//$qry = "SELECT value FROM sessions WHERE sesskey = '$key' AND expiry > " . time();
//$GLOBALS["dbcatalog"]->query($qry);

list($value) = $GLOBALS["dbcatalog"]->fetchRow();

echo "<br><br>\n";
echo "<pre>\n TIME = " . time() . "<br><br>\n";
var_dump ( $value );
echo "<br><br>\n";
echo "</pre>\n";













$GLOBALS["dbcatalog"]->query("SELECT * FROM cart WHERE expiry <  " . time());
//$qry = "SELECT value FROM sessions WHERE sesskey = '$key' AND expiry > " . time();
//$GLOBALS["dbcatalog"]->query($qry);

list($value) = $GLOBALS["dbcatalog"]->fetchRow();

echo "<br><br>\n";
echo "<pre>\n TIME = " . time() . "<br><br>\n";
var_dump ( $value );
echo "<br><br>\n";
echo "</pre>\n";



?>
