<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
include_once("product.class.php");

// Get the Global flag $DONTSHOW
if(isset($DONTSHOW)) $dontshow = $DONTSHOW;

// Verificar os produtos da cena
$sql = "SELECT * FROM Product WHERE SceneId='" . $scene  . "'";
if (!$dbcat->query($sql))
	die ($dbcat->errorMessage()); 
$nProds = $dbcat->numRows();

switch ($row_array['Sensor']) {
     case 'CCD': 
	   case 'WFI':
	   case 'IRM':
	      $tem = 'ccbers';
	      break;
	   case 'HRC':
	      $tem = 'cbers_hrc';
	      break;
	   case 'TM':
	   case 'ETM':
        $tem = 'GrdTm';
        break; 
     case 'MSS':
        $tem = 'Mss';
        break;
     case 'MODIS':
        $tem = 'ams';
        break;  
     case 'LIS3':
     case 'AWIF':
	$tem = 'P6';
	break;     
     default :
       $tem = 'ams'; 
};

$latLoc = $row_array["CenterLatitude"];
$longLoc = $row_array["CenterLongitude"];

$lang = $_SESSION['userLang'];

?>
<form method="POST" action="manage.php">
</table>
<table border="0">
<tr>
<td align="right"> 
<?php

	if ($pagina > 1)
		echo "<input type=\"submit\" name=\"ScrollAction\" value=\" < \">"; 
?>
</td>
<?
// if($row_array['Deleted'] == 1) $dontshow = 1;  // To avoid requesting scenes that have been rejected by Quality Control - but integrate mosaic
 if ($dontshow == 0) print "<td align=center><input type=submit value=\"$strCart\" name=action></td>";
?>
<!-- <td align="center"><input type="submit" ONCLICK="openwin_pro('prodForm.php?scene=<?=$scene?>&sat=<?=$sat?>');" value="<?=$strPerProd?>" name="persProd"></td> -->
<td align="center"><input type="submit" ONCLICK="openwin_pro('prodForm.php?ID=<?=$ID?>&sceneid=<?=$scene?>&sat=<?=$sat?>&DONTSHOW=<?=$dontshow?>');" value="<?=$strImgparms?>" name="persProd"></td>
<?php
if($row_array['Satellite'] == "T1")
{ 
?>
<!-- <td align=center><input type="submit" value="<?=$strMosaic?>" ONCLICK="openwin_pro('mosaico.Modis.php?SceneId=<?=$scene?>&Date=<?=$row_array["Date"]?>&Path=<?=$row_array["Path"]?>&Row=<?=$row_array["Row"]?>&Sensor=<?=$row_array["Sensor"]?>&DONTSHOW=<?=$dontshow?>')"></td> -->
<?php
}
?>

<td align="center"><input type="button" value="<?=$strLocation?>" onClick="window.open('http://www.dpi.inpe.br/cbers/mosaico.php?LAT=<?=$latLoc?>&LON=<?=$longLoc?>&SATELITE=<?=$row_array["Satellite"]?>&SENSOR=<?=$row_array["Sensor"]?>&ORBITA=<?=$row_array["Path"]?>&PONTO=<?=$row_array["Row"]?>&RES=1&IMA=esad2000&TEM=<?=$tem?>&TAM=G&LANG=<?=$lang?>&LATI=<?=$latLoc?>&LONI=<?=$longLoc?>'
,'windowProd','resizable=yes,scrollbars=yes,width=990,height=780')"</td>            

<td align="center"><input type="button" value="<?=$strClose?>"
       <? 				  	   
	         if ($TAM == "G")  // Ordinary querying for a set of Scenes - calling was from mosaico.php ; displaying in the same window
				     echo " onClick=window.history.go(-$ntimes)></td>";
					 else // Expanding image corresponding to a SceneId - calling was from mantab.php ; a new window has been opened
					     echo " onClick=window.close()></td>";	
 
			 ?> 
   
<td align="center"> 
<?php
	if ($pagina < $paginas)
		echo "<input type=\"submit\" name=\"ScrollAction\" value=\" > \">"; 
?>
</td>
</tr>
</table>
<?php
if($nProds)
{
?>
<table border="0">
<th colspan="10">Produtos Processados</th>
<tr>
<th><?=$strId?></th><th><?=$strShift?></th><th><?=$strCorLevel?></th><th><?=$strOrientation?></th>
<th><?=$strProj?></th><th><?=$strDatum?></th><th><?=$strRes?></th>
<th><?=$strBands?></th><th><?=$strFormat?></th><th><?=$strAction?></th>
</tr>
<?php
}
for($i=0; $i<$nProds; $i++)
{
      $prodRow = $dbcat->fetchRow($i);
      $prod = new Product ($dbcat);
      $prod->fill($prodRow["Id"]);
      $varProd = "prodId" . $i;
      $varSub = "action" . $i;
?>
<tr>
<td><?=$prod->Id?><td><?=$prod->sceneShift?></td><td><?=$prod->correctioLevel?></td>
<td><?=$prod->orientation?></td><td><?=$prod->projection?></td>
<td><?=$prod->datum?></td><td><?=$prod->resampling?></td><td><?=$prod->bands?></td>
<td><?=$prod->format?></td>
<td><input type="submit" value="<?=$strCart?>" name="<?=$varSub?>"></td>
<input type=hidden name="<?=$varProd?>" value=<?=$prod->Id?>>
<input type=hidden name=tamMat value=<?=$i+1?>>
</tr>
<?php
};
?>
<!-- <input type=hidden name=DONTSHOW value=<?=$dontshow?>> -->
</form>
