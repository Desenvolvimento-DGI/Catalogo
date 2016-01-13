// JavaScript Document


$(".accordion h3").eq(0).addClass("active");
$(".accordion .accord_cont").eq(0).show();
$(".accordion h3").click(function(){
	$(this).next(".accord_cont").slideToggle("slow")
	.siblings(".accord_cont:visible").slideUp("slow");
	$(this).toggleClass("active");
	$(this).siblings("h3").removeClass("active");
});	

	

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

