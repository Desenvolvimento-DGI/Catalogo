<?php 

include("session_mysql.php"); 
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include("css.php");
import_request_variables("gpc");

// echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>";
  
// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';

// Globals
$dbcat = $GLOBALS["dbcatalog"];

if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID Operator = ".$_SESSION['operatorId']." User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";

// Variables
$sensor = $SENSOR;
$satellite = $SATELITE;
$path = strtoupper($PATH);

require ("product_".$_SESSION['userLang'].".php");

#
# Find Passages of ofbit = PATH
#
$sql = "SELECT DISTINCT Date, Path, Orbit FROM `Scene` WHERE Satellite = '$satellite' AND Sensor = '$sensor' AND SceneId NOT LIKE '%.GLS'"; 
if(isset($path) and $path != "")
 if($sensor == "HRC") $sql .= " AND (Path ='" . $path . "_A'" . 
                              "  OR  Path ='" . $path . "_B'" .
															"  OR  Path ='" . $path . "_C'" .
															"  OR  Path ='" . $path . "_D'" .
															"  OR  Path ='" . $path . "_E') ";
 else $sql .= " AND Path = '$path' "; 
$sql .= " ORDER BY Date ";

// echo "<br> sql = $sql <br>";

$dbcat->query($sql) or $dbcat->error ($sql);
$numrows = $dbcat->numRows();
$complement = "";
if($satellite == "L5" or $satellite == "L7") $complement = "(GLS orbits not included)";
if ($numrows == 0) die ("<h2>No Records Recovered $complement !</h2>");

?> 
<html>
<head>
<title>Passagens</title>
</head>
<body>
<h2><big><?=$strPassages1?></big></h2><br><br> 

<?php
$orbit = ""; 
while ($row = $dbcat->fetchRow())
{
  $new_orbit = $row[Orbit];
  if($new_orbit != $orbit)
  {
?>
<big><em><b>&nbsp;<a title='<?=$strPassages2?>' style="font-size:15" href="mosaico_passagens.php?ORBIT=<?=$row[Orbit]?>&SENSOR=<?=$sensor?>&DATE=<?=$row[Date]?>&SATELITE=<?=$satellite?>&PASSAGE=<?=$path?>&HRC_PATH=<?if($sensor == "HRC")echo "$row[Path]"?>&">
<?=$row[Date]?><?if($sensor == "HRC") echo "($row[Path])"?></a>&nbsp;</b></em></big>
<?
  };
  $orbit = $new_orbit;
}
?>
</body>
</html>
