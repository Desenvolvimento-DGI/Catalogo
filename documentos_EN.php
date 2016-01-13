<html>
<head>
<?php include("css.php");
include("session_mysql.php");
session_start();
?>
</head>
<title>Available Documents and Files</title>
<body>
<p>
* Files are available only in portuguese.
<p>
<p style="font-size:15">
<A HREF="../Suporte/files/Grade_CBERS_spr.zip">CBERS Reference Grid - WRS (Spring format)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_CBERS_shp.zip">CBERS Reference Grid - WRS (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_spr.zip" style="font-size:15">LANDSAT MSS Reference Grid - WRS (Spring)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_shp.zip" style="font-size:15">LANDSAT MSS Reference Grid - WRS (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_ResourceSat1_LISS-3_shp.zip" style="font-size:15">ResourceSat1 LISS-3 Reference Grid (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2geometria_PT.htm">Explanation Text about CBERS-2 Geometry</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2faixas_PT.htm">Spectral Bands Characteristics </a>.
<br><br>
<A HREF="../Suporte/files/manual_usuario_PT.htm">Catalog Users's Manual</a>. 
<br><br>
<A HREF="../Suporte/files/HRC-CBERS-2B-informe1_PT.pdf">Explanation Text about HRC/CBERS-2B</a>.   
<br><br>
<?
if (isset($_SESSION['userId']))
{
?><a href="marlin_EN.php" style="font-size:15">MARLIN : a tool oriented for displaying and handling digital images .</a><?
}
?>
<p>
</body>
</html>
