<?php
//
// Essa classe eh utilizada para processar a pagina de retorno dos metodos de
// pagamento, gerando o cabecalho e o formulario para os metodos que envolvam
// mais de uma submissao de dados (Visanet).
//
include( "payment.class.php" );

class processaPaginaRetorno
{
  var $method;
  var $orderId;
  // Variaveis relacionadas ao metodo Visanet
  var $tid;
  var $lr ;      // Codigo de retorno da transacao
  var $retMsg;   // Msg de envia pela Contra-parte (Visanet)
  var $retPrice; // Preco efetivamente pago
  var $authCode; // Codigo de autorizacao da compra

  // Variaveis relacionadas ao metodo Mastercard
  var $numPedido;
  var $data;
  var $nr_cartao;
  var $origem_bin;
  var $numAutor;
  var $numCV;
  var $numAutent;
  var $numSQN;

  var $flags;   // Para o metodo Visanet:
                //   0: nao inicializado
                //   1: erro
                //   2: primeira etapa
		//   ...
                //   n: (n-1)-esima etapa

  function processaPaginaRetorno() {
    // Visanet
    $this->orderId    = 0;
    $this->flags      = 0;
    $this->tid        = 0;
    $this->lr         = -1;
    $this->authCode   = -1;
    $this->retMsg     = "";
    $this->retPrice   = "";
    $this->method     = "";
    // REDECARD
    $this->numPedido  = 0;
    $this->data       = 0;
    $this->nr_cartao  = 0;
    $this->origem_bin = 0;
    $this->numAutor   = 0;
    $this->numCV      = 0;
    $this->numAutent  = 0;
    $this->numSQN     = 0;
  }

  function setTID( $tid ) {
    $this->tid = $tid;
  }

  function setOrderId( $orderid ) {
    $this->orderId = $orderid;
  }

  function setLR( $lr ) {
    $this->lr = $lr;
  }

  function setRetMsg( $retMsg ) {
    $this->retMsg = $retMsg;
  }

  function setRetPrice( $retPrice ) {
    $this->retPrice = $retPrice;
  }

  function setAuthCode( $authCode ) {
    $this->authCode = $authCode;
  }

  function setPaymentMethod( $method ) {
    $this->method = $method;
  }

  // Descobre o tipo de operacao realizada para o metodo Visanet. Esse
  // processamento eh baseado na variavel POST 'lr' enviada pelo site remoto.
  function processaVisa() {
    $dbcat = $GLOBALS["dbcatalog"];
    $sql  = "UPDATE RequestPayment SET";
    // A unica pagina que nao seta o LR eh submissao 1
    if( $this->lr == -1 ) {
      $this->flags = 2;     // Transacao em andamento
    } elseif( $this->lr == 0 or $this->lr == 11 ) {
      $this->flags = 3;
      $status      = "'PAYED'";
      // Check if payed price is equal to the charged price
      $sql2 = "SELECT Price FROM RequestPayment WHERE ReqId=".o2r( $this->orderId );
      if( $dbcat->query( $sql2 ) ) {
        if( $row = $dbcat->fetchRow() ) {
          $chargedPrice = $row["Price"] * 100;
          if( $chargedPrice != $this->retPrice ) {
              $this->lr = 666;
              $this->retMsg = sprintf( "O valor pago pelo cliente (R$ %s) não casa	com preço da mercadoria adquirida (R$ %s)",
                                  number_format( $this->retPrice, 2, ',', '.' ),
                                  number_format( $chargedPrice, 2, ',', '.' ) );
              $status = "'INVALID_PAYMENT'";
          }
        }
      }
      $sql .= " status=".$status.", authorizationCode=".$this->lr;
    }
    else {// qualquer outro codigo eh tratado como erro
      $this->flags = 1;
      $sql .= " status='INVALID_PAYMENT', authorizationCode=".$this->lr;
    }

    // Update information on the database if necessary
    if( $this->lr != -1 ) {
      if( $this->authCode != "" ) {
          if( $this->retMsg != "" ) {
            $this->retMsg .= " - ";
          }
          $this->retMsg .= "Código de autorização: ".$this->authCode;
      }
      $sql  .= " , PaymentDate='".date( "Y-m-d G:i:s" );
      $sql  .= "' ,statusDescription='".$this->retMsg;
      $sql  .= "' WHERE ReqId=".o2r( $this->orderId );   // remove extra digit
      log_msg( $sql );

      if( !$dbcat->query( $sql ) ) {
        log_msg( $dbcat->error( $sql ) );
        die( $dbcat->error( $sql ) );
      }
    }
  }

  // Descobre o tipo de operacao realizada para o metodo Redecard.
  function processaMastercard() {
    // Se as variaveis foram POSTadas, entao essa eh a primeira etapa
    if( $this->numPedido  != 0 and $this->data != 0 and $this->nr_cartao != 0 and
        $this->origem_bin != 0 and $this->numAutor != 0 and $this->numCV != 0 and
	$this->numAutent  != 0 and $this->numSQN   != 0 ) {
      $this->flags = 2;
    }
//    elseif( $this->)
  }

  function generateVisaBody() {
      $bodyResult = '';
      switch( $this->flags ) {
        case 2:
          $bodyResult  = '<body onload="onLoadEvent();">'."\n";
          $bodyResult .= '<form name="visavbv" method="POST"
            action="http://comercio.locaweb.com.br/visavbv/dados_visa_vbv.asp">
            <input type="hidden" name="tid" value="'.$this->tid.'">
            <input type="hidden" name="URLRetornoVisa" value="'.$GLOBALS["urlRetornoVisaPath"].
	    '/retornoVisanet.php">
            <input type="hidden" name="PosicaoDadosVisanet" value="1">';
          $bodyResult .= ' </form>';
          break;

        case 3:
          $bodyResult  = "<body>\n<h3>";
          $bodyResult .= $this->retMsg;
          $bodyResult .= "\n</h3>\n";
          break;

        default:
          $bodyResult  = "<body>\n";
          $bodyResult .= "<h2>Erro (".$this->lr.")";
          if( $this->retMsg != "" )
            $bodyResult .= ":</h2>".$this->retMsg."\n";
          else
            $bodyResult .= "</h2>\n";
      }
      return $bodyResult;
  }

  function generateMastercardBody() {
    $bodyResult = '';
  }

  function processa() {
    switch( $this->method ) {
      case 'Boleto':
        break;

      case 'Mastercard':
        $this->processaMastercard();
        break;

      case 'Visa':
        $this->processaVisa();
        break;
    }
  }

  // Gera cabecalho da pagina de acordo com o metodo de pagamento e com o tipo
  // da operacao.
  function generateHeader() {
    echo "<head>\n";
    switch( $this->method ) {
      case 'Boleto':
        break;

      case 'Mastercard':
        break;

      case 'Visa':
        if( $this->flags == 2  ) {
          ?>
            <script type="text/javascript">
              function onLoadEvent() {
                window.document.visavbv.submit();
              }
            </script>
          <?
        }
        break;

      default:
        log_msg( 'problem in form generation. selected payment method ('.
                 $this->method.') is not supported', LOG_ERR );
    }
    include( "css.php" );
    echo "</head>\n";
  }

  // Gera o corpo da pagina de acordo com o metodo de pagamento e com o tipo
  // da operacao.
  function generateBody() {
    $formResult = '';
    switch( $this->method ) {
      case 'Boleto':
        echo "  <body>\n";
        $formResult = $this->generateBoletoBody();
        break;

      case 'Mastercard':
        echo "  <body>\n";
        $formResult = $this->generateMastercardBody();
        break;

      case 'Visa':
        echo $this->generateVisaBody();
        break;

      default:
        echo "  <body>\n";
        log_msg( 'problem in HTML body generation. selected payment method ('.
        $this->method.') is not supported', LOG_ERR );
    }
    echo "  </body>\n";
  }
}
?>
