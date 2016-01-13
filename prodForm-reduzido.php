<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------

include("session_mysql.php");   
session_start();
include("globals.php");
include_once("price.class.php");
include_once("cart.class.php"); 

import_request_variables("gpc");
sess_gc(get_cfg_var("session.gc_maxlifetime"));

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("cart_".$_SESSION['userLang'].".php");	
require ("product_".$_SESSION['userLang'].".php");
require ("manage_".$_SESSION['userLang'].".php");

// Globals
$dbcat = $GLOBALS["dbcatalog"];
// $GLOBALS["stationDebug"] = true;
include("css.php");

// Search image

$sql = "SELECT * FROM Scene WHERE SceneId='".$sceneid."'"; 
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();  
if ($itens == 0)
die("Erro nas Cenas");
$row = $dbcat->fetchRow();
$dbcat->freeResult($results);

if ($action == $strCart or $action == $strAlter)  $title = $strActimgparms;  else $title = $strImgparms; 
if ($action == $strDetail) $straction = $strAlter;
 else if($action == $strAlter) $straction = $strAlter;
			 else $straction = $strCart;
			 
?>
<html>
<head>
<title><?=$titFormProd?></title>  
<script language="javascript">
function changePrice(mode,values)   
{
//   if(midia == 1) alert ("Este item é tarifado - somente para pessoas juridicas");
 if(mode == 1 && values >= 0)
 {
   restore = document.productForm.restore.value;
   midia = document.productForm.midia.value;
   corrlev = document.productForm.pcorLevel.value;
   shift = 0; //document.productForm.shift.value;
   proj = document.productForm.proj.value;
   datum = document.productForm.datum.value;
   res = document.productForm.res.value;
   stdLat1 = 0; //document.productForm.stdLat1.value;
   stdLat2 = 0; //document.productForm.stdLat2.value;
   longO = 0; //document.productForm.longO.value;
   latO = 0; //document.productForm.latO.value;
   ID = document.productForm.ID.value;
   corrflag = 1;
   window.location.href="<?=$_SERVER['PHP_SELF']?>"+"?sceneid="+"<?=$sceneid?>"+"&restore="+restore+"&midia="+midia
                         +"&sat="+"<?=$row['Satellite']?>"+"&sens="+"<?=$row['Sensor']?>"+
												 "&dat="+"<?=$row['Date']?>"+"&pcorLevel="+corrlev+"&proj="+proj+"&shift="+shift+
												 "&datum="+datum+"&res="+res+"&stdLat1="+stdLat1+"&stdLat2="+stdLat2+"&longO="+longO+
												 "&latO="+latO+"&action="+"<?=$action?>"+"&ID="+ID+"&corrflag="+corrflag;	                            
 }; 
 if(mode == 2 && values == 1) alert ("<?=$strRestriction2?>");  // Priced Media 
}
function enableFields(proj)
{ 
/*
	projection = proj;
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
*/
}; 
</script> 
</head> 
<body onload="window.resizeTo(350,450)">  
<div><center class = azul>
<form name="productForm" method="POST" action="prodForm.php">  
<?php
 
$corrflag = $_GET['corrflag'];  // if ($corrflag) ---> Correction Level has been changed - changePrice has been called !
$puserType = $_SESSION['userType'];
if ($corrflag)
{
  $objPrice = new Price($dbcat); 
//  echo " sat = ".$sat." sens =".$sens." dat =".$dat." corr =".$pcorLevel;
  $price = $objPrice->searchPrice($sat, $sens, $dat,$productId, $_SESSION['userLang'],$pcorLevel,$puserType); 
  $action = "corrprice";    
}; 
?>
<table width="50%" border="0" cellspacing="1">    
<th colspan="3"><?=$titFormProd?></th>
<?
   // If product, diplay product Id
  if(trim($objCart->productId) != "") 
  {
?> 
   <tr><td align="right"><?=$strProduct?></td><td></td><td><?=$objCart->productId?></td></tr>
<?
  };
?>
<tr><td align="right"><?=$strSatellite?></td><td><?=$row["Satellite"]?></td>
<tr><td align="right"><?=$strInstrument?></td><td><?=$row["Sensor"]?></td>
<tr><td align="right"><?=$strPath?></td><td><?=$row["Path"]?></td>
<tr><td align="right"><?=$strRow?></td><td><?=$row["Row"]?></td>
<tr><td align="right"><?=$strDate?></td><td><?=$row["Date"]?></td>
<tr><td colspan="3"></td></tr>
<?php

if ($GLOBALS["stationDebug"])
{
	echo "Session = " . $PHPSESSID . " User = " . $_SESSION['userId'] . " Language = " . $_SESSION['userLang']." <br>\n";
  echo " Id cart = " . $ID;
}

// Seacrh the cart item

$objCart = new Cart($dbcat);  

if(trim($ID) != "" and $action == $strDetail) 
{
  $objCart->search($ID); 
  $shift = $objCart->sceneShift; if(!isset($shift)) $shift = 0;
  $longO = $objCart->longOrigin; if (!isset($longO)) $longO = 0;
  $latO = $objCart->latOrigin; if (!isset($latO)) $latOrigin = 0;
  $stdLat1 = $objCart->stdLat1; if (!isset($stdLat1)) $stdLat1 = 0;
  $stdLat2 = $objCart->stdLat2; if (!isset($stdLat2)) $stdLat2 = 0; 
  $pcorLevel = $objCart->corretion; 
  $res = $objCart->resampling;
  $datum = $objCart->datum;
  $proj = $objCart->projection;
  $midia = $objCart->media;
  $restore = $objCart->restoration;

}
else 
{ 
  if(trim($datum) == "") $datum = "SAD69";
  if(trim($proj) == "") $proj = "UTM";
  if(trim($stdLat1) == "") $stdLat1 = 0; 
	if(trim($stdLat2) == "") $stdLat2 = 0;
	if(trim($shift) == "") $shift = 0;
	if(trim($longO) == "") $longO = 0;
	if(trim($latO) == "") $latO = 0;
 };  

// Monta nome das matrizes
 
$sat = substr($sceneid,0,2);

$corLevel =  "matCorLevel" . $sat; 
$resampling = "matRes" . $sat;
$format = "matFormat" . $sat;

// Monta as matrizes

  if ($proj == "UTM") 
	{
	  $stdLat1 = 0;
	  $stdLat2 = 0;  
	}; 

if ($action == $strAlter or $action == $strCart)
{
 $objCart->fill($PHPSESSID,$sceneid, '', '', $shift,
               $pcorLevel, $orientation, $res, $datum, $proj, $longO, $latO,
               $stdLat1, $stdLat2, $form, $inter, $midia, $restore);

  $objCart->media = $midia;
  searchMedia($dbcat, $objMedia, $objCart->media);
  $objCart->price += $objMedia[0]->getPrice($_SESSION['userLang']); 
};

if ($action == $strCart) $objCart->insert(); 
else if ($action == $strAlter) 
	      {			 
				  $objCart->Id = $ID;
				  $objCart->modify();
				}; 

?>
<th colspan="2"><?=$title?></th>
<!--
<tr>
  <td align="right"><?//=$strShift?></td><td><input name="shift" tabindex="1" size="2" value="<?//=$shift?>"></td>
</tr>
-->
<tr> 
  <td align="right" nowrap><b><?=$strCorLevel?></b></td>
<td><select name="pcorLevel" tabindex="2" onChange="changePrice(1,this.selectedIndex)">   
<?php
   // Nível de Correção para cada satélite
   for($j = 1; $j <= ${$corLevel}[0]; $j++)
   {
     $sel = "";
//     if (${$corLevel}[$j] == $pcorLevel) $sel = "selected";
      if (${$corLevel}[$j] == "2") $sel = "selected";
?>
      <option value="<?=${$corLevel}[$j]?>"<?=$sel?>><?=${$corLevel}[$j]?></option>
<?
   } 
?>
	</select></td>
</tr>
<tr>
	<td align="right"><b><?=$strDatum?></b></td>
	<td><select name="datum" tabindex="4" onChange="changePrice(1,this.selectedIndex)">
<?
     $sel1 = $sel2 = "";  	
//     if ($datum == $matDatum[1]) $sel1 = "selected" ;  else $sel2 = "selected";
     $sel1 = "selected"; 
?>
   		<option value="SAD69"<?=$sel1?>><?=$matDatum[1]?></option>
   		<option value="WGS84"<?=$sel2?>><?=$matDatum[2]?></option>
	</select></td>
</tr>
<tr>	
	<td align="right"><b><?=$strProj?></b></td> 
<!--	<td><select name="proj" tabindex="5" ONCHANGE="enableFields(this.value)"> -->
    <td><select name="proj" tabindex="5" ONCHANGE="changePrice(1,this.selectedIndex)">

<?
     $sel1 = $sel2 = "";  	
//     if ($proj == $matProj[1]) $sel1 = "selected"; else $sel2 = "selected"; 
     if($row["Sensor"] == "WFI") $sel2 = "selected"; else $sel1 = "selected";  
?>	
   		<option value="UTM"<?=$sel1?>><?=$matProj[1]?></option>
   		<option value="Lambert"<?=$sel2?>><?=$matProj[2]?></option>
	</select></td> 
</tr> 
<!--  
<tr>
   <td align="right"><?//=$strLongO?></td><td><input name="longO" tabindex="6" size="15" value="<?//=$longO?>"></td>
</tr>
<tr>
   <td align="right"><?//=$strLatO?></td><td><input name="latO" tabindex="7" size="15" value="<?//=$latO?>"></td>
</tr>
<tr>
   <td align="right"><?//=$strStdLat1?></td><td><input name="stdLat1" tabindex="8" size="15" value="<?//=$stdLat1?>" disabled></td>
</tr>
<tr>
   <td align="right"><?=//$strStdLat2?></td><td><input name="stdLat2" tabindex="9" size="15" value="<?//=$stdLat2?>" disabled></td>
</tr>
-->
<tr>
	<td align="right"><b><?=$strRes?></b></td>
	<td><select name="res" tabindex="10" ONCHANGE="changePrice(1,this.selectedIndex)">
<?php
   // Algoritmo de reamostragem para cada satélite 
   for($j = 1; $j <= ${$resampling}[0]; $j++)
   {  
     $sel = "";
//     if (${$resampling}[$j] == $res) $sel = "selected"; 
     if (${$resampling}[$j] == "CC") $sel = "selected"; 
?>
      <option value="<?=${$resampling}[$j]?>"<?=$sel?>><?=${$resampling}[$j]?></option>
<?
   }
?>
  </select></td> 
</tr>
<tr>	
	<td align="right"><b><?=$strRest?></b></td>  
    <td><select name="restore" tabindex="5">
<?
     $sel1 = $sel2 = "";  	
     if ($restore == $strYes) $sel1 = "selected" ;  else $sel2 = "selected";
?>
   		<option value="Sim"<?=$sel1?>><?=$strYes?></option>
   		<option value="Nao"<?=$sel2?>><?=$strNo?></option>
	</select></td> 
</tr>
<tr>
	<td align="right"><b><?=$strMedia?></b></td>
<!--	<td><select name="midia" tabindex="10"> -->
<td><select name="midia" tabindex="10" onChange="changePrice(2,this.selectedIndex)">
<?php
   // Media
   for($j = 0; $j <count($matMedia); $j++)
   {
     $sel = "";
     if ($matMedia[$j] == $midia) $sel = "selected"; 
?>
     <option value="<?=$matMedia[$j]?>"<?=$sel?>><?=$matMedia[$j]?></option> 
<?
   }
?>
  </select></td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
   <td align="center" colspan="2">
   <input type="submit" value="<?=$straction?>" name="action"> 
   <input type="submit" value="<?=$strClose?>" name="action" ONCLICK="window.close();">
	</td>
</tr> 
<input type=hidden name=submitted value=<?=$submitted?>> 
<input type=hidden name=sceneid value=<?=$sceneid?>> 
<input type=hidden name=ID value=<?=$ID?>>
</table>
</form>
</div>
<? 
//if($action == "corrprice" and $price > 0) 
//{
?>
<script>
// alert("<?=$strRestriction2?>")
</script>
<?
// };// Priced CorrLevel 
if($proj == "Lambert") { ?><script> enableFields("Lambert"); </script> <? };  
?>
</body>
</html> 