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
<A HREF="../Suporte/files/Grade_CBERS_spr.zip" style="font-size:15">Grade WRS de &Oacute;rbitas e Pontos CBERS (Spring).</a>
<br><br>
<A HREF="../Suporte/files/Grade_CBERS_shp.zip" style="font-size:15">Grade WRS de &Oacute;rbitas e Pontos CBERS (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_spr.zip" style="font-size:15">Grade MSS de &Oacute;rbitas e Pontos LANDSAT (Spring)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_LANDSAT_MSS_shp.zip" style="font-size:15">Grade MSS de &Oacute;rbitas e Pontos LANDSAT (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_ResourceSat1_LISS-3_shp.zip" style="font-size:15">Grade LISS-3 de &Oacute;rbitas e Pontos ResourceSat-1 (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2geometria_PT.htm" style="font-size:15">Arquivo texto referente &agrave; geometria CBERS-2</a>.
<br><br>
<A HREF="../Suporte/files/CBERS2faixas_PT.htm" style="font-size:15">Arquivo texto referente &agrave;s faixas espectrais das imagens CBERS-2 </a>.
<br><br>
<A HREF="../Suporte/files/manual_usuario_PT.htm" style="font-size:15">Arquivo texto referente ao Cat&aacute;logo de Imagens</a>. 
<br><br>
<A HREF="../Suporte/files/HRC-CBERS-2B-informe1_PT.pdf" style="font-size:15">Imagens HRC/CBERS-2B: informe 1</a>.   
<br><br>
<a href="../Suporte/files/calibracao_absoluta_ccd.html" style="font-size:15">Orienta&ccedil;&atilde;o aos usu&aacute;rios sobre como proceder para converter os n&uacute;meros digitais (NDs) das imagens do CBERS-2B (CCD) para valores f&iacute;sicos.</a>
<br><br>
<a href="../Suporte/files/cbers2b_ccd.zip" target="_blank" style="font-size:15">Programa de computador que &eacute; executado em ambiente DOS e que tem como objetivo corrigir as imagens dos efeitos da atmosfera, convertendo-as em valores de Fatores de Reflect&acirc;ncia de Superf&iacute;cie (FRS).</a>
<br><br>
<?
if (isset($_SESSION['userId']))
{
?><a href="marlin_PT.php" style="font-size:15">MARLIN : ferramenta destinada &agrave; visualiza&ccedil;&atilde;o e avalia&ccedil;&atilde;o de imagens digitais.</a><?
}
?>
</p> 
</body>
</html>
