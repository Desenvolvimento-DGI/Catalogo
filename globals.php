<?php
// Including Globals Definition
include_once ("globals.inc.php");

// Including DB Classes
include_once("stationDB.class.php");
include_once("managerDB.class.php");

//------------------------------------------------------------------------------
// Setting DBGlobals
//------------------------------------------------------------------------------
// Catalogue
if (!isset($GLOBALS["dbcatalog"]))
//   $GLOBALS["dbcatalog"] = new stationDB($GLOBALS["dbhostname"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbcatname"]);
$GLOBALS["dbcatalog"] = new stationDB($GLOBALS["dbhostname"], $GLOBALS["dbport"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbcatname"]);


// User Catalogue
if (!isset($GLOBALS["dbusercat"]))
//   $GLOBALS["dbusercat"] = new stationDB($GLOBALS["dbhostname"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbusercatname"]);
$GLOBALS["dbusercat"] = new stationDB($GLOBALS["dbhostname"], $GLOBALS["dbport"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbusercatname"]);

// Manager
if (!isset($GLOBALS["dbmanager"]))
{
//   $GLOBALS["dbmanager"] = new managerDB($GLOBALS["dbhostname"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbmanname"]);
   $GLOBALS["dbmanager"] = new managerDB($GLOBALS["dbhostname"], $GLOBALS["dbport"], $GLOBALS["dbusername"], $GLOBALS["dbpassword"], $GLOBALS["dbmanname"]);
   $dbman = $GLOBALS["dbmanager"];
//  echo "Manager = " . $dbman->db_name. " - " .  $dbman->db_user . " - " . $dbman->db_passwd . " - " . $dbman->db_host . "<br>";
}
?>