<?php
//include("dbglobals.inc.php");
import_request_variables("gpc");
include ("globals.php");
$dbcat = $GLOBALS["dbcatalog"];

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";

if (!isset($TABELA))
{
	Header("Location:noimage.php");
	exit;
}
//$sql = "SELECT $TABELA from $TABELA WHERE  SceneId = '" . $INDICE . "'"; % before data-base tables changes
$sql = "SELECT $TABELA FROM $PREFIXO" . $TABELA . " WHERE SceneId = '" . $INDICE . "'";
// echo  "<br>$sql<br>";
$dbcat->query($sql);

if($INDICE == 'black' or $INDICE == "big.black")
{
  $path = "../Suporte/images/$INDICE.jpg";
	$image = imagecreatefromjpeg($path);
	Header("Content-Type: image/jpeg");
	imagejpeg($image);
  exit;
} 
if ($dbcat->numRows() == 0)
{
	Header("Location:noimage.php");
	exit;
};

// Send the header

	Header("Content-Type: image/jpeg");

// now send the image

	$row_array = $dbcat->fetchRow();
	echo $row_array[0]; 
  flush(); 
?> 