<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
?>
<!DOCTYPE html>

<!-- Definição da linguagem -->
<html lang="en">

<head>
    <meta charset="utf-8">
    
    <title>Divisão de Geração de Imagem :: Catálogo de Imagens</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página principal do site da DGI (Divisão de Geração de Imagem)">
    <meta name="author" content="Desenvolvimento Web - DGI">
    
    <!-- Estilos -->
    <link href="/catalogo/css/bootstrap.css" rel="stylesheet">
    <link href="/catalogo/css/style.css" rel="stylesheet">
    <link href="/catalogo/css/camera.css" rel="stylesheet">
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

<div class="body" style="background-color:#F3F3F3; border-left:solid thin #FFF;border-left-color:#FFF; border-left-width:thin; border-right:solid thin #FFF;border-right-width:thin; border-right-color:#FFF; border-top:none; border-bottom:none; padding: 0px 0 0px; padding-bottom:0px;">

    <br>
    
    <div class="row-fluid">

        <div class="accordion-group" style="background-color:#F3F3F3">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                Considerações iniciais </a>
            </div>
            <div id="collapseOne" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                     <br>
                     <p align="justify"> A pesquisa é realizada com base nos campos existentes na página acessada através da aba "Como pesquisar" e os resultados apresentados na página acessada através da aba "Resultados". Aqui serão apreesentadas informações básicas para realizar a pesquisa, entender o que é cada campo, como utilizá-lo, como visualizar os resultados em forma de registros e também como obter e visualizar informações referentes a cada um desses registros retornados pela pesquisa. 
                     Também serão apresentadas informações relacionadas à exibição e ocultação dos menus de Pesquisa de imagens, Ferramentas e informações, ao Cadastro, Carrinho de imagens selecionadas, Histórico de pedidos.</p>
                </div>
            </div>
        </div>
        
        
        
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                Como pesquisar </a>
            </div>
            <div id="collapseTwo" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                     Para realizar a pesquisa é necessário preencher alguns campos existentes na página acessaada através da aba "Pesquisa". Os resultados das pesquisas serão apresentadas em outra página, acessada através da aba "Resultados" . O usuário será redirecionado automaticamente para essa página após execução da pesquisa. Essa separação permite tornar mais simples e dinâmica a alteração dos critérios de pesquisa dos registros.
                </p>
                </div>
            </div>
        </div>
        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                Interagindo com os resultados </a>
            </div>
            <div id="collapseThree" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                Nesta página serão apresentados os resultados gerados com base nos critérios de seleção da página Pesquisar. Os resultados são apresentados páginados em 20 registros. Com base nos resgistros apresentados é possível obter informações de resumo, o footprint, o quicklook, colocar no carrinho de imagens.
                </p>
                </div>
            </div>
        </div>
        

        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                Visualizar ... </a>
            </div>
            <div id="collapseFour" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                     Esta opção do menu superior apresenta uma lista de itens de menu para que se possa exibir ou ocultar as janelas referente às janelas de Pesquisa e Seleção de idioma.
                     Basta apenas slecionar cada um para que as respectivas janelas sejam exibidas ou ocultadas.
                </p>
                </div>
            </div>
        </div>
        
 
        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                Seleção do idioma</a>
            </div>
            <div id="collapseFour" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                     Através desta opção é possível alterar o idioma selecionado e assim a página atual e as acessadas posteriormnete serão apresentadas no novo idioma selecionado.<br>
                     Para ter acesso à janela de selecção de idiomas basta apenas acessar o menu "Visualizar" e em seguida selecionar o item de menu "Janela de Idiomas".
                </p>
                </div>
            </div>
        </div>
        
                 
        
        
 
        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFive">
                Realizar cadastro</a>
            </div>
            <div id="collapseFive" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                Esta opção permite que o usuário realize seu cadastro no catálogo de imagens, e assim o mesmo poderá, além de pesquisar e visualizar as imagens, adicioná-las  ao carrinho,e dessa forma solicitar que as mesmas seja disponibilizadas. 
                </p>
                </div>
            </div>
        </div>
        
        

        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSix">
                Carrinho de imagens</a>
            </div>
            <div id="collapseSix" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                Através desta opção é possível ter acesso ao carrinho de imagens. Apresenta uma janela com a lista de todas as imagens selecionadas, podendo a partir desta janela finalizar o pedido para que o mesmo seja processado e as imagens disponibilizadas futuramente. 
                </p>
                </div>
            </div>
        </div>
        
        

                  

        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven">
                Histórico de pedidos</a>
            </div>
            <div id="collapseSeven" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                     Esta opção permite que sejam consultados todos os pedidos realziados pelo usuário corrente. 
                </p>
                </div>
            </div>
        </div>
        

                  

        
        
          
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseEight">
                Ajuda</a>
            </div>
            <div id="collapseEight" class="accordion-body collapse" style="height: 0px;background-color:#FEFEFE">
                <div class="accordion-inner">
                <br>
                <p align="justify">
                     A opção de ajuda disponibliza uma página contendo um indice das principais atividades e procedimentos relacionados ao uso do catálogo.
                </p>
                </div>
            </div>
        </div>
                                
    </div>
          
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
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>
<script src="/catalogo/instrucoes.js"></script>


<!-- CALL ACCORDION -->
<!--script type="text/javascript">

	$(".accordion h3").eq(0).addClass("active");
	$(".accordion .accord_cont").eq(0).show();
	$(".accordion h3").click(function(){
		$(this).next(".accord_cont").slideToggle("slow")
		.siblings(".accord_cont:visible").slideUp("slow");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});	

	
	
</script-->



<!-- Call opacity on hover images from recent news-->
<!--script type="text/javascript">
$(document).ready(function()
{
	
    $("img.imgOpa").hover(function() {
      $(this).stop().animate({opacity: "0.6"}, 'slow');
    },
    function() {
      $(this).stop().animate({opacity: "1.0"}, 'slow');
    });



	// Permite zerar o contador de tempo ocioso
	// Necessário para que a quatidade de segundos antes de realizar a desconexão do usuário seja reiniciada
	// Executa a função atualizaIdleTime existente na página container
	//
	// Funão acionada pelos seguintes eventos que ocorrem: click, dblclick, keypress, mouseenter, select, scroll, resize,
	// mouseover, mousemove, mouseout, mouseenter, blur, focus
	$(this).on("click dblclick keypress mouseenter select scroll resize mouseover mousemove mouseout mouseenter blur", function(e)
	{
	  top.atualizaIdleTime();
	});
		


				
});


</script-->
	



<!-- 
Final da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

</body>
</html>

