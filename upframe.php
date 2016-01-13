<?php
/*
  Source code for upper banner of Catalog home page.
  Contains menu option for Login, Logout, Register, Cart and Help functions 
  and language choice
  
*/
include("session_mysql.php");
session_start(); 
include("globals.php");
include("findCountry.php");

log_msg( __FILE__.":".__LINE__.":"."Entrei" );

$southAmerica = array ("Argentina","Bolivia","Brazil","Chile","Colombia","Ecuador","French Guiana","Guyana",
                       "Paraguay","Peru","Suriname","Uruguay","Venezuela");


#
#  Segmet for freeing Catalog.
#  Comments applied to statements bellow (and the next two ones) are to enphasize that Catalog free images can be requested all over the world (Open_area = TRUE always !)
#

/*
$country = findCountry();

$Open_area = false;		
if (in_array($country,$southAmerica)) $Open_area = true;
$_SESSION['openArea'] = $Open_area;
*/

$Open_area = true;
$_SESSION['openArea'] = $Open_area;

#
# End Segment for freeing Catalogo
#

$inpe = false;
$_SESSION['userIP'] = $_SERVER["REMOTE_ADDR"];
if (substr($_SESSION['userIP'],0,8) == "150.163.") $inpe = true;

// Local IP's must mean we are using from Brazil
if( strcmp( substr($_SESSION['userIP'],0,8), "192.168." ) == 0 ) {
  $country = "Brazil";
  $Open_area = true;
  $_SESSION['openArea'] = $Open_area;
}

if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
	{
		if ($country == "Brazil")
			$_SESSION['userLang']='PT';
		else
			$_SESSION['userLang']='EN';
	}
} 


require ("upframe_".$_SESSION['userLang'].".php");
// Globals - image
$imgDir = "../Suporte/images/"; 
?>
<html>
<head>
<title></title>
<base target="mosaico">
<script type="text/javascript">
function changeLang(entry)
{
	window.location.href = 'upframe.php?SESSION_LANGUAGE='+entry;
	parent.frames["panel"].location.href = 'panel.php?SESSION_LANGUAGE='+entry;
	parent.frames["mosaico"].location.href = 'first_'+entry+'.php';
}
</script>
<?php require("css.php");
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<form>
<td align="left" valign="top">
	<select name="SESSION_LANGUAGE" onChange="changeLang(SESSION_LANGUAGE.options[selectedIndex].value);">
<? if ($_SESSION['userLang']=='PT') { ?>
	<option selected value="PT">Portugues</option>
	<option value="EN">English</option>
	<option value="FR">Fran&ccedil;ais</option>
	<option value="ES">Espa&ntilde;ol</option> 
<? } else if ($_SESSION['userLang']=='EN'){ ?>
	<option value="PT">Portugues</option>
	<option selected value="EN">English</option>
	<option value="FR">Fran&ccedil;ais</option>
	<option value="ES">Espa&ntilde;ol</option> 
<? } else if ($_SESSION['userLang']=='FR'){ ?>
	<option value="PT">Portugues</option>
	<option selected value="FR">Fran&ccedil;ais</option>
	<option value="EN">English</option>
	<option value="ES">Espa&ntilde;ol</option> 
<? } else { ?>
	<option value="PT">Portugues</option>
	<option value="EN">English</option>
	<option value="FR">Fran&ccedil;ais</option>
 <option selected value="ES">Espa&ntilde;ol</option> 
<? } ?>	
	</select>
</form>
</td>
<td align="center"><img src="../Suporte/images/logonome.gif"></td>
<!-- <td align="center" valign="top" width="150" height="40"><a href="http://www.finep.gov.br/"><img src="../Suporte/images/logofinep.gif" width="125" height="40" border="0"></a></td> -->
<!-- <td nowrap align="center" valign="top"><h2><?=$strCatalog?></h2></td> -->
<!-- <td align=center valign=top><h3>&nbsp;<?=$_SESSION['userId']?></h3></td> --> <!-- stamp user name upon the banner -->
<td height="40" align=center width=290>
<table valign=top height=40 cellpadding=0 cellspacing=0>
<tr><td align="center" cellpadding="0" valign=top cellspacing="0"> <font size="2"><h1><?=$strCatalog?></h1></font></td></tr>
<tr><td align=center cellpadding="0" valign=top cellspacing="0"><h3><?if(isset($_SESSION['userId'])) echo $_SESSION['userId'] ;?></h3></td></tr> <!-- stamp user name upon the banner -->
</table>
</td>
<!-- <td align="center" height="40" valign="top" width="572"> -->
<td align="left" height="40" valign="top">
<!-- <table border="0" cellspacing="5" cellpadding="0" width="450" height="60%"> -->
<table border="0" cellspacing="5" cellpadding="5" height="50%">
<tr>
<?
//if ($country == "Brazil" || $inpe)
if ($Open_area) // || $inpe)
{
   if(!isset($_SESSION['operatorId']))
   {
?>
<td align="right" nowrap class="azul" valign="middle">
<a href="register.php" target="mosaico"><img src="<?=$imgDir . "home.gif"?>" border="0" align="middle"><span class="azul"> 
	 <font size="3"><b><?=$strRegister?></b></font></span></a>
</td>
<?
   }
?>
<td nowrap align="right" valign="middle" class="azul">
<a href="login.php" target="mosaico"><img src="<?=$imgDir . "seguranca.gif"?>" border="0" align="middle"><span class="azul">
	   <font size="3"><b><?=$strLogin?></b></font></span></a>
</td>
<td nowrap align="right" valign="middle" class="azul">
<a href="logout.php" target="mosaico"><img src="<?=$imgDir . "seguranca.gif"?>" border="0" align="middle"><span class="azul">
	   <font size="3"><b><?=$strLogout?></b></font></span></a>
</td>
<td nowrap align="right" valign="middle" class="azul">
<a href="cart.php" target="mosaico"><img src="<?=$imgDir . "carrinho.gif"?>" border="0" align="middle"><span class="azul">
	   <font size="3"><b><?=$strCart?></b></font></span></a>
</td>
<td nowrap align="right" valign="middle" class="azul">
<a href="history.php" target="mosaico"><img src="<?=$imgDir . "loja_ico2.gif"?>" border="0" align="middle"><span class="azul">
	   <font size="3"><b><?=$strHistory?></b></font></span></a>
</td>
<?
}
?>
<td nowrap align="right" valign="middle" class="azul">
<a href="help.php" target="mosaico"><img src="<?=$imgDir . "ajuda.gif"?>" border="0" height="13" align="middle"><span class="azul">
    <font size="3"><b><?=$strHelp?></b></font></span></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>