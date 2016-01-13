﻿<?php 

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
			"SateliteAmbiental, YEAR(Date) as Ano, " .
			"Image_CloudCover, Image_Quality ";
	
$strWhere=" WHERE ( Desativado = 'N' AND Satellite = 'CB4' AND Sensor = 'MUX' ) ";

// String contendo a instrução de pesquisa com o limite de registrs s serem retornados
$stringSQLPesquisa="SELECT $strCamposScene FROM Scene " . $strWhere . "  ORDER BY Path, Row ";
// Resgistros retornados
$registros = mysql_query( $stringSQLPesquisa, $conexao );


			
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = '<kml xmlns="http://earth.google.com/kml/2.1">';
$kml[] = '<Document>';

$kml[] = '<name>CBERS4-MUX: FOOTPRINTS E CENAS</name>';


$kml[] = '<Style id="normal">';
$kml[] = '    <LineStyle>';
$kml[] = '		  <width>1</width>';
$kml[] = '        <color>ff000000</color>';
$kml[] = '    </LineStyle>';
$kml[] = '    <PolyStyle>';
$kml[] = '		  <fill>0</fill>';
$kml[] = '    </PolyStyle>';
$kml[] = '</Style>';

$kml[] = '<Style id="highlight">';
$kml[] = '    <LineStyle>';
$kml[] = '		  <width>2</width>';
$kml[] = '        <color>a60000ff</color>';
$kml[] = '    </LineStyle>';
$kml[] = '    <PolyStyle>';
$kml[] = '		  <fill>1</fill>';
$kml[] = '        <color>66ffffff</color>';
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



$kml[] = '<Folder>';
$kml[] = '	<name>FootPrints</name>';
$kml[] = '	<Snippet>FootPrints: Permite visualizar ou ocultar footprints no Mapa</Snippet>';
$kml[] = '	<open>0</open>';
$kml[] = '	<visibility>1</visibility>';

$totalRegistros=0;


// Realiza a leitura de cada registro e atribui à variável ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $registros ) ) 
{	

	$orbita=$ratual['Path'];
	$ponto=$ratual['Row'];
					
	$centerLatitude=$ratual['CenterLatitude'];
	$centerLongitude=$ratual['CenterLongitude'];

	$footPrint_UL_Lat=$ratual['Image_UL_Lat'];
	$footPrint_UL_Lon=$ratual['Image_UL_Lon'];
	
	$footPrint_UR_Lat=$ratual['Image_UR_Lat'];
	$footPrint_UR_Lon=$ratual['Image_UR_Lon'];
		
	$footPrint_LR_Lat=$ratual['Image_LR_Lat'];
	$footPrint_LR_Lon=$ratual['Image_LR_Lon'];
	
	$footPrint_LL_Lat=$ratual['Image_LL_Lat'];
	$footPrint_LL_Lon=$ratual['Image_LL_Lon'];


	$kml[] = '	<Placemark>';
	$kml[] = '		<name>' . $orbita . '/' . $ponto . '</name>';
	$kml[] = '		<Snippet></Snippet>';
	$kml[] = '		<styleUrl>#formatacao</styleUrl>';
	$kml[] = '		<MultiGeometry>';
	$kml[] = '			<Polygon>';
	$kml[] = '				<tessellate>1</tessellate>';
	$kml[] = '				<extrude>1</extrude>';
	$kml[] = '				<altitudeMode>clampedToGround</altitudeMode>';							
	$kml[] = '				<outerBoundaryIs>';
	$kml[] = '					<LinearRing>';
	$kml[] = '						<coordinates>';
	$kml[] = '							' . $footPrint_UL_Lon . ','. $footPrint_UL_Lat . ',0 ';
	$kml[] = '							' . $footPrint_UR_Lon . ','. $footPrint_UR_Lat . ',0 ';
	$kml[] = '							' . $footPrint_LR_Lon . ','. $footPrint_LR_Lat . ',0 ';
	$kml[] = '							' . $footPrint_LL_Lon . ','. $footPrint_LL_Lat . ',0 ';
	$kml[] = '							' . $footPrint_UL_Lon . ','. $footPrint_UL_Lat . ',0 ';
	$kml[] = '						</coordinates>';
	$kml[] = '					</LinearRing>';
	$kml[] = '				</outerBoundaryIs>';
	$kml[] = '			</Polygon>';
	$kml[] = '			<Point>';
	$kml[] = '				<extrude>1</extrude>';
	$kml[] = '				<altitudeMode>clampedToGround</altitudeMode>';
	$kml[] = '				<coordinates>' . $centerLongitude . ',' . $centerLatitude . ',0 </coordinates>';
	$kml[] = '			</Point>';
	$kml[] = '		</MultiGeometry>';
	$kml[] = '	</Placemark>';
		
	$totalRegistros++;
}

$kml[] = '	<!-- TOTAL DE FOOTPRINTS GERADOS: ' . $totalRegistros.  ' -->';
$kml[] = '</Folder>';
$kml[] = '';



$registros = mysql_query( $stringSQLPesquisa, $conexao );

$kml[] = '<Folder>';
$kml[] = '	<name>Cenas</name>';
$kml[] = '	<Snippet>Cenas: Permite visualizar ou ocultar cenas no Mapa</Snippet>';
$kml[] = '	<open>1</open>';
$kml[] = '	<visibility>1</visibility>';

$totalRegistros=0;
// Realiza a leitura de cada registro e atribui à variável ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $registros ) ) 
{	

	$orbita=$ratual['Path'];
	$ponto=$ratual['Row'];
					
	$sceneId=$ratual['SceneId'];

	$norte=$ratual['Area_UR_Lat'];
	$sul=$ratual['Area_LL_Lat'];
	
	$leste=$ratual['Area_UR_Lon'];
	$oeste=$ratual['Area_LL_Lon'];
    
	$anoQL=$ratual['Ano'];

	$kml[] = '	<GroundOverlay>';
	$kml[] = '		<name>' . $orbita . '/' . $ponto . '</name>';
	$kml[] = '		<visibility>0</visibility>';
	$kml[] = '		<Icon>';
	$kml[] = '			<href>http://www.dgi.inpe.br/QUICKLOOK/CBERS4/MUX/' . $anoQL . '/QL_' . $sceneId . '_MED.png</href>';
	$kml[] = '			<viewBoundScale>1</viewBoundScale>';
	$kml[] = '		</Icon>';
	$kml[] = '		<LatLonBox>';
	$kml[] = '			<north>' . $norte . '</north>';
	$kml[] = '			<south>' . $sul . '</south>';
	$kml[] = '			<east>' . $leste . '</east>';
	$kml[] = '			<west>' . $oeste . '</west>';
	$kml[] = '			<rotation>0</rotation>';
	$kml[] = '		</LatLonBox>';
	$kml[] = '	</GroundOverlay>';
		
	$totalRegistros++;
}


$kml[] = '	<!-- TOTAL DE CENAS GERADAS: ' . $totalRegistros.  ' -->';
$kml[] = '</Folder>';
$kml[] = '';
$kml[] = '</Document>';
$kml[] = '</kml>';


// Libera as variáveis relacionadas aos registros retornados e à conexão com o banco de dados
mysql_free_result( $registros );   
mysql_close($conexao);

$kmlOutput = join("\n", $kml);
//header ('Content-Type: application/vnd.google-earth.kml+xml\n');
header ('Content-Type: text/plain\n');
echo $kmlOutput;
?>