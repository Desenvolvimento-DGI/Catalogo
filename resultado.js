

function mensagemOutroIframe( mensagem )
{	
	top.frames['fmosaico'].mostraMensagem( mensagem );
}



// Funções responsáveis por chamar as funções que
// possuem o código de execução na área do mapa



/**
* Função para exibir ou ocultar o footprint indiretamente através da
* chamada a função footPrint existente no frame interno fmosaico
*
* Recebe um vetor com informações da imagem 
*/
function chamaFootPrint( parametroFootPrint )
{
	// Executa a função footPrint no frame fmosaico
	top.frames['fmosaico'].footPrint( parametroFootPrint );	
}




/**
* Função para exibir ou ocultar o marcador indiretamente através da
* chamada a função marcadores existente no frame interno fmosaico
*
* Recebe um vetor com informações da imagem 
*/
function chamaMarcador( parametroMarcador )
{
	// Executa a função footPrint no frame fmosaico
	top.frames['fmosaico'].marcador( parametroMarcador );	
}




/**
* Função para exibir ou ocultar a imagem indiretamente através da
* chamada a função imgOverlay existente no frame interno fmosaico
*
* Recebe um vetor com informações da imagem 
*/
function chamaImgOverlay( parametroImgOverlay )
{
	
	// Altera a cord e fundo da linha referente à imagem
	var indiceImagem = parametroImgOverlay[0];
	var linhaImagem = document.getElementById('colunaimagem' + indiceImagem);
	var corAtual = linhaImagem.style.backgroundColor;
	
	
	if ( corAtual == "" ) 
	{
		linhaImagem.style.backgroundColor = "#05A";
	}
	else
	{
		linhaImagem.style.backgroundColor = "";
	}
	

	// Executa a função imgOverlay no frame fmosaico
	top.frames['fmosaico'].imgOverlay( parametroImgOverlay );	
}




/**
* Função para localizar e centralizar a imagem no mapa indiretamente através da
* chamada a função localizarImagem existente no frame interno fmosaico
*
* Recebe um vetor com as coordenadas central da imagem 
*/
function chamaLocalizarImagem( parametroCoordenadas )
{
	// Executa a função localizarImagem no frame fmosaico
	top.frames['fmosaico'].localizarImagem( parametroCoordenadas );	
}




/**
* Função para adicionar a imagem indiretamente no carrinho de imagens através da
* chamada a função adicionarAoCarrinho existente na página principal
*
* Recebe um vetor com informações da imagem 
*/
function chamaAdicionarAoCarrinho( parametroImagem )
{
	// Executa a função localizarImagem na página principal
	top.adicionarAoCarrinho( parametroImagem );	
}





/**
* Função para apresentar uma janela com informações da imagem informada através da
* chamada a função informacoesImagem existente no documento principal
*
* Recebe um vetor com informações da imagem 
*/
function chamaInformacoesImagem( parametroImagem )
{
	// Executa a função localizarImagem no frame fmosaico
	//alert("Dentro da pagina de resultados");
	top.detalhesDaImagem( parametroImagem );	
}




	


function obtemDadosPaginado( paginaAtual, strParametros )
{
	
	var objetoHTTP;
	
	// Libera os objetos criados
	top.frames['fmosaico'].liberaImagens();	
		
	if (window.ActiveXObject)
	{
		objetoHTTP=new ActiveXObject("Microsoft.XMLHTTP"); // IE
	}
	else if (window.XMLHttpRequest)
	{
		objetoHTTP=new XMLHttpRequest(); // Outros Navegadores
	}
	else
	{
		return null;
	}
	

	objetoHTTP.onreadystatechange=function()
	{
		// Elemento sapn nde serão gerados os dados
		var registros = document.getElementById('registros');
			
		if (objetoHTTP.readyState == 4 && objetoHTTP.status == 200)
		{
			var retorno = objetoHTTP.responseText;

			if ( retorno.trim() == "0" )
			{
				//alert( "Resultado =>  " + retorno);	
				retorno = '<table border="0" cellpadding="5" cellspacing="2">' +
				'<tr>' +
				'<td>Nenhum registro encontrado para a seleção realizada</td>' +
				'</tr>' +
				'</table>';
			}
			
			
			registros.innerHTML=retorno;
			top.desativaCarregamento();
		}
		else
		{			
			top.ativaCarregamento();				
		}
				
	}
	
	
	
	top.desativaCarregamento();
	objetoHTTP.open("GET","buscarimagens.php?p=1&pg=" + paginaAtual + strParametros, true);
	objetoHTTP.send();
}




function validarNumero( evento )
{

	var tecla = (window.event)?event.keyCode:evento.which;   
	
		if( (tecla > 47 ) && (tecla < 58) ) return true;
		else 
		{
			
			if ( tecla == 190 ) return true;
			
			if (tecla==8 || tecla==0) return true;
		    else  return false;
		}	
		
}





function verMaisInformacoes( parametroImagem )
{
	
	var idIcone = "iconmaisinfo" + parametroImagem[0];
	var idLinhaTabela = "linhamaisinfo"  + parametroImagem[0];
	var idBotaoAtual = "maisinfo"  + parametroImagem[0];
	
	var iconeVerMais = document.getElementById(idIcone);
	var linhaVerMais = document.getElementById(idLinhaTabela);
	var botaoAtual = document.getElementById(idBotaoAtual);
	
	var jqueryLinhaVerMais = "#" + idLinhaTabela;
	
	
	if ( iconeVerMais.className == 'icon-double-angle-up' )
	{
		linhaVerMais.style.display = 'block';		
		iconeVerMais.className = 'icon-double-angle-down';							
	}
	else
	{
		linhaVerMais.style.display = 'none';
		iconeVerMais.className = 'icon-double-angle-up';	
	}

	$(jqueryLinhaVerMais).slideToggle("slow");	
	
	window.status='';

}






function mostraToolTip( parametroNomeBotao )
{		
	var jqueryNomeBotao = "#" + parametroNomeBotao;	
	$(jqueryNomeBotao).tooltip('show');	
	window.status='';

}



function ocultaToolTip( parametroNomeBotao )
{		
	var jqueryNomeBotao = "#" + parametroNomeBotao;	
	$(jqueryNomeBotao).tooltip('hide');	
	window.status='';
	
}




    