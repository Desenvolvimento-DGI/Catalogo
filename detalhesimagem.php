<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 


error_reporting(E_ALL ^ E_DEPRECATED);



if(function_exists('date_default_timezone_set') )
{
   date_default_timezone_set('UTC');
}
else
{
   putenv("TZ=UTC");
}



// Variável de controle
if (! isset($_GET['p']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Execução executada de forma não permitida.</td>';	
	echo '	</tr>';	
	echo '</table>';
	
	exit;	
}



if (! isset($_GET['SceneId']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Necessário informar o SceneId da imagem.</td>';	
	echo '	</tr>';	
	echo '</table>';
	
	exit;	
}


include("session_mysql.php");
session_start();

include ("globals.php");
include_once("operator.class.php");

import_request_variables("gpc");



$SceneId='';
$diretorio_quicklook='/QUICKLOOK/QUICKLOOK/';



// Formata o acionador do evento, quem dispaou o evento
if (isset($_GET['SceneId']) )
{
	$SceneId=strtoupper($_GET['SceneId']);
}



if (empty($SceneId) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>SceneId informado é inválido.</td>';	
	echo '	</tr>';	
	echo '</table>';
	
	exit;	
}

$strWhere=' WHERE SceneId = \'' . $SceneId  . '\'';
$stringSQLPesquisa='SELECT * FROM Scene ' . $strWhere;
$stringSQLTotal='SELECT count(satellite) as total FROM Scene ' . $strWhere;


// Inicia a conexão com a base de dados  

/*
$dbhost = 'envisat.dgi.inpe.br:3333';
$dbuser = 'gerente';
$dbpass = 'gerente.200408';

$conexao = mysql_connect($dbhost, $dbuser, $dbpass);
*/



$dbhost = $GLOBALS["dbhostname"] . ':' . $GLOBALS["dbport"];
$dbuser = $GLOBALS["dbusername"] ;
$dbpass = $GLOBALS["dbpassword"] ;
$dbname = $GLOBALS["dbcatname"];


// Realizada a conexão com banco de dados
$conexao = mysql_connect($dbhost, $dbuser, $dbpass);




if(! $conexao )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Erro ao realizar conexão com o banco de dados.</td>';	
	echo '	</tr>';	
	echo '	<tr>';	
	echo '		<td>Segue o erro: <b>' . mysql_errno() . '</b><br>';
	echo '		' . mysql_error() ;
	echo '		</td>';	
	echo '	</tr>';	
	echo '</table>';
	
	exit;	
}



mysql_select_db( 'catalogo', $conexao); 
  
$contagem = mysql_query( $stringSQLTotal, $conexao );
$total_retorno = mysql_fetch_object ($contagem);
$total_registros=$total_retorno->total;






// Verifica se foram encontrados registros
//

if ( $total_registros < 1 )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>SceneId ' . $SceneId . ' informado não foi encontrado.</td>';	
	echo '	</tr>';	
	echo '</table>';

	exit;	
}

if ( $total_registros > 1 )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Encontrado mais de 1 (um) registro com o ScheId ' . $SceneId . '</td>';	
	echo '	</tr>';	
	echo '</table>';

	exit;	
}



$registro = mysql_query( $stringSQLPesquisa, $conexao );


while ( $ratual = mysql_fetch_assoc( $registro ) ) 
{	

	$varSceneId			= $ratual['SceneId'];
	$varSatellite		= $ratual['Satellite']; 
	$varSensor			= $ratual['Sensor']; 

	$varDate			= $ratual['Date']; 
	
	if ( strtoupper($varSatellite) == "NPP" or strtoupper($varSatellite) == "NPP" )
	{	
		$varHoraScene=substr($varSceneId, 17, 2) . ':' . substr($ratual['SceneId'],19, 2) . ':' . substr($ratual['SceneId'],21, 2);
	}
	else
	{
		$varHoraScene=substr($varSceneId, 16, 2) . ':' . substr($ratual['SceneId'],18, 2) . ':' . substr($ratual['SceneId'],20, 2);
	}
	
	$varCenterLatitude	= $ratual['CenterLatitude']; 
	$varCenterLongitude	= $ratual['CenterLongitude']; 
	
	$varTL_Latitude		= $ratual['TL_Latitude']; 
	$varTL_Longitude	= $ratual['TL_Longitude']; 								
	$varTR_Latitude		= $ratual['TR_Latitude']; 
	$varTR_Longitude	= $ratual['TR_Longitude']; 
	
	$varBR_Latitude		= $ratual['BR_Latitude']; 
	$varBR_Longitude	= $ratual['BR_Longitude']; 								
	$varBL_Latitude		= $ratual['BL_Latitude']; 
	$varBL_Longitude	= $ratual['BL_Longitude'];
	
	$varPath			= $ratual['Path']; 
	$varRow				= $ratual['Row']; 								
	
	$varCloudCoverQ1	= $ratual['CloudCoverQ1']; 
	$varCloudCoverQ2	= $ratual['CloudCoverQ2']; 								
	$varCloudCoverQ3	= $ratual['CloudCoverQ3']; 
	$varCloudCoverQ4	= $ratual['CloudCoverQ4'];

	$varQuickLookGrande	= $diretorio_quicklook . 'QL_' . $varSceneId . '_GRD.png';
	$varQuickLookMedio	= $diretorio_quicklook . 'QL_' . $varSceneId . '_MED.png';
	



}

mysql_free_result( $registro );   
mysql_close($conexao);



?>

    

<!-- Accordions-->
<br>
<ul id="myTabDetalhes" class="nav nav-tabs">					
    <li class="active"><a href="#informacoes" data-toggle="tab" >Informações</a></li>
    <li class=""><a href="#coordenadas" data-toggle="tab" >Coordenadas</a></li>
</ul>

<div id="myTabContentDetalhes" class="tab-content">
    
    <!--  Tab com os campos para realização da pesquisa -->
    <div class="tab-pane fade active in" id="informacoes">
        
        <font face="Tahoma, Geneva, sans-serif" size="2">
        
        <table border=0 cellpadding="2" cellspacing="2">
        <tr>
            <td align="left" valign="bottom" width="200"><b>ID da imagem</b>
            </td>
        </tr>
        <tr>
            <td align="left" width="200" bgcolor="#14354E" style="border-radius:4px"> <font color="#FFFFFF"><?php echo "$varSceneId" ?></font>
            </td>
        </tr>
        </table>
        
        <br>
        
        
        <table border=0 cellpadding="2" cellspacing="2">
        <!-- Titulo -->
        <tr>
            <td align="left" valign="bottom" width="90"> Satélite
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">Sensor
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">Data
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">Hora
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">Órbita
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">Ponto
            </td>
        </tr>
        <!-- Dados -->
        <tr>
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varSatellite" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varSensor" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varDate" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varHoraScene" ?>
            </td>
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varPath" ?>
            </td>
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varRow" ?>
            </td>
        </tr>  
        </table> 
        
        <br>
        <br>
        
        <table border=0 cellpadding="2" cellspacing="2">
        <!-- Titulo Cobertura de Nuvens -->
        <tr>
            <td align="left" colspan="7">
            <b>Cobertura de Nuvens</b>
            </td>
        </tr>
        <!-- Espaçamento -->
        <tr>
            <td align="left" colspan="7" height="4">
            </td>
        </tr>
        <!-- Titulo -->
        <tr>
            <td align="left" valign="bottom" width="90">1º Quadrante
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">2º Quadrante
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">3º Quadrante
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="90">4º Quadrante
            </td>
        </tr>
        <!-- dados -->
        <tr>
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCloudCoverQ1" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCloudCoverQ2" ?>
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCloudCoverQ3" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCloudCoverQ4" ?>
            </td>
        </tr>
        </table>
        <br>
        <br>
        </font>



        
    </div>
    <!-- Fim da tab pesquisar -->

    <!-- Tab para apresentar inormações referentes às coordenadas  -->
    <div class="tab-pane fade" id="coordenadas">
        
        

        <font face="Tahoma, Geneva, sans-serif" size="2">
        <table border=0 cellpadding="2" cellspacing="2">
        <!-- Titulo Geral-->
        <tr>
            <td align="left" valign="middle" colspan="3">
            <b>Centro da imagem</b>
            </td>
        </tr>
        
        <!-- Titulo coordenadas -->
        <tr>
            <td align="left" valign="bottom" width="120"> Latitude central
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Longitude central
            </td>
        </tr>
        
        <!-- Dados coordenadas-->
        <tr>
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCenterLatitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varCenterLongitude" ?>
            </td>
        </tr>  
        </table> 
        <br>
         
        <table border=0 cellpadding="2" cellspacing="2">
        <!-- Titulo Geral-->
        <tr>
            <td align="left" valign="middle" colspan="3">
            <b>Superior esquerdo</b>
            </td>
        
            <td align="left" width="40">&nbsp;
            </td>
        
            <td align="left" valign="middle" colspan="3">
            <b>Superior direito</b>
            </td>
        
            <td align="left" width="20">&nbsp;
            </td>
        </tr>
        <!-- Titulo coordenadas -->
        <tr>
            <td align="left" valign="bottom" width="120"> Latitude (Norte)
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Longitude (Oeste)
            </td>
        
            <td align="left" width="40">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Latitude (Norte)
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Longitude (Leste)
            </td>
        
            <td width="20">&nbsp;
            </td>
        
        </tr>
        <!-- Dados coordenadas-->
        <tr>
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varTL_Latitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varTL_Longitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varTR_Latitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varTR_Longitude" ?>
            </td>
            <td>&nbsp;
            </td>
        </tr>  
        </table> 
        <br>
        
        <table border=0 cellpadding="2" cellspacing="2">
        <!-- Titulo Geral-->
        <tr>
            <td align="left" valign="middle" colspan="3">
            <b>Infeior esquerdo</b>
            </td>
        
            <td align="left" width="40">&nbsp;
            </td>
        
            <td align="left" valign="middle" colspan="3">
            <b>Inferior direito</b>
            </td>
        
            <td align="left" width="20">&nbsp;
            </td>
        </tr>
        <!-- Titulo coordenadas -->
        <tr>
            <td align="left" valign="bottom" width="120"> Latitude (Sul)
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Longitude (Oeste)
            </td>
        
            <td align="left" width="40">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Latitude (Sul)
            </td>
        
            <td align="left" width="10">&nbsp;
            </td>
        
            <td align="left" valign="bottom" width="120">Longitude (Leste)
            </td>
        
            <td width="20">&nbsp;
            </td>
        </tr>
        <!-- Dados coordenadas-->
        <tr>
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varBL_Latitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varBL_Longitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varBR_Latitude" ?>
            </td>
        
            <td>&nbsp;
            </td>
        
            <td align="left" bgcolor="#FAFAFA" style="border-radius:4px"><?php echo "$varBR_Longitude" ?>
            </td>
            <td>&nbsp;
            </td>
        </tr>  
        </table> 
        <br>
        </font>
        
        
    </div>
    <!-- Fim da tab resultados -->
        
    
</div>

   
