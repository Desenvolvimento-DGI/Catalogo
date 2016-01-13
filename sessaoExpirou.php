<?php
// Inclusão de blibliotecas para tratamento do controle de acesso, controle de sessão, configurações 
// para acesso ao banco de ddos, definições de variáveis auxiliares e definições de varíaveis globais

include("session_mysql.php");
session_start();
include ("globals.php");
import_request_variables("gpc");

$userLogged = isset($_SESSION['userId']) ? true : false;

#
# Delete all possible remaining records from table Cart
#

if ( $userLogged )
{
	echo "LOGADO"	;
	return;
}


$dbcat = $GLOBALS["dbcatalog"];
$sql = "DELETE FROM Cart WHERE sesskey = '$PHPSESSID'";
$dbcat->query($sql) or $dbcat->error ($sql);


if (version_compare(PHP_VERSION, '4.3.3') == -1) setcookie(session_name(), session_id());
$lang = $_SESSION['userLang'];
$_SESSION["userId"] = "";  // remove user name from banner

  
$_SESSION = array();
session_destroy();
session_regenerate_id(); 
 
#	if (version_compare(PHP_VERSION, '4.3.3') == -1)
#	setcookie(session_name(), session_id());
session_set_save_handler(
	"sess_open",
	"sess_close",
	"sess_read",
	"sess_write",
	"sess_destroy",
	"sess_gc");
	
			
echo "EXPIROU";
		
?>

		