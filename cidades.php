<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Divisão de Geração de Imagem :: Catálogo de Imagens :: Municípios</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página principal do site da DGI (Divisão de Geração de Imagem)">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
    <!-- Estilos -->
    <link href="/catalogo/css/bootstrap.css" rel="stylesheet">
    <link href="/catalogo/css/style.css" rel="stylesheet">
    <!--link href="/catalogo/css/camera.css" rel="stylesheet"-->
    <link href="/catalogo/css/icons.css" rel="stylesheet">
    

    <!-- 
    O arquivo abaixo define o configuração de cores a serem utilizadas
    As opções disponíveis são:
    
    skin-blue.css				skin-green.css			skin-red.css
    skin-bluedark.css			skin-green2.css			skin-red2.css
    skin-bluedark2.css			skin-grey.css			skin-redbrown.css
    skin-bluelight.css			skin-khaki.css			skin-teal.css
    skin-bluelight2.css   		skin-lilac.css			skin-teal2.css
    skin-brown.css				skin-orange.css			skin-yellow.css
    skin-brown2.css				skin-pink.css			
    -->
    <link href="/catalogo/css/skin-blue.css" rel="stylesheet">
    <link href="/catalogo/css/bootstrap-responsive.css" rel="stylesheet">
    <!--link href="/catalogo/css/bootstrap.3.3.min.css" rel="stylesheet"-->    
	<link id="bsdp-css" href="/catalogo/css/datepicker3.css" rel="stylesheet">   

     
    
    <style>
		html, body 
		{
			margin: 0px;
			padding: 0px
		}
    </style>
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="/catalogo/js/html5shiv.js"></script>
        <![endif]-->
    <!-- Fav and touch icons -->   
    
    
    <script src="/catalogo/cidades.js"></script>
    
</head>



<!-- 
Layout macro do site
As opções são:
boxed	Toda estrutura do site encaixada em uma espécie de caixa, possuindo largura
wide	Toda estrutura do site utilizando-se de toda a área disponivel, não possuindo margens
-->
<body class="wide" style="background-color:#FFFFFF;border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0 0px; height:100%; padding-bottom:0px;">




<!-- 
Inicio da área principal 
class body
-->

<div class="body" style="border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px">



	<table border="0" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    	<tr>
            <td align="justify">
                <span id="registros" name="registros">                
                </span>
            </td>
        </tr>
	</table>   
    
    <!--   
    Local onde sera apresentado 
    -->
    
    
</div>

<!-- 
Final da área principal
class body 
-->




<!-- 
Inicio da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

<!-- 
Final da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>


<script>

$(document).ready(function(){
	
	
		$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur", function(e)
		{
		  top.atualizaIdleTime();
		});
				
});


</script>



</body>
</html>

