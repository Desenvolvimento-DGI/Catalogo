<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Globals
 

include("globals.php");
include_once("request.class.php");
import_request_variables("gpc");

log_msg("Calling request dispatcher for id $reqId");

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbman = $GLOBALS["dbmanager"];
echo "Request- Dispatcher = Man " . $GLOBALS["dbmanager"] . " Cat = "
                              . $GLOBALS["dbcatalog"] . "\n";
log_msg("Request- Dispatcher = Man " . $GLOBALS["dbmanager"] . " Cat = "
                              . $GLOBALS["dbcatalog"] . "\n");

// Openning log file
$logFile = $GLOBALS["systemLog"] . "requestDispatcher.log";
echo "Log = " . $logFile;

if (!($lf = fopen($logFile, "a"))) die ('N<E3>o foi possivel criar o arquivo de log');
$message = Date("d/m/Y H:m:s") . " - <Despachando para execução o pedido n. " . $reqId;
if(!$itemId)
   $message .= ">\r\n";
else
   $message .= "  - item n. " . $itemId . ">\r\n";
fwrite($lf, $message);

// Processsing the RequestItem or the entire Request
$nReq = searchReqByNumber($dbcat, $matReq, 1, 'B', "", $reqId);
$nReqQ = searchReqByNumber($dbcat, $matReq, 1, 'Q', "", $reqId); // Quality Control test for priced items
$nReq = $nReq + $nReqQ;

if($nReq)
{ 
   if($itemId)
   {
      $objReqIt = new RequestItem($dbcat);
      $objReqIt = $matReq[0]->getItemByNumber($nItem);
    
      if($objReqIt->getItemStatus() == 'B'or $objReqIt->getItemStatus() == 'Q')
         if(!$objReqIt->execute($matReq[0]->userId))
         {
               $message = Date("d/m/Y H:m:s") . " - <ERRO - Erro executando o Item do Pedido >\r\n";
               fwrite($lf, $message);
         }
   } else
   {
      //Request Itens - Status 'B' - waiting for production
      for($i=0; $i < $matReq[0]->nItens; $i++)
      {
         if($matReq[0]->itens[$i]->getItemStatus() == 'B' or $matReq[0]->itens[$i]->getItemStatus() == 'Q')
         {    
            $result = ($matReq[0]->itens[$i]->execute($matReq[0]->userId));
            if($result == 1) 
            {  
               $message = Date("d/m/Y H:m:s") . " - <ERRO - Executando o Pedido >\r\n";
               fwrite($lf, $message);
            }
					} 
      }
   }
};
/*  
$nReq = searchReqByNumber($dbcat, $matReq, 1, 'A', "", $reqId);
$user = $matReq[0]->userId;
$number_items = $matReq[0]->nItens;

for ($i=0; $i < $number_items; $i++) 
{
  $status = $matReq[0]->itens[$i]->status;
  if ($status == "A")
  {
    $nItem = $matReq[0]->itens[$i]->numItem;
	  $register = date("Y-m-d H:i:s");
	  $sql = "INSERT INTO modulestatus SET
			    module = '',
			    argument = 'Usuário $user : Item $nItem do Pedido $reqId AGUARDANDO AUTORIZAÇÃO ! ($user;$nItem)',
			    register = '$register',
			    dependid = 0,
			    host = '',
			    status = 'AUTHORIZE',
			    message = ''";
	  $dbman->query($sql);
	};
};
*/
// Close Log file
fclose ($lf);
?>
