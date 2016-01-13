<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">
<?php
include("css.php");
include("session_mysql.php");
session_start();
 
?>
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Dev-PHP 2.0.12"
<meta name=Originator content="Microsoft Word 9">
<link rel=File-List href="./first_SP_teste_arquivos/filelist.xml">
<title>Prezado Usuário, </title>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>DPI</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>DPI</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>5</o:TotalTime>
  <o:Created>2008-03-12T15:31:00Z</o:Created>
  <o:LastSaved>2008-03-12T15:31:00Z</o:LastSaved>
  <o:Pages>2</o:Pages>
  <o:Words>634</o:Words>
  <o:Characters>3618</o:Characters>
  <o:Company>OBT - INPE</o:Company>
  <o:Lines>30</o:Lines>
  <o:Paragraphs>7</o:Paragraphs>
  <o:CharactersWithSpaces>4443</o:CharactersWithSpaces>
  <o:Version>9.8960</o:Version>
 </o:DocumentProperties>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:HyphenationZone>21</w:HyphenationZone>
 </w:WordDocument>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
@font-face
	{font-family:Wingdings;
	panose-1:5 0 0 0 0 0 0 0 0 0;
	mso-font-charset:2;
	mso-generic-font-family:auto;
	mso-font-pitch:variable;
	mso-font-signature:0 268435456 0 0 -2147483648 0;}
 /* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";
	mso-ansi-language:ES-TRAD;}
h2
	{margin-right:0cm;
	mso-margin-top-alt:auto;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	mso-outline-level:2;
	font-size:18.0pt;
	font-family:"Times New Roman";
	mso-ansi-language:ES-TRAD;
	font-weight:bold;}
h3
	{margin-right:0cm;
	mso-margin-top-alt:auto;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	mso-outline-level:3;
	font-size:13.5pt;
	font-family:"Times New Roman";
	mso-ansi-language:ES-TRAD;
	font-weight:bold;}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;
	text-underline:single;}
a:visited, span.MsoHyperlinkFollowed
	{color:blue;
	text-decoration:underline;
	text-underline:single;}
p
	{margin-right:0cm;
	mso-margin-top-alt:auto;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";
	mso-ansi-language:ES-TRAD;}
@page Section1
	{size:612.0pt 792.0pt;
	margin:70.85pt 3.0cm 70.85pt 3.0cm;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.Section1
	{page:Section1;}
 /* List Definitions */
@list l0
	{mso-list-id:1163199278;
	mso-list-type:hybrid;
	mso-list-template-ids:1216254770 68550671 2010808610 68550683 68550671 68550681 68550683 68550671 68550681 68550683;}
@list l0:level1
	{mso-level-tab-stop:36.0pt;
	mso-level-number-position:left;
	text-indent:-18.0pt;}
@list l0:level2
	{mso-level-number-format:bullet;
	mso-level-text:\25AC;
	mso-level-tab-stop:62.5pt;
	mso-level-number-position:left;
	margin-left:62.5pt;
	text-indent:-8.5pt;
	mso-ansi-font-size:10.0pt;
	font-family:"Courier New";
	mso-bidi-font-family:"Times New Roman";}
@list l1
	{mso-list-id:1198274489;
	mso-list-type:hybrid;
	mso-list-template-ids:-1070805056 1645878750 68550659 68550661 68550657 68550659 68550661 68550657 68550659 68550661;}
@list l1:level1
	{mso-level-number-format:bullet;
	mso-level-text:\F09E;
	mso-level-tab-stop:23.65pt;
	mso-level-number-position:left;
	margin-left:25.1pt;
	text-indent:-7.1pt;
	mso-ansi-font-size:12.0pt;
	font-family:Wingdings;}
@list l1:level2
	{mso-level-number-format:bullet;
	mso-level-text:o;
	mso-level-tab-stop:72.0pt;
	mso-level-number-position:left;
	text-indent:-18.0pt;
	font-family:"Courier New";}
ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
-->
</style>
</head>

<body topmargin="0">
<div><center>
<table align="center" cellspacing="10" cellpadding="10">
<tr>
<!--<td> 
<a href="http://www.dgi.inpe.br/pesquisa2007"> 
<img align=center src="../Suporte/images/pesq_cbers.gif"></a>
</td> -->
<?
$logo1 = $GLOBALS["basedir"] . $GLOBALS['localinfo']. "logo_cbers.jpg";

if(file_exists("$logo1"))
{
 $logo1 = $GLOBALS['localinfo'] . "logo_cbers.jpg";
?>
<td> 
<a target="_blank" href="http://www.cbers.inpe.br/">
<img align=center src="<?=$logo1?>"></a>
</td>
<?
}
$logo2 = $GLOBALS["basedir"] . $GLOBALS['localinfo']. "logo_cbers.jpg";

if(file_exists("$logo2"))
{
 $logo2 = $GLOBALS['localinfo'] . "finep-jpeg.jpg";
?>
<td>
<a href="http://www.finep.gov.br/"><img src="<?=$logo2?>" width="140" height="146" border="0"></a>
</td>
<?
}
?>
</tr>
</table>
</div></center>
<div align="justify">
<?

$remarkable_msg = $GLOBALS["basedir"] . $GLOBALS['localinfo'] . "remarkableMsg_ES.php";

if(!file_exists("$remarkable_msg")) fopen("$remarkable_msg","x+"); 
$strRemarkableMsg = file_get_contents("$remarkable_msg");
?>
<p style="font-size:15">
<strong>
Estimado Usuario,
</strong>
</p>
<p>
<h3><?=$strRemarkableMsg?></h3>
</p>

<p style="font-size:15">

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>Bienvenido a la p&aacute;gina que le permite la
interacci&oacute;n entre usted y el Banco de Im&aacute;genes de la DGI/INPE. En este banco de
datos usted encontrar&aacute; imágenes de los sat&eacute;lites <a
href="../Suporte/files/Cameras-LANDSAT123_ES.php" style="font-size:15">Landsat-1,
Landsat-2, Landsat-3</a>, <a
href="../Suporte/files/Cameras-LANDSAT57_ES.php" style="font-size:15">Landsat-5,
Landsat-7</a>, <a href="../Suporte/files/Cameras-CBERS2_ES.php" style="font-size:15">CBERS-2
, CBERS-2B</a> (Sat&eacute;lite Chino-Brasile&ntilde;o de Recursos Terrestres) y <a HREF="../Suporte/files/Satelite-Indiano.pdf" style="font-size:15;">ResourceSat 1 (IRS P6)</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>Las im&aacute;genes de estos sat&eacute;lites son gratuitas (no
tarifadas). La forma de env&iacute;o de las im&aacute;genes (gratuitas) es por transferencia
de archivos (FTP) v&iacute;a Internet. Si lo desea, el usuario puede solicitar el
env&iacute;o de las escenas (im&aacute;genes) seleccionadas en CD (el cual le ser&aacute; remitido
por v&iacute;a postal), para lo cual el usuario debe tener un registro de compra, ya
que esta modalidad tiene un costo correspondiente al CD y al env&iacute;o. Las
im&aacute;genes solicitadas en CD ser&aacute;n enviadas adicionalmente, v&iacute;a FTP.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>El usuario habilitado con un registro de compra
podr&aacute; solicitar cualquier &iacute;tem del Cat&aacute;logo; los usuarios que no tengan el
registro podr&aacute;n comprar solamente los productos que no tienen costo. El s&iacute;mbolo
$ aparecer&aacute; en el marco superior de cada &iacute;tem tarifado del Cat&aacute;logo.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>El INPE espera que usted obtenga el mayor provecho
de los productos ofrecidos.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>Solicitamos que nos env&iacute;e, en la medida de lo
posible, los resultados de sus trabajos con las im&aacute;genes CBERS al igual que
comentarios y sugestiones, lo cual contribuir&aacute; con nuestro deseo de mejorar
constantemente el sistema.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>En este cat&aacutelogo usted podr&aacute;:<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>BUSCAR
IM&Aacute;GENES de su inter&eacute;s a trav&eacute;s de diferentes <a
href="help.php" style="font-size:15">formas de busca</a>: por
sat&eacute;lite y sensor, por fecha, por municipio, por &oacute;rbita/punto, por regi&oacute;n o por
medio de navegaci&oacute;n gr&aacute;fica. La consulta al cat&aacute;logo es libre, sin embargo para
descargar las im&aacute;genes completas es necesario que usted haga su <a
href="register.php" style="font-size:15">registro</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>REGISTRARSE
en el INPE: el <a href="register.php" style="font-size:15">registro</a>
es muy importante ya que le permite al INPE conocer los principales usuarios
del sistema y las &aacute;reas de aplicaci&oacute;n del CBERS. Sus datos de registro estar&aacute;n
bajo la guarda del INPE y no ser&aacute;n divulgados, s&oacute;lo servir&aacute;n para fines
estad&iacute;sticos y de comunicaci&oacute;n entre el INPE y usted. <o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>ACESAR el
Cat&aacute;logo y solicitar im&aacute;genes en resoluci&oacute;n plena. Las im&aacute;genes podr&aacute;n ser
solicitadas sin costo para descarga.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>DESCARGAR
IM&Aacute;GENES: en caso de que la capacidad de su sistema no sea adecuada para
descargar las im&aacute;genes - generalmente son archivos grandes (entre 40 - 50 MB para cada banda HRC, 12 - 20 MB
para cada banda CCD; 1,5 Mb para las bandas IRMSS y 8 Mb para cada banda del
WKI) - usted podr&aacute; solicitar las im&aacute;genes en CD-ROM al precio que aparece en la
tabla de precios oficial del INPE para el producto deseado. Para solicitar im&aacute;genes
en CD-ROM el usuario debe acceder el portal <a href="http://www.dgi.inpe.br" style="font-size:15"
target="_blank">http://www.dgi.inpe.br</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>DESCARGAR
ARCHIVOS Y DOCUMENTOS: archivos de datos y documentos (por ejemplo, el Manual
de Operaci&oacute;n del Cat&aacute;logo) de inter&eacute;s del usuario, se encuentran disponibles en
<a href="documentos_ES.php" style="font-size:15">Archivos y
Documentos</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l0 level1 lfo1;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='mso-ansi-language:ES'>6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'> DESCARGAR EL MARLIN: herramienta de aplicaci&oacute;n a imagenes digitales, disponible 
<? 
if (isset($_SESSION['userId']))
{
?> en &nbsp;<A href = "documentos_ES.php" style="font-size:15">Archivos y Documentos</a> <?
}
else
{
?> después del <a href="login.php" style="font-size:15">login.</a> <?
}
?>
</a>.<o:p></o:p></span></p>
<br>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>NOTAS:<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='font-family:Wingdings;mso-ansi-language:ES'>.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>Las im&aacute;genes
podr&aacute;n obtenerse SIN COSTO por parte del usuario cuando la adquisici&oacute;n sea a
trav&eacute;s de descarga de archivos. Para otros productos, los costos se rigen por
la tabla de precios oficial del INPE.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='font-family:Wingdings;mso-ansi-language:ES'>.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>Al realizar
la descarga o adquisici&oacute;n de cualquier imagen CBERS usted acepta
autom&aacute;ticamente los t&eacute;rminos contenidos en la 
<a href="../Suporte/files/politica_de_dados_cbers_v.1.4_ES.htm" style="font-size:15">Licencia
de Uso de Im&aacute;genes CBERS</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;margin-left:18.0pt;text-align:justify;
text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 18.0pt'><![if !supportLists]><span
lang=ES style='font-family:Wingdings;mso-ansi-language:ES'>.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span><![endif]><span lang=ES style='mso-ansi-language:ES'>Los
productos CBERS est&aacute;n en proceso continuo de perfeccionamiento. Siempre que
note problemas con los productos, por favor, no deje de notificarlos, con la
finalidad de que analicemos el problema y busquemos soluciones. Preferiblemente
anexe las im&aacute;genes a la descripci&oacute;n del problema observado.<o:p></o:p></span></p>

<h3 align=center style='text-align:center'><span lang=ES style='mso-ansi-language:
ES'> ===== // ====<o:p></o:p></span></h3>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>Para noticias, im&aacute;genes de demostraci&oacute;n,
fotograf&iacute;as, videos y informaciones
t&eacute;cnicas sobre el Programa CBERS, consulte el portal <a
href="http://www.cbers.inpe.br" style="font-size:15">http://www.cbers.inpe.br</a>.<o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'>Si tiene dudas sobre el uso de im&aacute;genes u otras
solicitudes, entre en contacto con Atenci&oacute;n al Usuario <a
href="mailto:atus@dgi.inpe.br" style="font-size:15">atus@dgi.inpe.br</a><o:p></o:p></span></p>

<p style='margin-bottom:12.0pt;text-align:justify'><span lang=ES
style='mso-ansi-language:ES'><br>
</span><b style='mso-bidi-font-weight:normal'><span lang=ES style='font-size:
18;mso-ansi-language:ES'>Para comentarios o sugestiones, entre en <a
href="mailto:atus@dgi.inpe.br" style="font-size:18">contacto</a> con nosotros.<br>
</span></b><span lang=ES style='mso-ansi-language:ES'><br>
<br>
<br>
<br style='mso-special-character:line-break'>
<![if !supportLineBreakNewLine]><br style='mso-special-character:line-break'>
<![endif]><o:p></o:p></span></p>

<p style='margin-bottom:12.0pt'><span lang=ES style='mso-ansi-language:ES'><br
style='mso-special-character:line-break'>
<![if !supportLineBreakNewLine]><br style='mso-special-character:line-break'>
<![endif]><o:p></o:p></span></p>

</div>
<!-- <IMG SRC="/cgi-bin/Count.cgi?ft=1&amp;sh=F&amp;df=cata-es.dat"> -->
</body>

</html>
