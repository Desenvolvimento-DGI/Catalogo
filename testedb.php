<?php





// Inicia a conexãcom a base de dados  

// Variáis contendo informaçs para acesso ao banco de dados

$dbhost = 'envisat.dgi.inpe.br:3333';
$dbuser = 'gerente';
$dbpass = 'gerente.200408';
$dbname = 'catalogo';
$dbtype = 'mysql';


try
{
	$conexao = new PDO( $dbtype . ":host=" . $dbhost . ";dbname=" . $dbname .";charset=utf8", "$dbuser", "$dbpass" );
}
catch(PDOException $ex) 
{
	echo $ex;
}





// Realizada a conexãcom banco de dados
//$conexao = mysql_connect($dbhost, $dbuser, $dbpass);

$stringWhere="WHERE Satellite = 'RES2' AND Sensor = 'LIS3'";
$stringSql = "SELECT SceneId, Satellite, Sensor, Date FROM catalogo.Scene " . ${stringWhere};

echo "\n<br>";
echo "\n<br>" . $stringSql . "\n<br>";
echo "\n<br>\n";



// Verifica se ocorreu erro ao realizar a conexãcom o banco de dados
if(! $conexao )
{
	echo "\n";
	echo mysql_errno() . "\n<br>";
	echo mysql_error() . "\n<br>";
	
	exit;	
}




//mysql_select_db( "$dbname", $conexao); 


// Obtéo nú total de registros da pesquisa  
/*
$contagem = mysql_query( $stringSql, $conexao );
$total_registros=mysql_num_rows($contagem);


echo "\n<br>\n";
echo "total_registros =  " . $total_registros . "\n<br>";
echo "\n<br>";
	


// Realiza a leitura de cada registro e atribui àariál ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $contagem ) ) 
{	

	$sceneid=strtolower($ratual['SceneId']);
	$satelite=strtolower($ratual['Satellite']);
	$sensor=strtolower($ratual['Sensor']);
	$data=strtolower($ratual['Date']);
	

	echo "\n<br>\n";	
	echo "$sceneid  -  $satelite   -   $sensor    -  $data";
	echo "\n";
	
}


echo "\n<br>\nTermino\n";	

mysql_free_result( $contagem );   
mysql_close($conexao);


echo "\n<br>\nFinal\n\n";	

*/

?>


