<?php
//Para configurar a url de retorno visanet é preciso abrir um chamado com
//a locaweb no endereço https://helpdesk.locaweb.com.br/

include( "globals.php" );
include( "processaPaginaRetorno.class.php" );

$procPagRetorno = new processaPaginaRetorno();
$procPagRetorno->setPaymentMethod( "Visa" );

log_msg( "\n\nEntrei na pagina ".__FILE__."\n" );

// Extrai todas as informacoes relevantes da pagina para determinar qual o tipo
// de transacao (erro, submissao 1, submissao 2). O tipo de submissao serah
// determinado pela classe 'processaPaginaRetorno' no metodo 'processa'
if( isset( $tid ) ) {
  log_msg( "  tid      = $tid" );
  $procPagRetorno->setTID( $tid );
}


if( isset( $orderid ) ) {
  log_msg( "  orderid  = $orderid" );
  $procPagRetorno->setOrderId( $orderid );
}

// Na documentacao eh dito que o valor pago eh retornado na variavel 'P_Amount',
// entretanto, um dump da pagina mostra que essa variavel nao existe e que o
// valor pago estah na variavel 'price'.
if( isset( $price ) ) {
  log_msg( "  retPrice = $price" );
  $procPagRetorno->setRetPrice( $price );
}

if( isset( $lr ) ) {
  log_msg( "  lr       = $lr" );
  $procPagRetorno->setLR( $lr );
}

if( isset( $ars ) ) {
  log_msg( "  ars      = $ars" );
  $procPagRetorno->setRetMsg( $ars );
}

if( isset( $arp ) ) {
  log_msg( "  arp      = $arp" );
  $procPagRetorno->setAuthCode( $arp );
}


// Descobre o tipo de pagina que deve ser gerado
$procPagRetorno->processa();

echo "<html>\n";

// Gera cabecalho da pagina
$procPagRetorno->generateHeader();

// Gera corpo da pagina
$procPagRetorno->generateBody();

echo "</html>\n";
?>
