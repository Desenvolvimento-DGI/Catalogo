<?php
// Produto
$titFormProd = "Ordem de Produ&ccedil;&atilde;o"; 
$strId = 'Id';
$strShift = 'Deslocamento';
$strCorLevel = 'N&iacute;vel de Corre&ccedil;&atilde;o Padr&atilde;o';
$strOrientation = 'Orienta�o';
$strProj = 'Proje&ccedil;&atilde;o Padr&atilde;o';
$strLongO = 'Longitude Origem';
$strLatO  = 'Latitude  Origem';
$strStdLat1 = 'Standard Latitude 1';
$strStdLat2 = 'Standard Latitude 2';
$strDatum = 'Datum Padr&atilde;o';
$strRes = 'Reamostragem Padr&atilde;o';
$strBands = 'Bandas';
$strFormat = 'Formato';
$strInter =  'Media Interleaving';
$strReset = "Limpar";
$strCancel = "Cancelar";
$strAdd = 'Comprar';
$strMedia = 'Media';
$strRest = 'Restaura&ccedil;&atilde;o'; 
$strYes = 'Sim';
$strNo = 'Nao'; 
$strMosaic = 'Mosaico';
$strMosaic1 = 'Mosaico das Passagens do Sat&eacute;lite ';
$strMosaic2 = 'Mosaico Brasil';
$strNopassages = 'N&atilde;o h&aacute; passagens em ';
$strEndpassage = 'FIM DA PASSAGEM ';

//Matrizes

$matOrientation = array(1=> "Norte Mapa", "Sat�ite",  "Norte Verdadeiro");
$matProj = array(1=> "UTM", "Lambert", "Polyconic", "LCC");
$matDatum = array(1=>"SAD69", "WGS84");

$matCorLevelL1 = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;fel 2");
$matCorLevelL2 = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;vel 2");
$matCorLevelL3 = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;vel 2");
$matCorLevelL5 = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;vel 2");
$matCorLevelL7 = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;vel 2");
$matCorLevelCB = array(3, "N&iacute;vel 0", "N&iacute;vel 1", "N&iacute;vel 2");
$matCorLevelT1 = array(1, "N&iacute;vel 2");
$matCorLevelA1 = array(1, "N&iacute;vel 2");

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

$matMedia = array("FTP","CD"); 

$strMosaicMsg = " Prezado Usu&aacute;rio,</br></br>
Esta ferramenta (Mosaico da Passagem) cont&eacute;m todas as cenas que foram gravadas durante a &oacute;rbita (path) indicada.</br> O mosaico pode conter cenas com baixa qualidade (e.g. ru&iacute;dos do sistema sensor) que tenham sido recusadas pelo controle de qualidade da DGI/INPE (<em style=\"color:#FF0000\">Row number</em> em vermelho). <br/>
O usu&aacute;rio poder&aacute; solicitar e fazer download de qualquer dessas imagens, bastando clicar sobre a cena desejada e coloc&aacute;-la no carrinho, estando, entretanto, ciente de que poder&aacute; ou N&Atilde;O receber imagens com os problemas citados acima. </br>
O objetivo de colocar todas as cenas &agrave; disposi&ccedil;&atilde;o do usu&aacute;rio no Mosaico da Passagem, &eacute; permitir amplo e irrestrito acesso ao banco de dados de imagens, mesmo &agrave;quelas imagens que t&ecirc;m problemas. </br>
 A partir das outras ferramentas de busca (quadro ao lado esquerdo), o usu&aacute;rio s&oacute; tem acesso &agrave;s cenas que passaram no controle de qualidade. </br></br> </br></br>";

$strPassages1 = "Passagens do sat&eacute;lite $satellite (sensor $sensor) pela &oacute;rbita $path.";
$strPassages2 = "Mosaico desta passagem";
$strPassages3 = " N&atilde;o h&aacute; passagens do sat&eacute;lite $satellite pela &oacute;rbita $path ";

?>