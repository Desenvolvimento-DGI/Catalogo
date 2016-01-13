<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Includes
//include("dbglobals.inc.php");
//include_once("..\classes\request.class.php");
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
require ("history_".$_SESSION['userLang'].".php");	
require ("cart_".$_SESSION['userLang'].".php");	

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

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
    
    <link href="/catalogo/css/skin-blue.css" rel="stylesheet">
    <link href="/catalogo/css/bootstrap-responsive.css" rel="stylesheet">



<!--?php include("css.php");?-->
</head>


<body class="wide" style="background-color:transparent; border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0 0px; height:100%; padding-bottom:0px;">

<div class="body" style="background-color:#FFFFFF; border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <div class="row-fluid" style="background-color:#FFFFFF;">


<?php

if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
	
// User must be logged to make see his history
if (isset($_SESSION['userId']))
{
	$localUserId = $_SESSION['userId'];
	$userChecked = true;
} else
   $userChecked = false;

// Update Dates
$today   = date( "Y-m-d") ;
$todayy   = date( "Y") ;
$todaym   = date( "m") ;
$agom = date( "m", strtotime ("-1 month", strtotime($today ) )) ;
$agoy = date( "Y", strtotime ("-5 year", strtotime($today ) )) ;

// Search for itens
//if(!isset($submitted)
//{
?>
<center> 
  <br>
  <form name="historySearch" method="POST" action="historyResults.php">
  <table border="0" cellpadding="1" cellspacing="1" class="table" style="width:80%">
  	
    
      <tbody>
  
      <tr>
      		<td align="right" style="width:180px"><?=$strReqNumber?></td>
      		<td><input type="text" name="reqId" tabindex="1" style="width:170px; height:28px; border-radius:4px;font-size:14px"></td>
      </tr>
      
      
      <tr>
      		<td align="right" valign="top"><?=$strReq?></td>
      		<td  valign="bottom">
            
            	<select name="option" style="width:170px; height:28px; border-radius:4px;font-size:14px">
                
                	<option value="all" selected><?=$strReqAll?></option>
                	<option value="open"><?=$strReqOpen?></option>
                	<option value="closed"><?=$strReqClose?></option>
                
                </select>
            	
                <!--
            	<p><input type="radio" name="option" value="open" tabindex="2"><?=$strReqOpen?></p>
                <p><input type="radio" name="option" value="closed"  tabindex="3"><?=$strReqClose?></p>
                <p><input type="radio" name="option" value="all"  tabindex="4" checked><?=$strReqAll?></p>
                -->
                
                
          </td>
      </tr>
      
      <tr>
            <td width="50%" align="right" nowrap><?=$strInitial?></td>
            <td nowrap valign="bottom">
            <input type="text" name="IDATEM" value="<?=$agom; ?>" tabindex="5" style="width:60px; height:28px; border-radius:4px;font-size:14px">&nbsp;&nbsp;
            <input type="text" name="IDATEY" value="<?=$agoy; ?>" tabindex="6" style="width:100px; height:28px; border-radius:4px;font-size:14px">
            </td>
        </tr>
        <tr>
            <td align="right" nowrap><?=$strFinal?></td>
            <td nowrap valign="bottom">
            <input type="text" name="FDATEM" value="<?=$todaym; ?>" tabindex="7" style="width:60px; height:28px; border-radius:4px;font-size:14px">&nbsp;&nbsp;
            <input type="text" name="FDATEY" value="<?=$todayy; ?>" tabindex="8" style="width:100px; height:28px; border-radius:4px;font-size:14px">
            </td>
        </tr>
        
<?php
if($userChecked)
{
?>
   <tr>
      <td align="center" colspan="2">
      		<br>
      		<center>
            
          <input type="submit" value="<?=$strBeginSearch?>" name="action" class="btn btn-info" style="width:170px; border-radius:4px">&nbsp;&nbsp;
	      <input type="reset" name="reset" value="<?=$strReset?>" class="btn" style="width:170px; border-radius:4px">
	  
       		</center>
	</td>
   </tr>
   
   
   
   <input type=hidden name=submitted value=1>
   <input type=hidden name=userid value=<?=$localUserId?>>
   <input type=hidden name=scene value=<?=$scene?>>
<? 
}
?>
   </tbody>
   </table>
   
   
   </form>
<?php
   if (!$userChecked)
	  echo $strWarning_1;
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

