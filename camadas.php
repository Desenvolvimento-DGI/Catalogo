<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 


include("session_mysql.php");
session_start();

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
	
	// Verificar se o usuário caso esteja logado, possua SIAPE
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



?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html lang="en">

<head>
    <meta charset="utf-8">
    
    <title>Divisão de Geração de Imagem :: Camadas</title>
    
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
       
    
    <script type="text/javascript">
	var possuiPermissaoRapidEye=<?php echo "$opcaoTodosSatelites" ?>;
	</script>    
    
        
    <script src="/catalogo/camadas.js"></script>
    
    
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
    
    <div class="row-fluid">
    
        <div class="accordion-group" style="background-color:#F4F4F4">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#gradesatelites">
                Grade de Satélites </a>
            </div>
            <div id="gradesatelites" class="accordion-body collapse" style="height: 0px;background-color:#F9F9F9">
                <div class="accordion-inner" style="background-color:#FDFDFD">
                
                
					<br>
                         
                    <table class="table table-hover" width="400" style="margin-left:0px">
                    

                        <tr onClick="toggleGradeCbers('gradecbers')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade CBERS
                            </td>                            
                            <td align="center" width="30"><span id="gradecbers"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                        
                    	<!--
                        <tr onClick="toggleGradeCbersCCD('gradecbersccd')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade CBERS CCD
                            </td>                            
                            <td align="center" width="30"><span id="gradecbersccd"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                        <tr onClick="toggleGradeCbersHRC('gradecbershrc')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade CBERS HRC
                            </td>                            
                            <td align="center" width="30"><span id="gradecbershrc"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                        -->
                    
                        <tr onClick="toggleGradeLandSat5('gradelandsat5')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade LandSat 5
                            </td>                            
                            <td align="center" width="30"><span id="gradelandsat5"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleGradeRapidEye('graderapideye')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade RapidEye
                            </td>                            
                            <td align="center" width="30"><span id="graderapideye"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                         
                        
                    	
                        <tr onClick="toggleGradeResourceSat1('graderesourcesat1')">
                            <td align="left" width="30"><img src="/catalogo/img/img_grade_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Grade ResourceSat-1 - LISS3
                            </td>                            
                            <td align="center" width="30"><span id="graderesourcesat1"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                        
                         
                    </table>
                       
                                                                             
                                        
                </div>
            </div>
        </div>
        
        
    
       
        
        
        
        <!-- Camada divisão política  Continental  -->
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#camadadpcontinental">
                Continentes e Limites Territoriais </a>
            </div>
            <div id="camadadpcontinental" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                
                
                
					<br>
                         
                    <table class="table table-hover" width="400" style="margin-left:0px">
                    
                        <tr onClick="toggleCamadaAmericaSul('camadaamericasul')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">América do Sul
                            </td>                            
                            <td align="center" width="30"><span id="camadaamericasul"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaAmericaCentral('camadaamericacentral')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">América Central
                            </td>                            
                            <td align="center" width="30"><span id="camadaamericacentral"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaAmericaNorte('camadaamericanorte')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">América do Norte
                            </td>                            
                            <td align="center" width="30"><span id="camadaamericanorte"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>

                    </table>
                                     
                                                                             
                                        
                </div>
            </div>
        </div>
        
 
  
  
  
  
  
  
  
  
  
    
        
        
        
        
        
        <!-- Permite pesquisar por região    -->
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#camadaestadosbrasil">
                Estados do Brasil e Municípios</a>
            </div>
            <div id="camadaestadosbrasil" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                

                
                
					<br>
                         
                    <table class="table table-hover" width="400" style="margin-left:0px">
                    
                        <tr onClick="toggleCamadaBrasil('camadabrasil')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Estados do Brasil
                            </td>                            
                            <td align="center" width="30"><span id="camadabrasil"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(0, 'camadaac')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Acre
                            </td>                            
                            <td align="center" width="30"><span id="camadaac"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(1, 'camadaal')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Alagoas
                            </td>                            
                            <td align="center" width="30"><span id="camadaal"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                         
                    
                        <tr onClick="toggleCamadaEstados(2, 'camadaam')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Amazonas
                            </td>                            
                            <td align="center" width="30"><span id="camadaam"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(3, 'camadaap')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Amapá
                            </td>                            
                            <td align="center" width="30"><span id="camadaap"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         

                    
                        <tr onClick="toggleCamadaEstados(4, 'camadaba')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Bahia
                            </td>                            
                            <td align="center" width="30"><span id="camadaba"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(5, 'camadace')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Ceará
                            </td>                            
                            <td align="center" width="30"><span id="camadace"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(6, 'camadadf')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Distrito Federal
                            </td>                            
                            <td align="center" width="30"><span id="camadadf"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(7, 'camadaes')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Espírito Santo
                            </td>                            
                            <td align="center" width="30"><span id="camadaes"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>

                    
                        <tr onClick="toggleCamadaEstados(8, 'camadago')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Goiás
                            </td>                            
                            <td align="center" width="30"><span id="camadago"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(9, 'camadama')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Maranhão
                            </td>                            
                            <td align="center" width="30"><span id="camadama"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(10, 'camadamg')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Minas Gerais
                            </td>                            
                            <td align="center" width="30"><span id="camadamg"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(12, 'camadamt')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Mato Grosso
                            </td>                            
                            <td align="center" width="30"><span id="camadamt"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(11, 'camadams')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Mato Grosso do Sul
                            </td>                            
                            <td align="center" width="30"><span id="camadams"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(13, 'camadapa')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Pará
                            </td>                            
                            <td align="center" width="30"><span id="camadapa"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(14, 'camadapb')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Paraíba
                            </td>                            
                            <td align="center" width="30"><span id="camadapb"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(15, 'camadape')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Pernambuco
                            </td>                            
                            <td align="center" width="30"><span id="camadape"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(16, 'camadapi')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Piauí
                            </td>                            
                            <td align="center" width="30"><span id="camadapi"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(17, 'camadapr')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Paraná
                            </td>                            
                            <td align="center" width="30"><span id="camadapr"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(18, 'camadarj')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Rio de Janeiro
                            </td>                            
                            <td align="center" width="30"><span id="camadarj"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                    
                        <tr onClick="toggleCamadaEstados(19, 'camadarn')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Rio Grande do Norte
                            </td>                            
                            <td align="center" width="30"><span id="camadarn"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(22, 'camadars')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Rio Grande do Sul
                            </td>                            
                            <td align="center" width="30"><span id="camadars"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                         
                    
                        <tr onClick="toggleCamadaEstados(20, 'camadaro')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Rondônia
                            </td>                            
                            <td align="center" width="30"><span id="camadaro"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
 
                     
                        <tr onClick="toggleCamadaEstados(21, 'camadarr')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Roraima
                            </td>                            
                            <td align="center" width="30"><span id="camadarr"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(23, 'camadasc')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Santa Catarina
                            </td>                            
                            <td align="center" width="30"><span id="camadasc"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                        
                    
                        <tr onClick="toggleCamadaEstados(24, 'camadase')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Sergipe
                            </td>                            
                            <td align="center" width="30"><span id="camadase"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(25, 'camadasp')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">São Paulo
                            </td>                            
                            <td align="center" width="30"><span id="camadasp"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                    
                        <tr onClick="toggleCamadaEstados(26, 'camadato')">
                            <td align="left" width="30"><img src="/catalogo/img/img_camada_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Tocantins
                            </td>                            
                            <td align="center" width="30"><span id="camadato"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         


                    </table>
                                     
                                              
                    
                    
                </div>
            </div>
        </div>
        
        
        
        
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#marcadores">
                Marcadores</a>
            </div>
            <div id="marcadores" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">

                
                
					<br>
                         
                    <table class="table table-hover" width="400" style="margin-left:0px">
                    
                    
                    
                    
                        <tr onClick="toggleMarcadoresAmericaSul('marcadoramericasul')">
                            <td align="left" width="30"><img src="/catalogo/img/img_marcadores_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Países da América do Sul
                            </td>                            
                            <td align="center" width="30"><span id="marcadoramericasul"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                         
                    
                        <tr onClick="toggleMarcadoresAmericaCentral('marcadoramericacentral')">
                            <td align="left" width="30"><img src="/catalogo/img/img_marcadores_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Países da América Central
                            </td>                            
                            <td align="center" width="30"><span id="marcadoramericacentral"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         
                         
                    
                        <tr onClick="toggleMarcadoresAmericaNorte('marcadoramericanorte')">
                            <td align="left" width="30"><img src="/catalogo/img/img_marcadores_01.png"></span>
                            </td>                            
                            <td align="left" width="300">Países da América do Norte
                            </td>                            
                            <td align="center" width="30"><span id="marcadoramericanorte"><i class="icon-check-empty"></i></span>
                            </td>                            
                            <td align="left" width="10">&nbsp;
                            </td>                            
                        </tr>
                         

                    </table>
                                     
 
                    

                </div>
            </div>
        </div>
      
                             
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

<script src="/catalogo/js/bootstrap-datepicker.js"></script>
<!--script src="/catalogo/js/bootstrap-datepicker.pl.js" charset="UTF-8"></script-->


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


<script>

$(document).ready(function(){
	
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

