<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
include("session_mysql.php");
session_start();
include("globals.php");
include_once("cart.class.php");
import_request_variables("gpc");

// Session
sess_gc(get_cfg_var("session.gc_maxlifetime"));

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("manage_".$_SESSION['userLang'].".php");
require ("product_".$_SESSION['userLang'].".php");
?>
<html>
<head>
<title><?=$titFormProd?></title>
<script language="javascript">
<!--
function enableFields()
{
   projection = document.productForm.proj.value;
   if( projection == "Lambert")
   {
      //document.productForm.stdLat1.style.visibility = 'visible';
      document.productForm.stdLat1.disabled = false;
      document.productForm.stdLat2.disabled = false;
   }
   else
    if( projection == "UTM")
    {
      //document.productForm.stdLat1.style.visibility = 'hidden';
      document.productForm.stdLat1.disabled = true;
      document.productForm.stdLat2.disabled = true;
    }
}
-->
</script>
<?php
include("css.php");
$dbcat = $GLOBALS["dbcatalog"];

// Inicializando campos
if(!isset($submitted))
{
   $shift = 0;
   $longO = $latO = 0;
   $stdLat1 = $stdLat2 = 0;
}

// Monta nome das matrizes
$corLevel =  "matCorLevel" . $sat;
$resampling = "matRes" . $sat;
$format = "matFormat" . $sat;

// Monta as matrizes
echo " sat = " . $sat . " scene = " . $scene; 

if($submitted and isset($action))
{
	$submitted = 0;
	$objCart = new Cart($dbcat);
	$objCart->fill($PHPSESSID,$scene, '', '', $shift,
       $pcorLevel, $orientation, $res, $datum, $proj, $longO, $latO,
       $stdLat1, $stdLat2, $form, $inter, $media);
   $objCart->insert();

?>
<!-- Inicializa as funções JavaScript -->
<script type="text/javascript">
window.close();
</script>
<?php
}
?>
</head>
<body>
<div><center class = azul>
<form name="productForm" method="POST" action="prodForm.php">
<table width="90%" border="0" cellspacing="1">
<th colspan="2"><?=$titFormProd?></th>
<tr>
   <td align="right"><?=$strShift?></td><td><input name="shift" tabindex="1" size="2" value="<?=$shift?>"></td>
</tr>
<tr>
	<td align="right" nowrap><b><?=$strCorLevel?></b></td>
	<td><select name="pcorLevel" tabindex="2">
<?php
   // Nível de Correção para cada satélite
   for($j = 1; $j <= ${$corLevel}[0]; $j++)
   {
?>
   		<option value="<?=${$corLevel}[$j]?>"><?=${$corLevel}[$j]?></option>
<?
   }
?>
	</select></td>
</tr>
<tr>
	<td align="right"><b><?=$strDatum?></b></td>
	<td><select name="datum" tabindex="4">
   		<option value="SAD69"><?=$matDatum[1]?></option>
   		<option value="WGS84"><?=$matDatum[2]?></option>
	</select></td>
</tr>
<tr>
	<td align="right"><b><?=$strProj?></b></td>
	<td><select name="proj" tabindex="5" ONCHANGE='enableFields();'>
   		<option value="UTM"><?=$matProj[1]?></option>
   		<option value="Lambert"><?=$matProj[2]?></option>
	</select></td>
</tr>
<tr>
   <td align="right"><?=$strLongO?></td><td><input name="longO" tabindex="6" size="15" value="<?=$longO?>"></td>
</tr>
<tr>
   <td align="right"><?=$strLatO?></td><td><input name="latO" tabindex="7" size="15" value="<?=$latO?>"></td>
</tr>
<tr>
   <td align="right"><?=$strStdLat1?></td><td><input name="stdLat1" tabindex="8" size="15" value="<?=$stdLat1?>" disabled></td>
</tr>
<tr>
   <td align="right"><?=$strStdLat2?></td><td><input name="stdLat2" tabindex="9" size="15" value="<?=$stdLat2?>" disabled></td>
</tr>
<tr>
	<td align="right"><b><?=$strRes?></b></td>
	<td><select name="res" tabindex="10">
<?php
   // Algoritmo de reamostragem para cada satélite
   for($j = 1; $j <= ${$resampling}[0]; $j++)
   {
?>
      <option value="<?=${$resampling}[$j]?>"><?=${$resampling}[$j]?></option>
<?
   }
?>
	</select></td>
</tr>

<tr>
	<td align="right"><b><?=$strMedia?></b></td>
	<td><select name="media" tabindex="10">
<?php
   // Media
   for($j = 0; $j <count($matMedia); $j++)
   {
?>
      <option value="<?=$matMedia[$j]?>"><?=$matMedia[$j]?></option>
<?
   }
?>
	</select></td>
</tr>

<tr><td colspan="2"></td></tr>
<tr>
   <td align="center" colspan="2">
   <input type="submit" value="<?=$strCart?>" name="action">
	<input type="reset" name="reset" value="<?=$strReset?>">
   <input type="submit" value="<?=$strCancel?>" name="cancel" ONCLICK="window.close();">
	</td>
</tr>
<input type=hidden name=submitted value=1>
<input type=hidden name=scene value=<?=$scene?>>
</table>
</form>
</div>
</body>
</html>
