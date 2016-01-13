<?php 
// Produto
$titFormProd = "Ordre de Production";
$strId = 'Id';
$strShift = 'Déplacement';
$strCorLevel = 'Niveau de Correction';
$strOrientation = 'Orientation';
$strProj = 'Projection';
$strLongO = "Longitude d'Origine";
$strLatO = "Latitude d'Origine";
$strStdLat1 = 'Standard Latitude 1';
$strStdLat2 = 'Standard Latitude 2';
$strDatum = 'Datum';
$strRes = 'Rééchantillonnage';
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
$strMosaic = 'Mosaïque';
$strMosaic1 = 'Mosaïque des Passages du Satellite ';
$strMosaic2 = 'Mosaïque Brésil';
$strNopassages = "Il n'y a pas de passage en ";
$strEndpassage = 'FIN DU PASSAGE ';

//Matrizes

$matOrientation = array(1=> "Nord de la Carte", "Satellite", "Nord Véritable");
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
Cet outil (Mosaïque du Passage) contient toutes les scènes qui ont été enregistrées durant l'orbite (path) indiqué.</br> La mosaïque peut contenir des scènes avec une qualité basse (e.g. bruits du système ) qui ont été refusées par le contrôle de qualité de la DGI/INPE (<em style=\"color:#FF0000\">Numéro de ligne</em> en rouge). <br/>
L'utilisateur pourra solliciter et télécharger n'importe laquelle de ces images, il suffira de cliquer sur la scène désirée et de la mettre dans le panier. Il est, cela dit, conscient qu'il pourra ou NON recevoir des images avec les problèmes cités au dessus. </br>
L'objectif de mettre toutes les scènes à disposition de l'utilisateur dans la mosaïque du Passage, est de permettre un accès large et sans restriction à la base de données d'images, même les images qui ont des problèmes. </br>
A partir des autres outils de recherche (tableau du coté gauche), l'utilisateur n'aura accès qu'aux scènes approuvées par le contrôle de qualité. </br></br> </br></br>";

$strPassages1 = "Passages du satellite $satellite (capteur $sensor) par l'orbite $path.";
$strPassages2 = "mosaïque de ce passage";
$strPassages3 = "Il n'y a pas de passages du satellite $satellite par l'orbite $path ";

?>