<?php
//PHP Script for CWIC Inventory Service

import_request_variables("gpc");
ob_implicit_flush();

include("globals.php");
include_once("stationDB.class.php");

$dbcat1 = $GLOBALS["dbcatalog"];
$dbcat2 = $GLOBALS["dbcatalog"];
$dbcat3 = $GLOBALS["dbcatalog"];
$myurl = $GLOBALS["catalogURL"];

//  echo "<br> " . $_SERVER['QUERY_STRING'] . " <br>" ;
//Entry IDs 
/*
INPE_LANDSAT1_MSS
INPE_LANDSAT2_MSS 
INPE_LANDSAT3_MSS
INPE_LANDSAT5_TM
INPE_LANDSAT7_ETM
INPE_TERRA1_MODIS 
INPE_AQUA1_MODIS 
INPE_IRS_LISS3 
INPE_IRS_AWIFS 
INPE_CBERS2_CCD 
INPE_CBERS2_WFI
INPE_CBERS2_IRM
INPE_CBERS2B_CCD
INPE_CBERS2B_WFI
INPE_CBERS2B_HRC
*/

//Translating Enrty IDs
$EID = $_GET['dataset'];

$result = explode ("_",$EID);
	$catid = $result[0];
	$satellite = $result[1];
	$sensor = $result[2];

//Exception for Resourcesat sensors	
if($sensor == "LISS3") $sensor = "LIS3";
if($sensor == "AWIFS") $sensor = "AWIF";


switch ($satellite)
{
 case "LANDSAT1":
        $SATELLITE = "L1";
        $SENSOR = "MSS";
        break;
 case "LANDSAT2":
        $SATELLITE = "L2";
        $SENSOR = "MSS";
        break;
 case "LANDSAT3":
        $SATELLITE = "L3";
        $SENSOR = "MSS";
        break;
 case "LANDSAT5":
        $SATELLITE = "L5";
        $SENSOR = "TM";
        break;
 case "LANDSAT7":
        $SATELLITE = "L7";
        $SENSOR = "ETM";
        break;
 case "TERRA1":
        $SATELLITE = "T1";
        $SENSOR = "MODIS";
        break;
 case "AQUA1":
        $SATELLITE = "A1";
        $SENSOR = "MODIS";
        break;
case "IRS":
        $SATELLITE = "P6";
        $SENSOR = $sensor;
        break;
case "CBERS2":
        $SATELLITE = "CB2";
        $SENSOR = $sensor;
        break;
case "CBERS2B":
        $SATELLITE = "CB2B";
        $SENSOR = $sensor;
        break;
}//End Switch

//Getting query parameters from URL
//$SATELLITE = $_GET['satellite'];
//$SENSOR = $_GET['sensor'];
$NORTH = $_GET['north'];
$SOUTH = $_GET['south']; 
$EAST = $_GET['east'];
$WEST = $_GET['west'];
$IDATE = $_GET['start_date'];
$FDATE = $_GET['end_date'];
$seasonal =$_GET['seasonal'];
$cc =$_GET['cc'];
$base = $_GET['startrec'];
$limit = $_GET['maxrecs'];

//$Q1 = $Q2 = $Q3 = $Q4 =  $cc*10;

if(!isset($limit)) $limit = 1000;
$maxrecs = $limit;
if(!isset($base)) $base = 0;
$no_satellite = 0;
$no_latlong = 0;

#
# Checking Satellite and LatLong
#

if(!isset($SATELLITE)) $no_satellite = 1; 
if(!isset($NORTH)) $no_latlong = 1;
if(!isset($SOUTH)) $no_latlong = 1;
if(!isset($EAST)) $no_latlong = 1;
if(!isset($WEST)) $no_latlong = 1;

if($NORTH <= $SOUTH) $no_latlong = 1;
if($EAST <= $WEST) $no_latlong = 1;


#
# End Checking Satellite
#

if($SATELLITE == "GLS") 
{
 $SATELLITE = "L[5,7]";
 $SENSOR = "[ETM,TM]";
 $condition = "REGEXP";
 $complement = " SceneId LIKE \"%GLS\" AND ";
}
else 
//if($SATELLITE == "L5" or $SATELLITE == "L7") { $condition = "=" ; $complement = " SceneId NOT LIKE '%GLS' AND ";} else
{
 $condition = "=";
 $complement = "";
}

$filename = "metadata.xml";

// Monta o sql de pesquisa - caso venha sql em branco
if ($no_satellite != 1 and $no_latlong != 1 and $catid == "INPE")
{

   $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM Scene WHERE Deleted='0' AND CloudCoverMethod='M' AND   ";

	if ($SATELLITE)
		$sql .= "Satellite $condition '".$SATELLITE."' AND $complement  ";
	if ($SENSOR)
		$sql .= "Sensor $condition '".$SENSOR."' AND   ";
	if ($SOUTH) 
		$sql .= "CenterLatitude >= $SOUTH AND   ";
	if ($NORTH)
		$sql .= "CenterLatitude <= $NORTH AND   ";
	if ($WEST) 
		$sql .= "CenterLongitude >= $WEST AND   ";
	if ($EAST)
		$sql .= "CenterLongitude <= $EAST AND   ";
		

	if ($IDATEM && $FDATEM)
	{
		if ($IDATEM > $FDATEM)
		{
			$sql .= "(month(Date)>=" . $IDATEM . " OR   ";
			$sql .= "month(Date)<=" . $FDATEM . ") AND   ";
		}
		else if ($IDATEM == $FDATEM)
		{
			$sql .= "month(Date)=" . $IDATEM . " AND   ";
		}
		else
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	}
	else if ($IDATEM)
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
		}
	else if ($FDATEM)
		{
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	if ($IDATEY && $FDATEY)
	{
		if ($IDATEY == $FDATEY)
		{
			$sql .= "year(Date)=" . $IDATEY . " AND   ";
		}
		else
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	}
	else if ($IDATEY)
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
		}
	else if ($FDATEY)
		{
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	 		 
	if ($IDATE)
//		$sql .= "Date>='" . $IDATE . " 00:00:00' AND   ";
      $sql .= "Date>='" . $IDATE . "' AND   ";
	if ($FDATE)
//		$sql .= "Date<='" . $FDATE . " 23:59:59' AND   ";
      $sql .= "Date<='" . $FDATE . "' AND   ";
	if (isset($Q1))
		$sql .= "CloudCoverQ1<=$Q1 AND   ";
	if (isset($Q2))
		$sql .= "CloudCoverQ2<=$Q2 AND   ";
	if (isset($Q3))
		$sql .= "CloudCoverQ3<=$Q3 AND   ";
	if (isset($Q4))
		$sql .= "CloudCoverQ4<=$Q4 AND   ";
	$sql = substr($sql,0,strlen($sql)-6);
	
//	$sql .="ORDER BY Date Desc, StartTime ASC LIMIT $limit";
  
	$sql .= "ORDER BY Date Desc, StartTime ASC LIMIT $base,$limit ";
	
//	echo "/n<br> ====> sql = $sql <br>";

  if (!$dbcat1->query($sql)) $dbcat1->error($sql);
  
   $sql_aux = "SELECT FOUND_ROWS()";  // Counting Total Records 
   
   if (!$dbcat3->query($sql_aux)) $dbcat3->error($sql_aux);
   
   $rowarray=$dbcat3->fetchRow();  // Counting Total Records 
   
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
	<startrec>$base</startrec>
	<maxrecs>$maxrecs</maxrecs>
	<totalRecords>$rowarray[0]</totalRecords>
	<recordsRecovered>$total_rows</recordsRecovered>
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
  if($no_latlong) $errmsg = "Invalid lat/long values";
  else if($no_satellite) $errmsg = "No satellite value";
	else if($catid != "INPE") $errmsg = "Unknown Catalog ID";
  echo "<?xml version=\"1.0\" ?> 
  <searchResponse xmlns=\"http://upe.ldcm.usgs.gov/schema/metadata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://upe.ldcm.usgs.gov/schema/metadata http://edcsns17.cr.usgs.gov/EE/metadata.xsd\">
  <returnStatus value=\"error\">$errmsg</returnStatus> \n";
}
echo "</searchResponse>";
?>
