<?php //------------------------------------------------------------------------------
// Strings
//------------------------------------------------------------------------------
// Affichage
$matSatCod = array (2, "CB2", "L3");
$matSatName = array (2, "Cbers", "Landsat");
$matOption = array(1=> "Inclure", "Rechercher");
$matCompanyType = array(12, "Commercialisation de Donn�es", "Entreprise Etatique", "Entreprise Priv�e",
"Education Basique", "Education Sup�rieure", "Recherche", "Collectivit� Territoriale",
"Pr�fecture", "Gouvernement F�d�rale", "Consultation", "ONG", "Autre");
$matActivity = array(15, "Agriculture", "Biologie", "Cartographie", "Environnement",
"Education", "Botanique", "G�ographie", "G�ologie", "Sant�", "Hydrologie",
"Traitment d'Images", "Planification", "Socio-economie", "Transports", "Autre");
$matState = array(28, " ", "AC", "AL", "AP", "AM", "BA", "DF", "CE", "ES", "GO",
"MA", "MT", "MS", "MG", "PA", "PB", "PE", "PI", "PR",
"RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");
$matUserType = array(5, "G�rant", "Internet", "Fonctionnaire INPE", "Personne Physique/Juridique", "Etranger");
$matStatus = array(4, "Autoris�", "Non Autoris�", "Bloqu�", "Annul�");
//$matOrderUser = array(4, "Utilisateur", "Nom", "Organisation", "Ville");
$matOrderUser = array(2, "Utilisateur", "Nom Utilisateur");
$matOrderReq = array(3, "Commande", "Donn�e", "Utilisateur");
$matReqItemStatus = array(9, "Attendre Autorisation", "Attendre la Production", "En Production",
"Attendre l'enregistrement du M�dia", "Attendre l'envoi", "Envoy�",
"Rejet�", "Erreur de Production", "Probl�me de Production");
$matReqStatus= array(6, "Attendre Autorisation", "Ouvert", "Produit", "Ferm�",
"Attendre la Production", "Probl�me");
$matDepartment = array(7, "AMZ - Programme de la Amazonie", "DGI- Division de Gestion des Images",
"DMA- Division deu Climat et de l'Environnement", "DMD - Division de Mod�lisation et D�veloppement",
"DPI - Division de Traitment des Images", "DSA - Division des Satellites et des Syst�mes Atmosph�riques",
"DSR - Division de Capture � Distance");
//Interne - Base de Donn�es
/*$matCompanyType = array(2, "Commerce", "Entreprise Etatique", "Entreprise Priv�e",
"Education Basique", "Education Sup�rieure", "Recherche", "Collectivit� Territoriale",
"Pr�fecture", "Consultation", "ONG", "Autre");
$matActivityDis = array(15, "Agriculture", "Biologie", "Cartographie", "Environnement",
"Education", "Botanique", "G�ographie", "G�ologie", "Sant�", "Hydrologie",
"Traitment d'Images", "Planification", "Socio-economie", "Transports", "Autre"
);
*/
$matOrderUserInt = array(4, "User.userId", "User.fullName", "Company.name", "Address.city");
$matOrderReqInt = array(3, "ReqId", "ReqDate", "UserId");
$matReqItemStatusInt = array(9, 'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W');
$matReqStatusInt = array(6, 'A', 'B', 'C', 'D', 'Z', 'Y');
?>