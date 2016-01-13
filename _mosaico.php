<?
include("session_mysql.php"); 
session_start();
require("css.php");
include("globals.php");

if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}

require ("panel_".$_SESSION['userLang'].".php"); 

// Incoming parameters : e.g. ---> QUICK=S&TAM=G&SATELITE=L5&SENSOR=TM&Q1=50&Q2=50&Q3=50&Q4=50&IDATE=1973-05-29&FDATE=2013-08-12&LAT=-17&LON=-48&IMA=esad&RES=1&TEM=cenas 

$sensor = $SENSOR;
$satelite = $SATELITE;
$latitude = $LAT;
$longitude = $LON;

if(!isset($ZOOM)) $ZOOM = 6;

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>"; 

	// sql para pegar as cenas
	
$dbcat = $GLOBALS["dbcatalog"];
$table="Scene";
$sql = "SELECT * FROM $table WHERE Deleted=0";
$params = "&";

if ($SATELITE)
{ 
  $complement = "";
  if($SATELITE == "GLS") 
  {
   $SATELITE = "L[5,7]";
   $condition = "REGEXP";
   $sql .= " AND SceneId LIKE '%GLS' ";
  }

  else $condition = "=";
	$sql .= " AND Satellite $condition '" . $SATELITE . "'" . $complement ;
	
	if($SATELITE == "L[5,7]")
	{
	 $SATELITE = "GLS";
	 $SENSOR = "";
	}
	
	$params .= "SATELITE=".$SATELITE."&";
}
if ($SENSOR)
{
	if($SENSOR == "TM,ETM") 
  {
   $SENSOR = "[ETM,TM]";
   $condition = "REGEXP";
  }
  else $condition = "=";
	$sql .= " AND Sensor $condition '" . $SENSOR . "'";

	$params .= "SENSOR=".$SENSOR."&";
}
if ($IDATEM && $FDATEM)
{
	$params .= "IDATEM=".$IDATEM."&"."FDATEM=".$FDATEM."&"; 
	if ($IDATEM > $FDATEM)
	{
		$sql .= " AND (month(Date)>=" . $IDATEM . " OR   ";
		$sql .= "month(Date)<=" . $FDATEM . ")";
	}
	else if ($IDATEM == $FDATEM)
	{
		$sql .= " AND month(Date)=" . $IDATEM;
	}
	else
	{
		$sql .= " AND month(Date)>=" . $IDATEM;
		$sql .= " AND month(Date)<=" . $FDATEM; 
	}
}
else if ($IDATEM)
	{
		$params .= "IDATEM=".$IDATEM."&";
		$sql .= " AND month(Date)>=" . $IDATEM;
	}
else if ($FDATEM)
	{
		$params .= "FDATEM=".$FDATEM."&";
		$sql .= " AND month(Date)<=" . $FDATEM;
	}
if ($IDATEY && $FDATEY)
{
	$params .= "IDATEY=".$IDATEY."&"."FDATEY=".$FDATEY."&";
	if ($IDATEY == $FDATEY)
	{
		$sql .= " AND year(Date)=" . $IDATEY;
	}
	else
	{
		$sql .= " AND year(Date)>=" . $IDATEY;
		$sql .= " AND year(Date)<=" . $FDATEY;
	}
}
else if ($IDATEY)
	{
		$params .= "IDATEY=".$IDATEY."&";
		$sql .= " AND year(Date)>=" . $IDATEY;
	}
else if ($FDATEY)
	{
		$params .= "FDATEY=".$FDATEY."&";
		$sql .= " AND year(Date)<=" . $FDATEY;
	}
if ($IDATE)
{
	$params .= "IDATE=".$IDATE."&";
	$sql .= " AND Date >= '" . $IDATE . " 00:00:00"."'";
}
if ($FDATE)
{
	$params .= "FDATE=".$FDATE."&";
	$sql .= " AND Date <= '" . $FDATE . " 23:59:59"."'";
}

if (isset($Q1))
{
	$params .= "Q1=".$Q1."&";
	$sql .= " AND CloudCoverQ1<=$Q1";
}
if (isset($Q2))
{
	$params .= "Q2=".$Q2."&";
	$sql .= " AND CloudCoverQ2<=$Q2";
}
if (isset($Q3))
{
	$params .= "Q3=".$Q3."&";
	$sql .= " AND CloudCoverQ3<=$Q3";
}
if (isset($Q4))
{
	$params .= "Q4=".$Q4."&";
	$sql .= " AND CloudCoverQ4<=$Q4";
}

if(isset($ZOOM) and $ZOOM == 9)
{
 $lat1 = $LAT - 2;
 $lat2 = $LAT + 2;
 $lon1 = $LON - 2;
 $lon2 = $LON + 2;
 $sql .= " AND CenterLatitude>=$lat1 AND CenterLatitude<=$lat2 ";
 $sql .= " AND CenterLongitude>=$lon1 AND CenterLongitude<=$lon2 ";
}

$sql .= " LIMIT 10001";  // Not to cause servers overload.

$manager = "mantab";
if (isset($QUICK) && $QUICK=="B")
	$manager = "manage";

// echo $sql;
$params .= "TAM=".$TAM; 



//$sql = "SELECT * FROM Scene WHERE Deleted=0 AND Satellite = 'L5' AND Sensor = 'TM' AND Path='224'";
$dbcat->query($sql);

if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : sql = $sql \n";
if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : records found = " . $dbcat->numRows() . " \n"; 
if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : params = $params \n";

//$number_registers = $dbcat->numRows();
//if($number_registers > 10000)
//{
// $message = $strNavigation1;
// echo "<script type='text/javascript'>alert('$message');</script>";
// die();
//}

$n = 1;
while ($myrow = $dbcat->fetchRow())
{
	$sensor = $myrow["Sensor"];
	$lat = $myrow["CenterLatitude"];
//	$auxlat = sprintf('%1$2.2f',$lat); // Write it with 2 decimal digits !
	$lon = $myrow["CenterLongitude"];
//	$auxlon = sprintf('%1$2.2f',$lon); // Write it with 2 decimal digits !
	$path = $myrow["Path"];
  $row = $myrow["Row"];
	$satelite = $myrow["Satellite"];
//	if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : satelite = $satelite path = $path row = $row lat = $lat lon = $lon <br> \n";

  $linha .= "['$satelite',$lat,$lon,$n,'$path','$row','$sensor'],";  // do not gather alpha characres [a-z] with digits [0-9] - because javascript conventions !

	$n++;
}
			
$myArray = "[".$linha."]";

$rectangle_upperleft_lat = $LAT + 3.0;
$rectangle_upperleft_lon = $LON - 3.0;
$rectangle_bottomright_lat = $LAT - 3.0;
$rectangle_bottomright_lon = $LON + 3.0;

$rectangle_upperright_lat = $LAT + 3.0;
$rectangle_upperright_lon = $LON + 3.0;
$rectangle_bottomleft_lat = $LAT - 3.0;
$rectangle_bottomleft_lon = $LON - 3.0;



// if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : myArray = $myArray <br> \n"; 
if ($GLOBALS["stationDebug"]) echo "\n <br> =========> mosaico.php : LAT = $LAT LON = $LON \n<br>
                                    rectangle_upperleft_lat = $rectangle_upperleft_lat rectangle_upperleft_lon = $rectangle_upperleft_lon \n<br>
   																	rectangle_bottomright_lat = $rectangle_bottomright_lat rectangle_bottomright_lon = $rectangle_bottomright_lon \n<br>
   																	rectangle_upperright_lat = $rectangle_upperright_lat rectangle_upperright_lon = $rectangle_upperright_lon \n<br>
   																	rectangle_bottomleft_lat = $rectangle_bottomleft_lat rectangle_bottomleft_lon = $rectangle_bottomleft_lon \n<br>";
   																	
			
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
				}
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing"></script> 
    <script>
var map ;
var overlay;
USGSOverlay.prototype = new google.maps.OverlayView();

function openwin(url) {
	nwin=window.open(url,'MW','');
	nwin.focus();
}

 
function initialize()
{
  var mapOptions = {
//    zoom: 6,
  zoom: <? echo $ZOOM ?>,
    center: new google.maps.LatLng(<? echo "$latitude,$longitude";?>),
    mapTypeId: google.maps.MapTypeId.HYBRID
 
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
	map.setTilt(0);
	
	var grdTMLayer = new google.maps.KmlLayer
	({
    url: '<? echo "$grd";?>',
  });

    	var drawingManager = new google.maps.drawing.DrawingManager
			({ 
			 drawingControl: true,
	     drawingControlOptions: 
			  {
        position: google.maps.ControlPosition.TOP_CENTER,
        drawingModes:	[google.maps.drawing.OverlayType.RECTANGLE]
        },    
      }); 
			
	drawingManager.setMap(map); 
	
	var n = 1;
			
 google.maps.event.addListener(drawingManager, 'rectanglecomplete', function(newRect) {
    rectangle = newRect;
		var rectangleBounds = rectangle.getBounds();
		var northEast = rectangleBounds.getNorthEast();
    var southWest = rectangleBounds.getSouthWest();
//    var southEast = rectangleBounds.getNorthEast();  // south and north in reverse !
//    var northWest = rectangleBounds.getSouthWest();  // south and north in reverse !

    // deduce the top-left and bottom-right corner coordinates
//    var topLeft = new google.maps.LatLng(northEast.lat(), southWest.lng());
//    var bottomRight = new google.maps.LatLng(southWest.lat(), northEast.lng());

    var bottomLeft = new google.maps.LatLng(southWest.lat(), southWest.lng());
    var topRight = new google.maps.LatLng(northEast.lat(), northEast.lng());
    
//    alert(topLeft.lat()); 
//    alert("southWest.lat = " + bottomLeft.lat() + " southWest.lng = " + bottomLeft.lng() + "\n" + 
//					" northEast.lat = " + topRight.lat() + " northEast.lng = " + topRight.lng()); 
				  
	 teste = confirm('<? echo $strMosaicAlert7 ?>');
			
	 if (teste == 1)
	 {
		 window.open("mantab.php?MOSAICO=1&QUICK=S&TAM=G&SATELITE=<? echo $SATELITE ?>&SENSOR=<? echo $SENSOR ?>&IDATE=<? echo $IDATE ?>&FDATE=<? echo $FDATE ?>&LAT1=" + bottomLeft.lat() + 
	               "&LAT2=" + topRight.lat() + "&LON1=" + bottomLeft.lng() + "&LON2=" + topRight.lng(),n,"height=800,width=800,scrollbars=1") ; 
	   n = n + 1;
	 }
	 
	 rectangle.setMap(null);
		
  }); 


	var instances = <? echo $myArray; ?>;
	setMarkers(map,instances);
	
} // fim do initialize ++++++++++

	
function setMarkers(map,instances)
{  

    var image = '../Suporte/images/arrow-blue.png';	
		var myLatLng = new google.maps.LatLng(<? echo $LAT ?>,<? echo $LON ?>);
		Marker = new google.maps.Marker
		({
       position: myLatLng,
       map: map,
       icon: image,
			 title: 'LAT = ' + '<? echo $LAT; ?>' + ' LON = ' + '<? echo $LON; ?>' 
		});
			

    for (var i = 0; i <= <? echo $n; ?>; i++)
		{		
     var instance = new Array();
		 instance = instances[i];
//		 alert(instance);
		 var image = '../Suporte/images/' + instance[0] + '.gif';	
		 var myLatLng = new google.maps.LatLng(instance[1], instance[2]);
		 
//	   console.log(instance[1]); 
      
		 Marker = new google.maps.Marker
		 ({
       position: myLatLng,
       map: map,
       icon: image,
			 title: 'lat = ' + instance[1] + ' lon = ' + instance[2] + ' satellite = ' + instance[0] + ' sensor = ' + instance[6] + ' path = ' + instance[4] + ' row = ' + instance[5],
			});
  
     
     var contentString = '<div id="content"><center>' + 
    ' <a style="white-space: nowrap" href="mantab.php?ORBITAI=' + instance[4]  + '&PONTOI=' + instance[5] + '<? echo $params ?>' + '"><br><h3><? echo $strNavigation2 ?></br>' + //</a>' + //' </center></div>' ;
		'lat = ' + instance[1].toFixed(2) + ' lon = ' + instance[2].toFixed(2) + ' sat = ' + instance[0] + ' path = ' + instance[4] + ' row = ' + instance[5] + '</h3></a></center></div>' ;
		
  	 var infowindow = new google.maps.InfoWindow({content: contentString});

     bindInfoWindow(Marker, map, infowindow, contentString); 

    } 
       
		 
		function bindInfoWindow(Marker,map,infowindow,html) {
        google.maps.event.addListener(Marker,'click',function() {
          infowindow.setContent(html);
          infowindow.open(map, Marker);
        });
    } 
	
} 


/* @constructor */
function USGSOverlay(bounds, image, map) {

  // Now initialize all properties.
  this.bounds_ = bounds;
  this.image_ = image;
  this.map_ = map;

  // We define a property to hold the image's div. We'll
  // actually create this div upon receipt of the onAdd()
  // method so we'll leave it null for now.
  this.div_ = null;

  // Explicitly call setMap on this overlay
  this.setMap(map);
}

USGSOverlay.prototype.onAdd = function() {

  // Note: an overlay's receipt of onAdd() indicates that
  // the map's panes are now available for attaching
  // the overlay to the map via the DOM.

  // Create the DIV and set some basic attributes.
  var div = document.createElement('div');
  div.style.borderStyle = 'none';
  div.style.borderWidth = '0px';
  div.style.position = 'absolute';

  // Create an IMG element and attach it to the DIV.
  var img = document.createElement('img');
  img.src = this.image_;
  img.style.width = '100%';
  img.style.height = '100%';
  img.style.position = 'absolute';
  div.appendChild(img);

  // Set the overlay's div_ property to this DIV
  this.div_ = div;

  // We add an overlay to a map via one of the map's panes.
  // We'll add this overlay to the overlayLayer pane.
  var panes = this.getPanes();
  panes.overlayLayer.appendChild(div);
}

USGSOverlay.prototype.draw = function() {

  // Size and position the overlay. We use a southwest and northeast
  // position of the overlay to peg it to the correct position and size.
  // We need to retrieve the projection from this overlay to do this.
  var overlayProjection = this.getProjection();

  // Retrieve the southwest and northeast coordinates of this overlay
  // in latlngs and convert them to pixels coordinates.
  // We'll use these coordinates to resize the DIV.
  var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

  // Resize the image's DIV to fit the indicated dimensions.
  var div = this.div_;
  div.style.left = sw.x + 'px';
  div.style.top = ne.y + 'px';
  div.style.width = (ne.x - sw.x) + 'px';
  div.style.height = (sw.y - ne.y) + 'px';
}

USGSOverlay.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);
  this.div_ = null;
} 

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
    <?
    $number_registers = $dbcat->numRows();
    if($number_registers > 10000)
    {
      $message = $strNavigation1;
      echo "<script type='text/javascript'>alert('$message');</script>";
		}
    ?>
  </body>
</html>
