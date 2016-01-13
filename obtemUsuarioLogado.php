<?php
// Inclusão de blibliotecas para tratamento do controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais

include("session_mysql.php");
session_start();
include ("globals.php");
import_request_variables("gpc");

$userLogged = isset($_SESSION['userId']) ? true : false;


if ( $userLogged ) echo "LOGADO";
else echo "";

return;
		
?>

		