<?php
include 'conecta_BD.php';

$sql ="SELECT *
FROM `noticia_externa`
ORDER BY `id` DESC
LIMIT 0 , 4";

$resultado = mysql_query($sql);
for($i=0;$i<=3;$i++)
    echo utf8_encode(mysql_result($resultado, $i, "ne_trecho")."#######".mysql_result($resultado, $i, "ne_link"))."#######";
?>
