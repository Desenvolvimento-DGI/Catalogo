<?php 

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



// String com a lista de campos
$strCamposScene="SceneId, Satellite, Sensor, Path, Row, Date, Orbit, CenterTime, CenterLatitude, CenterLongitude, TL_Latitude, TL_Longitude, " .
			"TR_Latitude, TR_Longitude, BL_Latitude, BL_Longitude, BR_Latitude, BR_Longitude, CloudCoverMethod," .
			"CloudCoverQ1, CloudCoverQ2, CloudCoverQ3, CloudCoverQ4, IngestDate, Regiao, Fuso, Deleted, Dataset, Quadrante, " .			
			"Image_UL_Lat, Image_UL_Lon, Image_UR_Lat, Image_UR_Lon, Image_LR_Lat, Image_LR_Lon, Image_LL_Lat, Image_LL_Lon, " .
			"Area_UL_Lat, Area_UL_Lon, Area_UR_Lat, Area_UR_Lon, Area_LR_Lat, Area_LR_Lon, Area_LL_Lat, Area_LL_Lon, " .
			"SateliteAmbiental, " .
			"Image_CloudCover, Image_Quality ";
	
$strWhere=" WHERE ( Desativado = 'N' AND Satellite = 'CB4' AND Sensor = 'MUX' ) ";
$strGroupBy=" GROUP BY Path, Row ";
$strOrderBy=" ORDER BY Path, Row ";


			
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = '<kml xmlns="http://earth.google.com/kml/2.1">';
$kml[] = '<Document>';
$kml[] = '<name>CBERS4-MUX</name>';

$kml[] = '';

$kml[] = '<Style id="normal">';
$kml[] = '    <LineStyle>';
$kml[] = '		  <width>1</width>';
$kml[] = '        <color>ff000000</color>';
$kml[] = '    </LineStyle>';
$kml[] = '    <PolyStyle>';
$kml[] = '		  <fill>0</fill>';
//$kml[] = '        <color>a6ffffff</color>';
$kml[] = '    </PolyStyle>';
$kml[] = '</Style>';

$kml[] = '<Style id="highlight">';
$kml[] = '    <LineStyle>';
$kml[] = '		  <width>2</width>';
$kml[] = '        <color>a60000ff</color>';
$kml[] = '    </LineStyle>';
$kml[] = '    <PolyStyle>';
$kml[] = '		  <fill>1</fill>';
$kml[] = '        <color>55ffffff</color>';
$kml[] = '    </PolyStyle>';
$kml[] = '</Style>';

$kml[] = '<StyleMap id="formatacao">';
$kml[] = '    <Pair>';
$kml[] = '        <key>normal</key>';
$kml[] = '        <styleUrl>#normal</styleUrl>';
$kml[] = '    </Pair>';
$kml[] = '    <Pair>';
$kml[] = '        <key>highlight</key>';
$kml[] = '        <styleUrl>#highlight</styleUrl>';
$kml[] = '    </Pair>';
$kml[] = '</StyleMap>';

$kml[] = '';




$stringSQLDate="SELECT DISTINCT Date, DAY(Date) as Dia, MONTH(Date) as Mes, YEAR(Date) as Ano  FROM Scene $strWhere AND Date = '2015-01-01' ORDER BY Date";
$registrosData = mysql_query( $stringSQLDate, $conexao );


// Data
while ( $ratualData = mysql_fetch_assoc( $registrosData ) ) 
{	
	$dataAtual = $ratualData['Date'];
	$diaAtual = $ratualData['Dia'];
	$mesAtual = $ratualData['Mes'];
	$anoAtual = $ratualData['Ano'];
	
	$dataAtualFormatada=$diaAtual . '/' . $mesAtual . '/' . $anoAtual;	
	
	$kml[] = '<Folder>';
	
	$kml[] = '	<name>' .  $dataAtualFormatada . '</name>';
	$kml[] = '	<Snippet>Todas bases para a data ' .  $dataAtualFormatada . '</Snippet>';
	$kml[] = '	<open>0</open>';
	$kml[] = '	<visibility>1</visibility>';
				


	$strWhereBase = " $strWhere  AND ( Date = '$dataAtual' ) ";
	$stringSQLBase="SELECT DISTINCT Path  FROM Scene $strWhereBase ORDER BY Date";
	$registrosPath = mysql_query( $stringSQLBase, $conexao );

	// Base
	while ( $ratualBase = mysql_fetch_assoc( $registrosPath ) ) 
	{

		$baseAtual = $ratualBase['Path'];	
		$kml[] = '	<Folder>';
		
		$kml[] = '		<name>' .  $baseAtual . '</name>';
		$kml[] = '		<Snippet>Todas os pontos para a base '. $baseAtual . '</Snippet>';
		$kml[] = '		<open>0</open>';
		$kml[] = '		<visibility>1</visibility>';
				

	
	
		$strWherePonto = "$strWhere AND ( Date = '$dataAtual'  AND Path = '$baseAtual') ";
		$stringSQLPonto="SELECT $strCamposScene FROM Scene $strWherePonto $strGroupBy $strOrderBy";
		$registrosPonto = mysql_query( $stringSQLPonto, $conexao );			
		
		// Ponto
		while ( $ratualPonto = mysql_fetch_assoc( $registrosPonto ) ) 
		{	
			
							
			$centerLatitude=$ratualPonto['CenterLatitude'];
			$centerLongitude=$ratualPonto['CenterLongitude'];
		
			$footPrint_UL_Lat=$ratualPonto['Image_UL_Lat'];
			$footPrint_UL_Lon=$ratualPonto['Image_UL_Lon'];
			
			$footPrint_UR_Lat=$ratualPonto['Image_UR_Lat'];
			$footPrint_UR_Lon=$ratualPonto['Image_UR_Lon'];
				
			$footPrint_LR_Lat=$ratualPonto['Image_LR_Lat'];
			$footPrint_LR_Lon=$ratualPonto['Image_LR_Lon'];
			
			$footPrint_LL_Lat=$ratualPonto['Image_LL_Lat'];
			$footPrint_LL_Lon=$ratualPonto['Image_LL_Lon'];
		
			$pontoAtual = $ratualPonto['Row'];	
			
		
			$kml[] = '			<Placemark>';
			$kml[] = '			<name>' . $baseAtual . '/' . $pontoAtual . '</name>';
			$kml[] = '			<Snippet></Snippet>';
			$kml[] = '			<styleUrl>#formatacao</styleUrl>';
			$kml[] = '			<MultiGeometry>';
			$kml[] = '				<Polygon>';
			$kml[] = '					<tessellate>1</tessellate>';
			$kml[] = '					<extrude>1</extrude>';
			$kml[] = '					<altitudeMode>clampedToGround</altitudeMode>';							
			$kml[] = '					<outerBoundaryIs>';
			$kml[] = '						<LinearRing>';
			$kml[] = '							<coordinates>';
			$kml[] = '								' . $footPrint_UL_Lon . ','. $footPrint_UL_Lat . ',0 ';
			$kml[] = '								' . $footPrint_UR_Lon . ','. $footPrint_UR_Lat . ',0 ';
			$kml[] = '								' . $footPrint_LR_Lon . ','. $footPrint_LR_Lat . ',0 ';
			$kml[] = '								' . $footPrint_LL_Lon . ','. $footPrint_LL_Lat . ',0 ';
			$kml[] = '								' . $footPrint_UL_Lon . ','. $footPrint_UL_Lat . ',0 ';
			$kml[] = '							</coordinates>';
			$kml[] = '						</LinearRing>';
			$kml[] = '					</outerBoundaryIs>';
			$kml[] = '				</Polygon>';
			$kml[] = '				<Point>';
			$kml[] = '					<extrude>1</extrude>';
			$kml[] = '					<altitudeMode>clampedToGround</altitudeMode>';
			$kml[] = '					<coordinates>' . $centerLongitude . ',' . $centerLatitude . ',0 </coordinates>';
			$kml[] = '				</Point>';
			$kml[] = '			</MultiGeometry>';
			$kml[] = '			</Placemark>';
					
				
		} // Ponto
		
		$kml[] = '	</Folder>';

	} // Base

	$kml[] = '</Folder>';
} // Data




$kml[] = '';
$kml[] = '</Document>';
$kml[] = '</kml>';


// Libera as variáveis relacionadas aos registros retornados e à conexão com o banco de dados
mysql_free_result( $registrosData );   
mysql_close($conexao);

$kmlOutput = join("\n", $kml);
//header ('Content-Type: application/vnd.google-earth.kml+xml\n');
header ('Content-Type: text/plain\n');
echo $kmlOutput;
?>