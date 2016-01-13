<?php
// Produto
$titFormProd = "Orden de Producci&oacute;n"; 
$strId = 'Id';
$strShift = 'Desplazamiento';
$strCorLevel = 'Nivel de Correcci&oacute;n';
$strOrientation = 'Orientaci&oacute;n';
$strProj = 'Proyecci&oacute;n';
$strLongO = 'Longitud de Origen';
$strLatO  = 'Latitud de Origen';
$strStdLat1 = 'Standard Latitud 1';
$strStdLat2 = 'Standard Latitud 2';
$strDatum = 'Datum';
$strRes = 'Remuestreo';
$strBands = 'Bandas';
$strFormat = 'Formato';
$strInter =  'Media Intercalada';
$strReset = "Restaurar";
$strCancel = "Cancelar";
$strAdd = 'Comprar';
$strMedia = 'Media';
$strRest = 'Restauraci&oacute;n'; 
$strYes = 'S&iacute;';
$strNo = 'No'; 
$strMosaic = 'Mosaico';
$strMosaic1 = 'Mosaico del Pasaje del Sat&eacute;lite';
$strMosaic2 = 'Mosaico Brasil';
$strNopassages = 'No hay pasajes en ';
$strEndpassage = 'FIN DEL PASAJE ';

//Matrizes

$matOrientation = array(1=> "Norte Mapa", "Sat&eacute;lite",  "Norte Verdadero");
$matProj = array(1=> "UTM", "Lambert", "Polyconic", "LCC");
$matDatum = array(1=>"SAD69", "WGS84");

$matCorLevelL1 = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelL2 = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelL3 = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelL5 = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelL7 = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelCB = array(3, "Nivel 0", "Nivel 1", "Nivel 2");
$matCorLevelT1 = array(1, "Nivel 2");
$matCorLevelA1 = array(1, "Nivel 2");

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


$strMosaicMsg = " Estimado Usuario,</br></br>
Esta herramienta (Mosaico del Pasaje) contiene todas las escenas que fueron grabadas durante la &oacute;rbita (path) indicada.</br> El mosaico puede contener escenas de baja calidad (por ejemplo, ruidos del sistema sensor) que hayan sido recusadas por el control de calidad de la DGI/INPE (<em style=\"color:#FF0000\">Row number</em> en rojo). <br/>
El usuario podr&aacute; solicitar y descargar cualquiera de estas im&aacute;genes, haga clic sobre la escena deseada y col&oacute;quela en el carro de compras, estando conciente de que poder&aacute; o no reciber im&aacutegenes con los problemas citados anteriormente. </br>
El objetivo de colocar todas as escenas a disposici&oacute;n del usuario en el Mosaico del Pasaje, es permitir el acceso amplio e irrestricto al banco de datos de im&aacute;genes, inclusive aquellas que tienen problemas. </br>
A partir de las otras herramientas de b&uacute;squeda (cuadro del lado izquierdo), el usuario s&oacute;lo tiene acceso a las escenas que pasaron el control de calidad. </br></br> </br></br>";

$strPassages1 = "Pasajes del sat&eacute;lite $satellite (sensor $sensor) por la &oacute;rbita $path.";
$strPassages2 = "Mosaico de este pasaje";
$strPassages3 = " No hay pasajen del satélite $satellite por la órbita $path ";

?>