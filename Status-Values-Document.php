<?
#
# Documentaзгo dos valores de Status para os Pedidos e seus Itens.
#
$matReqStatusInt = array(6,  'A', 'B', 'C', 'D', 'Z', 'Y');  // Cуdigos dos Pedidos

$matReqStatus= array(6, "Aguardando Autoriza&ccedil;&atilde;o", "Aberto", "Produzido", "Fechado", "Aguardando Produ&ccedil;&atilde;o", "Problema"); // Semantica dos Cуdigos dos Pedidos


$matReqItemStatusInt = array(9,  'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W'); // Cуdigos dos Itens

$matReqItemStatus = array(9,  "Aguardando Autoriza&ccedil;&atilde;o", "Aguardando Produ&ccedil;&atilde;o", "Em Produ&ccedil;&atilde;o", "Aguardando Grava&ccedil;&atilde;o Mнdia", "Aguardando Despacho", "Despachado", "Rejeitado", "Falha na Produ&ccedil;&atilde;o", "Problema na Produ&ccedil;&atilde;o"); // Semantica dos Cуdigos dos Itens

/*

. Convenзгo de valores de Status para um pedido (Request).

  УAФ Ц Aguardando Autorizaзгo : O pedido (Request) contйm itens tarifados e/ou sujeitos a Controle de Qualidade.
  УBФ Ц Aberto : O pedido (Request) contйm itens ainda nгo produzidos.
  УCФ Ц Produzido : Os itens do pedido (Request) foram todos produzidos.
  УDФ Ц Fechado : O pedido (Request) foi produzido e aguarda despacho.
  УZФ Ц Aguardando Produзгo : O pedido (Request) nгo entrou ainda em produзгo.


. Convenзгo de valores de Status para um item (RequestItem) de um pedido (Request).

  УAФ Ц Aguardando Autorizaзгo : O item (RequestItem) й tarifados e/ou sujeito a Controle de Qualidade.
  УBФ Ц Aguardando Produзгo : O item (RequestItem) esta aguardando (disponibilidade de processamento) produзгo.
  УCФ Ц Em produзгo : O item (RequestItem) encontra-se em produзгo (sendo processado).
  УDФ Ц Aguardando Gravaзгo : O item (RequestItem) aguarda providкncias para gravaзгo em mнdia.
  УEФ Ц Aguardando Despacho : O item (RequestItem) foi produzido e aguarda despacho (E-mail para o usuбrio solicitante contendo o link respectivo para download).
  УFФ Ц Despachado : O item (RequestItem) foi produzido e despachado (E-mail para o usuбrio solicitante contendo o link para download jб foi enviado).
  УYФ Ц Rejeitado : O item (RequestItem) apresenta algum problema (e. g. rejeiзгo do Controle de Qualidade) que compromete sua integridade.
  УZФ Ц Falha na Produзгo : A produзгo do item (RequestItem) foi comprometida (interrompida)  por falha em sua produзгo (problemas no processamento).
  УWФ Ц Problema na Produзгo : O sistema de produзгo encontra-se incapacitado para o atendimento ao processamento do item (RequestItem). 

*/

?>