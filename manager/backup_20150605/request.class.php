<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : November/2003
//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
log_msg("entrei no request.class");


include_once ("tiffFunctions.php");
include_once ("gralhaFunctions.php"); 
include_once("media.class.php");
include_once("globalsFunctions.php");
include_once("g2t.class.php");
include_once("p2u.class.php");
include_once("p2c.class.php");
include_once("m2c.class.php"); 
include_once("m2d.class.php");
include_once("d2g.class.php");
include_once("h2t.class.php");
include_once("c2h.class.php");
include_once("drdFunctions.php"); 


log_msg("terminei import do request.class"); 
//------------------------------------------------------------------------------
// Class Request
//------------------------------------------------------------------------------
class Request
{
   var $reqId;
   var $userId;
   var $reqDate;
   var $statusDate;
   var $payDate;
   var $delDate;
   var $priority;
   var $nItens;
   var $nItensDone;
   var $itens;
   var $total;
   var $status;
   var $operator;
   var $addressId;
   var $bd;
   var $reqIp;
   var $reqCountry;

   function Request($bd)
   {
      $this->reqId = "";
      $this->userId = "";
      $this->reqDate = 0;
      $this->statusDate = 0;
      $this->payDate = 0;
      $this->delDate = 0;
      $this->priority = 0;
      $this->nItens = 0;
      $this->nItensDone = 0;
      $this->total = 0;
      $this->operator="";
      $this->addressId=0;
	    $this->bd = $bd;
	    $this->reqIp = "";
	    $this->reqCountry = "";
   }

   function fill($parUser, $parDate=0, $parStatusDate=0,  $parPayDate=0,
      $pardelDate=0, $parPri = 0, $parOperator = "", $parAddress=0, $parIp = "",$parCountry = "")
   {
      // Filling attributes
      $this->userId = $parUser;
      if($parDate != 0) $this->reqDate = $parDate;
      if($parStatusDate != 0) $this->statusDate = $parStatusDate;
      if($parPayDate != 0) $this->payDate = $parPayDate;
      if($pardelDate != 0) $this->delDate = $pardelDate;
      if($parPri != 0) $this->priority = $parPri;
      if(trim($parOperator) != "") $this->operator = $parOperator;
      if($parAddress != 0) $this->addressId = $parAddress;
      $this->reqIp = $parIp;
      $this->reqCountry = $parCountry;
      $this->total = 0;
   }
      
   function insert()
   {
   
      // Make the INSERT query statement
      $sql  = "INSERT INTO Request ";
      $sql  .= "(UserId, ReqDate, StatusDate, PayDate, DelDate, ";
      $sql  .= "Priority, Operator, addressId, Ip, Country) VALUES ('";
		$sql  .= $this->userId . "', '" .  $this->reqDate . "', '";
		$sql  .= $this->statusDate . "', '" .  $this->payDate . "', '" . $this->delDate . "', '";
		$sql  .= $this->priority . "', '" . $this->operator . "', '" . $this->addressId . "', '";
		$sql  .= $this->reqIp . "', '" . $this->reqCountry . "')";

      // Query
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      $this->reqId = $this->bd->insertId();
      return $this->reqId;
   }

   function modify($parReqId=0)
   {

      // Verifying Parameter
      if(!$parReqId)
         $parReqId = $this->reqId;
   
      // Make the UPDATE query statement
      $sql  = "UPDATE Request SET ";
      $sql  .= "UserId = '" . $this->userId . "',";
      $sql  .= "ReqDate = '" .  $this->reqDate . "',";
      $sql  .= "StatusDate = '" .  $this->statusDate . "',";
      $sql  .= "PayDate = '" . $this->payDate . "', ";
      $sql  .= "DelDate = '" . $this->delDate . "',";
      $sql  .= "Priority = " .  $this->priority;
      $sql  .= "Operator = '" . $this->operator . "',";
      $sql  .= "addressId = " .  $this->addressId;
      $sql  .= " WHERE ReqId = " .  $parReqId ;
      
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

   function removeItem($numItem) 
   {
      for($i=0; $i < $this->nItens; $i++)
         if($this->itens[$i]->numItem == $numItem)
         {
            $auxStatus = $this->itens[$i]->status;
            $this->itens[$i]->remove();
         }
         
      $this->nItens--;
      if( $auxStatus == 'F') $this->nItensDone--;
      //$this->modify();
   }


   function removeParc($parReqId=0)
   {
      // Remove the current Request
      if(!$parReqId) $parReqId = $this->reqId;
   
      $sql  = "DELETE FROM Request WHERE ReqId = " .  $parReqId ;
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

   function removeComp($parReqId=0)
   {
      // Using parameter, you have to fill all itens fields
      if($parReqId)
         if(!$this->searchComp($parRegId)) return 0;
   
      // Remove the current Request
      if(!$parReqId) $parReqId = $this->reqId;

      // Remove request itens
      for($i = 0; $i < $this->nItens; $i++)
         if(!$this->itens[$i]->remove()) return 0;

      $sql  = "DELETE FROM Request WHERE ReqId = " .  $parReqId ;
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      return true;
   }

   function search($parReqId)
   {
      $sql = "SELECT * FROM Request WHERE ReqId = $parReqId";
      $this->bd->query($sql) or $this->bd->error($sql);
      $nReq = $this->bd->numRows();
      
      if(!$nReq)
      {
         echo "<br>Nenhum pedido encontrado!</br>";
         return false;
      }

      $row_array = $this->bd->fetchRow();
      $this->fillAttrib($row_array);
      return true;
   }
   
   function searchAll(&$nReq, $parUser="", $parDataI="", $parDataE="", $parStat="")
   {

      // Mounting the SQL
      $sql = "SELECT * FROM Request WHERE UserId LIKE '$parUser'";
      if(trim($parDataI) != "") $sql .= " and  ReqDate > '" . $parDataI . "' and  ReqDate < '" . $parDataE . "'";
      if(trim($parStat) != "")
      {
         if($parStat == "open")
            $sql .= " and NItem > NItemDone";
         else
            if($parStat == "closed") $sql .= " and NItem <= NItemDone";
      }

      // Order
      $sql .= " ORDER BY ReqId";
      
      // Query
      $this->bd->query($sql) or $this->bd->error($sql);
      $nReq = $this->bd->numRows();

      if(!$nReq)
      {
         echo "<br>Nenhum pedido encontrado!</br>";
         return false;
      }

      for($i = 0; $i < $nReq; $i++)
         $row_array[$i] = $this->bd->fetchRow($i);

      return $row_array;
   }

   function searchComp($parReqId)
   {
      // Seacrh the request
       if(!$this->search($parReqId))
         return false;

      // Search all itens for this request
      
      for($i = 0; $i < $this->nItens; $i++)
      {
         $this->itens[$i] = new RequestItem($this->bd);
         $this->itens[$i]->search($i, $parReqId);
         $this->total += $this->itens[$i]->price;
       }

   }
   	
   function fillAttrib($row)
   {
   	// Filling attributes from the Data Base
      $this->reqId = $row["ReqId"];
      $this->userId = $row["UserId"];
      $this->reqDate = $row["ReqDate"];
      $this->statusDate = $row["StatusDate"];
      $this->payDate = $row["PayDate"];
      $this->delDate = $row["DelDate"];
      $this->priority = $row["Priority"];
      $this->operator = $row["Operator"];
      $this->addressId = $row["addressId"];
      $this->reqIp = $row["Ip"];
      $this->reqCountry = $row["Country"];
   }
   
   function getItens()
   {
      $this->total = 0;
      $sql = "SELECT * FROM RequestItem WHERE ReqId = '" . $this->reqId . "'";
      $this->bd->query($sql) or $this->bd->error($sql);
      $this->nItens = $this->bd->numRows();

      if(!$this->nItens) return false;
      for($i=0; $i < $this->nItens; $i++)
      {
         $row = $this->bd->fetchRow();
         $this->itens[$i] = new RequestItem($this->bd);
         $this->itens[$i]->fillAttrib($row);
         $this->total +=$this->itens[$i]->price;
      }

      // C�culo dos intens prontos
      $this->setRequestStatus();
      $this->nItensDone = $this->searchItemStatus('E')  + $this->searchItemStatus('F');
   }

   //
   function searchItemStatus($status)
   {
      $item = 0;
      for($i = 0; $i < $this->nItens; $i++)
         if($this->itens[$i]->status == $status) $item++;
      return $item;
   }
   
   function setRequestStatus()
   {

      // Setting Request Status
      $this->status = 'B';
      $minutos = 5;
      $nAutorizacao = $nPronto = $nDespachado = $nProb = $nNaoProduzido = 0;
      for($i=0; $i < $this->nItens; $i++)
      {
         switch ($this->itens[$i]->status)
         {
            case 'A': $nAutorizacao++; break;
            case 'E': $nPronto++; break;
            case 'F': $nDespachado++; break;
            case 'Y': $nProb++; break;
            case 'Z': $nProb++; break;
            case 'B': if((date( "Y-m-d H:i:s") - $this->itens[$i]->statusDate) > $minutos*60)
                      {
                           $nNaoProduzido++;
                           $this->itens[$i]->updateStatus('W');
                      }
                      break;
            case 'W': $nNaoProduzido++;
         }
      }

      if(($nPronto + $nDespachado) == $this->nItens) $this->status = 'C';
      if($nDespachado == $this->nItens) $this->status = 'D';
      if($nAutorizacao) $this->status = 'A';
      if($nProb) $this->status = 'Y';
      if($nNaoProduzido) $this->status = 'Z';
   }
   // ??
   function getTotal()
   {
      $this->total=0;

      // Get iten's price
      for($i = 0; $i < $this->nItens; $i++)
         $this->total +=$this->itens[$i]->price();

   }
   
   function woGeneration()
   {
      // Generate one work order for each item
      for($i = 0; $i < $this->nItens; $i++)
         if(!$this->itens[$i]->woGeneration());
   }
   
   function addItem($parNitens=1)
   {
      $this->nItens+=$parNitens;
   }
   
   function subItem($parNitens=1)
   {
      $this->nItens-=$parNitens;
   }

   function authorize($numItem=0)
   {
      if($numItem>0)
      {
         for($i=0; $i < $this->nItens; $i++)
            if($this->itens[$i]->numItem == $numItem)
            {
               $this->itens[$i]->authorize($this->userId);
               break;
            }
      }
      else
         for($i=0; $i < $this->nItens; $i++) 
            $this->itens[$i]->authorize($this->userId);
      $this->setRequestStatus();
   }
   
   function dispatch($numItem=0)
   {
      if($numItem>0)
      {
         for($i=0; $i < $this->nItens; $i++)
            if($this->itens[$i]->numItem == $numItem)
            {
               $this->itens[$i]->dispatch();
               break;
            }
      }
      else
         for($i=0; $i < $this->nItens; $i++)
            $this->itens[$i]->dispatch();
      $this->setRequestStatus();
   }

   function reProduct($numItem=0)
   {
      if($numItem>0)
      {
         for($i=0; $i < $this->nItens; $i++)
            if($this->itens[$i]->numItem == $numItem)
            {
               $this->itens[$i]->reProduct($this->userId);
               break;
            }
      }
      else
         for($i=0; $i < $this->nItens; $i++)
            $this->itens[$i]->reProduct($this->userId);
      $this->setRequestStatus();
   }

   function getItemByNumber($nItem)
   {
      for($i=0; $i < $this->nItens; $i++)
      {
         if($nItem == $this->itens[$i]->numItem)
            return $this->itens[$i];
      }
   }
   function show()
   {

      echo "<br> REQUEST  -> ";
      echo $this->userId . " - ";
      echo $this->reqDate . " - ";
      echo $this->statusDate . " - ";
      echo $this->payDate . " - ";
      echo $this->delDate . " - ";
      echo $this->priority . " - ";
      echo $this->nItens . " - ";
      echo $this->nItensDone . " - ";
      echo $this->itens . " - ";
      echo $this->total . " - ";
      echo $this->status . " - ";
      echo $this->operator . " - ";
      echo $this->addressId . " - ";
      echo $this->reqIp . " - " ;
      echo $this->reqCountry . "<br>";
   }
};
//------------------------------------------------------------------------------
// Class RequestItem
//------------------------------------------------------------------------------
class RequestItem
{
   var $numItem;
   var $reqId;
   var $sceneId;
   var $productId;
   var $bands;
   var $sceneShift;
   var $correctionLevel;
   var $orientation;
   var $resampling;
   var $datum;
   var $projection;
   var $longOrigin;
   var $latOrigin;
   var $stdLat1;
   var $stdLat2;
   var $format;
   var $interleaving;
   var $media;
   var $price;
   var $prodDate;
   var $status;
   var $statusDate;
   var $restoration;
 	 var $bd;
 	
   function RequestItem($parbd)
   {
      $this->numItem = 0;
      $this->reqId = 0;
      $this->sceneId = "";
      $this->productId = "";
      $this->bands = "";
      $this->sceneShift = 0;
      $this->correctionLevel = "";
      $this->orientation = "";
      $this->resampling = "";
      $this->datum = "";
      $this->projection = "";
      $this->longOrigin = 0;
      $this->latOrigin = 0;
      $this->stdLat1 = 0;
      $this->stdLat2 = 0;
      $this->format =  "";
      $this->interleaving = "";
      $this->media = "";
      $this->price = "";
      $this->prodDate = 0;
      $this->status = "";
      $this->statusDate=0;
      $this->restoration = "";
	    $this->bd = $parbd;
   }
   function fill($parReqId, $parScene = "", $parProd="", $parBand="", $parShift=0,
                 $parCor = "", $parOri="", $parRes="", $parDatum="", $parProj="",
                 $parLong=0, $parLat=0, $parStd1=0, $parStd2=0, $parFormat="",
                 $parInter=0, $parMedia="", $parPrice="",  $parDate=0, $parStatus='', $parStatusDate=0,$parRestoration="")
   {
   	// Filling requestItem attributes with parameters
      $this->reqId = $parReqId;
      $this->sceneId = $parScene;
      $this->productId = $parProd;
      $this->bands = $parBand;
      $this->sceneShift = $parShift;
      $this->correctionLevel = $parCor;
      $this->orientation = $parOri;
      $this->resampling = $parRes;
      $this->datum = $parDatum;
      $this->projection = $parProj;
      $this->longOrigin = $parLong;
      $this->latOrigin = $parLat;
      $this->stdLat1 = $parStd1;
      $this->stdLat2 = $parStd2;
      $this->format = $parFormat;
      $this->interleaving = $parInter;
      $this->media = $parMedia;
      $this->price = $parPrice;
      $this->prodDate = $parDate;
      $this->status = $parStatus;
      $this->statusDate=$parStatusDate;
      $this->restoration=$parRestoration;
   }
   function insert()
   {
      // Get today   (?)
      $this->prodDate = date( "Y-m-d H:i:s" );

      // Builds the query statement
      $sql  = "INSERT INTO RequestItem ";
      $sql .= "(ReqId, SceneId, ProductId, Bands, SceneShift,";
      $sql .= " CorrectionLevel, Orientation, Resampling, Datum, Projection,";
      $sql .= " LongitudeOrigin, LatitudeOrigin, StandardLatitude1, StandardLatitude2, Format,";
      $sql .= " Interleaving, Media, Price, Prodate, Status, StatusDate, Restoration) VALUES (";
      $sql .= $this->reqId . ", '" . $this->sceneId ."', '";
      $sql .= $this->productId .  "', '" .  $this->bands . "', ";
      $sql .= $this->sceneShift .  ", '" .  $this->correctionLevel . "', '";
      $sql .= $this->orientation . "', '" . $this->resampling . "', '";
      $sql .= $this->datum  .  "', '" . $this->projection .  "', ";
      $sql .= $this->longOrigin . ", " . $this->latOrigin . ", ";
      $sql .= $this->stdLat1 . ", " . $this->stdLat2 . ", '";
      $sql .= $this->format . "', '" . $this->interleaving . "', '";
      $sql .= $this->media . "', " . $this->price . ", '";
      $sql .= $this->prodDate . "', '" . $this->status . "', '" . $this->statusDate . "', '" . $this->restoration . "')";

      // Does the quer y
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      
      return true;
   }

   function modify($parNum=0,$parReqId=0)
   {
      if($parNum==0) $parNum = $this->numItem;
      if($parReqId == 0)  $parReqId = $this->reqId;

      // Search request
      $sql  = "UPDATE RequestItem SET ";
      $sql  .= "SceneId = '" . $this->sceneId . "', ";
      $sql  .= "ProductId = '" . $this->productId . "', ";
      $sql  .= "Bands = '" .  $this->bands . "', ";
      $sql  .= "SceneShift = " . $this->sceneShift . ", ";
      $sql  .= "CorrectionLevel = '" . $this->correctionLevel . "', ";
      $sql  .= "Orientation = '" .  $this->orientation . "',";
      $sql  .= "Resampling = '" .  $this->resampling . "', ";
      $sql  .= "Datum = '" .  $this->datum . "', ";
      $sql  .= "Projection = '" . $this->projection . "', ";
      $sql  .= "LongitudeOrigin = " .  $this->longOrigin . ", ";
      $sql  .= "LatitudeOrigin = " .  $this->latOrigin . ", ";
      $sql  .= "StandardLatitude1 = " . $this->stdLat1 . ", ";
      $sql  .= "StandardLatitude1 = " . $this->stdLat2 . ",";
      $sql  .= "Format = '" .  $this->format . "', ";
      $sql  .= "Interleaving = '" .  $this->interleaving . "', ";
      $sql  .= "Media = '" . $this->media . "', ";
      $sql  .= "Price = " .  $this->price . ", ";
      $sql  .= "Prodate = '" .  $this->prodDate . "', ";
      $sql  .= "Status = '" .  $this->status . "', ";
      $sql  .= "StatusDate = '" .  $this->statusDate . "', ";
      $sql  .= "Restoration = ' " . $this->restoration . "'";
      $sql  .= " WHERE NumItem = " . $parNum . " and ReqId = " .  $parReqId;

      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
            
   }


   function remove($parNum=0,$parReqId=0)
   {
      // Remove the current Request
      if(!$parReqId) $parReqId = $this->reqId;
      if(!$parNum) $parNum = $this->numItem;

      // Building SQL statement
      $sql  = "DELETE FROM RequestItem WHERE NumItem = " . $parNum;
      $sql .= " and ReqId = " .  $parReqId ;
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;

   }

   function search($parNum=0,$parReqId=0)
   {
      $sql = "SELECT * FROM RequestItem WHERE ReqId = $parReqId";
      $this->bd->query($sql) or $this->bd->error($sql);
      $nReq = $this->bd->numRows();

      if(!$nReq)
      {
         echo "\n Nenhum item de pedido encontrado! \n";
         return 0;
      }

      $row_array = $this->bd->fetchRow($parNum);
      $this->fillAttrib($row_array);
   }

 function searchItem($parItem=0)
   {
      $sql = "SELECT * FROM RequestItem WHERE NumItem = $parItem";
      $this->bd->query($sql) or $this->bd->error($sql);
      $nReq = $this->bd->numRows();

      if(!$nReq)
      {
         echo "\n Nenhum item de pedido encontrado! \n";
         return 0;
      }

      $row_array = $this->bd->fetchRow($nReq);
      $this->fillAttrib($row_array);
   }
   	
   function fillAttrib($row)
   {
   	// Filling requestItem attributes with Data Base field
   	$this->numItem = $row["NumItem"];
      $this->reqId = $row["ReqId"];
      $this->sceneId = $row["SceneId"];
      $this->productId = $row["ProductId"];
      $this->bands = $row["Bands"];
      $this->sceneShift = $row["SceneShift"];
      $this->correctionLevel = $row["CorrectionLevel"];
      $this->orientation = $row["Orientation"];
      $this->resampling = $row["Resampling"];
      $this->datum = $row["Datum"];
      $this->projection = $row["Projection"];
      $this->longOrigin = $row["LongitudeOrigin"];
      $this->latOrigin = $row["LatitudeOrigin"];
      $this->stdLat1 = $row["StandardLatitude1"];
      $this->stdLat2 = $row["StandardLatitude2"];
      $this->format = $row["Format"];
      $this->interleaving = $row["Interleaving"];
      $this->media = $row["Media"];
      $this->price = $row["Price"];
      $this->prodDate = $row["Prodate"];
      $this->status = $row["Status"];
      $this->statusDate = $row["StatusDate"];
      $this->restoration = $row["Restoration"];
   }

   function updateStatus($parStatus=0)
   { 
      if($parStatus)
         $this->status = $parStatus;
      else
         $this->status++;
      $this->statusDate = date( "Y-m-d H:i:s" );
      $this->modify();
	 }
   
   function authorize($parUserId)
   {  
      if($this->status=='A') $this->updateStatus();
      $this->execute($parUserId);
   }
   
   function dispatch()
   {
      if($this->status=='E') $this->updateStatus();
   }

   function reProduct($parUserId)
   {
      if($this->status=='W') $this->updateStatus('B');
      //$this->execute($parUserId);
   }

   function getItemStatus()
   {
      return $this->status;
   }


   function woGeneration()
   {   
      // Retrive scene data
      //$objScene = new Scene();
      //$objScene->search($this->sceneId);

      // Verify if order for product or order for production
      //if((trim($this->productId)=="")
      //{

   }
   function execute($parUserId)
   {      
      // Arrays

      require ("arrays.php");
   
      //Verify item status  

      if($this->status != 'B' and $this->status != 'Q') return 1;  

      // Flags
      $inproduction = true;
      
      // Searching Scene
      $sql = "SELECT * FROM Scene WHERE SceneId='". $this->sceneId ."'";
	    $this->bd->query($sql) or die("<br> Error on execution/result sql = $sql <br>");
	    $itens = $this->bd->numRows();
      if ($itens == 0) die("Erro nas Cenas");
   	  $row = $this->bd->fetchRow();
      $this->bd->freeResult($results);

      // Scene data
      $res = explode(".",$row["SceneId"]);
      $sufix = $res[1];
      $satCod = $row["Satellite"];
      $sensor = $row["Sensor"];
      
      if(($satCod == "P6" and ($sensor == "LIS3" or $sensor == "AWIF")) or $satCod == "UKDMC")
      {
       $date_IRS = $row["Date"];
       $path_IRS = $row["Path"];
       $row_IRS = $row["Row"];
      }
      
	  
	  
      for($i=0; $i<$matSatCod[0]; $i++) if($satCod == $matSatCod[$i]) break; 
      $satName = $matSatName[$i];
      $taName = $satName . "Scene";
	  
	  
	  // Incluído em 04/08/2014 para tratar das imagens do satélite NPP
	  // ==============================================================
	  if ( ( strtoupper($satCod) == "NPP" or  strtoupper($satCod) == "SNPP" ) and strtoupper($sensor) == "VIIRS" )
	  {
		  $satName = "Npp";
		  $taName = $satName . "Scene";		  
	  }
	  
	  
	  // Incluído em 15/08/2014 para tratar das imagens do satélite UKDMC
	  // ================================================================
	  if ( ( strtoupper($satCod) == "UKDMC" or  strtoupper($satCod) == "UKDMC2" ) and strtoupper($sensor) == "SLIM" )
	  {
		  $satName = "UKDMC";
		  $taName = $satName . "Scene";		  
	  }
	  
	  
	  
	  // Incluído em 26/08/2014 para tratar das imagens do satélite RAPIDEYE
	  // ===================================================================
	  if ( ( strtoupper($satCod) == "RE1" or  strtoupper($satCod) == "RE2" or  strtoupper($satCod) == "RE3" or  strtoupper($satCod) == "RE4" or  strtoupper($satCod) == "RE5" ) and strtoupper($sensor) == "REIS" )
	  {
		  $satName = "Rapideye";
		  $taName = $satName . "Scene";		  
	  }
	  


	  
	  // Incluído em 25/09/2014 para tratar das imagens do satélite RESOURCESAT2
	  // =======================================================================
	  if ( ( strtoupper($satCod) == "RS2" or  strtoupper($satCod) == "RES2" ) and ( strtoupper($sensor) == "LIS3" or strtoupper($sensor) == "AWIF") )
	  {
		  $satName = "RES2";
		  $taName = $satName . "Scene";		  
	  }
	  
	  

	  
	  
       
      if ($GLOBALS["stationDebug"]) echo "\n RequestClass - Satelite = " . $satName . " TabName = " . $taName . " UserName = " .
            $parUserId .  " SceneId = " . $this->sceneId . " \n";
   
      //Finding Gralha/Fred
     	$sql = "SELECT * FROM $taName WHERE SceneId='".$this->sceneId ."'";
      if ($GLOBALS["stationDebug"]) echo "RequestClass - Sql Command: ". $sql ."\n";
      $this->bd->query($sql);
		  $row = $this->bd->fetchRow() or die("<br> Error on execution/result sql = $sql <br>"); 
		  $file = $row["Gralha"];
		  $this->bd->freeResult();
		  if ($GLOBALS["stationDebug"]) echo "RequestClass - File = " . $file . "\n";
      
      // Creating p2u argument

      $argument = $parUserId .";". $this->reqId . ";".$this->sceneId.";".$file.";".$this->numItem.";".$this->restoration;
      $procPar = 0;	
      if ($GLOBALS["stationDebug"]) echo "RequestClass - Argument = " . $argument . "\n";   	
         
      // Instantiating P2 class
     
      searchMedia($this->bd, $objMedia, $this->media);
      $execClass = $objMedia[0]->getExecClass();
      if ($GLOBALS["stationDebug"]) echo "RequestClass - starting P2U or P2C = " . $execClass . "\n";

#
# Begining Segment for special dealing with annomalous products P6 and DMC satellites and GLS DataDet
#

      if(($satCod == "P6" and ($sensor == "LIS3" or $sensor == "AWIF")) or $satCod == "UKDMC")
      { 
       include_once ("listFiles.php");
       if($sensor == "LIS3") $sensor = "LISS3";  // That's for the STUPID modification done (renaming LIS3 for LISS3 and AWIF for AWIFS) at CDSR.
       else if($sensor == "AWIF") $sensor = "AWIFS"; 	 
       $dir_path = getTiffDir($satCod);  
       $res = explode("-", $date_IRS);
       $date_dir = $res[0] . "_" . $res[1];
       $date_aux = $res[0] . $res[1] . $res[2];
       $tiff_dir = $dir_path . "$date_dir/$satCod" . "_$sensor" . "_$date_aux/$path_IRS" . "_$row_IRS";
//       $tiffs = listFiles2($tiff_dir,"");
//       $cmd = "ls -1 $tiff_dir";
//       $res = shell_exec($cmd); 
//			 $tiffs = explode("\n",$res);  
       $aux_tiffs = glob($tiff_dir . "/*");
       $index = 0;
			 foreach ($aux_tiffs as $file)
			 {
				 $tiffs[$index] = basename($file);
				 $index ++;
			 };          
		   print_r ($tiffs);
       $argument = $parUserId .";". $this->reqId . ";".$this->sceneId.";".$tiff_dir.";".$this->numItem.";".$satCod;
       if ($GLOBALS["stationDebug"]) echo "RequestClass ====> starting P2U for P6 products - Argument = $argument \n";
       if ($GLOBALS["stationDebug"])echo "\n RequestClass =====> tifdir = $tiff_dir \n";
       $p2u = new p2u($argument);
      }
      else
      if($sufix == "GLS")
      {
       $sql = "SELECT Gralha FROM LandsatScene WHERE SceneId='". $this->sceneId ."'";
	     $this->bd->query($sql) or die("<br> Error on execution/result sql = $sql <br>");
	     $itens = $this->bd->numRows();
       if ($itens == 0) die("Erro nas Cenas");
   	   $row = $this->bd->fetchRow();
       $this->bd->freeResult($results);
       $gralha = $row["Gralha"];
 
       decodeGralha($gralha,$satellite,$number,$instrument,$channel_gralha,$type,$date,$path,$row); 
       
       if($number == 5) $sat_prefix = "LT5";
       else $sat_prefix = "LE7";
       $year = substr($date,0,4);
       $timestamp = strtotime($date);
       $julian_date = strftime('%j', $timestamp);
       $sql = "SELECT Dataset FROM Scene WHERE SceneId='".$this->sceneId ."'";
       if ($GLOBALS["stationDebug"]) echo "\n =====> RequestClass - Sql Command: ". $sql ."\n";
       $this->bd->query($sql);
		   $row_result = $this->bd->fetchRow() or die("\n =====> Request.Class - execute ====> Error on execution/result sql = $sql \n"); 
		   $dataset = $row_result["Dataset"];
		   $this->bd->freeResult();
       if($dataset == "GLS2000")  $target_tiffs = "*" . $path . "*" . $row . "*" . $date . "*.tar.gz"; // For GLS2000 tar.gz, file titles have a different format	(syntax) 
			 else $target_tiffs = "*" . $path . "*" . $row . "*" . $year . $julian_date . "*.tar.gz" ; 
       $dir_path = getTiffDir($sufix); 
       $tiff_dir = $dir_path . $satellite . $number ."/" ;
       $cmd= "find $tiff_dir -name $target_tiffs";
       $cmd = addslashes($cmd);
	   //if ($GLOBALS["stationDebug"]) echo "\n =====> COMANDO - RESULTADO: ". $cmd ."\n";
       echo "\n =====> Request.Class - execute ====> com = $cmd \n";
       $output =  shell_exec($cmd);
       $output = substr($output,0,strlen($output)-1);  
       if($output == "") die("\n =====> Request.Class - execute ====> No files : $target_tiffs !");
       $tiffs = explode("\n",$output);
       sort($tiffs);
 //      print_r($tiffs);
       $argument = $parUserId . ";" . $this->reqId . ";" . $this->sceneId . ";" . $tiffs[0] . ";" . $this->numItem . ";" . $sufix;
       $p2u = new p2u($argument);
 //      echo "\n<br> argument = $argument";
      } 
#
# End of Segment for annomalous products
#
      else
      {
       $p2u = new $execClass($argument);
       $tiffs = $p2u->getTiffs ();
      };
      
//      print_r($tiffs); 

      // For generation of a product
      
      if($sensor == "CCD")
      {
        $bands_all = array(0 => "5", 1 => "BAND1", 2 => "BAND2", 3 => "BAND3", 4 => "BAND4", 5 => "BAND5");
				$bands_lacking = 0;      
        foreach($tiffs as $tiff_found)
        {
          $one_tiff = basename($tiff_found);
          for($i=1; $i <= $bands_all[0]; $i++)
					{
					  $found = strstr($one_tiff,$bands_all[$i]);
					  if($found)
						{
						 $bands_all[$i] = 1;
						 $bands_lacking += 1;
					  }
					}       
        }
        
        for($i=1; $i < 6 ; $i++)
				 if($bands_all[$i] != 1) $bands_lacking += 1;			        
      }
      
      if($sensor != "CCD") $factor = 1;   
      else 
			 if($bands_lacking > 0) $factor = 6; // If instrument = CCD we must deliver all 5 bands.  
			 else $factor = 5;                   // We could have two repeated bands 3 (CCD1XS and CCD2XS), plus bands 1,2 and 4, and no PAN, and we would have 5 bands on directory!

//      if(trim($this->productId)== "" and count($tiffs) < $factor)
      if(count($tiffs) < $factor)
      {
        
			 // Tiff does not exist in disk, lets build it from gralha
			 
        if ($GLOBALS["stationDebug"]) echo "RequestClass - Tiff not found, looking for $file\n";
        $gralhas = findSimilarGralhainDisk ($file);
        $count_gralhas = count($gralhas);
        
        if($count_gralhas > 0 or count($tiffs) > 0)   // we may have some CCD Tiffs but some Gralha may not be present on disk. 
        {       			  	 
          $gralhas_files = array(0 => "CCD1XS", 1 => "CCD2XS", 2 => "CCD2PAN");
          
					foreach($gralhas as $filedir)
          { 	
            if ($GLOBALS["stationDebug"]) echo "<br> \n RequestClass - $filedir found <br> \n";
            
            if($sensor == "CCD")
            {
              decodeGralha(basename($filedir),$satellite,$number,$instrument,$channel,$type,$date,$path,$row);
              $this_gralha_tiffs = findTiffinDiskFromGralha (basename($filedir),$this->restoration,$channel);  // look for specific (Channel) Tiff data
							switch($type)
							{
							  case "XS":
							    if($channel == 1) $gralhas_files[0] = "1"; // Gralha CCD1XS present
							    else $gralhas_files[1] = "1"; // Gralha CCD2XS present
							    break;
							    
							  case "PAN":
							    $gralhas_files[2] = "1"; // Gralha CCD2PAN present
							}
							
							if(count($this_gralha_tiffs) == 0) $procPar = $this->g2tStart($filedir,$procPar,$satCod);   
            }
            else
            {
              if($sensor == "ETM")
							{ 
							  $filepan = str_replace("XS","PAN",$filedir);
							  $procPar = $this->g2tStart($filepan,$procPar,$satCod);
							}
              $procPar = $this->g2tStart($filedir,$procPar,$satCod);   
            }
         }
					
					if($sensor == "CCD")
					for($i=0; $i < 3 ; $i++)
					if($gralhas_files[$i] != "1") // Gralha not present; let's verify Tiffs
					{
					  $gralha_name = str_replace("CCD1XS", $gralhas_files[$i], $file); // CCD1XS is the reference for Gralha in CbersScene
					  decodeGralha($gralha_name,$satellite,$number,$instrument,$channel,$type,$date,$path,$row);
						$tiffs_found = findTiffinDiskFromGralha($gralha_name,$this->restoration,$channel);   // look for specific (Channel) Tiff data
						 
					  if(count($tiffs_found) == 0) // Generate corresponding Gralha and then Tiffs
					  {
					    $status = findDRDfromSceneId ($this->sceneId,$drds,$tapeid,$tapepath,$skip);
					    if($status == 0) // DRD is registered in database
					    {
					     if($gralhas_files[$i] == "CCD1XS") $index = 0; // Corresponds to DRD[0]
					     else $index = 1; // Corresponds to DRD[1] (CCD2XS and CCD2PAN) 
							 $fulldrd = findDRDinDisk($drds[$index]); 
               if(strlen($fulldrd) > 1)
               {
                 $d2g = new d2g ($drds[$index]);
                 $d2g->createWorkOrder();
                 $d2g->start($procPar);
                 $procPar = $this->g2tStart($gralha_name,$d2g->id(),$satCod);
                 $gralhas_files[$i] = "1";
               } else $inproduction = false;
					    }else $inproduction = false;
					  } else $gralhas_files[$i] = "1"; // Gralha not present but Tiffs are !!! That's what matters !!!
					}
								      
        } else
               
				//if Gralha does not exist, generate it from DRD
				
        {
          if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Gralhas not found, looking for DRD\n";
          $status = findDRDfromSceneId ($this->sceneId,$drds,$tapeid,$tapepath,$skip);
          if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Nome DRD = $drds[0] <br> \n";



          if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Aqui 11111 <br> \n";




          if($status==0)
          
       // DRD is registered in database
       
          { 
					  if($sensor == "CCD") $limit = 2; // Two DRDs to process 
						else $limit = 1;		             // Just one DRD to process
						
            if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - drd = $drds \n";
            for($i = 0;$i < $limit;$i++)
						{ 
             $fulldrd = findDRDinDisk($drds[$i]);
             if (strlen($fulldrd) > 1)
             {          
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - drd = $drds full = $fulldrd \n";
              if ($satCod == "T1" or $satCod == "A1") $d2g = new c2h ($fulldrd);
              else 
              {
							  $d2g = new d2g ($drds[$i]);
                $d2g->createWorkOrder();
              }
              $d2g->start ($procPar);
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - After D2G, creating g2t para " . $gralha . "\n";

              if ($satCod == "T1" or $satCod == "A1") 
              {
               decodeDRD($drds[$i],$satellite,$number,$instrument,$year,$month,$day,$hour,$minute,$second,$channel);
               $datedir = $year . "_" . $month;
		           $datepath = getGralhaDir ($satellite.$number).$datedir;
               $result = explode("_",$file);
               $hdfName = $result[0] . "." . "hdf";
               $hdfFullName = $datepath . "/". $drds[$i] . "/" . $hdfName ;
               $arg = $hdfFullName;
              }	
              else $arg = $file; 

              if($i == 1) // CCD - We have to produce the two Gralhas (CCD2XS  and CCD2PAN)
							{
							  $arg = str_replace("CCD1XS","CCD2XS",$file);
							  $procPar = $this->g2tStart($arg,$d2g->id(),$satCod);
							  $arg = str_replace("CCD1XS","CCD2PAN",$file);
							  $procPar = $this->g2tStart($arg,$procPar,$satCod);
	
							} else $procPar = $this->g2tStart($arg,$d2g->id(),$satCod);

            } else
                         
			// DRD does not exist in disk, ask operator to read it from tape 
			
            {  
              /*
              #
              # Old code segment - proceedings have been improved.
              #
							if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - DRD not found, looking for DRD on tape\n";
              $targument = $tapeid.";".$tapepath.";;".$skip; // IMPORTANT
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Argument = " . $targument . "\n";
              $m2d = new m2d ($targument);
              $m2d->setStatus ("OPERATOR");
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Bd = Man " . $m2d->dbman . " Cat = " . $m2d->dbcat . "\n";
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - Bd = Man " . $GLOBALS["dbmanager"] . " Cat = " . $GLOBALS["dbcatalog"] . "\n";
              $m2d->start ();
              if ($GLOBALS["stationDebug"]) echo "<br> RequestClass - After M2D, creating d2g para " . $drds . "\n";
              $d2g = new d2g ($drds[$i]);
              $d2g->createWorkOrder();
              $d2g->start ($m2d->id());
              $procPar = $this->g2tStart($file, $d2g->id(),$satCod);
              #
              #
              #
              */
              
              echo "<br> RequestClass - DRD data ($drds[$i]) not found ! <br>\n";
              
              if($sensor == "CCD") // Segment code to provide warning message in case of CCD.
              {  
							  if($i > 0) $arg = str_replace("CCD1XS","CCD2XS",$argument); 
			          $m2d_warning = new m2d("$arg");
				        $m2d_warning->setStatus ("OPERATOR");
				        $m2d_warning->start(0,"<font color=#FF0000>WARNING ====> </font> DRD data ($drds[$i]) not found on proper directory ! ");
			        }
			        else $inproduction = false;
						}
           }
          } else
                     
		 // Problems with DRD in the DataBase
		 
           { 	    
             $inproduction = false;
           }
        }
      }
    
      // Setting up status
	  
	  
	  
	  
	  if ( $satCod == "A1" and $sensor == "MODIS" )
	  {
          if ($GLOBALS["stationDebug"]) echo "<br>sat_prefix  = $sat_prefix - satCod  = $satCod -  this->sceneId  = $this->sceneId <br> \n";			
	  }
	  

	  if ( $satCod == "NPP" and $sensor == "VIIRS" )
	  {
          if ($GLOBALS["stationDebug"]) echo "<br>XXX** sat_prefix  = $sat_prefix - satCod  = $satCod -  this->sceneId  = $this->sceneId <br> \n";				
	  }
	  
	  

	  if ( ( $satCod == "RE1" or $satCod == "RE2" or $satCod == "RE3" or $satCod == "RE4" or $satCod == "RE5" ) and $sensor == "REIS" )
	  {
          if ($GLOBALS["stationDebug"]) echo "<br>RAPIDEYE - XXX** sat_prefix  = $sat_prefix - satCod  = $satCod -  this->sceneId  = $this->sceneId <br> \n";				
	  }
	  
	 
	  
           
      if($sensor == "CCD" and $inproduction == false) // Segment code to provide request dispatch if at least one CCD (CCD1, CCD2) was found.
      {
        if($gralhas_files[0] != "1") // CCD1XS
				{ 
				 $arg = str_replace("CCD2XS","CCD1XS",$argument);
				 $msg = "Can\'t Generate GRALHA CCD1XS Data !";
				 $p2u_warning = new p2u($arg);
				 $p2u_warning->setStatus ("OPERATOR");
				 $p2u_warning->start(0,"<font color=#FF0000>WARNING ====>  </font>" . $msg);
				} 
			  if($gralhas_files[1] != "1") // CCD2XS 
				{
				 // Let's first check if CCD2XS Band 3 (that might be repeated due to CCD1XS) is the one that's lacking; if so, NO PROBLEMS !
				 if($bands_all[1] != "1" or $bands_all[3] != "1") // band 1 lacking	or band 3 lacking; we may need CCD2XS files	
				 {
				   $arg = str_replace("CCD1XS","CCD2XS",$argument);
				   $msg = "Can\'t Generate GRALHA CCD2XS Data !";
				   $p2u_warning = new p2u($arg);
				   $p2u_warning->setStatus ("OPERATOR");
				   $p2u_warning->start(0,"<font color=#FF0000>WARNING ====>  </font>" . $msg);
				 }
				}
				if($gralhas_files[2] != "1") // PAN
				{
				 $msg = "Can\'t Generate GRALHA CCD2PAN Data !";
				 $arg = str_replace("CCD1XS","CCD2PAN",$argument);
				 $p2u_warning = new p2u($arg);
				 $p2u_warning->setStatus ("OPERATOR");
				 $p2u_warning->start(0,"<font color=#FF0000>WARNING ====>  </font>" . $msg);
				}
				
			  $inproduction = true; // We previously know that we have at least one CCD - otherwise we would have been directed to the m2d operation above !!!
			}  

      if($this->status != 'Q')
         $this->updateStatus($inproduction?'C':'Y');

			if ($inproduction == true)
			{  
        if ($GLOBALS["stationDebug"]) echo "RequestClass - Everything OK - let's start p2u with this dependence " . $procPar . "\n";
  		  if(trim($execClass) != "p2u") 
				 if($this->status != 'Q')  // This clause has been added to provide a previous execution of a "p2c" request for allowing Control Quality
				                           // assesment, before mailing product to user.
				                           // Comment this statement if this policy be changed.
				  $p2u->setStatus ("OPERATOR");
   		  $p2u->start($procPar);
      } 
			else
			{ 
			  $p2u->sendErrorMail($file);
			  $p2u->sendFirstMail();
        $p2u->setStatus ("OPERATOR");
				switch ($satCod)
        {
         case "P6":
				    $msg = "Tiff files no found !";
						break;
				 case "L5":
				 case "L7":
				     if($sufix == "GLS") $msg = "Tiff files no found !";
				     else $msg = "DRD data not found !";
				     break;
			   default:  
			      $msg = "DRD data not found !";
				} 
				$p2u->start(0,$msg);
			}		
								 
   }

  function g2tStart($filedir, $dependsOfReqId, $satCod)   
  {
    
    if ($satCod == "T1" or $satCod == "A1") 
    {
      $arg = $filedir . ";" . $this->sceneId ;
      $g2t = new h2t($arg);
    }	
    else 
    {
      $gralhaFilename = basename($filedir);
      if ($GLOBALS["stationDebug"]) {
      echo "RequestClass - g2tStart : gralhaFilename = " . $gralhaFilename . "\n"; }
      $g2t = new g2t($gralhaFilename);
      $g2t->setRestoration($this->restoration);
    }
    $g2t->createWorkOrder();
    $g2t->start ($dependsOfReqId);

    return $g2t->id();
  } 

   function show()
   {

      echo "<br> REQUEST ITEM -> ";  
      echo $this->numItem . " - ";
      echo $this->reqId. " - ";
      echo $this->sceneId. " - ";
      echo $this->productId . " - ";
      echo $this->bands . " - ";
      echo $this->sceneShift . " - ";
      echo $this->correctionLevel . " - ";
      echo $this->orientation . " - ";
      echo $this->resampling . " - ";
      echo $this->datum . " - ";
      echo $this->projection . " - ";
      echo $this->longOrigin . " - ";
      echo $this->latOrigin . " - ";
      echo $this->stdLat1 . " - ";
      echo $this->stdLat2 . " - ";
      echo $this->format . " - ";
      echo $this->interleaving . " - ";
      echo $this->media . " - ";
      echo $this->price . " - ";
      echo $this->prodDate . " - ";
      echo $this->status . " - ";
      echo $this->restoration . " - ";
   }
};
//------------------------------------------------------------------------------
//
// Generic Functions
//
//------------------------------------------------------------------------------
// SEARCH REQUESTS
function searchReq(&$bd, &$matReq, $sql, $tpStatus, $status)
{
   // Query

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }

   $nrow = $bd->numRows();

   // Fill array of Request Objetcts
   for($i=0; $i < $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matAux[$i] = new Request($bd);
      $matAux[$i]->fillAttrib($row);
   }

   // Fill Itens
   for($i=0; $i < $nrow; $i++)
   {
      $matAux[$i]->getItens();
   }

    // Verfying itens and it's status
   if(trim($status) == "")
   {
      $matReq = $matAux;
      $nReqs = $nrow;
   } else
   {
      for($i=$nReqs = 0; $i < $nrow; $i++)
         if(!$tpStatus)  // Status by Request
         {
            if($matAux[$i]->status == $status) $matReq[$nReqs++] = $matAux[$i];
         }
         else
         {
            if($matAux[$i]->searchItemStatus($status)) $matReq[$nReqs++] = $matAux[$i];
         }
   }
   return $nReqs;
}
function searchReqComp(&$bd, &$objReq, $number="")
{
   $sql  = "SELECT * FROM Request";
   if(trim($number)!="") $sql .= " WHERE ReqId = " . $number;

   $nReq = searchReq(&$bd, &$matReq, $sql, 0, "");
   $objReq = $matReq[0];
   return $nReq;
}
function searchReqByNumber(&$bd, &$matReq, $tpStatus=0, $status="", $order="", $number="")
{
   $sql  = "SELECT * FROM Request";

   if(trim($number)!="")
      $sql .= " WHERE ReqId = " . $number;

   if(trim($order)!="")  $sql .= " ORDER By " . $order;
   return (searchReq(&$bd, &$matReq, $sql, $tpStatus, $status));

}
function searchReqByDate(&$bd, &$matReq, $tpStatus=0, $status="", $order="", $reqDate="")
{
   // Setting Date
   $dateIni = $reqDate . " 00:00:00";
   $dateFim = $reqDate . " 23:59:59";
   
   // Setting sql statement
   $sql  = "SELECT * FROM Request";
   if(trim($reqDate)!="")
      $sql .= " WHERE ReqDate >= '" . $dateIni  . "' AND ReqDate <= '" . $dateFim . "'";

   if(trim($order)!="")  $sql .= " ORDER By " . $order;

   return (searchReq(&$bd, &$matReq, $sql, $tpStatus, $status));
}

function searchReqByUserid(&$bd, &$matReq, $tpStatus=0, $status="", $order="", $userid="")
{
   $sql  = "SELECT * FROM Request";

   if(trim($userid)!="")
   {
      $userid = "%" . trim($userid) . "%";
      $sql .= " WHERE UserId LIKE'" . $userid . "'";
   }

   if(trim($order)!="")  $sql .= " ORDER By " . $order;

   return (searchReq(&$bd, &$matReq, $sql, $tpStatus, $status));
}

function searchReqByGeneric(&$bd, &$matReq,  $tpStatus=0, $status="", $order="", $numberFrom="", $numberTo="",
   $dateFrom="", $dateTo="", $nameFrom="", $nameTo="")
{
   $and = " WHERE ";
   $sql  = "SELECT * FROM Request";
   
   if(trim($nameFrom)!="" OR trim($nameTo)!="")
      $sql .= " inner join "  . $GLOBALS["dbusercatname"] . ".User on Request.UserId = " . $GLOBALS["dbusercatname"] . ".User.userId";

   if(trim($numberFrom)!="")
   {
      $sql .= $and . "Request.ReqId >= " . $numberFrom;
      $and = " AND ";
   }

   if(trim($numberTo)!="")
   {
      $sql .= $and . "Request.ReqId <= " . $numberTo;
      $and = " AND ";
   }

   if(trim($dateFrom)!="")
   {
      $dateFrom = $dateFrom . " 00:00:00";
      $sql .= $and . "Request.ReqDate >= '" . $dateFrom . "'";
      $and = " AND ";
   }

   if(trim($dateTo)!="")
   {
      $dateTo = $dateTo . " 23:59:59";
      $sql .= $and . "Request.ReqDate <= '" . $dateTo . "'";
      $and = " AND ";
   }


   if(trim($nameFrom)!="")
   {
      $sql .= $and .  $GLOBALS["dbusercatname"] . ".User.fullname >= '" . $nameFrom . "'";
      $and = " AND ";
   }
   if(trim($nameTo)!="")
   {
      $nameTo = $nameTo . "ZZZ";
      $sql .= $and .  $GLOBALS["dbusercatname"] . ".User.fullname <= '" . $nameTo . "'";
   }
   if(trim($order)!="")  $sql .= " ORDER By " . $order;
   return (searchReq(&$bd, &$matReq, $sql, $tpStatus, $status));
}

?>
