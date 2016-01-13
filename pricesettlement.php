<?php 
import_request_variables("gpc");
//include_once ("stationDB.php"); // already included in "managerDB.php" which is included in "dbglobals.php"
include_once ("dbglobals.inc.php");
//include_once("globals.inc.php"); // already included in "stationDB.php"
$dbcat = $GLOBALS["dbcatalog"];
$dbcad = $GLOBALS["dbcadastro"];
$sql  = "SELECT * FROM price WHERE Satellite  = '" . $parSat . "'";
$sql .= " and Sensor = '" . $parSensor . "'" . " and Corrlevel = '" . $parCorrlevel . "'";
if (!$dbcat->query($sql)) die ($dbcat->error ($sql));
$numrows = $dbcat->numrows();
if (!$numrows) die ("<br><br> Atributos inexistentes na tabela price !");
$scene_date = strtotime($IDate);
while($myrow = $dbcat->fetchRow()) 
{
 $policy = $myrow[Policy];
 $date = $myrow[Date];
 $date = strtotime(trim($date));
 $period = $myrow[Period];
 $dutyclass = $myrow[Dutyclass];
 $sql1  = "SELECT userType FROM User WHERE userid = '".$userid."'";
 if (!$dbcad->query($sql1)) die ($dbcad->error ($sql1));
 $numrows = $dbcad->numrows();
 if (!$numrows) die ("<br><br> User inexistente na tabela User !");
 $myrow1 = $dbcad->fetchRow();
 $usertype = $myrow1[userType];
 if ($usertype == $userclass)
 {
  if (trim($policy) == "A")  // Absolute Policy
  {
   if ($scene_date <= $date) $price = $myrow[Price];
	} else if (trim($policy) == "R") // Relative Policy
         {
				  $today_time = time();
					$threshold = $today_time - $period * (365 * 24 * 60 * 60);  // não considerei os dias a mais dos bissestos 
          $difference = $threshold - $scene_date;
					if ($difference >= 0) $price = $myrow[Price];
					}
 }
};
if (!$price) die ("<br><br> Atributos inexistentes na tabela price !");	  

?>

