<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : march/2004
//------------------------------------------------------------------------------
//include_once("../classes/request.class.php");
//include_once("../classes/cart.class.php");
//include_once("../classes/user.class.php");
include("session_mysql.php");
// Session

session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include_once("globals.php");
include_once("request.class.php");
include_once("cart.class.php");
include_once("user.class.php");
include_once( "payment.class.php" );
import_request_variables("gpc");


// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("cart_".$_SESSION['userLang'].".php");	
require ("messageCat_".$_SESSION['userLang'].".php");
require ("register_".$_SESSION['userLang'].".php");	

?>
<html>
<head>
<title><?=$strCart?></title>
<script type="text/javascript">
<!--
function openwinAddr(params, sess, itens)
{
  url = "cartDeliveryAddr.php?userid=" + params +  "&sesskey=" + sess + "&nItens=" + itens;
	newWinAddr=window.open(url,'windowAddress','resizable=yes,scrollbars=yes,width=600,height=300');
	newWinAddr.focus();
}
function goOn(key,act,sesskey,userid,nItens,total,mediaCD)
{
	 url = "cart.php?";
   if(key == 2) 
	 {
	  formaPagamento = '';
    for( i = 0; i < 3; i++ ) 
		{
         if( document.paymentChooseForm.formaPagamento[i].checked == "1" ) {
          formaPagamento = document.paymentChooseForm.formaPagamento[i].value;
	       break
        }
     }
     url = "cartAddress.php?formaPagamento=" + formaPagamento + "&action=" + act + "&";
    }
   params = "sesskey=" + sesskey + "&userid=" + userid + "&nItens=" + nItens + "&total=" + total + "&mediaCD=" + mediaCD;
   
   //parent.frames["mosaico"].location.href = url+params;
   // Alterado o container da nova página
   document.location.href = url+params;
}
function openPaymentConfirmationWindow( reqId, addrId, userId, formaPagamento)
{
    msg = "                                        I M P O R T A N T E \n\n";
		msg = msg = msg + "NÃO FAÇA O PAGAMENTO até receber confirmação da DGI/INPE (via E-mail)\n";
    msg = msg + "de que a imagem solicitada possa ser fornecida com qualidade satisfatória.";
    alert (msg);

//		  formaPagamento = '';
//      for( i = 0; i < 3; i++ ) {
//        if( document.paymentChooseForm.formaPagamento[i].checked == "1" ) {
//          formaPagamento = document.paymentChooseForm.formaPagamento[i].value;
//	     break
//      }
//   }

    url = "paymentConfirmation.php?reqid=" + reqId;
    url = url + "&userid=" + userId + "&addressid=" + addrId;
    url = url + "&formaPagamento="  + formaPagamento;

    if( formaPagamento != '' ) {
      window.open( url, "PaymentConfirmation" );
      // parent.frames["mosaico"].location.href = "waitingConfirmation.php?reqId=" + reqId;
	  // Alterado o container da nova página
	  document.location.href = "waitingConfirmation.php?reqId=" + reqId;
    } 
}

function chamaAtualizaNumeroItensCarrinho()
{
	top.atualizaNumeroItensCarrinho();
}

//-->
</script>



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
    

<!--?php include("css.php");?-->

</head>
<body onLoad="chamaAtualizaNumeroItensCarrinho()">
<?
// Verify Action
// if($action == $strCancel) 
if($action == $strBack)
{
?>
<script type="text/javascript">
goOn('1','void','<?=$sesskey?>','<?=$userid?>','<?=$nItens?>','<?=$total?>','<?=$mediaCD?>');
</script>
<?php
}

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];
$operator =   $_SESSION['operatorId'];

// --- if ($GLOBALS["stationDebug"]) {
	// --- echo "Session = $PHPSESSID". " Operator = " . $operator . " User = " . $_SESSION['userId'] . "Language = " .  $_SESSION['userLang']. " <br>\n";
// --- }
// Serching User Address
$nUser = searchByUserid($dbusercat, $matUser, $matAddr, $matComp, "", $userid, 1);

log_msg( "number of users: ".$nUser . "Number of addresses:". count($matAddr) . " AddrId = ". $matAddr[$address]->addressId . " Address: ".$address);

$CNPJ_CPF=$matUser[0]->CNPJ_CPF;
$compCNPJ = $matUser[0]->compCNPJ;
// $nAddr = searchAddressesByUserid($dbusercat, $matAddr, $userid);
$nAddr = searchAddrByCNPJ_CPF($dbusercat, $matAddr, $CNPJ_CPF, $compCNPJ);
if($total > 0 and ($_SESSION['userType'] != 1 or $_SESSION['userType'] != 3)) // Priced products and user not the System Manager
                                                                             // or Internal User.
 if($nAddr == 0) die("<br> <h3>$strNoCPFCNPJ<h3> <br>");

// Instanciating Cart Group
$objCartG = new CartGroup($dbcat);
$objCartG->fill($sesskey, $userid); 


// Verify Action
//if($action != $strCloseReq)
if($action == $strContinue or $action == $strAdd)
 {

?>
<form name="cartAddressForm" method="POST" action="cartAddress.php">

<?php

   // Showing Addresses
   if($nAddr)
     {  
       if ($mediaCD or $action == $strAdd)  
       {    
         ?>
         <br><h1><?=$strDeliveryAddr?></h1><hr>
         <?php
         for($i=0; $i < $nAddr ; $i++)
         {
           $complement = (trim($matAddr[$i]->complement)!= "") ? " - " . $matAddr[$i]->complement : "";
           ?>

           <table align= "center"> 

           <tr>
           <td width="20" align="center"><input type="radio" <?=($i==0)?"Checked":""?> value="<?=$i?>" name="address"></td>
           <td width="650"><?=$matUser[0]->fullname?></td>
           </tr>
           <tr>
           <td width="20"></td>
           <td width="650"><?=trim($matAddr[$i]->street).", ".trim($matAddr[$i]->number).$complement?></td>
           </tr>
           <tr>
           <td width="20"></td>
           <td width="650"><?=trim($matAddr[$i]->city).", ".$matAddr[$i]->state." - ".$matAddr[$i]->cep?></td>
           </tr>
           <tr>
           <td width="20"></td>
           <td width="650"><?=$matAddr[$i]->country?></td>
           </tr>
           <tr><td colspan="2"></td></tr>
           <?php
         }

         ?>
         <tr>
         <?php    
         if($mediaCD) 
         {
           ?>
           <td colspan="2" align="center"><a href="javascript:openwinAddr('<?=$userid?>', '<?=$sesskey?>', <?=$nItens?> )"><?=$strOtherAddr?></a></td>
           <?php
         }
       }
     };  
  ?>
   </tr>
   </table>
   
   <hr>
   <!--h2><?=$strAcceptance?><h2-->
   <font face="Arial, Helvetica, sans-serif" size="3"></i><?=$strAcceptance?></font>
	 <hr> 
   <table align="center">
   <tr>
      <td><input class="btn btn-success" type="submit" value="<?=$strCloseReq?>" name="action" style="border-radius:3px;width:120px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:14px;"></td>
      <td><input class="btn" type="submit" value="<?=$strBack?>" name="action" style="border-radius:3px;width:120px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:14px;"></td>
   </tr>
   </table>
   <input type="hidden" name="sesskey" value="<?=$sesskey?>">
   <input type="hidden" name="userid" value="<?=$userid?>">
   <input type="hidden" name="nItens" value="<?=$nItens?>">
   <input type="hidden" name="total" value="<?=$total?>">
   <input type="hidden" name="mediaCD" value="<?=$mediaCD?>">
</form>
<?php
} //end of address selection code


// Generating the Request and Request Itens - If Request closed

else if( $action == $strCloseReq and $nItens)
 {
  if(!isset($address)) $address = 0;
	$objCartG->operator  = $operator;
  $objCartG->addressId = $matAddr[$address]->addressId;  
  $total               = $objCartG->getTotalPrice();
 	if ($total == 0 or $GLOBALS['turnOnEletronicPayment'] == false)
  {
    if( !($reqId = $objCartG->generateRequest( $dbusercat )) ) 
		{
      echo "Mensagem = " . $cartRequestE1;
      die( $cartRequestE1 );
    }

  // Get the current Language for providing correct mail message language

  $lang = $_SESSION['userLang'];
  $sql = "UPDATE Request SET Language = '$lang' WHERE ReqId = '$reqId'";
  if( !$dbcat->query($sql) ) {
    $dbcat->error();
    die( "<br> ===========> Error Updating Data Base ! <br>" );
  };
    

  // Execute wget for each product  
  $path = "/usr/bin/wget -b -a " . $GLOBALS["systemLog"] . "wget.log -O " . $GLOBALS["systemLog"] . "wgetExec_" .$reqId . "log ";
  $path .= $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId"; 
  $path = escapeshellcmd ($path);
  $path .= " &"; 
  #
  # ATENï¿½O PARA A POSSIVEL OCORRENCIA DE ERRO QUANDO AS OPï¿½ES DE LOG ESTAO SETADAS ; EM ALGUMAS INSTALAï¿½ES, 
  # O COMANDO "WGET" ï¿½EXECUTADO COM SUCESSO EM LINHA DE COMANDO, MAS Nï¿½ FUNCIONA QUANDO DISPARADO DO PHP.
  # NA OCORRï¿½CIA DE UMA TAL ANOMALIA, UMA SOLUï¿½O CONSISTE EM SUPRIMIR ALGUMAS OPï¿½ES UTILIZADAS NA SINTAXE  
  # DO COMANDO.
  #
  # EX.: $path = "/usr/bin/wget " . $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId &";
  #

  //   echo "PATH Wget = " . $path;
  log_msg('*** - Executing in catalog: '.$path);
  exec( $path,$output,$retval );

  
  if( $retval == 0 ) 
  {
	/*
    echo '<hr><h3 align="center">'.$strConfirm1.$reqId.$strConfirm.$strConfirm2;
    if ( $mediaCD and $GLOBALS['turnOnEletronicPayment'] == false ) echo $strConfirm3;
    echo '</h3>';    
	echo '<hr><h3 align="center">'.$strNewsearch.'<h3>';
	*/
	
    echo '<hr><font size="3"><i class="icon-thumbs-up" style="color:#390;font-size:22px"></i>&nbsp;&nbsp;'.$strConfirm1.$reqId.$strConfirm.$strConfirm2;
    if ( $mediaCD and $GLOBALS['turnOnEletronicPayment'] == false ) echo $strConfirm3;
    echo '</font>';    
    echo '<hr>';    
	//echo '<hr><font size="3">'.$strNewsearch.'</font>';
	
	
	
	
  }


  //   echo "Retorno Wget = " . $output[0] . " Retorno = " . $reval;
  // If everything OK, delete cart ???
 } 
 else 
 {
    ?>
           <form name="paymentChooseForm"> 
					 <h2 align="center"><?=$strChoosePaymentType?><h2>
           <table align="center">
           <tr>
           <td width="20" align="center"><input type="radio" Checked value="Boleto" name="formaPagamento"></td>
           <td>Boleto Bancario</td>
           </tr>
           <tr>
           <td width="20" align="center"><input type="radio" value="Visa" name="formaPagamento"></td>
           <td>Visa</td>
           </tr>
           <tr>
           <td width="20" align="center"><input type="radio" value="Mastercard" name="formaPagamento"></td>
           <td>Mastercard</td>
           </tr>
           </table>
           <table align="center">
           <tr>
           <td><input type="button" value="<?=$strAcquire?>" name="action" onClick="javascript:goOn('2','<?=$strAcquire?>','<?=$sesskey?>','<?=$userid?>','<?=$nItens?>','<?=$total?>','<?=$mediaCD?>');"></td>
					 <td><input type="button" value="<?=$strBack?>" name="action" onClick="javascript:goOn('1','void','<?=$sesskey?>','<?=$userid?>','<?=$nItens?>','<?=$total?>','<?=$mediaCD?>');"></td>
           </tr>
           </table>
           </form>
           <h4><center><?=$strDisablePopup?></center></h4>
       <?
 }
}
else if($action == $strAcquire)
  {
   log_msg( __FILE__.":".__LINE__.": Inserindo ".$reqId." em RequestPayment" );
   
    if(!isset($address)) $address = 0;
	  $objCartG->operator  = $operator;
    $objCartG->addressId = $matAddr[$address]->addressId;  
    $total               = $objCartG->getTotalPrice();
    
    if( !($reqId = $objCartG->generateRequest( $dbusercat )) ) {
       echo "Mensagem = " . $cartRequestE1;
       die( $cartRequestE1 );
    };
    
    $addressData  = $matAddr[$address]->street.' '.$matAddr[$address]->number."\n".' '.$matAddr[$address]->complement."\n";
    $addressData .= $matAddr[$address]->city." - ".$matAddr[$address]->state."\n"."CEP ".$matAddr[$address]->cep."\n";
    $addressData .= $matAddr[$address]->country;
    $userData     = $matUser[0]->fullname."\nCPF: ".$matUser[0]->CNPJ_CPF."\nTel.: (".$matUser[0]->areaCode.") ".$matUser[0]->phone."\n".$matUser[0]->email;
    $sql  = "INSERT INTO RequestPayment ";
    $sql .= "(ReqId, addressId, addressType, addressData, userData, CNPJ_CPF, compCNPJ, digitCNPJ, district, delivery, payment, Price) ";
    $sql .= " VALUES ";
    $sql .= "('".$reqId."','".$matAddr[$address]->addressId."','";
    $sql .= $matAddr[$address]->addressType."','".$addressData."','".$userData."','".$matAddr[$address]->CNPJ_CPF;
    $sql .= "','".$matAddr[$address]->compCNPJ."','".$matAddr[$address]->digitCNPJ;
    $sql .="','".$matAddr[$address]->district."','".$matAddr[$address]->delivery."','".$matAddr[$address]->payment."','".$total."');" ;
    log_msg( __FILE__.":".__LINE__.": SQL: ".$sql );

    if( !$dbcat->query( $sql ) ) { 
      $dbcat->error();
      die ("<br> ===========> Error Updating RequestPayment Data Base $dbcat->db_name ! <br>");
    };

  // Get the current Language for providing correct mail message language

  $lang = $_SESSION['userLang'];
  $sql = "UPDATE Request SET Language = '$lang' WHERE ReqId = '$reqId'";
  if( !$dbcat->query($sql) ) {
    $dbcat->error();
    die( "<br> ===========> Error Updating Data Base ! <br>" );
  };
      
 
  // Execute wget for each product  
  $path = "/usr/bin/wget -a " . $GLOBALS["systemLog"] . "wget.log -O " . $GLOBALS["systemLog"] . "wgetExec_" .$reqId . "log ";
  $path .= $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId"; 
  $path = escapeshellcmd ($path);
  $path .= " &"; 
  #
  # ATENï¿½O PARA A POSSIVEL OCORRENCIA DE ERRO QUANDO AS OPï¿½ES DE LOG ESTAO SETADAS ; EM ALGUMAS INSTALAï¿½ES, 
  # O COMANDO "WGET" ï¿½EXECUTADO COM SUCESSO EM LINHA DE COMANDO, MAS Nï¿½ FUNCIONA QUANDO DISPARADO DO PHP.
  # NA OCORRï¿½CIA DE UMA TAL ANOMALIA, UMA SOLUï¿½O CONSISTE EM SUPRIMIR ALGUMAS OPï¿½ES UTILIZADAS NA SINTAXE  
  # DO COMANDO.
  #
  # EX.: $path = "/usr/bin/wget " . $GLOBALS["dispatcher"] . "requestDispatcher.php?reqId=$reqId &";
  #

  //   echo "PATH Wget = " . $path;
  log_msg('Executing in catalog: '.$path);
  exec( $path,$output,$retval );
  //   echo "Retorno Wget = " . $output[0] . " Retorno = " . $reval;
  // If everything OK, delete cart and execute the request.

?>
<script type="text/javascript"> 
 
 openPaymentConfirmationWindow('<?=$reqId?>', '<?=$matAddr[$address]->addressId?>', '<?=$userId?>', '<?=$formaPagamento?>');

</script>

<?php  
 
  }

?>


<!-- Placed at the end of the document so the pages load faster -->
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>



<script>

$(document).ready(function(){
	
	
		$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur", function(e)
		{
		 	top.atualizaIdleTime();
		});
				
});


</script>




</body>
</html>
