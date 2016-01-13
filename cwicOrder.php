<?php
ob_start();
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include_once("user.class.php"); 
include_once("globals.inc.php");  

import_request_variables("gpc");

 echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";
// Session
// Set Language
/*
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("cart_".$_SESSION['userLang'].".php");	
require ("mailMessage_".$_SESSION['userLang'].".php");	
*/

require ("cart_EN.php");	
require ("mailMessage_EN.php");	

// Globals

$dbcat = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];

$filename = "productdownload.xml";

#
# Insert User in Database
#

$userId = $userid;

$today = date( "Y-m-d H:i:s" );

$sql = "SELECT * FROM User WHERE userId='$userId'";
if ($GLOBALS["stationDebug"])
//echo "$sql <br>";
$dbusercat->query($sql) or $dbusercat->error ($sql);
$countu = $dbusercat->numRows();
$myrow = $dbusercat->fetchRow();
$dbusercat->freeResult();
if ($countu == 0)
{
  $msg = "User $userId not found !";
  ob_end_clean();
 	header("Content-type: application/octet-stream");
  header("Content-Type: text/xml;charset=ISO-8859-1"); 
  echo "<?xml version=\"1.0\" ?> 
  <searchResponse xmlns=\"http://upe.ldcm.usgs.gov/schema/metadata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://upe.ldcm.usgs.gov/schema/metadata http://edcsns17.cr.usgs.gov/EE/metadata.xsd\">
  <returnStatus value=\"success\">$msg</returnStatus>
	</searchResponse>
	";   
}
else
{ 
 $userId = $myrow['userId'];

 $scenes = explode(",",$scenes_set);
 $count = count($scenes);

// Lets post the request if asked to and if user is registered 

 $sql = "INSERT INTO Request SET
	UserId = '$userId',
	reqdate = '$today', 
	Language = 'EN' ";
	$dbcat->query($sql) or $dbcat->error ($sql);
	$reqId = $dbcat->insertId();


 for ($i=0; $i<$count;$i++)
 {
//	 echo "\n i = $i scenId = $scenes[$i] \n"; 
// Lets post the request if asked to and if user is registered
	
// Create a new request item entry
		$sql = "INSERT INTO RequestItem SET 
		ReqId = '$reqId',
		SceneId = '$scenes[$i]',
		Bands = 'All', 
		Media = 'FTP',
		StatusDate = '$today', 
		status = 'B'";
		$dbcat->query($sql) or $dbcat->error ($sql);
  }

 // Execute wget for each product  
  $path = "/usr/bin/wget -a " . $GLOBALS["systemLog"] . "wget.log -O " . $GLOBALS["systemLog"] . "wgetExec_" .$reqId . "log ";
  $path .= $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId"; 
  $path = escapeshellcmd ($path);
  $path .= " &"; 
  #
  # ATENCAO PARA A POSSIVEL OCORRENCIA DE ERRO QUANDO AS OPCOES DE LOG ESTAO SETADAS ; EM ALGUMAS INSTALACOES, 
  # O COMANDO "WGET" EH EXECUTADO COM SUCESSO EM LINHA DE COMANDO, MAS NAO FUNCIONA QUANDO DISPARADO DO PHP.
  # NA OCORRENCIA DE UMA TAL ANOMALIA, UMA SOLUCAO CONSISTE EM SUPRIMIR ALGUMAS OPCOES UTILIZADAS NA SINTAXE  
  # DO COMANDO.
  #
  # EX.: $path = "/usr/bin/wget " . $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId &";
  #

  exec($path,$output,$retval);

  for($i=0; $i < $count; $i++)
 {       
 //Searching the scenes
  $sql = "SELECT * FROM Scene WHERE SceneId='". $scenes[$i] ."'"; 
  $dbcat->query($sql) or $dbcat->error ($sql);
  $myrow = $dbcat->fetchRow();
  $dbcat->freeResult($results); 
 // Setting email message
  $satellite = $myrow["Satellite"];
  $sensor = $myrow["Sensor"];
  $path = $myrow["Path"];
	$row = $myrow["Row"]; 
 }
 
 	$ftpdir = $GLOBALS["wwwserver"].$userId.$reqId;
 	ob_end_clean();
 	header("Content-type: application/octet-stream");
  header("Content-Type: text/xml;charset=ISO-8859-1"); 
  echo "<?xml version=\"1.0\" ?> 
  <searchResponse xmlns=\"http://upe.ldcm.usgs.gov/schema/metadata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://upe.ldcm.usgs.gov/schema/metadata http://edcsns17.cr.usgs.gov/EE/metadata.xsd\">
  <returnStatus value=\"success\">Completed Successfully</returnStatus>
  <metaData>
  <ordernumber>$reqId</ordernumber>
  <numberofitens>$count</numberofitens>
  <ftpdirectory>$ftpdir</ftpdirectory>
	</metaData>
	</searchResponse>
	";    	  
}
?>
