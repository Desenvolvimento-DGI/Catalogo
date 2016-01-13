<?php
#
# Following HEADER command solves probles with caracter displaying in Portuques (ç , ã , õ, â , é etc.)
#
header("Content-Type: text/html; charset=ISO-8859-1");

include("session_mysql.php");
session_start();
require("css.php");
include("globals.php");

if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}; 

require ("mantab_".$_SESSION['userLang'].".php");

$dbcat = $GLOBALS["dbcatalog"];

//$sql = "SELECT * FROM municipios "; // There are many records repeated in data base !!!
$sql = "SELECT DISTINCT LAT,LON,MUNICIPIO,ESTADO,PAIS FROM municipios ";

$sql .= " WHERE MUNICIPIO LIKE '%" ;

$sql .=  trim($MUNICIPIO) != "" ? trim($MUNICIPIO) . "%'":"'";

$sql .= " AND ESTADO LIKE '%" ;
$sql .=  (strcmp($ESTADO,"*"))?$ESTADO . "%'":"'";

$sql .= " AND PAIS LIKE '%" ;
$sql .=  (strcmp($PAIS,"*"))?$PAIS . "%'":"'";

$sql .= " ORDER BY PAIS,MUNICIPIO,ESTADO";

$params =  $_SERVER['QUERY_STRING'];

if($GLOBALS["stationDebug"])
{
 echo "<br>params = $params  <br>";
 echo "<br> sql = $sql <br>";
};

$result = $dbcat->query($sql);

if ($dbcat->numRows() == 0)
{
	echo "<h3>$strNocity</h3>";
	exit;
}
if (!isset($TAM))
	$TAM = "M";

$params = "";
if (isset($SATELITE))
	$params .= "&SATELITE=".$SATELITE;
if (isset($SENSOR))
	$params .= "&SENSOR=".$SENSOR;
if (isset($IDATE))
	$params .= "&IDATE=".$IDATE;
if (isset($FDATE))
	$params .= "&FDATE=".$FDATE;
if (isset($IDATEM))
	$params .= "&IDATEM=".$IDATEM;
if (isset($IDATEY))
	$params .= "&IDATEY=".$IDATEY;
if (isset($FDATEM))
	$params .= "&FDATEM=".$FDATEM;
if (isset($FDATEY))
	$params .= "&FDATEY=".$FDATEY;
if (isset($QUICK))
	$params .= "&QUICK=".$QUICK;
	// comandos introduzidos - copiados da versão dpi
	if (isset($TEM))
	$params .= "&TEM=".$TEM;
if (isset($Q1))
   $params .= "&Q1=" . $Q1;
if (isset($Q2))
   $params .= "&Q2=" . $Q2;
if (isset($Q3))
   $params .= "&Q3=" . $Q3;
if (isset($Q4))
   $params .= "&Q4=" . $Q4;
  // fim comandos introduzidos 
?>
<html>
<head>
<title>Procura Municipio</title> 
<base target="mosaico">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php

echo "<br>";
echo "<div><center>";
echo "<table cellspacing=\"0\" cellpadding=\"0\" >";
echo "<tr>";
echo "<td >";
echo "<input  type=\"button\" value=$strClose onClick=window.location.href='first_". $_SESSION['userLang']. ".php'>" ;
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</div></center>"; 

?>
<table BORDER="0" cellpadding="2">
<?php
while ($myrow = $dbcat->fetchRow()) {
    printf("
	<tr>
	<td><a href=\"mosaico.php?ZOOM=9&LAT=%f&LON=%f&MYLAT=%f&MYLON=%f&TEM=politico&IMA=esad&RES=3&TAM=%s%s\">%s(%s)-(%s)</a>
	</td>
	</tr>\n",
	$myrow["LAT"],$myrow["LON"],$myrow["LAT"],$myrow["LON"],$TAM,$params,$myrow["MUNICIPIO"],$myrow["ESTADO"],$myrow["PAIS"]);
}
echo "</table>\n";
?>
</body>
</html>

