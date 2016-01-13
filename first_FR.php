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
<table align="center" cellpadding="10" cellspacing="10">
<tr>
<!--<td> <a target="_blank" href="http://www.dgi.inpe.br/pesquisa2007"> <img align=center src="../Suporte/images/pesq_cbers.gif"></a>
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

$remarkable_msg = $GLOBALS["basedir"] . $GLOBALS['localinfo'] . "remarkableMsg_FR.php";

if(!file_exists("$remarkable_msg")) fopen("$remarkable_msg","x+"); 
$strRemarkableMsg = file_get_contents("$remarkable_msg");
?>
<p style="font-size:15">
<strong>
Cher Utilisateur,
</strong>
</p>
<p>
<h3><?=$strRemarkableMsg?></h3>
</p>
<p style="font-size: 15px;">
Bienvenu sur la page de la Banque d&#8217;Images de la Division de G�n�ration
d&#8217;Images de l&#8217;Institut National de Recherche Spatial br�silien
(DGI/INPE). Dans cette base de donn�es, vous avez acc�s � des images
des satellites <a href="../Suporte/files/Cameras-LANDSAT123_FR.php" style="font-size: 15px;">Landsat-1, Landsat-2, Landsat-3</a>,
<a href="../Suporte/files/Cameras-LANDSAT57_FR.php" style="font-size: 15px;">Landsat-5, Landsat-7</a>, <a href="../Suporte/files/Cameras-CBERS2_PT.php" style="font-size: 15px;"> CBERS-2, CBERS-2B</a>
(Satellites Sino-br�siliens de Ressources Terrestres) et <a HREF="../Suporte/files/Satelite-Indiano.pdf" style="font-size: 15px;">ResourceSat 1 (IRS P6)</a>.<br>
Les images de ces satellites sont distribu�es gratuitement. Le mode
d&#8217;envoi normal de ces images passe par un transfert de fichiers
effectu�s par FTP au travers d&#8217;Internet. Si l&#8217;utilisateur le souhaite,
il peut demander l&#8217;envoi par la Poste des sc�nes choisies qui seront
alors grav�es sur des CD-ROM. L&#8217;envoi d&#8217;images sur CD-ROM n�cessite
auparavant que l&#8217;utilisateur soit cadastr� comme acheteur �tant donn�
que cette modalit� comporte des frais additionnels (Envoi et prix de
gravure sur CD-ROM) qui seront factur�s � l&#8217;utilisateur. Les demandes
d&#8217;images sur CD-ROM seront accompagn�es de la mise � disposition en
parall�le des sc�nes sur un site FTP.<br>
Tout utilisateur cadastr� comme acheteur pourra demander tout produit
du catalogue. Les utilisateurs non cadastr�s comme acheteurs pourront
solliciter uniquement les produits gratuits. Le symbole $ appara�tra
dans barre sup�rieure � tout produit factur� du catalogue.<br>
L&#8217;INPE esp�re que vous pourrez b�n�ficier au mieux des produits offerts
� partir de ce catalogue. <br>
Nous vous prions de bien vouloir nous envoyer, dans la mesure du
possible, les r�sultats de vos travaux obtenus avec des images CBERS,
de m�me que vos commentaires et suggestions afin de nous permettre de
continuer nos d�veloppements en observant les cons�quences directes de
l&#8217;am�lioration du syst�me pour les utilisateurs.<br>
<br>
Ici vous pourrez : <br>
<br>
1. RECHERCHER DES IMAGES de votre int�r�t, selon divers <a href="help.php" style="font-size: 15px;">modes de
recherche</a>: par satellite et capteur, par date, par municipe,
par orbite/point, par r�gion ou au moyen d&#8217;une navigation graphique. La
consultation du catalogue est ouverte � tous, cependant pour pouvoir
t�l�charger les sc�nes, il est auparavant n�cessaire de vous <a href="register.php" style="font-size: 15px;">inscrire</a>.
<br>
<br>
2. S'INSCRIRE � l&#8217;INPE : cette <a href="register.php" style="font-size: 15px;">inscription</a> est tr�s
importante, elle permet � l&#8217;INPE de conna�tre les principaux
utilisateurs du syst�me et des aires d&#8217;application de CBERS. Les
informations personnelles vous concernant resteront sous la
responsabilit� de l&#8217;INPE et ne seront en aucun cas divulgu�es � des
tierces. Ces informations serviront uniquement � des fins statistiques
et afin de faciliter la communication de l&#8217;INPE avec les utilisateurs
du syst�me CBERS.
<br>
<br>
3. ACCEDER au Catalogue et effectuer la demande des sc�nes. Les images
pourront �tre demand�es gratuitement pour t�l�chargement.<br>
<br>
4. TELECHARGER LES IMAGES : Au cas o� les limitations d&#8217;acc�s de votre
syst�me informatique au r�seau rendent difficile le t�l�chargement des
images &#8211; la taille des fichiers est de plusieurs Mb (typiquement de 40 � 50 Mb pour chaque bande HRC; 12
� 20 Mb pour chaque bande CCD ; 1,5 Mb pour les bandes du capteur IRMSS
et 8 Mb pour chaque bande du capteur WFI) &#8211; vous pourrez faire la
demande des images sur CD-ROM, au prix officiel d�livr� par l&#8217;INPE.
Afin de faire la demande des sc�nes sur CD-ROM, acc�dez au site <a target="_blank" href="http://www.dgi.inpe.br" style="font-size: 15px;">http://www.dgi.inpe.br</a>
<br>
<br>
5. TELECHARGER FICHIERS ET DOCUMENTS : les fichiers de donn�es et les
documents de votre choix (e.g. Manuel des Op�rations du Catalogue) sont
accessibles dans la section <a href="documentos_FR.php" style="font-size: 15px;">Fichiers et Documents</a>. <br>
<br>
6. DOWNLOAD MARLIN: a tool oriented for displaying and handling digital images, available 
<? 

if (isset($_SESSION['userId']))
{
?> at &nbsp;<A href = "documentos_FR.php" style="font-size:15">Files and Documents</a>. <?
}
else
{
?> after <a href="login.php" style="font-size:15">login.</a> <?
}
?>
<br><br>
NOTES:
<br>
<br>
. Les images pourront �tre acquises LIBRES DE CO�TS par l&#8217;utilisateur,
lorsque les acquisitions seront faites au travers d&#8217;un t�l�chargement.
Pour les autres produits, les prix sont fournis par l&#8217;INPE.
<br>
<br>
. En faisant l&#8217;acquisition d&#8217;images CBERS vous adh�rez de mani�re
implicite et automatique aux termes et conditions de la <a href="../Suporte/files/politica_de_dados_cbers_v.1.4_PT.htm" style="font-size: 15px;">Licence d'Utilisation des Images
CBERS</a>.
<br>
<br>
. Les produits CBERS connaissent un processus d&#8217;am�lioration continu.
Afin de participer � ce processus, d�s que vous observez des probl�mes
avec les produits acquis, n&#8217;h�sitez pas � nous pr�venir afin que nous
puissions analyser le probl�me rencontr� et proc�der � des
am�liorations. De pr�f�rence, fournir un aper�u des images en annexes �
la notification ainsi qu&#8217;une br�ve description du probl�me observ�.
<br>
<br>
<br>
</p>
<div align="center">
<h3>===== // ====</h3>
</div>
<br>
<p style="font-size: 15px;">
Pour toute information, images de d�monstration, photographies, vid�os,
informations techniques sur le programme CBERS, consultez <a href="http://www.cbers.inpe.br" style="font-size: 15px;">
http://www.cbers.inpe.br</a>
<br>
<br>
<!-- Participe da "1� Pesquisa sobre o Perfil dos Usu�rios das Imagens CBERS"
acessando <A HREF="http://www.dgi.inpe.br/pesquisa2007"> http://www.dgi.inpe.br/pesquisa2007</A>.
<br><br> -->
<!-- Veja o novo Video Educacional "Satelites e seus Sub-Sistemas", tendo o CBERS
como exemplo (<A HREF="http://www.cbers.inpe.br/pt/index_pt.htm"> http://www.cbers.inpe.br/pt/index_pt.htm</A> ).
<br><br> -->Pour
des questions sur l&#8217;utilisation des images ou pour toute autre demande,
merci d&#8217;entrer en contact avec
<a href="mailto:atus@dgi.inpe.br" style="font-size: 15px;">atus@dgi.inpe.br</a>
<br>
<br>
<br>
</p>
<h2>
<center>Pour
tout commentaire ou suggestion, nous vous prions d&#8217;entrer en
<a href="mailto:atus@dgi.inpe.br" style="font-size: 15px;">contact</a>
avec nous.</center>
</h2>
<p></p>
</div>
<!-- <IMG SRC="/cgi-bin/Count.cgi?ft=1&amp;sh=F&amp;df=cata-pt.dat"> -->
</body></html>