<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

// Inclusão de blibliotecas para tratamento do controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais

include("session_mysql.php");
include ("globals.php");
session_start();


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


// Verify se userLogged
$userLogged = isset($_SESSION['userId']) ? true : false;
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

$name=$_POST['name'];
$pwd=$_POST['pwd'];
$submitted=$_POST['submitted'];

$comandoRecarregarCatalogo='';
$comandoLoginValido='';
$mensagemErro='';

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
		

        if ($uflag == false && $pflag == false)
        {
            // Mounting sql statement
         
			$sql = "SELECT * FROM User WHERE userId='$name' ";
			if(!$operatorLogged) $sql .= " AND password = OLD_PASSWORD('$pwd')";
			
			$dbusercat->query($sql);
			$count = $dbusercat->numRows();
			
			
			if ($count == 0) // No such user and password
			{       
					$aflag = true;
					$_SESSION['userTry'] += 1;
					
					$mensagemErro="<font color=red><b>Usuário e/ou senha incorretos.</b></font>";
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
							  
				  }
				  
				  
				  if (isset($lang))
					  $_SESSION['userLang'] = $lang;
				  else
					 $_SESSION['userLang'] = 'PT';
					 
				  $_SESSION['userIP'] = $_SERVER["REMOTE_ADDR"];
				  $_SESSION['userId'] = strtolower($row_array["userId"]);
				  $_SESSION['userTry'] = 0;
				  $_SESSION['operatorId'] = $operator;
				  $_SESSION['userType'] = $row_array["userType"];
				  
				  //$comandoRecarregarCatalogo="Javascript:top.location='/catalogo/'";
				  $comandoLoginValido="Javascript:chamaLoginRealizado('" . $_SESSION['userId'] . "')";
				  	
					  $sql = "DELETE FROM sessions WHERE sesskey = '$PHPSESSID'";
					  $dbcat->query($sql) or $dbcat->error ($sql);
				   
				   	  
					  session_set_save_handler(
							  "sess_open",
							  "sess_close",
							  "sess_read",
							  "sess_write",
							  "sess_destroy",
							  "sess_gc");
					  session_start();
							 				  
								   
			}
     }
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
    
    
    
	<script type="text/javascript">
    
	function chamaLoginRealizado( parametroUsuario )
	{
		top.loginRealizado( parametroUsuario );	
	}
	
	
	
    function chamaEsqueceuSenhaFechaLogin()
    {
        top.esqueceuSenhaFechaLogin();
    }
    
    
    </script>    
    
    
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
<body class="wide" style="background-color:transparent; border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0 0px; height:100%; padding-bottom:0px;" onLoad="<?php echo $comandoLoginValido ?>">




<!-- 
Inicio da área principal 
class body
-->

<div class="body" style="background-color:#FFFFFF; border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <div class="row-fluid" style="background-color:#FFFFFF;">
    		<br>
            <table border="0" cellpadding="2" cellspacing="2">
               <tr>				                    
                  <td width="170" align="center" valign="middle">
                      <img src="/catalogo/img/lock04.png">                    
                  </td>

                  <td width="330" valign="middle">
                          
                      <form method="POST" name="acessar" action="login.php">
                          <input type="text" id="name" name="name" placeholder="Usuário" style="width:240px; border-radius:4px" value="<?php echo $name ?>">
                          <br>
                          <input type="password" id="pwd" name="pwd" placeholder="Senha"  style="width:240px; border-radius:4px">
                          <br>
                          <!--input type="reset" id="resetar" name="resetar" class="btn" value="Reiniciar valores" style="width=140px; border-radius:4px"--><input type="submit" id="enviar" name="enviar" class="btn btn-info" value="Realizar acesso"  style="width:255px; border-radius:4px">
                          <br>
                          <!--input type="button" id="esqueci" name="esqueci" class="btn btn-success" value="Clique aqui se esqueceu a senha" style="width=300px; border-radius:4px" onClick="javascript:chamaEsqueceuSenhaFechaLogin()"-->
                          <font size="2"> Esqueceu a senha? <a href="" onClick="javascript:chamaEsqueceuSenhaFechaLogin()">Clique aqui...</a></font>
                          
                          <input type="hidden" name="submitted" value=1>
                      </form>                      
                  </td>
               </tr>
<?php
	if ( ! empty($mensagemErro) )
	{
?>
               <tr>				                    
                  <td width="170" align="right" valign="middle">&nbsp;                      
                  </td>

                  <td width="330" align="left" valign="middle">
                      <p><?php echo "$mensagemErro" ?></p>                      
                  </td>
               </tr>
<?php
	} 
?>
             </table>                                
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


<script type="text/javascript">

function chamaEsqueceuSenhaFechaLogin()
{
	top.esqueceuSenhaFechaLogin();
}


</script>

</body>
</html>

