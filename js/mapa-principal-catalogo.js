// JavaScript Document


var rectangle;
var map;
var infoWindow;

function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-15.5, -47.5),
    zoom: 6,
	mapTypeId: google.maps.MapTypeId.HYBRID,	
	panControl: false,
	zoomControl: true,
	scaleControl: true			
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  var bounds = new google.maps.LatLngBounds(
	  new google.maps.LatLng(-19, -51),
      new google.maps.LatLng(-12, -44)
      
  );

  // Define the rectangle and set its editable property to true.
  /*
  rectangle = new google.maps.Rectangle({
    bounds: bounds,
    editable: true,
    draggable: true
  });
  */

  //rectangle.setMap(map);

  // Add an event listener on the rectangle.
  //google.maps.event.addListener(rectangle, 'bounds_changed', showNewRect);

  // Define an info window on the map.
  //infoWindow = new google.maps.InfoWindow();
}
// Show the new coordinates for the rectangle in an info window.

/** @this {google.maps.Rectangle} */
function showNewRect(event) {
  var ne = rectangle.getBounds().getNorthEast();
  var sw = rectangle.getBounds().getSouthWest();

  var contentString = '<b>Rectangle moved.</b><br>' +
      'New north-east corner: ' + ne.lat() + ', ' + ne.lng() + '<br>' +
      'New south-west corner: ' + sw.lat() + ', ' + sw.lng();

  // Set the info window's content and position.
  infoWindow.setContent(contentString);
  infoWindow.setPosition(ne);

  infoWindow.open(map);
}

google.maps.event.addDomListener(window, 'load', initialize);