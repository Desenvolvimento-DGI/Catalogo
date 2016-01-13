<?php 
include("session_mysql.php"); 
session_start();
header("Cache-Control: no-cache, must-revalidate");

sess_gc(get_cfg_var("session.gc_maxlifetime"));

include ("globals.php");
include("css.php");
include("cart.class.php");
include_once("price.class.php");
//import_request_variables("gpc");

// echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>" ;
 
// print getEnv("SCRIPT_NAME"); // relative path to DOCUMENT_ROOT
// print_r ($_GET);
// print '<br>';
// print_r ($GLOBALS['HTTP_REFERER']);
// print_r($_SERVER);
// print '<br>';
// print getEnv("SCRIPT_FILENAME"); // absolute path on server
// print_r($GLOBALS);
 
$input = $_SERVER['QUERY_STRING'];

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';

require ("manage_".$_SESSION['userLang'].".php");
require ("mantab_".$_SESSION['userLang'].".php");
require ("product_".$_SESSION['userLang'].".php");

// Globals
$dbcat = $GLOBALS["dbcatalog"];

// --- if ($GLOBALS["stationDebug"])
// ---	echo "Session = $PHPSESSID Operator = ".$_SESSION['operatorId']." User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";

// Variables
$sensor = $SENSOR;
if(isset($DATE))
{
  $date = $DATE;
  $result = explode("-",$date);                                // let's put the string $date in standart form
  if(strlen($result[1]) == 1) $result[1] = "0" . $result[1] ;  // months must have 2 digits (e.g. 01, 02, 03 ... 11, 12)
  if(strlen($result[2]) == 1) $result[2] = "0" . $result[2] ;  // days must have 2 digits (e.g. 01, 02, 03 ... 30, 31)
  $date = implode("-",$result); 
}
else die(" Lacking Date - No Records Recovered !"); 

$satellite = $SATELITE;
if(isset($PASSAGE))
{
 $passage = $PASSAGE;
 $orbit = $ORBIT;
}
$hrc_path = $HRC_PATH;
$action = $ACTION;

if(isset($date)) // Let's construct a generic a string for the purpose of retrieving the correct records based on the DRD (field "file_name" in table "passage") - later bellow
{
 $drd_date_aux = "$result[0]_$result[1]_$result[2]" ;
 if($satellite == "L8")
 {
  $aux = "$result[1]/$result[2]/$result[0]";
  $timestamp = strtotime($aux);
  $drd_date_aux = $result[0] . strftime("%j", $timestamp); 
 } 
};

#
# Let's find if mosaico's scenes are priced
#
if (isset($_SESSION['userId'])) $usertype = $_SESSION['userType'] ;
else $usertype = 2;
$objPrice = new Price($dbcat);
$price = $objPrice->searchPrice($satellite,$sensor,$date,"0",$_SESSION['userLang'],"2",$usertype); 
#

#
# Let's see if user is able to purchasing and if we can show the shopping cart icon
#
$OK_to_purchase = 0;
if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;
$dontshow = 0;
if ($price > 0)
 if (!$OK_to_purchase == 1) $dontshow = 1;
 else ;
else if (!$_SESSION['openArea']) $dontshow = 1;
#

//echo "<br> dontshow = $dontshow  usertype = $usertype Oktopurchase = $OK_to_purchase OpenArea = " . $_SESSION['openArea'] . "<br>";

if ($action == 1) // user is requesting a scene
{ 
  $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$SCENEID'";
  if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux);

  $num_scenes = $dbcat->numRows(); 
  
  if($num_scenes == 0)  // We only insert scenes that are still not in database 
  {  
   $PRODUTO = "";
	 $objCart = new Cart($dbcat); 
	 $objCart->fill($PHPSESSID,$SCENEID,$PRODUTO);
	 $objCart->insert();
	}
}

// tables involved
switch ($satellite)
{
 case "CB2":
	$tabprefix = "Cbers";
	$satname = "CBERS";
	$satnumber = substr($satellite,2,1);
	break;	
case "CB2B":
	$tabprefix = "Cbers";
	$satname = "CBERS";
	$satnumber = substr($satellite,2,2);
	break;
 case "T1": 
 $tabprefix = "Modis";
 $satname = "TERRA";
 $satnumber = substr($satellite,1,1);
	break;
 case "A1":
	$tabprefix = "Modis";
	$satname = "AQUA";
	$satnumber = substr($satellite,1,1);
	break;
 case "L1":
  $tabprefix = "Landsat";
  $satname = "LANDSAT";
  $satnumber = substr($satellite,1,1);
	break;
 case "L2":
  $tabprefix = "Landsat";
  $satname = "LANDSAT";
  $satnumber = substr($satellite,1,1);
	break;
 case "L3":
  $tabprefix = "Landsat";
  $satname = "LANDSAT"; 
  $satnumber = substr($satellite,1,1);
	break;
 case "L5":
  $tabprefix = "Landsat";
  $satname = "LANDSAT";
  $satnumber = substr($satellite,1,1);
	break;
 case "L7":
  $tabprefix = "Landsat";
  $satname = "LANDSAT";
  $satnumber = substr($satellite,1,1);
	break; 
 case "L8":
  $tabprefix = "Landsat";
  $satname = "LANDSAT";
  $satnumber = substr($satellite,1,1);
	break; 
 case "P6":
  $tabprefix = "P6";
  $satname = "P6";
  $satnumber = 1;
	break; 
} 

#
# If an specific path is set (as a searching criteria for the mosaic), let's construct a generic sceneid string for the purpose of retrieving its corresponding DRD
# from table "<satellite>Scene" (e.g. "CbersScene"), and then, retrieve the proper record from table "passage" based on that DRD. This procedure is so performed on account of
# observed discrepancies between values of fields "Date", "Path" and "Orbit" on table "Scene" and their respectivelly corresponding values on table "passage" (due to a bug in d2g process now beeing corrected).
#
if(isset($passage))
{
 $sceneid_generic = $satellite . $sensor . $passage . "%" . $result[0] . $result[1] . $result[2];
 $sql = "SELECT DISTINCT DRD FROM $tabprefix" . "Scene WHERE SceneId LIKE '$sceneid_generic%'";
// echo "<br> sql = $sql <br>"; 
 $dbcat->query($sql) or $dbcat->error ($sql);
 $num_of_records = $dbcat->numRows();
 if ($num_of_records == 0) die("Inconsistency on table $tabprefix.Scene");
 $myrow = $dbcat->fetchRow();
 $drd_record = $myrow[DRD];
}
#
# End segment 
#

$sql = "SELECT DISTINCT(orbit), number_of_scenes as nscenes FROM passage WHERE satellite='$satname' AND satellite_number = '$satnumber' AND instrument='$sensor' ";

if(isset($passage) and $passage != "") $sql .= " AND file_name = '$drd_record' "; 
else $sql .= " AND file_name LIKE '%$drd_date_aux%' ";

if($sensor == "CCD") $sql .= " AND channel = '1' "; 

// echo "<br> sql = $sql <br>"; 

$dbcat->query($sql) or $dbcat->error ($sql);
$num_of_passages = $dbcat->numRows();
if($satellite == "L5" or $satellite == "L7") $complement = "(GLS orbits not included)";
if ($num_of_passages == 0) die("<h2>No Records Recovered $complement !</h2>");

while($myrow = $dbcat->fetchRow())
{
  $numscenes += $myrow[nscenes]; 
}

// echo "<br> num-pass = $num_of_passages<br>";

if(isset($PASSAGE) and $PASSAGE != "")                       
 $complement = "(Path = $PASSAGE  Scenes = $numscenes)";
else $complement = "(Registered Orbits = $num_of_passages  Scenes = $numscenes)"; 
 
?>  
<html>
<head>
<title>Mosaico</title>

<script language="javascript">
function openwin(url) 
{
 	nwin=window.open(url,'MW','');
	nwin.focus();
}

function reenter(input,action,pos)
{
 params = "?" + input + "ACTION=" + action + "&" + "POS=" + pos + "&";; 
 document.location.href="mosaico_passagens.php" + params;
}
</script>


<?php
$spacing = 300;
$k = 0;
  
$sql = "SELECT * FROM Scene WHERE Satellite='$satellite' AND Sensor='$sensor' AND Date='$date' AND SceneId NOT LIKE '%.GLS'"; 
if(isset($PASSAGE) and $PASSAGE != "") $sql .= " AND Orbit='$orbit' "; // if the criteria for the mosaic was the "Path"
$sql .= " ORDER BY StartTime ASC ";
 
// echo "<br> sql = $sql <br>";

$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
if($satellite == "L5" or $satellite == "L7") $complement = "(GLS orbits not included)";
if ($itens == 0) die("<h2>No Records Recovered $complement !</h2>");
 
?>

</head>
<BODY LINK="#FFFFFF" ALINK="#FFFFFF" VLINK="#FFFFFF" onLoad="window.scrollTo(0,'<?=$POS?>')">

<h2 align=center><?=$strMosaic1?> <?=$satname." ".$satnumber?> (<?=$sensor?>) : <?=$date?> <?=$complement?></h2> 
<h3><?=$strMosaicMsg?></h3>


<?php

$path = "";  
while($myrow = $dbcat->fetchRow())
{  
	$new_path = $myrow[Path];
	$orbit = $myrow[Orbit];	
	$scene_id = $myrow["SceneId"];
	$row = $myrow["Row"];	 
	$deleted = $myrow["Deleted"]; 
	if($deleted > 0) $colour = "#FF0000"; else $colour = "#0000FF";
	$result = explode("SCENEID", $input);
	$input = $result[0] . "SCENEID=$scene_id&"; // if user decides requesting this scene
	
	if($new_path != $path)
	{
	 $spacing += 15;
?>
 <h1 style="position:absolute;top:<?=$spacing + $k*512?>;left:340;z-index:1;font-size:30">Path <?=$new_path?> (Orbit <?=$orbit?>)</h1> 
<?php 
   $spacing += 65;
  }
  $path = $new_path;
  
  if($dontshow == 0) // Let's display shopping cart 
  {
?> 
<!-- <input title="<?=$strCart?>" style="position:absolute;top:<?=$k*512 + $spacing + 230?>;left:815;" width=40 height=40 type=image src="../Suporte/images/carrinho.gif" onClick="JavaScript:reenter('<?=$input?>','1',document.body.scrollTop)"> -->
<!-- <img title="<?=$strCart?>" onClick="JavaScript:reenter('<?=$input?>','1',document.body.scrollTop)" style="position:absolute;top:<?=$k*512 + $spacing + 230?>;left:815;" width=40 height=40 src="../Suporte/images/carrinho.gif"> -->
<img title="<?=$strCart?>" onClick="JavaScript:pos = document.body.scrollTop; parent.mosaico.location.href='<?$_SERVER['PHP_SELF']?>?<?=$input?>' + 'ACTION=1&POS=' + pos  + '&';" style="cursor:pointer;position:absolute;top:<?=$k*512 + $spacing + 230?>;left:815;width:30;height:30;"  src="../Suporte/images/carrinho.gif">
<?php
  }
?>
  <h2 style="color:<?=$colour?>;z-index:1;position:absolute;top:<?=$k*512 + $spacing + 220?>;left:415;font-size:30;"> Row <?=$row?></h2>
   <a href=javascript:openwin('manage.php?SATELITE=<?=$satellite?>&SENSOR=<?=$sensor?>&INDICE=<?=$scene_id?>&MODULE=MOSAIC')>
   <img style="width:512;height:512;position:absolute;top:<?=$k*512 + $spacing?>;left:230;" title='<?=$strDetail . " Path " . $path . " Row " . $row?>' src='display.php?TABELA=Browse&PREFIXO=<?=$tabprefix?>&INDICE=<?=$scene_id?>'>
	 </a>
<?php 
  $k += 1;
}
?>
<h1 style="position:absolute;top:<?=$spacing + $k*512?>;left:345;z-index:1;font-size:30px"> <?=$strEndpassage?> </h1> 
<?php 
$spacing += 45;

$dbcat->freeResult();

?>
 <input style="position:absolute;top:<?=$spacing + $k*512 + 20?>;left:435;z-index:1;font-size:15" type="button" value="<?=$strClose?>" onClick=window.location.href='first_<?=$_SESSION['userLang']?>.php'>

</body>
</html>