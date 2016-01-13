<?php 
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 



//------------------------------------------------------------------------------
// Alterado Denise
//
// >>>v1
//------------------------------------------------------------------------------
// Includes
include("session_mysql.php");
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include ("globals.php");
include_once("cart.class.php");

import_request_variables("gpc");

// Session

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("cart_".$_SESSION['userLang'].".php");	

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

$sensors = array(6,MSS,TM,ETM,CCD,IRM,WFI);
$sensors_discount = array(6,MSS,TM,ETM,CCD,IRM,WFI);
$sensors_price = array(6,MSS,TM,ETM,CCD,IRM,WFI);

// echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>" ;



// Inclusão do arquivo com as funções necessárias para o correto uncionamento
require_once('func.vercarrinho.php');



?>
<html>
<head>
<title><?=$strCart?></title>
<script language="javascript">
<!--
function changePrice( midiaindex, carId, OKtopurchase )
{ 
  midia = midiaindex;
  if( midiaindex == 1 ) // midia CD
  {  
    if( OKtopurchase == 0 )
    {
      alert( "<?=$strRestriction2?>" );
      midia = 0; // document.productForm.midia.selectedIndex;
    }
  };
  url    = "cart.php"
  params = "?ID=" + carId + "&mid=" + midia + "&action=changePrice"
  //parent.frames["mosaico"].location.href = url + params;
  document.location.href = url + params;
}

function displayDetails(url) {
//	nwin3=window.open(url,'cartDetails', resizable=yes,scrollbars=yes,width=400,height=500);
  nwin3=window.open(url,'prodForm', resizable=yes,scrollbars=yes,width=400,height=500);
	nwin3.focus();
}
function openwin(url) {
  nwin2=window.open(url,'prodForm','resizable=yes,scrollbars=yes width=500,height=600');
	nwin2.focus();
}






/**
* Função para apresentar uma janela com informações da imagem informada através da
* chamada a função detalhesDaImagemPorSceneId existente no documento principal
*
*/
function chamaInformacoesImagem( parametroSceneId )
{
	// Executa a função detalhesDaImagemPorSceneId na página principal
	top.detalhesDaImagemPorSceneId( parametroSceneId );	
}





function verMaisInformacoesCarrinho( parametroImagemIndice )
{
	
	var idLinhaTabela = "linhamaisinfo"  + parametroImagemIndice;
	var idBotaoAtual = "maisinfo"  + parametroImagemIndice;

	
	
	var linhaVerMais = document.getElementById(idLinhaTabela);
	var botaoAtual = document.getElementById(idBotaoAtual);
	
	var jqueryLinhaVerMais = "#" + idLinhaTabela;
	
	
	//alert (botaoAtual.className);
	
	if ( botaoAtual.className == "btn" )
	{
		linhaVerMais.style.display = 'none';		
		botaoAtual.className = 'btn btn-info';	
	}
	else
	{
		linhaVerMais.style.display = 'block';
		botaoAtual.className = 'btn';	
	}
	

	//$(jqueryLinhaVerMais).slideToggle("slow");	
	
	
}








-->
</script>

<?php 
//include("css.php");
?>


    <!-- Estilos -->
    <link href="/catalogo/css/bootstrap.css" rel="stylesheet">
    <link href="/catalogo/css/style.css" rel="stylesheet">
    <!--link href="/catalogo/css/camera.css" rel="stylesheet"-->
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




<script type="text/javascript">

function chamaAtualizaNumeroItensCarrinho()
{
	top.atualizaNumeroItensCarrinho();
}

</script>


</head>
<body bgcolor="#FFFFFF" onLoad="chamaAtualizaNumeroItensCarrinho()">
<?php
if ($GLOBALS["stationDebug"])
	//echo "Session = $PHPSESSID Operator = ".$_SESSION['operatorId']." User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";

// Mount media table
$nMidias = searchMedia($dbcat, $matMedia);
/*$sql  = "SELECT * FROM MediaPrice";
if(!$dbcat->query($sql)) $dbcat->error();
$matMedia[0] = $dbcat->numRows();
$count=1;
while ($row_array = $dbcat->fetchRow())
{
	$matMedia[$count++] = $row_array["Media"];
}
*/


// Check if a request will be posted
$doRequest = false;
$userChecked = false;
$localUserId = "";
$objUser = new User($dbusercat);
// User must be logged to make a request
if (isset($_SESSION['userId']))
{
	$localUserId = $_SESSION['userId'];
	$userChecked = true;
   // Serching user
  $objUser->selectByUserId($localUserId);
  $usertype = $objUser->userType;
}

$objUser->selectByUserId($localUserid);

// Find if User is able for purchasing

$OK_to_purchase = 0;
$cnpj = $objUser->CNPJ_CPF;

if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;

// Check if an item will be deleted
if ($action == $strDelete)
{
	$objCart = new Cart($dbcat);
	$objCart->removeId($ID);
}else
{
  // Check if an item had its media changed
  if( $action == "changePrice" ) {
    $objCart = new Cart($dbcat);
    $objCart->search($ID);
    searchMedia($dbcat, $objMedia, $objCart->media);
    $objCart->media = $matMedia[$mid]->getMedia();

    $sql = "SELECT * FROM Scene WHERE SceneId='".$objCart->sceneId."'";
    $dbcat->query($sql) or $dbcat->error ($sql);
    $row = $dbcat->fetchRow();

    //$objCart->price = getPriceFromSatellite( $row["Satellite"], 1, $objCart->media );

    $objCart->price -= $objMedia[0]->getPrice( $_SESSION['userLang'] );
    $objCart->price += $matMedia[$mid]->getPrice( $_SESSION['userLang'] );

    // Apply changes
    $objCart->modify();
  }
}


// Searching for cart
$sql = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
if ($itens == 0)
	die ("<h3> <img src='/catalogo/img/noimage.jpg'> &nbsp;&nbsp; $strEmpty </h3>");

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


<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="font-size:15px">
<thead bgcolor="#FFFFFF">
  <tr height="40">
    <th><?=$strItem?></th>
    <th><?=$strThumb?></th>
    <th align="left"><?=$strInfo?></th>
<?
   if($userChecked)
   {
?>
    <th align="center"><?=$strMedia?></th>
    <th align="center"><?=$strPrice?></th>
<?
   }
?>
    <th align="center"><?=$strAction?></th>
  </tr>
</thead>
<tbody>

<?php
// Variables
$today = date( "Y-m-d H:i:s" );
$total = 0;
 
$corRegistroCinza="#EEEEEE"; 
$corRegistroBranco="F9F9F9"; 
$corRegistroAtual="";
 
// Loop for scenes
for ($i=0; $i<$count;$i++)
{
	
	$corRegistroAtual=(($i % 2) == 0)?$corRegistroCinza:$corRegistroBranco;

	
   // Get data from Scene Table
	$sql = "SELECT * FROM Scene WHERE SceneId='". $scenes[$i]."'";
	$dbcat->query($sql) or $dbcat->error ($sql);
	$itens = $dbcat->numRows();

	if ($itens == 0)
		die("Erro nas Cenas");
	$row = $dbcat->fetchRow();
	$dbcat->freeResult($results);
	
	
	
   // Get Scene price and Sensor count if priced item
   $total += $price[$i];
   if ($price[$i] > 0) $sensors[$row["Sensor"]] += 1;
   $sensors_price[$row["Sensor"]] = $price[$i];

   switch ($row["Satellite"])
    {
     case "CB1":
     case "CB2":
     case "CB2B":
     case "CB4":
       $tab_sat_prefix[$i] = "Cbers";
       break;
     case "L1":
     case "L2":
     case "L3":
     case "L5":
     case "L7":
     case "L8":
       $tab_sat_prefix[$i] = "Landsat";
       break;
     case "A1":
     case "T1":
       $tab_sat_prefix[$i] = "Modis";
       break;
     case "P6":
       $tab_sat_prefix[$i] = "P6";
       break;
     case "UKDMC":
       $tab_sat_prefix[$i] = "UKDMC";
       break;
     case "RES2":
       $tab_sat_prefix[$i] = "RES2";
       break;
    }
	
	
	
	$dirSateliteAtual="";
	
	switch( strtoupper($row["Satellite"]) )
	{
		case "A1":
			$dirSateliteAtual="AQUA";
			break;
		
		case "T1":
			$dirSateliteAtual="TERRA";
			break;
		
		case "NPP":
		case "SNPP":
		case "S-NPP":
			$dirSateliteAtual="S-NPP";
			break;
		
		case "UKDMC2":
		case "UK-DMC2":
		case "UK-DMC-2":
			$dirSateliteAtual="UK-DMC-2";
			break;
		
		case "RE1":
			$dirSateliteAtual="RAPIDEYE1";
			break;
				
		case "RE2":
			$dirSateliteAtual="RAPIDEYE2";
			break;
		
		case "RE3":
			$dirSateliteAtual="RAPIDEYE3";
			break;
		
		case "RE4":
			$dirSateliteAtual="RAPIDEYE4";
			break;
		
		case "RE5":
			$dirSateliteAtual="RAPIDEYE5";
			break;
		
		case "P6":
			$dirSateliteAtual="RESOURCESAT1";
			break;
		
		case "RS2":
		case "RE2":
		case "RES2":
			$dirSateliteAtual="RESOURCESAT2";
			break;
		
		case "L8":
		case "LANDSAT8":
		case "LANDSAT-8":
			$dirSateliteAtual="LANDSAT8";
			break;
		
		case "CB4":
		case "CB04":
		case "CBERS4":
		case "CBERS-4":
			$dirSateliteAtual="CBERS4";
			$dirAno=substr($row["SceneId"], 12,4 );
			$imagemQuickLook = "/QUICKLOOK/" . $dirSateliteAtual . "/" . strtoupper($row["Sensor"]) . "/" . $dirAno . "/QL_" . strtoupper($row["SceneId"]) . "_MIN.png";
			break;
		
	}
		
			
	
	//$imagemQuickLook = "/QUICKLOOK/" . $dirSateliteAtual . "/" . strtoupper($row["Sensor"]) . "/QL_" . strtoupper($row["SceneId"]) . "_MIN.png";
	if ( strtoupper($row["Satellite"]) != "CB4" )
	$imagemQuickLook = "/QUICKLOOK/" . $dirSateliteAtual . "/" . strtoupper($row["Sensor"]) . "/QL_" . strtoupper($row["SceneId"]) . "_MIN.png";
	
	
	
	
?>
	<tr bgcolor="#CCCCCC" style="height:2px">
    	<td colspan="6" style="height:2px"></td>
    </tr>
    
	<tr bgcolor="<?php echo $corRegistroAtual ?>">
    	<td colspan="6" height="6">&nbsp;</td>
    </tr>
    
    
	<tr bgcolor="<?php echo $corRegistroAtual ?>">

       <font size="2">
       
	   <td align="center"><font size="4"><b><?=$i+1?></b></font></td>
       
	   <td align="center">       
       		<img src="<?php echo "$imagemQuickLook"; ?>" border="0">       
       </td>
       
       
         
	   <td>
	     <table style="font-size:13px">
	     <?php
	     if(trim($prodId[$i])!="")
	        echo "<tr><td align=\"left\">$strProd</td><td></td><td align=\"left\">$prodId[$i]</td>";
	     ?>

	     <tr><td align="left" colspan="3"><font size="2"><?=$row["SceneId"]?></font></td>
         <tr><td align="left" colspan="3" height="4">&nbsp;</td>
         
         <tr><td align="left"><?=$strSatellite?></td><td></td width="4"><td align="left"><?=$row["Satellite"]?></td>
	     <tr><td align="left"><?=$strInstrument?></td><td></td  width="4"><td align="left"><?=$row["Sensor"]?></td>
	     <tr><td align="left"><?=$strPath?></td><td></td width="4"><td align="left"><?=$row["Path"]?></td>
	     <tr><td align="left"><?=$strRow?></td><td></td width="4"><td align="left"><?=$row["Row"]?></td>
	     <tr><td align="left"><?=$strDate?></td><td></td width="4"><td align="left"><?=$row["Date"]?></td>
	     </table>
	   </td>
<? 
      
      if($userChecked)  
      { 	     		
?>	

      <td align="center" style="font-size:14px">FTP</td>
      <td align="center" style="font-size:14px">R$&nbsp;<?=$price[$i]?></td>
<?
	    }

?>


      <td align="center" >
          <table style="font-size:14px">
          <tr>
              <td align="center" >
                  <a href="cart.php?ID=<?php echo $id[$i]?>&sceneid=<?php echo $scenes[$i]?>&prodid=<?php echo $prodId[$i]?>&action=<?php echo $strDelete ?>"><input id="deletaritem" type="button" class="btn btn-danger" value="<?php echo $strDelete ?>" style="border-radius:3px;width:70px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;"></a>
              </td>
          </tr>
                  
          <tr>
              <td align="center" >
                  
      
                  <input id="maisinfo<?php echo $i ?>" type="button" class="btn btn-info" value="<?=$strDetail?>" style="border-radius:3px;width:70px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;" onClick="verMaisInformacoesCarrinho( <?php echo $i ?> )">
      
              </td>
          </tr>
          </table>
      </td> 
    </font>
</tr>

<tr bgcolor="<?php echo $corRegistroAtual ?>">
	<td colspan="6">

<?php


	// Definição de uma array com informações da imagem atual para 
	// ser utilizada por funções em PHP
	$strArrayImagemAtual=array (  
								$i  , 
								$row['SceneId'] , 
								$row['Satellite'] , 
								$row['Sensor'] , 
								$row['Date'] ,
								$row['CenterLatitude'] ,
								$row['CenterLongitude'] ,
								
								$row['TL_Latitude'] , 
								$row['TL_Longitude'] , 								
								$row['TR_Latitude'] ,
								$row['TR_Longitude'] , 

								$row['BR_Latitude'] ,
								$row['BR_Longitude'] ,
								$row['BL_Latitude'] ,
								$row['BL_Longitude'] ,

								$row['Path'] , 
								$row['Row'] ,							
								
								$row['CloudCoverQ1'] ,
								$row['CloudCoverQ2'] ,								
								$row['CloudCoverQ3'] ,
								$row['CloudCoverQ4'] ,
								
								$row['Regiao'] ,
								$row['Fuso'] );
	



apresentaMaisDetalhesImagemCarrinho( $strArrayImagemAtual, $corRegistroAtual , '#FFFFFF' );

?>
	</td>
</tr>

<tr bgcolor="<?php echo $corRegistroAtual ?>">
    <td colspan="6" height="6">&nbsp;</td>
</tr>



<?

}

echo ' 	<tr bgcolor="#CCCCCC" style="height:3px">';
echo '    	<td colspan="6" style="height:3px"></td>';
echo '  </tr>';


#
# ===========================================   Discount Evaluation Module ====================================
#
// Check if number of images allows a discount
/*
 "Discount" is a field in the records of a table named "price" (of Catalog Data Base) storing the "discount-formulae".
 Discount-formulae is expressed by 3 sequences of 2 digit numbers, each one separeted by a "-" character from the next
 digit on the sequence. For instance, If we have in a "price" record "Discount" field a discount-formulae expressed by  
 15-24-10-25-49-15-50-@@-20 , it is meant that quantities of ordered scenes greater than or equal 15 and less than or equal 24 will have
 a 10% discount for the total price; quantities of ordered scenes greater than or equal 25 and less than or equal 49 
 will have a 15% discount ; and finally, quantities of ordered scenes greater than or equal 50 will have a 20% discount;
 the '@@' flag means no upper limit. 
*/

$total_discount_amount = 0;

for ($i=1; $i<=$sensors[0];$i++)
{
 // check if sensor has discount policy.
 $sql  = "SELECT Discount FROM price WHERE Sensor  = '" . $sensors[$i] . "'" . " and Corrlevel ='2'" . " and Discount != '0'";
 if (!$dbcat->query($sql)) die ($dbcat->error ($sql));
 $numrows = $dbcat->numrows();
 $sens = $sensors[$i];
 $nscenes = $sensors[$sens]; // In this case, the discoutn criteria doesn't allow adding quantities for different sensor images.
// $nscenes += $sensors[$sensors[$i]];  // uncomment - case the discount criteria allows adding quantities for different sensor images.
 if ($numrows > 0 and $nscenes > 0) // we have a discount and a priced sensor image. 
 {
   $myrow = $dbcat->fetchRow();
	 $discount_params = explode("-", $myrow[0]); // 
//	 while (list($key,$val) = each($discount_params))
//   echo $key . " " . $val . "<br>";
	 if ($nscenes >= $discount_params[0] and $nscenes <= $discount_params[1]) $sensors_discount[$sens] = $discount_params[2];
	 else
	   if($nscenes >= $discount_params[3] and $nscenes <= $discount_params[4]) $sensors_discount[$sens] = $discount_params[5];
		 else 
		   if ($nscenes >= $discount_params[6]) $sensors_discount[$sens] = $discount_params[8];
 }; 
 if($sensors_discount[$sens] > 0) $total_discount_amount += ($nscenes * $sensors_price[$sens]) * ($sensors_discount[$sens])/100;  
        
};

if($total_discount_amount == 0) $strDiscount = "";	else $total = $total - $total_discount_amount;

#
# ============================================= End of Discount Evaluation Module ==================================
#

if ($GLOBALS["paymentTestMode"] && $total > 0 ) {
  $total=1;
}

//
// Show total value
if($userChecked)
   echo "<tr bgcolor=\"#EEEEEE\" height=\"40\"><td align=\"center\">TOTAL $strDiscount </td><td colspan=\"3\"></td><td align=\"center\"><b>R$&nbsp;
      $total</b></td><td colspan=\"1\">";
      
// Show Request Button
echo "<input type=hidden name=userid value=$localUserId>\n"; 
echo "<input type=hidden name=sesskey value=$PHPSESSID>\n";

if (isset($_SESSION['userId']))
//	 echo "<tr><td colspan=\"9\" align=\"center\"><input type=\"submit\" value=\"$strRequest\" name=\"action\"></td></tr>";
{
//      echo "<tr ><td colspan=\"9\" align=\"center\"><input type=\"submit\" value=\"$strRequest\" name=\"action\">";
   echo "<tr><td colspan=\"9\" align=\"center\">";
	 if ($total > 0)
//	  if ($usertype > 0) echo "<input type=\"submit\" value=\"$strContinue\" name=\"action\">";

     if (true) echo "<br><input class=\"btn btn-success\" type=\"submit\" value=\"$strContinue\" name=\"action\" style=\"border-radius:3px;width:90px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;\">\n";
	  else ; 
	 else echo "<br><input class=\"btn btn-success\" type=\"submit\" value=\"$strContinue\" name=\"action\" style=\"border-radius:3px;width:90px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;\">\n";  
	 
   //echo "<input type=button value=$strClose onClick=window.location.href='first_" . $_SESSION['userLang'] . ".php'>";
   echo "\n</td></tr>"; 
} 
//else echo "<tr><td colspan=9 align=center><input type=button value=$strClose onClick=window.location.href='first_" . $_SESSION['userLang'] .".php'></td></tr>";   
 
echo "</form>
</tbody>
</table>";


//if (isset($_SESSION['userId']) and $total) echo "<h2>" . $strRestriction1 . "</h2>";  // Priced product

if (isset($_SESSION['userId']))
{
//	if ($action != $strRequest)
//		echo $strInformation;
}
else
{
	//echo $strWarning;   cellpadding="8" cellspacing="8" style="border-radius:4px"	

	echo '<br><br>';
	echo '<table border="0">';
	echo ' 	<tr bgcolor="#EEEEEE">';
	
	echo '    	<td width="50" height="50" align="center"   valign="middle">';	
	echo '			<font size="6">';	
	echo '			<i class="icon-info-sign" style="color:#CC0000"></i>';	
	echo '			</font>';	
	echo '		</td>';
	
	
	echo '    	<td height="50" valign="middle">';
	
	echo '			<font size="2" color="#444444">';	
	echo '			Para executar o download destas imagens, o usuário já cadastrado deve proceder antes ao <a href="" id="acesso" style="color:#000000"><b>log in</b></a>. O usuário não cadastrado deve, primeiramente, <a href="" id="cadastro" style="color:#000000"><b>cadastrar-se</b></a> no sistema.';	
	echo '			</font>';
	
	echo '		</td>';
	echo '  </tr>';
	echo '</table>';

}
?>


<!-- Placed at the end of the document so the pages load faster -->
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>



<script>

function chamaAtualizaNumeroItensCarrinho()
{
	top.atualizaNumeroItensCarrinho();
}


$(document).ready(function(){
	


		// Executa processamento para o botão login
        $("#acesso").live('click',function(e) {
			e.preventDefault();
			top.abreJanelaLogin();			
		});
	
	
		// Executa processamento para o botão login
        $("#cadastro").live('click',function(e) {
			e.preventDefault();
			top.abreJanelaCadastro();			
		});
		
	
		$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur", function(e)
		{
		 	top.atualizaIdleTime();
		});
		
									
				
});


</script>




</body>
</html>