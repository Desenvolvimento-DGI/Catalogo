<?
#
# Documenta��o dos valores de Status para os Pedidos e seus Itens.
#
$matReqStatusInt = array(6,  'A', 'B', 'C', 'D', 'Z', 'Y');  // C�digos dos Pedidos

$matReqStatus= array(6, "Aguardando Autoriza&ccedil;&atilde;o", "Aberto", "Produzido", "Fechado", "Aguardando Produ&ccedil;&atilde;o", "Problema"); // Semantica dos C�digos dos Pedidos


$matReqItemStatusInt = array(9,  'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W'); // C�digos dos Itens

$matReqItemStatus = array(9,  "Aguardando Autoriza&ccedil;&atilde;o", "Aguardando Produ&ccedil;&atilde;o", "Em Produ&ccedil;&atilde;o", "Aguardando Grava&ccedil;&atilde;o M�dia", "Aguardando Despacho", "Despachado", "Rejeitado", "Falha na Produ&ccedil;&atilde;o", "Problema na Produ&ccedil;&atilde;o"); // Semantica dos C�digos dos Itens

/*

. Conven��o de valores de Status para um pedido (Request).

  �A� � Aguardando Autoriza��o : O pedido (Request) cont�m itens tarifados e/ou sujeitos a Controle de Qualidade.
  �B� � Aberto : O pedido (Request) cont�m itens ainda n�o produzidos.
  �C� � Produzido : Os itens do pedido (Request) foram todos produzidos.
  �D� � Fechado : O pedido (Request) foi produzido e aguarda despacho.
  �Z� � Aguardando Produ��o : O pedido (Request) n�o entrou ainda em produ��o.


. Conven��o de valores de Status para um item (RequestItem) de um pedido (Request).

  �A� � Aguardando Autoriza��o : O item (RequestItem) � tarifados e/ou sujeito a Controle de Qualidade.
  �B� � Aguardando Produ��o : O item (RequestItem) esta aguardando (disponibilidade de processamento) produ��o.
  �C� � Em produ��o : O item (RequestItem) encontra-se em produ��o (sendo processado).
  �D� � Aguardando Grava��o : O item (RequestItem) aguarda provid�ncias para grava��o em m�dia.
  �E� � Aguardando Despacho : O item (RequestItem) foi produzido e aguarda despacho (E-mail para o usu�rio solicitante contendo o link respectivo para download).
  �F� � Despachado : O item (RequestItem) foi produzido e despachado (E-mail para o usu�rio solicitante contendo o link para download j� foi enviado).
  �Y� � Rejeitado : O item (RequestItem) apresenta algum problema (e. g. rejei��o do Controle de Qualidade) que compromete sua integridade.
  �Z� � Falha na Produ��o : A produ��o do item (RequestItem) foi comprometida (interrompida)  por falha em sua produ��o (problemas no processamento).
  �W� � Problema na Produ��o : O sistema de produ��o encontra-se incapacitado para o atendimento ao processamento do item (RequestItem). 

*/

?>