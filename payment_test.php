<?php
  include( "payment.class.php" );

  $pay = new Payment();
  $pay->setOrderId( 123 );
  $pay->setChargedPrice( 1234567 );
  $pay->setName( 'Flavio P. Duarte' );
  $pay->setCGCCPF( '98765432112' );
  $pay->setPaymentMethod( "XXX" );

  // Mostra a tela
  echo $pay->generateForm();
  echo '<BR>'.$pay->getOrderId().'<BR>';
?>
