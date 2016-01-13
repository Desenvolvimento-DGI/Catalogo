<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<?php include("css.php");
include("session_mysql.php");
session_start();
?><title>Documents et fichiers Disponibles</title>

</head>
<body>
<p>
<a href="../Suporte/files/Grade_CBERS_spr.zip" style="font-size: 15px;">Grille WRS d'Orbites et Points CBERS
(Spring).</a>
<br><br>
<a href="../Suporte/files/Grade_CBERS_shp.zip" style="font-size: 15px;">Grille WRS d'Orbites et Points CBERS
(Shape File)</a>.
<br><br>
<a href="../Suporte/files/Grade_LANDSAT_MSS_spr.zip" style="font-size: 15px;">Grille MSS d'Orbites et Points
LANDSAT (Spring)</a>.
<br><br>
<a href="../Suporte/files/Grade_LANDSAT_MSS_shp.zip" style="font-size: 15px;">Grille MSS d'Orbites et Points
LANDSAT (Shape File)</a>.
<br><br>
<A HREF="../Suporte/files/Grade_ResourceSat1_LISS-3_shp.zip" style="font-size:15px">Grille LISS-3 d'Orbites et Points
ResourceSat1 (Shape File)</a>.
<br><br>
<a href="../Suporte/files/CBERS2geometria_FR.htm" style="font-size: 15px;">Fichier texte concernant la g&eacute;ometrie
CBERS-2 </a>.
<br><br>
<a href="../Suporte/files/CBERS2faixas_FR.htm" style="font-size: 15px;">Fichier texte concernant les bandes spectrales des images CBERS-2 </a>.
<br><br>
<a href="../Suporte/files/manual_usuario_FR.htm" style="font-size: 15px;">Fichier texte concernant le Catalogue
d'Imags</a>
<br><br>
<a href="../Suporte/files/HRC-CBERS-2B-informe1_FR.pdf" style="font-size: 15px;">Images HRC/CBERS-2B: informe 1</a>.
<br><br>
<a href="../Suporte/files/calibracao_absoluta_CCD_FR.htm" style="font-size: 15px;">Orientation pour les utilisateurs sur la mani&egrave;re de proc&eacute;der pour convertir les num&eacute;ros digitaux (NDs) des images de
CBERS-2B (CCD) en valeurs physiques.</a>
<br><br>
<a href="../Suporte/files/cbers2b_ccd.zip" target="_blank" style="font-size: 15px;">Programme informatique ex&eacute;cut&eacute; en environnement DOS et qui a pour objetif de corriger les images
des effets de l'atmosph&eacute;re, en les convertissant en valeurs de Facteurs de
R&eacute;flectance de Surface (FRS).</a>
<br><br>
<?
if (isset($_SESSION['userId']))
{
?><a href="marlin_EN.php" style="font-size:15px">MARLIN : a tool oriented for displaying and handling digital images .</a><?
}
?>
</p>

</body></html>