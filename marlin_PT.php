<?php
//include("../../catalogo/css.php"); 
include("css.php");
include("session_mysql.php");
session_start();
include ("globals.php");

$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];
$submit = $SUBMIT;

if($submit != 0)
{
 $userid = 		$_SESSION['userId'];
 $sql = "SELECT * FROM User WHERE userId='$userid'";
 $dbusercat->query($sql) or $dbusercat->error ($sql);
 $sql = "UPDATE User SET marlin='1' WHERE userId='$userid' ";
 $dbusercat->query($sql) or $dbusercat->error ($sql);
 
 echo "<br><h2> > &nbsp; $userid, proceda agora ao download.</h2>";
 if($submit == 1)
 {
 ?><script> window.location.href="../Suporte/files/marlin-5.1.0-bin.exe" </script><!--script> window.location.href="../Suporte/files/marlin-3-1-1.msi" </script--><?
 }
 if($submit == 2)
 {
 ?><script> window.location.href="../Suporte/files/marlin-3.1.1-1.i386.rpm" </script><?
 }
 if($submit == 3)
 {
 ?><script> window.location.href="../Suporte/files/marlin-3.1.1_sources.zip" </script><?
 }
}

?>
<html>
<head>
</head>
<body>

<div style="font-size:15">
<p> 
O Marlin é um aplicativo de visualização e análise geométrica
e radiométrica de imagens de satélite.
</p>
<p>
Suas principais funcionalidades são:
</p>

 - versões para Linux e Windows; <br>
 - suporte aos formatos GeoTIFF, TIFF e JPEG; <br>
 - ajuste de brilho e contraste; <br>
 - filtros de realce; <br>
 - acoplagem geográfica de imagens de diferentes sensores e da mesma região, <br>
   facilitando a comparação entre as imagens; <br>
 - obtenção de pontos de controle e modelagem das transformações <br>
   afim, afim ortogonal, similaridade, ortogonal, translação e <br>
   polinominal que minimizam os resíduos; <br>
 - visualização dos vetores de resíduos das transformações geométricas; <br>
 - cálculo de registro e autocorrelação entre bandas espectrais distintas; <br>
 - visualização de shapefiles. <br><br>

<p>
O Marlin foi desenvolvido pela AMS Kepler sob contrato do INPE,
e utiliza a biblioteca Terralib <a href="http://terralib.org/" style="font-size:15"> (http://terralib.org/) </a>, e é
oferecido sob licença GPL3.
</p>

<p>
<!--a href="../Suporte/files/marlin-3-1-1.msi" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=1' ">Download Marlin Para Windows</a-->
<a href="../Suporte/files/marlin-5.1.0-bin.exe" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=1' ">Download Marlin Para Windows</a>. Instalador (com c&oacute;digo fonte).
<p>
<a href="../Suporte/files/marlin-3.1.1-1.i386.rpm" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=2' ">Download Marlin Para Linux</a> (RPM).
<p>
<a href="../Suporte/files/marlin-3.1.1_sources.zip" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=3' ">Download Codigdo Fonte </a> &nbsp;(Linux). 
<p>

</div>
<body>
</html>

