<?php
// Este arquivo conecta um banco de dados MySQL - Servidor = localhost
$server = "150.163.134.96:3333";//Indique o local do servidor mysql
$dbname = "site";// Indique o nome do banco de dados que será aberto
$user = "cdsr";// Indique o nome do usuário que tem acesso
$password = "cdsr.200408"; // Indique a senha do usuário

//1º passo - Conecta ao servidor MySQL
if(!($id = mysql_connect($server, $user, $password))){
    echo "BD Não esta conectado";
    exit;
}

//2º passo - Seleciona o Banco de Dados
if(!($conexao = mysql_select_db($dbname, $id))){
    echo "BD Não esta conectado";
    exit;
}
?>
