<?php

define( "NOMELOJA_LOCAWEB", "imagens-cobranca" );
// FIXME: Teste de homologacao do Visanet
// define( "DESCRIPTION", "Imagens de Satelite" );
define( "DESCRIPTION", "TESTE VISANET" );

class Payment {
  // Contantes
  var $n_afiliacao; // Numero de afiliacao (VISANET)
  var $nomeINPE;    // Nome da loja = ap ftp que usa na Locaweb
  var $banco;       // Banco onde lojista tem conta
  var $agencia;     // agencia. Sem explicações no documento

  // dados do sacado. Necessario dependendo do metodo de pagamento
  var $address;
  var $cgccpf;
  var $name;

  var $method;
  var $price;
  var $orderId;

  function Payment() {
    $price      = 0;
    $orderId    = 0;
  }

  function setChargedPrice( $price ) {
    $this->price = $price;
  }

  function getOrderId() {
    return substr( $this->orderId, 0, -1 );
  }

  function setOrderId( $orderId ) {
    $this->orderId = $orderId.$this->calcDV( $orderId );
  }

  function setAddress( $address ) {
    $this->address = $address;
  }

  function setName( $name ) {
    $this->name = $name;
  }

  function setCGCCPF( $cgccpf ) {
    $this->cgccpf = $cgccpf;
  }

  // Calcula o digito verificador de '$num'
  function calcDV( $num ) {
    $temp = $num;
    $dv   = 0;
    while( $temp > 0 ) {
      $dv = ($dv + $temp % 10) % 10;
      $temp /= 10;
    }
    return $dv;
  }

  function geraTID( $shopid, $pagamento ) {
    if( strlen( $shopid ) != 10 ) {
      echo "Tamanho do shopid deve ser 10 dígitos";
      exit;
    }

    if( is_numeric( $shopid ) != 1 ) {
      echo "Shopid deve ser numérico";
      exit;
    }

    if( strlen( $pagamento ) != 4 ) {
      echo "Tamanho do código de pagamento deve ser 4 dígitos.";
      exit;
    }

    //Número da Maquineta
    $shopid_formatado = substr($shopid, 4, 5);

    //Hora Minuto Segundo e Décimo de Segundo
    $hhmmssd = date("His").substr(sprintf("%0.1f",microtime()),-1);

    //Obter Data Juliana
    $datajuliana = sprintf("%03d",(date("z")+1));

    //Último dígito do ano
    $dig_ano = substr(date("y"), 1, 1);


    return $shopid_formatado.$dig_ano.$datajuliana.$hhmmssd.$pagamento;
  }

  // Retorna a data do vencimento, isto eh, a data atual mais trinta dias.
  function getVencimento() {
    $date=mktime();
    $date=mktime( 0, 0, 0, date( "m" , $date ), date( "d", $date ) + 30,
                  date( "Y", $date ) );
    return( date( "d/m/Y", $date ) );
  }

  function setPaymentMethod( $method ) {
    $this->method = $method;
  }

  function generateBoletoForm() {
    $formResult = NULL;
    // verifica campos obrigatorios
    if( $this->price == 0 ) {
      log_msg( 'Price must be greater than 0' );
    }
    elseif( !isset( $this->name ) ) {
      log_msg( 'Name is mandatory to use this method ('.$this->method.')' );
    }
    elseif( !isset( $this->cgccpf ) ) {
      log_msg( 'CPF/CGC is mandatory to use this method ('.$this->method.')');
    }
    else {
      $value = number_format( $this->price, 2, ',', '.' );

      $formResult  = '<form name="boleto" method="POST"
              action="http://comercio.locaweb.com.br/boleto/boleto.comp">';
      $formResult .= '  <input type="hidden" name="valor" value="'.$value.'">';
      $formResult .= '  <input type="hidden" name="banco" value="itau">';
      $formResult .= '  <input type="hidden" name="agencia" value="2971">';
//      $formResult .= '  <input type="hidden" name="carteira" value="175">';
      $formResult .= '  <input type="hidden" name="codigo_cedente" value="113770">';
      $formResult .= '  <input type="hidden" name="numdoc" value="'.$this->orderId.'">';
      $formResult .= '  <input type="hidden" name="conta" value="imagens-cobranca-itau">';
      $formResult .= '  <input type="hidden" name="sacado" value="'.$this->name.'">';
      $formResult .= '  <input type="hidden" name="cgccpfsac" value="'.$this->cgccpf.'">';
      $formResult .= '  <input type="hidden" name="datadoc" value="'.date( 'd/m/Y' ).'">';
      $formResult .= '  <input type="hidden" name="vencto" value="'.$this->getVencimento().'">';
      $formResult .= '  <input type="hidden" name="instr1" value="Este boleto e valido por 30 dias">';
      $formResult .= '  <input type="hidden" name="instr2" value="Pagar em qualquer banco">';
      $formResult .= '  <input type="hidden" name="instr3" value="">';
      $formResult .= '  <input type="hidden" name="instr4" value="">';
      $formResult .= '  <input type="hidden" name="instr5" value="">';
      $formResult .= '  <input type="submit" value="Enviar">';
      $formResult .= '</form>';
    }

    return $formResult;
  }

  function generateMastercardForm() {
    $formResult = NULL;
    if( $this->price == 0 ) {
      log_msg( 'Price must be greater than 0' );
    }
    else {
      // O formato para o preco eh 1000 para 10,00 reais
      $valor = $this->price * 100;

      $formResult  = '<form name="redecard" method="POST"
      action=http://cartao.locaweb.com.br/comercio.comp">
	<input type="hidden" name="meio_pagamento_seguro" value="REDECARD">
	<input type="hidden" name="metodo" value="SAFENET">
	<input type="hidden" name="loja" value="imagens-cobranca">
	<input type="hidden" name="valor" value="'.$valor.'">
	<input type="hidden" name="BANDEIRA" value="CREDICARD">
	<input type="hidden" name="pedido" value="'.$this->orderId.'">
	<input type="hidden" name="parcelas" value="1">
	<input type="hidden" name="juros" value="0">
	<input type="hidden" name="popup" value="0">
	<input type="hidden" name="urlback" value="'.$GLOBALS["urlRetornoVisaPath"].'/retornoMastercard.php">
	<input type="submit">';
      $formResult .= ' </form>';
    }

    return $formResult;
  }

  function generateVisaForm() {
    $formResult = NULL;
    if( $this->price == 0 ) {
      log_msg( 'Price must be greater than 0\n' );
    }
    else {
      $value   = $this->price * 100;
      $damount = number_format( $this->price, 2, ',', '.' );
      $tid     = $this->geraTID( 1001734898, 1001 );
      $order   = $this->name.';'.$this->address.';'.DESCRIPTION;

      $formResult  = '<form name="visavbv" method="POST"
              action="http://comercio.locaweb.com.br/visavbv/dados_visa_vbv.asp">'."\n";
      $formResult .= '  <input type="hidden" name="price" value="'.$value.'">'."\n";
      $formResult .= '  <input type="hidden" name="tid" value="'.$tid.'">'."\n";
      $formResult .= '  <input type="hidden" name="orderid" value="'.$this->orderId.'">'."\n";
      $formResult .= '  <input type="hidden" name="order" value="'.$order.'">'."\n";
      $formResult .= '  <input type="hidden" name="merchid" value="'.NOMELOJA_LOCAWEB.'">'."\n";
      $formResult .= '  <input type="hidden" name="damount" value="R$'.$damount.'">'."\n";
      $formResult .= '  <input type="hidden" name="authenttype" value="">'."\n";
//      $formResult .= '  <input type="hidden" name="visa_antipop" value="1">'."\n";
      $formResult .= '  <input type="hidden" name="free" value="'.$this->orderId.'">'."\n";
      $formResult .= '  <input type="hidden" name="PosicaoDadosVisanet" value="0">'."\n";
      $formResult .= '  <input type="submit" name="submit" value="Enviar">'."\n";
      $formResult .= '</form>'."\n";
    }

    return $formResult;
  }

  function generateForm() {
    $formResult = '';

    switch( $this->method ) {
      case 'Boleto':
	$formResult = $this->generateBoletoForm();
	break;

      case 'Mastercard':
	$formResult = $this->generateMastercardForm();
	break;

      case 'Visa':
	$formResult = $this->generateVisaForm();
	break;

      default:
        log_msg( 'problem in form generation. selected payment method ('.
	$this->method.') is not supported', LOG_ERR );
    }
    return $formResult;
  }

};

// Convert from orderId to ReqId
function o2r( $orderId ) {
  return substr( $orderId, 0, -1 );
}

// Convert from ReqId to orderId
function r2o( $reqId ) {
}
?>
