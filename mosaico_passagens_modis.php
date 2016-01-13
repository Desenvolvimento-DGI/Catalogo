<?php 

include("session_mysql.php"); 
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include("css.php");
import_request_variables("gpc");

  echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";

$params = $_SERVER['QUERY_STRING'];
 
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

$sensor = $SENSOR;
$date = $DATE;
$satellite = $SATELITE;

// Variables


// tables involved
switch ($satellite)
{
 case "CB2":
	$tabprefix = "Cbers";
	$satname = "CBERS2";
	break;
 case "T1":
 $tabprefix = "Modis";
 $satname = "TERRA";
	break;
 case "A1":
	$tabprefix = "Modis"; 
	$satname = "AQUA";
	break;
 case "L1":
  $tabprefix = "Landsat";
  $satname = "LANDSAT1";
	break;
 case "L2":
 $tabprefix = "Landsat";
  $satname = "LANDSAT2";
	break;
 case "L3":
 $tabprefix = "Landsat";
  $satname = "LANDSAT3";
	break;
 case "L5":
 $tabprefix = "Landsat";
  $satname = "LANDSAT5";
	break;
 case "L7":
  $tabprefix = "Landsat";
  $satname = "LANDSAT7";
	break;

 case "CB2B":
  $tabprefix = "Cbers";
  $satname = "CBERS2B";
  break;
}

 
#
# Find ranges of Path/Row and number of passages
#

# MySQL does automatic conversion between datatypes ; so "Path" and "Row", defined as character strings (VARCHAR) in Database, may be 
# converted into integers (INT) by simply adding a zero ( + 0) to their respective values in the query syntax.
   
$sql = "SELECT DRD, MAX(Path + 0) AS maxpath, MAX(Row + 0) AS maxrow, MIN(Path + 0) AS minpath, MIN(Row + 0) AS minrow FROM Scene
        INNER JOIN $tabprefix" . "Scene ON Scene.SceneId=" . $tabprefix . "Scene.SceneId WHERE Satellite='$satellite' AND Sensor='$sensor' AND Date='$date'
        GROUP BY DRD";

// echo "<br> sql = $sql <br>";

$dbcat->query($sql) or $dbcat->error ($sql);
$num_of_passages = $dbcat->numRows();
if($num_of_passages == 0)die ("<br> <h2 align=center>$strNopassages $date</h2> <br>");

for($i=1;$i <= $num_of_passages;$i++)
{
 $myrow = $dbcat->fetchRow();
 $MAXpath[$i] = $myrow[maxpath];
 $MINpath[$i] = $myrow[minpath];
 $MAXrow[$i] =  $myrow[maxrow];
 $MINrow[$i] =  $myrow[minrow];
}
$dbcat->freeResult();

//print_r($MAXrow);
//print_r($MINrow);

$max_path = max($MAXpath);
$min_path = min($MINpath);
$max_row = max($MAXrow);
$min_row = min($MINrow);

// echo "<br> minpath = $min_path maxpath = $max_path minrow = $min_row maxrow = $max_row<br>";

for ($i=$min_row; $i<=$max_row; $i++)
{ 
 for ($j=$min_path; $j<=$max_path; $j++)
 { 
   $scenes_registered[$i][$j] = 0;
   $scenes_coincident[$i][$j] = 0;
 };
}; 

// Get data from Scene Table
$sql = "SELECT * FROM Scene INNER JOIN $tabprefix" . "Scene ON Scene.SceneId=" . "$tabprefix" . "Scene.SceneId
        WHERE Satellite='$satellite' and Sensor='$sensor' and Date='$date' ORDER BY DRD ASC";
        
//echo "<br> sql = $sql <br>";

$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
if ($itens == 0) die("Erro nas Cenas");

while($myrow = $dbcat->fetchRow())
{
  $path =$myrow['Path'];
  $row = $myrow['Row'];
//  echo "<br> Path = $path row = $row SceneId = $myrow[SceneId] <br>"; 
  if($scenes_registered[$row][$path] != "0") $scenes_coincident[$row][$path] = $scenes_registered[$row][$path];
  $scenes_registered[$row][$path] = $myrow['SceneId'];
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

<h2 align=center><?=$strMosaic1?> <?=$satname?> (<?=$sensor?>) : <?=$date?></h2><br> 
<!-- <h3><?=$strMosaicMsg?></h3> --> 
<table align=center border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; border-spacing:0;" >  

<th>  </th>
<?php 
for($i=$min_path;$i<=$max_path; $i++)
{
  printf("<th>H%d</th>\n",$i);
}

printf("<th> </th>\n");
  
for ($i=$min_row; $i<=$max_row; ++$i)
{ 
?>
<tr style="margin:0;border:0;padding:0;">
<th>&nbsp; V<?=$i?> &nbsp;</th>
<?php 
  for ($j=$min_path; $j<=$max_path; ++$j)
  { 
  ?>	
	<td style="border-style:ridge;border-width:1px;border-color:#FFFFFF"> 
  <?php 
	  $scene_id = $details = $scenes_registered[$i][$j];
	  $coincident = $scenes_coincident[$i][$j]; 
//	 echo "<br> coincident = $coincident scene_id = $scene_id<br>"; 
	  if($scene_id != "0")
	  { 
	    if($coincident == "0") $coincident = $scene_id; 
	    else ;//$details = $coincident;
    ?>
		  <a style="margin:0;padding:0;" href=javascript:openwin('manage.php?SATELITE=<?=$satellite?>&SENSOR=<?=$sensor?>&DONTSHOW=<?=$DONTSHOW?>&MODULE=MOSAIC&IDATE=<?=$date?>&FDATE=<?=$date?>&ORBITAI=<?=$j?>&ORBITAF=<?=$j?>&PONTOI=<?=$i?>&PONTOF=<?=$i?>')>
    <?php 
	  } else 
		  {
			 $scene_id = $coincident = "black";
			 ?>
			  <a style="margin:0;padding:0;border:0;">  
			 <?php
		 	}	 
    ?>
     <img title='<?=$scene_id != "black" ? "$strDetail : H$j x V$i" : ""?>' src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scene_id?>' name='image' scrolling='no' marginwidth='0' frameborder='0' border=0      
      onmouseover="javascript:src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$coincident?>'"
      onmouseout="javascript:src='display.php?TABELA=Thumbnail&PREFIXO=Modis&INDICE=<?=$scene_id?>'"></a></td>     
		<?php
   }?>
   <th> &nbsp; V<?=$i?> &nbsp;</th>  	
	 </tr> 
<?php
}
printf("<th> </th>\n");
for($i=$min_path;$i<=$max_path; $i++)
{
  printf("<th>H%d</th>\n",$i);
}
printf("<th> </th>\n"); 
?>
<tr>
<td colspan=12> </td>
</tr>
<tr>
<td colspan=8 align=center><input type="button" value="<?=$strMosaic2?>" onClick=window.open('mosaico.Modis-Brasil.php?<?=$params?>')></td>
</tr>
<tr><td colspan=8></td></tr>
<tr>
<td colspan=8 align=center><input type="button" value="<?=$strClose?>" onClick=window.location.href='first_<?=$_SESSION['userLang']?>.php'></td>
</tr>
</table>
<div align=center><h2><br>Modis Sinusoidal Grid</h2></div>
<div align=center><img src='../Suporte/images/modis-sinusoidal.gif' width="610" height="339" scrolling='no' marginwidth='0' frameborder='0'></div> 
</body>
</html>