<?php 
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
error_reporting(E_ALL);



// Cofigura o timezone
if(function_exists('date_default_timezone_set') )
{
   date_default_timezone_set('UTC');
}
else
{
   putenv("TZ=UTC");
}



/*
Função: formataDataAnoMesDia
Convete a data passada como paâmetro de dia/mês/ano para ano-mês-dia

parData		Data a ser convertida
*/
function formataDataAnoMesDia( $parData )
{
	$ano = substr($parData, 6, 4);  // Obtém o ano 
	$mes = substr($parData, 3, 2);  // Obtém o mês 
	$dia = substr($parData, 0, 2);  // Obtém o dia 
	
	// Formata a data a se retornada
	$dataFormatada = "$ano-$mes-$dia";
	return $dataFormatada;
}









/**
 * Supplementary json_encode in case php version is < 5.2 (taken from http://gr.php.net/json_encode)
*/
if (!function_exists('json_encode'))
{
    function json_encode($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a))
            {
                //static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				static $jsonReplaces = array(array("\\", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return "'" . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . "'";
            }
            else
            return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = json_encode($v);
            return '[' . join(', ', $result) . "\n" . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			
			if ( $k == "results" ) 
	            return '{' . join(', ', $result) . '}';
			else
    	        return "\n" . '{' . join(', ', $result) . '}';
			
        }
    }
}








/* 
	Variável de controle do script
	Utilizada somente para saber se o script foi executado pela origem correta
*/
if (! isset($_REQUEST['key']) )
{
	echo 'CHAVE DE ACESSO AO SERVICO NAO INFORMADA';
	return;	
}
else
{
	$chaveAcesso = 	$_REQUEST['key'];	
}


// Variáveis


// Servidor dos quicklooks
$servidor_quicklook='www2.dgi.inpe.br';

// diretório base dos quicklooks
$diretorio_quicklook='/QUICKLOOK/';

// Quantidade máxima de registros por página 
// Necessáro para manter a performance na visualização dos resultados
$maximo_retorno=100;


// String contendo a cláusula de restrição/pesquisa
$strWhere='';
// String com os parametros formatados a serem utilizados por links de pesquisa
$strParametros='';
// String com os parametros formatados a serem utilizados por links de pesquisa com seleção por satélite
$strParametrosPorSatelite='';


$satelite='';  
$sensor=''; 
$sceneid='';


// String contendo a cláusula WHERE para realizar o filtro de registros no banco de dados
$strWhere=' WHERE ( Desativado = "N" AND Catalogo > 1 AND CloudCoverMethod = "M" )';






/* 
	Formata o acionador/gatilho do evento, o botão pelo qual foi disparado o evento
	Utilizado para saber qual botão disparou o evento e dessa forma poder utilizar os 
	campos corretamente na formulação da clausula de restrição da pesquisa no banco de dados
*/
$qtdeSceneId=0;
if (isset($_REQUEST['LISTASCENEID']) )
{
	$parametroSceneId=strtoupper($_REQUEST['LISTASCENEID']);
	$arraySceneId=explode(',', $parametroSceneId);	
	$qtdeSceneId=count($arraySceneId);
	
	$strItensSceneId=' AND SceneId IN ( ';
	for ( $contadorSceneId = 0; $contadorSceneId < $qtdeSceneId; $contadorSceneId++)
	{
		$strItensSceneId .= (  $contadorSceneId > 0 )?", ":"";
		$strItensSceneId .= "'" . $arraySceneId[$contadorSceneId] . "'";
	}	
	$strItensSceneId .= ' ) ';
	
	
	$strWhere .= $strItensSceneId;
	

}
else
{
// Início caso não seja informados os SceneId

	
	
	
	// Nome do satelite selecionado
	if (isset($_REQUEST['SATELITE']) )
	{
		$satelite=strtoupper($_REQUEST['SATELITE']);
		
		// Se estiver definido e não for vazio irá adicionar o filtro por satélite à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($satelite))
		{		
			$strWhere.=" AND ( satellite = '$satelite' ) ";	
		}
	
	}
	
	
	
	
	// Nome do sensor selecionado
	if (isset($_REQUEST['SENSOR']) )
	{
		$sensor=strtoupper($_REQUEST['SENSOR']);
	
		// Se estiver definido e não for vazio irá adicionar o filtro por sensor à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($sensor))
		{		
			$strWhere.=((empty($strWhere))?(" ( sensor = '$sensor' ) "):(" AND ( sensor = '$sensor' ) "));	
		}
	}
	
	
	
	
	
	
	
	// Validação de datas
	//
	
	$dataini='';
	$datafim='';
	
	
	// Data inicial do período
	if ( isset($_REQUEST['DATAINI']) )
	{
		$dataini = $_REQUEST['DATAINI'];
	}
	
	
	// Data final do período
	if ( isset($_REQUEST['DATAFIM']) )
	{
		$datafim = $_REQUEST['DATAFIM'];
	}
	
	
	
	if ( (! empty($dataini)) and (! empty($datafim)) )
	{			
		// Data final for mais antiga que a data inicial
		if ( formataDataAnoMesDia($dataini)  >  formataDataAnoMesDia($datafim) )
		{
			$dataini = $_REQUEST['DATAFIM'];
			$datafim = $_REQUEST['DATAINI'];	
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
	}
	
	
	
	
	
	
	// Órbita (Path)
	// -------------
	// Validação relacionada aos valores das órbitas
	
	
	// Orbita inicial
	if (isset($_REQUEST['ORBITAINI']) )
	{
		$orbitaini=strtoupper($_REQUEST['ORBITAINI']);
	}
	
	
	// Orbita final
	if (isset($_REQUEST['ORBITAFIM']) )
	{
		$orbitafim=strtoupper($_REQUEST['ORBITAFIM']);
	}
	
	
	
	// Orbita inicial
	if (isset($_REQUEST['ORBITAINI']) and isset($_REQUEST['ORBITAFIM']) )
	{
		// Se estiver definido e não for vazio irá adicionar o filtro por órbita à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($orbitaini) and ! empty($orbitafim) )
		{		
			$strWhere.=((empty($strWhere))?(""):(" AND "));	
			$strWhere.= " ( CONVERT(Path, UNSIGNED INTEGER) >= CONVERT('$orbitaini', UNSIGNED INTEGER) and CONVERT(Path, UNSIGNED INTEGER) <= CONVERT('$orbitafim', UNSIGNED INTEGER) ) ";
		}
	}
	else
	{
		
		// Somente o valor do campo Orbita inicial foi informado			
		if (isset($_REQUEST['ORBITAINI']) )
		{
			// Se estiver definido e não for vazio irá adicionar o filtro por órbita à
			// cláusula WHERE e à string de parâmetros
			if ( ! empty($orbitaini) )
			{
				$strWhere.=((empty($strWhere))?(""):(" AND "));	
				$strWhere.=" ( CONVERT(Path, UNSIGNED INTEGER) =  CONVERT('$orbitaini', UNSIGNED INTEGER) ) ";
			}
		}
		
		// Somente o valor do campo Orbita fim foi informado
		if (isset($_REQUEST['ORBITAFIM']) )
		{
				// cláusula WHERE e à string de parâmetros
				if ( ! empty($orbitafim) )
				{		
					$strWhere.=((empty($strWhere))?(""):(" AND "));	
					$strWhere.=" ( CONVERT(Path, UNSIGNED INTEGER) =  CONVERT('$orbitafim', UNSIGNED INTEGER) ) ";	
				}	
			
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	// Ponto (Row)
	// -----------
	// Validação relacionada aos valores dos pontos (rows)
	
	
	// Ponto inicial
	if (isset($_REQUEST['PONTOINI']) )
	{
		$pontoini=strtoupper($_REQUEST['PONTOINI']);
	}
	
	
	// Ponto final
	if (isset($_REQUEST['PONTOFIM']) )
	{
		$pontofim=strtoupper($_REQUEST['PONTOFIM']);
	}
	
	
	
	// Ponto inicial
	if (isset($_REQUEST['PONTOINI']) and isset($_REQUEST['PONTOFIM']) )
	{
		// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
		// cláusula WHERE e à string de parâmetros
		if ( ! empty($pontoini) and ! empty($pontofim) )
		{		
			$strWhere.=((empty($strWhere))?(""):(" AND "));
			$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) >=  CONVERT('$pontoini', UNSIGNED INTEGER) and  CONVERT(Row, UNSIGNED INTEGER) <=  CONVERT('$pontofim', UNSIGNED INTEGER) ) ";	
		}
	}
	else
	{
		
		// Somente o valor do campo ponto inicial foi informado			
		if (isset($_REQUEST['PONTOINI']) )
		{
			// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
			// cláusula WHERE e à string de parâmetros
			if ( ! empty($pontoini) )
			{		
				$strWhere.=((empty($strWhere))?(""):(" AND "));
				$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) =  CONVERT('$pontoini', UNSIGNED) ) ";	
			}
		}	
			
		
		// Somente o valor do campo ponto fim foi informado	
		if (isset($_REQUEST['PONTOFIM']) )
		{
			// Se estiver definido e não for vazio irá adicionar o filtro por ponto à
			// cláusula WHERE e à string de parâmetros
			if ( ! empty($pontofim) )
			{		
				$strWhere.=((empty($strWhere))?(""):(" AND "));
				$strWhere.=" ( CONVERT(Row, UNSIGNED INTEGER) =  CONVERT('$pontofim', UNSIGNED INTEGER) ) ";	
			}
		}	
	}

}
// Fim caso não seja informados os SceneId

// Inicia a conexão com a base de dados  

// Variáveis contendo informações para acesso ao banco de dados

$dbhost = '150.163.134.105:3333';
$dbuser = 'gerente';
$dbpass = 'gerente.200408';
$dbname = 'catalogo';


// Realizada a conexão com banco de dados
$conexao = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $conexao);


// Verifica se ocorreu erro ao realizar a conexão com o banco de dados
if(! $conexao )
{
	echo 'Erro de conexao ' . mysql_errno() . ' :: ' . mysql_error();
	exit;	
}





// Validas chave de acesso ao serviço
$chaveAcessoCodificada = sha1($chaveAcesso);
$stringSQLAcessoServico='SELECT chave FROM ServicesPermissions WHERE chave = "' . $chaveAcessoCodificada . '"';

$registroAcesso = mysql_query( $stringSQLAcessoServico, $conexao );
$totalRegistros = mysql_num_rows($registroAcesso);


if ( $totalRegistros == 0 )
{
	echo 'CHAVE DE ACESSO AO SERVICO INEXISTENTE OU INVALIDA';
	return;	
}

mysql_free_result( $registroAcesso );   





// Apresenta registros

// Inicializa variáveis auxiliares
$indice=0;
$contagem_atual=0;


// String com a lista de campos
$strCamposScene="SceneId, Satellite, Sensor, Path, Row, Date, Orbit, CenterTime, CenterLatitude, CenterLongitude, TL_Latitude, TL_Longitude, " .
			"TR_Latitude, TR_Longitude, BL_Latitude, BL_Longitude, BR_Latitude, BR_Longitude, CloudCoverMethod," .
			"CloudCoverQ1, CloudCoverQ2, CloudCoverQ3, CloudCoverQ4, IngestDate, Regiao, Fuso, Deleted, Dataset, Quadrante, " .			
			"Image_UL_Lat, Image_UL_Lon, Image_UR_Lat, Image_UR_Lon, Image_LR_Lat, Image_LR_Lon, Image_LL_Lat, Image_LL_Lon, " .
			"Area_UL_Lat, Area_UL_Lon, Area_UR_Lat, Area_UR_Lon, Area_LR_Lat, Area_LR_Lon, Area_LL_Lat, Area_LL_Lon, " .
			"SateliteAmbiental, " .
			"Image_CloudCover, Image_Quality ";
			

// String contendo a instrução de pesquisa com o limite de registrs s serem retornados
$stringSQLPesquisa="SELECT $strCamposScene FROM Scene " . $strWhere . "  ORDER BY Date DESC, SceneId Desc LIMIT 0,$maximo_retorno";
// Resgistros retornados
$registros = mysql_query( $stringSQLPesquisa, $conexao );


$arrayDados=array();



// Realiza a leitura de cada registro e atribui à variável ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $registros ) ) 
{	

	$sateliteAtual=strtolower($ratual['Satellite']);
	$imagemDeletada=$ratual['Deleted'];
	$metodoCoberturaNuven=$ratual['CloudCoverMethod'];
	$sateliteAmbiental=$ratual['SateliteAmbiental'];
	
	

	
	
	
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
		 	
	
	$linkQLMin=  $servidor_quicklook . $diretorio_quicklook . 'QL_' . $ratual['SceneId'] .'_MIN.png';
	$linkQLGrd=  $servidor_quicklook . $diretorio_quicklook . 'QL_' . $ratual['SceneId'] .'_GRD.png';
	








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




		
	
	$descricao_sensor = strtoupper($ratual['Sensor']);
	if ( $descricao_sensor == "LIS3") $descricao_sensor='LISS3';
	if ( $descricao_sensor == "AWIF") $descricao_sensor='AWIFS';
			
	
				
			
	
	$arrayRegistroAtual = array ( 	'SceneId' => $ratual['SceneId'], 									
									'SceneIdCatalogo' => $sceneidFormatado,
									
									'SateliteId' => $ratual['Satellite'],
									'SateliteDescricao' => $descricao_satelite,
		
	
									'Sensor' => $ratual['Sensor'] , 
									'Date' => $ratual['Date'] ,
									'CenterLatitude' => $ratual['CenterLatitude'] ,
									'CenterLongitude' => $ratual['CenterLongitude'] ,
																		
									'Area_UL_Lat' => $ratual['Area_UL_Lat'] , 
									'Area_UL_Lon' => $ratual['Area_UL_Lon'] , 								
									'Area_UL_Lon' => $ratual['Area_UL_Lon'] ,
									'Area_UR_Lon' => $ratual['Area_UR_Lon'] , 
	
									'Area_LR_Lat' => $ratual['Area_LR_Lat'] ,
									'Area_LR_Lon' => $ratual['Area_LR_Lon'] ,
									'Area_LL_Lat' => $ratual['Area_LL_Lat'] ,
									'Area_LL_Lon' => $ratual['Area_LL_Lon'] ,
	
																		
									'FootPrint_UL_Lat' => $ratual['Image_UL_Lat'] , 
									'FootPrint_UL_Lon' => $ratual['Image_UL_Lon'] , 								
									'FootPrint_UL_Lon' => $ratual['Image_UL_Lon'] ,
									'FootPrint_UR_Lon' => $ratual['Image_UR_Lon'] , 
	
									'FootPrint_LR_Lat' => $ratual['Image_LR_Lat'] ,
									'FootPrint_LR_Lon' => $ratual['Image_LR_Lon'] ,
									'FootPrint_LL_Lat' => $ratual['Image_LL_Lat'] ,
									'FootPrint_LL_Lon' => $ratual['Image_LL_Lon'] ,
	
									'Orbita' => $ratual['Path'] , 
									'Ponto' => $ratual['Row'] ,							
									
									'CoberturaNuvenQ1' => $ratual['CloudCoverQ1'] ,
									'CoberturaNuvenQ2' => $ratual['CloudCoverQ2'] ,								
									'CoberturaNuvenQ3' => $ratual['CloudCoverQ3'] ,
									'CoberturaNuvenQ4' => $ratual['CloudCoverQ4'] ,
									
									'QuickLookMinimo' => $linkQLMin , 
									'QuickLookGrande' => $linkQLGrd,
									
									'L8_CloudCover' => $ratual['Image_CloudCover'] , 
									'L8_Quality' => $ratual['Image_Quality']  );
	
	array_push($arrayDados, $arrayRegistroAtual);
	
	$contagem_atual++;
	$indice++;
		
}

$arrayGeraJSON=array( 'results' => $arrayDados);
// Libera as variáveis relacionadas aos registros retornados e à conexão com o banco de dados
mysql_free_result( $registros );   
mysql_close($conexao);


//$jsonDados = json_encode($arrayDados);arrayGeraJSON
$jsonDados = json_encode($arrayGeraJSON);

header('Content-Type:application/json'); 
header('Content-Length: ' . strlen($jsonDados));
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0'); // Proxies	
echo $jsonDados;
?>
