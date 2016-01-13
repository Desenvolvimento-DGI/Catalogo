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

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>"; 

// Globals
$dbcat = $GLOBALS["dbcatalog"];
// $GLOBALS["stationDebug"] = true;
include("css.php");
$dbusercat =  $GLOBALS["dbusercat"];

// Serching User and his attributes

$userid = $_SESSION['userId'];
$objUser = new User($dbusercat);
$objUser->selectByUserId($userid);

// Find if User is able for purchasing or has privileges (usertype = 1)

$OK_to_purchase = 0;
$cnpj = $objUser->CNPJ_CPF;
//if (trim($cnpj) != "" or $_SESSION["userType"] == 1) $OK_to_purchase = 1;
if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;

// Get the Global flag $DONTSHOW
$dontshow = $DONTSHOW;

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
function changePrice(values,OKtopurchase)   
{ 
   midia = '';
   restore = document.productForm.restore.value;
   if(values == 1) // midia CD
	 {
     alert ("<?=$strRestriction2?>");
		 if (OKtopurchase > 0) midia = document.productForm.midia.value;
//     else alert ("<?=$strRestriction2?>");  // Priced Media  
   };
	 corrlev = 0; // document.productForm.pcorLevel.value;
   shift = 0; //document.productForm.shift.value;
   proj = 0; //document.productForm.proj.value;
   datum = 0; //document.productForm.datum.value;
   res = 0; //document.productForm.res.value;
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
// }; 
// if(mode == 2 && values == 1) alert ("<?=$strRestriction2?>");  // Priced Media 
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
<body onload="window.resizeTo(550,450)">  
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
<table width="70%" border="0" cellspacing="1">    
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

$sat = substr($sceneid,0,2);

// Seacrh the cart item

$objCart = new Cart($dbcat); 

$default_datum = $objCart->datum = "WGS84";

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
//  $datum = $objCart->datum;
  $datum = $default_datum;
  $proj = $objCart->projection;
  $midia = $objCart->media;
  $restore = $objCart->restoration;

}
else 
{  
  if(trim($datum) == "")
   if($sat == "T1" or $sat == "A1") $datum = "WGS84"; 
//   else $datum = "SAD69";
     else $datum = $default_datum;
  if(trim($proj) == "") 
   if($sat == "T1" or $sat == "A1") $proj = "POLYCONIC";
   else $proj = "UTM";
  if(trim($stdLat1) == "") $stdLat1 = 0; 
  if(trim($stdLat2) == "") $stdLat2 = 0;
  if(trim($shift) == "") $shift = 0;
  if(trim($longO) == "") $longO = 0;
  if(trim($latO) == "") $latO = 0;
};  

// Monta nome das matrizes
 

$corLevel =  "matCorLevel" . $sat; 
$resampling = "matRes" . $sat;
$format = "matFormat" . $sat;

// Select index for Standard Correction Level
switch ($sat)
	{
		case "CB":
                 $std_corr_index = 3;
                 $std_dat_index = 1;
                 $std_proj_index = 1;
                 $std_res_index = 3;
		break;
		
		case "L1": case "L2": case "L3": case "L5": case "L7":
		             $std_corr_index = 2;
                 $std_dat_index = 1;
                 $std_proj_index = 1;
                 $std_res_index = 1;
		break;
		
                case "A1": case "T1":
                 $std_corr_index = 1;
                 $std_dat_index = 1;
                 $std_proj_index = 3;
                 $std_res_index = 1;
    break;
    
    case "P6" :
	               $std_corr_index = 3;
                 $std_dat_index = 1;
                 if($row["Sensor"] == "AWIF") $std_proj_index = 4;
                 else $std_proj_index = 1;
                 $std_res_index = 1;
		break;

		default :
	               $std_corr_index = 3;
                 $std_dat_index = 1;
                 $std_proj_index = 1;
                 $std_res_index = 1;
		break;
	};


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

if ($action == $strCart)
{
  $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$sceneid'";
  if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux);

  $nscenes = $dbcat->numRows();
  
  if($nscenes == 0)  // We only insert scenes that are still not in database. 
  { 
   $objCart->insert();
  }
} 
else if ($action == $strAlter) 
	      {			 
				  $objCart->Id = $ID;
				  $objCart->modify(); 
				};

if ($midia) 
{  
  $objCart->media = $midia;
  searchMedia($dbcat, $objMedia, $objCart->media);
  $midiaprice = $objMedia[0]->getPrice($_SESSION['userLang']); 
} else
  {
    $midia = "FTP";
//		$midiaprice = 0; 
	};

?>
<th colspan="2"><?=$title?></th>  
<!--
<tr>
  <td align="right"><?//=$strShift?></td><td><input name="shift" tabindex="1" size="2" value="<?//=$shift?>"></td>
</tr>
-->
<tr> 
  <td align="right" nowrap><b><?=$strCorLevel?></b></td><td> : <?=${$corLevel}[$std_corr_index]?></td>
</tr>
<tr>
 <!--	<td align="right"><b><?=$strDatum?></b></td><td> : <?=$matDatum[$std_dat_index]?></td> -->
   <td align="right"><b><?=$strDatum?></b></td><td> : <?=$datum?></td>
</tr>
<tr>	
	<td align="right"><b><?=$strProj?></b></td><td> : <?=$matProj[$std_proj_index]?></td> 
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
   <td align="right"><?//=$strStdLat2?></td><td><input name="stdLat2" tabindex="9" size="15" value="<?//=$stdLat2?>" disabled></td>
</tr>
-->
<tr>
	<td align="right"><b><?=$strRes?></b></td><td> : <?=${$resampling}[$std_res_index]?></td>
</tr>
<? if ($row["Sensor"] == "CCD")   // make restoration possible to be chosen
   {
?>
<tr>	
	<td align="right"><b><?=$strRest?></b></td>  
     <td><select name="restore" tabindex="5">
<?
     $sel1 = $sel2 = "";  	 
     if ($restore == '1') $sel1 = "selected" ;  else $sel2 = "selected";
?>
   		<option value="1"<?=$sel1?>><?=$strYes?></option>
   		<option value="0"<?=$sel2?>><?=$strNo?></option>
	</select></td> 
</tr>
<?
   };
?>
<tr>
	<td align="right"><b><?=$strMedia?></b></td>
<!--	<td><select name="midia" tabindex="10"> -->
<!-- <td><select name="midia" tabindex="10" onChange="changePrice(this.selectedIndex,<?=$OK_to_purchase?>)"> -->
<?php
   // Media
/*
    for($j = 0; $j <count($matMedia); $j++)
   { 
     $sel = "";
     if ($matMedia[$j] == $midia) $sel = "selected";
     searchMedia($dbcat,$objMedia,$matMedia[$j]);
     $midiaprice = $objMedia[0]->getPrice($_SESSION['userLang']); 
     if ($midiaprice == 0)
     {
*/
?>
<!--     <option value="<?=$matMedia[$j]?>"<?=$sel?>><?=$matMedia[$j]?></option> -->
<?
/*
      } else if ($OK_to_purchase) 
             { 
*/
?>
<!--    <option value="<?=$matMedia[$j]?>"<?=$sel?>><?=$matMedia[$j]?></option> -->
<? 
/*            
             };
   }
*/
?>
<!--  </select></td> -->
  <td align="left"><b>: FTP</b></td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
   <td align="center" colspan="2">
   <? // if ($dontshow == 0) print "<input type=submit value='$straction' name=action>" ?>
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