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
Função: mensagemNenhumRegistroEncontrado
Apresenta o código necessário para informar qe nenhum registro foi encontrado
*/
function mensagemNenhumRegistroEncontrado()
{
	
	echo '<table border="0" cellpadding="5" cellspacing="2"  case ';
	echo '	<tr>';	
	echo '		<td>Nenhum registro encontrado para a seleção realizada</td>';	
	echo '	</tr>';	
	echo '</table>';
	
}



function retornaSiglaEstado( $parEstado )
{


	$retorno="";

	switch ( strtoupper($parEstado) )
	{
	
		case "ACRE":
			$retorno="AC";
			break;
		
		case "ALAGOAS":
			$retorno="AL";
			break;
		
		case "AMAPÁ":
			$retorno="AP";
			break;
		
		case "AMAZONAS":
			$retorno="AM";
			break;
		
		case "BAHIA":
			$retorno="BA";
			break;
		
		case "DISTRITO FEDERAL":
			$retorno="DF";
			break;
		
		case "CEARÁ":
			$retorno="CE";
			break;
		
		case "ESPÍRITO SANTO":
			$retorno="ES";
			break;
		
		case "GOIÁS":
			$retorno="GO";
			break;
		
		case "MARANHÃO":
			$retorno="MA";
			break;
		
		case "MATO GROSSO":
			$retorno="MT";
			break;
		
		case "MATO GROSSO DO SUL":
			$retorno="MS";
			break;
		
		case "MINAS GERAIS":
			$retorno="MG";
			break;
		
		case "PARÁ":
			$retorno="PA";
			break;
		
		case "PARAÍBA":
			$retorno="PB";
			break;
		
		case "PERNAMBUCO":
			$retorno="PE";
			break;
		
		case "PIAUÍ":
			$retorno="PI";
			break;
		
		case "PARANÁ":
			$retorno="PR";
			break;
		
		case "RIO DE JANEIRO":
			$retorno="RJ";
			break;
		
		case "RIO GRANDE DO NORTE":
			$retorno="RN";
			break;
		
		case "RONDÔNIA":
			$retorno="RO";
			break;
		
		case "RORAIMA":
			$retorno="RR";
			break;
		
		case "RIO GRANDE DO SUL":
			$retorno="RS";
			break;
		
		case "SANTA CATARINA":
			$retorno="SC";
			break;
		
		case "SERGIPE":
			$retorno="SE";
			break;
		
		case "SÃO PAULO":
			$retorno="SP";
			break;
		
		case "TOCANTINS":
			$retorno="TO";
			break;
		

	}

	return $retorno;

	
}


?>


 