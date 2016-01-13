<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : december/2003
//------------------------------------------------------------------------------
//include("dbglobals.inc.php");
include("session_mysql.php");

// Session
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include_once("request.class.php");
import_request_variables("gpc");

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("history_".$_SESSION['userLang'].".php");
require ("cart_".$_SESSION['userLang'].".php");	
//require ("../include/arrays_".$_SESSION['userLang'].".php");
require ("arrays_".$_SESSION['userLang'].".php");		

// Globals
$dbcat = $GLOBALS["dbcatalog"]; 

// Mounting string
$strReqTit = "  " . $strReqItem  . " " . $reqId;
?>
<html>
<head>
<title><?=$strReqTit?></title>






    <meta charset="utf-8">
    
    
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página principal do site da DGI (Divisão de Geração de Imagem)">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
    <!-- Estilos -->
    <link href="/catalogo/css/bootstrap.css" rel="stylesheet">
    <link href="/catalogo/css/style.css" rel="stylesheet">
    <link href="/catalogo/css/camera.css" rel="stylesheet">
    <link href="/catalogo/css/icons.css" rel="stylesheet">
    

    <!-- 
    O arquivo abaixo define o configuração de cores a serem utilizadas
    As opções disponíveis são:
    
    skin-blue.css				skin-green.css			skin-red.css
    skin-bluedark.css			skin-green2.css			skin-red2.css
    skin-bluedark2.css			skin-grey.css			skin-redbrown.css
    skin-bluelight.css			skin-khaki.css			skin-teal.css
    skin-bluelight2.css   		skin-lilac.css			skin-teal2.css
    skin-brown.css				skin-orange.css			skin-yellow.css
    skin-brown2.css				skin-pink.css			
    -->
    <link href="/catalogo/css/skin-blue.css" rel="stylesheet">
    <link href="/catalogo/css/bootstrap-responsive.css" rel="stylesheet">







<script language="javascript"> 
<!--
function Delnumber(i,j,k,l,m)
{
 params = "reqId="+i+"&";
 params = params+"NumItem="+j+"&";
 params = params+"action="+k+"&";
 params = params+"index="+l+"&";
 params = params+"ItemStat="+m;
// alert(params);
 location.href="historyDetails.php?"+params;
}
-->
</script>
<!--?php include("css.php");?-->






</head>
<body>




<!-- 
Layout macro do site
As opções são:
boxed	Toda estrutura do site encaixada em uma espécie de caixa, possuindo largura
wide	Toda estrutura do site utilizando-se de toda a área disponivel, não possuindo margens
-->
<body class="wide" style="background-color:transparent; border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0 0px; height:100%; padding-bottom:0px;">




<!-- 
Inicio da área principal 
class body
-->

<div class="body" style="background-color:#FFFFFF; border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <div class="row-fluid" style="background-color:#FFFFFF;">
    		<br>




<center>
<form name="historyDetailsForm" method="POST" action="historyDetails.php">
<table width="50%" border="0" cellspacing="1" class="table table-hover">
<thead>
   <tr><th colspan="5" align="left"><?=$strReqTit?></th></tr>
   <tr>
    <th><?=$strItem?></th>
    <th><?=$strInfo?></th>
    <th><?=$strMedia?></th>
    <th><?=$strPrice?></th>
    <th><?=$strReqStatus?></th>
  </tr>
</thead>
<tbody>
<?php

if ($GLOBALS["stationDebug"])
{
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
  echo "Id cart = " . $ID;
}
 
// Seacrh the request and requestItens
searchReqByNumber($dbcat, $objReq, '', "", "", $reqId);
//echo " reqId = " . $reqId . " nitems = " . $objReq[0]->nItens;

if ($action == $strDelete and $ItemStat != -1)
{
    $objReq[0]->itens[$index]->remove($NumItem,$reqId);
    searchReqByNumber($dbcat, $objReq, '', "", "", $reqId);
    if ($objReq[0]->nItens == 0) $objReq[0]->removeParc($reqId);
};  

$total = 0; 
// Search image
for($i=0; $i < $objReq[0]->nItens; $i++) 
{
   // Getting Status
   for($indStatus=1; $indStatus <= $matReqItemStatusInt[0]; $indStatus++)
      if($objReq[0]->itens[$i]->status == $matReqItemStatusInt[$indStatus]) break;
 
   $ItemStat = $objReq[0]->itens[$i]->status;
   switch ($ItemStat) {
     case C: 
	   case D:
	   case E:
	   case F:
        $ItemStat = -1; // cannot be removed 
        break; 
   };

   //Searching the scene
   $sql = "SELECT * FROM Scene WHERE SceneId='". $objReq[0]->itens[$i]->sceneId."'";
   $dbcat->query($sql) or $dbcat->error ($sql);
   $itens = $dbcat->numRows();

   if ($itens == 0)
      die("Erro nas Cenas");
   $row = $dbcat->fetchRow();
   $dbcat->freeResult($results);
   
#
# Calculate item price (scene + media)
#
   $media = $objReq[0]->itens[$i]->media;
   searchMedia($dbcat, $objMedia, $media);
//   $price = $objMedia[0]->getPrice($_SESSION['userLang']); // This is the media price
   $price += $objReq[0]->itens[$i]->price; // We now add scene price ...
	 $total += $price; // ... and add to the total request price amount      
   
// Display details
?>
	<tr>
	   <td align="center"><?=$i+1?></td>

      <td>
	     <table class="">
	     <?php
	     if(trim($objReq[0]->itens[$i]->productId)!="")
	        echo "<tr><td align=\"right\">$strProd</td><td></td><td>$prodId[$i]</td>";
	     ?>
	     <tr><td align="right"><?=$strSatellite?></td><td></td><td><?=$row["Satellite"]?></td>
	     <tr><td align="right"><?=$strInstrument?></td><td></td><td><?=$row["Sensor"]?></td>
	     <tr><td align="right"><?=$strPath?></td><td></td><td><?=$row["Path"]?></td>
	     <tr><td align="right"><?=$strRow?></td><td></td><td><?=$row["Row"]?></td>
	     <tr><td align="right"><?=$strDate?></td><td></td><td><?=$row["Date"]?></td>
	     </table>
	   </td>
      <td align="center"><?=$objReq[0]->itens[$i]->media?></td>
      <td align="center">R$&nbsp;<?=$price?></td>
      <td align="center"><?=$matReqItemStatus[$indStatus]?></td> 
   </tr>
   <?php
      if ($ItemStat != -1)
			{
	 ?> 
     <tr><td align="center" colspan="5">
      <input type="button" value="<?=$strDelete?>" name="action" onclick =
		 "javaScript:Delnumber('<?=$reqId?>','<?=$ItemNum = $objReq[0]->itens[$i]->numItem ?>','<?=$strDelete?>','<?=$i?>','<?=$ItemStat?>')">
    </td>
    </tr>
    <?
       };
    ?>
<?php
}
?>
      <tr><td align="center">TOTAL</td><td colspan="2"></td><td align="center">R$&nbsp;<?=$total?></td><td colspan="1"></td></tr>
      
      <!--
      <tr><td align="center" colspan="5">
      <input type="button" value="<?=$strClose?>" name="action" ONCLICK="window.close();">
      </td>
      </tr>
      -->
<!-- <input type=hidden name=submitted value=1> --> 
<input type=hidden name=reqId value=<?=$reqId?>>
<input type=hidden name=del value="<?=$del?>">
</table>
</form>
<br>

    </div>
          
</div>

<!-- 
Final da área principal
class body 
-->




<!-- 
Inicio da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>

<!-- 
Final da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

</body>
</html>
