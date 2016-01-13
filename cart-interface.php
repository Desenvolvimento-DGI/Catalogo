<?php
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include_once("user.class.php");   

import_request_variables("gpc");

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";


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


#
# Insert User in Database
#

$userId = $MAIL;

$today = date( "Y-m-d H:i:s" );

$sql = "SELECT * FROM User WHERE userId='$userId'";
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
		$dbusercat->query($sql) or $dbusercat->error ($sql);
		$countu = $dbusercat->numRows();
		$dbusercat->freeResult();
		$sql = "SELECT * FROM User WHERE email='$MAIL'";
		$dbusercat->query($sql) or $dbusercat->error ($sql);
		$myrow = $dbusercat->fetchRow();
		$counte = $dbusercat->numRows();
		$dbusercat->freeResult();
		if ($countu == 0 && $counte == 0)
		{
//
      $sql = "INSERT INTO Address ( `addressId` , `userId` , `addressType` , `CNPJ_CPF` , `compCNPJ` , `digitCNPJ` , `cep` , `street` , `number` , `complement` , `district` , `city` , `state` , `country` , `delivery` , `payment` ) 
              VALUES ('', '$userId', 'S', NULL , NULL , NULL , '', NULL , NULL , NULL , NULL , NULL , NULL , NULL , NULL , NULL )";
      $dbusercat->query($sql) or $dbusercat->error ($sql);
      $sql = "SELECT addressId FROM Address WHERE userId = '$userId'";
      $dbusercat->query($sql) or $dbusercat->error ($sql);
      $row = $dbusercat->fetchRow();
      $addressId = $row["addressId"];
      
			$sql = "INSERT INTO User SET
			userId='$userId',
			addressId='$addressId',
			password=PASSWORD('$password'),
			fullname='$userId',
			CNPJ_CPF='$CNPJ_CPF',
			areaCode='$areaCode',
			phone='$phone',
			email='$MAIL',
			company='eoPortal',
			companyType='$companyType',
			activity='$activity',
			userType='$userType',
			registerDate='$today'
			";
			
	  	if ($GLOBALS["stationDebug"])
	  	echo "$sql <br>";
			$dbusercat->query($sql) or $dbusercat->error ($sql);
		}
		else $userId = $myrow['userId'];

$scenes = explode(",",$SCENES_SET);
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
		$sql = "DELETE FROM Cart WHERE sesskey='$PHPSESSID' AND SceneId='$scenes[$i]'";
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
/*
if ($retval != 0 && $GLOBALS["stationDebug"])
	echo "cart_interface Error $retval : ".nl2br ( $output[0]) ."<br>\n";
	
$mailMess = sprintf($strMailMessage_1,$userId,$reqId);
$status = mail($MAIL,"$strMailMessage_2 - $reqId","$mailMess \n" . str_repeat("-", 83) . "\n $mailMsgAtus","From:Cbers Production <$GLOBALS[contactmail]>");
*/

$media = "FTP";
$price = 0;
for($i=0; $i < $count; $i++)
{       
 //Searching the scenes
 $sql = "SELECT * FROM Scene WHERE SceneId='". $scenes[$i] ."'";
 $dbcat->query($sql) or $dbcat->error ($sql);
 $row = $dbcat->fetchRow();
 $dbcat->freeResult($results); 
 // Setting email message
 $messageItem .= sprintf("\n%-8d%s\t%s", $i+1, $strMessageSatellite, $row["Satellite"]);
 $messageItem .= sprintf("\n%-9s%s\t%s\t\t\t\t%s\t\t€$%7.2f", " " ,$strInstrument,$row["Sensor"],$media,$price);
 $messageItem .= sprintf("\n%-9s%s/%s\t%s/%s", " " ,$strMessagePath,$strMessageRow,$row["Path"],$row["Row"]);
 $messageItem .= sprintf("\n%-9s%s\t\t%s\n", " ",$strDate,$row["Date"]);
 $reqTot += $price;  
}

if($reqTot) $messageItem .= sprintf("%s\n\t\t\t\t\t\t\t\t%s\t€$%7.2f\n\n", str_repeat("-", 90), $strTotal, $reqTot);
else $messageItem .= sprintf("%s\n\n", str_repeat("-", 90));
               
// Generating E-mail
$message = sprintf($mailMsgReq1,$userId,$reqId);
if($reqTot) $message .= " " . $mailMsgReq2;
$message .= $mailMsgReq3 . str_repeat("-", 90);
$message .= $messageItem;
$message .= $mailMsgAtus;
if(!mail ( $MAIL, $mailSubReq . $reqId , $message, $mailSender)) echo "Problema ao enviar o e-mail";

	
?>

