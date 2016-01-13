var map = null;
var drawingManager;

var weatherLayer=null;
var cloudLayer=null;
var trafficLayer=null;
var transitLayer=null;

//var layerUTM=null;


var loaderId=null;

var ctaLayer=null;
var UtmLayer=null;
var BrasilLayer=null;



var gradeCBERS=null;
var gradeLandSat=null;
var gradeRapidEye=null;


var gradeCBERSCCD=null;
var gradeCBERSHRC=null;
var gradeLandSat5=null;
var gradeResourceSat1LISS3=null;



var marcadorCidadeAtual=null;
var infoWindowCidadeAtual=null;



//var mplabel1=null;
var layerDiaNoite=null;


var layerAmericaSul=null;
var layerAmericaCentral=null;
var layerAmericaNorte=null;




var minhaLocalizacao=null;
var meuMarcador=null;



//var data1=null;

/*
// Região Sul
var BRLayerPR=null;
var BRLayerSC=null;
var BRLayerRS=null;

// Região Sudeste
var BRLayerSP=null;
var BRLayerRJ=null;
var BRLayerES=null;
var BRLayerMG=null;


// Região Centr-oeste
var BRLayerGO=null;
var BRLayerDF=null;
var BRLayerMT=null;
var BRLayerMS=null;


// Região Nordeste
var BRLayerBA=null;
var BRLayerSE=null;
var BRLayerPB=null;
var BRLayerAL=null;
var BRLayerPE=null;
var BRLayerRN=null;
var BRLayerCE=null;
var BRLayerPI=null;
var BRLayerMA=null;


// Região Norte
var BRLayerAM=null;
var BRLayerAC=null;
var BRLayerAP=null;
var BRLayerRR=null;
var BRLayerRO=null;
var BRLayerPA=null;
var BRLayerTO=null;
*/

var contador=0;

var BRLayersRegioes = new Array(5);
for ( contador=0; contador < 5; contador++ ) BRLayersRegioes[contador]=null;


var BRLayersEstados = new Array(27);
for ( contador=0; contador < 27; contador++ ) BRLayersEstados[contador]=null;


var arrayKMLEstados = [ ['AC', 'Acre', 'Municipios_AC', 7, -70.28195260727132, -8.835695214542621, 'Norte'], 
						['AL', 'Alagoas', 'Municipios_AL', 8, -36.54204825273455, -9.724328542890188, 'Nordeste'], 
						['AM', 'Amazonas', 'Municipios_AM', 6, -64.65261999999999, -4.153799999999974, 'Norte'], 
						['AP', 'Amapá', 'Municipios_AP', 7, -51.95549, 1.443789999999998, 'Norte'],
						['BA', 'Bahia', 'Municipios_BA', 7, -41.72199, -12.47408999999998, 'Nordeste'], 
						['CE', 'Cerá', 'Municipios_CE', 7, -39.61762, -5.090849999999978, 'Nordeste'], 
						['DF', 'Distrito Federal', 'Municipios_DF', 8, -47.79685, -15.78066999999998, 'Centro-Oeste'], 
						['ES', 'Espírito Santo', 'Municipios_ES', 8, -40.48015402079354, -19.66147737501836, 'Sudeste'],
						['GO', 'Goiás', 'Municipios_GO', 7, -49.62333999999999, -16.04177999999998, 'Centro-Oeste'], 
						['MA', 'Maranhão', 'Municipios_MA', 7, -45.27859999999998, -5.055879999999993, 'Nordeste'], 
						['MG', 'Minas Gerais', 'Municipios_MG', 7, -44.67296999999999, -18.45573999999998, 'Sudeste'], 
						
						['MS', 'Mato Grosso do Sul', 'Municipios_MS', 7, -54.84502000000001, -20.32670999999998, 'Centro-Oeste'], 
						['MT', 'Mato Grosso', 'Municipios_MT', 6, -55.91235999999999, -12.94851999999999, 'Centro-Oeste'], 
						['PA', 'Pará', 'Municipios_PA', 6, -53.06459000000001, -3.974839999999993, 'Norte'], 
						['PB', 'Paraíba', 'Municipios_PB', 8, -36.49425466148509, -7.297471843983988, 'Nordeste'], 
						['PE', 'Pernambuco', 'Municipios_PE', 7, -37.99566999999999, -8.324630000000004, 'Nordeste'], 
						
						['PI', 'Piauí', 'Municipios_PI', 7, -42.96874, -7.387460000000004, 'Nordeste'], 
						['PR', 'Paraná', 'Municipios_PR', 7, -51.61613999999999, -24.63553999999999, 'Sul'], 
						['RJ', 'Rio de Janeiro', 'Municipios_RJ', 8, -42.61268259336639, -22.43506641497727, 'Sudeste'], 
						['RN', 'Rio Grande do Norte', 'Municipios_RN', 8, -36.67309, -5.83937999999999, 'Nordeste'], 
						['RO', 'Rondônia', 'Municipios_RO', 7, -62.84363999999998, -10.91142999999999, 'Norte'], 
						
						['RR', 'Roraima', 'Municipios_RR', 7, -61.39873999999999, 2.084540000000019, 'Norte'], 
						['RS', 'Rio Grande do Sul', 'Municipios_RS', 7, -53.31970999999999, -29.70562, 'Sul'], 
						['SC', 'Santa Catarina', 'Municipios_SC', 8, -50.48016, -27.24596999999999, 'Sul'], 
						['SE', 'Sergipe', 'Municipios_SE', 8, -37.3956349516407, -10.69673844617164, 'Nordeste'], 
						['SP', 'São Paulo', 'Municipios_SP', 7, -48.72847999999999, -22.26526999999998, 'Sudeste'], 
						
						['TO', 'Tocantins', 'Municipios_TO', 7, -48.32959999999999, -10.14969999999999, 'Norte'] ];





var marcadoresEstadosBrasil=new Array(27);
for ( contador=0; contador < 27; contador++ ) marcadoresEstadosBrasil[contador]=null;

var infoWindowEstadosBrasil=null;

var statusMarcadoresAmericaSul=false;
var statusMarcadoresAmericaCentral=false;
var statusMarcadoresAmericaNorte=false;




var marcadoresAmericaSul= [	[null, 'AR', 'Argentina', -34.777715803604686, -65.0830078125, 'argentina.png' ],
							[null, 'BO', 'Bolívia', -16.390139453117562, -64.6435546875, 'bolivia.png' ],
							[null, 'BR', 'Brasil', -7.826308503776192, -53.0419921875, 'brasil.png' ],
							[null, 'CH', 'Chile', -26.2145910237943, -70.2685546875, 'chile.png' ],
							[null, 'CO', 'Colombia', 3.9957805129630253, -73.01513671875, 'colombia.png' ],
							[null, 'EQ', 'Equador', -1.1165967983063982, -78.486328125, 'equador.png' ],
							[null, 'GU', 'Guiana', 5.397273407690917, -59.0625, 'guiana.png' ],
							[null, 'GF', 'Giana Francesa', 5.419148251825298, -52.7783203125, 'franca.png' ],
							[null, 'PR', 'Paraguai', -22.88873816096074, -58.46923828125, 'paraguai.png' ],
							[null, 'PE', 'Peru', -10.13867012060338, -76.13525390625, 'peru.png' ],
							[null, 'SU', 'Suriname', 4.808777551249867, -55.87646484375, 'suriname.png' ],
							[null, 'UR', 'Uruguai', -32.519026027827515, -56.019287109375, 'uruguai.png' ],
							[null, 'VE', 'Venezuela', 7.645664723491027, -66.07177734375, 'venezuela.png' ] ];




var marcadoresAmericaCentral= [	[null, 'AB', 'Antigua e Barbuda', 17.66495983051931, -61.336669921875 , 'antigua_barbuda.png' ],
								[null, 'BH', 'Bahamas', 25.443274612305746, -78.013916015625, 'bahamas.png' ],
								[null, 'BB', 'Barbados', 13.560561745081422, -59.56787109375, 'barbados.png' ],
								[null, 'BE', 'Belize', 17.14079039331665, -88.648681640625, 'belize.png' ],
								[null, 'CR', 'Costa Rica', 10.692996347925073, -84.111328125, 'costa_rica.png' ],
								[null, 'CU', 'Cuba', 21.94304553343818, -78.958740234375, 'cuba.png' ],
								[null, 'DM', 'Dominica', 15.623036831528264, -61.336669921875, 'dominica.png' ],
								[null, 'ES', 'El Salvador', 13.944729974920167, -88.604736328125, 'salvador.png' ],
								[null, 'GA', 'Guatemala', 15.971891580928968, -90.340576171875, 'guatemala.png' ],
								[null, 'GR', 'Granada', 12.425847783029146, -61.644287109375, 'granada.png' ],
								[null, 'HA', 'Haiti', 19.414792438099557, -72.608642578125, 'haiti.png' ],
								[null, 'HO', 'Honduras', 15.178180945596376, -86.9677734375, 'honduras.png' ],
								[null, 'JA', 'Jamaica', 18.490028573953296, -77.2998046875, 'jamaica.png' ],
								[null, 'NI', 'Nicaragua', 13.250639570043104, -85.0341796875, 'nicaragua.png' ],
								[null, 'PA', 'Panamá', 8.899795184328931, -80.079345703125, 'panama.png' ],
								[null, 'PR', 'Porto Rico', 18.739906226563672, -66.55517578125, 'porto_rico.png' ],
								[null, 'RD', 'Republica Dominicana', 19.611543503814232, -70.20263671875, 'republica_dominicana.png' ],
								[null, 'SL', 'Santa Lúcia', 14.211138758545793, -60.9521484375, 'santa_lucia.png' ],
								[null, 'TT', 'Trinidad e Tobago', 10.951978221389624, -61.19384765625, 'trinidad_tobago.png' ] ];



var marcadoresAmericaNorte= [	[null, 'CA', 'Canadá', 58.321029869251966, -101.6455078125, 'canada.png' ],
								[null, 'EU', 'Estados Unidos', 40.48038142908172, -101.3818359375, 'eua.png' ],
								[null, 'MX', 'México', 24.826624956562167, -102.5244140625, 'mexico.png' ] ];
								



//var kmzLayer;
var infoWindow = new google.maps.InfoWindow();

//var infoWindowImagens = new google.maps.InfoWindow();
var infoWindowImagens;
var exibeInfoWindowImagem = true;  
 
//var	centroMapa = new google.maps.LatLng(-15.5, -47.5);
var	centroMapa = new google.maps.LatLng(-15, -54);

var infoWindowPaises;
var exibeInfoWindowAmericaSul = false;  
var exibeInfoWindowAmericaCentral = false;  
var exibeInfoWindowAmericaNorte = false;  


var arrayFootPrint = null;
var arrayFootPrintProd = null;
var arrayFootPrintImage = null;

var arrayFootPrintStatus = null;



var arrayImgOverlay = null;
var arrayImgOverlayStatus = null;

var zoomMinimo = 2;
var zoomMaximo = 20;

var novoPoligono = null;

var qtdeElementos = 201;
var diretorioQuickLook = 'http://www.dgi.inpe.br/QUICKLOOK/';





/*
var imagemRAPIDEYE = null;
var imagemLANDSAT8_1 = null;
var imagemLANDSAT8_2 = null;
var imagemLANDSAT8_3 = null;
*/


function initialize() 
{

  //centroMapa = new google.maps.LatLng(-15.5, -47.5);

  var mapOptions = 
  {
	center: centroMapa,
	disableDoubleClickZoom: true,	
	zoom: 4,
	//mapTypeId: google.maps.MapTypeId.ROADMAP,	
	mapTypeId: google.maps.MapTypeId.TERRAIN,
	//mapTypeId: google.maps.MapTypeId.SATELLITE,	
    
	overviewMapControl: true,
	overviewMapControlOptions: {
		opened: true,
		position: google.maps.ControlPosition.CENTER },	
	
	panControl: true,
    panControlOptions: {
        position: google.maps.ControlPosition.RIGHT_CENTER },	
	
	zoomControl: true,
    zoomControlOptions: {
        position: google.maps.ControlPosition.RIGHT_CENTER,
		style: google.maps.ZoomControlStyle.SMALL
    },	
	
	scaleControl: true,
	streetViewControl: false
	
  };
  
  
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  
  
   
  
  
  
  map.enableKeyDragZoom({	  
	  boxStyle: {
              border: "1px dashed black",
              backgroundColor: "transparent",
              opacity: 1.0
            }
  });

  
  
  

  
  
  
  //kmzLayer = new google.maps.KmlLayer({ url: 'http://www.dgi.inpe.br/catalogo/kml/cidades_brasil.kml' });
  //kmzLayer.setMap(map);


  /*
  var kmlLayer = new google.maps.KmlLayer({
    url: 'http://www.dgi.inpe.br/catalogo/kml/cidades_brasil.kml',
    suppressInfoWindows: false });


  kmlLayer.setMap(map);
  */
  
  drawingManager = new google.maps.drawing.DrawingManager({
   // drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
	
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        //google.maps.drawing.OverlayType.MARKER,
        //google.maps.drawing.OverlayType.CIRCLE,
        //google.maps.drawing.OverlayType.POLYGON,
        //google.maps.drawing.OverlayType.POLYLINE,
        google.maps.drawing.OverlayType.RECTANGLE
      ]
    },
	
    rectangleOptions: {
      //fillColor: '#ffffff',
      fillOpacity: 0.20,
      strokeWeight: 2,
      clickable: true,
      editable: true,
	  draggable: true,
      zIndex: 1
    }	
	
  });
  





  
   google.maps.event.addListener(drawingManager, 'rectanglecomplete', function (rectangle) {
			
			google.maps.event.addListener(rectangle, 'bounds_changed', mostraMensagemAoRedimensionar );
			google.maps.event.addListener(rectangle, 'click', mostraMensagemAoRedimensionar );
			google.maps.event.addListener(rectangle, 'dblclick', removeRetangulo );
			drawingManager.setDrawingMode(null);
			mostraMensagemAoCriar(rectangle);
   });
  
 
 
 
 
 	// Evento para apresentar uma o Zoom atual no final do menu de opções
	google.maps.event.addListener(map, 'click', function(event) 
	{		
		var mylatlng = event.latLng;	
		console.log( mylatlng );	
		
	
 
	});
 
  

	// Evento para apresentar uma o Zoom atual no final do menu de opções
	google.maps.event.addListener(map, 'zoom_changed', function() 
	{		
		var zoomAtual = map.getZoom();	
		top.alteraTextoZoomAtual("Zoom [" + zoomAtual + "]");	
		
		/*
		if ( zoomAtual >= 8 )
		{			
			//alert("zoomAtual = " + zoomAtual );
			kmzLayer.setMap(map);
		}
		else
		{
			kmzLayer.setMap(null);
		}
		*/
		

		
	
		if ( zoomAtual > 4 ) 
		{
			if ( statusMarcadoresAmericaSul == true) exibeMarcadoresAmericaSul();			
			if ( statusMarcadoresAmericaCentral == true ) exibeMarcadoresAmericaCentral();			
			if ( statusMarcadoresAmericaNorte == true ) exibeMarcadoresAmericaNorte();			
		}
		else
		{
			ocultaMarcadoresAmericaSul();			
			ocultaMarcadoresAmericaCentral();			
			ocultaMarcadoresAmericaNorte();			
		}
		
		
		
		/*
		if ( zoomAtual > 6 ) 
		{
			exibeMarcadoresEstadosBrasil();			
		}
		else
		{
			ocultaMarcadoresEstadosBrasil();	
		}
		*/

		
				
	});
		  
  

  
  //drawingManager.setMap(map);  
  drawingManager.setMap(map);  
  
 
  
  


 /*
  weatherLayer = new google.maps.weather.WeatherLayer({
    temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS,
    windSpeedUnit: google.maps.weather.WindSpeedUnit.KILOMETERS_PER_HOUR,
	suppressInfoWindows: true
  });
  weatherLayer.setMap(map);
  */
	
  
  cloudLayer = new google.maps.weather.CloudLayer();
  cloudLayer.setMap(null);  
  
  
  
  /*
  ctaLayer = new google.maps.KmlLayer({
             url:'http://www.dgi.inpe.br/catalogo/kml/RapideyeGrade.kml',
             preserveViewport: true
             });

  ctaLayer.setMap(map);  
  
  
  ctaLayer = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/testes/CDSR_NEW/Grade_LANDSAT_AS.kml',
             preserveViewport: true
             });

  ctaLayer.setMap(map);    
  
  */
  
  /*
  ctaLayer = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/catalogo/kml/Municipios_SP.kml',
             preserveViewport: true
             });

  ctaLayer.setMap(map);  
  */
  
  
  
  /*
  ctaLayer = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/testes/CDSR_NEW/Estados.kml' //,
             //preserveViewport: true
             });

  ctaLayer.setMap(map);  
  */
  
  /*
  BrasilLayer = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/testes/CDSR_NEW/Estados.kml',
             preserveViewport: true
             });

  BrasilLayer.setMap(null);    
  */
  //map.data.loadGeoJson('http://www.dgi.inpe.br/testes/CDSR_NEW/municipios.json');
  //map.data.loadGeoJson('http://www.dgi.inpe.br/catalogo/kml/Brasil.json');
  
  
  /*
	data1 = new google.maps.Data();
	data1.loadGeoJson('http://www.dgi.inpe.br/catalogo/kml/mg.json' );
	data1.setStyle({ strokeColor: 'black', strokeWeight: 0.6, fillColor: 'gray', clickable: false, fillOpacity: 0.1 });
	
  
 
   data1.setStyle(function(feature) {
    var color = 'gray';
    if (feature.getProperty('isColorful')) {
      color = feature.getProperty('color');
    }
    return ({
      fillColor: '',
      strokeColor: 'black',
      strokeWeight: 0.6,	  
	  clickable: true, 
	  fillOpacity: 0.1	  
    });
  });


  
  
  


 
  data1.addListener('mouseover', function(event) {
    //data1.revertStyle();
    data1.overrideStyle(event.feature, {strokeWeight: 2, fillColor: 'gray', fillOpacity: 0.5 });
  });

  data1.addListener('mouseout', function(event) {
    //data1.revertStyle();
	data1.overrideStyle(event.feature, {strokeWeight: 0.6, fillColor: '', fillOpacity: 0 });
  });
 
  
  data1.setMap(map);
  
*/


  
  
/*  
var ctaLayer = new google.maps.KmlLayer({
    url: 'http://api.flickr.com/services/feeds/geo/?g=322338@N20&lang=en-us&format=feed-georss'
  });
  ctaLayer.setMap(map);  
*/   
  
  
  /*
  trafficLayer = new google.maps.TrafficLayer();
  trafficLayer.setMap(map);  
  
  transitLayer = new google.maps.TransitLayer();
  transitLayer.setMap(map);
  */  
    
  //ctaLayer.setMap(map);  

  inicializaVariaveis();
  

  
  //google.maps.event.addListener(map, 'mousemove', mostraCoordenadas );
  //google.maps.event.addListener(map, 'zoom_changed', mostraZoom );
  
  
  // Imagens de Teste (RAPIDEYE e LANDSAT8)
  		
		
  // Imagem RAPIDEYE
  /*
  var coordenadasRAPIDEYE = new  google.maps.LatLngBounds(
	  new google.maps.LatLng(-20.4029646, -40.3848249),
	  new google.maps.LatLng(-20.1754655, -40.1438340) );


  var localizacaoImgRAPIDEYE = 'http://www.dgi.inpe.br/catalogo/imagemRAPIDEYE.png';				    
			  
  imagemRAPIDEYE = new google.maps.GroundOverlay(localizacaoImgRAPIDEYE, coordenadasRAPIDEYE); 
  imagemRAPIDEYE.setMap(map);
  */
  
  
 

 // Imagem LANDSAT8 (1)
 /*
  var coordenadasLANDSAT8_1 = new  google.maps.LatLngBounds(
	  new google.maps.LatLng(-22.7116166,-47.2918722),
	  new google.maps.LatLng(-20.6298555,-45.0622472) );


  var localizacaoImgLANDSAT8_1 = 'http://www.dgi.inpe.br/catalogo/imagemLANDSAT8_1.png';				    
			  
  imagemLANDSAT8_1 = new google.maps.GroundOverlay(localizacaoImgLANDSAT8_1, coordenadasLANDSAT8_1); 
  imagemLANDSAT8_1.setMap(map);	
  */
  
  /*
  ctaLayer = new google.maps.KmlLayer({
    url:'http://www.dgi.inpe.br/catalogo/kml/UTMWorldZone.kml',
	preserveViewport: true
  });
  
  ctaLayer.setMap(map); 
  */
  
  /*	
  ctaLayer = new google.maps.KmlLayer('http://www.dgi.inpe.br/catalogo/kml/UTMWorldZone.kml');  
  ctaLayer.setMap(map);  
  
  
  */
  
  /*
    ctaLayer = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/testes/CDSR_NEW/Grade_LANDSAT_AS.kml',
             preserveViewport: true
             });

  ctaLayer.setMap(map);    
  
  */




  
 
}




function toggleExibeMarcadoresAmericaSul()
{
	statusMarcadoresAmericaSul=!(statusMarcadoresAmericaSul);
}




function toggleExibeMarcadoresAmericaCentral()
{
	statusMarcadoresAmericaCentral=!(statusMarcadoresAmericaCentral);
}





function toggleExibeMarcadoresAmericaNorte()
{
	statusMarcadoresAmericaNorte=!(statusMarcadoresAmericaNorte);
}




function alteraZoom( parametroZoom )
{
	map.setZoom( parametroZoom );	
}





function toggleMarcadoresAmericaSul()
{
	toggleExibeMarcadoresAmericaSul();
	if (statusMarcadoresAmericaSul) 
	{
		exibeMarcadoresAmericaSul();
	}
	else
	{
		ocultaMarcadoresAmericaSul();		
	}
}








function toggleMarcadoresAmericaCentral()
{
	toggleExibeMarcadoresAmericaCentral();
	if (statusMarcadoresAmericaCentral) 
	{
		exibeMarcadoresAmericaCentral();
	}
	else
	{
		ocultaMarcadoresAmericaCentral();		
	}
}








function toggleMarcadoresAmericaNorte()
{
	toggleExibeMarcadoresAmericaNorte();
	
	if (statusMarcadoresAmericaNorte) 
	{
		exibeMarcadoresAmericaNorte();
	}
	else
	{
		ocultaMarcadoresAmericaNorte();		
	}
}




function atualizaCamadaDiaNoite()
{

	if ( layerDiaNoite != null )	
	{
		if ( layerDiaNoite.getMap() != null )
		{
			layerDiaNoite.setDate(new Date());
			setTimeout('atualizaCamadaDiaNoite', 60000);
		}
	}
	
}




function desenhaMarcadorCidade( parLat, parLon, parMunicipio, parEstado, parPais )
{
	

	var imagemCidadeAtual='http://www.dgi.inpe.br/catalogo/img/cidades_laranja.png';
	var posicaoCidadeAtual=new google.maps.LatLng( parLat, parLon );
	
	if ( marcadorCidadeAtual != null )	
	{
		if ( marcadorCidadeAtual.getMap() != null  )
		{
			marcadorCidadeAtual.setMap(null);
		}
		marcadorCidadeAtual=null;
	}
	
	marcadorCidadeAtual = new google.maps.Marker({ position: posicaoCidadeAtual, icon: imagemCidadeAtual});
		
	infoWindowCidadeAtual=new InfoBubble();
	var coordenadasCentralDaCidade = new google.maps.LatLng(parLat, parLon);
	infoWindowCidadeAtual.setPosition(coordenadasCentralDaCidade);
	
	
	var htmlRemoveMarcadorCidade='<a href="#" onClick="javascript:removeMarcadorCidadeAtual() ; return false">' +
		'<font size="3" color="#369"><i class="icon-remove-sign" style="color:#369"></i></font><font size="2" color="#369">&nbsp;Remover marcador</font></a>';
	
	var htmlInformacoesSobreCidade = document.createElement('DIV');
	htmlInformacoesSobreCidade.innerHTML = '<table class="InfoWindow_TableOuter" celpadding="0" cellspacing="0">' + 
	//'<tr> <td colspan="2"><span class="InfoWindowTitle" >Informações</span> </td> </tr>' +
	
	'<tr> <td colspan="2"><span class="InfoWindowHead" >' + parMunicipio + '</span></td> </tr>' +
	'<tr> <td colspan="2">' + htmlRemoveMarcadorCidade + '</td> </tr>' +

	'<tr> <td width="50"><span class="InfoWindowHead" >Latitude</span></td> <td><span class="InfoWindowText">' + parLat + '</span></td> </tr>' + 
	'<tr> <td width="50"><span class="InfoWindowHead" >Longitude&nbsp;&nbsp;</span></td> <td><span class="InfoWindowText">' + parLon + '</span></td> </tr>' +
	'<tr> <td width="50"><span class="InfoWindowHead" >Estado</span></td> <td><span class="InfoWindowText">' + parEstado + '</span></td> </tr>' +
	'<tr> <td width="50"><span class="InfoWindowHead" >Pais</span></td> <td><span class="InfoWindowText">' + parPais + '</span></td> </tr>' +
	'</table>';
			
		
	infoWindowCidadeAtual.addTab('Cidade', htmlInformacoesSobreCidade);
	
	google.maps.event.addListener(marcadorCidadeAtual,'mouseover',function() 
	{		


	   if (infoWindowCidadeAtual != null ) 
	   {
		   if (!infoWindowCidadeAtual.isOpen()) 
		   {
				infoWindowCidadeAtual.open(map, marcadorCidadeAtual);
		   }
	   }
			
					
		//top.alteraTextoSateliteAtual(parametroImgOverlay[2]);			
	});
			  
	
		
	google.maps.event.addListener(marcadorCidadeAtual,'mouseout',function() 
	{ 			
		//infoWindowImagens.close(map, arrayImgOverlay[indice]);
	   if (infoWindowCidadeAtual != null ) 
	   {
		   if (infoWindowCidadeAtual.isOpen()) 
		   {
				infoWindowCidadeAtual.close();
		   }
	   }
			
		
	});
	
			
	
	
	map.panTo(posicaoCidadeAtual);
	//marcadorCidadeAtual.setAnimation(google.maps.Animation.BOUNCE);
	marcadorCidadeAtual.setMap(map);
	

	/*
	mplabel1 = new MapLabel({
           text: 'Lorena',
           position: posicaoCidadeAtual,
           map: map,
           fontSize: 16,
           align: 'center'
         });	
	*/
	//mplabel1.setMap(map);
	
}




function removeMarcadorCidadeAtual()
{
	
	if ( infoWindowCidadeAtual.isOpen() )
	{
		infoWindowCidadeAtual.close();
		infoWindowCidadeAtual=null;	
	}
	
	marcadorCidadeAtual.setMap(null);
	marcadorCidadeAtual=null;
	
	
}





function kmlLoader(parametroGrade) 
{
	
	if ( (typeof  parametroGrade.getMetadata()) == "object") 
	{
		top.desativaCarregamento();
		clearInterval(loaderId);
		return true;
	} 
	else 
	{	
		return false;
	}
}





function toggleGradeCbers()
{
	
  
  	if ( gradeCBERS == null )
	{
			
	/*	gradeCBERS = new google.maps.KmlLayer({
			      url: 'http://www.dgi.inpe.br/testes/CDSR_NEW/Grade_LANDSAT_AS.kml',
			      preserveViewport: true
			      });
	
		gradeCBERS.setMap(map);  
		//data1.setMap(null);
*/


  top.ativaCarregamento();
  gradeCBERS = new google.maps.KmlLayer({
             url: 'http://www.dgi.inpe.br/catalogo/kml/Grade_CBERS_AS.kml',
			 //url: 'http://www.dgi.inpe.br/catalogo/kml/camada_resorcesat1_liss3.kml',
             preserveViewport: true
             });
  
  gradeCBERS.setMap(map);   
  loaderId = setInterval("kmlLoader(gradeCBERS)", 200);

		 
	}
	else
	{
		if ( gradeCBERS.getMap() == null )
		{
			top.ativaCarregamento();
			
			gradeCBERS.setMap(map);  
			//data1.setMap(null);
			loaderId = setInterval("kmlLoader(gradeCBERS)", 200);
	
		}
		else
		{
			gradeCBERS.setMap(null);  
			//data1.setMap(map);

		}
	}
	//top.trocaTextoMenuCamadas("textogradecbers", "Grade CBERS"); 
	//loaderId = setInterval("kmlLoader()", 10);	
	//top.desativaCarregamento();
		
}











/*


function toggleGradeCbersCCD()
{
	
  
  	if ( gradeCBERSCCD == null )
	{
			
		gradeCBERSCCD = new google.maps.KmlLayer({
			      url: 'http://www.dgi.inpe.br/catalogo/kml/camada_resorcesat1_liss3.kml',
			      preserveViewport: true
			      });
	
		gradeCBERSCCD.setMap(map);  
		//data1.setMap(null);

		 
	}
	else
	{
		if ( gradeCBERSCCD.getMap() == null )
		{
			gradeCBERSCCD.setMap(map);  
			//data1.setMap(null);
	
		}
		else
		{
			gradeCBERSCCD.setMap(null);  
			//data1.setMap(map);

		}
	}
	//top.trocaTextoMenuCamadas("textogradecbers", "Grade CBERS"); 
			
		
}



*/






function toggleGradeLandSat5()
{
	
  
  	if ( gradeLandSat == null )
	{
		top.ativaCarregamento();	
		gradeLandSat = new google.maps.KmlLayer({
			      url: 'http://www.dgi.inpe.br/catalogo/kml/Grade_LANDSAT_AS.kml',
			      preserveViewport: true
			      });
		gradeLandSat.setMap(map);  
		//data1.setMap(null);
		loaderId = setInterval("kmlLoader(gradeLandSat)", 200);
		
		 
	}
	else
	{
		if ( gradeLandSat.getMap() == null )
		{
			top.ativaCarregamento();
			gradeLandSat.setMap(map);  
			//data1.setMap(null);
			loaderId = setInterval("kmlLoader(gradeLandSat)", 200);
	
		}
		else
		{
			gradeLandSat.setMap(null);  
			//data1.setMap(map);

		}
	}
	//top.trocaTextoMenuCamadas("textogradelandsat", "Grade LandSat"); 		
		
}









/*

function toggleGradeRapidEye()
{
	
  
  	if ( gradeRapidEye == null )
	{
			
		gradeRapidEye = new google.maps.KmlLayer({
			      url: 'http://www.dgi.inpe.br/catalogo/kml/grade_rapid_eye.kml',
			      preserveViewport: true
			      });
	
		gradeRapidEye.setMap(map);  
		//data1.setMap(null);

		 
	}
	else
	{
		if ( gradeRapidEye.getMap() == null )
		{
			gradeRapidEye.setMap(map);  
			//data1.setMap(null);
	
		}
		else
		{
			gradeRapidEye.setMap(null);  
			//data1.setMap(map);

		}
	}
	top.trocaTextoMenuCamadas("textograderapideye", "Grade RapidEye"); 		
		
}

*/




function toggleGradeRapidEye()
{

 
  	if ( gradeRapidEye == null )
	{
		top.ativaCarregamento();
		gradeRapidEye = new google.maps.Data();
		
google.maps.event.addListener(gradeRapidEye, "metadata_changed", function() {
   top.ativaCarregamento(); 
   //alert("metadata_changed");
});		
		
		gradeRapidEye.loadGeoJson( 'http://www.dgi.inpe.br/catalogo/kml/Grade_Rapideye_Brasil.geojson' );
		gradeRapidEye.setStyle({ strokeColor: 'red', strokeWeight: 0.7, fillColor: 'white', fillOpacity: 0.1, clickable: false});		
		gradeRapidEye.setMap(map);
		//data1.setMap(null);
		//loaderId = setInterval("kmlLoader(gradeRapidEye)", 200);
		top.desativaCarregamento();
	}
	else
	{
		if ( gradeRapidEye.getMap() == null )
		{
			top.ativaCarregamento();			
			gradeRapidEye.setMap(map);  
			//data1.setMap(null);
			//loaderId = setInterval("kmlLoader(gradeRapidEye)", 200);
			top.desativaCarregamento();
		}
		else
		{
			gradeRapidEye.setMap(null);  
			//data1.setMap(map);
		}
	}
	
	
	
	//top.trocaTextoMenuCamadas("textograderapideye", "Grade Rapideye"); 	


}






function toggleGradeResourceSat1()
{
	
  
  	if ( gradeResourceSat1LISS3 == null )
	{
		top.ativaCarregamento();	
		gradeResourceSat1LISS3 = new google.maps.KmlLayer({
			      //url: 'http://www.dgi.inpe.br/catalogo/kml/Grade_ResourceSat1_LISS3.kml',
			      url: 'http://www.dgi.inpe.br/catalogo/kml/camada_resorcesat1_liss3.kml',
			      preserveViewport: true
			      });
		gradeResourceSat1LISS3.setMap(map);  
		//data1.setMap(null);
		loaderId = setInterval("kmlLoader(gradeResourceSat1LISS3)", 200);
		
		 
	}
	else
	{
		if ( gradeResourceSat1LISS3.getMap() == null )
		{
			top.ativaCarregamento();
			gradeResourceSat1LISS3.setMap(map);  
			//data1.setMap(null);
			loaderId = setInterval("kmlLoader(gradeResourceSat1LISS3)", 200);
	
		}
		else
		{
			gradeResourceSat1LISS3.setMap(null);  
			//data1.setMap(map);

		}
	}
	//top.trocaTextoMenuCamadas("textogradelandsat", "Grade LandSat"); 		
		
}





















function toggleCamadaDiaNoite()
{
	
  
  	if ( layerDiaNoite == null )
	{
			
		layerDiaNoite = new DayNightOverlay({date: new Date()})
	
		layerDiaNoite.setMap(map);  
		//data1.setMap(null);
		setTimeout('atualizaCamadaDiaNoite', 60000);

		 
	}
	else
	{
		if ( layerDiaNoite.getMap() == null )
		{
			layerDiaNoite.setMap(map);  
			//data1.setMap(null);
	
		}
		else
		{
			layerDiaNoite.setMap(null);  
			//data1.setMap(map);

		}
	}
	//top.trocaTextoMenuCamadas("textocamadadianoite", "Camada Dia e Noite"); 		
		
}















function toggleCamadaBrasil()
{
	
  
  	if ( BrasilLayer == null )
	{

		BrasilLayer = new google.maps.Data();
		BrasilLayer.loadGeoJson( 'http://www.dgi.inpe.br/catalogo/kml/Brasil_Estados.geojson' );
		BrasilLayer.setStyle({ strokeColor: 'black', strokeWeight: 0.6, clickable: false});
	
		BrasilLayer.setMap(map);  
		//data1.setMap(null);
		
	}
	else
	{
		if ( BrasilLayer.getMap() == null )
		{
			BrasilLayer.setMap(map);  
			//data1.setMap(null);
		}
		else
		{
			BrasilLayer.setMap(null);  
			//data1.setMap(map);
		}
	}
	
		
	//top.trocaTextoMenuCamadas("textocamadabrasil", "Camada Brasil"); 		
		
}








function toggleCamadaAmericaSul()
{
	
  
  	if ( layerAmericaSul == null )
	{

		layerAmericaSul = new google.maps.Data();
		layerAmericaSul.loadGeoJson( 'http://www.dgi.inpe.br/catalogo/kml/america_do_sul.geojson' );
		layerAmericaSul.setStyle({ strokeColor: 'black', strokeWeight: 0.6, clickable: false, fillColor: 'gray', fillOpacity: 0.1});
	
		layerAmericaSul.setMap(map);  
		//data1.setMap(null);
		
	}
	else
	{
		if ( layerAmericaSul.getMap() == null )
		{
			layerAmericaSul.setMap(map);  
			//data1.setMap(null);
		}
		else
		{
			layerAmericaSul.setMap(null);  
			//data1.setMap(map);
		}
	}
	
	
	//top.trocaTextoMenuCamadas("textocamadabrasil", "Camada Brasil"); 		
		
}








function toggleCamadaAmericaCentral()
{
  	if ( layerAmericaCentral == null )
	{
		layerAmericaCentral = new google.maps.Data();
		layerAmericaCentral.loadGeoJson( 'http://www.dgi.inpe.br/catalogo/kml/america_central.geojson' );
		layerAmericaCentral.setStyle({ strokeColor: 'black', strokeWeight: 0.6, clickable: false, fillColor: 'gray', fillOpacity: 0.1});
		layerAmericaCentral.setMap(map);  
	}
	else
	{
		if ( layerAmericaCentral.getMap() == null )
		{
			layerAmericaCentral.setMap(map);  
		}
		else
		{
			layerAmericaCentral.setMap(null);  
		}
	}
}





function toggleCamadaAmericaNorte()
{
  	if ( layerAmericaNorte == null )
	{
		layerAmericaNorte = new google.maps.Data();
		layerAmericaNorte.loadGeoJson( 'http://www.dgi.inpe.br/catalogo/kml/america_do_norte.geojson' );
		layerAmericaNorte.setStyle({ strokeColor: 'black', strokeWeight: 0.6, clickable: false, fillColor: 'gray', fillOpacity: 0.1});
		layerAmericaNorte.setMap(map);  
	}
	else
	{
		if ( layerAmericaNorte.getMap() == null )
		{
			layerAmericaNorte.setMap(map);  
		}
		else
		{
			layerAmericaNorte.setMap(null);  
		}
	}
}














function toggleCamadaEstados( parametroIndiceEstado )
{
    	
  	// Parametros parametroIndiceEstado e parametroIdTextomenu inválidos
  	if ( parametroIndiceEstado < 0 ||  parametroIndiceEstado  > 27 )
	{		
		return false;		
	}
  
  
  	var geojsonEstado='http://www.dgi.inpe.br/catalogo/kml/' +  arrayKMLEstados[parametroIndiceEstado][2] + '.geojson';
	var centroEstado;
	
  	if ( BRLayersEstados[parametroIndiceEstado] == null )
	{
	 
		BRLayersEstados[parametroIndiceEstado] = new google.maps.Data();
		BRLayersEstados[parametroIndiceEstado].loadGeoJson(geojsonEstado );
		BRLayersEstados[parametroIndiceEstado].setStyle({ strokeColor: 'black', strokeWeight: 0.7, fillColor: '', clickable: true, fillOpacity: 0 });
		
	  
	 
	   BRLayersEstados[parametroIndiceEstado].setStyle(function(feature) {
		var color = 'gray';
		if (feature.getProperty('isColorful')) {
		  color = feature.getProperty('color');
		}
		return ({
		  fillColor: '',
		  strokeColor: 'black',
		  strokeWeight: 0.7,	  
		  clickable: true,
		  fillOpacity: 0  
		});
	  });
	
	  
	 
	  BRLayersEstados[parametroIndiceEstado].addListener('mouseover', function(event) {
		//data1.revertStyle();
		BRLayersEstados[parametroIndiceEstado].overrideStyle(event.feature, {strokeWeight: 2.2, fillColor: 'gray', fillOpacity: 0.5 });
	  });
	
	  BRLayersEstados[parametroIndiceEstado].addListener('mouseout', function(event) {
		//data1.revertStyle();
		BRLayersEstados[parametroIndiceEstado].overrideStyle(event.feature, {strokeWeight: 0.7, fillColor: '', fillOpacity: 0 });
	  });
	 
	  centroEstado = new google.maps.LatLng(arrayKMLEstados[parametroIndiceEstado][5], arrayKMLEstados[parametroIndiceEstado][4]);
	  map.panTo(centroEstado);	
	  //map.setZoom(arrayKMLEstados[parametroIndiceEstado][3]);  
	  BRLayersEstados[parametroIndiceEstado].setMap(map);
	  	  
				 
	}
	else
	{
		if ( BRLayersEstados[parametroIndiceEstado].getMap() == null )
		{
			centroEstado = new google.maps.LatLng(arrayKMLEstados[parametroIndiceEstado][5], arrayKMLEstados[parametroIndiceEstado][4]);
			map.panTo(centroEstado);
			//map.setZoom(arrayKMLEstados[parametroIndiceEstado][3]);
			BRLayersEstados[parametroIndiceEstado].setMap(map);  				
			
			
		}
		else
		{
			BRLayersEstados[parametroIndiceEstado].setMap(null);  
		}
	}		
			
}




/*
function mostraCoordenadas(event) 
{
  var latlng = event.latLng;
  var campoLat = document.getElementById("textolat");
  var campoLng = document.getElementById("textolon");
  
  campoLat.value = "Lat: " + latlng.lat();
  campoLng.value = "Lon: " + latlng.lng();

}


function mostraZoom(event) 
{
  var campoZoomAtual = document.getElementById("zoomatual");
  campoZoomAtual.value = "Zoom: " + map.getZoom();
}

*/









/**
* Nome: inicializaVariaveis
* Função para inciializar as matrizes relacionadas aos footprints, imagens, janelas de informações sobre as imagens
* e seus respsctivos status
*/
function inicializaVariaveis()
{

	var contador;
	
	arrayFootPrint = new Array(qtdeElementos);
	arrayFootPrintProd = new Array(qtdeElementos);
	arrayFootPrintImage = new Array(qtdeElementos);
	
	arrayFootPrintStatus = new Array(qtdeElementos);
	
	arrayImgOverlay = new Array(qtdeElementos);
	arrayImgOverlayStatus  = new Array(qtdeElementos);
	
	
	infoWindowImagens = new Array(qtdeElementos);

	
	for ( contador = 0; contador < qtdeElementos; contador++ )
	{
		arrayFootPrint[contador] =  null;
		arrayFootPrintProd[contador] =  null;
		arrayFootPrintImage[contador] =  null;
		
		arrayImgOverlay[contador] =  null;	
		infoWindowImagens[contador] =  null;
		infoWindowImagens[contador] = new InfoBubble();

		
		arrayFootPrintStatus[contador] = 0
		arrayImgOverlayStatus[contador] = 0;	
	}
	
}




/**
* Nome: liberaImagens 
* Libera os objetos e reinicia algumas variáveis para um melhor uso da memória
*/
function liberaImagens()
{
	
	var contador;
	
	for ( contador = 0; contador < qtdeElementos; contador++ )
	{	
		
		if ( arrayFootPrint[contador] != null ) 
		{
			arrayFootPrint[contador].setMap(null);			
			//arrayFootPrintProd[contador].setMap(null);			
			//arrayFootPrintImage[contador].setMap(null);						
		}
		
		
		if ( arrayImgOverlay[contador] != null ) arrayImgOverlay[contador].setMap(null);	
		
		if ( infoWindowImagens[contador] != null ) 
		{
			if ( infoWindowImagens[contador].isOpen() )
			{
				infoWindowImagens[contador].close();	
			}
		}
		
		

		arrayFootPrint[contador] = null;
		arrayFootPrintProd[contador] = null;
		arrayFootPrintImage[contador] = null;
		
		arrayImgOverlay[contador] = null;
		//infoWindowImagens[contador] = null;

		arrayFootPrintStatus[contador] = 0;	
		arrayImgOverlayStatus[contador] = 0;	
		
		infoWindowImagens[contador] = null;		

	}
	
}







function exibeMarcadoresEstadosBrasil()
{
	
	var contador, posicaoAtual, iconeAtual;
	
	for ( contador=0; contador < 27; contador++ )
	{
		if ( marcadoresEstadosBrasil[contador] == null )
		{
			posicaoAtual=new google.maps.LatLng(arrayKMLEstados[contador][5], arrayKMLEstados[contador][4]);
			iconeAtual='/catalogo/img/estado_' + arrayKMLEstados[contador][0].toLowerCase() + '.png';
			marcadoresEstadosBrasil[contador]=new google.maps.Marker( {
				position: posicaoAtual,
				icon: iconeAtual });
			
		}
		
		
		marcadoresEstadosBrasil[contador].setMap(map);
	}
	
}







function ocultaMarcadoresEstadosBrasil()
{
	
	var contador;
	
	for ( contador=0; contador < 27; contador++ )
	{
		if ( marcadoresEstadosBrasil[contador] != null )
		{
			marcadoresEstadosBrasil[contador].setMap(null);
			
		}
	}
	
}










/*
Marcadores da America do Sul
*/


function exibeMarcadoresAmericaSul()
{
	
	var contador, posicaoAtual, iconeAtual;
	
	for ( contador=0; contador <  13 ; contador++ )
	{
		if ( marcadoresAmericaSul[contador][0] == null )
		{
			posicaoAtual=new google.maps.LatLng(marcadoresAmericaSul[contador][3], marcadoresAmericaSul[contador][4]);
			iconeAtual='/catalogo/img/' + marcadoresAmericaSul[contador][5].toLowerCase();
			marcadoresAmericaSul[contador][0]=new google.maps.Marker( {
				position: posicaoAtual,
				icon: iconeAtual });
			
		}
	
		if ( map.getZoom() > 4 ) marcadoresAmericaSul[contador][0].setMap(map);
	}
	
}




function ocultaMarcadoresAmericaSul()
{
	
	var contador;
	
	for ( contador=0; contador < marcadoresAmericaSul.length; contador++ )
	{
		if ( marcadoresAmericaSul[contador][0] != null )
		{
			marcadoresAmericaSul[contador][0].setMap(null);
			
		}
	}
	
}








function exibeMarcadoresAmericaCentral()
{
	
	var contador, posicaoAtual, iconeAtual;
	
	for ( contador=0; contador < marcadoresAmericaCentral.length ; contador++ )
	{
		if ( marcadoresAmericaCentral[contador][0] == null )
		{
			posicaoAtual=new google.maps.LatLng(marcadoresAmericaCentral[contador][3], marcadoresAmericaCentral[contador][4]);
			iconeAtual='/catalogo/img/' + marcadoresAmericaCentral[contador][5].toLowerCase();
			marcadoresAmericaCentral[contador][0]=new google.maps.Marker( {
				position: posicaoAtual,
				icon: iconeAtual });
			
		}
	
		if ( map.getZoom() > 4 ) marcadoresAmericaCentral[contador][0].setMap(map);
	}
	
}




function ocultaMarcadoresAmericaCentral()
{
	
	var contador;
	
	for ( contador=0; contador < marcadoresAmericaCentral.length; contador++ )
	{
		if ( marcadoresAmericaCentral[contador][0] != null )
		{
			marcadoresAmericaCentral[contador][0].setMap(null);
			
		}
	}
	
}











function exibeMarcadoresAmericaNorte()
{
	
	var contador, posicaoAtual, iconeAtual;
	
	for ( contador=0; contador < marcadoresAmericaNorte.length ; contador++ )
	{
		if ( marcadoresAmericaNorte[contador][0] == null )
		{
			posicaoAtual=new google.maps.LatLng(marcadoresAmericaNorte[contador][3], marcadoresAmericaNorte[contador][4]);
			iconeAtual='/catalogo/img/' + marcadoresAmericaNorte[contador][5].toLowerCase();
			marcadoresAmericaNorte[contador][0]=new google.maps.Marker( {
				position: posicaoAtual,
				icon: iconeAtual });
			
		}
	
		if ( map.getZoom() > 4 ) marcadoresAmericaNorte[contador][0].setMap(map);
	}
	
}





function ocultaMarcadoresAmericaNorte()
{
	
	var contador;
	
	for ( contador=0; contador < marcadoresAmericaNorte.length; contador++ )
	{
		if ( marcadoresAmericaNorte[contador][0] != null )
		{
			marcadoresAmericaNorte[contador][0].setMap(null);
			
		}
	}
	
}
















/**
* Nome: footPrint
* Função para exibir ou ocultar o footprint indiretamente através da
* chamada a função footPrint existente no frame interno fmosaico
*
* parametroFootPrint	Vetor com informações da imagem 
*/
function footPrint( parametroFootPrint )
{
	
	
	
	var indice = parametroFootPrint[0];
	var centroFootPrint = new Array(parametroFootPrint[5], parametroFootPrint[6]);
	
	var idIconStatus='iconfootprint' + indice;	
	var idIconStatusBlink='iconfootprintblink' + indice;	

	var corIconStatus='#BBBBBB';

	var classIconStatus='icon-check-empty';
	var classIconStatusBlink = 'inativo'
	
	//localizarImagem( centroFootPrint );
	
	
	if ( arrayFootPrint[indice] == null )
	{
		
		localizarImagem( centroFootPrint );
		
		// Desenha footprint	
		
		/*
		var coordenadas = [ 
			new google.maps.LatLng(parametroFootPrint[7], parametroFootPrint[8]),
			new google.maps.LatLng(parametroFootPrint[9], parametroFootPrint[10]),
			new google.maps.LatLng(parametroFootPrint[11], parametroFootPrint[12]),
			new google.maps.LatLng(parametroFootPrint[13], parametroFootPrint[14]),
			new google.maps.LatLng(parametroFootPrint[7], parametroFootPrint[8]) ];
		
		
		
		novoPoligono = new google.maps.Polygon({
			paths: coordenadas,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 1,
			fillColor: '',
			fillOpacity: 0.25});	
		*/
		
		var coordenadas = [ 
			new google.maps.LatLng(parametroFootPrint[23], parametroFootPrint[24]),
			new google.maps.LatLng(parametroFootPrint[25], parametroFootPrint[26]),
			new google.maps.LatLng(parametroFootPrint[27], parametroFootPrint[28]),
			new google.maps.LatLng(parametroFootPrint[29], parametroFootPrint[30]),
			new google.maps.LatLng(parametroFootPrint[23], parametroFootPrint[24]) ];
		
		/*
		novoPoligono = new google.maps.Polygon({
			paths: coordenadas,
			strokeColor: '#FF0000',
			strokeOpacity: 1.0,
			strokeWeight: 1,
			fillColor: '#FFFFFF',
			fillOpacity: 0.10});	
		*/
		
		novoPoligono = new google.maps.Polyline({
			path: coordenadas,
			strokeColor: '#FF0000',
			strokeWeight: 1});	
		

			
		arrayFootPrint[indice] = novoPoligono;
		

		/*

		// Footprint Produto
		
		// Desenha footprint	
		var coordenadasProd = [ 
			new google.maps.LatLng(parametroFootPrint[23], parametroFootPrint[24]),
			new google.maps.LatLng(parametroFootPrint[25], parametroFootPrint[26]),
			new google.maps.LatLng(parametroFootPrint[27], parametroFootPrint[28]),
			new google.maps.LatLng(parametroFootPrint[29], parametroFootPrint[30]),
			new google.maps.LatLng(parametroFootPrint[23], parametroFootPrint[24]) ];
		
		
		novoPoligono = new google.maps.Polygon({
			paths: coordenadasProd,
			strokeColor: '#00EE00',
			strokeOpacity: 1,
			strokeWeight: 3,
			fillColor: ''});	
			
		arrayFootPrintProd[indice] = novoPoligono;
		
		
		
		
		// Footprint Image
		
		// Desenha footprint	
		var coordenadaImage = [ 
			new google.maps.LatLng(parametroFootPrint[31], parametroFootPrint[32]),
			new google.maps.LatLng(parametroFootPrint[33], parametroFootPrint[34]),
			new google.maps.LatLng(parametroFootPrint[35], parametroFootPrint[36]),
			new google.maps.LatLng(parametroFootPrint[37], parametroFootPrint[38]),
			new google.maps.LatLng(parametroFootPrint[31], parametroFootPrint[32]) ];
		
		
		novoPoligono = new google.maps.Polygon({
			paths: coordenadaImage,
			strokeColor: '#0011EE',
			strokeOpacity: 1,
			strokeWeight: 2,
			fillColor: ''});	
			
		arrayFootPrintImage[indice] = novoPoligono;
		
		
		*/
		
		
		
		arrayFootPrint[indice].setMap(map);	
		//arrayFootPrintProd[indice].setMap(map);	
		//arrayFootPrintImage[indice].setMap(map);	
		arrayFootPrintStatus[indice] = 1;	
		//corIconStatus='#BBBBBB';	
		classIconStatus='icon-sign-blank';		
		classIconStatusBlink = 'ativo';
								
	}
	else
	{
		
		if ( arrayFootPrintStatus[indice] == 0 ) 
		{
			localizarImagem( centroFootPrint );
			
			// Apenas apresenta o footprint, pois já existe o objeto em memória	
			arrayFootPrint[indice].setMap(map);	
			//arrayFootPrintProd[indice].setMap(map);	
			//arrayFootPrintImage[indice].setMap(map);	
			arrayFootPrintStatus[indice] = 1;	
			//corIconStatus='#BBBBBB';
			classIconStatus='icon-sign-blank';
			classIconStatusBlink = 'ativo';
		}
		else
		{	
			// Oculta o footprint, pois ele já existe e esta exibido no mapa			
			arrayFootPrint[indice].setMap(null);	
			//arrayFootPrintProd[indice].setMap(null);	
			//arrayFootPrintImage[indice].setMap(null);	
			arrayFootPrintStatus[indice] = 0;
			//corIconStatus='black';
			classIconStatus='icon-check-empty';
			classIconStatusBlink = 'inativo';
		}				
	}
	
	
    //alert ("classIconStatusBlink =  " + classIconStatusBlink);

	parent.document.getElementById('fresultado').contentWindow.document.getElementById(idIconStatus).className=classIconStatus;		
	//parent.document.getElementById('fresultado').contentWindow.document.getElementById(idIconStatusBlink).className=classIconStatusBlink;		
	//parent.document.getElementById('fresultado').contentWindow.document.getElementById(idIconStatus).style.color=corIconStatus;
	
	 
	
}









/**
* Nome: imgOverlay
* Função para exibir ou ocultar a imagem indiretamente através da
* chamada a função imgOverlay existente no frame interno fmosaico
*
* parametroImgOverlay	Vetor com informações da imagem 
*/
function imgOverlay( parametroImgOverlay, centralizaImagem, aplicaZoomImagem)
{
	
	
	centralizaImagem   = centralizaImagem   || true;
	aplicaZoomImagem   = aplicaZoomImagem   || true;
	
			
	var indice = parametroImgOverlay[0];
	var sateliteImagem = parametroImgOverlay[2];
	var sensorImagem = parametroImgOverlay[3];
	var dataImagem = parametroImgOverlay[4];
	
	var centroImgOverlay = new Array(parametroImgOverlay[5], parametroImgOverlay[6]);
	var dirAno = dataImagem.substr(0,4);
	var dirMes = dataImagem.substr(5,2);
	var dirAnoMes = dirAno + "_" + dirMes;
		
			
	var idIconStatus='iconimgoverlay' + indice;
	var idImgStatus='iconstatus' + indice;
	var idImgStatusBlink='imgstatusblink' + indice;

	var idImagemBlink='imagemblink' + indice;

	
	
	
	//var corIconStatus='black';
	var corImgStatus='#BBBBBB';	
	var classIconStatus='icon-eye-open';
	var classIconStatusBlink='inativo';
			
		
	var dirSateliteAtual="";
	var sufixoQuickLook="_GRD";
	

	//alert("indice = " + indice + "/n" + "sateliteImagem = " + sateliteImagem + "/n" + "sensorImagem = " + sensorImagem + "/n" );




	switch( parametroImgOverlay[2].toUpperCase() )
	{
		case "A1":
			dirSateliteAtual="AQUA";
			break;
		
		case "T1":
			dirSateliteAtual="TERRA";
			break;
		
		case "NPP":
		case "SNPP":
		case "S-NPP":
			dirSateliteAtual="S-NPP";
			break;
		
		case "UKDMC2":
		case "UK-DMC2":
		case "UK-DMC-2":
			dirSateliteAtual="UK-DMC-2";
			break;
		
		case "RE1":
			dirSateliteAtual="RAPIDEYE1";
			sufixoQuickLook="_MED";
			break;
				
		case "RE2":
			dirSateliteAtual="RAPIDEYE2";
			sufixoQuickLook="_MED";
			break;
		
		case "RE3":
			dirSateliteAtual="RAPIDEYE3";
			sufixoQuickLook="_MED";
			break;
		
		case "RE4":
			dirSateliteAtual="RAPIDEYE4";
			sufixoQuickLook="_MED";
			break;
		
		case "RE5":
			dirSateliteAtual="RAPIDEYE5";
			sufixoQuickLook="_MED";
			break;
		
		case "P6":
			dirSateliteAtual="RESOURCESAT1";
			sufixoQuickLook="_GRD";
			break;
		
		case "RS2":
		case "RE2":
		case "RES2":
			dirSateliteAtual="RESOURCESAT2";
			sufixoQuickLook="_GRD";
			break;
		
		case "CB2":
		case "CBERS2":
		case "CBERS-2":
			dirSateliteAtual="CBERS2";
			sufixoQuickLook="_GRD";
			break;
		
		case "L5":
		case "LANDSAT5":
		case "LANDSAT-5":
			dirSateliteAtual="LANDSAT5";
			sufixoQuickLook="_GRD";
			break;

		case "L8":
		case "LANDSAT8":
		case "LANDSAT-8":
			dirSateliteAtual="LANDSAT8";
			sufixoQuickLook="_GRD";
			break;
		
		case "CB4":
		case "CBERS4":
		case "CBERS-4":
			dirSateliteAtual="CBERS4";
			sufixoQuickLook="_GRD";
			break;
		
	}



	
	var coordenadas = new  google.maps.LatLngBounds(
		new google.maps.LatLng(parametroImgOverlay[13], parametroImgOverlay[14]),
		new google.maps.LatLng(parametroImgOverlay[9], parametroImgOverlay[10]) );

			
			
	//localizarImagem( centroImgOverlay );
	//definirZoomImagem( sateliteImagem );
		
	if ( arrayImgOverlay[indice] == null )
	{
		

		if ( centralizaImagem ) localizarImagem( centroImgOverlay );		
		if ( aplicaZoomImagem ) definirZoomImagem( sateliteImagem, sensorImagem );


		var localizacaoImagem = diretorioQuickLook +  dirSateliteAtual + '/' +
		                        parametroImgOverlay[3].toUpperCase() ;
		
		
		if ( parametroImgOverlay[2].toUpperCase() == "L5" ) localizacaoImagem = localizacaoImagem + "/" + dirAnoMes;
		if ( parametroImgOverlay[2].toUpperCase() == "CB4" ) localizacaoImagem = localizacaoImagem + "/" + dirAno;

		localizacaoImagem = localizacaoImagem + '/QL_' + parametroImgOverlay[1] + 
								sufixoQuickLook + '.png';
		
		//alert("localizacaoImagem = " + localizacaoImagem + "/n" + "dataImagem = "  + dataImagem);
		var nomeCompletoSatelite = obtemNomeSatelite(parametroImgOverlay[2].toUpperCase());						
		
		/*						
		var htmlInformacoesSobreImagem = //'Informações da imagem<br>' +
			'Scene ID : <b>' + parametroImgOverlay[1].toUpperCase() + '</b><br>' +
			'Satélite : ' + nomeCompletoSatelite + ' Sensor : ' + parametroImgOverlay[3].toUpperCase() + '<br><br>' +
			'Clique sobre a imagem para visualizar <br>informações detalhadas';
		*/



		/*
		
		var htmlInformacoesSobreImagem = document.createElement('DIV');
		htmlInformacoesSobreImagem.innerHTML = '<table class="InfoWindow_TableOuter">' + 
		'<tr> <td><span class="InfoWindowTitle" >Imagem de satélite</span> </td></tr>' +
		
		'<tr> <td><span class="InfoWindowHead" >Scene ID: </span> </td></tr>' +
		'<tr><td><span class="InfoWindowText">RE1REIS20140987T61552R52423</span></td></tr>' +
		
		'<tr><td><span class="InfoWindowHead" >Satélite: </span></td></tr>' + 
		'<tr><td><span class="InfoWindowText">Software Engineer</span> </td></tr>' + 
		
		'<tr><td><span class="InfoWindowHead" >Sensor: </span> </td></tr>' +
		'<tr><td><span class="InfoWindowText">Byculla, Mumbai</span> </td></tr>' + 
		'</table>';					
		
		*/			
				
		var arrayParametro="[ '" +  
								parametroImgOverlay[0]  + "', '" + 
								parametroImgOverlay[1] + "', '"  +
								parametroImgOverlay[2] + "', '"  +
								parametroImgOverlay[3] + "', '"  +
								parametroImgOverlay[4] + "', '"  + 
								parametroImgOverlay[5] + "', '"  +
								parametroImgOverlay[6] + "', '"  + 
								
								parametroImgOverlay[7] + "', '"  +
								parametroImgOverlay[8] + "', '"  +
								parametroImgOverlay[9] + "', '"  +
								parametroImgOverlay[10] + "', '"  + 

								parametroImgOverlay[11] + "', '"  +
								parametroImgOverlay[12] + "', '"  + 								
								parametroImgOverlay[13] + "', '"  +
								parametroImgOverlay[14] + "', '"  +

								parametroImgOverlay[15] + "', '"  + 
								parametroImgOverlay[16] + "', '"  +					
								
								parametroImgOverlay[17] + "', '"  + 
								parametroImgOverlay[18] + "', '"  + 								
								parametroImgOverlay[19] + "', '"  + 
								parametroImgOverlay[20] + "', '"  +
								
								parametroImgOverlay[21] + "', '"  +
								parametroImgOverlay[22] + 
								"' ]";
								
		
		var htmlAdicionaAoCarrinho='<a href="#" onClick="javascript:chamaAdicionarAoCarrinho(' +  arrayParametro + ') ; return false">' +
		'<font size="3" color="#369"><i class="icon-shopping-cart" style="color:#369"></i></font><font size="2" color="#369">&nbsp;Adicionar ao carrinho</font></a>';
			
		var htmlOcultaImagemDoMapa='<a href="#" onClick="javascript:ocultaImgOverlayInfoWindow(' +  arrayParametro + ') ; return false">' +
		'<font size="3" color="#369"><i class="icon-eye-close" style="color:#369"></i></font><font size="2" color="#369">&nbsp;Ocultar imagem</font></a>';


		
		var nomeSensor=parametroImgOverlay[3].toUpperCase();
		if ( parametroImgOverlay[3].toUpperCase() == "LIS3") nomeSensor="LISS3";
		if ( parametroImgOverlay[3].toUpperCase() == "AWIF") nomeSensor="AWIFS";
		

					
		var htmlInformacoesSobreImagem = document.createElement('DIV');
		htmlInformacoesSobreImagem.innerHTML = '<table class="InfoWindow_TableOuter" celpadding="0" cellspacing="0">' + 
		//'<tr> <td colspan="2"><span class="InfoWindowTitle" >Informações</span> </td> </tr>' +
		
		//'<tr> <td colspan="2"><span class="InfoWindowHead" >Scene ID: </span></td> </tr>' +
		'<tr> <td colspan="2"><span class="InfoWindowHead"> ' + parametroImgOverlay[1].toUpperCase() + '</span></td> </tr>' +
		
		//'<tr> <td colspan="2"><span class="InfoWindowText">' + parametroImgOverlay[1].toUpperCase() + '</span></td> </tr>' +

		
		'<tr> <td colspan="2">' + htmlAdicionaAoCarrinho + '</td> </tr>' +
		'<tr> <td colspan="2">' + htmlOcultaImagemDoMapa + '</td> </tr>' +
		'<tr> <td colspan="2">&nbsp;</td> </tr>' +
		
		'<tr> <td width="50"><span class="InfoWindowHead" >Satélite: </span></td> <td><span class="InfoWindowText">' + nomeCompletoSatelite + '</span></td> </tr>' + 
		'<tr> <td width="50"><span class="InfoWindowHead" >Sensor: </span></td> <td><span class="InfoWindowText">' + nomeSensor + '</span></td> </tr>' +
		'<tr> <td width="50"><span class="InfoWindowHead" >Data: </span></td> <td><span class="InfoWindowText">' + parametroImgOverlay[4].toUpperCase() + '</span></td> </tr>' +
		'</table>';
		

		
			
		arrayImgOverlay[indice] = new google.maps.GroundOverlay(localizacaoImagem, coordenadas); 
		arrayImgOverlay[indice].setMap(map);					
		arrayImgOverlayStatus[indice] = 1;	
		corIconStatus='green';
		
		//corIconStatus='#BBBBBB';
		corImgStatus='green';
		classIconStatus='icon-eye-close';
		classIconStatusBlink='ativo';



	

		// Evento para apresentar  informações sobre a a imagem quando o mouse
		// passar sobre a mesma
		//google.maps.event.addListener(arrayImgOverlay[indice],'mouseover',function() 
		google.maps.event.addListener(arrayImgOverlay[indice],'click',function() 
		{		


           if (infoWindowImagens[indice] != null ) 
		   {
			   if (infoWindowImagens[indice].isOpen()) 
			   {
					infoWindowImagens[indice].close();
			   }
		   }
		   else
		   {
			   infoWindowImagens[indice] = new InfoBubble();
		   }
				
   		   //infoWindowImagens[indice] = null;
		   
			
		//infoWindowImagens[indice] = null;
			   
		   
		   //infoWindowImagens[indice] = new InfoBubble();
		   				

			var coordenadasCentralDaImagem = new google.maps.LatLng(parametroImgOverlay[5], parametroImgOverlay[6]);
			//infoWindowImagens.setContent(htmlInformacoesSobreImagem);
			infoWindowImagens[indice].setPosition(coordenadasCentralDaImagem);
			infoWindowImagens[indice].addTab('infoimagem', htmlInformacoesSobreImagem);
			
						

			if ( exibeInfoWindowImagem ) 
			{
				infoWindowImagens[indice].open(map);
			}
						
			//top.alteraTextoSateliteAtual(parametroImgOverlay[2]);			
		});
				  




		google.maps.event.addListener(arrayImgOverlay[indice],'onclose',function() 
		{		


           if (infoWindowImagens[indice] != null ) 
		   {
			   if (infoWindowImagens[indice].isOpen()) 
			   {
					infoWindowImagens[indice].close();
			   }
		   }
		   
   		   infoWindowImagens[indice] = null;
		   
		
		});
				  
		


		
		// Evento para ocultar  informações sobre a a imagem quando o mouse
		// sair de cima da mesma	
			
		google.maps.event.addListener(arrayImgOverlay[indice],'mouseout',function() 
		{ 			
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			if ( exibeInfoWindowImagem ) 
			{			
				if ( infoWindowImagens[indice] != null )
				{
					infoWindowImagens[indice].close();
				}
				//infoWindowImagens[indice] = null;
			}
			
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			if ( arrayImgOverlay[indice] != null ) 
			{
				arrayImgOverlay[indice].setOpacity(1);
			}
			
			
			
			
			//top.alteraTextoSateliteAtual("");
		});
		
		  
		// Evento para ocultar a janela com informações sobre a a imagem quando o mouse
		// sair de cima da mesma
				
		/*
		google.maps.event.addListener(arrayImgOverlay[indice],'click',function() 
		{ 			
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			if ( exibeInfoWindowImagem ) 
			{
				if ( infoWindowImagens[indice] != null )
				{
					infoWindowImagens[indice].close();
				}
				infoWindowImagens[indice] = null;
			}
			
			//top.alteraTextoSateliteAtual(parametroImgOverlay[2]);
			//top.detalhesDaImagem( parametroImgOverlay);
			
		});
		
		*/  


		google.maps.event.addListener(arrayImgOverlay[indice],'mouseover',function() 
		{ 			
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			if ( arrayImgOverlay[indice] != null ) 
			{
				arrayImgOverlay[indice].setOpacity(0.55);
			}
			
			
		});



		

		  
		// Evento para ocultar a imagem quando realizar duplo clique sobre a mesma
		google.maps.event.addListener(arrayImgOverlay[indice],'dblclick',function() 
		{ 			
		
			// Executa a função imgOverlay no frame fmosaico
			top.frames['fresultado'].chamaImgOverlay( parametroImgOverlay );	
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			
			if ( infoWindowImagens[indice] != null )
			{
				if ( infoWindowImagens[indice].isOpen() )
				{
					infoWindowImagens[indice].close();
				}
				infoWindowImagens[indice] = null;
			}
			//infoWindowImagens[indice] = null;
		
		});
		
		  
		

		
									
	}
	else
	{
		
		if ( arrayImgOverlayStatus[indice] == 0 ) 
		{
			
			if ( centralizaImagem ) localizarImagem( centroImgOverlay );		
			if ( aplicaZoomImagem ) definirZoomImagem( sateliteImagem, sensorImagem );
			
			
			// Apenas apresenta o footprint, pois já existe o objeto em memória	
			arrayImgOverlay[indice].setMap(map);	
			arrayImgOverlayStatus[indice] = 1;	
			corIconStatus='green';
			
			//corIconStatus='#BBBBBB';
			corImgStatus='green';
			classIconStatus='icon-eye-close';
			classIconStatusBlink='ativo';
			
			
			
			//var coordenadasCentralDaImagem = new google.maps.LatLng(parametroImgOverlay[5], parametroImgOverlay[6]);
			//infoWindowImagens.setPosition(coordenadasCentralDaImagem);
			//infoWindowImagens.setContent(htmlInformacoesSobreImagem);
			//infoWindowImagens.open(map, arrayImgOverlay[indice]);


			//var coordenadasCentralDaImagem = new google.maps.LatLng(parametroImgOverlay[5], parametroImgOverlay[6]);
			//infoWindowImagens.setPosition(coordenadasCentralDaImagem);neImagem

		}
		else
		{	
			// Oculta o footprint, pois ele já existe e esta exibido no mapa			
			arrayImgOverlay[indice].setMap(null);	
			
			if ( infoWindowImagens[indice] != null )
			{
				infoWindowImagens[indice].close();
			}
			infoWindowImagens[indice] = null;
			
			arrayImgOverlayStatus[indice] = 0;
			corIconStatus='black';

			//corIconStatus='black';
			corImgStatus='#BBBBBB';
			classIconStatus='icon-eye-open';
			classIconStatusBlink='inativo';
		}				
	}	
	
	// Liga ou desliga a marca de status de visualização da imagem	
	parent.document.getElementById('fresultado').contentWindow.document.getElementById(idIconStatus).className=classIconStatus;	
	parent.document.getElementById('fresultado').contentWindow.document.getElementById(idImgStatusBlink).className=classIconStatusBlink;	

	//parent.document.getElementById('fresultado').contentWindow.document.getElementById(idImagemBlink).className=classIconStatusBlink;	
	
	
	// Responsável por alterar a cord o indicar de exibição da imagem no Mapa
	// Alterado para informar se a imagem esta em uma das seguintes situações:
	// - Imagem controlada ( Aprovada ou Rejeitada)
	// - Imagem não controlada
	//
	
	//parent.document.getElementById('fresultado').contentWindow.document.getElementById(idImgStatus).style.color=corImgStatus;	
		
				
}




/**
* Nome: localizarImagem
* Função para localizar e centralizar a imagem no mapa indiretamente através da
* chamada a função localizarImagem existente no frame interno fmosaico
*
* parametroCoordenadas	Vetor com as coordenadas central da imagem 
*/
function localizarImagem( parametroCoordenadas )
{
	var centroImagem =  new google.maps.LatLng( parametroCoordenadas[0], parametroCoordenadas[1] )
	//map.setCenter(centroImagem);		
	//map.panTo(centroImagem);		
	
	map.panTo(centroImagem);		
}





/**
* Nome: definirZoomImagem
* Função para definir o Zoom do mapa conforme o satélite referente à imagem
* informada
*
* parametroSatelite	Parametro contendo a identificação do satelite 
*/
function definirZoomImagem( parametroSatelite, parametroSensor )
{
	var zoomAtual;
	
	
	switch ( parametroSatelite.toUpperCase() )
	{
	
		case 'A1':
		case 'AQUA':
		case 'T1':
		case 'TERRA':
		case 'NPP':
		case 'S-NPP':
			zoomAtal=4;	
			break;

		case 'UKDMC2':
		case 'UK-DMC2':
			zoomAtal=7;	
			break;
			
		case 'RAPIDEYE':
		case 'RE1':
		case 'RE2':
		case 'RE3':
		case 'RE4':
		case 'RE5':
			zoomAtal=11;	
			break;

		case 'L5':
		case 'LANDSAT5':
		case 'LANDSAT-5':
			zoomAtal=8;	
			break;

		case 'L8':
		case 'LANDSAT8':
		case 'LANDSAT-8':
			zoomAtal=8;	
			break;


		case 'CB4':
		case 'CBERS4':
		case 'CBERS-4':
			zoomAtal=6;			
			if ( parametroSensor == 'AWIF' ) zoomAtal=6;
			if ( parametroSensor == 'WIF' ) zoomAtal=6;
			if ( parametroSensor == 'MUX' ) zoomAtal=9;
			break;


		case 'P6':
		case 'RESURCESAT1':
		case 'RESURCESAT-1':
			zoomAtal=8;			
			if ( parametroSensor == 'AWIF' ) zoomAtal=7;		 			
			break;

		case 'RES2':
		case 'RESURCESAT2':
		case 'RESURCESAT-2':					
			zoomAtal=8;			
			if ( parametroSensor == 'AWIF' ) zoomAtal=7;		 			
			break;

		case 'CB2':
		case 'CBERS2':
		case 'CBERS-2':					
			zoomAtal=9;			
			if ( parametroSensor == 'WFI' ) zoomAtal=6;		 			
			break;

		default:
			zoomAtal=4;	
	}
	
	map.setZoom(zoomAtal);		
}






/**
* Nome: obtemNomeSatelite
* Função para obter o nome do satélite de acordo com sua identificação
*
* parametroSatelite	Parametro contendo a identificação do satelite 
*/
function obtemNomeSatelite( parametroSatelite )
{
	var nomeSatelite="";


	switch ( parametroSatelite.toUpperCase() )
	{
	
		case 'A1':
		case 'AQUA':
			nomeSatelite="AQUA";
			break;
					
		case 'T1':
		case 'TERRA':
			nomeSatelite="TERRA";
			break;
			
		case 'NPP':
		case 'S-NPP':
			nomeSatelite="S-NPP";
			break;			
			
			
		case 'UKDMC2':
		case 'UK-DMC2':
			nomeSatelite="UK-DMC 2";
			break;			
			

		case 'RAPIDEYE':
			nomeSatelite="RAPIDEYE";
			break;
					
		case 'RE1':
			nomeSatelite="RAPIDEYE 1";
			break;
					
		case 'RE2':
			nomeSatelite="RAPIDEYE 2";
			break;
					
		case 'RE3':
			nomeSatelite="RAPIDEYE 3";
			break;
					
		case 'RE4':
			nomeSatelite="RAPIDEYE 4";
			break;
					
		case 'RE5':
			nomeSatelite="RAPIDEYE 5";
			break;
					

		case 'L5':
		case 'LANDSAT5':
			nomeSatelite="LANDSAT 5";
			break;
					

		case 'L8':
		case 'LANDSAT8':
			nomeSatelite="LANDSAT 8";
			break;
					
		case 'CB4':
		case 'CBERS4':
		case 'CBERS-4':
			nomeSatelite="CBERS 4";
			break;
					
		case 'P6':
		case 'RESOURCESAT1':
		case 'RESOURCESAT-1':
			nomeSatelite="RESOURCESAT 1";
			break;
					

					
		case 'RES2':
		case 'RESOURCESAT2':
		case 'RESOURCESAT-2':
			nomeSatelite="RESOURCESAT 2";
			break;
					
					
		case 'CB2':
		case 'CBERS2':
		case 'CBERS-2':
			nomeSatelite="CBERS 2";
			break;
					

		default:
			nomeSatelite="DESCONHECIDO";
			break;
	}
		
	return nomeSatelite;
}







/**
* Nome: aumentarZoom
* Função que permite aumentar o Zoom do mapa
*/
function aumentarZoom()
{
	var zoomAtual;
	
	zoomAtual = map.getZoom();	
	
	if ( zoomAtual < zoomMaximo )
	{
		zoomAtual++;
		map.setZoom(zoomAtal);		
	}
}




/**
* Nome: diminuirZoom
* Função que permite diminuir o Zoom do mapa
*/
function diminuirZoom()
{
	var zoomAtual;
	
	zoomAtual = map.getZoom();	
	
	if ( zoomAtual > zoomMinimo )
	{
		zoomAtual--;
		map.setZoom(zoomAtal);		
	}
}








/**
* Nome: chamaAdicionarAoCarrinho
* Função responsável por executar a função adicionarAoCarrinho existente na página container 
* do Catálogo
*
* parametroImagem	Parametro contendo um vetor com informações da imagem 
*/
function chamaAdicionarAoCarrinho( parametroImagem )
{
	// Executa a função localizarImagem na página principal
	top.adicionarAoCarrinho( parametroImagem );	
}




/**
* Nome: alternaFerramentaDesenho
* Função responsável porexibir ou ocultar a caixa de ferramentas de desenho na área de mapa do Catálogo
*/
function alternaFerramentaDesenho()
{

	if ( drawingManager.getMap() == null )		
	{
		drawingManager.setMap(map);
	}
	else
	{
		drawingManager.setMap(null);	
	}
	
}




/**
* Nome: mostraMensagemAoRedimensionar
* Apresenta uma janela com informações das coordenadas da figura que esta sendo redimensionada
*/
function mostraMensagemAoRedimensionar(event) 
{
  var ne = this.getBounds().getNorthEast();
  var sw = this.getBounds().getSouthWest();

  var mensagemLocalizacao = '<b>&Agrave;rea selecionada : </b><br>' +
	  'Canto norte-leste : ' + ne.lat().toFixed(8) + ', ' + ne.lng().toFixed(8) + '<br>' +
	  'Canto sul-oeste   : ' + sw.lat().toFixed(8) + ', ' + sw.lng().toFixed(8) + '<br><br>' +
	  'Clique <button id="pesquisaporarea" type="button" class="btn" style="border-radius:3px" onclick="buscarImagensPorRegiao(' + 
	  ne.lat().toFixed(8) + ', ' + sw.lat().toFixed(8) + ', ' + ne.lng().toFixed(8) + ', ' + sw.lng().toFixed(8) + 
	  ')">' + 
	  'aqui</button> para pesquisar na área selecionada<br>' +
	  '<b>Duplo clique na área para removê-la do mapa</b>';	  

  // Set the info window's content and position.
  infoWindow.setContent(mensagemLocalizacao);
  infoWindow.setPosition(ne);

  infoWindow.open(map);
}
	  




/**
* Nome: mostraMensagemAoCriar
* Apresenta uma janela com informações das coordenadas da figura quando a figura é criada
*
* retangulo	Parametro contendo o objeto retângulo criado
*/
function mostraMensagemAoCriar(retangulo) 
{
  var ne = retangulo.getBounds().getNorthEast();
  var sw = retangulo.getBounds().getSouthWest();

  var mensagemLocalizacao = '<b>&Agrave;rea selecionada : </b><br>' +
	  'Canto norte-leste : ' + ne.lat().toFixed(8) + ', ' + ne.lng().toFixed(8) + '<br>' +
	  'Canto sul-oeste   : ' + sw.lat().toFixed(8) + ', ' + sw.lng().toFixed(8) + '<br><br>' +
	  'Clique <button id="pesquisaporarea" type="button" class="btn" style="border-radius:3px" onclick="buscarImagensPorRegiao(' + 
	  ne.lat().toFixed(8) + ', ' + sw.lat().toFixed(8) + ', ' + ne.lng().toFixed(8) + ', ' + sw.lng().toFixed(8) + 
	  ')">' + 
	  'aqui</button> para pesquisar na área selecionada<br>' +
	  '<b>Duplo clique na área para removê-la do mapa</b>';	  

  // Set the info window's content and position.
  infoWindow.setContent(mensagemLocalizacao);
  infoWindow.setPosition(ne);

  infoWindow.open(map);
}
	  




/**
* Nome: removeRetangulo
* Remove o retângulo atual do mapa
*/
function removeRetangulo(event)
{
	this.setMap(null);	
	infoWindow.close(map, this);
}



/**
* Nome: infoWindowImagemToggle
* Alterna o valor da variável exibeInfoWindowImagem entre verdadeiro (true) e falso (false) a cada execução da função
*/
function infoWindowImagemToggle()
{
	exibeInfoWindowImagem=(!exibeInfoWindowImagem);
}



/**
* Nome: buscarImagensPorRegiao
* Permite executar a busca de imagens no Catálogo com base nas coordenadas passadas como parâmetro
*
* latitudeNorte		Parametro contendo a latitude  Norte
* latitudeSul		Parametro contendo a latitude  Sul
* longitudeLeste	Parametro contendo a longitude Leste
* longitudeOeste	Parametro contendo a longitude Oeste
*/
function buscarImagensPorRegiao(latitudeNorte, latitudeSul, longitudeLeste, longitudeOeste)
{
	
	// Atualiza os campos de pesquisa referentes às coordenadas com base nas coordenadas
	// passadas como parâmtro
	var hnorte = top.frames['fpesquisa'].document.getElementById('NORTE');
	var hsul   = top.frames['fpesquisa'].document.getElementById('SUL');
	var hleste = top.frames['fpesquisa'].document.getElementById('LESTE');
	var hoeste = top.frames['fpesquisa'].document.getElementById('OESTE');
	
	hnorte.value = latitudeNorte;
	hsul.value = latitudeSul;
	hleste.value = longitudeLeste;
	hoeste.value = longitudeOeste;
	
	
	// Executa a função de pesquisa com base nas coordenadas informadas
	top.frames['fpesquisa'].obtemDados(null, 'BTNREGIAO');
	
}



/*
function toggleWeatherLayer()
{
	if ( weatherLayer.getMap() == null )
	{
		weatherLayer.setMap(map);			
	}
	else
	{
		weatherLayer.setMap(null);
	}
	top.trocaTextoMenu("textocamadatempo", "Camada Tempo");	
	
}
*/





function toggleCamadaNuvens()
{
	if ( cloudLayer.getMap() == null )
	{
		cloudLayer.setMap(map);	
	}
	else
	{
		cloudLayer.setMap(null);
	}
	//top.trocaTextoMenuCamadas("textocamadanuvens", "Camada Nuvens");

}



function ocultaImgOverlayInfoWindow( parametroImgOverlay )
{

		
			// Executa a função imgOverlay no frame fmosaico
			top.frames['fresultado'].chamaImgOverlay( parametroImgOverlay );	
			//infoWindowImagens.close(map, arrayImgOverlay[indice]);
			
			if ( infoWindowImagens[indice] != null )
			{
				if ( infoWindowImagens[indice].isOpen() )
				{
					infoWindowImagens[indice].close();
				}
				infoWindowImagens[indice] = null;
			}
			//infoWindowImagens[indice] = null;

}





// Inicializa o Mapa
google.maps.event.addDomListener(window, 'load', initialize);	



