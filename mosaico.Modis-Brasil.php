<?php 

include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include("css.php");
import_request_variables("gpc");

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";
 
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

if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID Operator = ".$_SESSION['operatorId']." User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";


// Variables
$scenes_registered = array(8-12,10-14);  // 9-14 - horizontal tiles' range to cover most South America; 8-12 - verical tiles' range to cover most South America
$scenes_coincident = array(8-12,10-14);  // registers Path/Row where we have two scenes on the same day (due to a possible overlaping on passages)

$sensor = $SENSOR;
$date = $DATE;
$satellite = $SATELITE;
if($satellite == "T1") $satname = "TERRA";
else $satname = "AQUA";

for ($i=8; $i < 13; ++$i)
{ 
 for ($j=10; $j < 15; ++$j)
 { 
   $scenes_registered[$i][$j] = 0;
   $scenes_coincident[$i][$j] = 0;
 };
}; 

// Get data from Scene Table
$sql = "SELECT * FROM Scene INNER JOIN ModisScene ON Scene.SceneId=ModisScene.SceneId
        WHERE Satellite='$satellite' and Sensor='MODIS' and Date='$date' ORDER BY DRD ASC";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
if ($itens == 0) die("Erro nas Cenas");

$passage_1 = $passage_2 = '0'; // registers the distinct daily passages (sometimes more than 1)

while($myrow = $dbcat->fetchRow())
{
  $path =$myrow['Path'];
  $row = $myrow['Row'];
//  echo "<br> Path = $path row = $row SceneId = $myrow[SceneId] <br>"; 
  if($scenes_registered[$row][$path] != "0") $scenes_coincident[$row][$path] = $scenes_registered[$row][$path];
  if($path <= 14 and $path >= 10 and $row >= 8 and $row <= 12)
	{
	  $scenes_registered[$row][$path] = $myrow['SceneId'];
	  $passage = substr($scenes_registered[$row][$path],22);
		if($passage != $passage_1) 
		 if($passage_1 == '0') $passage_1 = $passage;
		 else if($passage_2 == '0') $passage_2 = $passage;  
	}
}

//echo "<br> passage-1 = $passage_1 passage-2 = $passage_2 <br>";
//print_r($scenes_registered); 

?>
<html>
<head>
<title>Mosaico</title>
<script language="javascript">
function openwin(url) {
	nwin=window.open(url,'MW','');
	nwin.focus();
}

function backgroundChange(sceneid,i,j)
{ alert("Hi");
 document.general.style.position = "absolute";
 document.body.style.backgroundImage = "url('../Suporte/images/bolinha.gif')";
 document.body.style.backgroundRepeat = "no-repeat";
 document.body.style.left = "180";
 document.body.style.top = "130";
 pos1 = 3 + (j-10)*(650/5);
 pos2 = (650/5)*(i-8) + 1;
 document.body.style.backgroundLeft = "pos1";
 document.body.style.backgroundTop = "pos2"; 
}
</script>
</head>

<BODY LINK="#FFFFFF" ALINK="#FFFFFF" VLINK="#FFFFFF">  

<h2 style="position:absolute;top:50;left:325;"><?=$strMosaic1?> <?=$satname?> (<?=$sensor?>) : <?=$date?></h2>

<?php
$passage_on_print = $passage_1;
$shift = 0;
$tab = "tab1"; 
$map = "../Suporte/images/mapa-brasil.gif";
$count = 0; 
$limit = ($passage_1 and $passage_2) != '0' ? 3 : 2; 

for ($pasg=1; $pasg < $limit; ++$pasg)
{
?>
<table name=<?=$tab?> border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border-spacing:0;position:absolute;top:<?=150+ $shift?>;left:<?=207?>;">

<th> </th>
<th>H10</th>
<th>H11</th>
<th>H12</th>
<th>H13</th>
<th>H14</th> 
<th> </th>

<?php  
 
 for ($i=8; $i < 13; ++$i)
{ 
?>
<tr style"margin-top:0;margin-bottom:0;margin-left:0;margin-right:0;">
<th>V<?=$i?></th>
<?php 
for ($j=10; $j < 15; ++$j)
 { 
?>
<td style="border-style:ridge;border-width:1px;border-color:#FFFFFF;">
<?php 
   ++$count;
	 $scene_id = $scenes_registered[$i][$j];
	 if($scene_id == '0') $passage_scene = '0';
	 else $passage_scene = substr($scene_id,22);
	 $coincident = $scenes_coincident[$i][$j];
	 if($coincident == '0') $passage_coincident = '0';
	 else $passage_coincident = substr($coincident,22);
//	 echo "<br> passage_scene = $passage_scene passage_on_print $passage_on_print passage_coincident = $passage_coincident scene_id = $scene_id coincident = $coincident<br>";
	 if($passage_scene != $passage_on_print)
	 {  
		  if($passage_on_print == $passage_coincident) $scene_id = $coincident;
			else $scene_id = "black";		 
   }  

?>
 
<img style="margin-width:0;border-width:0;padding:0;" src="display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scene_id?>" name=<?=$tab?> scrolling='no' marginwidth='0' frameborder='0' border=0></td> 
 
<?php  
 } 
?> 
<th>V<?=$i?></th>
</tr> 
<?php
}
?>
<th></th>
<th>H10</th>
<th>H11</th>
<th>H12</th>
<th>H13</th>
<th>H14</th> 
<th></th>

</table>

<!-- <img src="<?=$map?>" style="position:absolute;top:<?=164 + $shift?>;left:<?=233?>;" height=650 width=650> --> 
 <img src="<?=$map?>" style="position:absolute;top:<?=166 + $shift?>;left:<?=241?>;" height=645 width=641>

<?php
if($limit > 2)
{ 
 $passage_on_print = $passage_2;
 $shift = 800;
 $tab = "tab2";
 $count = 0;
}
} 
?>
<div style="position:absolute;top:<?=865 + $shift?>;left:<?=512?>;" ><input style="font-size:18;" type="button" value="<?=$strClose?>" onClick=window.close()></div>
</body>
</html>