<?php

// String contendo a instrução de pesquisa com o limite de registrs s serem retornados
$stringSQLPesquisa="SELECT SceneId, satellite  FROM Scene";
// Resgistros retornados



// Realizada a conexão com banco de dados
$conexao = mysql_connect("150.163.134.105:3333", "gerente", "gerente.200408") or die ("Erro de conexao = " . mysql_error());
mysql_select_db( "catalogo", $conexao); 



$registros = mysql_query( $stringSQLPesquisa, $conexao );


// Realiza a leitura de cada registro e atribui à variável ratual (Registro Atual)
while ( $ratual = mysql_fetch_assoc( $registros ) ) 
{	

	$nomeHost=$ratual['SceneId'];
	$nomeModulo=$ratual['satellite'];


	echo "SceneId = " . $nomeHost . "  : Satelite = " . $nomeModulo . "\n";
	
}


mysql_close($conexao);

echo "\n<br><br>\n";

phpinfo();


?>
