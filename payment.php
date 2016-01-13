<?php
printf( '<html>%c', 13 );
printf( '  <head>%c', 13 );
printf( '    <title>Forma de Pagamento</title>%c', 13 );
include( "css.php" );
printf( '  </head>' );
printf( '  <body>' );

echo "This spans\nmultiple lines. The newlines will be\noutput as well.";

printf( '    <form name="paymentChooseForm" method="POST" action="paymentConfirmation.php">' );
printf( '      <h3 align="center">'.'Escolha forma de pagamento'.'<h3>' );
printf( '      <table align="center">' );
printf( '      <tr>' );
printf( '        <td width="20" align="center"><input type="radio" Checked value="Boleto" name="formaPagamento"></td>' );
printf( '        <td>Boleto Bancario</td>%c', 13 );
printf( '      </tr>%c', 13 );
printf( '      <tr>%c', 13 );
printf( '        <td width="20" align="center"><input type="radio" value="Visa" name="formaPagamento"></td>%c', 13 );
printf( '        <td>Visa</td>%c', 13 );
printf( '      </tr>%c', 13 );
printf( '      <tr>%c', 13 );
printf( '        <td width="20" align="center"><input type="radio" value="Mastercard" name="formaPagamento"></td>%c', 13 );
printf( '        <td>Mastercard</td>%c', 13 );
printf( '      </tr>%c', 13 );
printf( '      </table>' );
printf( '   <h3>%s<h3> ', '' );
printf( '   <table align="center"> ' );
printf( '   <tr> ' );
printf( '      <td><input type="submit" value="%s" name="action" ></td>', 'Continuar' );
printf( '      <td><input type="submit" value="%s" name="action"></td>', 'Voltar' );
printf( '      <td><input type="hidden" value="%s" name="userid" ></td>', 'fduarte' );
printf( '   </tr>' );
printf( '   </table> ' );
printf( '    </form>%c', 13 );
printf( '  </body>%c', 13 );
printf( '</html>%c', 13 );
?>
