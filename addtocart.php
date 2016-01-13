<?php
// Verifica sessao



// Cofigura o timezone
if(function_exists('date_default_timezone_set') )
{
   date_default_timezone_set('UTC');
}
else
{
   putenv("TZ=UTC");
}



include("session_mysql.php"); 
session_start();
header("Cache-Control: no-cache, must-revalidate");
include("globals.php");
//include_once("request.class.php");
//include_once("user.class.php");
include_once("price.class.php");
//include_once("media.class.php");


import_request_variables("gpc");
 
// Verifica o idioma para exibir a pagina corretamente
if (isset($SESSION_LANGUAGE))
	  $_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
require ("mantab_".$_SESSION['userLang'].".php");
require ("product_".$_SESSION['userLang'].".php");

//define("BASEDIR", "../Suporte/");




// Pega o handle para a base de dados

include("cart.class.php");

$dbcat = $GLOBALS["dbcatalog"];
$dbcat1 = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];

// Serching User and his attributes

$userid = $_SESSION['userId'];
$objUser = new User($dbusercat);
$objUser->selectByUserId($userid);

// Find if User is able for purchasing

$OK_to_purchase = 0;
$cnpj = $objUser->CNPJ_CPF;


if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;

// Define a tabela
$table="Scene";


if ( ! isset($_GET['INDICE'] ))
{
	$INDICE=$_GET['INDICE'];	
	$INDICE=$_REQUEST['INDICE'];	
}


$retorno="";

// Verificar se o Indice existe

$sqlSceneId = "SELECT * FROM Scene WHERE SceneId = '$INDICE'";
if (!$dbcat->query($sqlSceneId)) $dbcat->error($sqlSceneId); 

$nregistros = $dbcat->numRows();

if( $nregistros == 0)  // We only insert scenes that are still not in database 
{ 
	$retorno = "SCENEIDNAOEXISTE";
}
else
{

	// Se acao de adicionar ao carrinho, insere na tabela cart
	if ($ACTION == "CART")
	{
	  $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$INDICE'";
	  if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux); 
	
	  $nscenes = $dbcat->numRows();
	
	  if($nscenes == 0)  // We only insert scenes that are still not in database 
	  { 
		 $objCart = new Cart($dbcat); 
		 $objCart->fill($PHPSESSID,$INDICE, $PRODUTO);
		 $objCart->insert();
		 
		 $retorno = "OK";
	  }
	  else
	  {
		 $retorno = "JAADICIONADO";
	  }
		
	}
}

echo $retorno;
?>
