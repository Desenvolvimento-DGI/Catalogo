<html>
<head>
<?php
//session_start();
include("css.php");
include("session_mysql.php");

?> 
     <link type="text/css" href="jquery-ui-1.8.7.custom/css/custom-theme/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="jquery-ui-1.8.7.custom/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="jquery-ui-1.8.7.custom/js/jquery-ui-1.8.7.custom.min.js"></script>
    <style type="text/css">
			/*demo page css*/
			#tabs{ font: 13px "Trebuchet MS", sans-serif; margin-bottom:25px; margin-top:10px; margin-left:25px; margin-right:25px;}
			.linkNoticia:hover {font-size: 13px }
    </style>
    <script type="text/javascript">
        $(function(){
 			 $.getScript("noticia_do_BD.php", function(data){
            data = data.split("#######");

            $("<ul><li><a href=\"#tabs-1\">1</a></li><li><a href=\"#tabs-2\">2</a></li><li><a href=\"#tabs-3\">3</a></li><li><a href=\"#tabs-4\">4</a></li></ul>").appendTo("#tabs");

            $("<div id=\"tabs-1\"><a class = \"linkNoticia\" href="+data[1]+">"+data[0]+"</a></div>").appendTo("#tabs");
            $("<div id=\"tabs-2\"><a class = \"linkNoticia\" href="+data[3]+">"+data[2]+"</a></div>").appendTo("#tabs");
            $("<div id=\"tabs-3\"><a class = \"linkNoticia\" target=\"_blank\" href="+data[5]+">"+data[4]+"</a></div>").appendTo("#tabs");
            $("<div id=\"tabs-4\"><a class = \"linkNoticia\" href="+data[7]+">"+data[6]+"</a></div>").appendTo("#tabs");
            $('#tabs').tabs();
                        
        });                
                setInterval('mudarNoticia()',8000);
        });

        noticia = 0;
        function mudarNoticia(){
            $( "#tabs" ).tabs( "select", noticia );
            noticia++;
            if(noticia>4){
                noticia = 0;
            }

        }
    </script>

<style type="text/css">
<!--
.style6 {font-size: 14px}
.style12 {font-size: 16px}
.style14 {
	color: #000066;
	font-size: 14px;
	font-weight: bold;
}
.style15 {color: #990000}
.style16 {color: #990000; font-size: 16px; }
.style17 {font-size: 15px}
-->
</style>
</head>

<body topmargin="0" style="background-color:#FFFFFF" >
<div><center>
<table align="center" cellspacing="10" cellpadding="10">
<tr>
<!--<td> 
<a target="_blank" href="http://www.dgi.inpe.br/pesquisa2007"> 
<img align=center src="../Suporte/images/pesq_cbers.gif"></a>
</td> -->

<td> 
<a target="_blank" href="http://www.cbers.inpe.br/">
<img align=center src="../Suporte/images/logo_cbers.jpg"></a>
</td>
<td>
<a href="http://www.finep.gov.br/"><img src="../Suporte/images/finep-jpeg.jpg" width="140" height="146" border="0"></a>
</td>

</tr>
</table>
</div></center>
<div align="justify">
  <p class="style16">  
<p>
<p>
<?
if(!file_exists("remarkableMsg_PT.php")) fopen("remarkableMsg_PT.php","x+"); 
$strRemarkableMsg = file_get_contents("remarkableMsg_PT.php");
?>
      <br>
      
      <span class="style14"> Not&iacute;cias:</span>
  <div style="line-height:22px;" id="tabs">  </div>
    <p class="style15" style="font-size:15px" >&nbsp;</p>
    <table cellpadding="0" cellspacing="0" width="205" border="0">
    <tr>
      <td style="background:#FFFFFF; padding-left:5px; font-size:12px" width="85">Siga a DGI no </td>
      <td width="120"><a href="http://twitter.com/inpe_dgi" target="_blank"><img src="img/tw.jpg" width="120" height="43" border="0"></a></td>
    </tr>
  </table>
    <br>
    <span class="style6">Prezado Usu&aacute;rio, </span>
    </p>
<p>
    <?=$strRemarkableMsg?>
 <span style="font-size:15"> Bem-vindo &agrave; p&aacute;gina que permite a intera&ccedil;&atilde;o entre voc&ecirc; e o Banco de Imagens da DGI/INPE. 
    Neste Banco de Dados, voce encontrara, presentemente, imagens dos sat&eacute;lites <a href=../Suporte/files/Cameras-LANDSAT123_PT.php style="font-size:15">Landsat-1, Landsat-2, Landsat-3</a>, <a href=../Suporte/files/Cameras-LANDSAT57_PT.php style="font-size:15">Landsat-5, Landsat-7</a>, <a href=../Suporte/files/Cameras-CBERS2_PT.php style="font-size:15"> CBERS-2 , CBERS-2B</a> (Sat&eacute;lite Sino-Brasileiro de Recursos Terrestres), <a href="http://www.dgi.inpe.br/CDSR/ir-p6.html" style="font-size:15">IRS-P6 - Resource-sat1</a>.<br><br>
    
    <font color="#FF0000"><b>Atenção: </b></font>&nbsp; As imagens dos sat&eacute;lites <b>AQUA</b> e <b>TERRA</b> devem ser consultadas no seguinte endereço:&nbsp;<a href="http://www.dgi.inpe.br/catalogo/" target="new"  style="font-size:16"><b>www.dgi.inpe.br/catalogo/</b></a><br>
    O usuário e a respectiva senha de acesso são os mesmos utilizados para acesso neste Catálogo.<br><br>
    
    
    Cada sat&eacute;lite tem seu <a style="font-size:15" href="../Suporte/files/ini_fim_Sats.php"  >periodo de atividade.</a> <br>
    As imagens destes sat&eacute;lites s&atilde;o inteiramente gratuitas (n&atilde;o tarifadas). 
    O meio de envio padr&atilde;o das imagens (gratuitas) &eacute; por transfer&ecirc;ncia de arquivos
    (FTP) via Internet. <br>

    <!-- 
    Chamado: 7482     Data: 01/04/2014
    Inclusao de um texto    
    -->     

    Informo que no cat&aacute;logo oferecemos as imagens ortorretificadas GLS-LANDSAT, para serem usada de base para georeferenciamento. <br> 
 
    O INPE espera que voc&ecirc; fa&ccedil;a o melhor proveito
    poss&iacute;vel dos produtos aqui oferecidos. <br>
    Solicitamos a gentileza de nos enviar, na medida do possivel, os resultados de seus trabalhos com as imagens CBERS, bem como seus coment&aacute;rios e sugest&otilde;es, subsidiando assim, a continuidade de nosso empenho com vistas a uma permanente melhoria do sistema.
  <br><br>
    Aqui voc&ecirc; poder&aacute;:
  <br>
    
  <br>
    1. PESQUISAR IMAGENS do seu interesse, segundo v&aacute;rios <A HREF="help.php" style="font-size:15">modos de busca</a>: por sat&eacute;lite e sensor, por data,
    por munic&iacute;pio, por &oacute;rbita/ponto, por regi&atilde;o ou por meio de navega&ccedil;&atilde;o gr&aacute;fica. A consulta ao cat&aacute;logo &eacute; livre, mas para fazer
    download de imagens completas &eacute; necess&aacute;rio que voc&ecirc; se <A HREF="register.php" style="font-size:15">cadastre</a>.
  <br><br>
    2. CADASTRAR-SE junto ao INPE: esse <A HREF="register.php" style="font-size:15">cadastro</a> &eacute; muito importante, pois permite ao INPE o conhecimento
    dos principais usu&aacute;rios do sistema e das &aacute;reas de aplica&ccedil;&atilde;o do CBERS. Os seus dados cadastrais ficar&atilde;o
    sob a guarda do INPE e n&atilde;o ser&atilde;o repassados a ningu&eacute;m; servir&atilde;o unicamente para fins estat&iacute;sticos e de
    comunica&ccedil;&otilde;es entre o INPE e voc&ecirc;.
  <br><br>
    3. ACESSAR o Cat&aacute;logo e solicitar imagens em resolu&ccedil;&atilde;o plena. As imagens poder&atilde;o ser solicitadas sem custo para download.
  <br>
  <br>
    4. FAZER DOWNLOAD DE ARQUIVOS E DOCUMENTOS: arquivos de dados e documentos (e.g. manual de opera&ccedil;&atilde;o do Cat&aacute;logo) de interesse
    do usu&aacute;rio, encontram-se dispon&iacute;veis em <A href = "documentos_PT.php" style="font-size:15">Arquivos e Documentos</a>.  
  <br>
  <br>
    5. FAZER DOWNLOAD DO UTILIT&Aacute;RIO MARLIN: ferramenta destinada &agrave; visualiza&ccedil;&atilde;o e avalia&ccedil;&atilde;o de imagens digitais, 
    dispon&iacute;vel
  <? 

if (isset($_SESSION['userId']))
{
?> em &nbsp;<A href = "documentos_PT.php" style="font-size:15">Arquivos e Documentos</a>. <?
}
else
{
?> ap&oacute;s <a href="login.php" style="font-size:15">login.</a> <?
}
?>
    
  <br>
  <br>
    
    NOTAS:
  <br><br>
    
    . As imagens poder&atilde;o ser obtidas LIVRES DE CUSTO pelo usu&aacute;rio, quando a aquisi&ccedil;&atilde;o for atrav&eacute;s de download.
    Para outros produtos, o custo segue a tabela oficial do INPE.
  <br><br>
    . Os produtos CBERS estão em contínuo processo de aperfeiçoamento. 
    Neste sentido, sempre que notar problemas com os produtos não deixe de nos notificar, a fim de analisarmos o problema e buscarmos melhorias. 
    Preferencialmente, anexe imagens e descrições do problema observado.
  <br>
  </p>
  
 
  <p style="font-size:15">
    Para notícias, imagens de demonstração, fotografias, informações técnicas sobre o Programa CBERS, vídeos, etc., consulte
    <A HREF="http://www.cbers.inpe.br" style="font-size:15"> http://www.cbers.inpe.br</A>
  <br></span><!-- 
Participe da "1ª Pesquisa sobre o Perfil dos Usuários das Imagens CBERS"
acessando <A HREF="http://www.dgi.inpe.br/pesquisa2007"> http://www.dgi.inpe.br/pesquisa2007</A>.
<br><br> -->
    
  <!-- Veja o novo Video Educacional "Satelites e seus Sub-Sistemas", tendo o CBERS
como exemplo (<A HREF="http://www.cbers.inpe.br/pt/index_pt.htm"> http://www.cbers.inpe.br/pt/index_pt.htm</A> ).
<br><br> -->
  <p class="style6">  Para d&uacute;vidas sobre o uso de imagens ou outras solicita&ccedil;&otilde;es, entre em contato com
  <A HREF="mailto:atus@dgi.inpe.br">atus@dgi.inpe.br</a>
  <br><br><br>
  <h2><center>Para coment&aacute;rios ou sugest&otilde;es, entre em <A HREF="mailto:atus@dgi.inpe.br" style="font-size:15">contato</a>
    conosco.</center> </h2>
  </p>
  
<!-- 
Chamado: 7205
Data atendimento: 29/01/2014
Data solução: 29/01/2014
-->
<hr>
<br>
<span class="style12"><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Licen&ccedil;a Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a><br />
<font style="font-size:15">Imagem de sensoriamento remoto por <a xmlns:cc="http://creativecommons.org/ns#" style="font-size:15" href="http://www.inpe.br" property="cc:attributionName" rel="cc:attributionURL">INPE - Instituto Nacional de Pesquisas Espaciais</a> esta licenciado sobre a <a style="font-size:15" rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Atribui&ccedil;&atilde;o-Compartilhamento pela mesma licen&ccedil;a 3.0 Unported License</a>.</font></span> 
<br>
<br>

</div>
<!-- <IMG SRC="/cgi-bin/Count.cgi?ft=1&amp;sh=F&amp;df=cata-pt.dat"> -->
</body>
</html>
