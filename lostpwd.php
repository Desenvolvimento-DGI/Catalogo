<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

require_once("class_ocr_captcha.inc.php");
include("session_mysql.php");
session_start();


$erroCaptcha="";
$erroEMailInvalido="";
$erroUsuarioInvalido="";

    
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
} 

require ("lostpwd_".$_SESSION['userLang'].".php");

if (isset($HTTP_POST_VARS['public_key']))
	$public=$HTTP_POST_VARS['public_key'];

if (isset($HTTP_POST_VARS['private_key']))
	$private=$HTTP_POST_VARS['private_key'];

$p=new ocr_captcha(); 

$erroCaptcha="";
if (isset($HTTP_POST_VARS['private_key']))
{ 
	if ($p->check_captcha($public,$private))
	  $captcha = true;
	else
	{
		$captcha = false;
		$erroCaptcha="<font color=\"#CC0000\"><b>Caracteres da chave de segurança não conferem</b></font>";
	}
	
}

import_request_variables("gpc");
include_once("globals.inc.php"); 
include_once("globals.php");
$dbusercat = $GLOBALS["dbusercat"];


// Verificação dos dados enviados pelo usuário
//
$userChecked = false;
$emailChecked = false;


if ($action == $strExecute)
{
	if ($email !== "") // email is set
	{
		// Check if email is a valid one
		include ("validateEmail.php");
		$result = ValidateMail($email);
		if ($result[0] == true) // If it is valid, check if user is already registered
		{
			$sql = "SELECT * FROM User WHERE email='$email'";
			$dbusercat->query($sql) or $dbusercat->error ($sql);
			$users = $dbusercat->numRows();
			if ($users > 0) // user is registered
			{
				$userChecked = true;
				$row = $dbusercat->fetchRow();
				$fullname = $row['fullname'];
				$userIdent =  $row['userId'];
			}
			$emailChecked = true;
		}
	}
	if (!$emailChecked)
		$erroEMailInvalido="<font color=\"#CC0000\"><b>Endereço de e-mail inválido</b></font><br>";
		
	if (!$userChecked)
		$erroUsuarioInvalido="<font color=\"#CC0000\"><b>Usuário não cadastrado no Catálogo</b></font><br>";
}
?>

<!DOCTYPE html>

<!-- Definição da linguagem -->
<!--html lang="en"-->

<head>
    <meta charset="utf-8">
    
    <title>Divisão de Geração de Imagem :: Acesso ao Catálogo</title>
    
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
    
    <style>
		html, body 
		{
			margin: 0px;
			padding: 0px
		}
    </style>
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="/catalogo/js/html5shiv.js"></script>
        <![endif]-->
    <!-- Fav and touch icons -->   
</head>



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


		<form name="novasenha" method="POST" action="lostpwd.php" onSubmit="return verificaCampos()">
        <table border="0" cellpadding="2" cellspacing="2" width="500">   
             
        <tr>
            <td witdh="30">&nbsp;</td>
            <td colspan="3" align="left" valign="middle">
                Informe seu e-mail<br>
                <input type="text" id="email" name="email" value="<?=$email?>" style="width:280px;border-radius:4px">
            </td>  
                      
            <td witdh="70">&nbsp;</td>
            <td align="right" valign="middle">
            	<input type="reset" id="resetar" name="resetar" class="btn" value="Reiniciar campos" style="width:130px;border-radius:4px">
            </td>
            
            <td>&nbsp;</td>
        </tr>
        
        
        <tr>
            <td witdh="30">&nbsp;</td>
            <td witdh="160" align="left" valign="top">
            	Código de segurança<br>
            	<?echo $p->display_captcha(true);?>
            </td>
            
            <td witdh="30">&nbsp;</td>
            
            <td witdh="120" align="left" valign="top">
                Digite o código<br>
            	<input type="text" id="private_key" name="private_key" value='<?=$private_key?>' maxlength="4" size="6" style="width:100px;border-radius:4px">
            </td>
            
            <td witdh="70">&nbsp;</td>
            <td align="right" valign="middle">
            	<input type="submit" class="btn btn-info" value="<?=$strExecute?>" maxsize="4" name="action" style="width:130px;border-radius:4px">
            </td>                        
            
            <td>&nbsp;</td>
        </tr>
                        
        
        <tr>
            <td colspan="7" width="500"><hr></td>
        </tr>   


        <tr>
            <td witdh="30">&nbsp;</td>
            <td align="center" colspan="6">
<?php

	if (!$emailChecked)
		echo $erroEMailInvalido;
		
	if ( (!$userChecked ) && ($emailChecked))
		echo $erroUsuarioInvalido;          
		
	if (!empty($erroCaptcha))
		echo $erroCaptcha;     
		
		
if ($action == $strExecute && $userChecked && $captcha)
{
    $newpass = substr(md5(time()),0,6);
    require ("lostpwd_".$_SESSION['userLang'].".php");
    $sql = "UPDATE User SET
              password = OLD_PASSWORD('$newpass')
              WHERE email = '$email'";

	$dbusercat->query($sql) or $dbusercat->error ($sql);


	// Email the file links to the user.
	
	/*
	if ($GLOBALS["stationDebug"])
		echo "message= $strPasswdrecover <br>\n";
	$status = true;
	*/
	
	$status = mail($email,$strYournewpaswd,
	$strPasswdrecover, "From:<$GLOBALS[contactmail]>");

	if ($status)
	{
		$_SESSION['userTry'] = 0;
		echo "<b> $strSuccess </b>";
	}
}
/*
else if (!isset($_SESSION['userId']))
	echo "<font size=\"1\"> $strInformation </font>";	
*/	
		
		
		     
?>            
            </td>
        </tr>   
        
        
             
        </tbody>
        </table>
        </form>        
      
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



<!-- Inicio  da seção de códigos Javascript  -->
<script type="text/javascript">

/**
Nome: alteraDimensoes
Altera as dimensoões dos frames e das divs (Camadas) conforme a janela do browser é redimensionada, adequando
corretamente à área existente
*/
function verificaCampos()
{
	var campoEmail  = document.getElementById("email");
	var campoCodigo = document.getElementById("private_key");

	
	if ( campoEmail.value == "" )
	{
		campoEmail.focus();
		return false;
	}

	if ( campoCodigo.value == "" )
	{
		campoCodigo.focus();
		return false;
	}

		
	return true;
}


</script>



</body>
</html>