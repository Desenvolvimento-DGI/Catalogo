


/**
Nome: trocaTextoOpcaoCamadas
parametroIdOpcao		Identificação (Id) do menu que terá seu texto alterado
parametroTextoOpcao	Texto comum ao menu nas situações em que estiver oculto ou em exibição

*/
function trocaTextoOpcaoCamadas(parametroIdOpcao)
{
	var hTextoOpcaoAtual = document.getElementById(parametroIdOpcao); // Handle para o objeto
	var texto = hTextoOpcaoAtual.innerHTML; // Texto do objeto
	
	var opcaoAtiva       = '<i class="icon-check" style="font-weight:bold"></i>';
	var opcaoDesativada  = '<i class="icon-check-empty"></i>';
	
	// Altera o texto conforme valor do texto atual
	hTextoOpcaoAtual.innerHTML = (texto == opcaoAtiva)?opcaoDesativada:opcaoAtiva;
}






/**
Ativa ou desativa camada do mapa do Brasil com os estados
*/

function toggleCamadaBrasil(parametroIdOpcao)
{
		top.ativaCarregamento();
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaBrasil();
		top.desativaCarregamento();
		
}



/**
Ativa ou desativa camada de nuvens
*/
function toggleCamadaNuvens(parametroIdOpcao)
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaNuvens();
}

	





/**
Ativa ou desativa as grades dos Satélites
*/

function toggleGradeCbers(parametroIdOpcao)
{
		//top.ativaCarregamento();	
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleGradeCbers();
		//top.desativaCarregamento();
}


/*
function toggleGradeCbersCCD(parametroIdOpcao)
{
		//trocaTextoOpcaoCamadas(parametroIdOpcao);
		//top.frames['fmosaico'].toggleGradeCbersCCD();
}


function toggleGradeCbersHRC(parametroIdOpcao)
{
		//trocaTextoOpcaoCamadas(parametroIdOpcao);
		//top.frames['fmosaico'].toggleGradeCbersHRC();
}
*/

function toggleGradeLandSat5(parametroIdOpcao)
{
		//top.ativaCarregamento();	
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleGradeLandSat5();
		//top.desativaCarregamento();
}


function toggleGradeRapidEye(parametroIdOpcao)
{
		//top.ativaCarregamento();
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleGradeRapidEye();
		//top.desativaCarregamento();
}




function toggleGradeResourceSat1(parametroIdOpcao)
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleGradeResourceSat1();
}


// Final grades de satélite







/**
Ativa ou desativa as camadas de divisões territoriais
da Américas do Sul, Central e Norte
*/

function toggleCamadaAmericaSul(parametroIdOpcao)
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaAmericaSul();
}


function toggleCamadaAmericaCentral(parametroIdOpcao)
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaAmericaCentral();
}


function toggleCamadaAmericaNorte(parametroIdOpcao)
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaAmericaNorte();
}


// Final grades de satélite







/**
Ativa ou desativa as camadas de divisões estaduais
*/
function toggleCamadaEstados(parametroIndiceEstado, parametroIdOpcao )
{
		//top.ativaCarregamento();
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleCamadaEstados(parametroIndiceEstado);		
		//top.desativaCarregamento();
}












/**
Marcadores
*/





/**
Ativa ou desativa as camadas de divisões estaduais
*/
function toggleMarcadoresAmericaSul( parametroIdOpcao )
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleMarcadoresAmericaSul();		
}





/**
Ativa ou desativa as camadas de divisões estaduais
*/
function toggleMarcadoresAmericaCentral( parametroIdOpcao )
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleMarcadoresAmericaCentral();		
}





/**
Ativa ou desativa as camadas de divisões estaduais
*/
function toggleMarcadoresAmericaNorte( parametroIdOpcao )
{
		trocaTextoOpcaoCamadas(parametroIdOpcao);
		top.frames['fmosaico'].toggleMarcadoresAmericaNorte();		
}



