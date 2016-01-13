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
 
 echo "<br><h2> > &nbsp; $userid, proceed download now.</h2>";
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
Marlin is an application for visualization and geometric /
radiometric analysis of satellite imagery. 
</p>
<p>
Its main features are:
</p>
  - Linux and Windows versions;<br>
  - support for GeoTIFF, TIFF and JPEG image file formats;<br>
  - contrast and brightness adjust;<br>
  - image processing filters;<br>
  - imagery from distinct satellite sensor but of the same<br>
    region may be geographically coupled, making the comparison<br>
    of the images easier;<br>
  - control points acquisition and transformation modeling,<br>
    supporting the following transformations: affine, orthogonal<br>
    affine, similarity, orthogonal, translation and polynomial;<br>
  - vector visualization of the residuals from the<br>
    geographical transformations;<br>
  - radiometric registration between distinct spectral bands;<br>
  - shapefiles visualization.<br><br>

<p>
Marlin was developed by AMS Kepler under contract from INPE,
uses the Terralib library (http://terralib.org/) and is offered
under GPL3 license.
</p>

<p>
<!--a href="../Suporte/files/marlin-3-1-1.msi" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=1' "-->
<a href="../Suporte/files/marlin-5.1.0-bin.exe" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=1' ">Download Marlin for Windows</a>. Installer (plus source code)
<p>
<a href="../Suporte/files/marlin-3.1.1-1.i386.rpm" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=2' ">Download for Linux</a> (RPM).
<p>
<a href="../Suporte/files/marlin-3.1.1_sources.zip" style="font-size:15" onClick="href='<?=$_SERVER['PHP_SELF']?>?SUBMIT=3' ">Download Source Code </a> &nbsp;(Linux).
<p>

</div>
<body>
</html>

