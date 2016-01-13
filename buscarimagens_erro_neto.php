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


/* 
	Variável de controle do script
	Utilizada somente para saber se o script foi executado pela origem correta
*/
if (! isset($_GET['p']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Execução executada de forma não permitida.</td>';	
	echo '	</tr>';	
	echo '</table>';
	return;	
}


// Verifica quem disparou o evento
if (! isset($_GET['TRIGGER']) )
{
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Execução executada de forma não permitida.</td>';	
	echo '	</tr>';	
	echo '</table>';
	return;	
}




/*
	Função para validar o valor da cobertura de nuvens
	Deve ser um dos valores definidos, caso contrário o valor é
	configurado para vazio 
*/
function validaCloudCover( $parCloudCover )
{
	$retorno = 100;
	switch ( $parCloudCover )
	{
		case "0":
		case "10":
		case "20":
		case "30":
		case "40":
		case "50":
		case "60":
		case "70":
		case "80":
		case "90":
		case "100":
			$retorno = $parCloudCover;
			break;	
		case "-1":
			$retorno = 0;
			break;
	}		
	return $retorno;
	

}







/*
	Função para validar o valor da cobertura de nuvens da imagem toda
*/
function validaCoberturaNuvemTotal( $parCloudCover )
{
	$retorno = $parCloudCover;
	$parteInteira="";	
	$parteDecimal="";
	
	if ( strpos($parCloudCover, '.') )
	{
		list($parteInteira, $parteDecimal)=explode(".", $parCloudCover);
		$parteDecimal=substr($parteDecimal,0,2);		
		$retorno=$parteInteira . ',' . $parteDecimal;
	}
	
	return $retorno;

}



// Inclusão do arquivo com as funções necessárias para o correto uncionamento
require_once('func.buscarimagens.php');


// Variáveis

// diretório base dos quicklooks
$diretorio_quicklook='/QUICKLOOK/';

// Quantidade de registros por página 
$registros_por_pagina=20;
// Quantidade máxima de registros por página 
// Necessáro para manter a performance na visualização dos resultados
$maximo_registros_por_pagina=200;


// String contendo a cláusula de restrição/pesquisa
$strWhere='';
// String com os parametros formatados a serem utilizados por links de pesquisa
$strParametros='';
// String com os parametros formatados a serem utilizados por links de pesquisa com seleção por satélite
$strParametrosPorSatelite='';

$trigger=''; // Quem originou a execução deete programa
$satelite='';  
$sensor=''; 

$brregiao='';
$brfuso='';


/* 
	Formata o acionador/gatilho do evento, o botão pelo qual foi disparado o evento
	Utilizado para saber qual botão disparou o evento e dessa forma poder utilizar os 
	campos corretamente na formulação da clausula de restrição da pesquisa no banco de dados
*/
if (isset($_GET['TRIGGER']) )
{
	$trigger=strtoupper($_GET['TRIGGER']);
	
	// Se o acionador deste programa for informado, o mesmo será definido com este valor
	if ( ! empty($trigger))
	{			
		$strParametros="&TRIGGER=$trigger";
	}
	else
	// Caso o acionador deste programa não seja informado, o mesmo será definido com o valor padrão
	{
		$strParametros="&TRIGGER=BTNBASICA";
	}
	
	$strParametrosPorSatelite=$strParametros;
}




// String contendo a cláusula WHERE para realizar o filtro de registros no banco de dados
//$strWhere=(empty($strWhere))?$strWhere:(" WHERE " . $strWhere);
//$strWhere=(empty($strWhere))?' WHERE ( Desativado = "N" AND Catalogo > 1 AND Deleted = 0 )':($strWhere . ' AND ( Desativado = "N" AND Catalogo > 1 AND Deleted = 0 )');

$strWhere=(empty($strWhere))?' WHERE ( Desativado = "N" AND Catalogo > 1 AND CloudCoverMethod = "M" )':($strWhere . ' AND ( Desativado = "N" AND Catalogo > 1 AND CloudCoverMethod = "M" )');




$strWhereCQ="";
$strParametrosCQ="";
$controleCQ=0;


// Imagens aprovadas
if (isset($_GET['CQA']) )
{
	$cqa=strtoupper($_GET['CQA']);

	// Se estiver definido e não for vazio irá adicionar o filtro por status da imagem/cena à
	// cláusula WHERE e à string de parâmetros
	if ( strtoupper($cqa) == "CA" )
	{		
		$strWhereCQ="  Deleted = 0  ";
		$strParametrosCQ.="&CQA=$cqa";
		$controleCQ++;
	}
}



// Imagens rejeitadas
if (isset($_GET['CQR']) )
{
	$cqr=strtoupper($_GET['CQR']);

	// Se estiver definido e não for vazio irá adicionar o filtro por status da imagem/cena à
	// cláusula WHERE e à string de parâmetros
	if ( strtoupper($cqr) == "CR" )
	{				
		$strWhereCQ="  Deleted = 1  ";
		$strParametrosCQ.="&CQR=$cqr";
		$controleCQ++;
	}	
}


if ( $controleCQ == 1) 
{
	$strWhere.=((empty($strWhere))?("  $strWhereCQ  "):(" AND $strWhereCQ  "));		
	$strParametros.="$strParametrosCQ";
	$strParametrosPorSatelite.="$strParametrosCQ";			
}







// Nome do satelite selecionado
if (isset($_GET['SATELITE']) )
{
	$satelite=strtoupper($_GET['SATELITE']);
	
	// Se estiver definido e não for vazio irá adicionar o filtro por satélite à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($satelite))
	{	
	
		if ( $permissaoRapieye )
		{
			// Verifica se foi escolhido o conjunto de satélites RapidEye (Todos)
			if ( $satelite == "RE" )
			{
				$strWhere.=" AND ( satellite in ('RE1', 'RE2', 'RE3', 'RE4', 'RE5') ) ";
			}
			else
			{
				$strWhere.=" AND ( satellite = '$satelite' ) ";	
			}
		}
		else
		{
			// Verifica se foi escolhido o conjunto de satélites RapidEye (Todos)
			if ( $satelite == "RE" or  $satelite == "RE1" or  $satelite == "RE2" or  $satelite == "RE3" or  $satelite == "RE4" or  $satelite == "RE5" )
			{
				$strWhere.=" AND ( satellite NOT IN ('RE1', 'RE2', 'RE3', 'RE4', 'RE5')  ) ";
			}
			else
			{
				$strWhere.=" AND ( satellite = '$satelite' ) AND ( satellite NOT IN ('RE1', 'RE2', 'RE3', 'RE4', 'RE5')  ) ";	
			}
				
		}		
		
		$strParametros.="&SATELITE=$satelite";
	}
	else
	{		
		if ( ! $permissaoRapieye )
		{
			$strWhere.=" AND ( satellite NOT IN ('RE1', 'RE2', 'RE3', 'RE4', 'RE5')  ) ";	
		}		
	}	
	
}
else
{
	if ( ! $permissaoRapieye )
	{
		$strWhere.=" AND ( satellite NOT IN ('RE1', 'RE2', 'RE3', 'RE4', 'RE5')  ) ";	
	}	
}





// Nome do sensor selecionado
if (isset($_GET['SENSOR']) )
{
	$sensor=strtoupper($_GET['SENSOR']);

	// Se estiver definido e não for vazio irá adicionar o filtro por sensor à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($sensor))
	{		
		$strWhere.=((empty($strWhere))?(" ( sensor = '$sensor' ) "):(" AND ( sensor = '$sensor' ) "));	
		$strParametros.="&SENSOR=$sensor";
		$strParametrosPorSatelite.="&SENSOR=$sensor";
	}
}







// Validação de datas
//

$dataini='';
$datafim='';


// Data inicial do período
if ( isset($_GET['DATAINI']) )
{
	$dataini = $_GET['DATAINI'];
}


// Data final do período
if ( isset($_GET['DATAFIM']) )
{
	$datafim = $_GET['DATAFIM'];
}



if ( (! empty($dataini)) and (! empty($datafim)) )
{			
	// Data final for mais antiga que a data inicial
	if ( formataDataAnoMesDia($dataini)  >  formataDataAnoMesDia($datafim) )
	{
		$dataini = $_GET['DATAFIM'];
		$datafim = $_GET['DATAINI'];	
	}
}



/* 
	Verifica se uma das datas esta vázia e caso esteja, configura a data vazia igual 
	a data que não esta vazia
*/
if ( (! empty($dataini)) and ( empty($datafim)) )
{		
		$datafim = $dataini;		
}

if ( (! empty($datafim)) and ( empty($dataini)) )
{	
		$dataini = $datafim;		
}






/*
	Formatar as datas informadas
	Formata as datas corretamente a serem utilizadas para consultas no banco de dados
	Formato informado: dia/mes/ano
	Formato gerado   : ano-mes-dia
*/
if ( (! empty($dataini)) and (! empty($datafim)) )
{
	// Validar data	no formato que as datas se encontram gravadas no banco de dados
	// De Dia/Mes/Ano para Ano-Mes-Dia
	$strDataIni=formataDataAnoMesDia($dataini);
	$strDataFim=formataDataAnoMesDia($datafim);

	$strWhere.=((empty($strWhere))?(" ( Date >= '$strDataIni' )  AND ( Date <= '$strDataFim' )"):(" AND ( Date >= '$strDataIni' )  AND ( Date <= '$strDataFim' )"));		
	$strParametros.="&DATAINI=$dataini&DATAFIM=$datafim";
	$strParametrosPorSatelite.="&DATAINI=$dataini&DATAFIM=$datafim";
}






// Órbita (Path)
// -------------
// Validação relacionada aos valores das órbitas


// Orbita inicial
if (isset($_GET['ORBITAINI']) )
{
	$orbitaini=strtoupper($_GET['ORBITAINI']);
}


// Orbita final
if (isset($_GET['ORBITAFIM']) )
{
	$orbitafim=strtoupper($_GET['ORBITAFIM']);
}



// Orbita inicial
if (isset($_GET['ORBITAINI']) and isset($_GET['ORBITAFIM']) )
{
	// Se estiver definido e não for vazio irá adicionar o filtro por órbita à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($orbitaini) and ! empty($orbitafim) )
	{		
		$strWhere.=((empty($strWhere))?(""):(" AND "));	
		$strWhere.= " ( CONVERT(Path, UNSIGNED INTEGER) >= CONVERT('$orbitaini', UNSIGNED INTEGER) and CONVERT(Path, UNSIGNED INTEGER) <= CONVERT('$orbitafim', UNSIGNED INTEGER) ) ";
		$strParametros.="&ORBITAINI=$orbitaini&ORBITAFIM=$orbitafim";
		$strParametrosPorSatelite.="&ORBITAINI=$orbitaini&ORBITAFIM=$orbitafim";
	}
}
else
{
	
	// Somente o valor do campo Orbita inicial foi informado			
	if (isset($_GET['ORBITAINI']) )
	{
		// Se estiver definido e não for vazio irá adicionar o filtro por órbita à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($orbitaini) )
		{
			$strWhere.=((empty($strWhere))?(""):(" AND "));	
			$strWhere.=" ( CONVERT(Path, UNSIGNED INTEGER) =  CONVERT('$orbitaini', UNSIGNED INTEGER) ) ";
			$strParametros.="&ORBITAINI=$orbitaini";
			$strParametrosPorSatelite.="&ORBITAINI=$orbitaini";
		}
	}
	
	// Somente o valor do campo Orbita fim foi informado
	if (isset($_GET['ORBITAFIM']) )
	{
			// cláusula WHERE e à string de parâmetros
			if ( ! empty($orbitafim) )
			{		
				$strWhere.=((empty($strWhere))?(""):(" AND "));	
				$strWhere.=" ( CONVERT(Path, UNSIGNED INTEGER) =  CONVERT('$orbitafim', UNSIGNED INTEGER) ) ";	
				$strParametros.="&ORBITAFIM=$orbitafim";
				$strParametrosPorSatelite.="&ORBITAFIM=$orbitafim";
			}	
		
		
	}
		
	
}












// Ponto (Row)
// -----------
// Validação relacionada aos valores dos pontos (rows)


// Ponto inicial
if (isset($_GET['PONTOINI']) )
{
	$pontoini=strtoupper($_GET['PONTOINI']);
}


// Ponto final
if (isset($_GET['PONTOFIM']) )
{
	$pontofim=strtoupper($_GET['PONTOFIM']);
}



// Ponto inicial
if (isset($_GET['PONTOINI']) and isset($_GET['PONTOFIM']) )
{
	// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
	// cláusula WHERE e à string de parâmetros
	if ( ! empty($pontoini) and ! empty($pontofim) )
	{		
		$strWhere.=((empty($strWhere))?(""):(" AND "));
		$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) >=  CONVERT('$pontoini', UNSIGNED INTEGER) and  CONVERT(Row, UNSIGNED INTEGER) <=  CONVERT('$pontofim', UNSIGNED INTEGER) ) ";	
		$strParametros.="&PONTOINI=$pontoini&PONTOFIM=$pontofim";
		$strParametrosPorSatelite.="&PONTOINI=$pontoini&PONTOFIM=$pontofim";
	}
}
else
{
	
	// Somente o valor do campo ponto inicial foi informado			
	if (isset($_GET['PONTOINI']) )
	{
		// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($pontoini) )
		{		
			$strWhere.=((empty($strWhere))?(""):(" AND "));
			$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) =  CONVERT('$pontoini', UNSIGNED) ) ";	
			$strParametros.="&PONTOINI=$pontoini";
			$strParametrosPorSatelite.="&PONTOINI=$pontoini";
		}
	}	
		
	
	// Somente o valor do campo ponto fim foi informado	
	if (isset($_GET['PONTOFIM']) )
	{
		// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($pontofim) )
		{		
			$strWhere.=((empty($strWhere))?(""):(" AND "));
			$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) =  CONVERT('$pontofim', UNSIGNED INTEGER) ) ";	
			$strParametros.="&PONTOFIM=$pontofim";
			$strParametrosPorSatelite.="&PONTOFIM=$pontofim";
		}
	}	
	
	
}








$strWhereCNQI='';
$strParametrosCNQI='';	
$strParametrosPorSateliteCNQI='';		



$strWhereCN='';
$strParametrosCN='';	
$strParametrosPorSateliteCN='';		

$strWhereQI='';
$strParametrosQI='';	
$strParametrosPorSateliteQI='';		



if ( $satelite == "L8" /*||$satelite == "CB4" */|| (!isset($_GET['SATELITE'])) || empty($satelite) )
{
	if ( isset($_GET['CNTOTAL']) )
	{
		// Possui controle de Cobertura de Nuven pra imagem toda e não por quadrante
		$cqcnt=$_GET['CNTOTAL'];
		$strWhereCN=" ( Image_CloudCover <= $cqcnt ) ";
		$strParametrosCN="CNTOTAL=$cqcnt";	
		$strParametrosPorSateliteCN="CNTOTAL=$cqcnt";		
	}
	
	
	
	if ( isset($_GET['QITOTAL']) )
	{
		// Possui controle de Qualidade da imagem toda (Nota geral pra imagem)		
		$cqqit=$_GET['QITOTAL'];
		$strWhereQI=" ( Image_Quality >= $cqqit ) ";
		$strParametrosQI="QITOTAL=$cqqit";	
		$strParametrosPorSateliteQI="QITOTAL=$cqqit";		
	}
	
}


	

if ( ! empty($strWhereCN) )
{

	if ( ! empty($strWhereQI) )
	{	
		$strWhereCNQI.= " ( " . $strWhereCN . " AND " . $strWhereQI . " ) ";
		$strParametrosCNQI.="&" . $strParametrosCN . "&" . $strParametrosQI;	
		$strParametrosPorSateliteCNQI=$strParametrosCNQI;					
	}
	else
	{
		$strWhereCNQI.=$strWhereCN;
		$strParametrosCNQI.="&" . $strParametrosCN;	
		$strParametrosPorSateliteCNQI=$strParametrosCNQI;					
	}
}
else
{	
	if ( ! empty($strWhereQI) )
	{	
		$strWhereCNQI.= $strWhereQI;
		$strParametrosCNQI.="&" . $strParametrosQI;	
		$strParametrosPorSateliteCNQI=$strParametrosCNQI;					
	}	
	
}




$strWhereCQ='';
$strParametrosCQ='';	
$strParametrosPorSateliteCQ='';

// Se o satélite não for o NPP inclui a cobertura de nuves
if ( ( $satelite != "L8" /*&&  $satelite != "CB4"*/ ) || (!isset($_GET['SATELITE'])) || empty($satelite) )
{
	// Quantidade máxima de nuves 
	// Quadrante 1 (Q1)	
	$ccq1=(isset($_GET['Q1']))?($_GET['Q1']):(100);
	$ccq1=($ccq1 == "")?(100):($ccq1);
	
	// Se a variável Q1 estiver definida e possir valor não vazio, a mesma será adicionada da
	// cláusula WHERE e às strind de parametros
	if ( "$ccq1" != "" )	
	{	
		$strWhereCQ.=((empty($strWhereCQ))?(" ( CloudCoverQ1 <= $ccq1 ) "):(" AND ( CloudCoverQ1 <= $ccq1 ) "));	
		$strParametrosCQ.="&Q1=$ccq1";	
		$strParametrosPorSateliteCQ.="&Q1=$ccq1";		
	}
	
	
	
	
	// Quantidade máxima de nuves 
	// Quadrante 2 (Q2)
	$ccq2=(isset($_GET['Q2']))?($_GET['Q2']):(100);
	$ccq2=($ccq2 == "")?(100):($ccq2);
			
	// Se a variável Q2 estiver definida e possir valor não vazio, a mesma será adicionada da
	// cláusula WHERE e às strind de parametros
	if ( $ccq2 != "" )	
	{
		$strWhereCQ.=((empty($strWhereCQ))?(" ( CloudCoverQ2 <= $ccq2 ) "):(" AND ( CloudCoverQ2 <= $ccq2 ) "));	
		$strParametrosCQ.="&Q2=$ccq2";		
		$strParametrosPorSateliteCQ.="&Q2=$ccq2";		
	}		
	

	
	// Quantidade máxima de nuves 
	// Quadrante 3 (Q3)
	$ccq3=(isset($_GET['Q3']))?($_GET['Q3']):(100);
	$ccq3=($ccq3 == "")?(100):($ccq3);
		
	// Se a variável Q3 estiver definida e possir valor não vazio, a mesma será adicionada da
	// cláusula WHERE e às strind de parametros
	if ( $ccq3 != "" )	
	{
		$strWhereCQ.=((empty($strWhereCQ))?(" ( CloudCoverQ3 <= $ccq3 ) "):(" AND ( CloudCoverQ3 <= $ccq3 ) "));	
		$strParametrosCQ.="&Q3=$ccq3";		
		$strParametrosPorSateliteCQ.="&Q3=$ccq3";		
	}


	
	// Quantidade máxima de nuves 
	// Quadrante 4 (Q4)
	$ccq4=(isset($_GET['Q4']))?($_GET['Q4']):(100);
	$ccq4=($ccq4 == "")?(100):($ccq4);
				
	// Se a variável Q4 estiver definida e possir valor não vazio, a mesma será adicionada da
	// cláusula WHERE e às strind de parametros
	if ( $ccq4 != "" ) 
	{
		$strWhereCQ.=((empty($strWhereCQ))?(" ( CloudCoverQ4 <= $ccq4 ) "):(" AND ( CloudCoverQ4 <= $ccq4 ) "));	
		$strParametrosCQ.="&Q4=$ccq4";		
		$strParametrosPorSateliteCQ.="&Q4=$ccq4";		
	}
		
}



if ( isset($_GET['SATELITE']) && (!empty($satelite)))
{
	
	// Somente pro Landsat-8
	if ( $satelite == "L8" /* || $satelite == "CB4"  */)
	{		
		$strWhere.=((empty($strWhereCNQI))?($strWhereCNQI):(" AND $strWhereCNQI "));	
		$strParametros.=$strParametrosCNQI;		
		$strParametrosPorSatelite.=$strParametrosCNQI;								

	}
	else
	{
		// Para qualquer outro satélite selecionado
		$strWhere.=((empty($strWhereCQ))?($strWhereCQ):(" AND $strWhereCQ "));	
		$strParametros.=$strParametrosCQ;		
		$strParametrosPorSatelite.=$strParametrosCQ;

	}

	
}
else
{
	// Quando não for selecionado satélite

	if ( !empty($strWhereCQ))
	{

		
		if ( !empty($strWhereCNQI) )
		{
			$strWhere.= ((empty($strWhere))?(""):(" AND "));		
			$strWhere.= " ( $strWhereCNQI  OR $strWhereCQ  ) ";	
			$strParametros.= "$strParametrosCNQI" . "$strParametrosCQ";		
			$strParametrosPorSatelite.= "$strParametrosPorSateliteCNQI" . "$strParametrosPorSateliteCQ";
		}
		else
		{
			$strWhere.=((empty($strWhere))?(""):(" AND $strWhereCQ "));		
			$strParametros.=$strParametrosCQ;		
			$strParametrosPorSatelite.=$strParametrosPorSateliteCQ;																	
		}			
	}
	else
	{	

		if ( !empty($strWhereCNQI) )
		{			
			$strWhere.=((empty($strWhere))?($strWhereCNQI):(" AND $strWhereCNQI "));	
			$strParametros.=$strParametrosCNQI;		
			$strParametrosPorSatelite.=$strParametrosPorSateliteCNQI;				
		}
		
	}
	
}




// Satélites RAPIDEYE ou nenhum satélite selecionado
if ( empty($satelite) or ( strtoupper(substr($satelite,0,2)) == "RE" ) )
{


	
	// Região selecionada
	if (isset($_GET['REGIAOBRASIL']) )
	{
		$brregiao=strtoupper($_GET['REGIAOBRASIL']);
	
		// Se estiver definido e não for vazio irá adicionar o filtro por regiao à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($brregiao))
		{		
			$strWhere.=((empty($strWhere))?(" ( regiao = '$brregiao' ) "):(" AND ( regiao = '$brregiao' ) "));	
			$strParametros.="&REGIAOBRASIL=$brregiao";		
		}
	}
	
	
	
	
	// Fuso selecionado
	if (isset($_GET['FUSO']) )
	{
		$brfuso=strtoupper($_GET['FUSO']);
	
		// Se estiver definido e não for vazio irá adicionar o filtro por fuso à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($brfuso))
		{		
			$strWhere.=((empty($strWhere))?(" ( Fuso = $brfuso ) "):(" AND ( Fuso = $brfuso ) "));	
			$strParametros.="&FUSO=$brfuso";		
		}
	}
	
	
}






// Verificar e definir o tamanho da página de visualização (Número de registros por página)
//
if (isset($_GET['TAMPAGINA']) )
{
	$registros_por_pagina=strtoupper($_GET['TAMPAGINA']);

	if ( empty($registros_por_pagina))
	{
		$registros_por_pagina=20;	
	}
	else
	{		
		if ( $registros_por_pagina < 20 )
		{
			$registros_por_pagina=20;
		}
		else
		{
			if ( $registros_por_pagina > $maximo_registros_por_pagina )
			{
				$registros_por_pagina = $maximo_registros_por_pagina;
			}
		}
		
	}
}
else
{
	// Tamanho padrão da página de registros (Numero de registros)
	$registros_por_pagina=20;		
}




$strParametros.="&TAMPAGINA=$registros_por_pagina";
$strParametrosPorSatelite.="&TAMPAGINA=$registros_por_pagina";
	






// Caso o acionador seja o botão Região
if ( $trigger == "BTNREGIAO" )
{
	
	// Definição das variáveis responsáveis por armazenar as coordenadas da região seleconada
	$norte='';
	$sul='';
	$leste='';
	$oeste='';	
	$strWhereRegiao='';

	// Se a a coordenada NORTE estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['NORTE']) )
	{
		$norte = $_GET['NORTE'];
	}

	// Se a a coordenada SUL estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['SUL']) )
	{
		$sul = $_GET['SUL'];
	}

	// Se a a coordenada LESTE estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['LESTE']) )
	{
		$leste = $_GET['LESTE'];
	}

	// Se a a coordenada OESTE estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['OESTE']) )
	{
		$oeste = $_GET['OESTE'];
	}
	
		
	
	// Verifica se não existe alguma coordenada vazia
	if ( empty($norte) or  empty($sul) or  empty($leste) or  empty($oeste) )
	{
		// Apresenta uma mensagem informando sobre problema com as coordenadas da região
		mensagemRegiaoInvalida("$norte", "$sul", "$leste", "$oeste");
		return;	
	}
	else
	{
			// Adiciona à clausula WHERE por Região a instrução correta para pesquisar pela área informada
			
			// Os vértices da área se encontram dentro da área das imagens
			$strWhereRegiao=" ( " . 
							"((TL_Latitude <= $norte and TL_Latitude >= $sul ) and ( TL_Longitude <= $leste and TL_Longitude >= $oeste)) OR " .
			                "((TR_Latitude <= $norte and TR_Latitude >= $sul ) and ( TR_Longitude <= $leste and TR_Longitude >= $oeste)) OR " .
							"((BL_Latitude <= $norte and BL_Latitude >= $sul ) and ( BL_Longitude <= $leste and BL_Longitude >= $oeste)) OR " .
							"((BR_Latitude <= $norte and BR_Latitude >= $sul ) and ( BR_Longitude <= $leste and BR_Longitude >= $oeste)) OR " .
		
							"((TL_Latitude >= $norte and BL_Latitude <= $sul ) and ( TL_Longitude >= $oeste and TR_Longitude <= $leste)) OR " .							
			                "((TL_Latitude <= $norte and BL_Latitude >= $sul ) and ( TL_Longitude <= $oeste and TR_Longitude >= $leste)) OR " .
															
							"(($norte <= TL_Latitude and $norte >= BL_Latitude ) and ( $oeste >= TL_Longitude and $oeste <= TR_Longitude)) OR " .
			                "(($norte <= TL_Latitude and $norte >= BL_Latitude ) and ( $leste >= TL_Longitude and $leste <= TR_Longitude)) OR " .							
							"(($sul   <= TL_Latitude and $sul   >= BL_Latitude ) and ( $oeste >= TL_Longitude and $oeste <= TR_Longitude)) OR " .
			                "(($sul   <= TL_Latitude and $sul   >= BL_Latitude ) and ( $leste >= TL_Longitude and $leste <= TR_Longitude))" . " ) ";
			
		
			// Formata a cláusula WHERE geral adicionando a cláusula WHERE por Região
			$strWhere.=((empty($strWhere))?(" ( $strWhereRegiao ) "):(" AND ( $strWhereRegiao ) "));	
			
			// Adicona os parâmetros da região às strings que contém os parâmetros
			$strParametros.="&NORTE=$norte&SUL=$sul&LESTE=$leste&OESTE=$oeste";		
			$strParametrosPorSatelite.="&NORTE=$norte&SUL=$sul&LESTE=$leste&OESTE=$oeste";		
			
			//echo "<br> $strWhereRegiao <br><br>";
	}
	
}




// Caso o acionador seja o botão Interface
if ( $trigger == "BTNINTERFACE" )
{
	
	$lat='';
	$lon='';	
	$strWhereInterface='';

	// Se a a coordenada LAT estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['LAT']) )
	{
		$lat = $_GET['LAT'];
	}

	// Se a a coordenada LON estiver definida, armazena a mesma para uso posterior
	if (isset($_GET['LON']) )
	{
		$lon = $_GET['LON'];
	}

		
	
	// Verifica se não existe alguma coordenada vazia
	if ( empty($lat) or  empty($lon)  )
	{
		// Apresenta uma mensagem informando sobre problema com as coordenadas da região
		mensagemInterfaceInvalida("$lat", "$lon");
		return;	
	}
	else
	{
		
			// Adiciona à clausula WHERE por Coordenada a instrução correta para realizar a pesquisa
			$strWhereInterface=" ((TL_Latitude >= $lat and BL_Latitude <= $lat ) and ( TL_Longitude <= $lon and TR_Longitude >= $lon)) ";
		
			// Formata a cláusula WHERE geral adicionando a cláusula WHERE por Coordenada
			$strWhere.=((empty($strWhere))?(" $strWhereInterface "):(" AND $strWhereInterface "));	

			// Adicona os parâmetros da região às strings que contém os parâmetros
			$strParametros.="&LAT=$lat&LON=$lon";		
			$strParametrosPorSatelite.="&LAT=$lat&LON=$lon";				
	}
	
}





// Validação do valor da pagina atual passada como parâmetro
//
$pagina_atual=1;

if (isset($_GET['pg']) )
{
	$aux_paginaatual=$_GET['pg'];
	
	if ( is_numeric($aux_paginaatual) )
	{
		$pagina_atual=( is_int($aux_paginaatual) )?$aux_paginaatual:intval($aux_paginaatual);		
	}
}
// Valida o valor da página atual, impedindo que valores menor ou igual a zero sejam informados 	
$pagina_atual=( $pagina_atual < 1)?1:$pagina_atual;





// Inicia a conexão com a base de dados  

// Variáveis contendo informações para acesso ao banco de dados
/*
$dbhost = 'saci.dgi.inpe.br:3333';
$dbuser = 'dgi';
$dbpass = '';
*/



$dbhost = $GLOBALS["dbhostname"] . ':' . $GLOBALS["dbport"];
$dbuser = $GLOBALS["dbusername"] ;
$dbpass = $GLOBALS["dbpassword"] ;
$dbname = $GLOBALS["dbcatname"];


// Realizada a conexão com banco de dados
$conexao = mysql_connect($dbhost, $dbuser, $dbpass);


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



$cor_nula='';
$cor_cinza_fraco='#EFEFEF';
$cor_cinza_medio='#DFDFDF';
$cor_realce='#FFFFFF';
$cor_registro=$cor_cinza_medio;

// String contendo a cláusula WHERE para realizar o filtro de registros no banco de dados
//$strWhere=(empty($strWhere))?$strWhere:(" WHERE " . $strWhere);
//$strWhere=(empty($strWhere))?' WHERE Deleted = 99':($strWhere . ' AND Deleted = 99');


// String contendo a instrução SQL para realizar a contagem dos registros conforme 
// filtro gerado na cláusula WHERE
//$stringSQLTotal="SELECT count(satellite) as total FROM Scene " . $strWhere;
$stringSQLTotal="SELECT count(1) as total FROM Scene " . $strWhere;
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



// Valida a quantidade de páginas geradas com base no número de registros 
// retornados com o número da página informada

// Resto da divisão do total de registros pelo tamanho da página atual 
// Também indica o numero de registros da última página
$resto_divisao = ( $total_registros % $registros_por_pagina );  

// Total de páginas obtidas com base no tamanho da página atual (Variável registros_por_pagina)
$total_paginas = ( ( $total_registros - $resto_divisao ) / $registros_por_pagina );  


// Se o reso da divisão for maior que zero, então é necessário incrementar uma nova página
// ao total de páginas, pois existe uma página que contém u número de registros menor que o definido
// na váriavel registros_por_pagina
if ( $resto_divisao != 0 )
{
	$total_paginas = $total_paginas + 1;
}

// Valida o valor passado na variável pagina atual, pois ela não poderá ser maior que o total
// de páginas obtidos. Caso seja maior ela será igualada ao valor da última página
if ( $pagina_atual > $total_paginas	)
{
	$pagina_atual = $total_paginas;
}


// Obtem os números inicial e final dos registros a serem apresentados
$contagem_inicial=(($pagina_atual - 1) * $registros_por_pagina ) + 1;
$contagem_final=$contagem_inicial  + $registros_por_pagina - 1;

// Caso a contagem final obtida for maior que o número total de registrs retornados pela pesquisa, 
// a mesma será igualada ao valor do último registro
if ( $contagem_final > $total_registros )
{
	$contagem_final   = $total_registros;
}

// Define o valor do limite inicial de registros a ser retornado na cláusula LIMIT
$limite_inicial=$contagem_inicial - 1;



// Calculos relacionados à paginação


// Informações relacionadas à paginação
//

// Definição da cor de funco para os registros
$corAtivo='#000';       // Cor utilizada quando o botão estiver ativo
$corInativo='#999999';  // Cor utilizada quando o botão estiver inativo
$comandoInativo='return false'; // Comando a ser utilizado quando o botão estiver inativo


// Comando contendo a função a ser chamada para cada botão estiver ativo

// Define a linha de comando quando o botão "Ir Para" do topo da página estiver ativo
$irParaTopoAtivo='obtemDadosPaginado(document.getElementById(\'pgatualtopo\').value, \'' . $strParametros . '\')';
// Define a linha de comando quando o botão "Ir Para" da base da página estiver ativo
$irParaBaseAtivo='obtemDadosPaginado(document.getElementById(\'pgatualbase\').value, \'' . $strParametros . '\')';


// Define a linha de comando quando o botão "Primeira Página" estiver ativo
$primeiroAtivo='obtemDadosPaginado(1, \'' . $strParametros . '\')';
// Define a linha de comando quando o botão "Págna Anerior" estiver ativo
$anteriorAtivo='obtemDadosPaginado(' . ($pagina_atual - 1) . ', \'' . $strParametros . '\')';
// Define a linha de comando quando o botão "Próxima Página" estiver ativo
$proximoAtivo='obtemDadosPaginado(' . ($pagina_atual + 1) . ', \'' . $strParametros . '\')';
// Define a linha de comando quando o botão "Última Página" estiver ativo
$ultimoAtivo='obtemDadosPaginado(' . $total_paginas . ', \'' . $strParametros . '\')';


// Atribui os comandos acima às váriaveis que serão utilizadas para definir os botões
$botaoPrimeiro=$primeiroAtivo;
$botaoAnterior=$anteriorAtivo;
$corPrimeiro=$corAtivo;
$corAnterior=$corAtivo;	


$botaoProximo=$proximoAtivo;
$botaoUltimo=$ultimoAtivo;
$botaoIrParaTopo=$irParaTopoAtivo;
$botaoIrParaBase=$irParaBaseAtivo;

$corProximo=$corAtivo;
$corUltimo=$corAtivo;	
$corIrPara=$corAtivo;	


// Realizada validações com base na página atual e no total de páginas
if (  $pagina_atual == 1 )
{
	// Página atual igual à primeira página, sendo necessário tornar os
	// botões Primeiro e Anterior inativas
	$botaoPrimeiro=$comandoInativo;
	$botaoAnterior=$comandoInativo;
	
	// Define a cor dos botões como inativas
	$corPrimeiro=$corInativo;
	$corAnterior=$corInativo;	


	// Verifica se existe apenas 1 página
	if ( $total_paginas == 1 )
	{	
		// Página atual igual à primeira página e o toal de páginas igual a 1, sendo necessário tornar os
		// botões Próximo, Último e Ir Para (Topo e Base)  inativas		
		$botaoProximo=$comandoInativo;
		$botaoUltimo=$comandoInativo;
		$botaoIrParaTopo=$comandoInativo;
		$botaoIrParaBase=$comandoInativo;
		
		// Define a cor dos botões como inativas
		$corProximo=$corInativo;
		$corUltimo=$corInativo;	
		$corIrPara=$corInativo;					
	}
	
}
else
{
	// Verifica se a página atual é igual ao total de páginas
	if (  $pagina_atual == $total_paginas )
	{
			// Página atual igual ao total de páginas, sendo necessário tornar os
			// botões Próximo e Último inativos
			$botaoProximo=$comandoInativo;
			$botaoUltimo=$comandoInativo;
			//$botaoIrPara=$comandoInativo;
			
			// Define a cor dos botões como inativas
			$corProximo=$corInativo;
			$corUltimo=$corInativo;	
			//$corIrPara=$corInativo;							
	}
}


//echo "strParametros = $strParametros<br><br>";

//echo "strWhere = $strWhere<br><br>";


// Apresenta o cabeçalho com o painel de navegação e informações de páginas
//
echo '<center>';
echo '<table border="0" cellpadding="5" cellspacing="5" width="394">';
echo '<tr bgcolor="#DFDFDF" height="32">';

echo '    <td valign="top" align="left" valign="baseline">';
echo '		&nbsp;&nbsp;Página  <b>' . $pagina_atual . '</b> de ' . $total_paginas;
echo '    </td>';

echo '    <td valign="top" align="left">';
echo '		&nbsp;&nbsp;<b>' . $total_registros . '</b> registros encontrados.';
echo '    </td>';
echo '</tr>';
echo '</table>';
echo '</center>';



// Barra de navegação superior (Topo)
// Apresenta os botões Primeira Página, Página Anterior, Ir Para, Próxima Página e Última Página

echo '<table border="0" cellpadding="5" cellspacing="5" width="394">';
echo '<tr  bgcolor="#DFDFDF">';

// Botões referente aos comandos de navegação: Primeira Página e Página Anterior
echo '    <td valign="top" align="right">';
echo '    <div class="btn-group">';
echo '        <button class="btn" id="primeirotopo" onClick="ocultaToolTip(\'primeirotopo\') ; ' . $botaoPrimeiro . ' ; " data-toggle="tooltip" title="Primeiro" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body" onmouseover="mostraToolTip(\'primeirotopo\')"  onmouseout="ocultaToolTip(\'primeirotopo\')"><i class="icon-step-backward" style="color:' . $corPrimeiro . ';font-size:16px;"></i></button>';

echo '        <button class="btn" id="anteriortopo" onClick="ocultaToolTip(\'anteriortopo\') ; ' . $botaoAnterior . '" data-toggle="tooltip" title="Anterior" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body" onmouseover="mostraToolTip(\'anteriortopo\')"  onmouseout="ocultaToolTip(\'anteriortopo\')"><i class="icon-backward" style="color:' . $corAnterior . ';font-size:16px;"></i></button>';
echo '    </div>';
echo '    </td>';

echo '    <td valign="top" align="center" width="140">';
echo '    <div class="btn-group">';
    	
// Botões referente ao comando de navegação: Ir para a a página informada
echo '	    <button class="btn" id="textoirparatopo" onClick="return false" style="font-size:14px;">&nbsp;&nbsp;Ir para</button>';
echo '        <input id="pgatualtopo" name="pgatualtopo" type="number" value="' . $pagina_atual . '" min="1" max="' . $total_paginas . '" width="55" style="width:55px;text-align:center;vertical-align:top;alignment-adjust:top;font-size:15px;border:medium" align="top" onKeyPress="return validarNumero(event)">';
echo '        <button class="btn" id="irparatopo" onClick="ocultaToolTip(\'irparatopo\') ; ' . $botaoIrParaTopo . '" data-toggle="tooltip" title="Ir para a página" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'irparatopo\')"   onmouseout="ocultaToolTip(\'irparatopo\')"><i class="icon-ok" style="color:' . $corIrPara . ';font-size:16px;"></i></button>';
        
echo '    </div>';
echo '    </td>';

// Botões referente aos comandos de navegação: Última Página e Próxima Página
echo '    <td valign="top" align="left">';
echo '    <div class="btn-group">';
echo '        <button class="btn" id="proximotopo" onClick="ocultaToolTip(\'proximotopo\') ; ' . $botaoProximo . '" data-toggle="tooltip" title="Próximo" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'proximotopo\')"  onmouseout="ocultaToolTip(\'proximotopo\')"><i class="icon-forward" style="color:' . $corProximo . ';font-size:16px;"></i></button>';

echo '        <button class="btn" id="ultimotopo" onClick="ocultaToolTip(\'ultimotopo\') ; ' . $botaoUltimo . '" data-toggle="tooltip" title="Último" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'ultimotopo\')"  onmouseout="ocultaToolTip(\'ultimotopo\')"><i class="icon-step-forward" style="color:' . $corUltimo . ';font-size:16px;"></i></button>';
echo '    </div>';
echo '    </td>';

echo '</tr>';

echo '</table>';

echo '<br>';


// Fim do cabeçalho de navegação superior (Topo)




//Adiciona a possibilidade de inibir o usuário adicionar nnimagem atual para 
	// ser utilizada por funções em Javscript
	$strArrayInformacoesImagem="[ '" .  
								$indice  .  "', '" . 
								$ratual['SceneId'] . "', '"  . 
								$ratual['Satellite'] . "', '"  . 
								$ratual['Sensor'] . "', '"  . 
								$ratual['Date'] . "', '"  . 
								$ratual['CenterLatitude'] . "', '"  . 
								$ratual['CenterLongitude'] . "', '"  . 
								
								/*
								$ratual['TL_Latitude'] . "', '"  . 
								$ratual['TL_Longitude'] . "', '"  . 								
								$ratual['TR_Latitude'] . "', '"  . 
								$ratual['TR_Longitude'] . "', '"  . 

								$ratual['BR_Latitude'] . "', '"  . 
								$ratual['BR_Longitude'] . "', '"  . 								
								$ratual['BL_Latitude'] . "', '"  . 
								$ratual['BL_Longitude'] . "', '"  .
								*/
								$ratual['Area_UL_Lat'] . "', '"  . 
								$ratual['Area_UL_Lon'] . "', '"  . 								
								$ratual['Area_UR_Lat'] . "', '"  . 
								$ratual['Area_UR_Lon'] . "', '"  . 

								$ratual['Area_LR_Lat'] . "', '"  . 
								$ratual['Area_LR_Lon'] . "', '"  . 								
								$ratual['Area_LL_Lat'] . "', '"  . 
								$ratual['Area_LL_Lon'] . "', '"  . 

								$ratual['Path'] . "', '"  . 
								$ratual['Row'] . "', '"  . 								
								
								$ratual['CloudCoverQ1'] . "', '"  . 
								$ratual['CloudCoverQ2'] . "', '"  . 								
								$ratual['CloudCoverQ3'] . "', '"  . 
								$ratual['CloudCoverQ4'] . "', '"  . 
								
								$ratual['Regiao'] . "', '"  . 																
								$ratual['Fuso'] .  "', '"  . 		
								
								$ratual['Image_UL_Lat'] . "', '"  . 																
								$ratual['Image_UL_Lon'] . "', '"  . 																
								$ratual['Image_UR_Lat'] . "', '"  . 																
								$ratual['Image_UR_Lon'] . "', '"  . 																
								$ratual['Image_LR_Lat'] . "', '"  . 																
								$ratual['Image_LR_Lon'] . "', '"  . 																
								$ratual['Image_LL_Lat'] . "', '"  . 																
								$ratual['Image_LL_Lon'] . 
								"' ]";
	
	
	

	// Definição de uma array com informações da imagem atual para 
	// ser utilizada por funções em PHP
	$strArrayImagemAtual=array (  
								$indice  , 
								$ratual['SceneId'] , 
								$ratual['Satellite'] , 
								$ratual['Sensor'] , 
								$ratual['Date'] ,
								$ratual['CenterLatitude'] ,
								$ratual['CenterLongitude'] ,
								
								/*
								$ratual['TL_Latitude'] , 
								$ratual['TL_Longitude'] , 								
								$ratual['TR_Latitude'] ,
								$ratual['TR_Longitude'] , 

								$ratual['BR_Latitude'] ,
								$ratual['BR_Longitude'] ,
								$ratual['BL_Latitude'] ,
								$ratual['BL_Longitude'] ,
								*/
								
								$ratual['Area_UL_Lat'] , 
								$ratual['Area_UL_Lon'] , 								
								$ratual['Area_UR_Lat'] ,
								$ratual['Area_UR_Lon'] , 

								$ratual['Area_LR_Lat'] ,
								$ratual['Area_LR_Lon'] ,
								$ratual['Area_LL_Lat'] ,
								$ratual['Area_LL_Lon'] ,

								$ratual['Path'] , 
								$ratual['Row'] ,							
								
								$ratual['CloudCoverQ1'] ,
								$ratual['CloudCoverQ2'] ,								
								$ratual['CloudCoverQ3'] ,
								$ratual['CloudCoverQ4'] ,
								
								$ratual['Regiao'] ,
								$ratual['Fuso'] );
	
	
	
	// Definição de uma array com informações da coordenada central da imagem atual para 
	// ser utilizada por funções em Javscript	
	$strArrayCentralizar="[ '" .
								$ratual['CenterLatitude'] . "', '"  . 
								$ratual['CenterLongitude'] . 	
						  "' ]";
	
	
	$dirSateliteAtual='';
	$dirAnoMes='';
	
	// Formatar a hora da geração da imagem de acordo com o SceneId
	switch ( strtolower($ratual['Satellite']) )
	{
		// Aqua
		case 'a1':
			$horaScene=substr($ratual['SceneId'],16, 2) . ':' . substr($ratual['SceneId'],18, 2) . ':' . substr($ratual['SceneId'],20, 2);
			$tamanhoImagem='_MIN';
			$dirSateliteAtual='AQUA';
			break;
			
		// Terra
		case 't1':		
			$horaScene=substr($ratual['SceneId'],16, 2) . ':' . substr($ratual['SceneId'],18, 2) . ':' . substr($ratual['SceneId'],20, 2);
			$tamanhoImagem='_MIN';
			$dirSateliteAtual='TERRA';
			break;
			
		// Os satélites Rapideye possuem as informações de hora em posições semelhantes
		case 're1':
		case 're2':		
		case 're3':		
		case 're4':		
		case 're5':		
			$horaScene=substr($ratual['SceneId'],16, 2) . ':' . substr($ratual['SceneId'],18, 2) . ':' . substr($ratual['SceneId'],20, 2);
			$tamanhoImagem='_MIN';
			$dirSateliteAtual='RAPIDEYE' . substr($ratual['Satellite'], 2,1) ;
			break;
			
		// O satélite S-NPP possui as informações de hora em posições diferentes da posição dos satélites Aque e Terra
		case 'npp':	
			$horaScene=substr($ratual['SceneId'],17, 2) . ':' . substr($ratual['SceneId'],19, 2) . ':' . substr($ratual['SceneId'],21, 2);
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='S-NPP' ;
			break;

		// O satélite S-NPP 
		case 'ukdmc2':	
			$horaScene=substr($ratual['IngestDate'],11, 8);
			$tamanhoImagem='_MIN';
			$dirSateliteAtual='UK-DMC-2';			
			break;
			

		// O satélite ResourceSat-1 
		case 'p6':	
		case 'resourcesat1':	
		case 'resourcesat-1':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			$horaScene=gmdate("H:i:s", $ratual['CenterTime']*24*3600 );
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='RESOURCESAT1';
			break;			

		// O satélite ResourceSat-1 
		case 'res2':	
		case 'resourcesat2':	
		case 'resourcesat-2':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			//$horaScene="00:00:00";
			$horaScene=substr($ratual['IngestDate'],11, 8);
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='RESOURCESAT2';
			break;			


		// O satélite CBERS-2
		case 'cb2':	
		case 'cbers2':	
		case 'cbers-2':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			//$horaScene="00:00:00";
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			$horaScene=gmdate("H:i:s", $ratual['CenterTime']*24*3600 );
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='CBERS2';
			break;			


		// O satélite CEBERS-14
		case 'cb4':	
		case 'cbers4':	
		case 'cbers-4':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			//$horaScene="00:00:00";
			$horaScene=substr($ratual['IngestDate'],11, 8);
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='CBERS4';			
			$anoScene=substr($ratual['SceneId'],12, 4);
			$mesScene=substr($ratual['SceneId'],16, 2);			
			$dirSensor=substr($ratual['SceneId'],3, 3);			
			break;			



		// O satélite LANDSAT-8
		case 'l5':	
		case 'landsat5':	
		case 'landsat-5':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			//$horaScene="00:00:00";
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			$horaScene=gmdate("H:i:s", $ratual['CenterTime']*24*3600 );
			$anoScene=substr($ratual['SceneId'],10, 4);
			$mesScene=substr($ratual['SceneId'],14, 2);
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='LANDSAT5';
			$dirAnoMes= $anoScene . '_' . $mesScene;
			break;			



		// O satélite LANDSAT-8
		case 'l8':	
		case 'landsat8':	
		case 'landsat-8':	
			//$horaScene=substr($ratual['IngestDate'],11, 8);
			//$horaScene="00:00:00";
			$horaScene=substr($ratual['IngestDate'],11, 8);
			$tamanhoImagem='_MIN';			
			$dirSateliteAtual='LANDSAT8';
			break;			



	}
	
	
	// Define o diretório onde se encontra o quicklook da imagem atual
	$diretorio_quicklook='/QUICKLOOK/' . $dirSateliteAtual. '/' . strtoupper($ratual['Sensor']) . '/';

	if ( strtolower($ratual['Satellite']) == "l5" )
	{
		$diretorio_quicklook=$diretorio_quicklook . $dirAnoMes. '/' ;
	}
	
	if ( strtolower($ratual['Satellite']) == "cb4" )
	{
		$diretorio_quicklook=$diretorio_quicklook  . '/' . $anoScene. '/' ;
	}
	
	 	
	
	
	// Início ta tabela container dos registros das imagens
	//
	echo '<table border="0" cellpadding="0" cellspacing="0" width="394">';
	
	echo '<tr bgcolor="#CCCCCC">';

	echo '	<td colspan="4" style="height:2px">';
	echo '	<td>';
	
	echo '</tr>';	 
	
	// Define a cor de fundo da celula quando o mouse estiver sobre a mesma e a cor quando o mouse sair da área da céula
	echo '	<tr bgcolor="' . $cor_registro . '" onMouseOut="javascript:this.style.backgroundColor=\'' . $cor_registro . '\'" onMouseOver="javascript:this.style.backgroundColor=\'' . $cor_realce . '\'">';
	
	// Apresentação do quicklook da imagem
	echo '		<td width="140"  height="170">';
	echo '			<a  onclick="Javascript:chamaImgOverlay(' . $strArrayInformacoesImagem . ')">';
	
	echo '          <span id="imagemblink' . $indice . '" class="inativo">';
	
	echo '			<img src="' . $diretorio_quicklook . 'QL_' . $ratual['SceneId'] . $tamanhoImagem . '.png" border=0 id="linkminiatura' . $indice . '"  onmouseover="mostraToolTip(\'linkminiatura' . $indice . '\')"  onmouseout="ocultaToolTip(\'' . 'linkminiatura' . $indice . '\')" data-toggle="tooltip" title="Clique para exibir ou ocultar a projeção da imagem sobre o mapa" data-trigger="hover" data-animation="true" data-placement="bottom">';
	
	echo '			</span>';
	
	echo '			</a>';
	echo '		</td>';	


	echo '		<td width="10">';
	echo '			&nbsp;';
	echo '		</td>';	



	// Apresentação dos dados e comandos
	//
	echo '		<td width="240">';
	
	echo '			<font size="2">';	
	echo '			<table border="0" cellpadding="0" cellspacing="0" width="100%">';
	echo '			<tr height="34">';
	echo '				<td width="120" align="left">';
	echo '					<font size="2">' . $contagem_atual . '/' . $total_registros . '</font>';	
	echo '				</td>';

	// Apresenta o botão referente ao satélite relacionado à imagem atual
	// Utiliza a função retornaBotaoSatelite
	echo '				<td align="left">';
	echo '					' . retornaBotaoSatelite($indice, $ratual['Satellite'], $strParametrosPorSatelite);
	echo '				</td>';	
	echo '			</tr>';


	// Obtém a descrição do sátelite e o SceneId formatado
	$descricao_satelite='';
	$sceneidFormatado='';
	$sceneidTemp=$ratual['SceneId'];
	$sensorTemp=$ratual['Sensor'];
	$quadranteTemp=$ratual['Quadrante'];
	
	switch ( strtoupper($ratual['Satellite']) )
	{
		case 'A1':
		case 'AQUA':
			$descricao_satelite = 'AQUA';
			$sceneidFormatado="A1-MODIS " . substr($sceneidTemp, 7,4) . '-' . substr($sceneidTemp, 11,2) . '-' . 
			                                substr($sceneidTemp, 13,2) . ' ' .  substr($sceneidTemp, 16,6);
			break;
				
		case 'T1':
		case 'TERRA':
			$descricao_satelite = 'TERRA';
			$sceneidFormatado="T1-MODIS " . substr($sceneidTemp, 7,4) . '-' . substr($sceneidTemp, 11,2) . '-' . 
			                                substr($sceneidTemp, 13,2) . ' ' .  substr($sceneidTemp, 16,6);
			break;
				
		case 'NPP':
		case 'S-NPP':
			$descricao_satelite = 'S-NPP';  
			$sceneidFormatado="SNPP-VIIRS " . substr($sceneidTemp, 8,4) . '-' . substr($sceneidTemp, 12,2) . '-' . 
			                                  substr($sceneidTemp, 14,2) . ' ' .  substr($sceneidTemp, 17,6);
			break;
			
				
		case 'UKDMC2':
		case 'UK-DMC2':
			$descricao_satelite = 'UK-DMC 2'; 
			$sceneidFormatado="UKDMC2-SLIM " . substr($sceneidTemp, 10,4) . '-' . substr($sceneidTemp, 14,2) . '-' . 
			                                   substr($sceneidTemp, 16,2) . ' ' .  substr($sceneidTemp, 18,3). '/' .  
											   substr($sceneidTemp, 21,3);
			break;
			

		case 'P6':
		case 'RESOURCESAT1':
		case 'RESOURCESAT-1':
			$descricao_satelite = 'ResourceSat-1'; 
			$sceneidFormatado="P6-" . $sensorTemp . ' ' . substr($sceneidTemp, 6,3) . '/' . substr($sceneidTemp, 9,3) . ' ' . 
			                                   substr($sceneidTemp, 12,4) . '-' .  substr($sceneidTemp, 16,2). '-' .  
											   substr($sceneidTemp, 18,2);
			
			if ( $quadranteTemp != null and  $quadranteTemp != "" )
				$sceneidFormatado=$sceneidFormatado . '-' . $quadranteTemp;
			break;
			


		case 'RES2':
		case 'RESOURCESAT2':
		case 'RESOURCESAT-2':
			$descricao_satelite = 'ResourceSat-2';
			$sceneidFormatado="RES2-" . $sensorTemp . ' ' . substr($sceneidTemp, 8,3) . '/' . substr($sceneidTemp, 11,3) . ' ' . 
			                                   substr($sceneidTemp, 14,4) . '-' .  substr($sceneidTemp, 18,2). '-' .  
											   substr($sceneidTemp, 20,2);
			if ( $quadranteTemp != null and  $quadranteTemp != "" )
				$sceneidFormatado=$sceneidFormatado . '-' . $quadranteTemp;
											   
			break;
			

		case 'CB2':
		case 'CBERS2':
		case 'CBERS-2':
			$descricao_satelite = 'CBERS-2';
			$sceneidFormatado="CBERS2-" . $sensorTemp . ' ' . substr($sceneidTemp, 6,3) . '/' . substr($sceneidTemp, 9,3) . ' ' . 
			                                   substr($sceneidTemp, 12,4) . '-' .  substr($sceneidTemp, 16,2). '-' .  
											   substr($sceneidTemp, 18,2);
											   
			break;
			



		case 'CB4':
		case 'CBERS4':
		case 'CBERS-4':
			$descricao_satelite = 'CBERS-4';
			$sceneidFormatado="CBERS4-" . $sensorTemp . ' ' . substr($sceneidTemp, 6,3) . '/' . substr($sceneidTemp, 9,3) . ' ' . 
			                                   substr($sceneidTemp, 12,4) . '-' .  substr($sceneidTemp, 16,2). '-' .  
											   substr($sceneidTemp, 18,2);
											   
			break;
			


		case 'L5':
		case 'LANDSAT5':
		case 'LANDSAT-5':
			$descricao_satelite = 'LANDSAT-5';
			$sceneidFormatado="L5-" . $sensorTemp . ' ' . substr($sceneidTemp, 4,3) . '/' . substr($sceneidTemp, 7,3) . ' ' . 
			                                   substr($sceneidTemp, 10,4) . '-' .  substr($sceneidTemp, 14,2). '-' .  
											   substr($sceneidTemp, 16,2);
											   
			break;
			


		case 'L8':
		case 'LANDSAT8':
		case 'LANDSAT-8':
			$descricao_satelite = 'LANDSAT-8';
			$sceneidFormatado="L8-" . $sensorTemp . ' ' . substr($sceneidTemp, 5,3) . '/' . substr($sceneidTemp, 8,3) . ' ' . 
			                                   substr($sceneidTemp, 11,4) . '-' .  substr($sceneidTemp, 15,2). '-' .  
											   substr($sceneidTemp, 17,2);
											   
			break;
			


		case 'RE1':
		case 'RAPDIEYE1':
			$descricao_satelite = 'RAPIDEYE-1'; 
			$sceneidFormatado="RE1-" . $sensorTemp . ' ' . substr($sceneidTemp, 7,4) . '-' .  substr($sceneidTemp, 11,2). '-' .  
									   substr($sceneidTemp, 13,2) . ' ' . substr($sceneidTemp, 16,6) . '-' .  substr($sceneidTemp, 23,8);			
			break;
			
		case 'RE2':
		case 'RAPDIEYE2':
			$descricao_satelite = 'RAPIDEYE-2';
			$sceneidFormatado="RE2-" . $sensorTemp . ' ' . substr($sceneidTemp, 7,4) . '-' .  substr($sceneidTemp, 11,2). '-' .  
									   substr($sceneidTemp, 13,2) . ' ' . substr($sceneidTemp, 16,6) . '-' .  substr($sceneidTemp, 23,8);
			break;
			
		case 'RE3':
		case 'RAPDIEYE3':
			$descricao_satelite = 'RAPIDEYE-3';
			$sceneidFormatado="RE3-" . $sensorTemp . ' ' . substr($sceneidTemp, 7,4) . '-' .  substr($sceneidTemp, 11,2). '-' .  
									   substr($sceneidTemp, 13,2) . ' ' . substr($sceneidTemp, 16,6) . '-' .  substr($sceneidTemp, 23,8);
			break;
			
		case 'RE4':
		case 'RAPDIEYE4':
			$descricao_satelite = 'RAPIDEYE-4';
			$sceneidFormatado="RE4-" . $sensorTemp . ' ' . substr($sceneidTemp, 7,4) . '-' .  substr($sceneidTemp, 11,2). '-' .  
									   substr($sceneidTemp, 13,2) . ' ' . substr($sceneidTemp, 16,6) . '-' .  substr($sceneidTemp, 23,8);
			break;
			
		case 'RE5':
		case 'RAPDIEYE5':
			$descricao_satelite = 'RAPIDEYE-5';
			$sceneidFormatado="RE5-" . $sensorTemp . ' ' . substr($sceneidTemp, 7,4) . '-' .  substr($sceneidTemp, 11,2). '-' .  
									   substr($sceneidTemp, 13,2) . ' ' . substr($sceneidTemp, 16,6) . '-' .  substr($sceneidTemp, 23,8);
			break;
			
				
	}
	
	// Apresenta informações mais gerais relaconadas à imagem atual
	echo '			<tr>';
	echo '				<td colspan="2">';
	
	
	// Campos extras relacionados às imagens dos satélites da constelação RapidEye			
	if ( substr($sateliteAtual,0,2) == "re" )
	{		
		//echo '					ID: <font style="font-size:11px">' . $ratual['SceneId'] . '</font><br>';	
		echo '					<font style="font-size:11px">' . $sceneidFormatado . '</font><br>';	
		
	}
	else
	{
		echo '					<font style="font-size:12px">' . $sceneidFormatado . '</font><br>';	
	}
		
	
	$descricao_sensor = strtoupper($ratual['Sensor']);
	if ( $descricao_sensor == "LIS3") $descricao_sensor='LISS3';
	if ( $descricao_sensor == "AWIF") $descricao_sensor='AWIFS';
	
	
	echo '					Data:' . $ratual['Date'] . ' &nbsp;&nbsp;Hora:' . $horaScene . '<br>';
	echo '					Satelite: <font style="font-size:12px">' . $descricao_satelite . '</font>&nbsp;&nbsp;Sensor: <font style="font-size:12px">' . $descricao_sensor . '</font><br>';	
				
	// Campos extras relacionados às imagens do satélite Landsat-8	
		
	$coberturaNuvemValidada=validaCoberturaNuvemTotal($ratual['Image_CloudCover']);
	if ( strtoupper($ratual['Satellite']) == "L8"  /*||  strtoupper($ratual['Satellite']) == "CB4" */)
	{		
		echo '					% Nuvens: <b>' . $coberturaNuvemValidada . '</b>&nbsp;&nbsp; Qualidade: <b>' . $ratual['Image_Quality'] . '</b>';	
	}
		
			
	
	// Campos extras relacionados às imagns dos satélites da constelação RapidEye		
	/*	
	if ( substr($sateliteAtual,0,2) == "re" )
	{		
		echo '					Região: ' . $ratual['Regiao'] . '&nbsp;&nbsp; Fuso: ' . $ratual['Fuso'];	
	}
		
	*/		
	echo '				</td>';	
	echo '			</tr>';
	
	
	
	// Apresenta os botões de comandos relacionados à imagem atual
	// Os botões são os seguintes: 
	// Status da imagem 			Indica se a imagem esta visivel ou oculta no mapa
	// FootPrint					Permite visualizar o footprint da imagem na área do mapa
	// Visualizar imagem no mapa	Permite visualizar a imagem na área do mapa
	// Centralizar 					Permite centralizar o mapa com base nas coordenadaqs centrais da imagem
	// Informações detalhadas		Apresenta uma janela com informações detalhadas sobre a imagem
	// Informações básicas			Exibe/oculta área com ifnormações básicas logo abaixo do registro da imagem
	// Carrinho 					Adiciona a imagem atual ao carrinho de seleção de imagens
	//
	
	if( $habilitaCarrinho ) 
	{
		$cliqueCarrinho='chamaAdicionarAoCarrinho(' . $strArrayInformacoesImagem . ')'; 
	}
	else
	{
		$cliqueCarrinho='return false' ;
	}

	

	
	# Mensagem e cor do ícone que representa a situação da Imagem
	$msgStatusImagem="Imagem não controlada pelo Controle de Qualidade";	
	$corIconeStatusImagem='#DDDDDD';
	
	switch ( strtoupper($metodoCoberturaNuven) )
	{
		case 'A':
		
			$msgStatusImagem="Imagem não controlada pelo Controle de Qualidade";	
			$corIconeStatusImagem='#DDDDDD';
			break;	
										
		case 'M':

			switch ( $imagemDeletada )
			{
				case 0:		
					/*	
					if ( $sateliteAmbiental == "S" )
					{						
						$msgStatusImagem="Satélite Ambiental. Imagem não é submetida ao Controlde de Qualidade";	
						$corIconeStatusImagem='gray';						
					}
					else
					{				
						$msgStatusImagem="Imagem aprovada pelo Controlde de Qualidade";	
						$corIconeStatusImagem='green';
					}
					*/
					
					$msgStatusImagem="Imagem aprovada pelo Controlde de Qualidade";	
					$corIconeStatusImagem='green';
					
					
					break;	
								
				case 1:
					$msgStatusImagem="Imagem rejeitada pelo Controlde de Qualidade";	
					$corIconeStatusImagem='red';
					break;
			}
			break;		
		
	}
	
		
	
	
	echo '			<tr>';
	echo '				<td colspan="2"><br>';
		
	echo '					<div class="btn-group">';	
	
	//							Botão de indicação do status da exibição da imagem no mapa
	echo '						<button  class="btn" id="imgstatus' . $indice . '" style="width:35px;padding-left:3px;padding-right:3px"  data-toggle="tooltip" title="' . $msgStatusImagem . '" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body" onmouseover="mostraToolTip(\'' . 'imgstatus' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'imgstatus' . $indice . '\')"><span id="imgstatusblink' . $indice . '" class="inativo"><i class="icon-circle" id="iconstatus' . $indice . '" style="color:' . $corIconeStatusImagem . ';font-size:17px;"></i></span></button>';
	
	//							Botão de comando que permite exibir/ocultar a exibição do footprint da imagem no mapa
	echo '						<button  class="btn" id="footprint' . $indice . '" style="width:35px;padding-left:3px;padding-right:3px" onClick="ocultaToolTip(\'footprint' . $indice . '\') ; chamaFootPrint(' . $strArrayInformacoesImagem . ')"   data-toggle="tooltip" title="Exibe ou oculta o footprint referente à imagem sobre mapa" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body" onmouseover="mostraToolTip(\'' . 'footprint' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'footprint' . $indice . '\')"><span id="iconfootprintblink' . $indice . '" class="inativo"><i class="icon-check-empty" id="iconfootprint' . $indice . '" style="color:black;font-size:17px;"></i></class></button>';
	
	//							Botão de comando que permite exibir/ocultar a exibição da imagem no mapa
	echo '						<button  class="btn" id="verimagem' . $indice . '" style="width:35px;padding-left:3px;padding-right:3px" onClick="ocultaToolTip(\'verimagem' . $indice . '\') ; chamaImgOverlay(' . $strArrayInformacoesImagem . ')"   data-toggle="tooltip" title="Exibe ou oculta a projeção da imagem sobre o mapa" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'' . 'verimagem' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'verimagem' . $indice . '\')"><i class="icon-eye-open" id="iconimgoverlay' . $indice . '" style="color:black;font-size:17px;"></i></button>';
	
	//							Botão de comando que permite centralizar a imagem no mapa com base nas coordenadas centrais da imagem
	echo '						<button  class="btn" id="centralizar' . $indice . '" style="width:35px;padding-left:3px;padding-right:3px" onClick="ocultaToolTip(\'centralizar' . $indice . '\') ; chamaLocalizarImagem(' . $strArrayCentralizar . ')"   data-toggle="tooltip" title="Centraliza o mapa com base no centro desta imagem" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body" onmouseover="mostraToolTip(\'' . 'centralizar' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'centralizar' . $indice . '\')"><i class="icon-bullseye" style="color:black;font-size:17px;"></i></button>';
	

	//							Botão de comando que apresenta uma janela com informações detalhadas da imagem
	//echo '						<button  class="btn" id="detalhes' . $indice . '" style="width:32px;padding-left:3px;padding-right:3px" onClick="ocultaToolTip(\'detalhes' . $indice . '\') ; chamaInformacoesImagem(' . $strArrayInformacoesImagem . ')"   data-toggle="tooltip" title="Exibe informações detalhadas referente à imagem" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'' . 'detalhes' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'detalhes' . $indice . '\')"><i class="icon-list" style="color:black;font-size:17px;"></i></button>';



	//							Botão de comando que permite exibir/ocultar a exibição de informações básicas da imagem
	echo '						<button  class="btn" id="maisinfo' . $indice .'" style="width:35px;padding-left:3px;padding-right:3px" onClick="ocultaToolTip(\'maisinfo' . $indice . '\') ; verMaisInformacoes( ' . $strArrayInformacoesImagem . ')"   data-toggle="tooltip" title="Exibe ou oculta informações básicas sobre a imagem" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'' . 'maisinfo' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'maisinfo' . $indice . '\')"><i class="icon-double-angle-down" id="iconmaisinfo' . $indice . '"style="color:black;font-size:17px;"></i></button>';

	//							Botão de comando que permite adicionar a imagem ao carrinho de seleção de imagens
	echo '						<button  class="btn" id="carrinho' . $indice . '" style="width:35px;padding-left:3px;padding-right:3px;" onClick="ocultaToolTip(\'carrinho' . $indice . '\') ; ' . $cliqueCarrinho . '"   data-toggle="tooltip" title="Adiciona a imagem corrente ao carrinho" data-trigger="hover" data-animation="true" data-placement="bottom" data-container="body" onmouseover="mostraToolTip(\'' . 'carrinho' . $indice . '\')" onmouseout="ocultaToolTip(\'' . 'carrinho' . $indice . '\')"><i class="icon-shopping-cart" style="color:'  . $corCarrinho . ';font-size:17px;"></i></button>';		
	echo '					</div>';
		
	echo '				</td>';	
	echo '			</tr>';
	echo '			</table>';
	echo '			</font>';

	echo '		</td>';		
	
	echo '		<td id="colunaimagem' . $indice . '" width="5"> &nbsp;';		
	echo '		</td>';		
	
	echo '	</tr>';	
	echo '<table>';
	
	// Função que gera o código necessário para apresentação da área de informações básicas da imagem
	apresentaMaisDetalhesImagem( $strArrayImagemAtual, "" , $cor_realce );
	
	// Alterna a cor de fundo do registro de cada imagem apresentada
	$cor_registro=($cor_registro == $cor_cinza_fraco)?$cor_cinza_medio:$cor_cinza_fraco;
	$contagem_atual++;
	$indice++;


// Libera as variáveis relacionadas aos registros retornados e à conexão com o banco de dados
mysql_free_result( $registros );   
mysql_close($conexao);





// Apresenta o cabeçalho com o painel de navegação e informações de páginas
//
echo '<br>';

echo '<center>';
echo '<table border="0" cellpadding="5" cellspacing="5" width="394">';
echo '<tr bgcolor="#DFDFDF" height="32">';

echo '    <td valign="top" align="left" valign="baseline">';
echo '		&nbsp;&nbsp;Página  <b>' . $pagina_atual . '</b> de ' . $total_paginas;
echo '    </td>';

echo '    <td valign="top" align="left">';
echo '		&nbsp;&nbsp;<b>' . $total_registros . '</b> registros encontrados.';
echo '    </td>';
echo '</tr>';
echo '</table>';
echo '</center>';


// Barra de navegação inferior (Base)
// Apresenta os botões Primeira Página, Página Anterior, Ir Para, Próxima Página e Última Página

echo '<table border="0" cellpadding="5" cellspacing="5" width="394">';
echo '<tr  bgcolor="#DFDFDF">';

// Botões referentes aos comandos de navegação: Primeira Página e Página Anterior
echo '    <td valign="top" align="right">';
echo '    <div class="btn-group">';
echo '        <button class="btn" id="primeirobase" onClick="ocultaToolTip(\'primeirobase\') ; ' . $botaoPrimeiro . '" data-toggle="tooltip" title="Primeiro" data-trigger="hover" data-animation="true" data-placement="top"  data-container="body" onmouseover="mostraToolTip(\'primeirobase\')" onmouseout="ocultaToolTip(\'primeirobase\')"><i class="icon-step-backward" style="color:' . $corPrimeiro . ';font-size:16px;"></i></button>';

echo '        <button class="btn" id="anteriorbase" onClick="ocultaToolTip(\'anteriorbase\') ; ' . $botaoAnterior . '" data-toggle="tooltip" title="Anterior" data-trigger="hover" data-animation="true" data-placement="top" data-container="body" onmouseover="mostraToolTip(\'anteriorbase\')" onmouseout="ocultaToolTip(\'anteriorbase\')"><i class="icon-backward" style="color:' . $corAnterior . ';font-size:16px;"></i></button>';
echo '    </div>';
echo '    </td>';

echo '    <td valign="top" align="center" width="140">';
echo '    <div class="btn-group">';
    	
// Botões referente ao comando de navegação: Ir para a a página informada
echo '	    <button class="btn" id="textoirparabase" onClick="return false" style="font-size:14px;">&nbsp;&nbsp;Ir para</button>';
echo '        <input id="pgatualbase" name="pgatualbase" type="number" value="' . $pagina_atual . '" min="1" max="' . $total_paginas . '" width="55" style="width:55px;text-align:center;vertical-align:top;alignment-adjust:top;font-size:15px;border:medium" align="top" onKeyPress="return validarNumero(event)" onkeyup="sincronizarCampos(this)">';

echo '        <button class="btn" id="irparabase" onClick="ocultaToolTip(\'irparabase\') ; ' . $botaoIrParaBase . '" data-toggle="tooltip" title="Ir para a página" data-trigger="hover" data-animation="true" data-placement="top" data-container="body" onmouseover="mostraToolTip(\'irparabase\')" onmouseout="ocultaToolTip(\'irparabase\')"><i class="icon-ok" style="color:' . $corIrPara . ';font-size:16px;"></i></button>';
        
echo '    </div>';
echo '    </td>';

// Botões referentes aos comandos de navegação: Última Página e Próxima Página
echo '    <td valign="top" align="left">';
echo '    <div class="btn-group">';
echo '        <button class="btn" id="proximobase" onClick="ocultaToolTip(\'proximobase\') ; ' . $botaoProximo . '" data-toggle="tooltip" title="Próximo" data-trigger="hover" data-animation="true" data-placement="top" data-container="body" onmouseover="mostraToolTip(\'proximobase\')" onmouseout="ocultaToolTip(\'proximobase\')"><i class="icon-forward" style="color:' . $corProximo . ';font-size:16px;"></i></button>';

echo '        <button class="btn" id="ultimobase" onClick="ocultaToolTip(\'ultimobase\') ; ' . $botaoUltimo . '" data-toggle="tooltip" title="Último" data-trigger="hover" data-animation="true" data-placement="top" data-container="body" onmouseover="mostraToolTip(\'ultimobase\')" onmouseout="ocultaToolTip(\'ultimobase\')"><i class="icon-step-forward" style="color:' . $corUltimo . ';font-size:16px;"></i></button>';
echo '    </div>';
echo '    </td>';

echo '</tr>';

echo '</table>';

// Fim do cabeçalho de navegação superior (Topo)

?>
