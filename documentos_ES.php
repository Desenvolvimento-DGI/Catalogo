<html>
<head>
<?php include("css.php");
include("session_mysql.php");
session_start();
?>
</head>
<title>Documentos e Arqvuios Disponiveis</title>
<body>
<p>
<p style="font-size:15">
<A HREF="../Suporte/files/Grade_CBERS_spr.zip" style="font-size:15">Ret&iacute;cula WRS de Path/Row CBERS (Spring)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_CBERS_shp.zip" style="font-size:15">Ret&iacute;cula WRS de Path/Row CBERS (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_spr.zip" style="font-size:15">Ret&iacute;cula WRS de Path/Row LANDSAT MSS (Spring)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_shp.zip" style="font-size:15">Ret&iacute;cula WRS de Path/Row LANDSAT MSS (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_ResourceSat1_LISS-3_shp.zip" style="font-size:15">Ret&iacute;cula WRS de Path/Row ResourceSat1 LISS-3 (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2geometria_ES.htm" style="font-size:15">Archivo de texto sobre la geometr&iacute;a CBERS-2</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2faixas_ES.htm" style="font-size:15">Archivo de texto sobre las bandas espectrales de las im&iacute;genes CBERS-2</a>.
<br><br>
<A HREF="../Suporte/files/manual_usuario_ES.htm" style="font-size:15">Archivo de texto sobre el Cat&aacute;logo de Im&aacute;genes</a>.
<br><br> 
<?
if (isset($_SESSION['userId']))
{
?><a href="marlin_EN.php" style="font-size:15">MARLIN : herramienta de aplicaci&oacute;n a imagenes digitales.</a><?
}
?> 
<p>
</body>
</html>
