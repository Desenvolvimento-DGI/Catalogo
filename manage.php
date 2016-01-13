<?php
//------------------------------------------------------------------------------
// Alterado Denise
//
// >>>v1
//------------------------------------------------------------------------------
?>
<?php
include("session_mysql.php");
include_once("price.class.php");
session_start();
header("Cache-Control: no-cache, must-revalidate");

$limit = 10000; // Set limits for the number of records to retrieved each time a query is addressed to the database.

$localuserLang=$_SESSION['userLang'];
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
require ("manage_".$_SESSION['userLang'].".php");
require ("product_".$_SESSION['userLang'].".php");
?>
<html>
<head>
<title>Cadastro de Cenas</title>
<script language="javascript"> 
<!--
function openwin_pro(url) {
  nwin2=window.open(url,'windowProd','resizable=yes,scrollbars=yes,'); 
	nwin2.focus();
}
-->
</script>
<?php 
require("css.php");
?>
</head>
<body topmargin="0" marginwidth="0">
<?php
include("globals.php");
include_once("stationDB.class.php");
include("cart.class.php");
$dbcat = $GLOBALS["dbcatalog"];
$table="Scene";

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";

if($SATELITE == "GLS") 
{
 $SATELITE = "L[5,7]";
 $SENSOR = "[ETM,TM]";
 $condition = "REGEXP";
 $complement = " SceneId LIKE '%GLS' AND ";
}
else 
//if($SATELITE == "L5" or $SATELITE == "L7") { $condition = "=" ; $complement = " SceneId NOT LIKE '%GLS' AND ";} else
{
 $condition = "=";
 $complement = "";
}

if ($sql == "")
{
//    $sql = "SELECT * FROM $table WHERE Deleted=0 AND   ";
//    $sql = "SELECT * FROM $table WHERE Deleted=0 AND CloudCoverMethod='M' AND   ";

if(isset($MODULE) and $MODULE == "MOSAIC") $sql = "SELECT * FROM $table WHERE ";
// else $sql = "SELECT * FROM $table WHERE 1 AND ";
else $sql = "SELECT * FROM $table WHERE Deleted='0' AND CloudCoverMethod='M' AND   ";
  
	if ($SATELITE)
		$sql .= "Satellite $condition '".$SATELITE."' AND  $complement ";
	if ($INDICE)
		$sql .= "SceneId ='".$INDICE."'  AND   ";
	if ($SENSOR)
		$sql .= "Sensor $condition '".$SENSOR."' AND   ";
	if ($LAT1)
		$sql .= "CenterLatitude >= $LAT1 AND   ";
	if ($LAT2)
		$sql .= "CenterLatitude <= $LAT2 AND   ";
	if ($LON1)
		$sql .= "CenterLongitude >= $LON1 AND   ";
	if ($LON2)
		$sql .= "CenterLongitude <= $LON2 AND   ";	
		
switch ($SATELITE)
{
 case "UKDMC":
      if(isset($ORBITAI)) $ORBITAI = trim($ORBITAI); 
      if(isset($ORBITAF)) $ORBITAF = trim($ORBITAF); 
      if(isset($PONTOI)) $PONTOI = trim($PONTOI); 
      if(isset($PONTOF)) $PONTOF = trim($PONTOF);
			if ($ORBITAI && !isset($ORBITAF)) $sql .= "Path='$ORBITAI' AND   ";
	    else
	    {
		   if ($ORBITAI) $sql .= "Path>='$ORBITAI' AND   ";
		   if (trim($ORBITAF) != "") $sql .= "Path<='$ORBITAF' AND   ";
	    }
	    if ($PONTOI && !$PONTOF) $sql .= "Row='$PONTOI' AND   ";
	    else
	    {  
		   if ($PONTOI) $sql .= "Row>='$PONTOI' AND   ";
		   if (trim($PONTOF) != "") $sql .= "Row<='$PONTOF' AND   ";
	    }			 
      break;
 default:
      $result_orbitaI = explode("_",$ORBITAI);
	    $result_orbitaF = explode("_",$ORBITAF);	
	    if ($ORBITAI && !isset($ORBITAF))
		  $sql .= "SUBSTRING_INDEX(Path,'_',1) + 0 = SUBSTRING_INDEX('$ORBITAI','_',1) + 0 AND    ";
	    else
	    {
		    if ($ORBITAI)
		    {
		     if($result_orbitaI[1] == "") $result_orbitaI[1] = 'A';
         $sql .= "10*SUBSTRING_INDEX(Path,'_',1) + ASCII(ELT(left(Path regexp '[[:alpha:]]+$',1)+1,'$result_orbitaI[1]',SUBSTRING_INDEX(Path,'_',-(left(Path regexp '[[:alpha:]]+$',1))))) >= 10*SUBSTRING_INDEX($result_orbitaI[0],'_',1) + ASCII('$result_orbitaI[1]')  AND   ";
		    }
		  if (trim($ORBITAF) != "") 
		  {
		   if($result_orbitaF[1] == "") $result_orbitaF[1] = 'E';
       $sql .= "10*SUBSTRING_INDEX(Path,'_',1) + ASCII(ELT(left(Path regexp '[[:alpha:]]+$',1)+1,'$result_orbitaF[1]',SUBSTRING_INDEX(Path,'_',-(left(Path regexp '[[:alpha:]]+$',1))))) <= 10*SUBSTRING_INDEX($result_orbitaF[0],'_',1) + ASCII('$result_orbitaF[1]') AND   ";
	    }
	   }
	   if ($PONTOI && !$PONTOF)
		 $sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 = SUBSTRING_INDEX('$PONTOI','_',1) + 0 AND   ";
	   else
	   {
		  if ($PONTOI)
			$sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 >= SUBSTRING_INDEX('$PONTOI','_',1) + 0 AND   ";
		  if ($PONTOF)
			$sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 <= SUBSTRING_INDEX('$PONTOF','_',1) + 0 AND   ";
	   }
      break;
}

	if ($IDATEM && $FDATEM)
	{
		if ($IDATEM > $FDATEM)
		{
			$sql .= "(month(Date)>=" . $IDATEM . " OR   ";
			$sql .= "month(Date)<=" . $FDATEM . ") AND   ";
		}
		else if ($IDATEM == $FDATEM)
		{
			$sql .= "month(Date)=" . $IDATEM . " AND   ";
		}
		else
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	}
	else if ($IDATEM)
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
		}
	else if ($FDATEM)
		{
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	if ($IDATEY && $FDATEY)
	{
		if ($IDATEY == $FDATEY)
		{
			$sql .= "year(Date)=" . $IDATEY . " AND   ";
		}
		else
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	}
	else if ($IDATEY)
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
		}
	else if ($FDATEY)
		{
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	if ($IDATE)
//		$sql .= "Date>='" . $IDATE . " 00:00:00' AND   ";
      $sql .= "Date>='" . $IDATE . "' AND   ";
	if ($FDATE)
//		$sql .= "Date<='" . $FDATE . " 23:59:59' AND   ";
      $sql .= "Date<='" . $FDATE . "' AND   ";
	if (isset($Q1))
		$sql .= "CloudCoverQ1<=$Q1 AND   ";
	if (isset($Q2))
		$sql .= "CloudCoverQ2<=$Q2 AND   ";
	if (isset($Q3))
		$sql .= "CloudCoverQ3<=$Q3 AND   ";
	if (isset($Q4))
		$sql .= "CloudCoverQ4<=$Q4 AND   ";

	$sql = substr($sql,0,strlen($sql)-6);

//	$sql .= "ORDER BY Date DESC,Path,Row LIMIT 1000";
  $sql .= "ORDER BY Date Desc, StartTime ASC LIMIT $limit ";
	$pagina = 1;
}
else
	$sql = stripslashes($sql);
	

// Verificando se foram escolhidos produtos
$prodId = "";
for($i = 0; $i < $tamMat; $i++)
{
   $var = "action" . $i;
   $var1 = "prodId" . $i;
   if(isset($$var))
   {
      $prodId = $$var1;
      break;
   }
}
if ($action == $strCart or $prodId)
{  
  $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$SceneId'"; 
  if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux); 

  $nscenes = $dbcat->numRows();

  if($nscenes == 0)  // We only insert scenes that are still not in database. 
  { 
	 $objCart = new Cart($dbcat);
	 $objCart->fill($PHPSESSID,$SceneId, $prodId);
	 $objCart->insert();
	}
}

if ($ScrollAction != "")
{
	if ($ScrollAction == " < ")
		$pagina = $pagina - 1;
	else
		$pagina = $pagina + 1;
}

// --- if ($GLOBALS["stationDebug"]) echo "<br> sql = $sql <br>\n";

if (!$dbcat->query($sql))
	die ($dbcat->errorMessage());

$paginas = $dbcat->numRows();

if ($paginas == 0)
{
	echo "$strNoScene !";
	exit;
}
if ($pagina >= $paginas) 
	$pagina = $paginas;
if ($pagina==0 && $paginas>0)
	$pagina = 1;

// Se algum registro foi recuperado, posicione o cursor em page-1 e pegue o resultado.

if (!($row_array = $dbcat->fetchRow($pagina-1)))
	die ($dbcat->errorMessage());
$dbcat->freeResult();

// Armazena sat�ite e cena
$sat = substr($row_array["Satellite"],0,2);
$scene =$row_array["SceneId"];

#
# Code Segment to evaluate purchase conditions for displaying of cart icons
#
$OK_to_purchase = 0;
if (isset($_SESSION['userId'])) $usertype = $_SESSION['userType'] ;
else $usertype = 2;
if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;
$objPrice = new Price($dbcat);
$priced = $objPrice->searchPrice($row_array[Satellite],$row_array[Sensor],$row_array[Date],"0",$_SESSION['userLang'],"2",$usertype); 

$dontshow = 0;
if ($priced > 0)
 if (!$OK_to_purchase == 1) $dontshow = 1;
 else ;
else if (!$_SESSION['openArea']) $dontshow = 1;

// echo "<br>sat = $row_array[Satellite] usertype = $usertype priced = $priced OK = $OK_to_purchase dontshow = $dontshow<br>";
#
# End Segment
#

$tab_sat_prefix = "";
switch ($sat)
{
  case "CB":
	$supsql = "SELECT * FROM CbersScene WHERE SceneId='".$row_array["SceneId"]."'";
	$dbcat->query($supsql);
	if (!($suprow_array = $dbcat->fetchRow()))
		die ($dbcat->errorMessage());
	$gralha = $suprow_array["Gralha"];
	$tab_sat_prefix = "Cbers";
	break;
  case "T1":
  case "A1":
	$supsql = "SELECT * FROM ModisScene WHERE SceneId='".$row_array["SceneId"]."'";
	$dbcat->query($supsql);
	if (!($suprow_array = $dbcat->fetchRow()))
		die ($dbcat->errorMessage());
	$gralha = $suprow_array["Gralha"];
	$tab_sat_prefix = "Modis";
	break;
  case "L1":
  case "L2":
  case "L3":
  case "L5":
  case "L7":
  case "L8":
	$supsql = "SELECT * FROM LandsatScene WHERE SceneId='".$row_array["SceneId"]."'";
	$dbcat->query($supsql);
	if (!($suprow_array = $dbcat->fetchRow()))
	    die ($dbcat->errorMessage());
	$tab_sat_prefix = "Landsat";
	break;
  case "P6":
	 $supsql = "SELECT * FROM P6Scene WHERE SceneId='".$row_array["SceneId"]."'";
	 $dbcat->query($supsql);
	 if (!($suprow_array = $dbcat->fetchRow()))
	    die ($dbcat->errorMessage());
	 $tab_sat_prefix = "P6";
	 break;
  case "UK":
	$supsql = "SELECT * FROM UKDMCScene WHERE SceneId='".$row_array["SceneId"]."'";
	$dbcat->query($supsql);
	if (!($suprow_array = $dbcat->fetchRow()))
		die ($dbcat->errorMessage());
	$gralha = $suprow_array["Gralha"];
	$tab_sat_prefix = "UKDMC";
	break;
}
$dbcat->freeResult();
$ntimes = $ntimes + 1; 

?>
<form method="GET" action="manage.php">
<input type="hidden" name="sql" value="<?php echo $sql;?>">
<input type="hidden" name="pagina" value="<?php echo $pagina;?>">
<input type="hidden" name="paginas" value="<?php echo $paginas;?>">
<input type="hidden" name="SceneId" value="<?=$row_array["SceneId"]?>">
<input type="hidden" name="Satellite" value="<?=$row_array["Satellite"]?>">
<input type="hidden" name="ntimes" value="<?php echo $ntimes;?>">
<input type="hidden" name="TAM" value="<?php echo $TAM;?>">
<table>
<tr>
<td valign="top">
<table>
<?php
if ($paginas!=1)
	printf ("<th colspan=\"2\" align=\"center\">%s %d/%d</th>",$strScene,$pagina,$paginas);
else
	printf ("<th colspan=\"2\" align=\"center\">$strInformation</th>");
?>
<tr>
<td align="right"><?=$strSatelite?>&nbsp;</td>
<td><?=$row_array["Satellite"]?></td>
</tr>
<tr>
<td align="right"><?=$strSensor?>&nbsp;</td>
<td><?=$row_array["Sensor"]?></td>
</tr>
<tr>
<td align="right"><?=$strBase?>&nbsp;</td>
<td><?=$row_array["Path"]?></td>
</tr>
<tr>
<td align="right"><?=$strPonto?>&nbsp;</td>
<td><?=$row_array["Row"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strData?>&nbsp;</td>
<td><?=$row_array["Date"]?></td>
</tr>
<tr>
<td align="right" nowrap>SceneId &nbsp;</td>
<td><?=$row_array["SceneId"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strOrbita?>&nbsp;</td>
<td><?=$row_array["Orbit"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strTLLat?>&nbsp;</td>
<td><?=$row_array["TL_Latitude"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strTLLon?>&nbsp;</td>
<td><?=$row_array["TL_Longitude"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strBRLat?>&nbsp;</td>
<td><?=$row_array["BR_Latitude"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strBRLon?>&nbsp;</td>
<td><?=$row_array["BR_Longitude"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strCenter?>(GMT)&nbsp;</td>
<td><?=gmdate("H:i:s",$row_array["CenterTime"]*24*3600);?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strImg?>&nbsp;</td>
<td><?=$row_array["ImageOrientation"]?></td>
</tr>
<!-- Denise -->
<tr>
<td align="right" nowrap><?=$strSunIncidenceAngle?>&nbsp;</td>
<td><?=$suprow_array["OffNadirAngle"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strSunAzimuth?>&nbsp;</td>
<td><?=$suprow_array["SunAzimuth"]?></td>
</tr>
<tr>
<td align="right" nowrap><?=$strSunElevation?>&nbsp;</td>
<td><?=$suprow_array["SunElevation"]?></td>
</tr>
<!-- Fim Denise -->
<tr><tr> </tr></tr>
<tr><th colspan="2" align="center" ><?=$strCobertura?></th></tr>
<tr>
<td align="center" nowrap>Q1&nbsp; <?=$row_array["CloudCoverQ1"]?></td>
<td align="center" nowrap>Q2&nbsp <?=$row_array["CloudCoverQ2"]?></td>
</tr>
<tr>
<td align="center" nowrap>Q3&nbsp; <?=$row_array["CloudCoverQ3"]?></td>
<td align="center" nowrap>Q4&nbsp; <?=$row_array["CloudCoverQ4"]?></td>
</tr>
<tr>
<td align="right" colspan="2">
<div align="center"><center>

<!-- antes daqui
<table border="0" width="100%">
<tr>
<td align="right">
-->
<?php
//	if ($pagina > 1)
//		echo "<input type=\"submit\" name=\"ScrollAction\" value=\" < \">";
?>
<!--
</td>
<td align="center"><input type="submit" value="<?=$strCart?>" name="action"></td>
<td align="left">
-->
<?php
//	if ($pagina < $paginas)
//		echo "<input type=\"submit\" name=\"ScrollAction\" value=\" > \">";
?>
<!--
</td>
</tr>
</table>
-->
</center></div></td>
</tr>

</table>
</td>
<td align="center" colspan="2">
<!-- <iframe src='display.php?TABELA=Browse&PREFIXO=<?=$tab_sat_prefix?>&INDICE=<?=$row_array["SceneId"]?>' name='image' width='484'
height='484' scrolling='no' marginwidth='0' frameborder='0'></iframe> -->
<img style="width:512;height:512;" src='display.php?TABELA=Browse&PREFIXO=<?=$tab_sat_prefix?>&INDICE=<?=$row_array["SceneId"]?>'>  
</td>
</tr>
</table>
<?php
// Inclus� dos Produtos
include 'manageProd.php';
?>
</form>
</body>
</html>