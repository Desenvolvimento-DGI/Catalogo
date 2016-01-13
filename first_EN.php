<html>
<head>
<?php
include("css.php");
include("session_mysql.php");
session_start();
 
?>  
</head>
<body topmargin="0">
<div><center>
<table align="center" cellspacing="10" cellpadding="10">
<tr>
<?
$logo1 = $GLOBALS["basedir"] . $GLOBALS['localinfo']. "logo_cbers.jpg";

if(file_exists("$logo1"))
{
 $logo1 = "$GLOBALS[localinfo]logo_cbers.jpg";
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
 $logo2 = "$GLOBALS[localinfo]finep-jpeg.jpg";
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

<p style="font-size:15">
<strong>
Dear User,
</strong>
</p>

<?
$remarkable_msg = $GLOBALS["basedir"] . $GLOBALS['localinfo'] . "remarkableMsg_EN.php";

if(!file_exists("$remarkable_msg")) fopen("$remarkable_msg","x+"); 
$strRemarkableMsg = file_get_contents("$remarkable_msg");
?>
<p>
<h3><?=$strRemarkableMsg?></h3>
</p>

<?
$localmessage_msg = $GLOBALS["basedir"] . $GLOBALS["localinfo"] . "localmessage_EN.php";

if(!file_exists("$localmessage_msg")) fopen("$localmessage_msg","x+"); 
$strLocalMessage = file_get_contents("$localmessage_msg");
?>
<h3><?=$strLocalMessage?></h3>

<br>
<p style="font-size:15">
<strong>
Herein users are allowed to :

<br><br>

1. SEARCH FOR IMAGES using the following <a HREF="help.php" style="font-size:15">criteria</a>: satellite/sensor, data, municipality, path/row, region 
or by geografically sailing. The access to the Catalog is entirely free ; images download require user <a HREF="register.php" style="font-size:15">register</a>.
<br><br>
2. REGISTER in User's database: this <a HREF="register.php" style="font-size:15">registration</a> gives us the knowledge of the Catalog users technical profiles and their 
aplication areas of interest. User registration data will be exclusively used for statistics purposes and communication.
<br><br>
3.ACCESS the Catalog system and request images in their total resolution, free of charge.<br><br>
4. DOWNLOAD IMAGES : If your Internet connection isn't sufficient to download images (Generally files from 40 to 50 Mb for each HRC band, from 12 to 20 Mb for each CCD band, 1.5 Mb for IRMSS and 8 Mb for WFI band) you can order images in CD media, with cost, contacting <A HREF="mailto:atus@dgi.inpe.br" style="font-size:15">ATUS</a>. If you need further information, please enter the site <a target="_blank" HREF="http://www.dgi.inpe.br" style="font-size:15">http://www.dgi.inpe.br</a>.
<br><br>
5. DOWNLOAD FILES AND DOCUMENTS: Data files and documents (e.g. catalog's operation manual) are available on  <A href = "documentos_EN.php" style="font-size:15">Files and Documents.</a><br><br>
6. DOWNLOAD MARLIN: a tool oriented for displaying and handling digital images, available 
<? 

if (isset($_SESSION['userId']))
{
?> at &nbsp;<A href = "documentos_ES.php" style="font-size:15">Files and Documents</a>. <?
}
else
{
?> after <a href="login.php" style="font-size:15">login.</a> <?
}
?>
</strong>
<br><br>

<?
$footnotes_msg = $GLOBALS["basedir"] . $GLOBALS["localinfo"] . "footnotes_EN.php";

if(!file_exists("$footnotes_msg")) fopen("$footnotes_msg","x+"); 
$strFootNotes = file_get_contents("$footnotes_msg");
?>
<h3><?=$strFootNotes?></h3>

</div>
<!--		<IMG SRC="/cgi-bin/Count.cgi?ft=1&amp;sh=F&amp;df=cata-en.dat">  -->
</body>
</html>