<?php
include_once( "globals.php" );
include_once( "user.class.php" );
include_once( "payment.class.php" );
import_request_variables( "gpc" );

// Set Language
if( isset( $SESSION_LANGUAGE ) ) {
  $_SESSION[ 'userLang' ] = $SESSION_LANGUAGE;
}
else {
  if( !isset( $_SESSION[ 'userLang' ] ) ) {
    $_SESSION[ 'userLang' ] = 'PT';
  }
}

require ("cart_".$_SESSION['userLang'].".php");	
require ("payment_".$_SESSION['userLang'].".php");	

printf( '<html>
    <title>Data Confirmation</title>
  <head>' );
include( "css.php" );
printf( '</head>' );

printf( '<body>' );
$dbcat     = $GLOBALS[ "dbcatalog" ];
$dbusercat = $GLOBALS[ "dbusercat" ];
$operator  = $_SESSION[ 'operatorId' ];

if( $GLOBALS[ "stationDebug" ] ) {
  printf( 'Data confirmation for user %s <br>', $userid );
  printf( '  Address identification: %d <br>', $addressid );
  printf( '  Request Identification: %d <br>', $reqid );
  printf( '  Payment type: %s<br>', $formaPagamento );
}

$nUser = searchByUserid( $dbusercat, $matUser, $matAddr, $matComp, "", $userid, 1 );
$nAddr = searchAddrById( $dbusercat, $matAddr, $addressid );

// Store all items in cart into local variables
$sql   = "SELECT * FROM RequestItem WHERE ReqId='$reqid'";
$dbcat->query($sql) or $dbcat->error ($sql);
$itens = $dbcat->numRows();
$count = 0;
while( $row = $dbcat->fetchRow() )
{
  $scenes[$count] = $row[ "SceneId" ];
  $media[$count]  = $row[ "Media" ];
  $price[$count]  = $row[ "Price" ]; 
  $id[$count++]   = $row[ "Id" ];
}
$dbcat->freeResult($result);

// Draw table header
?>

<center>
<h2><?=$strPaymentConfirmation.$formaPagamento?></h2>
<h3><?=$strPaymentDescription?></h3>
</center>

<form name="productForm" method="POST">
  <table width="100%" cellpadding="4">
  <thead>
    <tr>
      <th><?=$strItem?></th>
      <th><?=$strInfo?></th>
      <th><?=$strMedia?></th>
      <th><?=$strPrice?></th>
    </tr>
  </thead>
  <tbody>


<?
// Draw table contents
$totalPrice = 0;
for( $i = 0; $i < $itens; $i++ ) {
  $sql = "SELECT * FROM Scene WHERE SceneId='".$scenes[ $i ]."'";
  $dbcat->query( $sql ) or $dbcat->error( $sql );
  $rowScene = $dbcat->fetchRow();
  ?>
    <tr>
      <td align="center"><?=$i + 1?></td>
      <td>
	<table>
	<tr><td align="right"><?=$strSatellite?></td><td></td><td><?=$rowScene["Satellite"]?></td>
	<tr><td align="right"><?=$strInstrument?></td><td></td><td><?=$rowScene["Sensor"]?></td>
	<tr><td align="right"><?=$strPath?></td><td></td><td><?=$rowScene["Path"]?></td>
	<tr><td align="right"><?=$strRow?></td><td></td><td><?=$rowScene["Row"]?></td>
	<tr><td align="right"><?=$strDate?></td><td></td><td><?=$rowScene["Date"]?></td>
	</table>
      </td>
      <td align="center"><?=$media[ $i ]?></td>
      <td align="center">R$ <?=$price[ $i ]?>,00</td>
    </tr>
  <?
  $totalPrice += $price[ $i ];
  $dbcat->freeResult( $result );
}

// Draw table footer, i. e., total price
echo "<tr><td align=\"center\">TOTAL</td><td colspan=\"2\"></td><td align=\"center\">R$&nbsp;
$totalPrice,00</td></tr>\n";
echo "</tbody>\n</table>\n</form>\n<br><br>";
echo '<h3>'.$strPaymentAddress.'</h3>';

  $matAddr[0]->street     = trim( $matAddr[0]->street );
  $matAddr[0]->number     = trim( $matAddr[0]->number );
  $matAddr[0]->complement = trim( $matAddr[0]->complement );
  $matAddr[0]->city       = trim( $matAddr[0]->city );
  $complement = ($matAddr[0]->complement != "") ? " - " . $matAddr[0]->complement : "";

  $fullAddress  = $matAddr[0]->street.';'.$matAddr[0]->number.';'.$complement;
  $fullAddress .= ';'.$matAddr[0]->city.';'.$matAddr[0]->state.';';
  $fullAddress .= $matAddr[0]->cep.';'.$matAddr[0]->country;
?>
  <table width="100%" align= "center"> 
    <tr>
      <td><?=$matUser[0]->fullname?></td>
    </tr>
    <tr>
      <td><?=$matAddr[0]->street.", ".$matAddr[0]->number.$complement?></td>
    </tr>
    <tr>
      <td><?=$matAddr[0]->city.", ".$matAddr[0]->state." - ".$matAddr[0]->cep?></td>
    </tr>
    <tr>
      <td><?=$matAddr[0]->country?></td>
  </table>
<?

// Finally, draw submit button for appropriated payment method
$pay = new Payment();
$pay->setOrderId( $reqid );
$pay->setChargedPrice( $totalPrice );
$pay->setName( $matUser[0]->fullname );
$pay->setAddress( $fullAddress );
$pay->setCGCCPF( $matUser[0]->CNPJ_CPF );
$pay->setPaymentMethod( $formaPagamento );
echo "\n<center>".$pay->generateForm()."</center>\n";

$sql  = "UPDATE RequestPayment SET paymentType='".$formaPagamento."' ";
$sql .= "WHERE ReqId=".$reqid;
log_msg( $sql );

$dbcat = $GLOBALS["dbcatalog"]; 
if( !$dbcat->query( $sql ) ) {
  log_msg( $dbcat->error( $sql ) );
  die( $dbcat->error( $sql ) );
}

?>
  </body>
</html>
