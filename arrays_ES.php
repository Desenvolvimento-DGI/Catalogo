<?php
//------------------------------------------------------------------------------
//    Strings
//------------------------------------------------------------------------------
// Exibiчуo
$matSatCod =  array (2, "CB2", "L3");

$matSatName = array (2, "Cbers", "Landsat");

$matOption = array(1=> "Incluir", "Buscar");

$matCompanyType = array(12, "Comercializaci&oacute;n de Datos", "Empresa Estatal", "Empresa Privada",
            "Educaci&oacute;n B&aacute;sica", "Educaci&oacute;n Superior", "Investigaci&oacute;n", "Gobierno Municipal",
            "Gobierno Estatal", "Gobierno Federal", "Consultor&iacute;a", "ONG", "Otro");

$matActivity = array(15, "Agricultura", "Biolog&iacute;a", "Cartograf&iacute;a", "Medio Ambiente",
            "Educaci&oacute;n", "&Aacute;rea Forestal", "Geograf&iacute;a", "Geolog&iacute;a", "Salud", "Hidrolog&iacute;a",
            "Procesamiento de Im&aacute;genes", "Planeamiento", "Socioeconom&iacute;a", "Transportes", "Otro");

$matState = array(28, " ", "AC", "AL", "AP", "AM", "BA", "DF", "CE", "ES", "GO",
                           "MA", "MT", "MS", "MG", "PA", "PB", "PE", "PI", "PR",
                           "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");

$matUserType =  array(5, "Gerente", "Internet", "Funcionario INPE", "Persona F&iacute;sica/Jur&iacute;dica", "Extrajero");

$matStatus =  array(4, "Autorizado", "No Autorizado", "Bloqueado", "Cancelado");

//$matOrderUser = array(4, "Usuario", "Nombre", "Organizaci&oacute;n", "Ciudad");
$matOrderUser = array(2, "Usuario", "Nombre del Usuario");

$matOrderReq = array(3, "Pedido", "Fecha", "Usuario");

$matReqItemStatus = array(9,  "Esperando Autorizaci&oacute;n", "Esperando Producci&oacute;n", "En Producci&oacute;n",
                              "Esperando Grabaci&oacute;n en Dispositivo de Almacenamiento", "Esperando Despacho", "Despachado",
                              "Rechazado", "Falla en la Producci&oacute;n", "Problema en la Producci&oacute;n");
$matReqStatus= array(6, "Esperando Autorizaci&oacute;n", "Abierto", "Producido", "Cerrado",
                         "Esperando Producci&oacute;n", "Problema");
$matDepartment = array(7, "AMZ - Programa de la Amazon&iacute;a", "DGI- Divisi&oacute;n de Gerneraci&oacute;n de Im&aacute;genes",
                           "DMA- Divis&oacute;n de Clima y Medio Ambiente", "DMD - Divis&oacute;n de Modelaje y Desarrollo",
                           "DPI - Divis&oacute;n de Procesamiento de Im&aacute;genes", "DSA - Divis&oacute;n de Sat&eacute;lites y Sistemas Ambientales",
                           "DSR - Divis&oacute;n de Percepci&oacute;n Remoto");
                           
                        
//Interna - Base de Dados
/*$matCompanyType = array(2, "Comercio", "Empresa Estatal", "Empresa Privada",
            "Educaci&oacute;n B&aacute;sica", " Educaci&oacute;n Superior", "Investigaci&oacute;n ", "Gobierno Municipal",
            "Gobierno Estadual", "Consultor&iacute;a", "ONG", "Otro");

$matActivityDis = array(15, "Agricultura", "Biolog&iacute;a", "Cartograf&iacute;a", "Medio Ambiente",
            "Educaci&oacute;n", "&Aacute;rea Forestal", "Geograf&iacute;a", "Geolog&iacute;a", "Salud", "Hidrolog&iacute;a",
            "Procesamiento de Im&aacute;genes", "Planeamiento", "Socioeconom&iacute;a", "Transportes", "Otro");
*/
$matOrderUserInt = array(4, "User.userId", "User.fullName", "Company.name", "Address.city");
$matOrderReqInt = array(3, "ReqId", "ReqDate", "UserId");
$matReqItemStatusInt = array(9,  'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W');
$matReqStatusInt = array(6,  'A', 'B', 'C', 'D', 'Z', 'Y');
?>