<?php


/*
Função: formataDataAnoMesDia
Convete a data passada como paâmetro de dia/mês/ano para ano-mês-dia

parData		Data a ser convertida
*/
function formataDataAnoMesDia( $parData )
{
	$ano = substr($parData, 6, 4);  // Obtém o ano 
	$mes = substr($parData, 3, 2);  // Obtém o mês 
	$dia = substr($parData, 0, 2);  // Obtém o dia 
	
	// Formata a data a se retornada
	$dataFormatada = "$ano-$mes-$dia";
	return $dataFormatada;
}





/*
Função: apresentaMaisDetalhesImagem
Gera o código necessário para apresentar as informações básicas relacionadas à imagem

parametroImagem		Parametro contendo as informações relacionadas a imagm
cor_registro		Parametro contendo o valor da cor padrão do registro
cor_realce			Parametro contendo o valor da cor de realce do registro (Mouse estiver sobre o registro)
*/
function apresentaMaisDetalhesImagemCarrinho( $parametroImagem, $cor_registro, $cor_realce )
{


	$indice = $parametroImagem[0];

	$CT_Lat=$parametroImagem[5];
	$CT_Lon=$parametroImagem[6];
		
	$TL_Lat=$parametroImagem[7];
	$TL_Lon=$parametroImagem[8];
	
	$TR_Lat=$parametroImagem[9];
	$TR_Lon=$parametroImagem[10];

	$BR_Lat=$parametroImagem[11];
	$BR_Lon=$parametroImagem[12];

	$BL_Lat=$parametroImagem[13];
	$BL_Lon=$parametroImagem[14];
	
	
	$orbita=$parametroImagem[15];
	$ponto=$parametroImagem[16];

	$q1=$parametroImagem[17];
	$q2=$parametroImagem[18];
	$q3=$parametroImagem[19];
	$q4=$parametroImagem[20];
	
	$regiao=$parametroImagem[21];
	$fuso=$parametroImagem[22];




echo '<table id="linhamaisinfo' . $indice . '" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="' . $cor_registro . '" style="display:none">';

echo '<tr valign="baseline">';
echo '	<td valign="baseline" height="8">';
echo '		<hr>';
echo '	</td>';
echo '</tr>';



echo '<tr height="175" valign="top">';
echo '<td valign="top">';

echo '	<font face="Arial, Helvetica, sans-serif" size="2">';
echo '	<ul id="myTab' . $indice . '" class="nav nav-tabs">';
echo '		<li class="active"><a href="#nuvens' . $indice . '" data-toggle="tab">Nuvens</a></li>';
//echo '		<li class=""><a href="#orbita' . $indice . '" data-toggle="tab">Orbita</a></li>';
echo '		<li class=""><a href="#centro' . $indice . '" data-toggle="tab">Centro</a></li>';
echo '		<li class=""><a href="#superior' . $indice . '" data-toggle="tab">Superior</a></li>';
echo '		<li class=""><a href="#inferior' . $indice . '" data-toggle="tab">Inferior</a></li>';
echo '		<li class=""><a href="#mais' . $indice . '" data-toggle="tab">Mais</a></li>';
echo '	</ul>';
	
echo '	<div id="myTabContent' . $indice . '" class="tab-content">';
	
// Aba/página referente à cobertura de nuvens			
//		
echo '		<!-- Página relativa às informações de cobertura de nuvens -->';
echo '		<div class="tab-pane fade active in" id="nuvens' . $indice . '">';
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="100%">';
			
echo '			<tr height="32">';
echo '				<td width="10">&nbsp;';
echo '				</td>';
echo '				<td colspan="8" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Cobertura máxima de nuvens</font>';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="100" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Quadrante 1</font>';
echo '				</td>';
echo '				<td width="50" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $q1 . '</font>';
echo '				</td>';
echo '				<td width="20">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2"><b>%</b></font>';
echo '				</td>';
	
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
				
echo '				<td width="100" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Quadrante 2</font>';
echo '				</td>';
echo '				<td width="50" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $q2 . '</font>';
echo '				</td>';
echo '				<td width="20">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2"><b>%</b></font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
			
echo '			<tr height="12">';
echo '				<td colspan="9"></td>';
echo '			</tr>';
			
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
			
echo '				<td width="100" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Quadrante 3</font>';
echo '				</td>';
echo '				<td width="50" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $q3 . '</font>';
echo '				</td>';
echo '				<td width="20">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2"><b>%</b></font>';
echo '				</td>';
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
				
echo '				<td width="100" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Quadrante 4</font>';
echo '				</td>';
echo '				<td width="50" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $q4 . '</font>';
echo '				</td>';
echo '				<td width="20">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2"><b>%</b></font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';

echo '			</tr>';
					
echo '			</table>';
			
echo '		</div>';
	
	
/*	
echo '		<!-- Página relativa às informações de órbita e ponto (Path, Row) -->';
echo '		<div class="tab-pane fade" id="orbita' . $indice . '">';
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="360">';
			
echo '			<tr height="32">';
echo '				<td width="10">&nbsp;';
					
echo '				</td>';
echo '				<td colspan="7" valign="top">';
echo '					Informação da órbita e ponto ';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Órbita';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF"  align="right">';
echo '					' . $orbita;
echo '				</td>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Ponto';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF" align="right">';
echo '					' . $ponto;
echo '				</td>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
							
echo '			</tr>';
							
echo '			</table>';
			
echo '		</div>';
	
*/	
	
	
	
// Aba/página referente à coordenada central			
//
echo '		<!-- Página relativa às informações de coordenadas centrais  -->';
echo '		<div class="tab-pane fade" id="centro' . $indice . '">';
			
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="100%">';
			
echo '			<tr height="32">';
echo '				<td width="10">&nbsp;';					
echo '				</td>';
echo '				<td colspan="6" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Coordenada central da imagem ';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Latitude</font>';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $CT_Lat . '</font>';
echo '				</td>';
		
echo '				<td width="30">&nbsp;';					
echo '				</td>';

echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Longitude</font>';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $CT_Lon . '</font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
							
echo '			</table>';
			
echo '		</div>';
	
	
	
	
	
// Aba/página referente à coordenada superior			
//
echo '		<!-- Página contendo a coordenada central -->';
echo '		<div class="tab-pane fade" id="superior' . $indice . '">';
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="100%">';
			
echo '			<tr height="22" style="height:22px;font-size:16px;font-family:Arial, Helvetica, sans-serif">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Superior Esquerdo</font>';
echo '				</td>';

echo '				<td width="30">&nbsp;';					
echo '				</td>';

echo '				<td colspan="2" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Superior Direito</font>';
echo '				</td>';
echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';				
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Latitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $TL_Lat . '</font>';
echo '				</td>';
	
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Latitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $TR_Lat . '</font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';							
echo '			</tr>';
			
			
echo '			<tr height="4">';
echo '				<td colspan="7"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Longitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $TL_Lon . '</font>';
echo '				</td>';
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Longitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $TR_Lon . '</font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
				  
echo '			</table>';
			
echo '		</div>';
	
		
		
// Aba/página referente à coordenada inferior			
//
echo '		<!-- Página contendo as coordenadas da imagem -->';
echo '		<div class="tab-pane fade" id="inferior' . $indice . '">';
					 
echo '			<table border="0" cellpadding="4" cellspacing="4" width="100%">';
			
		
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Inferior Esquerdo</font>';
echo '				</td>';
echo '				<td width="30">&nbsp;';					
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Inferior Direito</font>';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
					
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Latitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $BL_Lat . '</font>';
echo '				</td>';
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Latitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $BR_Lat . '</font>';
echo '				</td>';
							
echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="4">';
echo '			<td colspan="7"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Longitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $BL_Lon . '</font>';
echo '				</td>';
		
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Longitude</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $BR_Lon . '</font>';
echo '				</td>';
							
echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
echo '			</table>';
	
echo '		</div>';
		




// Aba/página referente àmais informações			
//
echo '		<!-- Página contendo mais informações -->';
echo '		<div class="tab-pane fade" id="mais' . $indice . '">';
					 
echo '			<table border="0" cellpadding="4" cellspacing="4" width="100%">';
			
		
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Mais informações ...</font>';
echo '				</td>';
echo '				<td width="30">&nbsp;';					
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					&nbsp;';
echo '				</td>';

echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
					
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Órbita</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $orbita . '</font>';
echo '				</td>';
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Região</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $regiao . '</font>';
echo '				</td>';
							
echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="4">';
echo '			<td colspan="7"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Ponto</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $ponto . '</font>';
echo '				</td>';
	
echo '				<td width="30">&nbsp;';					
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">Fuso</font>';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					<font face="Arial, Helvetica, sans-serif" size="2">' . $fuso . '</font>';
echo '				</td>';
							
echo '				<td>&nbsp;';					
echo '				</td>';
echo '			</tr>';
echo '			</table>';
	
echo '		</div>';
		
		
echo '	</div>';	
echo '	</font>';
	
echo '</td>';
echo '</tr>';


//echo '<tr bgcolor="#FFFFFF">';
//echo '<td bgcolor="#FFFFFF" style="height:6px">';
//echo '';
//echo '</td>';
//echo '</tr>';

echo '</table>';
//echo '<br>';
	
}


?>


 