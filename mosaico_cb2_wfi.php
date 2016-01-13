<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html>

<head>
    <meta charset="utf-8">
    
    
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta name="description" content="Mosaico">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
    
    
    
    
    
<!--STYLE TYPE="text/css">
<!--
BODY, OL, UL, LI { font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 7pt; background-color: #EEEEEE;}
form { font-family: Arial, Verdana, Helvetica, sans-serif; font-size: small}
CAPTION { font-family: Arial, Verdana, Helvetica, sans-serif; font-size: small;font-weight: bold; background-color: #BBBBBB; color : #004477 }
TH { font-family: Arial, Verdana, Helvetica, sans-serif; font-size: small;font-weight: bold; background-color: #BBBBBB; color : #004477 }
TD { font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699; font-size: 7pt;font-weight: bold; background-color: #DDDDDD; height: 10px;align : "right";}
H1 { font-size: 175%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699}
H2 { font-size: 150%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699 }
H3 { font-size: 125%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699 }
H4 { font-size: 100%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699 }
H5 { font-size: 75%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699 }
H6 { font-size: 50%; font-family: Arial, Verdana, Helvetica, sans-serif; color : #006699 }
PRE, TT, CODE { font-family: courier, sans-serif; font-size: small; }
select {font-size:x-small;font-family:Arial, Verdana, Helvetica, sans-serif;background-color:#F7F7F7;border:#944100;border-style:solid;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px}
input {font-size:x-small;font-family:Arial, Verdana, Helvetica, sans-serif;background-color:#F7F7F7;border:#944100;border-style:solid;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px}
A:hover { text-decoration: none; color: #FF6666; font-size: 7pt; }
A.menus { color: #FF6666; text-decoration: none; font-size: 7pt; }
A.menus:visited { color: #FF6666; text-decoration: none; font-size: 7pt; }
A.menus:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: 7pt; }
A.menussel { color: #FF6666; text-decoration: none; background: #ffa; font-size: 7pt; }
A.menussel:visited { color: #FF6666; text-decoration: none; background: #ffa; font-size: 7pt; }
A.menussel:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: 7pt; }
A.menusxxs { color: #FF6666; text-decoration: none; font-size: x-small; }
A.menusxxs:visited { color: #FF6666; text-decoration: none; font-size: x-small; }
A.menusxxs:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: x-small; }
.vermelho {color : #FF0000;}
.verde {color : #00FF00;}
.ciano {color : #00FFFF;}
.azul {color : #0000FF;}
.preto {color : #000000;}
.black {font-size : x-small; color : #000000; font-family : Arial; }
-->
</STYLE-->    
    
    
    <style>
		html, body, #map-canvas 
		{
			height: 100%;
			margin: 0px;
			padding: 0px
		}
					
		
    </style>
    
    <!-- Estilos -->
    <link href="/catalogo/css/icons.css" rel="stylesheet">     
    <link href="/catalogo/css/infobubble.css" rel="stylesheet">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=drawing,weather" type="text/javascript"></script>       
    <script src="/catalogo/js/infobubble.js" type="text/javascript"></script>
    <!--script src="/catalogo/js/maplabel.js" type="text/javascript"></script-->
    <!--script src="/catalogo/js/daynightoverlay.js" type="text/javascript"></script-->
    <script src="/catalogo/js/keydragzoom.js" type="text/javascript"></script>
    <!--script src="/catalogo/mosaico.js" type="text/javascript"></script-->
    
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="/catalogo/js/html5shiv.js"></script>
        <![endif]-->
    <!-- Fav and touch icons -->
   
</head>



<!-- 
Layout macro do site
As opções são:
boxed	Toda estrutura do site encaixada em uma espécie de caixa, possuindo largura
wide	Toda estrutura do site utilizando-se de toda a área disponivel, não possuindo margens
-->
<body>


<div id="map-canvas">                    
</div>  

<script src="/catalogo/mosaico_cb2_wfi.js" type="text/javascript"></script>

</body>
</html>

