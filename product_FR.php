<?php 
// Produto
$titFormProd = "Ordre de Production";
$strId = 'Id';
$strShift = 'D�placement';
$strCorLevel = 'Niveau de Correction';
$strOrientation = 'Orientation';
$strProj = 'Projection';
$strLongO = "Longitude d'Origine";
$strLatO = "Latitude d'Origine";
$strStdLat1 = 'Standard Latitude 1';
$strStdLat2 = 'Standard Latitude 2';
$strDatum = 'Datum';
$strRes = 'R��chantillonnage';
$strBands = 'Bandes';
$strFormat = 'Format';
$strInter = 'Media Interleaving';
$strReset = "Nettoyer";
$strCancel = "Annuler";
$strAdd = 'Acheter';
$strMedia = 'Media';
$strRest = 'Restauration'; 
$strYes = 'Oui';
$strNo = 'Non'; 
$strMosaic = 'Mosa�que';
$strMosaic1 = 'Mosa�que des Passages du Satellite ';
$strMosaic2 = 'Mosa�que Br�sil';
$strNopassages = "Il n'y a pas de passage en ";
$strEndpassage = 'FIN DU PASSAGE ';

//Matrizes

$matOrientation = array(1=> "Nord de la Carte", "Satellite", "Nord V�ritable");
$matProj = array(1=> "UTM", "Lambert", "Polyconic", "LCC");
$matDatum = array(1=>"SAD69", "WGS84");

$matCorLevelL1 = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelL2 = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelL3 = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelL5 = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelL7 = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelCB = array(3, "Niveau 0", "Niveau 1", "Niveau 2");
$matCorLevelT1 = array(1, "Niveau 2");
$matCorLevelA1 = array(1, "Niveau 2");

$matResL1 = array(5, "NN", "BL", "BC", "8 point Sinc", "16 point Sinc");
$matResL2 = array(5, "NN", "BL", "BC", "8 point Sinc", "16 point Sinc");
$matResL3 = array(5, "NN", "BL", "BC", "8 point Sinc", "16 point Sinc");
$matResL5 = array(5, "NN", "BL", "BC", "8 point Sinc", "16 point Sinc");
$matResL7 = array(5, "NN", "BL", "BC", "8 point Sinc", "16 point Sinc");
$matResCB = array(3, "NN", "BL", "BC");
$matResT1 = array(3, "NN", "BL", "BC");
$matResA1 = array(3, "NN", "BL", "BC");

$matFormatL5 = array(3, "GeoTiff", "Fast Format", "CCRS");
$matFormatL7 = array(3, "GeoTiff", "FastL7A", "HDF");
$matFormatCB = array(1, "GeoTiff");

$matMedia = array("FTP","CD");

$strMosaicMsg = " Cher Utilisateur,</br></br>
Cet outil (Mosa�que du Passage) contient toutes les sc�nes qui ont �t� enregistr�es durant l'orbite (path) indiqu�.</br> La mosa�que peut contenir des sc�nes avec une qualit� basse (e.g. bruits du syst�me ) qui ont �t� refus�es par le contr�le de qualit� de la DGI/INPE (<em style=\"color:#FF0000\">Num�ro de ligne</em> en rouge). <br/>
L'utilisateur pourra solliciter et t�l�charger n'importe laquelle de ces images, il suffira de cliquer sur la sc�ne d�sir�e et de la mettre dans le panier. Il est, cela dit, conscient qu'il pourra ou NON recevoir des images avec les probl�mes cit�s au dessus. </br>
L'objectif de mettre toutes les sc�nes � disposition de l'utilisateur dans la mosa�que du Passage, est de permettre un acc�s large et sans restriction � la base de donn�es d'images, m�me les images qui ont des probl�mes. </br>
A partir des autres outils de recherche (tableau du cot� gauche), l'utilisateur n'aura acc�s qu'aux sc�nes approuv�es par le contr�le de qualit�. </br></br> </br></br>";

$strPassages1 = "Passages du satellite $satellite (capteur $sensor) par l'orbite $path.";
$strPassages2 = "mosa�que de ce passage";
$strPassages3 = "Il n'y a pas de passages du satellite $satellite par l'orbite $path ";

?>