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
function apresentaMaisDetalhesImagem( $parametroImagem, $cor_registro, $cor_realce )
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




echo '<table id="linhamaisinfo' . $indice . '" border="0" cellpadding="0" cellspacing="0" width="390" bgcolor="' . $cor_registro . '" style="display:none">';

echo '<tr valign="baseline">';
echo '	<td valign="baseline">';
echo '		&nbsp;';
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
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
echo '			<tr height="32">';
echo '				<td width="10">&nbsp;';
echo '				</td>';
echo '				<td colspan="7" valign="top">';
echo '					Cobertura máxima de nuvens';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Quadrante 1';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF"  align="right">';
echo '					' . $q1;
echo '				</td>';
echo '				<td width="10">';
echo '					<b>%</b>';
echo '				</td>';
	
	
echo '				<td>&nbsp;';
					
echo '				</td>';
				
				
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Quadrante 2';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF" align="right">';
echo '					' . $q2;
echo '				</td>';
echo '				<td width="10">';
echo '					<b>%</b>';
echo '				</td>';
							
echo '			</tr>';
			
			
echo '			<tr height="12">';
echo '				<td colspan="8"></td>';
echo '			</tr>';
			
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Quadrante 3';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF"  align="right">';
echo '					' . $q3;
echo '				</td>';
echo '				<td width="10">';
echo '					<b>%</b>';
echo '				</td>';
	
echo '				<td>&nbsp;';
echo '				</td>';
				
echo '				<td width="80" bgcolor="#DDDDDD">';
echo '					Quadrante 4';
echo '				</td>';
echo '				<td width="30" bgcolor="#FFFFFF" align="right">';
echo '					' . $q4;
echo '				</td>';
echo '				<td width="10">';
echo '					<b>%</b>';
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
			
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
echo '			<tr height="32">';
echo '				<td width="10">&nbsp;';					
echo '				</td>';
echo '				<td colspan="5" valign="top">';
echo '					Coordenada central da imagem ';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr>';
echo '				<td width="10">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Latitude';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF"  align="right">';
echo '					' . $CT_Lat;
echo '				</td>';
		
echo '				<td>&nbsp;';
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Longitude';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF" align="right">';
echo '					' . $CT_Lon;
echo '				</td>';
echo '			</tr>';
							
echo '			</table>';
			
echo '		</div>';
	
	
	
	
	
// Aba/página referente à coordenada superior			
//
echo '		<!-- Página contendo a coordenada central -->';
echo '		<div class="tab-pane fade" id="superior' . $indice . '">';
			
echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					Superior Esquerdo';
echo '				</td>';
echo '				<td>&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					Superior Direito';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';				
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Latitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $TL_Lat;
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Latitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					' . $TR_Lat;
echo '				</td>';
							
echo '			</tr>';
			
			
echo '			<tr height="4">';
echo '			<td colspan="6"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Longitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $TL_Lon;
echo '				</td>';
	
echo '				<td>&nbsp;';
echo '				</td>';
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Longitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					' . $TR_Lon;
echo '				</td>';
							
echo '			</tr>';
				  
echo '			</table>';
			
echo '		</div>';
	
		
		
// Aba/página referente à coordenada inferior			
//
echo '		<!-- Página contendo as coordenadas da imagem -->';
echo '		<div class="tab-pane fade" id="inferior' . $indice . '">';
					 
echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
		
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					Inferior Esquerdo';
echo '				</td>';
echo '				<td>&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					Inferior Direito';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
					
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Latitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $BL_Lat;
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Latitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					' . $BR_Lat;
echo '				</td>';
							
echo '			</tr>';
			
echo '			<tr height="4">';
echo '			<td colspan="6"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Longitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $BL_Lon;
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Longitude';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
echo '					' . $BR_Lon;
echo '				</td>';
							
echo '			</tr>';
echo '			</table>';
	
echo '		</div>';
		




// Aba/página referente à mais informações			
//
echo '		<!-- Página contendo mais informações -->';
echo '		<div class="tab-pane fade" id="mais' . $indice . '">';
					 
echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
		
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					Mais informações ...';
echo '				</td>';
echo '				<td>&nbsp;';
echo '				</td>';
echo '				<td colspan="2" valign="top">';
echo '					&nbsp;';
echo '				</td>';
echo '			</tr>';
			
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
					
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Órbita';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $orbita;
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Região';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF" align="right" style="font-size:10px">';
echo '					' . $regiao;
echo '				</td>';
							
echo '			</tr>';
			
echo '			<tr height="4">';
echo '			<td colspan="6"></td>';
echo '			</tr>';
			
	
echo '			<tr height="22" style="height:22px">';
echo '				<td width="10" height="22">&nbsp;';
echo '				</td>';
			
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Ponto';
echo '				</td>';
echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
echo '					' . $ponto;
echo '				</td>';
	
	
echo '				<td>&nbsp;';
echo '				</td>';
				
				
echo '				<td width="65" bgcolor="#DDDDDD">';
echo '					Zona UTM';
echo '				</td>';
echo '				<td width="80" bgcolor="#FFFFFF" align="right">';
echo '					' . $fuso;
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



/*
Função: retornaBotaoSatelite
Apresenta o código necessário contendo a mensagem referente à(s) coordenada(s) inválida(s)

parametroIndice					Parametro referente ao indice da imagem 
parametroSatelite				Parametro contendo o satélite 
parametroPesquisaPorSatelite	Parametro contendo os valores dos parâmetros da pesquisa atual.
								Será utilizado 
*/
function retornaBotaoSatelite( $parametroIndice, $parametroSatelite, $parametroPesquisaPorSatelite )
{

	$satelite=strtoupper($parametroSatelite);
	$imagem='/catalogo/img/icone_satelite_branco.png';
	$retorno='';
	
	// Parâmetro a ser utilizad com link para o botão rferente ao filtro para o satélite da imagem
	$parametroNovo='&SATELITE=' . $satelite . $parametroPesquisaPorSatelite;
	
	// Formata a descrição e a imagem referente ao satélite informado
	$descricao_sateliete='';
	switch ( $satelite )
	{
		case 'NPP':
			$descricao_sateliete="S-NPP";
			$imagem = '/catalogo/img/icone_npp_01.png';
			break;	

		case 'A1':
		case 'AQUA':
			$descricao_sateliete="AQUA";
			$imagem = '/catalogo/img/icone_aqua_01.png';
			break;	

		case 'T1':
		case 'TERRA':
			$descricao_sateliete="TERRA";		
			$imagem = '/catalogo/img/icone_terra_01.png';
			break;	

		case 'UKDMC':
		case 'UKDMC2':
		case 'UK-DMC2':
			$descricao_sateliete="UK-DMC2";
			$imagem = '/catalogo/img/icone_ukdmc2_01.png';
			break;	





		case 'P6':
		case 'RESOURCESAT1':
		case 'RESOURCESAT-1':
			$descricao_sateliete="RourceSat-1";
			$imagem = '/catalogo/img/icone_resourcesat1_01.png';
			break;	



		case 'RES2':
		case 'RESOURCESAT2':
		case 'RESOURCESAT-2':
			$descricao_sateliete="RourceSat-2";
			$imagem = '/catalogo/img/icone_resourcesat2_01.png';
			break;	



		case 'CB2':
		case 'CBERS2':
		case 'CBERS-2':
			$descricao_sateliete="CBERS-2";
			$imagem = '/catalogo/img/icone_cbers2_01.png';
			break;	

		case 'L5':
		case 'LANDSAT5':
		case 'LANDSAT-5':
			$descricao_sateliete="LANDSAT-5";
			$imagem = '/catalogo/img/icone_landsat5_01.png';
			break;	


		case 'L8':
		case 'LANDSAT8':
		case 'LANDSAT-8':
			$descricao_sateliete="LANDSAT-8";
			$imagem = '/catalogo/img/icone_landsat8_01.png';
			break;	
			

		case 'CB4':
		case 'CBERS4':
		case 'CBERS-8':
			$descricao_sateliete="CBERS-4";
			$imagem = '/catalogo/img/icone_cbers4_01.png';
			break;	


		case 'RE1':
		case 'RAPIDEYE1':
			$descricao_sateliete="RAPIDEYE-1";		
			$imagem = '/catalogo/img/icone_rapideye_01.png';
			break;	


		case 'RE2':
		case 'RAPIDEYE2':
			$descricao_sateliete="RAPIDEYE-2";		
			$imagem = '/catalogo/img/icone_rapideye_02.png';
			break;	


		case 'RE3':
		case 'RAPIDEYE3':
			$descricao_sateliete="RAPIDEYE-3";		
			$imagem = '/catalogo/img/icone_rapideye_03.png';
			break;	


		case 'RE4':
		case 'RAPIDEYE4':
			$descricao_sateliete="RAPIDEYE-4";		
			$imagem = '/catalogo/img/icone_rapideye_04.png';
			break;	


		case 'RE5':
		case 'RAPIDEYE5':
			$descricao_sateliete="RAPIDEYE-5";		
			$imagem = '/catalogo/img/icone_rapideye_05.png';
			break;	

	}	
	
	// Formata o link responsável por filtrar a pesquisa corrente apenas para o satélite da imagem atual
	$linkPesquisa='<a id="linksatelite' . $parametroIndice . '"  data-toggle="tooltip" title="Clique aqui para filtrar a pesquisa atual para o satélite ' . strtolower($descricao_sateliete) . '" data-trigger="hover" data-animation="true" data-placement="bottom"  data-container="body"   onmouseover="mostraToolTip(\'' . 'linksatelite' . $parametroIndice . '\')"  onmouseout="ocultaToolTip(\'' . 'linksatelite' . $parametroIndice . '\')"    onclick="ocultaToolTip(\'' . 'linksatelite' . $parametroIndice . '\'); obtemDadosPaginado(1, \'' . $parametroNovo . '\')" >';

	// Froamata o ink de retorno para que contenha o link e a imagem do satélite 
	$retorno=$linkPesquisa . '<img src="' . $imagem . '" alt="' . $satelite . '"></a>';	
		
	return $retorno;
}





/*
Função: mensagemNenhumRegistroEncontrado
Apresenta o código necessário para informar qe nenhum registro foi encontrado
*/
function mensagemNenhumRegistroEncontrado()
{
	
	echo '<table border="0" cellpadding="5" cellspacing="2">';
	echo '	<tr>';	
	echo '		<td>Nenhum registro encontrado para a seleção realizada</td>';	
	echo '	</tr>';	
	echo '</table>';
	
}



/*
Função: mensagemRegiaoInvalida
Apresenta o código necessário contendo a mensagem referente à(s) coordenada(s) inválida(s)

norte		Parametro contendo o valor da coordenada Norte
sul			Parametro contendo o valor da coordenada Sul
leste		Parametro contendo o valor da coordenada Leste
oeste		Parametro contendo o valor da coordenada Oeste
*/
function mensagemRegiaoInvalida( $norte, $sul, $leste, $oeste )
{
		
	echo '			<br>';
	echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
			
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
	echo '				</td>';
	echo '				<td colspan="5" valign="top">';
	echo '					Latitude ou longitude com valores vazios ou inválido(s).';
	echo '				</td>';
	echo '			</tr>';
								
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
	echo '				</td>';
	echo '				<td colspan="2" valign="top">';
	echo '					Latitude';
	echo '				</td>';
	echo '				<td>&nbsp;';
	echo '				</td>';
	echo '				<td colspan="2" valign="top">';
	echo '					Longitude';
	echo '				</td>';
	echo '			</tr>';			
				
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
						
	echo '				</td>';
				
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Latitude';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
	echo '					' . $norte;
	echo '				</td>';
		
	echo '				<td>&nbsp;';
	echo '				</td>';
					
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Latitude';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
	echo '					' . $leste;
	echo '				</td>';
								
	echo '			</tr>';
				
	echo '			<tr height="4">';
	echo '			<td colspan="6"></td>';
	echo '			</tr>';
				
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
	echo '				</td>';
				
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Sul ';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
	echo '					' . $sul;
	echo '				</td>';
		
	echo '				<td>&nbsp;';
	echo '				</td>';
					
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Oeste';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
	echo '					' . $oeste;
	echo '				</td>';
								
	echo '			</tr>';
	echo '			</table>';

}



/*
Função: mensagemInterfaceInvalida
Apresenta o código necessário contendo a mensagem referente à(s) coordenada(s) inválida(s)

lat		Parametro contendo o valor da coordenada Latitude
lon		Parametro contendo o valor da coordenada Longitude
*/
function mensagemInterfaceInvalida( $lat, $lon )
{
	echo '			<br>';
	echo '			<table border="0" cellpadding="4" cellspacing="4" width="380">';
				
			
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
	echo '				</td>';
	echo '				<td colspan="5" valign="top">';
	echo '					Latitude ou longitude com valor vazio ou inválido.';
	echo '				</td>';
	echo '			</tr>';
				
									 
	echo '			<tr height="22" style="height:22px">';
	echo '				<td width="10" height="22">&nbsp;';
						
	echo '				</td>';
				
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Latitude';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF"  align="right">';
	echo '					' . $lat;
	echo '				</td>';
		
		
	echo '				<td>&nbsp;';
	echo '				</td>';
					
					
	echo '				<td width="65" bgcolor="#DDDDDD">';
	echo '					Longitude';
	echo '				</td>';
	echo '				<td width="75" bgcolor="#FFFFFF" align="right">';
	echo '					' . $lon;
	echo '				</td>';
								
	echo '			</tr>';
				
	echo '			<tr height="4">';
	echo '				<td colspan="6"></td>';
	echo '			</tr>';
				
	echo '			</table>';	
}



?>


 