<?php
//Retrive only one record based on SceneId
import_request_variables("gpc");
ob_implicit_flush();

include("globals.php");
include_once("stationDB.class.php");

$dbcat1 = $GLOBALS["dbcatalog"];
$dbcat2 = $GLOBALS["dbcatalog"];
$myurl = $GLOBALS["catalogURL"];

//  echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>" ;

//Getting query parameters from URL
$SCENEID = $_GET['sceneid'];

if(!isset($limit)) $limit = 1;
$invalid_path = 0;
$no_satellite = 0;

$filename = "metadata.xml";

// Monta o sql de pesquisa - caso venha sql em branco
if ($invalid_path != 1 and $no_satellite != 1)
{
   $sql = "SELECT * FROM Scene WHERE Deleted='0' AND CloudCoverMethod='M' AND SceneId= '$SCENEID'";
//$sql = "SELECT * FROM Scene WHERE 1";	
//echo "/n<br> ====> sql = $sql <br>";
  
if (!$dbcat1->query($sql)) $dbcat1->error($sql);
  
  $total_rows = $dbcat1->numRows();
  
  header("Content-type: application/octet-stream");
  header("Content-Type: text/xml;charset=ISO-8859-1"); 
  echo "<?xml version=\"1.0\" ?> 
  <searchResponse xmlns=\"http://upe.ldcm.usgs.gov/schema/metadata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://upe.ldcm.usgs.gov/schema/metadata http://edcsns17.cr.usgs.gov/EE/metadata.xsd\">
  <returnStatus value=\"success\">Completed Successfully</returnStatus>";

// $num_rows = mysql_num_rows($dbcat1->query($sql));

  if(isset($limit) and $limit > 0)
	 if($total_rows > $limit) $num_rows = $limit;
   else $limit = $num_rows = $total_rows;
   
//   echo "\n sql =$sql \n";
//   echo "\n limit = $limit numrows = $num_rows totalrows = $total_rows \n";
//  while($rowarray=$dbcat1->fetchRow()) 

echo "
	<totalRecords>$total_rows</totalRecords>
	<numRecords>$num_rows</numRecords>
";
   for ($i=1;$i<=$limit;$i++) 
	{
	$rowarray=$dbcat1->fetchRow();
	$SceneId = $rowarray["SceneId"];
	$Satellite = $rowarray["Satellite"];
	$Sensor = $rowarray["Sensor"];
	$Path = $rowarray["Path"];
	$Row = $rowarray["Row"];
	$Date = $rowarray["Date"];
	$C_Lat=$rowarray["CenterLatitude"];
	$C_Long=$rowarray["CenterLongitude"];
	$TL_Lat=$rowarray["TL_Latitude"];
	$TL_Long=$rowarray["TL_Longitude"];
	$BR_Lat=$rowarray["BR_Latitude"];
	$BR_Long=$rowarray["BR_Longitude"];
	$TR_Lat=$rowarray["TR_Latitude"];
	$TR_Long=$rowarray["TR_Longitude"];
	$BL_Lat=$rowarray["BL_Latitude"];
	$BL_Long=$rowarray["BL_Longitude"];
		
  if(!isset($TR_Lat)) $TR_Lat = $TL_Lat;
	if(!isset($BL_Lat)) $BL_Lat = $BR_Lat;
	if(!isset($TR_Long)) $TR_Long = $BR_Long;
	if(!isset($BL_Long)) $BL_Long = $TL_Long;
  
	$StartTime = $rowarray["StartTime"];
	$StopTime = $rowarray["StopTime"];
	$CloudCoverQ1 = $rowarray["CloudCoverQ1"];
	$CloudCoverQ2 = $rowarray["CloudCoverQ2"];
	$CloudCoverQ3 = $rowarray["CloudCoverQ3"];
	$CloudCoverQ4 = $rowarray["CloudCoverQ4"];
	$IngestDate = $rowarray["IngestDate"];
	$Grade = $rowarray["Grade"];
	$receivingStation = "CBA";
  	$processingStation = "CP";
	$distributionFormat = "GeoTIFF";
	$distributor = "DGI";
	
	switch ($rowarray["Satellite"])
      	{
  		case "CB1":
  		case "CB2":
  		case "CB2B":
  		$tab_sat_prefix = "Cbers";
  		break;
  		case "L1":
  		case "L2":
  		case "L3":
  		case "L5":
  		case "L7":
  		$tab_sat_prefix = "Landsat";
  		break;
  		case "A1":
  		case "T1":
  		$tab_sat_prefix = "Modis";
  		break;
		case "P6":
		$tab_sat_prefix = "P6";
		break; 
	};

    $table = $tab_sat_prefix . "Scene"; 
    
    $StartTime = gmdate("H:i:s",$rowarray["StartTime"]*24*3600);
    $StopTime = gmdate("H:i:s",$rowarray["StopTime"]*24*3600);
    $cloudCoverFull = (($CloudCoverQ1+$CloudCoverQ2+$CloudCoverQ3+$CloudCoverQ4)/4);
    $browseURL = "$myurl"."display.php?TABELA=Browse&amp;PREFIXO=$tab_sat_prefix&amp;INDICE=$SceneId";
    $cartURL = "$myurl"."cart-cwic.php?SCENEID=$SceneId";
    
    $satsen = $satellite . "_$Sensor";
    $browseAv = "Y";
     
    $sql = "SELECT * FROM $table WHERE SceneId='$SceneId'";
            $dbcat2->query($sql);
     	 if (!($suprow_array = $dbcat2->fetchRow())) die ($dbcat2->errorMessage());
    	$OffNadirAngle = $suprow_array["OffNadirAngle"];
    	$SunAzimuth = $suprow_array["SunAzimuth"];
    	$SunElevation = $suprow_array["SunElevation"];
    //  $dbcat->freeResult(); 
    
    echo "<metaData>
    	<browseAvailable>$browseAv</browseAvailable>
    	<browseURL>$browseURL</browseURL>
	<cartURL>$cartURL</cartURL>
    	<sceneID>$SceneId</sceneID>
    	<sensor>$satsen</sensor>
    	<acquisitionDate>$Date</acquisitionDate>
    	<dateUpdated>$IngestDate</dateUpdated>
    	<path>$Path</path>
    	<row>$Row</row>
	<upperLeftCornerLatitude>$TL_Lat</upperLeftCornerLatitude>
    	<upperLeftCornerLongitude>$TL_Long</upperLeftCornerLongitude>
    	<upperRightCornerLatitude>$TR_Lat</upperRightCornerLatitude>
    	<upperRightCornerLongitude>$TR_Long</upperRightCornerLongitude>
    	<lowerLeftCornerLatitude>$BL_Lat</lowerLeftCornerLatitude>
    	<lowerLeftCornerLongitude>$BL_Long</lowerLeftCornerLongitude>
    	<lowerRightCornerLatitude>$BR_Lat</lowerRightCornerLatitude>
    	<lowerRightCornerLongitude>$BR_Long</lowerRightCornerLongitude>
    	<sceneCenterLatitude>$C_Lat</sceneCenterLatitude>
    	<sceneCenterLongitude>$C_Long</sceneCenterLongitude>
    	<sceneStartTime>$StartTime</sceneStartTime>
    	<sceneStopTime>$StopTime</sceneStopTime>
	<cloudCoverFull>$cloudCoverFull</cloudCoverFull>
    	<FULL_UL_QUAD_CCA>$CloudCoverQ1</FULL_UL_QUAD_CCA>
    	<FULL_UR_QUAD_CCA>$CloudCoverQ2</FULL_UR_QUAD_CCA>
    	<FULL_LL_QUAD_CCA>$CloudCoverQ3</FULL_LL_QUAD_CCA>
    	<FULL_LR_QUAD_CCA>$CloudCoverQ4</FULL_LR_QUAD_CCA>
    	<sunElevation>$SunElevation</sunElevation>
    	<sunAzimuth>$SunAzimuth</sunAzimuth>
	<imageQuality>$Grade</imageQuality>
	<receivingStation>$receivingStation</receivingStation>
    	<processingStation>$processingStation</processingStation>
	<distributionFormat>$distributionFormat</distributionFormat>
	<distributor>$distributor</distributor>
    	</metaData>
	";
  }  // end while statement
}
else 
{
  header("Content-type: application/octet-stream");
  header("Content-Type: text/xml;charset=ISO-8859-1"); 
  if($invalid_path) $errmsg = "Invalid path/row values";
  else if($no_satellite) $errmsg = "No satellite value";
  echo "<?xml version=\"1.0\" ?> 
  <searchResponse xmlns=\"http://upe.ldcm.usgs.gov/schema/metadata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://upe.ldcm.usgs.gov/schema/metadata http://edcsns17.cr.usgs.gov/EE/metadata.xsd\">
  <returnStatus value=\"error\">$errmsg</returnStatus> \n";
}
echo "</searchResponse>";
?>
