<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
//header('Content-Type: text/html; charset=UTF-8');

//error_reporting(E_ALL ^ E_DEPRECATED); // Não apresenta as mensagens relacionadas a comandos 
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ERROR);



// Cofigura o timezone
if(function_exists('date_default_timezone_set') )
{
   date_default_timezone_set('UTC');
}
else
{
   putenv("TZ=UTC");
}

// Inclusão de blibliotecas para tratamento do controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais

include("session_mysql.php");
session_start();

include ("globals.php");
include_once("operator.class.php");

import_request_variables("gpc");



/* 
	Variável de controle do script
	Utilizada somente para saber se o script foi executado pela origem correta
*/
if (! isset($_GET['p']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Pesquisa executada de forma não permitida.</td>';	
	echo '	</tr>';	
	echo '</table>';
	return;	
}


// Verifica quem disparou o evento
if (! isset($_GET['TRIGGER']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Pesquisa executada de forma não permitida.</td>';	
	echo '	</tr>';	
	echo '</table>';
	return;	
}



// Inclusão do arquivo com as funções necessárias para o correto uncionamento
require_once('func.buscarcidades.php');


// String com os parametros formatados a serem utilizados por links de pesquisa
$strParametros='';

// String contendo a cláusula de restrição/pesquisa
$strWhere='';

$trigger=''; // Quem originou a execução deete programa






// Nome do país selecionado
if (isset($_GET['PAIS']) )
{
	$pais=strtoupper($_GET['PAIS']);
	
	// Se estiver definido e não for vazio irá adicionar o filtro por país à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($pais))
	{	
		$strWhere.=((empty($strWhere))?(" ( PAIS = '$pais' ) "):(" AND ( PAIS = '$pais' ) "));	
	}
}







// Nome do estado selecionado
if (isset($_GET['ESTADO']) )
{
	$estado=strtoupper($_GET['ESTADO']);
	
	// Se estiver definido e não for vazio irá adicionar o filtro por Estado à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($estado))
	{	
		$siglaEstado = retornaSiglaEstado($estado);
		$strWhere.=((empty($strWhere))?(" ( ESTADO = '$siglaEstado' ) "):(" AND ( ESTADO = '$siglaEstado' ) "));	
	}
}






// Nome do estado selecionado
if (isset($_GET['MUNICIPIO']) )
{
	$municipio=strtoupper($_GET['MUNICIPIO']);
	
	// Se estiver definido e não for vazio irá adicionar o filtro por Estado à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($municipio))
	{	
		$strWhere.=((empty($strWhere))?(" ( MUNICIPIO LIKE '%$municipio%' ) "):(" AND ( MUNICIPIO LIKE '%$municipio%' ) "));	
	}
}




if ( ! empty($strWhere))
{	
	$strWhere=" WHERE " . "$strWhere";	
}



// Inicia a conexão com a base de dados  

// Variáveis contendo informações para acesso ao banco de dados
/*
$dbhost = 'saci.dgi.inpe.br:3333';
$dbuser = 'dgi';
$dbpass = 'dgi.2013';
*/

$dbhost = $GLOBALS["dbhostname"] . ':' . $GLOBALS["dbport"];
$dbuser = $GLOBALS["dbusername"] ;
$dbpass = $GLOBALS["dbpassword"] ;
$dbname = $GLOBALS["dbcatname"];


// Realizada a conexão com banco de dados
$conexao = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conexao);



// Verifica se ocorreu erro ao realizar a conexão com o banco de dados
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



// String contendo a instrução SQL para realizar a contagem dos registros conforme 
// filtro gerado na cláusula WHERE
//$stringSQLTotal="SELECT count(satellite) as total FROM Scene " . $strWhere;
$stringSQLTotal="SELECT count(1) as total FROM municipios " . $strWhere;



mysql_select_db( "$dbname", $conexao); 

// Obtém o número total de registros da pesquisa  
$contagem = mysql_query( $stringSQLTotal, $conexao );
$total_retorno = mysql_fetch_object ($contagem);
$total_registros=$total_retorno->total;




// Verifica se foram encontrados registros
if ( $total_registros < 1 )
{
	echo "0";
	exit;	
}



// String com a lista de campos
$strCamposMunicipios=" MUNICIPIO, ESTADO, PAIS, LAT, LON ";
			
// String contendo a instrução de pesquisa com o limite de registrs s serem retornados
$stringSQLPesquisa="SELECT $strCamposMunicipios FROM municipios " . $strWhere . "  ORDER BY MUNICIPIO ";
// Resgistros retornados
$registros = mysql_query( $stringSQLPesquisa, $conexao );

$contagem_atual=1;


	
echo '<div style="width:385px">';
echo '    <table class="table table-hover" style="margin-right:5px">';
echo '        <thead>';
echo '            <tr>';
echo '                <th>#</th>';
echo '                <th>Município</th>';
echo '                <th>Estado</th>';
echo '                <th>País</th>';
echo '            </tr>';
echo '        </thead>';
echo '        <tbody>';



// Realiza a leitura de cada registro e atribui à variável ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $registros ) ) 
{	

	$varLat=$ratual['LAT'];
	$varLon=$ratual['LON'];
	
	$varMunicipio=$ratual['MUNICIPIO'];
	$varEstado=$ratual['ESTADO'];
	$varPais=$ratual['PAIS'];
	
	//echo '	<tr onclick="javascript:chamaExecutaPesquisaCoordenadas(' . $varLat . ' , ' . $varLon . ')" style="cursor:pointer">';
	echo '	<tr onclick="javascript:top.frames[\'fpesquisa\'].executaPesquisaCoordenadas(' . $varLat . ' , ' . $varLon . ' , \'' . $varMunicipio . '\''  . ' , \'' . $varEstado . '\'' . ' , \'' . $varPais . '\'' . ')" style="cursor:pointer">';
	echo '		<td>' . $contagem_atual . '</td>';
	echo '		<td>' . $ratual['MUNICIPIO'] . '</td>';
	echo '		<td>' . $ratual['ESTADO'] . '</td>';
	echo '		<td>' . $ratual['PAIS'] . '</td>';
	echo '	</tr>';


	$contagem_atual++;

}


echo '        </tbody>';
echo '    </table>';
echo '</div>';




// Libera as variáveis relacionadas aos registros retornados e à conexão com o banco de dados
mysql_free_result( $registros );   
mysql_close($conexao);




?>
