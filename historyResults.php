<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003 //TARGET="_blank"
//------------------------------------------------------------------------------
// Includes
//include("dbglobals.inc.php");
include("session_mysql.php");

// Session
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include_once("globals.php");
include_once("request.class.php");
include_once("user.class.php");

import_request_variables("gpc");

// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
	
require ("history_".$_SESSION['userLang'].".php");	
//require ("cart_".$_SESSION['userLang'].".php");	

//require ("arrays_".$_SESSION['userLang'].".php");	

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat  = $GLOBALS["dbusercat"];

// Search for user name
$objUser = new User($dbusercat);
$objUser->selectByUserId($userid);

// Setting Dates
$dateIni = date("Y-m-d H:i:s",  mktime (0,0,0,$IDATEM,1,$IDATEY));
if($FDATEM==2)
   $dayMonth = checkdate($FDATEM,29,$FDATEY) ? 29 : 28;
else
   $dayMonth = checkdate($FDATEM,31,$FDATEY) ? 31 : 30;
$dateEnd = date("Y-m-d H:i:s",  mktime (23,59,59,$FDATEM,$dayMonth,$FDATEY));

// Verifying the search arguments
if ($option=="all") $option = '';
if ($option=="closed") $option = 'D';
if ($option=="open") $option="*"; 
// echo " dateIni = " . $dateIni . " dateEnd = " . $dateEnd ;
$nReg = 0;
if(trim($reqId!=""))
{
   $nReg = searchReqByNumber($dbcat, $objReq,'', "", "", $reqId);
   if($nReg and $objReq[0]->userId != $userid) $nReg=0;
}
else
   $nReg = searchReqByGeneric($dbcat, $objReq, '', '', "", "", "", $dateIni, $dateEnd, $objUser->fullname, $objUser->fullname);
?>
<html>
<head> 
<title><?=$strHistory?></title>  



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


<script type="text/javascript">

function chamaVerPedido(parametroPedido)
{
	top.verPedido(parametroPedido);
	return false;
}


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



<form name="historySearch" method="POST" action="historyResults.php">
<?php
if(!$nReg)
{
?>
   <br><h1 align="center"><?=$strNoItems?><h1>
<?
} else
{
?> 
   <!--br><h3 align="center"><?=$strUserReqs?><?=$objUser->fullname?><h3-->
   <div><center>
   <!--input type="submit" value="<?=$strUpdate?>" name="action" ONCLICK="window.location.href = 'historyResults.php'"-->
   <table width="90%" align="center" cellpadding="1" cellspacing="1" class="table table-hover">
   <thead>
   <tr>
      <th><?=$strReqNumber?></th>
      <th><?=$strReqDate?></th>
      <th><?=$strReqItens?></th>
      <th><?=$strReqItensDone?></th>
      <th><?=$strReqAction?></th> 
   </tr>
   </thead>
   <tbody>
<?php
   // Loop for requests
   $oneatleast = false;
   for ($i=0; $i<$nReg;$i++)
   {
      if((trim($option)=="") or ($objReq[$i]->status == $option) or ($option=='*' and $objReq[$i]->status!='D'))
      {
        $oneatleast = true;
?>
	 
	  <tr>
	   <td align="center"><?=$objReq[$i]->reqId?></td>
	   <td align="center"><?=$objReq[$i]->reqDate?></td>
	   <td align="center"><?=$objReq[$i]->nItens?></td>
	   <td align="center"><?=$objReq[$i]->nItensDone?></td>
       <td align="center"><a href="historyDetails.php?reqId=<?=$objReq[$i]->reqId?>"><?=$strReqDetails?></a></td>
      </tr>
            
      
<?php
      }
   }

?> 
     <input type=hidden name=userid value=<?=$userid?>> 
     <input type=hidden name=reqId value=<?=$reqId?>>
     <input type=hidden name=option value=<?=$option?>>
     <input type=hidden name=IDATEM value=<?=$IDATEM?>>
	   <input type=hidden name=IDATEY value=<?=$IDATEY?>>
  	 <input type=hidden name=FDATEM value=<?=$FDATEM?>>
		 <input type=hidden name=FDATEY value=<?=$FDATEY?>> 
     
</tbody>
</table>
</form>

<?php

	if ( $nReg > 0 )
	{
?>		
      		<br>
      		<center>
            
          <input type="submit" value="Atualizar Pesquisa" name="atualizar" class="btn btn-info" style="width:180px; border-radius:4px">&nbsp;&nbsp;
	      <input type="button" value="Retornar para Pesquisa" name="retornar" class="btn" style="width:180px; border-radius:4px" onClick="javascript:location.href='history.php'">
	  
       		</center>
<?php		
	}

     if (!$oneatleast) echo "<h3>" . $strNoItems . "<h3>";
};
?>



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
