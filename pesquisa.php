<?php
// Informa ao servidor que é necessário compactar o código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 


include("session_mysql.php");
session_start();

// Necessário para atualizar as imagens
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0'); // Proxies


include ("globals.php");
//include_once("operator.class.php");

import_request_variables("gpc");


$permissaoRapieye=false;

$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];


// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];









// Verificar se o usuário possui permissão para visualizar imagens do RapidEye


$permissaoRapieye=false;

$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];


$usuarioLogado="";

if(isset($_SESSION['userId'])) 
{
	$usuarioLogado=$_SESSION['userId'];
	
	// Verificar se o usuário, caso esteja logado, possua SIAPE
	$stringSQLUser="SELECT SIAPE FROM User WHERE userId = '" . $usuarioLogado . "'";
	
	$dbusercat->query($stringSQLUser);
	$totalUser = $dbusercat->numRows();	
	$registroUser = $dbusercat->fetchRow();
	
	if ( $totalUser == 1 )
	{
			$siape=$registroUser['SIAPE'];
			
			if ( $siape == 0 or is_null($siape) )
			{
				$permissaoRapieye=false;	
			}
			else
			{
				$permissaoRapieye=true;	
			}
	}
	else
	{
		$permissaoRapieye=false;	
	}	

	$dbusercat->freeResult();	
}
else
{
	$permissaoRapieye=false;		
}


// Fim verificar se o usuário possui permissão para visualizar imagens do RapidEye


if ( $permissaoRapieye )
{
	$opcaoTodosSatelites="true";
}
else
{
	$opcaoTodosSatelites="false";	
}




/*
$sqlLimiteDatas='SELECT Satellite, max(Date) as MaisRecente, min(date) as MaisAntigo FROM `Scene` WHERE Deleted=99 Group By Satellite';
$dbcat->query($sqlLimiteDatas) or $dbcat->error ($sqlLimiteDatas);


// Limite de datas para o satálite AQUA
$dataInicialAQUA="";
$dataFinalAQUA="";

// Limite de datas para o satálite TERRA
$dataInicialTERRA="";
$dataFinalTERRA="";


// Limite de datas para o satálite S-NPP
$dataInicialSNPP="";
$dataFinalSNPP="";

// Limite de datas para o satálite UK-DMC2
$dataInicialUKDMC2="";
$dataFinalUKDMC2="";






// Inclusão de blibliotecas para tratamento de idioma, controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais


while ( $registroAtual =  $dbcat->fetchRow() )
{
	
	//echo "";
	
	$sateliteAtual=strtoupper($registroAtual['Satellite']);
	
	
	$dataTempInferior=$registroAtual['MaisAntigo'];
	
	$anoData=substr($dataTempInferior, 0, 4);
	$mesData=substr($dataTempInferior, 5, 2);
	$diaData=substr($dataTempInferior, 8, 2);

	$dataLimiteInferior=$diaData . '/' . $mesData . '/' . $anoData;
	
	
	$dataTempSuperior=$registroAtual['MaisRecente'];
	$anoData=substr($dataTempSuperior, 0, 4);
	$mesData=substr($dataTempSuperior, 5, 2);
	$diaData=substr($dataTempSuperior, 8, 2);
	
	$dataLimiteSuperior=$diaData . '/' . $mesData . '/' . $anoData;
	

		

		
	// Formatar a hora da geração da imagem de acordo com o SceneId
	switch ( $sateliteAtual )
	{
		// Define os limites superior e inferior de datas para o satélite AQUA
		case 'A1':
		case 'AQUA':		
			$dataInicialAQUA=$dataLimiteInferior;
			$dataFinalAQUA=$dataLimiteSuperior;
			break;
			
		// Define os limites superior e inferior de datas para o satélite TERRA
		case 'T1':
		case 'TERRA':		
			$dataInicialTERRA=$dataLimiteInferior;
			$dataFinalTERRA=$dataLimiteSuperior;
			break;
			
		// Define os limites superior e inferior de datas para o satélite S-NPP
		case 'NPP':
		case 'SNPP':		
		case 'S-NPP':		
			$dataInicialSNPP=$dataLimiteInferior;
			$dataFinalSNPP=$dataLimiteSuperior;
			break;
			
		// Define os limites superior e inferior de datas para o satélite UKDMC2
		case 'UKDMC':
		case 'UKDMC2':		
		case 'UK-DMC2':		
			$dataInicialUKDMC2=$dataLimiteInferior;
			$dataFinalUKDMC2=$dataLimiteSuperior;
			break;					
			
	}
	
	
	
	
}


*/





?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html lang="en">

<head>
    <meta charset="utf-8">
    
    <title>Divisão de Geração de Imagem :: Catálogo de Imagens</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página principal do site da DGI (Divisão de Geração de Imagem)">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
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
    <!--link href="/catalogo/css/bootstrap.3.3.min.css" rel="stylesheet"-->    
	<link id="bsdp-css" href="/catalogo/css/datepicker3.css" rel="stylesheet">   
	<link href="/catalogo/css/bootstrap-slider.css" rel="stylesheet">   

     
    
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
       
	<!--script type="text/javascript">

	// Limite de datas para o satálite AQUA
	dataInicialAQUA="<?php //echo $dataInicialAQUA ?>";
	dataFinalAQUA="<?php //echo $dataFinalAQUA ?>";
	
	// Limite de datas para o satálite TERRA
	dataInicialTERRA="<?php //echo $dataInicialTERRA ?>";
	dataFinalTERRA="<?php //echo $dataFinalTERRA ?>";
	
	
	// Limite de datas para o satálite S-NPP
	dataInicialSNPP="<?php //echo $dataInicialSNPP ?>";
	dataFinalSNPP="<?php //echo $dataFinalSNPP ?>";
	
	// Limite de datas para o satálite UK-DMC2
	dataInicialUKDMC2="<?php //echo $dataInicialUKDMC2 ?>";
	dataFinalUKDMC2="<?php //echo $dataFinalUKDMC2 ?>";


	var possuiPermissaoRapidEye=<?php echo "$opcaoTodosSatelites" ?>;

	</script-->
    
    <script type="text/javascript">
	var possuiPermissaoRapidEye=<?php echo "$opcaoTodosSatelites" ?>;
	</script>    
    
        
    <script src="/catalogo/pesquisa.js"></script>
    
    
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

<div class="body" style="border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <br>
    <form name="general" id="general" onSubmit="return false">
    <div class="row-fluid">
    
        <div class="accordion-group" style="background-color:#F4F4F4">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#parametrosBasicos">
                Parâmetros Básicos </a>
            </div>
            <div id="parametrosBasicos" class="accordion-body collapse" style="height: 0px;background-color:#F9F9F9">
                <div class="accordion-inner" style="background-color:#FDFDFD">
                
                	
                   
                    <br>    
                    <table border="0" cellpadding="0" cellspacing="0" width="400" height="30">
                        <tr>
                            <td align="left" width="160" style="background-color:#EFEFEF;border-radius:3px">                                   
                                <div class="checkbox">
                                  <label><input type="checkbox" id="CQA" name="CQA" value="CA" checked><font size="2">Aprovadas</font></label>
                                </div>
                            </td>
                            
                            <td align="left" width="20">&nbsp;
                            </td>                            
                            
                            <td align="left" width="160" style="background-color:#EFEFEF;border-radius:3px">
                                <div class="checkbox">
                                  <label><input type="checkbox" id="CQR" name="CQR" value="CR"><font size="2">Rejeitadas</font></label>
                                </div>
                            </td>
 
                            <td align="left">&nbsp;
                            </td>                                                                                    
  						</tr>
                        
                    </table>   
                    
					
                    <br>
                         
                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                        <tr>
                            <td align="left" width="160">
                            
                            	                           
                                <label for="SATELITE"><font size="2">Satélite</font></label>
                                <select id="SATELITE" name="SATELITE" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="displaySatellite(this.selectedIndex);">
                                	<option value=""> </option>
                                    <option value="A1">Aqua</option>
                                    <!--option value="CB4">CBERS-4</option-->
                                    <option value="L8">LandSat-8</option> 

                                    
<?php
// Se possui permissão para visualizar as imagens do satélite RapidEye
// Inclui as opções para serem acessadas
if ( $permissaoRapieye ) 
{
?>                                    
                                    <option value="RE">RapidEye (Todos)</option>  
                                    <option value="RE1">RapidEye 1 (Rápido)</option>  
                                    <option value="RE2">RapidEye 2 (Olho)</option>  
                                    <option value="RE3">RapidEye 3 (Terra)</option>  
                                    <option value="RE4">RapidEye 4 (Espaço)</option>  
                                    <option value="RE5">RapidEye 5 (Órbita)</option>                                     
<?php
}
?>                                                                                                               
                                    
                                    <option value="P6">ResourceSat-1</option>                                      
                                    <option value="RES2">ResourceSat-2</option>                                      
                                    <option value="NPP">S-NPP</option>                                      
                                    <option value="T1">Terra</option>  
                                    <option value="UKDMC2">UK-DMC 2</option>                                      
                                    
                             </select>
                                
                              
                                
                            </td>
                            
                            <td align="left" width="20">&nbsp;
                            </td>                            
                            
                            
                            <td align="left" width="160">
                            
                                <label for="SENSOR"><font size="2">Instrumento</font></label>
                                <select id="SENSOR" name="SENSOR" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value=""> </option>
                                	<option value="AWIF">AWIFS</option>
                                	<option value="MODIS">MODIS</option>
                                    <!--option value="MUX">MUX</option-->
                                	<option value="OLI">OLI</option>
                                	<option value="LIS3">LISS3</option>
<?php
// Se possui permissão para visualizar as imagens do satélite RapidEye
// Inclui as opções para serem acessadas
if ( $permissaoRapieye ) 
{
?>                                     
                                	<option value="REIS">REIS</option>
<?php
}
?>

                                	<option value="SLIM">SLIM</option>
                                    <option value="TM">TM</option>
                                    <option value="VIIRS">VIIRS</option>
                                    <option value="WFI">WFI</option>
                                	<!--option value="CCD">CCD</option-->
                                	<!--option value="IRM">IRM</option-->
                                	<!--option value="WFI">WFI</option-->
                                </select>

                            </td>                            
                          
                            <td align="left">&nbsp;
                            </td>                            
                        
                        </tr>   
                    </table>
                       
                       
                        
                                                               
                    <table border="0" cellpadding="0" cellspacing="0" width="400">                        
                        <tr>
                            <td align="left" width="160">
                            
                            	<!--
                                <label for="satelite"><font size="2">Data início</font></label>
                                <input type="date" id="dataini" name="dataini" width="140" style="width:140px;height:22px;padding:0">
                                -->
                                
                   
                               <div class="input-append date" style="margin:0px;margin-bottom:10px; margin-right:20px;text-align:left">
                                   <label for="DATAINI"><font size="2">Data início</font></label>                      
                                   <input type="text" id="DATAINI" name="DATAINI" placeholder="dd/mm/aaaa" style="width:120px;border-radius:3px" onKeyPress="validaCampoData(event,this)"><span class="add-on"><i id="dpdataini" class="icon-th"></i></span>
                               </div>
               
                                
                                
                                
                            </td>
                        
                            <td align="left" width="160">
                            
                            	<!--
                                <label for="sensor"><font size="2">Data final</font></label>
                                <input type="date" id="datafim" name="datafim" width="140" style="width:140px;height:22px;padding:0">
                                -->
                                
                                
                               <div class="input-append date" style="margin:0px;margin-bottom:10px; margin-right:10px;text-align:left">
                                   <label for="DATAFIM"><font size="2">Data fim</font></label>                      
                                   <input type="text" id="DATAFIM" name="DATAFIM" placeholder="dd/mm/aaaa" style="width:120px;border-radius:3px" onKeyPress="validaCampoData(event,this)"><span class="add-on"><i id="dpdatafim"class="icon-th"></i></span>
                               </div>
                                
                                
                            </td>    
                            
                            <td align="left">&nbsp;
                            </td>                            
                                                    
                        
                        </tr>                        
                    </table>
      
      
      
      
					<!-- Região do Brasil (Norte, Sul, Sudeste, Nordeste e Centro-oeste) -->
                    
                    
                    <!--
                    <span id="verorbitaponto" style="display:block"> 
                       <br>
                       
                       <div class="input-append" style="margin:0px;margin-bottom:10px; text-align:left">
                                    <label for="ORBITAINI"><font size="2">Órbita</font></label>
                                    <input type="text" id="ORBITAINI" name="ORBITAINI" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="De">
    
                       </div>
                   
                        
                        <div class="input-append" style="margin:0px;margin-bottom:10px;margin-right:10px;text-align:left">
                                    <label for="ORBITAFIM"><font size="2">&nbsp;</font></label>
                                    <input type="text" id="ORBITAFIM" name="ORBITAFIM" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Até">
                        
                        </div>                                
    
    
                       
                    
                       <div class="input-append" style="margin:0px;margin-bottom:10px; text-align:left">
                                    <label for="PONTOINI"><font size="2">Ponto</font></label>
                                    <input type="text" id="PONTOINI" name="PONTOINI" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="De">
    
                       </div>
                   
                        
                        <div class="input-append" style="margin:0px;margin-bottom:10px;text-align:left">
                                    <label for="PONTOFIM"><font size="2">&nbsp;</font></label>
                                    <input type="text" id="PONTOFIM" name="PONTOFIM" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Até">
                        
                        </div> 
                                                    
    
                        <br>
    
                    
                    </span>  
                    
                    -->                 
                    <!-- Região do Brasil -->       


      
      
      
      
      
      
					<!-- Região do Brasil (Norte, Sul, Sudeste, Nordeste e Centro-oeste) -->
                    <span id="verregiaobrasil" style="display:none"> 
                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                        <tr>
                            <td align="left" width="160">
                            
                            	                           
                                <label for="REGIAOBRASIL"><font size="2">Região</font></label>
                                <select id="REGIAOBRASIL" name="REGIAOBRASIL" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value=""> </option>
                                    <option value="centro-oeste">Centro-oeste</option>
                                    <option value="nordeste">Nordeste</option> 
                                    <option value="norte">Norte</option>                                      
                                    <option value="sudeste">Sudeste</option>  
                                    <option value="sul">Sul</option>  
                                    
                                </select>
                                
                              
                                
                            </td>
                            
                            <td align="left" width="20">&nbsp;
                            </td>                            
                            
                            
                            <td align="left" width="160">
                            
                                <label for="FUSO"><font size="2">Fuso</font></label>
                                <select id="FUSO" name="FUSO" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value=""> </option>
                                	<option value="19">Fuso 19</option>
                                	<option value="20">Fuso 20</option>
                                	<option value="21">Fuso 21</option>
                                	<option value="22">Fuso 22</option>
                                	<option value="23">Fuso 23</option>
                                	<option value="24">Fuso 24</option>
                                </select>

                            </td>                            
                        
                            <td align="left">&nbsp;
                            </td>                            
                        
                        </tr>   
                    </table>
                    
                    </span>                   
                    <!-- Região do Brasil -->       


                    <br>
                    
                    <span id="coberturanuvens" style="display:block">                    
                    <p><font size="2">Cobertura máxima de nuvens</font></p>
                    <table border="0" cellpadding="0" cellspacing="0" width="400">                        

						<!-- Primeiro e segundo Quadrantes -->
                        <tr>
                            <td align="left" width="90">
                                <font size="2">1º Quadrante:</font>
                            </td>
                        
                            <td align="left" width="10">&nbsp;
                            </td>                        
                        
                            <td align="left" width="60">
                               
                                <select id="Q1" name="Q1" style="width:60px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value="" selected> </option>
                                	<option value="0"> 0%</option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                	<option value="100"> 100%</option>
                                </select>
                            </td>
                        
                            <td align="left" width="20">&nbsp;
                            </td>
                        
                            <td align="left" width="90">
                                <font size="2">2º Quadrante:</font>
                            </td>

                            <td align="left" width="10">&nbsp;
                            </td>
                        
                            <td align="left" width="60">
                                
                                <select id="Q2" name="Q2" style="width:60px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:1px;">
                                	<option value="" selected> </option>
                                	<option value="0"> 0%</option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                	<option value="100"> 100%</option>                                    
                                </select>
                            </td>
                            
                            <td align="left">&nbsp;
                            </td>
                            
                        </tr> 
                        
						<!-- Terceiro e quarto Quadrantes -->                        
                        <tr>
                            <td align="left" width="90">
                            	<font size="2">3º Quadrante:</font>
                            </td>
                        
                            <td align="left" width="10">&nbsp;
                            </td>                        
                        
                            <td align="left" width="60">
                               
                                <select id="Q3" name="Q3" style="width:60px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value="" selected> </option>
                                	<option value="0"> 0%</option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                	<option value="100"> 100%</option>
                                </select>
                            </td>
                        
                            <td align="left" width="20">&nbsp;
                            </td>
                        
                            <td align="left" width="90">
                            	<font size="2">4º Quadrante:</font>
                            </td>

                            <td align="left" width="10">&nbsp;
                            </td>
                        
                            <td align="left" width="60">
                                
                                <select id="Q4" name="Q4" style="width:60px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:1px;">
                                	<option value="" selected></option>
                                	<option value="0"> 0%</option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                	<option value="100"> 100%</option>
                                </select>
                            </td>
                            
                            <td align="left">&nbsp;
                            </td>
                            
                        </tr>                        
                        
                        
                                               
                    </table>
                    
                    <br>
                    
                    </span>
                    
                    <span id="coberturaequalidade" style="display:none">                    
                    <!--p><font size="2">Cobertura máxima de nuvens</font></p-->
                    <table border="0" cellpadding="0" cellspacing="0" width="380">                        


                        <tr  height="25" valign="top">
                            <td align="left" width="160">
                       			<font size="2">% Cobertura Máxima</font>  
                            </td>

                            <td align="left" width="20" >&nbsp;
                            </td>
                        
                            <td align="left" width="160">
	                            <font size="2">Qualidade Mínima</font>
                            </td> 
                            <td align="left">&nbsp;
                            </td>
                                                   
                        </tr>



                        <tr>

                            <td align="left" width="160">
                                <font size="2">0</font>&nbsp;
                                <input id="CNTOTAL" name="CNTOTAL" type="text" class="span2" value="100" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="100" style="width:115px"/> 
                                &nbsp;<font size="2">100</font>
                            </td>                                                
                        

                            <td align="left" width="20" >&nbsp;
                            </td>
                        
                        
                            <td align="left" width="160">
                                <font size="2">0</font>&nbsp;
                                <input id="QITOTAL" name="QITOTAL" type="text" class="span2" value="0" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="0" style="width:120px"/>
                                &nbsp;<font size="2">10</font>
                            </td>                                                
                            <td align="left">&nbsp;
                            </td>
                            
                        </tr> 
                                               
                    </table>
                    
                    <br>
                    
                    </span>
                    
                    

                    
                    <!-- Opção de pesquisar apenas com os parâmetros básicos -->
                         
                    <table border="0" cellpadding="0" cellspacing="0" width="400">

                        <tr>
                        	<td height="4" colspan="4"> </td>                            
                        </tr>                    
                    
                        <tr>
                            <td align="left" width="160">
                        	<input class="btn" type="button"  id="rbasica" name="rbasica" value="Reiniciar Valores" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                            
                            <td width="20">&nbsp;
                            </td>     
                            
                            <td align="left" width="160">
                        	<input class="btn btn-info" type="button"  id="pbasica" name="pbasica" value="Pesquisar Imagens" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                                                   
                            <td align="left">&nbsp;
                            </td>     

                        </tr>   
                    </table>
                    <br>
                                                                             
                                        
                </div>
            </div>
        </div>
        
        <!-- Fim pesquisa básica    -->
       
        

 
 
 
        
        
        <!-- Permite pesquisar por Órbita e Ponto    -->
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#orbitaponto">
                Órbita e Ponto </a>
            </div>
            <div id="orbitaponto" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                
                
            
                   <br>
                   
                   <div class="input-append" style="margin:0px;margin-bottom:10px; text-align:left">
                                <label for="ORBITAINI"><font size="2">Órbita</font></label>
                                <input type="text" id="ORBITAINI" name="ORBITAINI" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px"  placeholder="Inicial" onFocus="onFocusBlurCampo(this, '#BACFFF')" onBlur="onFocusBlurCampo(this, '#FFFFFF')" maxlength="3" onKeyPress="return validarNumeroInteiro(event)">
                   </div>
               
                    
                    <div class="input-append" style="margin:0px;margin-bottom:10px;margin-right:10px;text-align:left">
                                <label for="ORBITAFIM"><font size="2">&nbsp;</font></label>
                                <input type="text" id="ORBITAFIM" name="ORBITAFIM" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Final" onFocus="onFocusBlurCampo(this, '#BACFFF')" onBlur="onFocusBlurCampo(this, '#FFFFFF')" maxlength="3" onKeyPress="return validarNumeroInteiro(event)">
                    </div>                                

                
                   <div class="input-append" style="margin:0px;margin-bottom:10px; text-align:left">
                                <label for="PONTOINI"><font size="2">Ponto</font></label>
                                <input type="text" id="PONTOINI" name="PONTOINI" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Inicial" onFocus="onFocusBlurCampo(this, '#BACFFF')" onBlur="onFocusBlurCampo(this, '#FFFFFF')" maxlength="3" onKeyPress="return validarNumeroInteiro(event)">
                   </div>
               
                    
                    <div class="input-append" style="margin:0px;margin-bottom:10px;text-align:left">
                                <label for="PONTOFIM"><font size="2">&nbsp;</font></label>
                                <input type="text" id="PONTOFIM" name="PONTOFIM" style="width:75px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Final" onFocus="onFocusBlurCampo(this, '#BACFFF')" onBlur="onFocusBlurCampo(this, '#FFFFFF')" maxlength="3" onKeyPress="return validarNumeroInteiro(event)">
                    </div>                         
                    <br>

                    <!-- Opção de pesquisar apenas com os parâmetros básicos -->
                         
                    <table border="0" cellpadding="0" cellspacing="0" width="400">

                        <tr>
                        	<td height="4" colspan="4"> </td>                            
                        </tr>                    
                    
                        <tr>
                            <td align="left" width="165">
                        	<input class="btn btn-info" type="button"  id="optodos" name="optodos" value="Listar em página Única" style="border-radius:3px;width:165px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                            
                            <td width="12">&nbsp;
                            </td>     
                            
                            <td align="left" width="165">
                        	<input class="btn btn-info" type="button"  id="oppaginado" name="oppaginado" value="Listar páginado" style="border-radius:3px;width:165px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                                                   
                            <td align="left">&nbsp;
                            </td>     

                        </tr>   
                    </table>
                    <br>
                                                                             
                                        
                </div>
            </div>
        </div>
        
        <!-- Fim pesquisa por Órbita e Ponto    -->
       
        

 
      
 
 
 
 
 
        
        
        <!-- Permite pesquisar por Cidade    -->
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#cidade">
                Municípios </a>
            </div>
            <div id="cidade" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                
                
                
     
    
  
                      <table border="0" cellpadding="0" cellspacing="0" width="404">
                        <tr>
                            <td align="left" width="165" valign="top">
                            
                         
							<form name="general" id="general" onSubmit="return false"> 
                            
                            	<div class="span3">                           	                           
                            
                                <label for="PAIS"><font size="2">País</font></label>
                                <select id="PAIS" name="PAIS" style="width:155px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="displayStates(this.selectedIndex);realizaPesquisaPaisEstado()">
                                    <option value=""></option>
                                    <option value="ARGENTINA">ARGENTINA</option>
                                    <option value="BOLIVIA">BOLIVIA</option>
                                    <option value="BRASIL">BRASIL</option>
                                    <option value="CHILE">CHILE</option>
                                    <option value="COLOMBIA">COLOMBIA</option>
                                    <option value="ECUADOR">ECUADOR</option>
                                    <option value="FRENCH GUIANA">FRENCH GUIANA</option>
                                    <option value="GUYANA">GUYANA</option>      
                                    <option value="PARAGUAY">PARAGUAY</option>
                                    <option value="PERU">PERU</option>
                                    <option value="SURINAME">SURINAME</option>
                                    <option value="URUGUAY">URUGUAY</option>   
                                    <option value="VENEZUELA">VENEZUELA</option>
                                    <option value="ALGERIA">ALGERIA</option>
                                    <option value="ANGOLA">ANGOLA</option>
                                    <option value="BENIN">BENIN</option>
                                    <option value="BOTSWANA">BOTSWANA</option>
                                    <option value="BURKINA FASO">BURKINA FASO</option>
                                    <option value="BURUNDI">BURUNDI</option>
                                    <option value="CAMEROON">CAMEROON</option>
                                    <option value="CENT AF REP">CENT AF REP</option>
                                    <option value="CHAD">CHAD</option>
                                    <option value="CONGO">CONGO</option>
                                    <option value="DJIBOTI">DJIBOUTI</option>
                                    <option value="EGYPT">EGYPT</option>
                                    <option value="EQ GUINEA">EQ GUINEA</option>
                                    <option value="ERITREA">ERITREA</option>
                                    <option value="ETHIOPIA">ETHIOPIA</option>
                                    <option value="GABON">GABON</option>
                                    <option value="GAMBIA">GAMBIA</option>
                                    <option value="GHANA">GHANA</option>
                                    <option value="GUINEA">GUINEA</option>
                                    <option value="GUINEABISSAU">GUINEABISSAU</option>
                                    <option value="IVORY COAST">IVORY COAST</option>
                                    <option value="KENYA">KENYA</option>
                                    <option value="LESOTHO">LESOTHO</option>
                                    <option value="LIBERIA">LIBERIA</option>
                                    <option value="LIBYA">LIBYA</option>
                                    <option value="MADAGASCAR">MADAGASCAR</option>
                                    <option value="MALAWI">MALAWI</option>
                                    <option value="MALI">MALI</option>
                                    <option value="MAURITANIA">MAURITANIA</option>
                                    <option value="MOROCCO">MOROCCO</option>
                                    <option value="MOZAMBIQUE">MOZAMBIQUE</option>
                                    <option value="NAMIBIA">NAMIBIA</option>
                                    <option value="NIGER">NIGER</option>
                                    <option value="NIGERIA">NIGERIA</option>
                                    <option value="RWANDA"> RWANDA</option>
                                    <option value="SENEGAL">SENEGAL</option>
                                    <option value="SIERRA LEONE">SIERRA LEONE</option>
                                    <option value="SOMALIA">SOMALIA</option>
                                    <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                    <option value="SUDAN">SUDAN</option>
                                    <option value="SWAZILAND">SWAZILAND</option>
                                    <option value="TANZANIA">TANZANIA</option>
                                    <option value="TOGO">TOGO</option>
                                    <option value="TUNISIA">TUNISIA</option>
                                    <option value="UGANDA">UGANDA</option>
                                    <option value="W SAHARA">W SAHARA</option>
                                    <option value="ZAIRE">ZAIRE</option>
                                    <option value="ZAMBIA">ZAMBIA</option>
                                    <option value="ZIMBABWE">ZIMBABWE</option>                            
                                </select>  
                                </div>                                                                                            
                            </td>
                            
                            <td align="left" width="20">&nbsp;
                            </td>                            
                                                       
                            <td align="left" width="165" valign="top">
	                            <div class="span3">
                                <label for="ESTADO"><font size="2">Estado</font></label>
                                <select id="ESTADO" name="ESTADO" style="width:155px;height:26px;font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="realizaPesquisaPaisEstado()">
                                </select>
                                </div>
                            </td>                            
                        
                            <td align="left">&nbsp;
                            </td>                            
                        
                        </tr>   
                                                                        
                        <tr>
                            <td align="left" colspan="4">   
                            <div class="span4">                                                      	                           
                                <input type="text" id="MUNICIPIO" name="MUNICIPIO" style="width:334px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:13px;border-radius:3px;padding:2px;"  placeholder="Município (Mínimo de 3 letras)" onKeyUp="realizaPesquisaMunicipios()">   
                            </div>                             
                            </td>
                        <tr>                        
                    </table>                     
                    </form>
  
                     <span id="registrosCidades" name="registrosCidades">
                     </span>
                                         
                                                                             
                                        
                </div>
            </div>
        </div>
        
 
  
  
  
  
  
  
  
  
  
    
        
        
        
        
        
        <!-- Permite pesquisar por região    -->
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#regiao">
                Região </a>
            </div>
            <div id="regiao" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                
                
                
                	<!--
                    <table border="0" cellpadding="2" cellspacing="2" width="360">                        
                        <tr>
                            <td align="center" colspan="3">
                            
                              <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:0px;text-align:center;margin-left:0px;">
                                        <input type="text" id="LAT2" name="LAT2" style="width:100px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" placeholder="Norte" onKeyPress="return validarCoordenada(event)">
                              </div>

							</td>                            
                        </tr>
                        <tr>
                            <td align="center" width="90px">
                              <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:10px;text-align:center">
                                        <input type="text" id="LON1" name="LON1" style="width:100px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" placeholder="Oeste" onKeyPress="return validarCoordenada(event)">
                              </div>
							</td> 
                                                       
                            <td align="center" width="130px" style="margin-left:6px;text-align:center">
                                <div class="input-append" >
                                    <a href=""><input class="btn btn-info" type="button"  id="regiao"  value="Executar" style="border-radius:3px;width:106px;height:28px"></a>
                                </div>
                            
							</td>                            
                            <td align="center" width="120px">
                              <div class="input-append" style="margin:0px;margin-bottom:10px; margin-left:10px;text-align:center">
                                        <input type="text" id="LON2" name="LON2" style="width:100px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" placeholder="Leste" onKeyPress="return validarCoordenada(event)">
                              </div>

							</td>                            
                        </tr>
                        <tr>
                            <td align="center" colspan="3">
                              <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:0px;text-align:center;margin-left:0px;">
                                        <input type="text" id="LAT1" name="LAT1" style="width:100px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" placeholder="Sul" onKeyPress="return validarCoordenada(event)">
                              </div>

							</td>                            
                        </tr>                        
					</table>
                    
                    -->
                    
                   <br>               
                
                   <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:20px;text-align:left">
                                <!--label for="NORTE"><font size="2">Norte</font></label-->
                                <input type="text" id="NORTE" name="NORTE" style="width:155px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Latitude Norte" onKeyPress="return validarCoordenada(event)">

                   </div>
               
                    
                    <div class="input-append" style="margin:0px;margin-bottom:10px;text-align:left">
                                <!--label for="LESTE"><font size="2">Leste</font></label-->
                                <input type="text" id="LESTE" name="LESTE" style="width:150px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Longitude Leste" onKeyPress="return validarCoordenada(event)">
                    
                    </div>                                


                
                   <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:20px;text-align:left">
                                <!--label for="SUL"><font size="2">Sul</font></label-->
                                <input type="text" id="SUL" name="SUL" style="width:155px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Latitude Sul" onKeyPress="return validarCoordenada(event)">

                   </div>
               
                    
                    <div class="input-append" style="margin:0px;margin-bottom:10px;text-align:left">
                                <!--label for="OESTE"><font size="2">Oeste</font></label-->
                                <input type="text" id="OESTE" name="OESTE" style="width:150px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Longitude Oeste" onKeyPress="return validarCoordenada(event)">
                    
                    </div>                                

                    <br>


                    
                    <table border="0" cellpadding="0" cellspacing="0" width="390">
                    	
                        <tr>
                        	<td height="4" colspan="4"> </td>                            
                        </tr>
                    	
                        <tr>
                            <td align="left" width="160">
                        	<input class="btn" type="button"  id="rregiao" name="rregiao" value="Reiniciar Valores" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                            
                            <td width="20">&nbsp;
                            </td>     
                            
                            <td align="left" width="160">
                        	<input class="btn btn-info" type="button"  id="pregiao" name="pregiao" value="Pesquisar Imagens" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                                                   
                            <td align="left">&nbsp;
                            </td>     

                        </tr>   
                    </table>
                    
                    <br>                    
                    
                    
                </div>
            </div>
        </div>
        
        
        
        
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#interfaceGrafica">
                Interface Gráfica </a>
            </div>
            <div id="interfaceGrafica" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">

                   <br>
                
                   <div class="input-append" style="margin:0px;margin-bottom:10px; margin-right:20px;text-align:left">
                                <!--label for="LAT"><font size="2">Latitude central</font></label-->
                                <input type="text" id="LAT" name="LAT" style="width:155px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" placeholder="Latitude Central" onKeyPress="return validarCoordenada(event)">

                   </div>
               
                    
                    <div class="input-append" style="margin:0px;margin-bottom:10px;text-align:left">
                                <!--label for="LON"><font size="2">Longitude central</font></label-->
                                <input type="text" id="LON" name="LON" style="width:150px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;"  placeholder="Longitude Central" onKeyPress="return validarCoordenada(event)">
                    
                    </div>                                

                    <br>
                    
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="390">

                        <tr>
                        	<td height="4" colspan="4"> </td>                            
                        </tr>                    
                    
                        <tr>
                            <td align="left" width="160">
                        	<input class="btn" type="button"  id="rinterface" name="rinterface" value="Reiniciar Valores" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                            
                            <td width="20">&nbsp;
                            </td>     
                            
                            <td align="left" width="160">
                        	<input class="btn btn-info" type="button"  id="pinterface" name="pinterface" value="Pesquisar Imagens" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                                                   
                            <td align="left">&nbsp;
                            </td>     

                        </tr>   
                    </table>

                    <br>
                    

                </div>
            </div>
        </div>
      
                             
    </div>
    
    </form>
          
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

<script src="/catalogo/js/bootstrap-datepicker.js"></script>
<script src="/catalogo/js/bootstrap-slider.js"></script>


<!--script src="/catalogo/js/bootstrap-datepicker.pl.js" charset="UTF-8"></script-->


<!-- CALL CAMERA SLIDER -->
<!--
<script>
	jQuery(function(){
		jQuery('#camera_wrap_4').camera({
			height: 'auto',
			loader: 'bar',
			pagination: false,
			thumbnails: false,
			hover: true,
			opacityOnGrid: false,
			imagePath: '/catalogo/img/'
		});
	});
</script>

-->

<!-- CALL FEATURED WORK -->

<!--
<script type="text/javascript">
$(window).load(function(){			
			$('#recent-projects').carouFredSel({
				responsive: true,
				width: '100%',
				auto: true,
				circular	: true,
				infinite	: false,
				prev : {
					button		: "#car_prev",
					key			: "left",
						},
				next : {
					button		: "#car_next",
					key			: "right",
							},
				swipe: {
					onMouse: true,
					onTouch: true
					},
				scroll : 1500,
				items: {
					visible: {
						min: 1,
						max: 1
					}
				}
			});
		});	
</script>

-->


<!-- CALL ACCORDION -->
<script type="text/javascript">
	$(".accordion h3").eq(0).addClass("active");
	$(".accordion .accord_cont").eq(0).show();
	$(".accordion h3").click(function(){
		$(this).next(".accord_cont").slideToggle("slow")
		.siblings(".accord_cont:visible").slideUp("slow");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});	
</script>



<!-- Call opacity on hover images from recent news-->
<script type="text/javascript">
$(document).ready(function(){
	
    $("img.imgOpa").hover(function() {
      $(this).stop().animate({opacity: "0.6"}, 'slow');
    },
	
    function() {
      $(this).stop().animate({opacity: "1.0"}, 'slow');
    });

  });
</script>


<script type="text/javascript">

	/*
	$('.input-append.date').datepicker({
	   todayBtn: false,
	   forceParse: true,
	   autoclose: true,
	   todayHighlight: true,
	   format: "dd/mm/yyyy"
	});	
	
	*/
	
	
	
   $('#DATAINI').datepicker({
	   todayBtn: false,
	   forceParse: true,
	   autoclose: true,
	   todayHighlight: true,
	   format: "dd/mm/yyyy"	   
	});	
	
	
	$('#dpdataini').click( function() {	
	   $('#DATAINI').datepicker("show");	
	});
	
	
	
	
	
   $('#DATAFIM').datepicker({
	   todayBtn: false,
	   forceParse: true,
	   autoclose: true,
	   todayHighlight: true,
	   format: "dd/mm/yyyy"	   
	});	
	
	
	
	$('#dpdatafim').click( function() {	
	   $('#DATAFIM').datepicker("show");	
	});
	
	
	
	

</script>


<script type="text/javascript">


function validarNumero( evento )
{

	var tecla = (window.event)?event.keyCode:evento.which;   
	
		if( (tecla > 47 ) && (tecla < 58) ) return true;
		else 
		{
			if (tecla==8 || tecla==0) return true;
		    else  return false;
		}	
		
}





function validarNumeroInteiro( evento )
{


	var tecla = (window.event)?event.keyCode:evento.which;   
	
		if( (tecla > 47 ) && (tecla < 58) ) return true;
		else
		{
			if ( tecla==8 || tecla==0 || tecla==13 || tecla==16 || tecla==17 || tecla==37 || tecla==38 || tecla==39 || tecla==40 || tecla== 45 || tecla== 46) return true;
			else return false;
		}
		
}




function validarCoordenada( evento )
{

	var tecla = (window.event)?event.keyCode:evento.which;   
	
		if( (tecla > 47) && (tecla < 58) ) return true;
		else 
		{
			if ( tecla == 8 || tecla == 0 || tecla == 43 || tecla == 46 || tecla == 45 ) return true;
		    else  return false;
		}	
		
		
}


</script>


<script>

$(document).ready(function(){
	
	
		// Executa processamento para o botão Pesquisa apenas com os parâmeros básicos
        $("#pbasica").live('click',function(e) {
				//$('#myTab a[href=#resultados]', window.parent.document).tab('show');
				obtemDados(e, 'BTNBASICA');				
				
		});
	
		// Reinicializa os valores dos campos de Pesquisa básicos	
        $("#rbasica").live('click',function(e) {
				
				$('#SATELITE').val(0);
				
				displaySatellite(0);
				
				$('#Q1').val(0);
				$('#Q2').val(0);
				$('#Q3').val(0);
				$('#Q4').val(0);							

		});
	
	



		// Executa processamento para o botão Pesquisa com os parâmetros báscios e de região
        $("#pregiao").live('click',function(e) {
				obtemDados(e, 'BTNREGIAO');				
				
		});
		
	
		// Reinicializa os valores dos campos da Região	
        $("#rregiao").live('click',function(e) {				
				$('#NORTE').val('');
				$('#SUL').val('');
				$('#LESTE').val('');
				$('#OESTE').val('');				
		});
	
	
	



		// Executa processamento para o botão Pesquisa com os parâmetros báscios e de interface
        $("#pinterface").live('click',function(e) {
				obtemDados(e, 'BTNINTERFACE');				
				
		});
	
	
		// Reinicializa os valores dos campos da Interface Gráfica	
        $("#rinterface").live('click',function(e) {				
				$('#LAT').val('');
				$('#LON').val('');
		});
	




		// Executa processamento para o botão Pesquisa paginada com os parâmetros báscios e de Órbita e Ponto
        $("#oppaginado").live('click',function(e) {
				obtemDados(e, 'BTNOPPAGINADO');								
		});
	

		// Executa processamento para o botão Pesquisa em página única com os parâmetros báscios e de Órbita e Ponto
        $("#optodos").live('click',function(e) {
				obtemDados(e, 'BTNOPTODOS');								
		});
	


		// Cobertura de nuvens de uma imagem total
		$("#CNTOTAL").slider({});

		// Qualidade da imagem
		$("#QITOTAL").slider({});


	
	
		// Executa a função atualizaIdleTime, que permite zerar o contador de tempo ocioso
		// Necessário para que possa ser obtida a quatidade de segundos antes de realizar a desconexão do usuário
		// Funão acionada pelos seguintes eventos que ocorrem: click, dblclick, keypress, mouseenter, select, scroll, resize,
		// mouseover, mousemove, mouseout, mouseenter, blur, focus
		$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur", function(e)
		{
		  top.atualizaIdleTime();
		});

	
		//$('#parametrosBasicos').collapse('show');

				
});





</script>



<!-- 
Final da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

</body>
</html>
