<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
//include("dbglobals.inc.php");
include_once("user.class.php");
import_request_variables("gpc");
include("session_mysql.php");
include_once("globals.php");

// Session
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("register_".$_SESSION['userLang'].".php");
require ("cart_".$_SESSION['userLang'].".php");
require ("arrays_".$_SESSION['userLang'].".php");
?>
<html>
<head>
<title><?=$strDeliveryAddr?></title>
<?php
include("css.php");
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];
$tab=1;

// If submitted
if($submitted and isset($action))
{
   $nUser = searchByUserid($dbusercat, $matUser, $matAddr, $matComp, "", $userid, 1);
   $objAddr = new Address($dbusercat);
   $objAddr->setFields( 'D', $matUser[0]->CNPJ_CPF, $matUser[0]->$compCNPJ, 0, $cep, $street, $number,
             $complement, $district, $city, $state, $country );
   $objAddr->setUserId( $userid );
   $objAddr->insert();
   $auxact = $action; // action é palavra reservada em Java.
?>
<!-- Inicializa as funções JavaScript -->
<script type="text/javascript">
goOn(<?="'".$sesskey."'"?>, <?="'".$userid."'"?>, <?="'".$nItens."'"?>, <?="'".$auxact."'"?>);
function goOn(sesskey, userid, nItens, auxact)
{
   url = "cartAddress.php"; 
   params = "?userid="+ userid + "&nItens="+ nItens + "&sesskey=" + sesskey + "&action=" + auxact;
   opener.location.href = url+params;  
   close();
}
</script>
<?php
}
?> 
</head>
<body>
<div><center class = azul>
<form method="post" action="cartDeliveryAddr.php">
<table border="0">
   <th colspan="4"><?=$strDeliveryAddr?></th>
   <tr><td><?=$strStreet?></td><td><input name="street" tabindex=<?=$tab++?> size="48" value="<?=$street?>"></td>
       <td><?=$strNumber?></td><td><input name="number" tabindex=<?=$tab++?> size="6" value="<?=$number?>"></td>
   </tr>
   <tr>
      <td><?=$strDistrict?></td><td colspan="3"><input name="district" tabindex=<?=$tab++?> size="25" value="<?=$district?>"></td>
   </tr>
   <tr>
      <td><?=$strComp?></td><td><input name="complement" tabindex=<?=$tab++?> size="25" value="<?=$complement?>"></td>
      <td><?=$strZip?></td><td><input name="cep" tabindex=<?=$tab++?> size="9" value="<?=$cep?>"></td>
   </tr>
   <tr>
      <td><?=$strCity?></td><td colspan="3"><input name="city" size="48" tabindex="10" value="<?=$city?>"></td>
   </tr>
   <tr><td><?=$strState?></td>
   <td colspan="3">
   <select name="state" tabindex="11" >
      <option selected><?=$state?>
<?
	     	for($j = 1; $j <= $matState[0]; $j++)
	     	{
?>
           <option value="<?=$matState[$j]?>" <?=($matState[$i]=="SP")?"Selected":""?>><?=$matState[$j]?></option>
<?
         }
?>
      </td>
   </tr>
   <tr><td><?=$strCountry?></td><td colspan="3">
   <select name=country tabindex="12" >
   <option selected><?=$country?>
<?php
   $sql = "SELECT DISTINCT(COUNTRY_NAME) FROM ipToCountry ORDER BY COUNTRY_NAME";
   $dbusercat->query($sql) or $dbusercat->error ($sql);
	while ($row = $dbusercat->fetchRow())
	{
			$country = ucwords(strtolower($row["COUNTRY_NAME"]));
			echo "<option value=\"$country\">$country</option>\n";
	}
	$dbusercat->freeResult($result);
?>
   </select>
   </tr>

   <tr><td colspan="4"></td></tr>
   <tr>
      <td align="center" colspan="4">
      <input type="submit" value="<?=$strAdd?>" name="action">
      <input type="reset" name="reset" value="<?=$strReset?>">
      <input type="submit" value="<?=$strCancel?>" name="cancel" ONCLICK="window.close();">
      </td>
   </tr>
   <input type=hidden name=submitted value=1>
   <input type=hidden name=userid value=<?=$userid?>>
   <input type=hidden name=CNPJ_CPF value=<?=$CNPJ_CPF?>>
   <input type=hidden name=compCNPJ value=<?=$compCNPJ?>>
   <input type="hidden" name="sesskey" value="<?=$sesskey?>">
   <input type="hidden" name="nItens" value="<?=$nItens?>">
</table>
</form>
</div>
</body>
</html>
