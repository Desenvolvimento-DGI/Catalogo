<?php
// Product
$titFormProd = "Production Order";
$strId = 'Id';
$strShift = 'Shift';
$strCorLevel = 'Correction Level';
$strOrientation = 'Orientation';
$strProj = 'Projection';
$strLongO = 'Longitude Origin';
$strLatO  = 'Latitude Origin';
$strStdLat1 = 'Standard Latitude 1';
$strStdLat2 = 'Standard Latitude 2';
$strDatum = 'Datum';
$strRes = 'Resampling';
$strBands = 'Bands';
$strFormat = 'Format';
$strInter =  'Media Interleaving';
$strReset = 'Reset';
$strCancel = 'Cancel';
$strAdd = 'Buy Product';
$strMedia = 'Media';
$strRest = 'Restoration'; 
$strYes = 'Yes';
$strNo = 'No';
$strMosaic = 'Mosaic';
$strMosaic1 = 'Scenes Mosaic of ';
$strMosaic2 = 'Brazil\'s Mosaic';
$strNopassages = 'No passages on ';
$strEndpassage = ' END OF PASSAGE ';

//Matrizes
$matOrientation = array(1=> "Map North", "Satellite", "True North");
$matProj = array(1=> "UTM", "Lambert", "Polyconic", "LCC");
$matDatum = array(1=>"SAD69", "WGS 1984");

$matCorLevelL1 = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelL2 = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelL3 = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelL5 = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelL7 = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelCB = array(3, "Level 0", "Level 1", "Level 2");
$matCorLevelT1 = array(1, "Level 2");
$matCorLevelA1 = array(1, "Level 2");

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

// Product Default
$matProdDef = array ("Bands"=>"All", "SceneShift"=>0, "Corretion"=>"Systematic", "Orientation"=>"Satellite",
               "Resampling" => "BC", "Datum" => "SAD69", "Projection" => "UTM", "LongOrigin" => 0,
               "LatOrigin" => 0, "StdLat1" => 0, "StdLat2" => 0, "Format" => "GeoTiff", "Interleaving" => "BSQ",
               "Media" => "FTP");
$matMedia = array("FTP","CD");

$strMosaicMsg = " Dear User,</br></br>
This tool (Mosaic) renders all scenes recorded during the selected orbit (path).</br> Mosaics may contain low quality scenes (e.g. scenes containing system sensors noises) that have been refused by DGI/INPE's Image Quality Control (<em style=\"color:#FF0000\">Row number</em> in red). <br/>
Users may request and download these scenes just clicking on the target one and loading it into the cart, being awared of potential problems (quality) mentioned above and, in some cases, the impossibility of accomplishing the request. </br>
Placing all scenes at users' reach by the Mosaic tool, attends the purpose of irrestricted access to the image data base assemblage.</br>
Stating from other searching tools (panel on the left), only control quality approved images will be retrieved. </br></br></br></br>";  

$strPassages1 = "$satellite satellite (sensor $sensor) passages on orbit $path.";
$strPassages2 = "This Passage Mosaic";
$strPassages3 = " No $satellite passages on orbit $path ";

?>