<?php
include_once("globals.php"); 
import_request_variables("gpc");
include_once("request.class.php");
include_once("p2u.class.php");

#
# Parameter $ReqId comes from paymentManager.php which executes a Wget call to requestAuthorize.php 
# to provide the proper suite execution for the requested items, that were pending on payment confirmation. 
#

   $dbcat = $GLOBALS["dbcatalog"];
   $objReq = new Request($dbcat);
   searchReqComp($dbcat, $objReq, $ReqId);
   $objReq->authorize(); 

?>
