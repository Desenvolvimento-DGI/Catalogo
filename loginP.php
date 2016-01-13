<?php
include("session_mysql.php");
session_start();
//------------------------------------------------------------------------------
//  Denise
//
// >>>v1
//------------------------------------------------------------------------------
include ("globals.php");
include_once("operator.class.php");

import_request_variables("gpc");

// Language
// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("login_".$_SESSION['userLang'].".php");

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

// Echo
// - - - if ($GLOBALS["stationDebug"])
// - - - {
// - - -    echo "Session = $PHPSESSID OperatorIP = " . $_SESSION['operIP'] . " Operator = " . $_SESSION['operatorId'] . " Language = ".$_SESSION['userLang']. "<br>\n";
// - - -    echo "Session = $PHPSESSID UserIP = " . $_SESSION['userIP'] . " User = " . $_SESSION['userId'] . " Language = ".$_SESSION['userLang']. "<br>\n";
// - - - }

// Verify se userLogged
$userLogged = isset($_SESSION['userId']) ? true : false;
if ($userLogged =="1"){
//echo"<BR>";
}
//echo"oi $userLogged";
$strP = $strPass;
if(isset($_SESSION['operatorId']))
{
   $operatorLogged = true;
   $operator = $_SESSION['operatorId'];
   $strP = $strOperPass;
}


// Flags
$uflag = false;
$pflag = false;
$aflag = false;
$oflag = false;

if($submitted)
{
	if ( (trim($name)=="") )
	{
		$uflag = true;
	}
	if ( (trim($pwd)==""))
	{
		$pflag = true; 
	}
	if ($uflag==false && $pflag==false)
	{
	   // Mounting sql statement
		$sql = "SELECT * FROM User WHERE userid='$name'";
		if(!$operatorLogged) $sql .= " AND password = OLD_PASSWORD('$pwd')";
		$dbusercat->query($sql);
		$count = $dbusercat->numRows();
		if ($count == 0) // No such user and password
		{
			$aflag = true;
			$_SESSION['userTry'] += 1;
			$logado = "nao";
		}else{
		$logado="sim";
		}
      if($operatorLogged)
      { 
            $objOp = new Operator($dbcat);
            if(!$objOp->getOperator($operator, $pwd))
            {
               $aflag = true;
			      $_SESSION['userTry'] += 1;
			      }
	  	}
		if(!$aflag)
      {
			$oflag = true;
			$row_array = $dbusercat->fetchRow();
			$dbusercat->freeResult();
			if (isset($_SESSION['userLang'])) $lang = $_SESSION['userLang'];
			if ($userLogged)
			{
				$_SESSION = array();
				session_destroy();
				session_regenerate_id();

				if (version_compare(PHP_VERSION, '4.3.3') == -1)
					setcookie(session_name(), session_id());
				session_set_save_handler(
					"sess_open",
					"sess_close",
					"sess_read",
					"sess_write",
					"sess_destroy",
					"sess_gc");
				session_start();
			}
			if (isset($lang))
				$_SESSION['userLang'] = $lang;
			else
			   $_SESSION['userLang'] = 'PT';
			$_SESSION['userIP'] = $_SERVER["REMOTE_ADDR"];
			$_SESSION['userId'] = $row_array["userId"];
			$_SESSION['userTry'] = 0;
			$_SESSION['operatorId'] = $operator;
			$_SESSION['userType'] = $row_array["userType"];
			     
     }

			
		}
}
if ($name!=""){
	//echo "oi";
	if ( (trim($pwd)==""))
		{
			$pflag = true; 
		}else{
		if ($logado =="sim")
			{
					echo "<meta HTTP-EQUIV='Refresh' CONTENT='0; URL=../siteDgi/logado.php?login=$name' >";
			}
	}
}
?>

<?php
//include_once 'css.php';
?>

<!--
body {
	background-color: #000000;
}
.style5 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->

<style type="text/css">

#dek {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:300;}
div#qTip {
 padding: 3px;
 border: 1px solid #666;
 border-right-width: 2px;
 border-bottom-width: 2px;
 display: none;
 background: #999;
 color: #FFF;
 font: bold 9px Verdana, Arial, Helvetica, sans-serif;
 text-align: left;
 position: absolute;
 z-index: 1000;
}
<!--
body,td,th {
	color: #FFFFFF;
}

.style24 {color: #FFFFFF}
.style25 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; size:11px; }
.style26 {	color: #fff;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
.menuDestaque {color:#fff; 
}
body { padding-left:1px;
	background-color: #2E3E3E;margin-top:0px; padding-top:0px;
}
.style24 {color: #FFFFFF}
.style25 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; padding-bottom:0px; }
.style26 {
	color: #fff;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}


a:link {
	color: #CCFF33;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #CCFF33;
}
a:hover {
	text-decoration: none;
	color: #FFFFFF;
}
a:active {
	text-decoration: none;
	color: #EBE9ED;
}
.style39 {color: #000000}
.style40 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
.style42 {font-weight: bold; color: #FF6633; font-family: Verdana, Arial, Helvetica, sans-serif;}
.style43 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFF00;}
.style44 {font-size: 11px}
.style46 {font-size: 14px}
.style49 {font-weight: bold; color: #00FF00; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; }

 a.dcontexto{
  position:relative; 
  font:12px Verdana, Arial, Helvetica, sans-serif; 
  padding:0;
  color:#FFFFFF;
    font-weight: bold;
  text-decoration:none;


  
  z-index:24;
  }
  a.dcontexto:hover{
    font-weight: bold;
  background:transparent;
  z-index:25; 
  }
  a.dcontexto span{display: none;  font-weight: bold;}
  a.dcontexto:hover span{ 
    font-weight: bold;
  display:block;
  position:absolute;
  width:230px; 
  top:3em;
  text-align:justify;
  left:0;
  font: 12px arial, verdana, helvetica, sans-serif; 
  padding:5px 10px;
  border:1px solid #999;
  background:#CCCCCC; 
  color:#000;
  }
  /*corecao\*/
  *html ul li { float:left;}
  * html ul li a {height:1%}
.style50 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body style="margin-top:0px; margin-left:1px;">
<DIV class="style44" ID="dek"></DIV>
<div class="style44" style="margin-top:0px; margin-left:1px; padding-top:0px;">
<?php
// - - - if ($GLOBALS["stationDebug"])
// - - - 	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." $userLogged<br>\n";
if ($oflag)
{
  ?> <script>parent.frames["banner"].location.href ="banner.php"</script> <? // to stamp user name upon the banner
	die ("<h3> $strWelcome $name !  </h3>");
	
};

if ($aflag){
	echo "<h3> $strAcess !</h3><br>";
	$pflag = true; 
	$logado = "nao"; }
if ($uflag)
	echo "<h3> $strUflag </h3><br>";
if ($pflag){
	echo "<h3> $strPflag </h3><br>";
	$pflag = true; }
	
	if ($userLogged!="1"){
?>
<form style="margin-top:0px;" method="post" action="<? echo $PHP_SELF?>">
  <table style="margin-top:0px; padding-top:0px" width="565" border="0">
<tr>
  <td width="18" class="style42">&nbsp;</td>
  <td  width="48"><span class="style48"><a href="#" class="dcontexto" ><span> Logar para acessar seu historico de pedidos e pedir imagens </span>Usu&aacute;rio</span></a></td>
  <td width="94"><input style="background-image:url(../siteDgi/imagens/mainnav2.png); border-style:solid; color:#FFFFFF" name="name" value="<?=$name?>" size="9"></td>
  <td width="40"><span class="style50">Senha</span></span></td>
  <td width="87"><input style="background-image:url(../siteDgi/imagens/mainnav2.png); border-style:solid; color:#FFFFFF" name="pwd" type="password" size="9"></td>
  <td width="53"><a href="#" class="dcontexto"><span>Clicando no "OK" você terá acesso ao seu histórico de pedidos, bem como solicitar novas imagens de satélites.</span><input style="" name="submit" type="submit" value="ok"></a>
      <input type=hidden name=submitted value=1></td>
  <td width="10">&nbsp;</td>
  <td width="181"><div align="center"><p ><a  href="siteDgi_iframe_register.php"  target="_self" class="dcontexto style49">Novo Cadastro<span>Cadastrar no Catalogo</span></a></p>
  </div></td>
  </tr>
</table>
</form>
<?php
}else{
?>

<div style="margin-top:0px; padding-top:0px">
  <table style="margin-top:0px; padding-top:0px" width="537" border="0">
  <tr>
    <td width="443" class="style43" > <span class="style34 style46"><em><strong><a href="../siteDgi/logado.php?login=<?=$_SESSION['userId']?>" class="style32">Informações e Histórico do usuário</a></strong></em></span>      <strong><a href="../siteDgi/logado.php?login=<?=$_SESSION['userId']?>">
      <?=$_SESSION['userId']?>
      </a></strong><span class="style34">            <img src="../siteDgi/imagens/logado.jpg" width="18" height="22"></span></td>
    <td width="84"><span class="style34"><strong><a href="site_logout.php">logoff</a></strong> </span><img style="padding-left:5px;" src="../siteDgi/imagens/logoff.jpg" width="18" height="22" border="0"></td>
  </tr>
</table>
</div>

<?
echo"<BR>";
}
//if ($_SESSION['userTry'] > 0)
if(!$operatorLogged)
{
  // echo "
   //<a href=\"lostpwd.php\"> $strForgot ?</a>";
}
?>
</div>
<span class="style44">
<?php
$opcao= rand(1, 5);
switch ($opcao) {
    case 1:
    //    echo "i igual a 0";
		$imagem = "../siteDgi/imgCatalogo/1.jpg";
        break;
    case 2:
     //   echo "i igual a 1";
		$imagem = "../siteDgi/imgCatalogo/2.jpg";
        break;
    case 3:
       // echo "i igual a 2";
		$imagem = "../siteDgi/imgCatalogo/3.jpg";
        break;
	case 4:
       // echo "i igual a 3";
		$imagem = "../siteDgi/imgCatalogo/4.jpg";		
        break;	
	case 5:
       // echo "i igual a 4";
		$imagem = "../siteDgi/imgCatalogo/5.jpg";		
        break;	
		
}
//echo $imagem;	




?>
</span>
<table style="padding-left:19px; padding-top:0px;" width="569" border="0">
  <tr>
    <td width="391"><div align="center" class="style40">
      <div align="left">Clique na imagem para entrar no Cat&aacute;logo </div>
    </div></td>
    <td width="14" style="padding-left:5px;"> </td>
    <td width="150" rowspan="2" style="background-image:url(../siteDgi/imagens/imgdiaFundo.jpg); background-repeat:no-repeat; width:150px;"><div align="center" class="style24"></div>
      <div align="center" class="style25">
        <div align="center" class="style24">
          
          
        <div style="padding-top:15px; padding-left:22px;" align="center">
		
          <script TYPE="text/javascript">
<!--
//Pop up information box II
//(Mike McGrath (mike_mcgrath@lineone.net,

//http://website.lineone.net/~mike_mcgrath))

//Permission granted to Dynamicdrive.com to include script in archive

//For this and 100's more DHTML scripts, visit http://dynamicdrive.com


Xoffset=-400; // modify these values to ...

Yoffset= 0; // change the popup position.
var old,skn,iex=(document.all),yyy=-1000;
var ns4=document. layers
var ns6=document.getElementById&&!document.all
var ie4=document.all
if (ns4)
skn=document.dek
else if (ns6)
skn=document.getElementById("dek").style
else if (ie4)
skn=document.all.dek.style
if(ns4)document.captureEvents(Event.MOUSEMOVE);
else{
skn.visibility="visible"
skn.display="none"
}
document.onmousemove=get_mouse;
function popup(msg,bak){
var content="<TABLE WIDTH=150 CELLPADDING=2 CELLSPACING=0 "+
"BGCOLOR="+bak+"><TD ALIGN=center><FONT COLOR=black SIZE=2>"+msg+"</FONT></TD></TABLE>";
yyy=Yoffset;
if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"}
if(ns6){document.getElementById("dek").innerHTML=content;skn.display=''}
if(ie4){document.all("dek").innerHTML=content;skn.display=''}
}
function get_mouse(e){
var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft;
skn.left=(x+10)+Xoffset;
var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop;
skn.top=(0+0)+yyy;
}
function kill(){
yyy=-1000;
if(ns4){skn.visibility="hidden";}
else if (ns6||ie4)
skn.display="none"
}
//-->
        </SCRIPT>
          <script language="JavaScript" src="qTip.js" type="text/JavaScript"></script>
          
          
          <? 
	  
	  //-----------------------aqui começa o codigo da img do dia
	  
	  $hoje = date("Ymd");
$dir = "/htdocs/imgdia/imagens/pequenas/";
$dh = opendir($dir);
while (false !== ($filename = readdir($dh))) {
  if ($filename != "." && $filename != "..") {
    $data = substr($filename, 0, 8);
    if ($data <= $hoje) {
      $files[] = $filename;
    } 
  } 
} 
$size = count($files);
if ($size != 0) {
  rsort($files);
  $filename = $files[0];
 ?>
          <a href="http://www.dgi.inpe.br/siteDgi/imgdia/" target="_blank" onMouseOver="popup('<img src=../imgdia/imagens/mediana/<?=$filename?>>')"; onMouseOut="kill()"><img style="border:none; padding-left:4px; padding-top:0px;" src="../imgdia/imagens/pequenas/<?=$filename?>" width="98" height="120"></a><span class="style24">
          .<?
} 

//-------------------------------------------fim imgday


?>
          </span></div>
        </div>
    </div>  <div></div>    
    <span class="style24 style39"><a href="http://www.dgi.inpe.br/pesquisa2007/"></a></span></td>
  </tr>
  <tr>
    <td><span class="style26"><a href="http://www.dgi.inpe.br/CDSR" target="_blank"><img src="<?=$imagem?>"width="377" height="200" border="0" align="top"  style="padding-top:4px; padding-bottom:0px;" /></a></span></td>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>
