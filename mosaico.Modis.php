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
$scenes_registered = array(8-12,9-14);  // 9-14 - horizontal tiles' range to cover most South America; 8-12 - verical tiles' range to cover most South America
$scenes_coincident = array(8-12,9-14);  // registers Path/Row where we have two scenes on the same day (due to a possible overlaping on passages)

for ($i=8; $i < 13; ++$i)
{ 
 for ($j=9; $j < 15; ++$j)
 { 
   $scenes_registered[$i][$j] = 0;
   $scenes_coincident[$i][$j] = 0;
 };
}; 

// Get data from Scene Table
$sql = "SELECT * FROM Scene INNER JOIN ModisScene ON Scene.SceneId=ModisScene.SceneId
        WHERE Satellite='T1' and Sensor='MODIS' and Date='$Date' ORDER BY DRD ASC";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
if ($itens == 0) die("Erro nas Cenas");

while($myrow = $dbcat->fetchRow())
{
  $path =$myrow['Path'];
  $row = $myrow['Row'];
//  echo "<br> Path = $path row = $row SceneId = $myrow[SceneId] <br>"; 
  if($scenes_registered[$row][$path] != "0") $scenes_coincident[$row][$path] = $scenes_registered[$row][$path];
  if($path <= 14 and $path >= 9 and $row >= 8 and $row <= 12) $scenes_registered[$row][$path] = $myrow['SceneId'];
}
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
</script>
</head>

<BODY LINK="#FFFFFF" ALINK="#FFFFFF" VLINK="#FFFFFF">  

<h3 align=center><?=$strMosaic1?>  <?=$Date?></h3>
<table align=center border="0" cellspacing="0" cellpadding="0" >  
 
<th>  </th>
<th>H9</th>
<th>H10</th>
<th>H11</th>
<th>H12</th>
<th>H13</th>
<th>H14</th>
<?php
    
 for ($i=8; $i < 13; ++$i)
{ 
?>
 <tr>
 <td>V<?=$i?></td>
<?php 
 for ($j=9; $j < 15; ++$j)
 { 
?>
	<td> 
<!--		  <iframe src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scenes_registered[$i][$j]?>' name='image' width='100' height='100' scrolling='no' marginwidth='0' frameborder='0'></iframe> -->
  <?php 
	 $scene_id = $details = $scenes_registered[$i][$j];
	 $coincident = $scenes_coincident[$i][$j]; 
//	 echo "<br> coincident = $coincident scene_id = $scene_id<br>";
	 if($scene_id != "0")
	 { 
	   if($coincident == "0") $coincident = $scene_id;
	   else ;//$details = $coincident;
 ?> 
  <a href=javascript:openwin('manage.php?&SENSOR=<?=$Sensor?>&DONTSHOW=<?=$DONTSHOW?>&MODULE=MOSAIC&IDATE=<?=$Date?>&FDATE=<?=$Date?>&ORBITAI=<?=$j?>&ORBITAF=<?=$j?>&PONTOI=<?=$i?>&PONTOF=<?=$i?>')>
 <?php 
	 } else $scene_id = $coincident = "black";
	 
 ?>
<img title='<?=$strDetail?>' src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scene_id?>' name='image' scrolling='no' marginwidth='0' frameborder='0' border=0      
 onmouseover="javascript:src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$coincident?>'"
 onmouseout="javascript:src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scene_id?>'"></a></td>	   
<?php
 }
?>
 </tr> 
<?php
} 
?>
<td colspan=7 align=center><input type="button" value="<?=$strClose?>" onClick=window.close()></td>
</table>
<div align=center><img src='../Suporte/images/modis-sinusoidal.gif' width="600" height="300" scrolling='no' marginwidth='0' frameborder='0'></div> 
</body>
</html>
