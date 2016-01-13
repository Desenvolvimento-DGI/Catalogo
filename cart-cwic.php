<?php 
//------------------------------------------------------------------------------
// Alterado Denise 
//
// >>>v1
//------------------------------------------------------------------------------
// Includes
include("session_mysql.php");
include ("globals.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include_once("cart.class.php");

import_request_variables("gpc");

// Set Language

$SESSION_LANGUAGE = $_SESSION['userLang'] = 'EN'; 
require ("cart_".$_SESSION['userLang'].".php");	

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

// echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>" ;

// echo "<br> Sessid = $PHPSESSID Sessid ===> $sessionId <br>";

if($action=="login" or $action=="register")
{
 include("findCountry.php");

 $_SESSION['userIP']=$_SERVER["REMOTE_ADDR"];
 $_SESSION['userTry']=0;

 $country=findCountry();
 if(!isset($_SESSION['userLang']))
  if($country == "Brazil") $_SESSION['userLang']='PT';
  else  $_SESSION['userLang']='EN';

// echo "<br> action = $action country = $country userLang = " . $_SESSION['userLang'] . "<br>";

?>
<html>
<head> 
<title>Cat&aacute;logo de Imagens</title>
</head>
<!-- <frameset name="geral" rows="55,*" frameborder="0"> -->
<frameset name="geral" rows="75,*" frameborder="0">
  <frame name="banner" scrolling="no" noresize target="mosaico" src="upframe.php">
  <frameset cols="26%,*">
    <frame name="panel" scrolling="yes" target="mosaico" src="panel.php">
	<frame name="mosaico" src="<?=$action?>.php">
</frameset>
</frameset> 
</html>
<?
 die();
}

?>
<html>
<head>
<title><?=$strCart?></title>
<?php include("css.php"); ?>
</head>
<body>
<?php
if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID Operator = ".$_SESSION['operatorId']." User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";

// Check if a request will be posted
$doRequest = false;
$userChecked = false;
$localUserId = "";
$objUser = new User($dbusercat);

if(!isset($action)) 
{
 $PHPSESSID = $sessionId = session_id();
 $INDICE = $_GET['SCENEID'];

#
# Insert SESSION ID, SCENEID into Table Cart
#
 $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$INDICE'";
 if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux); 

 $nscenes = $dbcat->numRows();

 if($nscenes == 0)  // We only insert scenes that are still not in database 
 { 
	 $objCart = new Cart($dbcat); 
	 $objCart->fill($PHPSESSID,$INDICE, $PRODUTO);
	 $objCart->insert();
 }
#
}

if ($action == $strDelete)
{
	$objCart = new Cart($dbcat);
	$objCart->removeId($ID);
} 

// Searching for cart
$sql = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();

if ($itens == 0)
{
  echo "<h2>Empty Cart</h2>\n";
	$strWarning = '<h2>To download images, you must either 
                register yourself or, if you are already registered, log in at <a href="index.php" style="font-size:15">Catalog\'s banner (top) main page</a>.</h2><br>';
	echo $strWarning;
	die();
}
	
$count  = 0;
while ($row_array = $dbcat->fetchRow())
{
  $scenes[$count] = $row_array["SceneId"];
  $prodId[$count] = $row_array["ProductId"];
  $mediaCart[$count] = $row_array["Media"];
  $price[$count] = $row_array["Price"]; 
  $id[$count++] = $row_array["Id"];
}
$dbcat->freeResult($result);

?>
<form name="productForm" method="POST" action="cartRequest.php">
<table width="100%" cellpadding="4">
<thead>
  <tr>
    <th><?=$strItem?></th>
    <th><?=$strThumb?></th>
    <th><?=$strInfo?></th>
<?
   if($userChecked)
   {
?>
    <th><?=$strMedia?></th>
    <th><?=$strPrice?></th>
<?
   }
?>
    <th><?=$strAction?></th>
  </tr>
</thead>
<tbody>

<?php
// Variables
$today = date( "Y-m-d H:i:s" );
$total = 0;
 
// Loop for scenes
for ($i=0; $i<$count;$i++)
{
   // Get data from Scene Table
	$sql = "SELECT * FROM Scene WHERE SceneId='".$scenes[$i]."'";
	$dbcat->query($sql) or $dbcat->error ($sql);
	$itens = $dbcat->numRows();

  if ($itens == 0) die("Erro nas Cenas");
	
	  $row = $dbcat->fetchRow();
	  $dbcat->freeResult($results);
	
    switch ($row["Satellite"])
    {
     case "CB1":
     case "CB2":
     case "CB2B":
       $tab_sat_prefix[$i] = "Cbers";
       break;
     case "L1":
     case "L2":
     case "L3":
     case "L5":
     case "L7":
       $tab_sat_prefix[$i] = "Landsat";
       break;
     case "A1":
     case "T1":
       $tab_sat_prefix[$i] = "Modis";
       break;
     case "P6":
       $tab_sat_prefix[$i] = "P6";
       break;
    }; 
?>
	<tr>
	   <td align="center"><?=$i+1?></td>
	   <td align="center"><iframe src='display.php?TABELA=Thumbnail&PREFIXO=<?=$tab_sat_prefix[$i]?>&INDICE=<?=$row["SceneId"]?>' name='image' width='128'
         height='128' scrolling='no' marginwidth='0' frameborder='0'></iframe></td>
	   <td>
	     <table>
	     <?php
	     if(trim($prodId[$i])!="")
	        echo "<tr><td align=\"right\">$strProd</td><td></td><td>$prodId[$i]</td>";
	     ?>
	     <tr><td align="right"><?=$strSatellite?></td><td></td><td><?=$row["Satellite"]?></td>
	     <tr><td align="right"><?=$strInstrument?></td><td></td><td><?=$row["Sensor"]?></td>
	     <tr><td align="right"><?=$strPath?></td><td></td><td><?=$row["Path"]?></td>
	     <tr><td align="right"><?=$strRow?></td><td></td><td><?=$row["Row"]?></td>
	     <tr><td align="right"><?=$strDate?></td><td></td><td><?=$row["Date"]?></td>
	     </table>
	   </td>
<? 
      
      if($userChecked)  
      { 	     		
?>	

      <td align="center">FTP</td>
      <td align="center">R$&nbsp;<?=$price[$i]?></td>
<?
	    }
      printf("<td><table><tr><td><a href=\"cart-cwic.php?ID=%d&sceneid=%s&prodid=%s&action=%s\">%s</a></td></tr>\n",
		$id[$i],$scenes[$i],$prodId[$i],$strDelete,$strDelete);

?>
<tr><td><a href="prodForm.php?ID=<?=$id[$i]?>&sceneid=<?=$scenes[$i]?>&prodid=<?=$prodId[$i]?>&action=<?=$strDetail?>" target="_blank"><?=$strDetail?></a></td></tr></table></td> 
<?

}

echo "<input type=hidden name=userid value=$localUserId>\n"; 
echo "<input type=hidden name=sesskey value=$PHPSESSID>\n";

echo "<tr><td colspan=9 align=center><input type=button value=$strClose onClick=window.location.href='index.php'></td></tr>";       
echo "</form>
</tbody>
</table>";

$strWarning = "<h2>To download the images, you must either 
                <a href='cart-cwic.php?action=register' style=\"font-size:15\">register</a> yourself or, if you are already registered, <a href='cart-cwic.php?action=login' style=\"font-size:15\">login </a> at Catalog's banner (top) main page.</h2><br>";
echo $strWarning;

?>
</body>
</html>