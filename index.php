<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

//error_reporting(E_ERROR | E_WARNING | E_PARSE);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');


header('Content-Type: text/html; charset=UTF-8');

$ip = $_SERVER["REMOTE_ADDR"];


//$ip = "150.163.134.166";

/*
if ( ("$ip" != "150.163.134.166") and ("$ip" != "150.163.134.69") and ("$ip" != "150.163.134.61") and ("$ip" != "150.163.134.74") and ("$ip" != "150.163.134.67")  and ("$ip" != "150.163.134.53")  and ("$ip" != "150.163.134.59") and ("$ip" != "150.163.134.111") and ("$ip" != "150.163.134.169") and ("$ip" != "150.163.134.161") and ("$ip" != "150.163.134.34") )
{
	header("Location: http://www.dgi.inpe.br/CDSR/"); 	
}
*/


?>


<?php
// Inclusão de blibliotecas para tratamento de idioma, controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais

include_once("session_mysql.php");
include_once("globals.php");
include_once("findCountry.php");

//ini_set("session.gc_maxlifetime", "30"); 
//session_cache_expire(30);
session_start();
//ini_set("session.gc_maxlifetime", "30"); 
sess_gc(get_cfg_var("session.gc_maxlifetime"));


$_SESSION['userIP']=$_SERVER["REMOTE_ADDR"];
$_SESSION['userTry']=0;




// Bloco para gerenciamento de idiomas
//
//log_msg( __FILE__.":".__LINE__.":"."Entrei" );

$southAmerica = array ("Argentina","Bolivia","Brazil","Chile","Colombia","Ecuador","French Guiana","Guyana",
					   "Paraguay","Peru","Suriname","Uruguay","Venezuela");



// Verifica o pais conforme IP do usuário
$country=findCountry();


// Segment for freeing Catalogo
$Open_area = true;
$_SESSION['openArea'] = $Open_area;
// End Segment for freeing Catalogo


$inpe = false;
$_SESSION['userIP'] = $_SERVER["REMOTE_ADDR"];
if (substr($_SESSION['userIP'],0,8) == "150.163.") $inpe = true;

// Local IP's must mean we are using from Brazil
if( strcmp( substr($_SESSION['userIP'],0,8), "192.168." ) == 0 ) {
  $country = "Brazil";
  $Open_area = true;
  $_SESSION['openArea'] = $Open_area;
}

if (isset($SL))
	$_SESSION['userLang']=$SL;
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


// require ("upframe_".$_SESSION['userLang'].".php");
// Globals - image
$imgDir = "../Suporte/images/"; 


// Fim do bloco para gerenciamento de idiomas


switch ( strtoupper($_SESSION['userLang']))
{
	case 'PT':
		$iconeIdiomaSelecionado='<img src="/catalogo/img/icone-bandeira-brasil-pequeno.png" border="0" alt="Português">';
		break;
	
	case 'EN':
		$iconeIdiomaSelecionado='<img src="/catalogo/img/icone-bandeira-inglaterra-pequeno.png" border="0" alt="English">';
		break;
	
	case 'ES':
		$iconeIdiomaSelecionado='<img src="/catalogo/img/icone-bandeira-espanha-pequeno.png" border="0" alt="Español">';
		break;
	
	case 'FR':
		$iconeIdiomaSelecionado='<img src="/catalogo/img/icone-bandeira-franca-pequeno.png" border="0" alt="Français">';
		break;	
}

	
// Icone referente ao usuário (Logado ou vistitante)

//$stringAcessoUsuarioTopo='<i class="icon-user" style="color:#FFFFFF;font-size:15px"></i><font style="color:#FFFFFF;font-size:10px;">&nbsp;&nbsp;NÃO LOGADO</font>';
$stringAcessoUsuarioTopo='';

//$stringAcessoUsuario='<i class="icon-user" style="color:#FFFFFF;font-size:22px;"></i><font style="color:#FFFFFF;font-size:12px;">&nbsp;&nbsp;NÃO LOGADO</font>';
$stringAcessoUsuario='';


$varEstaLogado = "false";

if(isset($_SESSION['userId'])) 
{

	$varEstaLogado = "true";
	
	$stringAcessoUsuarioTopo='<i class="icon-user" style="color:#FC3;font-size:15px"></i><font style="color:#FC3;font-size:12px;">&nbsp;&nbsp;' . $_SESSION['userId'] . '</font>';	

	$stringAcessoUsuario='<i class="icon-user" style="color:#FC3;font-size:22px"></i><font style="color:#FC3;font-size:16px;">&nbsp;&nbsp;' . $_SESSION['userId'] . '</font>';	

}



// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];

// Searching for cart
$sql = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);
$numeroitenscarrinho = $dbcat->numRows();




?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<!--html lang="en"-->

<head>
	<!--meta charset="utf-8"-->
	
	<title>Divisão de Geração de Imagem :: Catálogo de Imagens</title>
	
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
	
	<!--link href="/catalogo/css/bootstrap-modal.css" rel="stylesheet"-->
	<!--link href="/catalogo/css/bootstrap-modal-bs3patch.css" rel="stylesheet"-->
	
	<style>
	
	
		html, body, #map-canvas 
		{
			margin: 0px;
			padding: 0px
		}
		
	</style>
	
    
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-41269752-1', 'inpe.br');
      ga('send', 'pageview');
    
    </script>




	<script type="text/javascript">
	
	// Variáveis globais
	
	
	var sentidoAnimacaoTitulo="DIREITA";
	var tituloDgiAtual="DIVISÃO DE GERAÇÃO DE IMAGENS | OBT | INPE";
	var objetoTituloDgi;
	
	var posicaoAtualTituloDgi=0;
	var limiteLetraDestaque=28; // Posições de 0 até 28 (DIVISÃO DE GERAÇÃO DE IMAGENS)
	var tamanhoTituloDGI;
	var corAtualDestaque="#FC3";
	var	corSemDestaque = "#FFF";
	
	var estaLogado = <?php echo "$varEstaLogado" ?>;
	
	/*
	
	var countries = new Array("","ARGENTINA","BOLIVIA","BRASIL","CHILE","COLOMBIA","ECUADOR", "FRENCH GUIANA","GUYANA","PARAGUAY","PERU","SURINAME","URUGUAY","VENEZUELA","ALGERIA","ANGOLA","BENIN","BOTSWANA","BURKINA FASO","BURUNDI","CAMEROON","CENT AF REP","CHAD","CONGO","DJIBOUTI","EGYPT", "EQ GUINEA","ERITREA","ETHIOPIA","GABON","GAMBIA","GHANA","GUINEA", "GUINEABISSAU","IVORY COAST","KENYA","LESOTHO","LIBERIA","LIBYA","MADAGASCAR","MALAWI","MALI","MAURITANIA","MOROCCO","MOZAMBIQUE","NAMIBIA","NIGER","NIGERIA","RWANDA","SENEGAL","SIERRA LEONE","SOMALIA","SOUTH AFRICA","SUDAN","SWAZILAND","TANZANIA","TOGO","TUNISIA","UGANDA","W SAHARA","ZAIRE","ZAMBIA","ZIMBABWE");
	
	var states = new Array();
	states[0] = new Array("");
	states[1] = new Array("");
	states[2] = new Array("");
	states[3] = new Array("","ACRE","ALAGOAS","AMAPÁ","AMAZONAS","BAHIA","CEARÁ","DISTRITO FEDERAL","ESPÍRITO SANTO","GOIÁS","MARANHÃO","MINAS GERAIS","MATO GROSSO","MATO GROSSO DO SUL","PARÁ","PARAÍBA", "PERNAMBUCO","PIAUÍ","PARANÁ","RIO DE JANEIRO","RIO GRANDE DO NORTE","RONDÔNIA","RORAIMA","RIO GRANDE DO SUL","SANTA CATARINA","SERGIPE","SÃO PAULO","TOCANTINS");
	states[4] = new Array("");
	states[5] = new Array("");
	states[6] = new Array("");
	states[7] = new Array("");
	states[8] = new Array("");
	states[9] = new Array("");
	states[10] = new Array("");
	states[11] = new Array("");
	states[12] = new Array("");
	states[13] = new Array("");
	
	//States for Africa
	states[14] = states[15] = states[16] = states[17] = states[18] = states[19] = states[20] = states[21] = states[22] = states[23] = states[24] = states[25] = states[26] = states[27] = states[28] = states[29] = states[30] = states[31] = states[32] = states[33] = states[34] = states[35] = states[36] = states[37] = states[38] = states[39] = states[40] = states[14] = states[41] = states[42] = states[43] = states[44] = states[45] = states[46] = states[47] = states[48] = states[49] = states[50] = states[51]= states[52] = states[53] = states[54] = states[55] = states[56] = states[57] = states[58] = states[59] = states[60] = states[61] = states[62] = states[63] = new Array("");
	*/
	
	
	
	String.prototype.trim = function () 
	{
		return this.replace(/^\s*/, "").replace(/\s*$/, ""); 
	}
	
	

	
	/**
	* Nome: displayStates
	* Função executada sempre que um país é selecionado na caixa de seleção de países da área de pesquisa
	* Responsável por alterar o valor dos campos estados a cada seleção de um país
	*
	* entry		Indice referente ao país selecionado na caixa de seleção de países
	*/
	
	/*
	function displayStates(entry)
	{
			while (states[entry].length < document.general.ESTADO.options.length)
			{
					document.general.ESTADO.options[(document.general.ESTADO.options.length - 1)] = null;
			}
			for (y=0;y<states[entry].length;y++)
			{
					document.general.ESTADO[y]=new Option(states[entry][y]);
			}
			
			document.getElementById("MUNICIPIO").value="";
	}
	
	*/
		
	
	// Executa processamento paa o botão Pesquisa Municípios
	/*
	function realizaPesquisaMunicipios()
	{
		var textoMUNICIPIO = String(document.getElementById("MUNICIPIO").value);
		var tamanhoMUNICIPIO = textoMUNICIPIO.length;
		if ( tamanhoMUNICIPIO > 2  ) 
		{
			obtemCidades('BTNCIDADE');
		}
		else
		{
			
			if ( tamanhoMUNICIPIO == 0  ) 
			{
				obtemCidades('BTNCIDADE');
			}
		}
			
	}	
	*/
	
	
	// Executa processamento paa o botão Pesquisa Municípios
	/*
	function realizaPesquisaPaisEstado()
	{
			obtemCidades('BTNCIDADE');
	}	
	*/
	
	
	/*
	function obtemCidades(botao)
	{
		var objetoHTTP;		
		var pais = document.getElementById('PAIS').value;
		var estado = document.getElementById('ESTADO').value;
		var municipio = document.getElementById('MUNICIPIO').value;
	
		var registros = (parent.document.getElementById('fcidades')).contentWindow.document.getElementById('registros');
		var strParametros="&TRIGGER=" + botao;


		if ( pais == '' &&  estado == '' &&  municipio == '' )	
		{
			registros.innerHTML='';							
			return true;
		}
			
		// Parâmetros básicos para todas as opções de botões
		strParametros += "&PAIS=" + pais + "&ESTADO=" + estado + "&MUNICIPIO=" + municipio;
		
		if (window.ActiveXObject)
		{
			objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
		}
		else if (window.XMLHttpRequest)
		{
			objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
		}
		else
		{
			return null;
		}
		
		
		
	
		objetoHTTP.onreadystatechange=function()
		{
			
				
			if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
			{
				var retorno = objetoHTTP.responseText;
	
				if ( retorno.trim() == "0" )
				{
					
					retorno="";
					var mensagem = 'Nenhum registro encontrado para a seleção realizada.<br>' +
								   'Selecione novos valores e execute a pesquisa novamente.';
					
					registros.innerHTML='';
					
					//top.exibeMensagemGenerica( mensagem );
				}
	
				registros.innerHTML=retorno;
			}
			else
			{			
				//top.selecionaPaginaResultados();
				//registros.innerHTML="Processando.";	
			}
				
		}
	
		objetoHTTP.open("GET","buscarcidades.php?p=1&pg=1" + strParametros, true);
		objetoHTTP.send();
	
	}
	
	
	*/
	
	
	</script>
	
	
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script src="/catalogo/js/mapa-principal-catalogo.js"></script>
	
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		  <script src="/catalogo/js/html5shiv.js"></script>
		<![endif]-->
	<!-- Fav and touch icons -->
	<link rel="shortcut icon" href="/catalogo/img/ico/favicon.png">
	
</head>



<!-- 
Layout macro do site
As opções são:
boxed	Toda estrutura do site encaixada em uma espécie de caixa, possuindo largura
wide	Toda estrutura do site utilizando-se de toda a área disponivel, não possuindo margens
-->
<body class="wide" style="border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0px 0px; height:100%; padding-bottom:0px; padding-top:opx; overflow:hidden;"  onLoad="inicializacao()" onResize="alteraDimensoes()">




<!-- 
Inicio da área principal 
class body
-->

<div class="body" style="border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0px 0px; padding-bottom:0px;padding-top:opx;">

	
    <!--div id="barra-brasil" style="background:#FFCC33; height: 20px; padding:0 0 0 10px;display:block;"--> 
    <div id="barra-brasil" style="background:#7F7F7F; height:20px; padding:0 0 0 10px; display:block;"> 
    
        <ul id="menu-barra-temp" style="list-style:none;">
            <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED">
                <a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">
                	Portal do Governo Brasileiro
                </a>
            </li> 
            
            <li>
                <a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">
                	Atualize sua Barra de Governo
                </a>
            </li>
        </ul>
    </div>        
     
    
<!--        
<div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;"> 
	<ul id="menu-barra-temp" style="list-style:none;">
		<li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li> 
		<li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
	</ul>
</div>        
        
-->        
        
        
        
        
        
        



	<div id="acessoainformacao" style="background-image: url(/catalogo/img/fundo01.png);color:#fff;height:35px;left:0;margin:0;padding:0;position:relative;top:0;width:100%}">
	<table border="0"  cellpadding="0" cellspacing="0" width="100%" height="35" hspace="0" vspace="0">
		<tr>
			<!-- Espaçamento -->        
			<td width="2" height="35">&nbsp;             
			</td>
				
            <!--
			<td width="20" align="left" valign="middle" height="35">            	
				<font size="5"><b>                    
					<a href="" id="menutopo">
					<span id="iconemenu">
					

						<i id="iconeexibirocultarmenu" class="icon-double-angle-up" style="color:#FFF" data-toggle="tooltip" title="Clique para Ocultar o Cabeçalho e o Menu de Opções" data-trigger="hover" data-animation="true" data-placement="bottom"></i>
					</span>
					</a>
				</b></font>
			</td>
			 
			-->	        
			
			<!-- Menu com opções -->
			
			<td width="80" align="left" valign="middle" height="35">            	
				
                <!--span id="iconemenusuperior" style="display:none"-->
                <span id="iconemenusuperior" style="display:block">
			
					<div class="dropdown" id="menupopup" style="background-color:transparent;border-color:transparent;z-index:1010">
						<a id="submenuprincipal" role="button" data-toggle="dropdown" class="btn btn-info" data-target="#" href="" style="background-color:transparent;border-color:transparent;font-family:Arial, Helvetica, sans-serif;font-size:16px">
							<i id="menuopcoesprincipal" class="icon-align-justify" style="font-size:16px;font-style:normal"></i>&nbsp;<font size="1"><b>MENU</b></font>
						</a>
						
						<ul id="itenssubmenuprincipal" class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
						  <!--li id="msjanelapesquisa"><a href="#"><i class="icon-search"></i>&nbsp;&nbsp;<span id="msjanelapesquisatexto">Ocultar Janela de Pesquisa</span></a></li-->
						  <li id="mscaixadesenho"><a href="#"><i class="icon-crop"></i>&nbsp;&nbsp;<span id="mscaixadesenhotexto">Ocultar Caixa de Desenho</span></a></li>
						  <li id="msinfowindow"><a href="#"><i class="icon-eye-open"></i>&nbsp;&nbsp;<span id="msinfowindowtexto">Ocultar Informações da Imagem</span></a></li>
	
  
  
  
  
    					  <!--

						  <li class="dropdown-submenu">
							<a tabindex="-1" href="#"><i class="icon-tasks"></i>&nbsp;&nbsp;Camadas ...</a>
							<ul class="dropdown-menu">                  
							  <li class="dropdown-submenu">
								  
								  <li id="camadanuvens"><a href="#"><span id="textocamadanuvens">&nbsp; Cobertura de Nuvens</span></a></li>
                                  <li id="camadabrasil"><a href="#"><span id="textocamadabrasil">&nbsp; Estados do Brasil</span></a></li>
                                  
                             
                                   <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"></i>&nbsp;Municípios do Nordeste</a>
                                    <ul class="dropdown-menu">                  
                                      <li class="dropdown-submenu">
                                          
                                          <li id="camadaal" onClick="toggleCamadas(1, 'textocamadaal')"><a href="#">&nbsp;<span id="textocamadaal">&nbsp; Alagoas</span></a></li>
                                          <li id="camadaba" onClick="toggleCamadas(4, 'textocamadaba')"><a href="#">&nbsp;<span id="textocamadaba">&nbsp; Bahia</span></a></li>
                                          <li id="camadace" onClick="toggleCamadas(5, 'textocamadace')"><a href="#">&nbsp;<span id="textocamadace">&nbsp; Ceará</span></a></li>
                                          <li id="camadama" onClick="toggleCamadas(9, 'textocamadama')"><a href="#">&nbsp;<span id="textocamadama">&nbsp; Maranhão</span></a></li>
                                          <li id="camadapb" onClick="toggleCamadas(14, 'textocamadapb')"><a href="#">&nbsp;<span id="textocamadapb">&nbsp; Paraíba</span></a></li>
                                          <li id="camadape" onClick="toggleCamadas(15, 'textocamadape')"><a href="#">&nbsp;<span id="textocamadape">&nbsp; Pernambuco</span></a></li>
                                          <li id="camadapi" onClick="toggleCamadas(16, 'textocamadapi')"><a href="#">&nbsp;<span id="textocamadapi">&nbsp; Piauí</span></a></li>
                                          <li id="camadarn" onClick="toggleCamadas(19, 'textocamadarn')"><a href="#">&nbsp;<span id="textocamadarn">&nbsp; Rio Grande do Norte</span></a></li>
                                          <li id="camadase" onClick="toggleCamadas(24, 'textocamadase')"><a href="#">&nbsp;<span id="textocamadase">&nbsp; Sergipe</span></a></li>
                                      </li>
                                    </ul>
                                  </li>
                                  

                                   <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"></i>&nbsp;Municípios do Norte</a>
                                    <ul class="dropdown-menu">                  
                                      <li class="dropdown-submenu">
                                          
                                          <li id="camadaac" onClick="toggleCamadas(0, 'textocamadaac')"><a href="#">&nbsp;<span id="textocamadaac">&nbsp; Acre</span></a></li>
                                          <li id="camadaap" onClick="toggleCamadas(3, 'textocamadaap')"><a href="#">&nbsp;<span id="textocamadaap">&nbsp; Amapá</span></a></li>
                                          <li id="camadaam" onClick="toggleCamadas(2, 'textocamadaam')"><a href="#">&nbsp;<span id="textocamadaam">&nbsp; Amazonas</span></a></li>
                                          <li id="camadapa" onClick="toggleCamadas(13, 'textocamadapa')"><a href="#">&nbsp;<span id="textocamadapa">&nbsp; Pará</span></a></li>
                                          <li id="camadaro" onClick="toggleCamadas(20, 'textocamadaro')"><a href="#">&nbsp;<span id="textocamadaro">&nbsp; Rondônia</span></a></li>
                                          <li id="camadarr" onClick="toggleCamadas(21, 'textocamadarr')"><a href="#">&nbsp;<span id="textocamadarr">&nbsp; Raraima</span></a></li>
                                          <li id="camadato" onClick="toggleCamadas(26, 'textocamadato')"><a href="#">&nbsp;<span id="textocamadato">&nbsp; Tocantins</span></a></li>
                                      </li>
                                    </ul>
                                  </li>
                                       

                                  
                                  
                                  <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"></i>&nbsp;Municípios do Centro-Oeste</a>
                                    <ul class="dropdown-menu">                  
                                      <li class="dropdown-submenu">
                                          
                                          <li id="camadadf" onClick="toggleCamadas(6, 'textocamadadf')"><a href="#">&nbsp;<span id="textocamadadf">&nbsp; Distrito Federal</span></a></li>
                                          <li id="camadago" onClick="toggleCamadas(8, 'textocamadago')"><a href="#">&nbsp;<span id="textocamadago">&nbsp; Goiás</span></a></li>
                                          <li id="camadamt" onClick="toggleCamadas(12, 'textocamadamt')"><a href="#">&nbsp;<span id="textocamadamt">&nbsp; Mato Grosso</span></a></li>
                                          <li id="camadams" onClick="toggleCamadas(11, 'textocamadams')"><a href="#">&nbsp;<span id="textocamadams">&nbsp; Mato Grosso do Sul</span></a></li>
                                          
                                      </li>
                                    </ul>
                                  </li>
                                  
 
                                   
                                  <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"></i>&nbsp;Municípios do Sudeste</a>
                                    <ul class="dropdown-menu">                  
                                      <li class="dropdown-submenu">
                                          
                                          <li id="camadaes" onClick="toggleCamadas(7, 'textocamadaes')"><a href="#">&nbsp;<span id="textocamadaes">&nbsp; Espírito Santo</span></a></li>
                                          <li id="camadamg" onClick="toggleCamadas(10, 'textocamadamg')"><a href="#">&nbsp;<span id="textocamadamg">&nbsp; Minas Gerais</span></a></li>
                                          <li id="camadarj" onClick="toggleCamadas(18, 'textocamadarj')"><a href="#">&nbsp;<span id="textocamadarj">&nbsp; Rio de Janeiro</span></a></li>
                                          <li id="camadasp" onClick="toggleCamadas(25, 'textocamadasp')"><a href="#">&nbsp;<span id="textocamadasp">&nbsp; São Paulo</span></a></li>
                                      </li>
                                    </ul>
                                  </li>
                                  
                                  
                                 
                                  
                                  
                                  <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"></i>&nbsp;Municípios do Sul</a>
                                    <ul class="dropdown-menu">                  
                                      <li class="dropdown-submenu">
                                          
                                          <li id="camadapr" onClick="toggleCamadas(17, 'textocamadapr')"><a href="#">&nbsp;<span id="textocamadapr">&nbsp; Paraná</span></a></li>
                                          <li id="camadars" onClick="toggleCamadas(22, 'textocamadars')"><a href="#">&nbsp;<span id="textocamadars">&nbsp; Rio Grande do Sul</span></a></li>
                                          <li id="camadasc" onClick="toggleCamadas(23, 'textocamadasc')"><a href="#">&nbsp;<span id="textocamadasc">&nbsp; Santa Catarina</span></a></li>
                                      </li>
                                    </ul>
                                  </li>
                                  
                                  
                                  
							  </li>
							</ul>
						  </li>

						  -->
						

						  <!--
						  <li class="dropdown-submenu">
							<a tabindex="-1" href="#"><i class="icon-tasks"></i>&nbsp;&nbsp;Grades Satélites ...</a>
							<ul class="dropdown-menu">                  
							  <li class="dropdown-submenu">
                              
								  
								  <li id="camadadianoite" onClick="toggleDiaNoite()"><a href="#"><span id="textocamadadianoite">&nbsp; Camada Dia e Noite</span></a></li>
								  <li id="gradecbers" onClick="toggleCBERS()"><a href="#"><span id="textogradecbers">&nbsp; Grade CBERS</span></a></li>
                                  <li id="gradelandsat" onClick="toggleLANDSAT()"><a href="#"><span id="textogradelandsat">&nbsp; Grade LandSat</span></a></li>
                                  <li id="graderapideye" onClick="toggleRAPIDEYE()"><a href="#"><span id="textograderapideye">&nbsp; Grade RapidEye</span></a></li>
                                  
							  </li>
							</ul>
						  </li>


						  -->


          
    					  <!--
						  <li class="dropdown-submenu">
							<a tabindex="-1" href="#"><i class="icon-comments-alt"></i>&nbsp;&nbsp;Selecione o Idioma</a>
							<ul class="dropdown-menu">                  
							  <li class="dropdown-submenu">
								  <li><a href="/catalogo/?SL=PT">&nbsp;Português</a></li>
								  <li><a href="/catalogo/?SL=EN">&nbsp;English</a></li>
								  <li><a href="/catalogo/?SL=ES">&nbsp;Español</a></li>
								  <li><a href="/catalogo/?SL=FR">&nbsp;Français</a></li>
							  </li>
							</ul>
						  </li>
                          -->
                          
	
						  <!--li id="msrealizarlogin"><a href="#"><i class="icon-key"></i>&nbsp;&nbsp;Realizar login</a></li-->
						  <li id="msnovasenha"><a href="#"><i class="icon-lock"></i>&nbsp;&nbsp;Solicitar nova senha</a></li>
						  <li id="msrealizarcadastro"><a href="#"><i class="icon-user"></i>&nbsp;&nbsp;Realizar cadastro</a></li>
						  
						  <!--li id="msvisualizarcarrinho"><a href="#"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;Visualizar carrinho</a></li-->
						  <li id="mshistoricopedidos"><a href="#"><i class="icon-list"></i>&nbsp;&nbsp;Histórico de pedidos</a></li>
						  
						  <li id="mslogout"><a href="/catalogo/logout.php"><i class="icon-signout"></i>&nbsp;&nbsp;Sair</a></li>
						</ul>
					</div>
				</span>
			</td>
			
			 <td width="15" align="center" valign="middle" height="35">&nbsp;  
			
			
			<td width="280" align="left" valign="middle" height="35">            	
					<font size="1"><b>
						<span id="titulodgi" style="display:block">
						DIVISÃO DE GERAÇÃO DE IMAGENS | OBT | INPE
						</span>
					</b></font>
			</td>
			
			
			<td width="160" align="center" valign="middle" height="35">            	
					<font size="1" color="#FC3"><b>
						<span id="titulocatalogo" style="display:block">
						CATÁLOGO DE IMAGENS
						</span>
					</b></font>
			</td>


			 <!-- Espaaçamento -->
			 <td width="20" align="center" valign="middle" height="35">&nbsp;  
								   
			</td>








			<!-- 
<a id="btncarrinho" data-toggle="dropdown" class="btn btn-info" data-target="#" href="" style="background-color:transparent;border-color:transparent;font-family:Arial, Helvetica, sans-serif;font-size:15px;padding-left:4px;padding-right:4px"> -->
            

			<td width="100" align="center" valign="middle" height="35" style="border-left-color:#EFEFEF;border-left-style:groove;border-left-width:thin">  
            	<a href="" id="btnlogin" style="color:#FFF">          	
					<i id="iconelogin" class="icon-key" style="font-size:15px;color:#FC3" data-toggle="tooltip" title="Clique para que seja apresentada a janela de login" data-trigger="hover" data-animation="true" data-placement="bottom"></i>&nbsp;<font size="1"><b>ACESSO</b></font>
                </a>
			</td>
            
            
            
            <!--
<a id="btnlogin" data-toggle="dropdown" class="btn btn-info" data-target="#" href="" style="background-color:transparent;border-color:transparent;font-family:Arial, Helvetica, sans-serif;font-size:15px;padding-left:4px;padding-right:4px">            
            -->
            
			<td width="120" align="center" valign="middle" height="35">  
            	<a href="" id="btncarrinho" style="color:#FFF">          	                      	
					<i id="iconecarrinho" class="icon-shopping-cart" style="font-size:15px;color:#FC3" data-toggle="tooltip" title="Clique para visualizar o carrinho de imagens" data-trigger="hover" data-animation="true" data-placement="bottom"></i>&nbsp;<font size="1"><b>CARRINHO <font size="2">[<font color="#FC3"><span id="numeroitenscarrinho"><?php echo "$numeroitenscarrinho" ?></span></font>]</font></b></font>
                </a>
			</td>



			<td width="80" align="center" valign="middle" height="35" style="border-right-color:#EFEFEF;border-right-style:groove;border-right-width:thin">            	
					<font size="1"><b>
						<span id="titulozoom" style="display:block">
						ZOOM [4]
						</span>					
					</b></font>
			</td>

			<!--td width="80" align="left" valign="middle" height="35">            	
					<font size="1"><b>
						<span id="titulosatelite" style="display:block">
						[-]
						</span>
					</b></font>
			</td-->

			
           
            
			<td width="140" align="center" valign="middle" height="35">            	
					<b>
						<span id="titulousuario" style="display:block">
						<?php echo $stringAcessoUsuarioTopo ?>
						</span>
					</b>
			</td>



			<td width="70" align="left" valign="middle" height="35">            	
					<b>
						<span id="titulotimeout" style="display:block" data-toggle="tooltip" title="Tempo restante para que o usuário seja desconectado por timeout" data-trigger="hover" data-animation="true" data-placement="bottom">	
                        <!--i class="icon-time" style="color:#FFF;font-size:15px"></i><font style="color:#FFF;font-size:12px;">&nbsp;&nbsp;</font-->					
						</span>
					</b>
			</td>


			
			<td align="center" valign="middle" height="35">&nbsp;    
			</td>
		
			<!-- ìcone referente ao link para o site de acesso à informação --> 
            <!--       
			<td width="180" align="center" valign="baseline" height="35">
				 <a href="http://www.acessoainformacao.gov.br/">
				 <img src="/catalogo/img/acesso01.png" height="35" border=0>
				 </a>
			</td>
			-->
        	
			<!-- ìcone referente ao link para o site do Brasil -->        
            <!--       
			<td width="128" align="center" valign="baseline" height="35">
				 <a href="http://www.brasil.gov.br">
				 <img src="/catalogo/img/brasil01.png" height="35" border=0>
				 </a>
			</td>
            -->
            
			<td width="80" align="left" valign="middle" height="35" style="border-right-color:#EFEFEF;border-right-style:groove;border-right-width:thin">  
            

					<div class="dropdown" id="menuajudaprincipal">
						<a id="btnajuda" role="button" data-toggle="dropdown" class="btn btn-info" data-target="#" href="" style="background-color:transparent;border-color:transparent;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#FFF">
							<i id="iconeajuda" class="icon-info" style="font-size:18px;color:#FC3" data-toggle="tooltip" title="Clique para que seja apresentado um menu com itens de ajuda." data-trigger="hover" data-animation="true" data-placement="bottom"></i>&nbsp;&nbsp;<font size="1"><b>AJUDA</b></font>
						</a>
						                        
						<ul id="itensmenuajuda" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
						  <li id="masobredgi"><a href="#"><i class="icon-info"></i>&nbsp;&nbsp;Sobre a DGI</a></li>
						  <li id="masatelites"><a href="#"><i class="icon-info"></i>&nbsp;&nbsp;Satélites</a></li>
						  <li id="macomopesquisar"><a href="#"><i class="icon-info"></i>&nbsp;&nbsp;Como Pesquisar</a></li>
						  <li id="macontatos"><a href="#"><i class="icon-info"></i>&nbsp;&nbsp;Contatos</a></li>
						</ul>
					</div>
                
               
			</td>
            

			<td width="70" align="center" valign="middle" height="35"> 
            	&nbsp; 
            	<a href="/catalogo/logout.php" id="btnsair" style="color:#FFF">          	
					<i id="iconesair" class="icon-signout" style="font-size:18px;color:#FC3" data-toggle="tooltip" title="Clique para realizar logout do Catálogo" data-trigger="hover" data-animation="true" data-placement="bottom"></i>&nbsp;<font size="1"><b>SAIR</b></font>
                </a>
			</td>

            
			
			<!-- Espaçamento -->        
			<td width="8" align="center" valign="baseline" height="35">&nbsp;             
			</td>
		
		</tr>
	</table> 
	 
	</div>


	
	<div id="cabecalho" class="row-fluid" style="display:none">
	
	
		<!-- Imagem de fundo do cabeçalho  -->
		<div class="span12" style="background-image:url(/catalogo/img/bg/101.jpg);background-size:cover; border-top:none; border-bottom:none; padding:0;padding-bottom:0;border-bottom-width:thin;border-top-width:thin;border:thin;">
						   
			 
			<table border="0" width="100%" cellpadding="10" cellspacing="4">
			
				<tr>
					<td width="300" align="left" valign="middle">
						<font style="font-family:'Myriad Pro'; font-size:1.1em; color:#FFF">
						DIVIS&Atilde;O DE GERA&Ccedil;&Atilde;O DE IMAGENS
						</font>
						<font style="font-family:'Myriad Pro'; font-size:0.7em; color:#FFF">
						INSTITUTO NACIONAL DE PESQUISA ESPACIAIS
						</font>                
					</td>
	
					<td width="40">&nbsp;</td>
	
					<td width="230" align="left" valign="middle">
						<font style="font-family:'Myriad Pro'; font-size:1.3em; color:#FFF">
						CATÁLOGO DE IMAGENS
						</font>
					</td>
                    
					<td width="40">&nbsp;</td>

					<td width="150" align="rigth" valign="middle">
						<b>
						<span id="cabecalhousuario">
						<?php echo $stringAcessoUsuario ?>
						</span>
						</b> 
					</td>
	
					<td width="*">&nbsp;</td>
	
					<td width="80" align="left" valign="middle">
						<b>
						<span id="cabecalhotimeout" style="display:none" data-toggle="tooltip" title="Tempo restante para que o usuário seja desconectado por timeout" data-trigger="hover" data-animation="true" data-placement="bottom">	
                        <!--i class="icon-time" style="color:#FC3;font-size:22px"></i><font style="color:#FC3;font-size:16px;">&nbsp;&nbsp;</font-->					
						</span>
						</b> 
					</td>
						
					<td width="10">&nbsp;</td>
			
				</tr>
			</table>
			
			<!--
			<div class="clearfix">
			</div>
			-->
			
			
			<!--
			Menu de navegação com as opções
			-->
			
			<div class="row-nav navbar">
				<div class="navbar-inner">
					<ul id="nav" class="nav">
					
						<!-- Idioma selecionado 
						Apresenta a bandeira do idioma selecionado
						-->
						<li id="idiomaselecionado"><a href=""><?php echo "$iconeIdiomaSelecionado" ?></a></li>
						<li class="divider-vertical"></li>

																		
						<!-- Seleção de idioma 
						Apresenta os itens de menu referentes aos idiomas que podem ser selecionados
						O idioma corrente não é apresentado
						-->                            
						<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Idioma<b class="caret"></b></a>
						<ul class="dropdown-menu js-activated" style="display: none;">
							<li id="meidiomabrasil"><a href="/catalogo/?SL=PT"><img src="/catalogo/img/icone-bandeira-brasil-pequeno.png" border="0">&nbsp;&nbsp;Português</a></li>
							<li id="meidiomainglaterra"><a href="/catalogo/?SL=EN"><img src="/catalogo/img/icone-bandeira-inglaterra-pequeno.png" border="0">&nbsp;&nbsp;English</a></li>
							<li id="meidiomaespanha"><a href="/catalogo/?SL=ES"><img src="/catalogo/img/icone-bandeira-espanha-pequeno.png" border="0">&nbsp;&nbsp;Español</a></li>
							<li id="meidiomafranca"><a href="/catalogo/?SL=FR"><img src="/catalogo/img/icone-bandeira-franca-pequeno.png" border="0">&nbsp;&nbsp;Français</a></li>
							<!--.dropdown-->
						</ul>
						</li>                                                        
						<li class="divider-vertical"></li>

																		
						<!-- janelas -->                            
						<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Visualizar... <b class="caret"></b></a>
						<ul class="dropdown-menu js-activated" style="display: none;">
							<li id="mepesquisar"><a href=""><i class="icon-search"></i>&nbsp;&nbsp;<span id="itemmenupesquisar">Ocultar Janela de Pesquisa</span></a></li>
							<li id="meferramenta"><a href=""><i class="icon-crop"></i>&nbsp;&nbsp;<span id="itemmenuferramenta">Ocultar Caixa de Desenho</span></a></li>
							<li id="meinfowindow"><a href="#"><i class="icon-eye-open"></i>&nbsp;&nbsp;<span id="itemmenuinfowindow">Ocultar Informações da Imagem</span></a></li>
							<!--.dropdown-->
						</ul>
						</li>                                                        
						<li class="divider-vertical"></li>


						<!-- Home -->
						<li id="menuhome" data-toggle="tooltip" title="Acesso à página inicial do Catálogo" data-content="Ao selecionar este menu você será redicrecionado para a página inicial do Catálogo." data-trigger="hover" data-animation="true" data-placement="bottom"><a href="/catalogo/"><class="icon-home"></i>&nbsp;&nbsp;Home</a></li>
						<li class="divider-vertical"></li>
						
													

						<!-- Acesso -->                            
						<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Acesso... <b class="caret"></b></a>
						<ul class="dropdown-menu js-activated" style="display: none;">
							<li id="smenuacessar"><a href=""><i class="icon-key"></i>&nbsp;&nbsp;Relizar login</a></li>
							<li id="smenualterarsenha"><a href=""><i class="icon-lock"></i>&nbsp;&nbsp;Solicitar nova senha</a></li>
							<li id="smenucadastro"><a href=""><i class="icon-user"></i>&nbsp;&nbsp;Realizar cadastro</a></li>
							<!--.dropdown-->
						</ul>
						</li>                                                        
						<li class="divider-vertical"></li>


						<!-- Acesso -->                            
						<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Carrinho... <b class="caret"></b></a>
						<ul class="dropdown-menu js-activated" style="display: none;">
							<li id="smenucarrinho"><a href=""><i class="icon-shopping-cart"></i>&nbsp;&nbsp;Visualizar Carrinho</a></li>
							<li id="smenuhistorico"><a href=""><i class="icon-list"></i>&nbsp;&nbsp;Histórico de Pedidos</a></li>
							<!--.dropdown-->
						</ul>
						</li>                                                        
						<li class="divider-vertical"></li>
																				

						<!-- Ajuda -->
						<li id="menuajuda" data-toggle="tooltip" title="Apresenta uma janela com informações relacionadas ao catálogo e sua peração" data-content="Ao selecionar este menu você será redicrecionado para a página que permitira realizar o seu cadastro no catalogo." data-trigger="hover" data-animation="true" data-placement="bottom"><a href="/catalogo/">&nbsp;&nbsp;Ajuda</a></li>
						<li class="divider-vertical"></li>
																											

						<!-- Sair -->
						<li id="menusair" title="Permite ao usuário logado realizar logout do Catálogo" data-content="Permite ao usuário logado realizar logout do Catálogo" data-trigger="hover" data-animation="true" data-placement="bottom"><a href="/catalogo/logout.php">&nbsp;&nbsp;Sair</a></li>
						<li class="divider-vertical"></li>
								   
													
						<!-- Sair -->
						<!--
						<li id="menuhoraatual"><a href="">&nbsp;&nbsp;<span id="horaatual">99:99:99</span>&nbsp;&nbsp;</a></li>
						<li class="divider-vertical"></li>
						-->

						<!-- Zoom Atual -->
						<li id="menuzoomatual"><a href="">&nbsp;<i class="icon-zoom-in"></i>&nbsp;&nbsp;<span id="zoomatual">Zoom [4]</span>&nbsp;&nbsp;</a></li>
						<li class="divider-vertical"></li>

						<!-- Satélite atual -->
						<li id="menusateliteatual"><a href="">&nbsp;&nbsp;<span id="sateliteatual">[-]</span>&nbsp;&nbsp;</a></li>
						<li class="divider-vertical"></li>
													
					</ul>                   
					
				</div>
			</div>
			
			
			<!--
			Fim do nenu de navegação com as opções
			-->        
		</div>
	</div>
	<!-- 
	Fim do Cabeçalho
	-->



    <!--div id="divexibirJanelaPesquisa" style="display:none;color:#004679;top:68px;left:2px;width:70px;position:absolute;background-color:#004679;border-color:transparent;z-index:1000;border-radius:4px 4px 4px 4px;padding-bottom:4px;padding-top:6px;opacity:0.8">
    	<center>
            <a href="" id="exibirJanelaPesquisa">
                <i id="iconeExibirJanelaPesquisa" class="icon-search" style="font-size:23px;color:#FC3;font-weight:bold"></i><br>
                <font style="font-size:12px;color:#FFF">Pesquisar</font>
            </a>
        </center>
    </div-->






    <!--div id="divCarregando" style="top:250px;left:700px;width:50px;height:50px;position:absolute;background-color:transparent;z-index:1011;display:none"-->
    <!--div id="divCarregando" style="top:250px;left:700px;width:50px;height:50px;position:absolute;background-color:#FFFFFF;border-radius:8px 8px 8px 8px;border-color:#DDDDDD;border-style:solid;border-width:thin;z-index:1011;display:none"-->
    <div id="divCarregando" style="top:250px;left:700px;width:52px;height:54px;position:absolute;background-color:#FFFFFF;border-radius:10px 10px 10px 10px;border-color:#DDDDDD;border-style:solid;border-width:thin;z-index:1011;display:none">
        <center> 
        <img src="/catalogo/img/carregando007.gif" style="position:relative;top:4px">
        </center>        
    </div>







    <div id="divocultarJanelaPesquisa" style="top:67px;left:423px;width:28px;height:58px;position:absolute;background-color:#EEEEEE;opacity:0.88;border-radius:0px 8px 8px 0px;border-color:#DDDDDD;border-style:solid;border-width:thin;z-index:1009">
        <center> 
        <i id="ocultarJanelaPesquisa" class="icon-double-angle-left" style="position:relative;top:10px;font-size:26px;color:#000;font-weight:bold" data-toggle="tooltip" title="Clique aqui para ocultar a janela de Pesquisa" data-content="Ao selecionar este menu a Janela de Pesquisa será ocultada" data-trigger="hover" data-animation="true" data-placement="right" data-container="body"></i>
        </center>  
              
    </div>




	<!--
	Id: caixaopcoes    
	Caixa de opções contendo a página com os campos e pesquisa, a página de resultados da pesquisa realizada e a página
	com uma pequena documentação sobre como realizar a pesquisa
	-->
	
    
    <div class="row-fluid" id="caixaopcoes" draggable="true" style="position:absolute;top:36px;left:1px;width:420px;opacity:0.88;border:thin;border-color:#999;border-style:solid;background-color:#F3F3F3;display:none;z-index:1008">
	
		<div class="span6" id="subopcoes" style="width:420px;background-color:#F3F3F3; height:inherit;max-height:inherit">
			<!-- Accordions-->
			<br>
			<ul id="myTab" class="nav nav-tabs">					
				<li class="active"><a href="#pesquisar" data-toggle="tab"><i class="icon-search"></i>&nbsp;Pesquisar</a></li>
				<!--li class=""><a href="#cidades" data-toggle="tab"><i class="icon-list"></i>&nbsp;Municípios</a></li-->
				<li class=""><a href="#resultados" data-toggle="tab"><i class="icon-list"></i>&nbsp;Resultados</a></li>
				<li class=""><a href="#camadas" data-toggle="tab"><i class="icon-th-large"></i>&nbsp;Camadas</a></li>
				<!--li class=""><a href="#instrucoes" data-toggle="tab">Ajuda</a></li-->
				<!--li class="" id="ocultarJanelaPesquisa"  data-toggle="tooltip" title="Clique aqui para ocultar a janela de Pesquisa" data-content="Ao selecionar este menu você será redicrecionado para a página inicial do Catálogo." data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body"><a href=""><i class="icon-chevron-sign-up" style="font-size:20px;color:#000"></i></a></li-->
                
                <!--
                <div id="divocultarJanelaPesquisa" style="top:26px;left:370px;position:absolute;background-color:transparent;border-color:transparent;z-index:9999">
                	<a href="">                	
                    <img id="ocultarJanelaPesquisa" src="/catalogo/img/icone_left_01.png" data-toggle="tooltip" title="Clique aqui para ocultar a janela de Pesquisa" data-content="Ao selecionar este menu você será redicrecionado para a página inicial do Catálogo." data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body">
                    
                    </a>
                </div>
                -->
                
			</ul>
			
			<div id="myTabContent" class="tab-content">
				



				<!--  Tab com os campos para realização da pesquisa -->
				<div class="tab-pane fade active in" id="pesquisar">                
					<!--
					Id/Name: fpesquisa    
					Frame contendo a área da caixa de opções onde serão apresentados os campos para pesquisa
					-->
					<iframe id="fpesquisa" name="fpesquisa" style="width:418px;height:inherit;background-color:#F3F3F3" src="pesquisa.php" frameborder="0" scrolling="auto"> </iframe>
				</div>
				<!-- Fim da tab pesquisar -->


				<!-- Tab para apresentar os resultados da pesquisa por cidades  -->
                
				<!--div class="tab-pane fade" id="cidades"-->
					<!--
					Id/Name: fciddes    
					Frame contendo a área da caixa de opções onde serão apresentadas a listagem com o nome das cidades
					-->
                    
                    <!--
                    <table border="0" cellpadding="0" cellspacing="0" width="404" style="margin-left:15px">
                        <tr>
                            <td align="left" width="170" valign="top">
                            
                         
							<form name="general" id="general" onSubmit="return false"> 
                            
                            	<div class="span3">                           	                           
                            
                                <label for="PAIS"><font size="2">País</font></label>
                                <select id="PAIS" name="PAIS" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="displayStates(this.selectedIndex);realizaPesquisaPaisEstado()">
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
                                                       
                            <td align="left" width="170" valign="top">
	                            <div class="span3">
                                <label for="ESTADO"><font size="2">Estado</font></label>
                                <select id="ESTADO" name="ESTADO" style="width:160px;height:26px;font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="realizaPesquisaPaisEstado()">
                                </select>
                                </div>
                            </td>                            
                        
                            <td align="left">&nbsp;
                            </td>                            
                        
                        </tr>   
                                                                        
                        <tr>
                            <td align="left" colspan="4">   
                            <div class="span4">                                                      	                           
                                <input type="text" id="MUNICIPIO" name="MUNICIPIO" style="width:344px; height:22px; font-family:Tahoma, Geneva, sans-serif;font-size:13px;border-radius:3px;padding:2px;"  placeholder="Município (Mínimo de 3 letras)" onKeyUp="realizaPesquisaMunicipios()">   
                            </div>                             
                            </td>
                        <tr>                        
                    </table>                     
                    </form>
                   
					<iframe id="fcidades" name="fcidades" style="width:418px;height:inherit;background-color:#F3F3F3;margin-top:10px" src="cidades.php" frameborder="0" scrolling="auto"> </iframe>
                    
				</div>
                -->
                
				<!-- Fim da tab cidades -->


				<!-- Tab para apresentar os resultados da pesquisa  -->
				<div class="tab-pane fade" id="resultados">
					<!--
					Id/Name: fresultado    
					Frame contendo a área da caixa de opções onde serão apresentados os resultados (registros) retornados 
					pela pesquisa
					-->
					<iframe id="fresultado" name="fresultado" style="width:418px;height:inherit;background-color:#F3F3F3" src="resultado.php" frameborder="0" scrolling="auto"> </iframe>
				</div>
				<!-- Fim da tab resultados -->
                
                
				<!-- Tab para apresentar as instruções iniciais  -->
				<div class="tab-pane fade" id="camadas">                    
					<!--
					Id/Name: finstrucoes    
					Frame contendo a área da caixa de opções onde serão apresentadas a listagem com o nome das cidades
					-->
					<iframe id="fcamadas" name="fcamadas" style="width:418px;height:inherit;background-color:#F3F3F3" src="camadas.php" frameborder="0" scrolling="auto"> </iframe>
				</div>
				<!-- Fim da tab instruções -->


                
				<!-- Tab para apresentar as instruções iniciais  -->
				<div class="tab-pane fade" id="instrucoes">                    
					<!--
					Id/Name: finstrucoes    
					Frame contendo a área da caixa de opções onde serão apresentadas a listagem com o nome das cidades
					-->
					<iframe id="finstrucoes" name="finstrucoes" style="width:418px;height:inherit;background-color:#F3F3F3" src="instrucoes.php" frameborder="0" scrolling="auto"> </iframe>
				</div>
				<!-- Fim da tab instruções -->

				
				
			</div>

		</div>
	</div>
	<!-- Fim div caixaopcoes -->
     
	


	<!--
	Id/Name: fmosaico    
	Frame contendo a área do mapa, onde serão apresentadas as imagens selecionadas
	-->        
	<iframe width="100%" height="250" id="fmosaico" name="fmosaico" frameborder="0" src="mosaico.php" hspace="0" scrolling="no" vspace="0">
	</iframe>
			

	<!--
	Id/Name: frodape    
	Frame contendo a área do mapa, onde será apresentado o rodapé
	--> 
	<!--       
	<iframe width="100%" height="75" id="frodape" name="frodape" frameborder="1" marginheight="2" src="rodape.php" align="bottom" scrolling="no" hspace="0" vspace="0">
	</iframe>
	-->
		  

	<!-- 
	Id: wmacesso    
	Apresentação da janela de login 
	Contém a div (camada) responsável por apresentar a tela de login
	-->
	<div id="wmacesso" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px;">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-key"></i>&nbsp;&nbsp;Realizar login</font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF;border-bottom-left-radius:8px;border-bottom-right-radius:8px">
		<div class="row-fluid">

			<iframe width="500" height="200" id="flogin" name="flogin" frameborder="0" hspace="0" scrolling="no" vspace="0">
			</iframe>
	
		</div>
	  </div>
	  <!--
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">        
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	  -->
	</div>



	<!-- 
	Id: wmnovasenha    
	Apresentação da janela para solicitar uma nova senha
	Contém a div (camada) responsável por apresentar a tela para solicitar uma nova senha
	-->
	<div id="wmnovasenha" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-lock"></i>&nbsp;&nbsp;Solicitar nova senha</font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF;border-bottom-left-radius:8px;border-bottom-right-radius:8px">
		<div class="row-fluid">

			<iframe width="520" height="225" id="fnovasenha" name="fnovasenha" frameborder="0" hspace="0" scrolling="no" vspace="0">
			</iframe>
			
		</div>
	  </div>
	  <!--
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">        
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	  -->
	</div>





	<!-- 
	Id: wmcadastro    
	Apresentação da janela para que o usuário visitante possa se cadastrar 
	Contém a div (camada) responsável por apresentar a tela contendo o formulário necessário para se cadastrar no
	Catálogo
	-->
	<div id="wmcadastro" class="modal hide fade" tabindex="-1" data-width="700" style="width:700px;border-radius:8px;margin-lef: -350px">
	  <div class="modal-header" style="background-color:#14354E;width:700px;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-user"></i>&nbsp;&nbsp;Realizar cadastro no Catálogo</font>
	  </div>
	  <div class="modal-body" style="width:700px;background-color:#FDFDFD">
		<div class="row-fluid" style="background-color:#FDFDFD">

			<iframe width="700" height="370" id="fcadastro" name="fcadastro" frameborder="0" hspace="0" scrolling="auto" vspace="0" style="background-color:#FDFDFD">
			</iframe>

		</div>
	  </div>
	  <div class="modal-footer" style="width:700px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	</div>





	<!-- 
	Id: wmhistorico    
	Apresentação da janela para que o usuário possa consultar o histórico de pedidos que realizou
	Contém a div (camada) responsável por apresentar a tela contendo o formulário necessário para consultar o
	Histórico de pedidos realizados
	-->
	<div id="wmhistorico" class="modal hide fade" tabindex="-1" data-width="700" style="width:700px;border-radius:8px;margin-left:-350px">
	  <div class="modal-header" style="background-color:#14354E;width:700px;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-list"></i>&nbsp;&nbsp;Histórico de pedidos de imagens</font>
	  </div>
	  <div class="modal-body" style="width:700px;background-color:#FFFFFF">
		<div class="row-fluid">

			<iframe width="700" height="350" id="fhistorico" name="fhistorico" frameborder="0" hspace="0" scrolling="auto" vspace="0">
			</iframe>

		</div>
	  </div>
	  <div class="modal-footer" style="width:700px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	</div>





	<!-- 
	Id: wmhistorico    
	Apresentação da janela para que o usuário possa consultar o histórico de pedidos que realizou
	Contém a div (camada) responsável por apresentar a tela contendo o formulário necessário para consultar o
	Histórico de pedidos realizados
	-->
	<div id="wmverpedido" class="modal hide fade" tabindex="-1" data-width="700" style="width:700px;border-radius:8px;margin-left:-350px">
	  <div class="modal-header" style="background-color:#14354E;width:700px;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-list"></i>&nbsp;&nbsp;Histórico de pedidos de imagens</font>
	  </div>
	  <div class="modal-body" style="width:700px;background-color:#FFFFFF">
		<div class="row-fluid">

			<iframe width="700" height="350" id="fverpedido" name="fverpedido" frameborder="0" hspace="0" scrolling="auto" vspace="0">
			</iframe>

		</div>
	  </div>
	  <div class="modal-footer" style="width:700px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	</div>






	<!-- 
	Id: wmcarrinho    
	Apresentação da janela para que o usuário possa consultar as imagens selecionadas por ele
	Contém a div (camada) responsável por apresentar a tela contendo a lista das imagens selecionadas
	-->
	<div id="wmcarrinho" class="modal hide fade" tabindex="-1" data-width="700" style="width:700px;border-radius:8px;margin-left: -350px">
	  <div class="modal-header" style="background-color:#14354E;width:700px;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;Carrinho de imagens</font>
	  </div>
	  <div class="modal-body" style="width:700px;background-color:#FFFFFF">
		<div class="row-fluid">

			<iframe width="700" height="350" id="fcarrinho" name="fcarrinho" frameborder="0" hspace="0" scrolling="auto" vspace="0">
			</iframe>

		</div>
	  </div>
	  <div class="modal-footer" style="width:700px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:4px">Fechar</button>
	  </div>
	</div>




	<!-- 
	Id: wmdetalhesimagem    
	Apresentação da janela de detalhes sobre a imagem/cena selecionado 
	Contém a div (camada) responsável por apresentar informações mais detalhadas sobre a imagem selecionada
	-->
	<div id="wmdetalhesimagem" class="modal hide fade" tabindex="-1" data-width="700" style="width:700px;border-radius:8px;margin-left: -350px">
	  <div class="modal-header" style="background-color:#14354E;width:700px;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-list"></i>&nbsp;&nbsp;Detalhes da imagem selecionada </font>
	  </div>
	  <div class="modal-body" style="width:700px;background-color:#FFFFFF">
		<div class="row-fluid">

		<span id="detalhesimagemselecionada"></span>

		</div>
	  </div>
	  <div class="modal-footer" style="width:700px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<!--button type="button" class="btn btn-info">Adicionar ao carrinho</button-->
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:3px">Fechar janela</button>
	  </div>
	</div>





	<!-- 
	Id: wmmensagem
	Apresentação da janela de mensagens de aviso, advertência ou erro 
	Contém a div (camada) responsável por apresentar mensagem geral, contendo apenas um texto comum    
	-->
	<div id="wmaddtocart" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;Carrinho de imagens </font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF">
		<div class="row-fluid">

		<br>
			<span id="wmtextoaddtocart"></span>
		<br>

		</div>
	  </div>
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<!--button type="button" class="btn btn-info">Adicionar ao carrinho</button-->
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:3px">Fechar janela</button>
	  </div>
	</div>






	<!-- 
	Id: wmtimeout
	Apresentação da janela de mensagens de aviso, advertência ou erro 
	Contém a div (camada) responsável por apresentar mensagem geral, contendo apenas um texto comum    
	-->
	<div id="wmtimeout" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><i class="icon-time"></i>&nbsp;&nbsp;TimeOut do Acesso </font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF">
		<div class="row-fluid">

			<span id="wmtextoconexaoociosa">
				<table border="0" cellpadding="2" cellspacing="2" width="500">
					<tr>
						<td width="160" align="center" valign="middle">
							<img src="/catalogo/img/timeout-01.jpg" border="0">
						</td>

						<td align="justify" valign="middle">
							<font size="2" face="Arial, Helvetica, sans-serif" color="#FFFFFF"></font>
								<b>Desconexão por timeout.</b><br>
								Você ficou mais de 30 minutos sem realizar qualquer interação com o Catálogo de imagens. Por este motivo sua sessão foi encerrada. <br>
                                As imagens selecionadas ainda constam no carrinho, sendo necessário apenas realizar novo acesso pra finalizar o pedido.
							</font>
						</td>
					</tr>
				</table>
			</span>

		</div>
	  </div>
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<!--button type="button" class="btn btn-info">Adicionar ao carrinho</button-->
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:3px">Fechar janela</button>
	  </div>
	</div>




	<!-- 
	Id: wmmensagem
	Apresentação da janela de mensagens de aviso, advertência ou erro 
	Contém a div (camada) responsável por apresentar mensagem geral, contendo apenas um texto comum    
	-->
	<div id="wmmensagem" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;&nbsp;Mensagem </font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF">
		<div class="row-fluid">

		<br>
			<span id="wmtextomensagem"></span>
		<br>

		</div>
	  </div>
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<!--button type="button" class="btn btn-info">Adicionar ao carrinho</button-->
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:3px">Fechar janela</button>
	  </div>
	</div>




	<!-- 
	Id: wmmensagem
	Apresentação da janela de mensagens e avisos 
	-->
	<div id="wmnotas" class="modal hide fade" tabindex="-1" data-width="550" style="width:550px;border-radius:8px">
	  <div class="modal-header" style="background-color:#14354E;border-top-left-radius:8px;border-top-right-radius:8px">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#FFF">x</button>
		<font size="3" face="Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;&nbsp;Nota sobre as imagens do CBERS-4</font>
	  </div>
	  <div class="modal-body" style="background-color:#FFFFFF">
		<div class="row-fluid">

		<br>
			<p align="justify">
			Enquanto conclui suas atividades de comissionamento do satélite CBERS-4, o INPE disponibiliza cerca de 1.158 imagens da câmera MUX sobre o 
            território brasileiro e outras regiões, processadas na China pela CRESDA (contraparte chinesa do Programa de Aplicações CBERS).
			O INPE não realizou avaliações técnicas sobre a qualidade radiométrica e geométrica das imagens.            
            </p>
		<br>

		</div>
	  </div>
	  <div class="modal-footer" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;background-color:#DFDFDF">
		<!--button type="button" class="btn btn-info">Adicionar ao carrinho</button-->
		<button type="button" data-dismiss="modal" class="btn" style="border-radius:3px">Fechar janela</button>
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
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>


<!-- 
Fim da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
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



<!-- Inicio  da seção de códigos Javascript  -->
<script type="text/javascript">

// Variáveis globais

var idleTime=0;
var tempoConexaoTimeOut=1800; 

var objetoTituloDgi=document.getElementById('titulodgi');
var tamanhoTituloDGI=tituloDgiAtual.length;

var retornoUsuarioLogado="";

/**
Nome: alteraDimensoes

Altera as dimensoões dos frames e das divs (Camadas) conforme a janela do browser é redimensionada, adequando
corretamente à área existente
*/
function alteraDimensoes()
{
	

	var divBarraGovernoFederal = window.document.getElementById("barra-brasil");	
	var divAcessoAInformacao = window.document.getElementById("acessoainformacao");
	var divCabecalho = window.document.getElementById("cabecalho");
	var posicaoBase;
	
	
	
	var frameMosaico = window.document.getElementById("fmosaico");
	var caixaOpcoes = window.document.getElementById("caixaopcoes");
	var subOpcoes = window.document.getElementById("subopcoes");
	var finstrucoes = window.document.getElementById("finstrucoes");
	var fcidades = window.document.getElementById("fcidades");
	var fpesquisa = window.document.getElementById("fpesquisa");
	var fresultado = window.document.getElementById("fresultado");		
	//var frameRodape = window.document.getElementById("frodape");
	var fcamadas = window.document.getElementById("fcamadas");
		
	var divCarregando = document.getElementById("divCarregando");
	
	
	
	// Verific se o cabeçalho esta oculto
	if ( (divCabecalho.style.display).toLowerCase() == "none")
	{
		// Se o cabeçalho estiver oculto não contabiliza a altura do mesmo na posicaoBase
		posicaoBase = divAcessoAInformacao.clientHeight + divBarraGovernoFederal.clientHeight;
	}
	else
	{
		// Se o cabeçalho não estiver oculto contabiliza a altura do mesmo na posicaoBase
		posicaoBase = divAcessoAInformacao.clientHeight + divCabecalho.clientHeight + divBarraGovernoFederal.clientHeight;				
	}


	frameMosaico.height = 	window.innerHeight - ( posicaoBase + /*frameRodape.clientHeight + */ 5);

	/*
	if ( window.innerHeight >= 500 )
	{
		//frameMosaico.height = 	window.innerHeight - ( posicaoBase + frameRodape.clientHeight + 5);
		frameMosaico.height = 	window.innerHeight - ( posicaoBase + 5);
	}
	else
	{
		frameMosaico.height = 	250;
	}
	*/
	
	
	//frameRodape.style.top = (window.innerHeight - frameRodape.clientHeight ) + "px";
	
	caixaOpcoes.style.top = posicaoBase + "px";
	caixaOpcoes.style.height = (frameMosaico.height - 2) + "px";
	caixaOpcoes.style.maxHeight = (frameMosaico.height - 2) + "px";
	
	
	//fcidades.style.height = (frameMosaico.height - (80 + 100)) + "px";
	//fcidades.style.maxHeight = (frameMosaico.height - (80 + 100)) + "px";

	finstrucoes.style.height = (frameMosaico.height - 80) + "px";
	finstrucoes.style.maxHeight = (frameMosaico.height - 80) + "px";

	
	fpesquisa.style.height = (frameMosaico.height - 80) + "px";
	fpesquisa.style.maxHeight = (frameMosaico.height - 80) + "px";
		
	fresultado.style.height = (frameMosaico.height - 80) + "px";
	fresultado.style.maxHeight = (frameMosaico.height - 80) + "px";


	fcamadas.style.height = (frameMosaico.height - 80) + "px";
	fcamadas.style.maxHeight = (frameMosaico.height - 80) + "px";




	divCarregando.style.top = ( parseInt( frameMosaico.height / 2 ) - 25) + "px";
	divCarregando.style.left = ( parseInt( window.innerWidth / 2 ) - 25) + "px";


	
	//caixaOpcoes.style.display = 'block';			
	return true;
}




/**
Nome: relogioDigital
Apresenta a hora na barra de menu
*/
function relogioDigital()
{	
	var momentoAtual = new Date();
	var hora = momentoAtual.getHours();
	var minuto = momentoAtual.getMinutes();
	var segundo = momentoAtual.getSeconds(); 

	var stringSegundo = new String (segundo);
	if (stringSegundo.length == 1) 
		 segundo = "0" + segundo;

	var stringMinuto = new String (minuto);
	if (stringMinuto.length == 1) 
		 minuto = "0" + minuto;

	var stringHora = new String (hora);
	if (stringHora.length == 1) 
		 hora = "0" + hora;

	var stringHoraCompleta = hora + " : " + minuto + " : " + segundo;
	document.getElementById('horaatual').innerHTML = stringHoraCompleta;

	setTimeout("relogioDigital()",1000) 		
}





/**
Nome: animacaoTititulo
Animação do titulo da DGI
Somente é apresentado quando o menu compacto estiver sendo exibido
*/
function animacaoTititulo()
{	
	var novoTitulo="";
	var corDestaqueInicio="<font color='#FC3'>";
	var corDestaqueFim="</font>";
			
	if ( posicaoAtualTituloDgi == 0 )
	{
		novoTituloDgi=corDestaqueInicio + tituloDgiAtual.substr(0,1) + corDestaqueFim + tituloDgiAtual.substr(1, (tamanhoTituloDGI))
		//alert(novoTituloDgi);
	}
	else
	{
		novoTituloDgi=tituloDgiAtual.substr(0,posicaoAtualTituloDgi) + 
		corDestaqueInicio  + tituloDgiAtual.substr(posicaoAtualTituloDgi,1) + corDestaqueFim + 
		tituloDgiAtual.substr((posicaoAtualTituloDgi + 1),(tamanhoTituloDGI - (posicaoAtualTituloDgi + 1)));
		//alert(novoTituloDgi);
	}
		

	if ( sentidoAnimacaoTitulo == "DIREITA" )
	{
		posicaoAtualTituloDgi++;
		if ( posicaoAtualTituloDgi > limiteLetraDestaque ) sentidoAnimacaoTitulo = "ESQUERDA";
	}
	else
	{
		posicaoAtualTituloDgi--;
		if ( posicaoAtualTituloDgi == 0 ) sentidoAnimacaoTitulo = "DIREITA";
	}
		
	objetoTituloDgi.innerHTML = novoTituloDgi;
	setTimeout("animacaoTititulo()",25);		
}







/**
Nome: animacaoTititulo2
Animação do titulo da DGI
Somente é Apresentado quando o menu compacto estiver sendo exibido
*/
function animacaoTititulo2()
{
	
	var novoTitulo="";
	var corDestaqueInicio="<font color='#FC3'>";
	var corDestaqueFim="</font>";
		
		
	//alert(posicaoAtualTituloDgi);
	
	novoTituloDgi=corDestaqueInicio + tituloDgiAtual.substr(0,posicaoAtualTituloDgi+1) + corDestaqueFim + tituloDgiAtual.substr(posicaoAtualTituloDgi + 1, (tamanhoTituloDGI -1))

	//alert(novoTituloDgi);
	
	if ( sentidoAnimacaoTitulo == "DIREITA" )
	{
		posicaoAtualTituloDgi++;
		if ( posicaoAtualTituloDgi > limiteLetraDestaque ) sentidoAnimacaoTitulo = "ESQUERDA";
	}
	else
	{
		posicaoAtualTituloDgi--;
		if ( posicaoAtualTituloDgi == 0 ) sentidoAnimacaoTitulo = "DIREITA";		
	}
		
	objetoTituloDgi.innerHTML = novoTituloDgi;
	setTimeout("animacaoTititulo2()",25);		
}






/**
Nome: animacaoTititulo
Animação do titulo da DGI
Somente é Apresentado quando o menu compacto estiver sendo exibido
*/
function animacaoTititulo3()
{	
	var novoTitulo="";
	var corDestaqueInicio="<font style=\"background-color:#FC3;color:#000\">";
	var corDestaqueFim="</font>";
			
	if ( posicaoAtualTituloDgi == 0 )
	{
		novoTituloDgi=corDestaqueInicio + tituloDgiAtual.substr(0,1) + corDestaqueFim + tituloDgiAtual.substr(1, (tamanhoTituloDGI))
		//alert(novoTituloDgi);
	}
	else
	{
		novoTituloDgi=tituloDgiAtual.substr(0,posicaoAtualTituloDgi) + 
		corDestaqueInicio  + tituloDgiAtual.substr(posicaoAtualTituloDgi,1) + corDestaqueFim + 
		tituloDgiAtual.substr((posicaoAtualTituloDgi + 1),(tamanhoTituloDGI - (posicaoAtualTituloDgi + 1)));
		//alert(novoTituloDgi);
	}
		

	if ( sentidoAnimacaoTitulo == "DIREITA" )
	{
		posicaoAtualTituloDgi++;
		if ( posicaoAtualTituloDgi > limiteLetraDestaque ) sentidoAnimacaoTitulo = "ESQUERDA";
	}
	else
	{
		posicaoAtualTituloDgi--;
		if ( posicaoAtualTituloDgi == 0 ) sentidoAnimacaoTitulo = "DIREITA";		
	}
		
	objetoTituloDgi.innerHTML = novoTituloDgi;
	setTimeout("animacaoTititulo3()",25);		
}




/**
Nome: animacaoTititulo4
Animação do titulo da DGI
Somente é Apresentado quando o menu compacto estiver sendo exibido
*/
function animacaoTititulo4()
{	
	var novoTitulo="";
	var corDestaqueInicio="<font color='" + corAtualDestaque + "'>";
	var corSemDestaqueInicio="<font color='" + corSemDestaque + "'>";

	var corDestaqueFim="</font>";
	var corSemDestaqueFim="</font>";		
			
	novoTituloDgi=corSemDestaqueInicio + corDestaqueInicio + tituloDgiAtual.substr(0,posicaoAtualTituloDgi+1) + corDestaqueFim + 
				  tituloDgiAtual.substr(posicaoAtualTituloDgi + 1, (tamanhoTituloDGI -1)) + corSemDestaqueFim;
	
	posicaoAtualTituloDgi++;
	if ( posicaoAtualTituloDgi == tamanhoTituloDGI ) 
	{
		if ( corAtualDestaque == "#FC3" )
		{
			corAtualDestaque = "#FFF";
			corSemDestaque = "#FC3";
		}
		else
		{
			corAtualDestaque = "#FC3";
			corSemDestaque = "#FFF";
		}
						
		posicaoAtualTituloDgi=0;
	}
	
	objetoTituloDgi.innerHTML = novoTituloDgi;
	setTimeout("animacaoTititulo4()",30);		
}




/**
Nome: trocaTextoMenu
Permite alterar o texto dos itens de menu de "Ocultar"  para "Exibir" e vice-versa, conforme
a situação atual dos elementos referenciados

parametroMenu		Identificação (Id) do menu que terá seu texto alterado
parametroTextoMenu	Texto comum ao menu nas situações em que estiver oculto ou em exibição

*/
function trocaTextoMenu(parametroMenu, parametroTextoMenu)
{
	var hTextoOpcaoAtual = document.getElementById(parametroMenu); // Handle para o objeto
	var texto = hTextoOpcaoAtual.innerHTML; // Texto do objeto
	
	var textoOcultar = 'Ocultar ' + parametroTextoMenu;
	var textoExibir  = 'Exibir &nbsp;&nbsp; ' + parametroTextoMenu;
	
	// Altera o texto conforme valor do texto atual
	hTextoOpcaoAtual.innerHTML = (texto == textoOcultar)?textoExibir:textoOcultar;
}






/**
Nome: trocaTextoMenu
Permite alterar o texto dos itens de menu de "Ocultar"  para "Exibir" e vice-versa, conforme
a situação atual dos elementos referenciados

parametroMenu		Identificação (Id) do menu que terá seu texto alterado
parametroTextoMenu	Texto comum ao menu nas situações em que estiver oculto ou em exibição

*/
/*
function trocaTextoMenuCamadas(parametroMenu, parametroTextoMenu)
{
	var hTextoOpcaoAtual = document.getElementById(parametroMenu); // Handle para o objeto
	var texto = hTextoOpcaoAtual.innerHTML; // Texto do objeto
	
	var textoOcultar = '&nbsp; ' + parametroTextoMenu + ' &nbsp;&nbsp;<i class="icon-ok"></i>';
	var textoExibir  = '&nbsp; ' + parametroTextoMenu;
	
	// Altera o texto conforme valor do texto atual
	hTextoOpcaoAtual.innerHTML = (texto == textoOcultar)?textoExibir:textoOcultar;
}
*/






/**
Nome: inicializacao
Executa funções no momento que a página é carregada.
*/
function inicializacao()
{
	
	var caixaOpcoes = window.document.getElementById("caixaopcoes");
	var vartitulotimeout = document.getElementById("titulotimeout");
	var varcabecalhotimeout = document.getElementById("cabecalhotimeout");
	
	var barraBrasil = window.document.getElementById("barra-brasil");
	
	
	
	var cabecalhoDisplay = document.getElementById("cabecalho").style.display;
	var cabecalhoEstaVisivel=((cabecalhoDisplay.toLowerCase() == 'block')?true:false);
	
	if ( estaLogado )
	{
		if ( cabecalhoEstaVisivel )
		{
			vartitulotimeout.style.display = 'none';
			varcabecalhotimeout.style.display = 'block';
		}
		else
		{			
			vartitulotimeout.style.display = 'block';
			varcabecalhotimeout.style.display = 'none';
		}

	}
	else
	{
		vartitulotimeout.style.display = 'none';
		varcabecalhotimeout.style.display = 'none';		
	}	
	
	//barraBrasil.style.background="#003366";
	//barraBrasil.style.background="#009900";
	
	alteraDimensoes();
	caixaOpcoes.style.display='block';
	//relogioDigital();
	animacaoTititulo4();
	//abreNota();
}





/**
Nome: chamaAlternaFerramentaDesenho
Permite executar a função alternaFerramentaDesenho que se encontra definida no documento
carregado no fram "fmosaico". Esta função permite exibir ou ocultar a caixa com a ferramenta
de desenho da área para pesquisa de imagens relacionadas à essa área
*/
function chamaAlternaFerramentaDesenho()
{
	top.frames['fmosaico'].alternaFerramentaDesenho(); 
}




/**
Nome: detalhesDaImagem
Obtém informações da imagem informada como parâmetro e em seguida apresenta essas informações em uma janela

parametroImagem		Parametro contendo informações relacionadas à imagem a ser apresentada
*/
function detalhesDaImagem( parametroImagem )
{
	var sceneID = parametroImagem[1]; // Obtém o sceneId da Imagem
	detalhesDaImagemPorSceneId(sceneID); // Chama a função responsável por realizar a busca pelo registro e apresentar a janela
}






/**
Nome: detalhesDaImagemPorSceneId
Obtém informações da imagem informada como parâmetro e em seguida apresenta essas informações em uma janela

parametroSceneId	Parametro contendo o SceneId da imagem a ser apresentada
*/
function detalhesDaImagemPorSceneId( parametroSceneId )
{
	obtemDadosDetalhes(parametroSceneId)	; // Executa a função responsável por oter dados referente ao SceneId 
	$('#wmdetalhesimagem').modal('show'); // Apresenta a janela contendo as informações obtidas pela função acima
}





/**
Nome: alteraTextoSateliteAtual
Apresenta o satélite relacionado a imagem em exibição no momento que o mouse é posicionado sobre a mesma
Este texto fica logo após o texto referente ao horário atual

parametroNomeSatelite	Parametro contendo o nome do satélite a ser exibido
*/
function alteraTextoSateliteAtual( parametroNomeSatelite )
{	
	var objetoNomeSatelite = document.getElementById('sateliteatual');
	var objetoTituloSatelite = document.getElementById('titulosatelite');
	
	var nomeSatelite = '[-]';
	
	switch (parametroNomeSatelite.toUpperCase())
	{
		case 'A1':
			nomeSatelite='[AQUA ]';
			break;
			
		case 'T1':
			nomeSatelite='[TERRA]';
			break;	
			
		case 'NPP':
		case 'S-NPP':
			nomeSatelite='[S-NPP]';
			break;

			
		case 'UKDMC':
		case 'UKDMC2':
		case 'UK-DMC2':
			nomeSatelite='[UK-DMC 2]';
			break;


		case 'RE1':
		case 'RAPIDEYE1':
			nomeSatelite='[RAPIDEYE-1]';
			break;

		case 'RE2':
		case 'RAPIDEYE2':
			nomeSatelite='[RAPIDEYE-2]';
			break;

		case 'RE3':
		case 'RAPIDEYE3':
			nomeSatelite='[RAPIDEYE-3]';
			break;

		case 'RE4':
		case 'RAPIDEYE4':
			nomeSatelite='[RAPIDEYE-4]';
			break;

		case 'RE5':
		case 'RAPIDEYE5':
			nomeSatelite='[RAPIDEYE-5]';
			break;

	}
	objetoNomeSatelite.innerHTML = nomeSatelite;
	objetoTituloSatelite.innerHTML = nomeSatelite;
}




/**
Nome: alteraTextoZoomAtual
Apresenta o Zoom atual do mapa no final do menu de opções

parametroZoomAtual	Parametro contendo o valor atual do Zoom no mapa
*/
function alteraTextoZoomAtual(parametroZoomAtual)
{	
	var objetoZoomAtual = document.getElementById('zoomatual');
	var tituloZoomAtual = document.getElementById('titulozoom');
	
	objetoZoomAtual.innerHTML = parametroZoomAtual;
	tituloZoomAtual.innerHTML = parametroZoomAtual.toUpperCase();
	
}





/**
Nome: obtemDadosDetalhes
Obtém os dados da imagem solicitada e atribui o texto HTML retornado ao elemento SPAN responsável
por exibir os detalhes desta imagem

parametroSceneId	Parametro contendo sceneId da imagem
*/
function obtemDadosDetalhes(parametroSceneId)
{
	
	var objetoHTTPDetalhes;
	var objetoDestinoDados;
	
	objetoDestinoDados = document.getElementById('detalhesimagemselecionada');
	
	
	if (window.ActiveXObject)
	{
		objetoHTTPDetalhes=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTPDetalhes=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	

	objetoHTTPDetalhes.onreadystatechange=function()
	{			
		if (objetoHTTPDetalhes.readyState == 4 && objetoHTTPDetalhes.status == 200)
		{
			var retorno = objetoHTTPDetalhes.responseText;
			objetoDestinoDados.innerHTML=retorno;
		}
	}
	
	objetoHTTPDetalhes.open("GET","detalhesimagem.php?p=1&SceneId=" + parametroSceneId, true);
	objetoHTTPDetalhes.send();
	
}



/**
Nome: exibeMensagemGenerica
Exibe uma mensagem simples em uma janela modal

parametroMensagem	Parametro contendo a mensagem a ser exibida
*/
function exibeMensagemGenerica( parametroMensagem )
{
	var objetoMensagem = document.getElementById('wmtextomensagem');// Obtem o objeto que ira conter a mensagem
	objetoMensagem.innerHTML = 	parametroMensagem; // Atribui a mensagem ao objeto
	
	$('#wmmensagem').modal('show');	 // Apresenta a janela com a mensagem
	return false;
}





/**
Nome: exibeMensagemCarrinho
Exibe uma mensagem simples em uma janela modal

parametroMensagem	Parametro contendo a mensagem a ser exibida
*/
function exibeMensagemCarrinho( parametroMensagem )
{
	var objetoMensagem = document.getElementById('wmtextoaddtocart');	// Obtem o objeto que ira conter a mensagem
	objetoMensagem.innerHTML = 	parametroMensagem;// Atribui a mensagem ao objeto
	
	$('#wmaddtocart').modal('show');// Apresenta a janela com a mensagem
	return false;
}





/**
Nome: selecionaPaginaResultados
Função que selecona e apresenta a página de resultados da pesquisa realizada.
*/
function selecionaPaginaResultados()
{
	$('#myTab a[href=#resultados]', document).tab('show');
}



/**
Nome: selecionaPaginaCidades
Função que selecona e apresenta a página de resultados da pesquisa por municípios 
*/
function selecionaPaginaCidades()
{
	$('#myTab a[href=#cidades]', document).tab('show');
}




/**
Nome: exibeOcultaCabecalho
Permite exibir ou ocultar o cabeçalho expandido ou o compacto, alternando entre os modos
de cabeçalho
*/
function exibeOcultaCabecalho()
{
	
	// Declara variáveis para referenciar os objetos a serem ocultados ou exibidos
	
	var divCabecalho = document.getElementById("cabecalho");
	var statusExibicao = divCabecalho.style.display;
	
	var iconemenu = document.getElementById("iconemenu");
	//var iconepesquisa = document.getElementById("iconepesquisa");	
	var iconemenusuperior = document.getElementById("iconemenusuperior");
	
	var titulodgi = document.getElementById("titulodgi");
	var titulocatalogo = document.getElementById("titulocatalogo");
	
	var titulozoom = document.getElementById("titulozoom");
	var titulosatelite = document.getElementById("titulosatelite");
	var titulousuario = document.getElementById("titulousuario");
	var titulotimeout = document.getElementById("titulotimeout");
	var cabecalhotimeout = document.getElementById("cabecalhotimeout");
	
	
	 
	// Icones referentes à operação de conrole de acesso e carrinho de compras
	/*
	var iconeacessar = document.getElementById("iconeacessar");
	var iconealterarsenha = document.getElementById("iconealterarsenha");
	var iconerealizarcadastro = document.getElementById("iconerealizarcadastro");

	var iconecarrinho = document.getElementById("iconecarrinho");
	var iconehistoricopedidos = document.getElementById("iconehistoricopedidos");
	*/

	// Verifica se o cabeçalho e o menu de opção estão ocultos ou visiveis
		
	if ( statusExibicao == 'none' )
	{
		// Se estiver oculto o mesmo deve ser reexibido	
		divCabecalho.style.display = 'block';	
		iconemenu.innerHTML = '<i id="iconeexibirocultarmenu" class="icon-double-angle-up" style="color:#FFF" data-toggle="tooltip" title="Clique para Ocultar o Cabeçalho e o Menu de Opções" data-trigger="hover" data-animation="true" data-placement="bottom"></i>';
		
		titulodgi.style.display = 'none';
		titulocatalogo.style.display = 'none';
		titulozoom.style.display = 'none';
		titulosatelite.style.display = 'none';
		titulousuario.style.display = 'none';		
		titulotimeout.style.display = 'none';
		
		iconemenusuperior.style.display = 'none';
		//iconepesquisa.style.display = 'none';
		
		/*
		iconeacessar.style.display = 'none';
		iconealterarsenha.style.display = 'none';
		iconerealizarcadastro.style.display = 'none';
		
		iconecarrinho.style.display = 'none';
		iconehistoricopedidos.style.display = 'none';
		*/
		if ( estaLogado ) 
		{
			cabecalhotimeout.style.display = 'block';
		}
		else 
		{
			cabecalhotimeout.style.display = 'none';
		}
		
		
		
	}
	else
	{
		// Se estiver visivél o mesmo deve ser ocultado
		
		divCabecalho.style.display = 'none';		
		iconemenu.innerHTML = '<i id="iconeexibirocultarmenu" class="icon-double-angle-down" style="color:#FC3" data-toggle="tooltip" title="Clique para Exibir o Cabeçalho e o Menu de Opções" data-trigger="hover" data-animation="true" data-placement="bottom"></i>';

		titulodgi.style.display = 'block';
		titulocatalogo.style.display = 'block';	
		titulozoom.style.display = 'block';
		titulosatelite.style.display = 'block';	
		iconemenusuperior.style.display = 'block';
		titulousuario.style.display = 'block';
		
		if ( estaLogado ) titulotimeout.style.display = 'block';
		
			
		//iconepesquisa.style.display = 'block';
		
		/*
		iconeacessar.style.display = 'block';
		iconealterarsenha.style.display = 'block';
		iconerealizarcadastro.style.display = 'block';
		
		iconecarrinho.style.display = 'block';
		iconehistoricopedidos.style.display = 'block';
		*/
	}
	
	
	
}






/**
* Nome: adicionarAoCarrinho
* Permite adicionar a imagem indiretamente no carrinho de imagens 
*
* parametroImagem	Vetor com informações da imagem a ser adicionada ao carrinho
*/
function adicionarAoCarrinho( parametroImagem )
{

	var objetoHTTP; // Objeto responsável por gerenciar s requisições HTTP
	
	
	if (window.ActiveXObject)
	{
		objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	
	
	
	

	//alert( "parametroImagem[1] = " + parametroImagem[1]);

	objetoHTTP.onreadystatechange=function()
	{

		// Caso seja executado com sucesso
		if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
		{
			
			var retorno = objetoHTTP.responseText;
			

			retorno = retorno.trim().toUpperCase();
			
			//alert(retorno);
			
			if ( retorno == "OK" )
			{
				
				var mensagem = '<table border="0"  style="background-color:"#FFFFFF">' +
				'<tr>' + 
				'<td width="140"><img src="/QUICKLOOK/QUICKLOOK/' + parametroImagem[2].toUpperCase() + "/" +   parametroImagem[3].toUpperCase() + "/QL_" + 
				parametroImagem[1] + '_MIN.png" border=0></td>' +
				
				'<td>' +
				'Imagem <b>' + parametroImagem[1] + '</b> adicionada ao carrinho.&nbsp;' +
				'Continue selecionado imagens ou finalize seu pedido para ' +
				'que o mesmo seja processado.' +
				'</td>' +
				
				'</tr>' +
				'</table>';
				
				
				//top.exibeMensagemCarrinho( mensagem );
				
				//top.atualizaNumeroItensCarrinho();
				top.atualizaNumeroItensCarrinho();
				
			}

			if ( retorno == "SCENEIDNAOEXISTE" )
			{
				//alert("parametroImagem[1] = " + parametroImagem[1] );
				var mensagem = 'Imagem <b>' + parametroImagem[1] + '</b> informada não existe no cadastro<br>' +
							   'de imagens do Catálogo. Favor verificar.';
				
				top.exibeMensagemCarrinho( mensagem );
			}


		}
		/*
		else
		{
			registros.innerHTML="Erro ao realizar pesquisa.";	
		}
		*/
			
	}

	objetoHTTP.open("GET",'addtocart.php?ACTION=CART&INDICE=' + parametroImagem[1], true);
	objetoHTTP.send();


				
	
}





/**
Nome: atualizaIdleTime
Função responsável por reinicializar (Atribuir vaor zero) à variável global idleTime
É utilizada por funções de outros frames, permitindo que os mesmos a reinicializem
*/
function atualizaIdleTime()
{
	idleTime = 0;
}



/**
Nome: chamainfoWindowImagemToggle
Permite executar a função infoWindowImagemToggle definida na frame "fmosaico", que permite
alterar o valor da variável exibeInfoWindowImagem, e dessa forma define se a chame de informações
curtas será exibida ou não quando o mouse esiver sobre a mesma
*/
function chamainfoWindowImagemToggle()
{		
	top.frames['fmosaico'].infoWindowImagemToggle();		
}





/**
Nome: loginRealizado
Função utilizada para apresentar o texto referente ao usuário logado. O texto é atualizado nos locais
referente aos modos de apresentação do cabeçalho (Compacto e expandido/normal).
Os locais definidos são identificados pelos id´s titulousuario e cabecalhousuario

parametroUsuario	Parametro texto com o nome do usuário que acabou de se logar no sistema
*/
function loginRealizado( parametroUsuario)
{		
	
	var divCabecalho = document.getElementById("cabecalho");
	var statusExibicao = (divCabecalho.style.display).toLowerCase();
	
	
	//var stringAcessoUsuarioTopo='<i class="icon-user" style="color:#FC3;font-size:15px"></i><font style="color:#FC3;font-size:12px;">&nbsp;&nbsp;' + parametroUsuario + '</font>';	
	var stringAcessoUsuarioTopo='<i class="icon-user" style="color:#FC3;font-size:15px"></i><font style="color:#FC3;font-size:12px;">&nbsp;&nbsp;' + parametroUsuario + '</font>';	

	var stringAcessoUsuario='<i class="icon-user" style="color:#FC3;font-size:22px"></i><font style="color:#FC3;font-size:16px;">&nbsp;&nbsp;' + parametroUsuario + '</font>';	
	
	var titulousuario = document.getElementById("titulousuario");
	var cabecalhousuario = document.getElementById("cabecalhousuario");
	
	
	titulousuario.innerHTML = 	stringAcessoUsuarioTopo;
	cabecalhousuario.innerHTML = stringAcessoUsuario;
		
	estaLogado = true;
	
	var titulotimeout = document.getElementById("titulotimeout");
	var cabecalhotimeout = document.getElementById("cabecalhotimeout");

	
	if ( statusExibicao == 'none' )
	{
		titulotimeout.style.display = 'block';
	}
	else
	{
		titulotimeout.style.display = 'none';		
	}

	cabecalhotimeout.style.display = 'block';		
	
	// Oculta a janela de login após sucesso no acesso
	$('#wmacesso').modal('hide');	
	
	//top.frames['fnovasenha'].location="lostpwd.php";
	//$('#wmnovasenha').modal('show');	
	
	
	top.frames['fmosaico'].liberaImagens();
	top.frames['fpesquisa'].location="pesquisa.php";
	top.frames['fresultado'].location="resultado.php";
	
	//top.location="/catalogo/"
		
	return false;
			
}




/**
Nome: esqueceuSenhaFechaLogin
Função utilizada quando o link referente ao esquecimento da senha é clicado. Apos o mesmo ser clicado
a janela de acesso/login é fechada e a janela referente à solicitação de uma nova senha é realizada
*/
function esqueceuSenhaFechaLogin()
{		
	$('#wmacesso').modal('hide');	
	
	top.frames['fnovasenha'].location="lostpwd.php";
	$('#wmnovasenha').modal('show');		
	return false;
			
}






/**
Nome: esqueceuSenhaFechaLogin
Função utilizada quando o link referente ao esquecimento da senha é clicado. Apos o mesmo ser clicado
a janela de acesso/login é fechada e a janela referente à solicitação de uma nova senha é realizada
*/
/*
function verPedido(parametroPedido)
{		
	//$('#wmhistorico').modal('hide');		
	top.frames['fverpedido'].location="historyDetails.php?reqId=" + parametroPedido;
	$('#wmverpedido').modal('show');		
	return false;
			
}
*/


function abreNota()
{
	$('#wmnotas').modal('show');
}



/*
function toggleCamadas(parametroIndiceEstado, parametroIdTexto )
{
		top.frames['fmosaico'].toggleLayerEstado(parametroIndiceEstado, parametroIdTexto);		
}
*/



function toggleDiaNoite()
{
		top.frames['fmosaico'].toggleCamadaDiaNoite();
}



function toggleCBERS()
{
		top.frames['fmosaico'].toggleGradeCBERS();
}



function toggleLANDSAT()
{
		top.frames['fmosaico'].toggleGradeLandSat();
}




function toggleRAPIDEYE()
{
		top.frames['fmosaico'].toggleGradeRapidEye();
}





</script>
<!-- Fim  da seção de códigos Javascript  -->





<!-- Inicio  da seção de códigos jQuery  -->
<script>

$(function (){

	// Exibe/Oculta a caixa de opções referente às páginas de pesquisa, resultados e instruções
	// Acionado através do menu "Exibir/Ocultar Janela de Pesquisa"
	$("#mepesquisar").live('click',function(e) {
		e.preventDefault();				
		trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa'); // Alterna o texto do menu
		//trocaTextoMenu('msjanelapesquisatexto', 'Janela de Pesquisa');		 
		//$("#caixaopcoes").slideToggle(800, 'swing'); // Exibe/Oculta a caixa de opções
		$("#caixaopcoes").animate({width: 'toggle'});
		$("#divocultarJanelaPesquisa").animate({width: 'toggle'},200);
		
		if ( $("#divexibirJanelaPesquisa").css('display') == 'none' )
			$("#divexibirJanelaPesquisa").css('display', 'block');
		else
			$("#divexibirJanelaPesquisa").css('display', 'none');
		
	});
	



		
			


	// Exibe/Oculta a caixa de desenho, que permite ao usuário desenhar uma área retangular 
	// Acionado através do menu "Exibir/Ocultar Caixa de Desenho" no menu expandido e no menu
	// compacto
	$('#meferramenta, #mscaixadesenho').live('click',function(e) {
		e.preventDefault();
		trocaTextoMenu('itemmenuferramenta', 'Caixa de Desenho');
		trocaTextoMenu('mscaixadesenhotexto', 'Caixa de Desenho');
		chamaAlternaFerramentaDesenho();	
	});




	// Exibe/Oculta a caixa de opções referente às páginas de pesquisa, resultados e instruções
	// Acionado através do botão existente na própria janela referente à caixa de opções e no item de
	// menu do menu compacto
	//
	/*	
	$("#ocultarJanelaPesquisa, #msjanelapesquisa").live('click',function(e) {
		//e.preventDefault();
		trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa');		
		trocaTextoMenu('msjanelapesquisatexto', 'Janela de Pesquisa');		
		//$("#caixaopcoes").slideToggle(800, 'swing'); 
		
		
		$("#ocultarJanelaPesquisa").toggle(
		function()
		{
        	$("#caixaopcoes").animate({left:'-422px'}, {queue: false, duration: 500});
        	$("#divocultarJanelaPesquisa").animate({left:'0px'}, {queue: false, duration: 500});    	
		},		
		function()
		{
        	$("#caixaopcoes").animate({left:'1px'}, {queue: false, duration: 500});
        	$("#divocultarJanelaPesquisa").animate({left:'423px'}, {queue: false, duration: 500});    	
		} );
		
		
		// Comentados os códigos abaixo
		
		$("#caixaopcoes").animate({width: 'toggle'});
		$("#divocultarJanelaPesquisa").animate({width: 'toggle'},200);
		
		if ( $("#divexibirJanelaPesquisa").css('display') == 'none' )
		{
			$("#caixaopcoes").css('opacity', 1);
			$("#divexibirJanelaPesquisa").css('display', 'block');
		}
		else
		{
			$("#divexibirJanelaPesquisa").css('display', 'none');
			$("#caixaopcoes").css('opacity', 0.88);
		}
		
		// Até aqui
		
		
	});
	
    */
   






	// Exibe/Oculta a caixa de opções referente às páginas de pesquisa, resultados e instruções
	// Acionado através do menu "Exibir/Ocultar Janela de Pesquisa"
	$("#exibirJanelaPesquisa").live('click',function(e) {
		e.preventDefault();				
		trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa'); // Alterna o texto do menu
		//trocaTextoMenu('msjanelapesquisatexto', 'Janela de Pesquisa');		 
		//$("#caixaopcoes").slideToggle(800, 'swing'); // Exibe/Oculta a caixa de opções
		
		if ( $("#divexibirJanelaPesquisa").css('display') == 'none' )
		{
			$("#caixaopcoes").css('opacity', 1);
			$("#divexibirJanelaPesquisa").css('display', 'block');
		}
		else
		{
			$("#divexibirJanelaPesquisa").css('display', 'none');
			$("#caixaopcoes").css('opacity', 0.88);
		}

		$("#caixaopcoes").animate({width: 'toggle'},400);
		$("#divocultarJanelaPesquisa").animate({width: 'toggle'},200);
		
	});
	
	



	// Exibe/Oculta a caixa de opções referente às páginas de pesquisa, resultados e instruções
	// Acionado através do menu "Exibir/Ocultar Janela de Pesquisa"
	$("#exibirJanelaPesquisa").live('mouseover',function(e) {
		e.preventDefault();				
		
		//$("#divexibirJanelaPesquisa").animate({left: '2px'}, 300);
		//$("#exibirJanelaPesquisa").animate({left: '2px'}, 300);
		//$("#exibirJanelaPesquisa").css('font-size', '42px');
		$("#iconeExibirJanelaPesquisa").css('text-shadow', '2px 2px 2px #FFFFFF');		
		
		/*
		$("#divexibirJanelaPesquisa").css('left', '1px');
		$("#exibirJanelaPesquisa").css('left', '1px');
		$("#exibirJanelaPesquisa").css('text-shadow', '2px 2px 2px #DDDDDD');		
		*/
	});
	
	// Exibe/Oculta a caixa de opções referente às páginas de pesquisa, resultados e instruções
	// Acionado através do menu "Exibir/Ocultar Janela de Pesquisa"
	$("#exibirJanelaPesquisa").live('mouseout',function(e) {
		e.preventDefault();				
		
		//$("#divexibirJanelaPesquisa").animate({left: '-5px'}, 500);
		//$("#exibirJanelaPesquisa").animate({left: '-5px'}, 500);
		//$("#exibirJanelaPesquisa").css('font-size', '36px');
		$("#iconeExibirJanelaPesquisa").css('text-shadow', '');		

		/*		
		$("#divexibirJanelaPesquisa").css('left', '-3px');
		$("#exibirJanelaPesquisa").css('left', '-3px');
		$("#exibirJanelaPesquisa").css('text-shadow', '');		
		*/
	});
	
	



	// Exibe/Oculta a exibição da janela de informações da imagem quando o mouse passa pela imagem
	// Acionado através do menu "Exibir/Ocultar Informações da Imagem" no menu expandido e no menu
	// compacto
	$('#meinfowindow, #msinfowindow').live('click',function(e) {
		e.preventDefault();
		trocaTextoMenu('itemmenuinfowindow', 'Informações da Imagem');
		trocaTextoMenu('msinfowindowtexto', 'Informações da Imagem');
		chamainfoWindowImagemToggle();	
	});




	// Exibe/oculta camadas de deivisão política
	






	


	// Ignora o clique sobre o menu contendo a hora atual
	/*
	$("#menuhoraatual").live('click',function(e) { 			  
		 e.preventDefault();
	});
	*/


	
	// Ignora o clique sobre os menus identificados como idiomaselecionado, menuzoomatual e menusateliteatual
	$('#idiomaselecionado, #menuzoomatual, #menusateliteatual').live('click',function(e) {			
		e.preventDefault();
	});
	



	
	// Ignora o clique sobre o icone de ajuda 
	$('#btnajuda').live('click',function(e) {			
		e.preventDefault();
	});
	
		
	
	/*
	// Ignora o clique sobre o menu contendo a hora atual
	$('#idiomaselecionado').live('click',function(e) {			
		e.preventDefault();
	});
	

	// Ignora o clique sobre o menu contendo informações do Zoom atual 
	$("#menuzoomatual").live('click',function(e) { 			  
		 e.preventDefault();
	});
	

	// Ignora o clique sobre o menu contendo informações do satélite da imagem clicadal 
	$("#menusateliteatual").live('click',function(e) { 			  
		 e.preventDefault();
	});
	*/
	

	// Apresenta a janela para que o usuáro possa realizar login no catálogo e assim adicionar
	// imagens ao carrinho de imagens
	// Acionados pelos objetos de id smenuacessar e msrealizarlogin
	$('#smenuacessar, #msrealizarlogin, #btnlogin').live('click',function(e) {
		
		if ( estaLogado )
		{
			var mensagemEstaLogado="<b>Usuário já está logado</b><br>" +
			"Realize logout antes de acessar com um novo usuário.";
	
			exibeMensagemGenerica(mensagemEstaLogado);		
			e.preventDefault();
		}
		else
		{
			top.frames['flogin'].location="login.php";	
			$('#wmacesso').modal('show');	
			e.preventDefault();
		}
	});


	
	// Apresenta a janela para que o usuáro possa solicitar nova senha para acesso ao catálogo 
	// Acionados pelos objetos de id smenualterarsenha e msnovasenha
	$('#smenualterarsenha, #msnovasenha').live('click',function(e) {
		top.frames['fnovasenha'].location="lostpwd.php";	
		$('#wmnovasenha').modal('show');	
		e.preventDefault();
	});



	
		
	


	// Apresenta a janela para que o usuáro possa realizar seu cadastro no catálogo ou realizar a alteração
	// de seus dados ou alterar a senha
	// Acionados pelos objetos de id smenucadastro e msrealizarcadastro
	$('#smenucadastro, #msrealizarcadastro').live('click',function(e) {
		top.frames['fcadastro'].location="register.php";			
		$('#wmcadastro').modal('show');	
		e.preventDefault();
	});
	


	// Apresenta a janela para que o usuáro possa realizar seu cadastro no catálogo ou realizar a alteração
	// de seus dados ou alterar a senha
	// Acionados pelos objetos de id smenuhistorico e mshistoricopedidos
	$('#smenuhistorico, #mshistoricopedidos').live('click',function(e) {
		
		
		
		if ( !estaLogado )
		{
			var mensagemNaoEstaLogado="<b>Usuário deve estar logado</b><br>" +
			"Para visualizar o histórico de pedidos é necessário realizar log in.";
	
			exibeMensagemGenerica(mensagemNaoEstaLogado);		
			e.preventDefault();
		}
		else
		{			
			top.frames['fhistorico'].location="history.php";			
			$('#wmhistorico').modal('show');	
			e.preventDefault();
		}
	});


	// Apresenta a janela para que o usuáro possa consultar os itens do carrinho de imagens
	// Nesta janela tambáme é possível finalizar o pedido
	// Acionados pelos objetos de id smenucarrinho e msvisualizarcarrinho
	$('#smenucarrinho, #msvisualizarcarrinho, #btncarrinho').live('click',function(e) {	
		e.preventDefault();
		apresentaCarrinhoDeImagens();
	});	
	
	


	// Ícone para alternar o cabeçalho e menu entre os modos expandido/normal e compacto
	// Quando o objeto é clicado são executadas as funcções para ocultar ou exibir o cabeçalho expandido 
	// e realizar redimensionamento dos elementos da página
	// Acionados pelo objeto de id menutopo
	$('#menutopo').live('click',function(e) {	
		exibeOcultaCabecalho();
		alteraDimensoes();				
		e.preventDefault();					
	});	
	
	


	// Ícone que permite ocultar a área de pesquisa/resultados do site
	// Quando o objeto é clicado a janela container das páginas de pesquisa e resultados é ocultada  
	// Acionados pelo objeto de id menupesquisa
	$('#menupesquisa').live('click',function(e) {			
		alteraDimensoes();				
		$("#caixaopcoes").slideToggle(800); 
		trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa');
		e.preventDefault();					
	});	
	
	
	
	
	
	/*
	$('#camadatempo').live('click',function(e) {			
		top.frames['fmosaico'].toggleWeatherLayer();
		e.preventDefault();					
	});	
	*/

	
	
	/*

	$('#camadanuvens').live('click',function(e) {			
		top.frames['fmosaico'].toggleCloudLayer();
		e.preventDefault();					
	});	
	
	
	
	
	$('#camadabrasil').live('click',function(e) {			
		top.frames['fmosaico'].toggleLayerBrasil();
		e.preventDefault();					
	});	
	
	*/
	
	
	


	// Icone Submenu
	/*
	$('#submenuprincipal').live('mouseover',function(e) {	
		$("#itenssubmenuprincipal").show(); 
		e.preventDefault();					
	});	
	*/


	/*
	$('#menupopup').live('mouseover',function(e) {	
		$("#menupopup").show(); 
		e.preventDefault();					
	});	
	
	$('#menupopup').live('mouseout',function(e) {	
		$("#menupopup").hide(); 
		e.preventDefault();					
	});	
	*/










	// Tooltips 
	
	// Apresenta uma tooltip quando o mouse passar sobre o menu "Home"			
	$('#menuhome').live('mouseover',function(e) {
		$('#menuhome').tooltip('show');	
	});


	// Apresenta uma tooltip quando o mouse passar sobre o menu "Ajuda"			
	$('#menuajuda').live('mouseover',function(e) {
		$('#menuajuda').tooltip('show');	
	});


	// Apresenta uma tooltip quando o mouse passar sobre o menu "Sair"			
	$('#menusair').live('mouseover',function(e) {
		$('#menusair').tooltip('show');	
	});


	// Apresenta uma tooltip quando o mouse passar sobre o icone para ocultar a caixa de
	// opções existente na própria janela da caixa de opções
	$('#ocultarJanelaPesquisa').live('mouseover',function(e) {
		$('#ocultarJanelaPesquisa').tooltip('show');
		$("#ocultarJanelaPesquisa").css('text-shadow', '3px 3px 3px #CCCCCC');			
		//$("#ocultarJanelaPesquisa").css('font-size', '36px');			
		$("#ocultarJanelaPesquisa").animate({'font-size': '36px'}, 300);			
	});
	
	
	// Apresenta uma tooltip quando o mouse passar sobre o icone para ocultar a caixa de
	// opções existente na própria janela da caixa de opções
	$('#ocultarJanelaPesquisa').live('mouseout',function(e) {
		$('#ocultarJanelaPesquisa').tooltip('hide');
		$("#ocultarJanelaPesquisa").css('text-shadow', '');			
		//$("#ocultarJanelaPesquisa").css('font-size', '32px');			
		$("#ocultarJanelaPesquisa").animate({'font-size': '32px'}, 300);			
	});
	
	

	// Apresenta uma tooltip quando o mouse passar sobre o icone para ocultar a caixa de
	// opções existente na própria janela da caixa de opções
	$('#exibirJanelaPesquisa').live('mouseover',function(e) {
		$('#exibirJanelaPesquisa').tooltip('show');				
	});
	
	
	// Apresenta uma tooltip quando o mouse passar sobre o icone para ocultar ou exibir a
	// o cabeçalho e o menu
	$('#iconeexibirocultarmenu').live('mouseover',function(e) {
		$('#iconeexibirocultarmenu').tooltip('show');				
	});
		
	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto titulotimeout, que contém
	// o texto referente ao tempo restante para timeout da sessão atual
	$('#titulotimeout').live('mouseover',function(e) {
		$('#titulotimeout').tooltip('show');				
	});
	
	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto cabecalhotimeout, que contém
	// o texto referente ao tempo restante para timeout da sessão atual
	$('#cabecalhotimeout').live('mouseover',function(e) {
		$('#cabecalhotimeout').tooltip('show');				
	});
	

	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto iconelogin, que contém
	// o ícone para acesso à tela de login
	$('#iconelogin').live('mouseover',function(e) {
		$('#iconelogin').tooltip('show');				
	});
	
	
	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto iconecarrinho, que contém
	// o ícone para acesso à tela que contém as imagens selecionadas 
	$('#iconecarrinho').live('mouseover',function(e) {
		$('#iconecarrinho').tooltip('show');				
	});
	

	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto iconeajuda, que contém
	// o ícone para acesso à tela de ajuda
	$('#iconeajuda').live('mouseover',function(e) {
		$('#iconeajuda').tooltip('show');				
	});
	
	
	// Apresenta uma tooltip quando o mouse passar sobre o objeto iconesair, que contém
	// o ícone para realizar logout do Catálogo
	$('#iconesair').live('mouseover',function(e) {
		$('#iconesair').tooltip('show');				
	});
	
	
	

});


</script>
<!-- Fim da seção de códigos jQuery  -->


<script>

$(function(){


	//intervalo de tempo e a funão a ser executada períodcamente
	var idleInterval = setInterval(timerIncrement, 1000);
	
	
	// Função resposável por realizar o monitoramento do empo de conexão, e se necess´´ario
	// realiza a desconexão do usuário do Catálogo
	function timerIncrement()
	{
		idleTime++;
		
		// Atualiza o texto de timeout do relógio com o tempo restante para desconexão
		atualizaTextoTimeout(tempoConexaoTimeOut, idleTime);
		
		// Se o tempo decorrido sem aivitidade (Idle) é igual ao tempo a sera 
		if ((idleTime == tempoConexaoTimeOut) && ( estaLogado == true ))
		{
			// Executa as tarefas necessarias para finaliza a sessão atual
			executaTarefasTimeOut();			  
		}
	
	}


	// Permite zerar o contador de tempo ocioso
	// Necessário para que a quatidade de segundos antes de realizar a desconexão do usuário seja reiniciada
	// Funão acionada pelos seguintes eventos que ocorrem: click, dblclick, keypress, mouseenter, select, scroll, resize,
	// mouseover, mousemove, mouseout, mouseenter, blur, focus
	$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur focus", function(e)
	{
	  idleTime = 0;	  
	});



	$("#divocultarJanelaPesquisa").toggle(

		function()
		{
			trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa');		
			//trocaTextoMenu('msjanelapesquisatexto', 'Janela de Pesquisa');		
			
			$('#ocultarJanelaPesquisa').tooltip('hide');
			
			$("#caixaopcoes").animate({left:'-423px'}, {queue: false, duration: 800});
			$("#divocultarJanelaPesquisa").animate({left:'0px'}, {queue: false, duration: 800});    
			

			$("#divocultarJanelaPesquisa").html(
			'<center>' +
			'<i id="ocultarJanelaPesquisa" class="icon-double-angle-right" style="position:relative;top:10px;font-size:26px;color:#000;font-weight:bold" data-toggle="tooltip" title="Clique aqui para exibir a janela de Pesquisa" data-content="Ao selecionar este menu você será redicrecionado para a página inicial do Catálogo." data-trigger="hover" data-animation="true" data-placement="right" data-container="body"></i>' +
			'</center>');
			
			
							
		},
		function()
		{
			trocaTextoMenu('itemmenupesquisar', 'Janela de Pesquisa');		
			//trocaTextoMenu('msjanelapesquisatexto', 'Janela de Pesquisa');		

			$('#ocultarJanelaPesquisa').tooltip('hide');
			
			$("#caixaopcoes").animate({left:'1px'}, {queue: false, duration: 800});
			$("#divocultarJanelaPesquisa").animate({left:'423px'}, {queue: false, duration: 800});    	

			$("#divocultarJanelaPesquisa").html(
			'<center>' +
			'<i id="ocultarJanelaPesquisa" class="icon-double-angle-left" style="position:relative;top:10px;font-size:26px;color:#000;font-weight:bold" data-toggle="tooltip" title="Clique aqui para ocultar a janela de Pesquisa" data-content="Ao selecionar este menu você será redicrecionado para a página inicial do Catálogo." data-trigger="hover" data-animation="true" data-placement="right" data-container="body"></i>' +
			'</center>');

		}		
	);
		




	/**
	Nome: executaTarefasTimeOut
	Função responsável por executar tarefas relativas ao timeout da sessão atual do usuário quando a mesma
	expirar. Executa o programa PHP usuarioEstaLogado.php pra verificar se a sessão ainda esta ativa e dessa forma 
	torna-la inativa
	*/ 
	function executaTarefasTimeOut()
	{
	
		idleTime = 0;
		 
		var objetoHTTP;
		
		
		
		if (window.ActiveXObject)
		{
			objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
		}
		else if (window.XMLHttpRequest)
		{
			objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
		}
		else
		{
			return null;
		}
		
		
		
		objetoHTTP.onreadystatechange=function()
		{
			if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
			{
					var retorno = objetoHTTP.responseText;
							
					
					// Verifica se o usuário esta logado
					if (  retorno.trim().toUpperCase() == "LOGADO" ) 
					{	
						// Chama a função responsável por tornar inativa a sessão atual do usuário
						timeOutSessaoUsuario();	
						estaLogado=false;
						
						// Oculta a apresentação do cronometro de timeout
						var vartitulotimeout = document.getElementById("titulotimeout");
						var varcabecalhotimeout = document.getElementById("cabecalhotimeout");
						vartitulotimeout.style.display = 'none';
						varcabecalhotimeout.style.display = 'none';	
						
						
						// Fecha outras janelas modais que possam estar abertas						
						fechaTodasJanelas();
						
						//top.location.href='www2.dgi.inpe.br/catalogo/';
						
						top.frames['fmosaico'].liberaImagens();
						top.frames['fresultado'].location="resultado.php";
						top.frames['fpesquisa'].location="pesquisa.php";

		

						// Apresenta janela modal informando sobre a desconexão por timeout
						$('#wmtimeout').modal('show');	
					}	 
			}
		}
		
		// Executa o programa usuarioEstaLogado.php através de uma conexão ajax
		objetoHTTP.open("GET","usuarioEstaLogado.php", true);
		objetoHTTP.send();

	}


});


/**
Nome: abreJanelaLogin
Função responsável por apresentar a janela de login, sendo que antes fecha qualquer janela que esteja aberta
*/ 
function abreJanelaLogin()
{	
	fechaTodasJanelas();
	
	top.frames['flogin'].location="login.php";	
	$('#wmacesso').modal('show');	
}




/**
Nome: abreJanelaCadastro
Função responsável por apresentar a janela de cadastro de usuário, sendo que antes fecha qualquer janela que esteja aberta
*/ 
function abreJanelaCadastro()
{
	fechaTodasJanelas();
	
	top.frames['fcadastro'].location="register.php";
	$('#wmcadastro').modal('show');	
}



/**
Nome: fechaTodasJanelas
Função responsável por fechar qualquer uma das seguintes janelas caso esteja aberta:
wmacesso, wmaddtocart, wmcadastro, wmcarrinho, wmdetalhesimagem, wmhistorico, wmmensagem e wmnovasenha

*/ 
function fechaTodasJanelas()
{
	$('#wmacesso, #wmaddtocart, #wmcadastro, #wmcarrinho, #wmdetalhesimagem, #wmhistorico, #wmmensagem, #wmnovasenha').modal('hide');	
}











/**
Nome: timeOutSessaoUsuario
Função responsável por tornar inativa a sessão do usuário atual
*/ 
function timeOutSessaoUsuario()
{

	var objetoHTTP;
	
	var titulousuario = document.getElementById("titulousuario");
	var cabecalhousuario = document.getElementById("cabecalhousuario");


	
	
	if (window.ActiveXObject)
	{
		objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	
	
	

	objetoHTTP.onreadystatechange=function()
	{
		
			
		if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
		{
			var retorno = objetoHTTP.responseText;
			
			if ( retorno.trim().toUpperCase() == "OK" )
			{
				
				// Altera o texto dos titulos compacto e expandido referentes ao login do usuário para que apresentem 
				// a informação que o usuário não esta mais logado
				
				//titulousuario.innerHTML = '<i class="icon-user" style="color:#FFFFFF;font-size:15px"></i><font style="color:#FFFFFF;font-size:10px;">&nbsp;&nbsp;NÃO LOGADO</font>';
				titulousuario.innerHTML = '';
				
				//cabecalhousuario.innerHTML = '<i class="icon-user" style="color:#FFFFFF;font-size:22px;"></i><font style="color:#FFFFFF;font-size:12px;">&nbsp;&nbsp;NÃO LOGADO</font>';
				cabecalhousuario.innerHTML = '';
								

			}
		}
	}

	// Executa o programa PHP executaTimeOutSessaoAtual.php responsável por inativar a sessão do usuário
	objetoHTTP.open("GET","executaTimeOutSessaoAtual.php", true);
	objetoHTTP.send();
		
}




/**
Nome: atualizaTextoTimeout
Função responsável po atualizar o texto dos cronômetros de timeout da sessão
*/ 
function atualizaTextoTimeout(parametroTempoConexaoTimeOut, parametroIdleTime)
{		
	

	// Gerar e formatar o texto com o tempo restante para timeout da sessão
	
	var totalSegundosRestantes = (parametroTempoConexaoTimeOut - parametroIdleTime);
	var minutos = Math.floor(totalSegundosRestantes/60);
	var segundos = (totalSegundosRestantes%60);
	
	var strMinutos = (minutos < 10)?("0" + minutos):("" + minutos);
	var strSegundos = (segundos < 10)?("0" + segundos):("" + segundos);
	
	// Texto formatado com o tempo restante para timeout da sessão
	var stringTempoRestanteTimeOut =  strMinutos + ":" + strSegundos;
	
	
	// Atualiza o texto referente ao cronômetro existente no modo compacto
	var stringTimeOutTopo='<i class="icon-time" style="color:#FFF;font-size:15px"></i><font style="color:#FFF;font-size:12px;">&nbsp;&nbsp;' + stringTempoRestanteTimeOut + '</font>';	
	
	// Atualiza o texto referente ao cronometro existente no modo expandido
	var stringTimeOut='<i class="icon-time" style="color:#FC3;font-size:22px"></i><font style="color:#FC3;font-size:16px;">&nbsp;&nbsp;' + stringTempoRestanteTimeOut + '</font>';	
	
	
	// Variáveis para referenciar os cronômetros com o tempo restante de timeout da sessão 
	var titulotimeout = document.getElementById("titulotimeout");
	var cabecalhotimeout = document.getElementById("cabecalhotimeout");
	
	// Atualiza o texto dos objetos referentes aos cronometros com o texto atualizado contendo o tempo restante
	// para realizar o timeout da sessão
	titulotimeout.innerHTML = 	stringTimeOutTopo;
	cabecalhotimeout.innerHTML = stringTimeOut;
		
}








/**
* Nome: adicionarAoCarrinho
* Permite adicionar a imagem indiretamente no carrinho de imagens 
*
* parametroImagem	Vetor com informações da imagem a ser adicionada ao carrinho
*/
function atualizaNumeroItensCarrinho()
{

	var objetoHTTP; // Objeto responsável por gerenciar s requisições HTTP
	
	
	if (window.ActiveXObject)
	{
		objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	

	objetoHTTP.onreadystatechange=function()
	{

		// Caso seja executado com sucesso
		if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
		{
			var objetoNumeroItensCarrinho = document.getElementById("numeroitenscarrinho");
			var retorno = objetoHTTP.responseText;
			retorno = retorno.trim().toUpperCase();
			
			objetoNumeroItensCarrinho.innerHTML = retorno;
		}
		/*
		else
		{
			registros.innerHTML="Erro ao realizar pesquisa.";	
		}
		*/
			
	}

	objetoHTTP.open("GET","numeroitenscarrinho.php", true);
	objetoHTTP.send();


				
	
}









/**
* Nome: adicionarAoCarrinho
* Permite verificar se o carrinho esta vazio
*/
function apresentaCarrinhoDeImagens()
{

	var objetoHTTP; // Objeto responsável por gerenciar s requisições HTTP
	
	
	if (window.ActiveXObject)
	{
		objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	

	objetoHTTP.onreadystatechange=function()
	{

		// Caso seja executado com sucesso
		if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
		{
			var numeroItens = objetoHTTP.responseText;
			numeroItens = numeroItens.trim();
			


			if ( numeroItens == 0 )
			{
				var mensagemCarrinhoVazio="<b>Carrinho de imagens está vazio.</b><br>" +
				"O carrinho de imagens está vazio e por isso o mesmo não será apresentado.";
				exibeMensagemGenerica(mensagemCarrinhoVazio);
			}
			else
			{		
				top.frames['fcarrinho'].location="cart.php";			
				$('#wmcarrinho').modal('show');	
			}
	
		}			
	}

	objetoHTTP.open("GET","numeroitenscarrinho.php", true);
	objetoHTTP.send();					
}



function ativaCarregamento()
{
	var divCarregando = document.getElementById("divCarregando");
	divCarregando.style.display='block';
}



function desativaCarregamento()
{
	var divCarregando = document.getElementById("divCarregando");
	divCarregando.style.display='none';
}



</script>


<script src="http://barra.brasil.gov.br/barra.js" type="text/javascript"></script>

</body>
</html>
