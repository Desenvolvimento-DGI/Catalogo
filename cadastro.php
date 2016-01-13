<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html lang="en">

<head>
    <meta charset="utf-8">
    
    <title>Divisão de Geração de Imagem :: Catálogo de Imagens - Cadastro</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página principal do site da DGI (Divisão de Geração de Imagem)">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
    <!-- Estilos -->
    <link href="/sitedgi/css/bootstrap.css" rel="stylesheet">
    <link href="/sitedgi/css/style.css" rel="stylesheet">
    <!--link href="/sitedgi/css/camera.css" rel="stylesheet"-->
    <link href="/sitedgi/css/icons.css" rel="stylesheet">
    

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
    <link href="/sitedgi/css/skin-blue.css" rel="stylesheet">
    <link href="/sitedgi/css/bootstrap-responsive.css" rel="stylesheet">
    <!--link href="/sitedgi/css/bootstrap.3.3.min.css" rel="stylesheet"-->    
	<link id="bsdp-css" href="/sitedgi/css/datepicker3.css" rel="stylesheet">   

     
    
    <style>
		html, body 
		{
			margin: 0px;
			padding: 0px
		}
    </style>
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="/sitedgi/js/html5shiv.js"></script>
        <![endif]-->
    <!-- Fav and touch icons -->   
       
    
    <!--script src="/cdsr/cadastro.js"></script-->
    
    
</head>



<!-- 
Layout macro do site
As opções são:
boxed	Toda estrutura do site encaixada em uma espécie de caixa, possuindo largura
wide	Toda estrutura do site utilizando-se de toda a área disponivel, não possuindo margens
-->
<body class="wide" style="background-color:transparent; border-left:thin;border-left-color:#FFF; border-right-width:medium; border-right-color:#C03; border-top:thin;	background-size: cover; position: relative;	padding: 0px 0 0px; height:100%; padding-bottom:0px;">




<!-- 
Inicio da área principal 
class body
-->

<div class="body" style="border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <form name="cadastro" id="cadastro" onSubmit="return false">
    
                         
                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                        <tr>
                            <td align="left" width="160">
                            
                            	                           
                                <label for="SATELITE"><font size="2">Satélite</font></label>
                                <select id="SATELITE" name="SATELITE" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;" onChange="displaySatellite(this.selectedIndex);">
                                	<option value=""> </option>
                                    <option value="NPP">S-NPP</option>
                                    <option value="T1">Terra 1</option> 
                                    <option value="A1">Aqua 1</option>  
                                </select>
                              
                                
                            </td>
                            
                            <td align="left" width="20">&nbsp;
                            </td>                            
                            
                            
                            <td align="left" width="160">
                            
                                <label for="SENSOR"><font size="2">Instrumento</font></label>
                                <select id="SENSOR" name="SENSOR" style="width:160px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value=""> </option>
                                </select>

                            </td>                            
                        
                            <td align="left">&nbsp;
                            </td>                            
                        
                        </tr>   
                    </table>
                           
                                                               
                    <table border="0" cellpadding="0" cellspacing="0" width="370">                        
                        <tr>
                            <td align="left" width="160">
                            
                            	<!--
                                <label for="satelite"><font size="2">Data início</font></label>
                                <input type="date" id="dataini" name="dataini" width="140" style="width:140px;height:22px;padding:0">
                                -->
                                
                   
                               <div class="input-append date" style="margin:0px;margin-bottom:10px; margin-right:20px;text-align:left">
                                   <label for="DATAINI"><font size="2">Data início</font></label>                      
                                   <input type="text" id="DATAINI" name="DATAINI" placeholder="dd/mm/aaaa" style="width:120px;border-radius:3px" ><span class="add-on"><i class="icon-th"></i></span>
                               </div>
               
                                
                                
                                
                            </td>
                        
                            <td align="left" width="160">
                            
                            	<!--
                                <label for="sensor"><font size="2">Data final</font></label>
                                <input type="date" id="datafim" name="datafim" width="140" style="width:140px;height:22px;padding:0">
                                -->
                                
                                
                               <div class="input-append date" style="margin:0px;margin-bottom:10px; margin-right:10px;text-align:left">
                                   <label for="DATAFIM"><font size="2">Data fim</font></label>                      
                                   <input type="text" id="DATAFIM" name="DATAFIM" placeholder="dd/mm/aaaa" style="width:120px;border-radius:3px"><span class="add-on"><i class="icon-th"></i></span>
                               </div>
                                
                                
                            </td>    
                            
                            <td align="left">&nbsp;
                            </td>                            
                                                    
                        
                        </tr>                        
                    </table>
      
                    <br>
                    
                    <span id="coberturanuvens">                    
                    <p><font size="2">Cobertura máxima de nuvens</font></p>
                    <table border="0" cellpadding="0" cellspacing="0" width="400">                        

						<!-- Primeiro e segundo Quadrantes -->
                        <tr>
                            <td align="left" width="80">
                                <font size="2">1º Quadrante:</font></button>
                            </td>
                        
                            <td align="left" width="10">&nbsp;
                            </td>                        
                        
                            <td align="left" width="70">
                               
                                <select id="Q1" name="Q1" style="width:70px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value="" selected> </option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                </select>
                            </td>
                        
                            <td align="left" width="20">&nbsp;
                            </td>
                        
                            <td align="left" width="80">
                                <font size="2">2º Quadrante:</font></button>
                            </td>

                            <td align="left" width="10">&nbsp;
                            </td>
                        
                            <td align="left" width="70">
                                
                                <select id="Q2" name="Q2" style="width:70px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:1px;">
                                	<option value="" selected> </option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                </select>
                            </td>
                            
                            <td align="left">&nbsp;
                            </td>
                            
                        </tr> 
                        
						<!-- Terceiro e quarto Quadrantes -->                        
                        <tr>
                            <td align="left" width="80">
                            	<font size="2">3º Quadrante:</font>
                            </td>
                        
                            <td align="left" width="10">&nbsp;
                            </td>                        
                        
                            <td align="left" width="70">
                               
                                <select id="Q3" name="Q3" style="width:70px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:2px;">
                                	<option value="" selected> </option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                </select>
                            </td>
                        
                            <td align="left" width="20">&nbsp;
                            </td>
                        
                            <td align="left" width="80">
                            	<font size="2">4º Quadrante:</font>
                            </td>

                            <td align="left" width="10">&nbsp;
                            </td>
                        
                            <td align="left" width="70">
                                
                                <select id="Q4" name="Q4" style="width:70px; height:26px; font-family:Tahoma, Geneva, sans-serif;font-size:12px;border-radius:3px;padding:1px;">
                                	<option value="" selected> </option>
                                	<option value="10"> 10%</option>
                                	<option value="20"> 20%</option>
                                	<option value="30"> 30%</option>
                                	<option value="40"> 40%</option>
                                	<option value="50"> 50%</option>
                                	<option value="60"> 60%</option>
                                	<option value="70"> 70%</option>
                                	<option value="80"> 80%</option>
                                	<option value="90"> 90%</option>
                                </select>
                            </td>
                            
                            <td align="left">&nbsp;
                            </td>
                            
                        </tr>                        
                        
                        
                                               
                    </table>
                    
                    <br>
                    </span>
                    
                    
                    <!-- Opção de pesquisar apenas com os parâmetros básicos -->
                         
                    <table border="0" cellpadding="0" cellspacing="0" width="400">

                        <tr>
                        	<td height="4" colspan="4"> </td>                            
                        </tr>                    
                    
                        <tr>
                            <td align="left" width="160">
                        	<input class="btn" type="button"  id="rbasica" name="rbasica" value="Reiniciar Valores" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                            
                            <td width="20">&nbsp;
                            </td>     
                            
                            <td align="left" width="160">
                        	<input class="btn btn-primary" type="button"  id="pbasica" name="pbasica" value="Pesquisar Imagens" style="border-radius:3px;width:160px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:12px;">                        
                            </td>
                                                   
                            <td align="left">&nbsp;
                            </td>     

                        </tr>   
                    </table>
                                                             
    
    </form>
          
</div>

<!-- 
Final da área principal
class body 
-->




<!-- 
Inicio da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="/sitedgi/js/jquery.js"></script>
<script src="/sitedgi/js/bootstrap.js"></script>
<script src="/sitedgi/js/plugins.js"></script>
<script src="/sitedgi/js/custom.js"></script>

<script src="/sitedgi/js/bootstrap-datepicker.js"></script>
<!--script src="/sitedgi/js/bootstrap-datepicker.pl.js" charset="UTF-8"></script-->


<script type="text/javascript">

	$('.input-append.date').datepicker({
	   todayBtn: false,
	   forceParse: true,
	   autoclose: true,
	   todayHighlight: true,
	   format: "dd/mm/yyyy"
	});	

</script>


<script type="text/javascript">

function validarNumero( evento )
{

	var tecla = (window.event)?event.keyCode:evento.which;   
	
		if( (tecla > 47 ) && (tecla < 58) ) return true;
		else 
		{
			if (tecla==8 || tecla==0) return true;
		    else  return false;
		}	
		
}


</script>


<!-- 
Final da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

</body>
</html>

