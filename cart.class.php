<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Class CartGroup
//------------------------------------------------------------------------------
// Includes
include_once("request.class.php");
include_once("user.class.php");
include_once("price.class.php");
include_once("media.class.php");

class CartGroup 
{
  var $sessKey;
	var $nItens;
	var $userId;
	var $total;
	var $cart;
	var $operator;
	var $addressId;
	var $bd;	

   function CartGroup($parbd)
   {
      $this->sessKey = "";
      $this->nItens = 0;
      $this->userId = "";
      $this->total = 0;
      $this->operator = "";
      $this->addressId = 0;
      $this->bd = $parbd;
   }

   function fill($parSess, $userId)
   {
      // Search for all cart itens of this session
      $sql = "SELECT * FROM Cart WHERE Cart.sesskey='$parSess'";
      $this->bd->query($sql) or $this->bd->error($sql);
      $this->nItens = $this->bd->numRows();
      $this->sessKey =  $parSess;

      // Calculate the totalPrice
      for($i=0; $i < $this->nItens; $i++)
      {
         $this->cart[$i] = $this->bd->fetchRow($i);
         $this->total+= $this->cart[$i]["Price"];
      }

      // Update userId
      $this->userId = $userId;

      return $cart;
   }

   function generateRequest($dbusercat)
   {
//       require ("../include/mailMessage_".$_SESSION['userLang'].".php");
//       require	("../catalogov2.0/cart_".$_SESSION['userLang'].".php");
        require ("mailMessage_".$_SESSION['userLang'].".php");
        require	("cart_".$_SESSION['userLang'].".php");
        include_once("globals.php");

      // Today
      $dbcadastro = $GLOBALS["dbusercat"];
      $today = date( "Y-m-d H:i:s" );
      $messageItem = "\t";
      setlocale(LC_MONETARY, 'bra');
      
      // Serching user
      $objUser = new User($dbusercat);
      $objUser->selectByUserId($this->userId);
   
       // Generating Request
      $objReq = new Request($this->bd);
      $reqIp = $_SERVER['REMOTE_ADDR']; 
      $sql = "SELECT COUNTRY_NAME FROM ipToCountry WHERE IP_FROM <= inet_aton('$reqIp') AND IP_TO >= inet_aton('$reqIp')";
      $dbcadastro->query($sql) or $dbcadastro->error($sql);
      $row = $dbcadastro->fetchRow();
      $reqCountry = $row[COUNTRY_NAME];
      $objReq->fill($this->userId, $today, 0, 0, 0, 0, $this->operator, $this->addressId, $reqIp, $reqCountry);
      if(!($reqId = $objReq->insert()))
      {
         echo "Problema inserindo o request!";
         return false;
      }

      // Generating Request Itens
      $nItensReq = 0;
      for($i=0; $i < $this->nItens; $i++)
      {
         $carItem = $this->cart[$i]["Id"];
         $objItemCart = new Cart($this->bd);
         $objItemCart->search($carItem);
         $price = $objItemCart->price;
         $userType = $objUser->getTypeOfUser(); 
         if($price == 0 or  ($price > 0 and $userType !=2))
         {
            //Searching the scene
            $sql = "SELECT * FROM Scene WHERE SceneId='". $objItemCart->sceneId ."'";
            $this->bd->query($sql) or $this->bd->error ($sql);
            $itens = $this->bd->numRows();
            if ($itens == 0)
            {
                  echo "Erro nas Cenas";
                  return false;
            }
            $row = $this->bd->fetchRow();
            $this->bd->freeResult($results); 
            // Setting email message
            //$price = money_format('%10.2n', $objItemCart->price);
            $messageItem .= sprintf("\n%-8d%s\t\t%s", $i+1, $strMessageSatellite, $row["Satellite"]);
            $messageItem .= sprintf("\n%-9s%s\t%s\t\t\t\t%s\t\tR$%7.2f", " " , $strInstrument,$row["Sensor"], $objItemCart->media, $objItemCart->price );
            $messageItem .= sprintf("\n%-9s%s/%s\t%s/%s", " " , $strMessagePath, $strMessageRow, $row["Path"], $row["Row"]);
            $messageItem .= sprintf("\n%-9s%s\t\t\t%s\n", " ", $strDate, $row["Date"]);
            $reqTot += $objItemCart->price;

             // Generate Request Item 
            if(!$objItemCart->generateReqItem($reqId,$userType))
            {
               echo "<br>Problema ao gerar item de pedido a partir do Carrinho</br>";
               return false;
            }
     
            // Removing from cart
            if(!$objItemCart->removeId())
            {
               echo "<br>Problema ao remover pedido do Carrinho</br>";
               return false;
            }
            $nItensReq++;          
         }
      }
      $this->nItens-= $nItensReq;
      if($reqTot) $messageItem .= sprintf("%s\n\t\t\t\t\t\t\t\t%s\tR$%7.2f\n\n", str_repeat("-", 98), $strTotal, $this->total);
      else
         $messageItem .= sprintf("%s\n\n", str_repeat("-", 98));
               
      // Generating E-mail
      $message = sprintf($mailMsgReq1,  $objUser->fullname, $reqId);
      if($reqTot) $message .= " " . $mailMsgReq2;
      $message .= $mailMsgReq3 . str_repeat("-", 98);
      $message .= $messageItem;
      $message .= $mailMsgAtus;
      if(!mail ( $objUser->email, $mailSubReq . $reqId , $message, $mailSender))
      {
         echo "Problema ao enviar o e-mail";
         return false;
      }

      // Returnind Request Number
      return $reqId;
   }
   function remove()
   {
      // Removing all cart itens
      $carItem = $this->cart[0]["Id"];
      $objItemCart = new Cart($this->bd);
      $objItemCart->remove($this->sessKey);
   }

   function show()
   {
      echo "<br> Exibindo Grupo Cart - " .  $this->sessKey . ", ";
      echo $this->nItens . ", ";
      echo $this->userId . ", ";
      echo $this->total . ", ";
      echo $this->operator . ", ";
      echo $this->addressId . "<br>";
   }

   function hasCD()
   {
     $retVal = false;
     // Calculate the totalPrice
     for( $i = 0; $i < $this->nItens; $i++ ) {
       if( strcmp( $this->cart[ $i ]["Media"], "CD" ) == 0 ) {
         $retVal = true;
         break;
       }
     }

     return( $retVal );
   }

   function getTotalPrice()
   {
     $retTotal = 0;
     // Calculate the totalPrice
     for( $i = 0; $i < $this->nItens; $i++ ) {
       $retTotal += $this->cart[ $i ]["Price"];
     }

     return( $retTotal );
   }
};
//------------------------------------------------------------------------------
// Class Cart
//------------------------------------------------------------------------------
class Cart
{
   var $Id;
   var $sessKey;
   var $sceneId;
   var $productId;
   var $bands;
   var $sceneShift;
   var $corretion;
   var $orientation;
   var $resampling;
   var $datum;
   var $projection;
   var $longOrigin;
   var $latOrigin;
   var $stdLat1;
   var $stdLat2;
   var $format;
   var $inter;
   var $media;
   var $restoration;
   var $price;
	 var $bd;
	
   function Cart($bd) 
   {
   
//      $matProdDef = array ("Bands"=>"All", "SceneShift"=>0, "Corretion"=>"Systematic", "Orientation"=>"Map North",
      $matProdDef = array ("Bands"=>"All", "SceneShift"=>0, "Corretion"=>"2", "Orientation"=>"Map North",
               "Resampling" => "BC", "Datum" => "WGS84", "Projection" => "UTM", "LongOrigin" => 0,
               "LatOrigin" => 0, "StdLat1" => 0, "StdLat2" => 0, "Format" => "GeoTiff", "Interleaving" => "BSQ",
               "Media" => "FTP", "Restoration" => "0");  
      $this->Id = 0;
      $this->sessKey = "";
      $this->sceneId = ""; 
      $this->productId = "";
      $this->bands = $matProdDef["Bands"];
      $this->sceneShift = $matProdDef["SceneShift"];
      $this->corretion = $matProdDef["Corretion"];
      $this->orientation = $matProdDef["Orientation"];
      $this->resampling = $matProdDef["Resampling"];
      $this->datum= $matProdDef["Datum"];
      $this->projection = $matProdDef["Projection"];
      $this->longOrigin = $matProdDef["LongOrigin"];
      $this->latOrigin = $matProdDef["LatOrigin"];
      $this->stdLat1 = $matProdDef["StdLat1"];
      $this->stdLat2 = $matProdDef["StdLat2"];
      $this->format = $matProdDef["Format"];
      $this->inter = $matProdDef["Interleaving"];
      $this->media = $matProdDef["Media"];
      $this->restoration = $matProdDef["Restoration"];
      $this->price = 0;
	    $this->bd = $bd;
   }

   function fill($parSess, $parScene = "", $parProd="", $parBand="", $parShift=0,
                 $parCor = "", $parOri="", $parRes="", $parDatum="", $parProj="",
                 $parLong  = 0, $parLat=0, $parStd1=0, $parStd2=0, $parFormat="",
                 $parInter="",$parMedia="",$parRestoration="")
   {
   
      // Parameters Values
      $this->sessKey = $parSess;
      $this->sceneId = $parScene;
      $this->productId = $parProd;
   
      if(trim($parBand)!="") $this->bands = $parBand;
      if($parShift != 0) $this->sceneShift = $parShift; else $this->sceneShift = 0;
      if(trim($parCor)!="") $this->corretion =  $parCor;
      if(trim($parOri) !="") $this->orientation = $parOri;
      if(trim($parRes)!="") $this->resampling = $parRes;
      if(trim($parDatum)!="") $this->datum = $parDatum;
      if(trim($parProj)!="") $this->projection = $parProj;
      if($parLong!= 0) $this->longOrigin = $parLong; else $this->longOrigin = 0;
      if($parLat!= 0) $this->latOrigin = $parLat; else $this->latOrigin = 0;
      if($parStd1!= 0)$this->stdLat1 = $parStd1; else $this->stdLat1 = 0;
      if($parStd2!= 0) $this->stdLat2 = $parStd2; else $this->stdLat2 = 0;
      if(trim($parFormat)!="") $this->format = $parFormat;
      if(trim($parInter)!="") $this->inter = $parInter; 
      if(trim($parMedia)!="") $this->media = $parMedia;
      if(trim($parRestoration)!="") $this->restoration = $parRestoration;

      // Get Scene price - colocar scene class
      $sql = "SELECT * FROM Scene WHERE SceneId='". $parScene ."'";
	    $this->bd->query($sql) or $this->bd->error ($sql); 
	    $itens = $this->bd->numRows();
      if ($itens == 0)
		    die("Erro nas Cenas");
    	$row = $this->bd->fetchRow();
      $this->bd->freeResult($results); 
      $objPrice = new Price($this->bd); 
//      $this->price = $objPrice->searchPrice_orig($row["Satellite"], $row["Sensor"], $row["Date"],$this->productId, $_SESSION['userLang']);
      $this->price = $objPrice->searchPrice($row["Satellite"], $row["Sensor"], $row["Date"],$this->productId, $_SESSION['userLang'],$this->corretion,$_SESSION['userType']);
//      echo "price = $this->price, Sat = $row[Satellite], Sens = $row[Sensor], Date = $row[Date], prod = $this->productId, Lang = $_SESSION[userLang], Corr = $this->corretion, UserType = $_SESSION[userType]"; 
	 }
    
   function search($parId, $parSess="", $parScene="", $parProd="")
   {
      $sql = "SELECT * FROM Cart WHERE Id = " . $parId;
      if(trim($parSess)!="")
         $sql.= " and sesskey= '" . $parsSess . "'";
      if(trim($parScene)!="") 
         $sql .= " and SceneId = '" . $parScene . "'";
      if(trim($parProd)!="")
         $sql .= " and ProductId = '" . $parProd . "'";

      $this->bd->query($sql) or $this->bd->error ($sql);
      $itens = $this->bd->numRows();
      $row = $this->bd->fetchRow();
      $this->Id = $row["Id"];
      $this->sessKey = $row["sesskey"];
      $this->sceneId = $row["SceneId"];
      $this->productId = $row["ProductId"];
      $this->bands = $row["Bands"];
      $this->sceneShift = $row["SceneShift"];
      $this->corretion =  $row["CorrectionLevel"];
      $this->orientation = $row["Orientation"];
      $this->resampling = $row["Resampling"];
      $this->datum= $row["Datum"];
      $this->projection = $row["Projection"];
      $this->longOrigin =$row["LongitudeOrigin"];
      $this->latOrigin = $row["LatitudeOrigin"];
      $this->stdLat1 = $row["StandardLatitude1"];
      $this->stdLat2 =$row["StandardLatitude2"];
      $this->format = $row["Format"];
      $this->inter = $row["Interleaving"];
      $this->media = $row["Media"];
      $this->price = $row["Price"];
      $this->restoration = $row["Restoration"];
   }

   function insert()
   {
      // Make the query statement   
      $sql  = "INSERT INTO Cart ";
      $sql  .= "(sessKey, SceneID, ProductId, Bands, SceneShift, CorrectionLevel, ";
      $sql  .= "Orientation, Resampling, Datum, Projection, LongitudeOrigin, ";
      $sql  .= "LatitudeOrigin, StandardLatitude1, StandardLatitude2, Format, ";
      $sql  .= "Interleaving, Media, Price, Restoration) VALUES ( '";
      $sql  .= $this->sessKey . "', '" . $this->sceneId . "', '";
	  	$sql  .= $this->productId . "', '" .  $this->bands . "', ";
		  $sql  .= $this->sceneShift . ", '" .  $this->corretion . "','";
		  $sql  .= $this->orientation . "', '" . $this->resampling . "','";
      $sql  .= $this->datum . "', '" . $this->projection . "', ";
      $sql  .=  $this->longOrigin . ", " .  $this->latOrigin . ", ";
      $sql  .=  $this->stdLat1 . ", " .  $this->stdLat2 . ", '";
      $sql  .=  $this->format. "', '" .  $this->inter . "', '";
      $sql  .=  $this->media . "', '" . $this->price . "', '" . $this->restoration . "')";

      // Verifica se produto
      if(!$this->bd->query($sql)) 
      {
            $this->bd->error();
            return false;
      }

      return true;
   }

   function modify() 
   {
      $sql  = "UPDATE Cart SET ";
      $sql  .= "sessKey = '" .  $this->sessKey . "',";
      $sql  .= "SceneID = '" . $this->sceneId . "',";
      $sql  .= "ProductId = '" .  $this->productId . "',";
      $sql  .= "Bands = '" .  $this->bands . "',";
      $sql  .= "SceneShift = " . $this->sceneShift . ", ";
      $sql  .= "CorrectionLevel = '" . $this->corretion . "',";
      $sql  .= "Orientation = '" .  $this->orientation . "',";
      $sql  .= "Resampling = '" .  $this->resampling . "',";
      $sql  .= "Datum = '" .  $this->datum . "',";
      $sql  .= "Projection = '" . $this->projection . "',";
      $sql  .= "LongitudeOrigin = " .  $this->longOrigin . ", ";
      $sql  .= "LatitudeOrigin = " . $this->latOrigin . ", ";
      $sql  .= "StandardLatitude1 = " . $this->stdLat1 . ", ";
      $sql  .= "StandardLatitude2 = " . $this->stdLat2 . ", ";
      $sql  .= "Format = '" . $this->format . "',";
      $sql  .= "Interleaving = '" . $this->inter . "',";
      $sql  .= "Media = '" . $this->media . "',";
      $sql  .= "Price = '" . $this->price . "',";
      $sql  .= "Restoration = '" .  $this->restoration . "'"; 
      $sql  .= " WHERE Id = " . $this->Id;

        if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else 
            return true;

   }


   function removeId($parId="")
   {

      if(trim($parId)=="") $parId = $this->Id;

      $sql  = "DELETE FROM Cart WHERE Id = " . $parId;
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

   function remove($parSess, $parScene="", $parProd = "")
   {
      $sql  = "DELETE FROM Cart WHERE ";
      $sql .= "sesskey= '". $parSess  . "'";
      if((trim($parScene)!=""))
         $sql .= " and SceneId = '" . $parScene . "'";
      if((trim($parProd)!=""))
         $sql .= " and ProductId = '" . $parProd . "'";

      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }
   
   function searchCart($parSess, &$itens, $parScene="")
   {
      $sql = "SELECT * FROM Cart WHERE Cart.sesskey='$parsSess'";
      if((trim($parScene)!=""))
         $sql .= " and SceneId = '" . $parScene . "'";

      $this->bd->query($sql) or $this->bd->error ($sql);
      $itens = $this->bd->numRows();
      $row_array = $this->bd->fetchRow();
   	return $row_array;
   }

   function generateReqItem($parReqId,$parUserType)
   {
      $parDate = date( "Y-m-d H:i:s" );
      $nM = searchMedia($this->bd, $matMedia, $this->media);
      $parStatus = 'B';
//			$this->price = $this->price - $matMedia[0]->getPrice(); // trim media price (occasionally) embedded in image price
			                                                        // when evaluating cart item price. 
	    $userType = $parUserType;
      if(($this->price or $matMedia[0]->getPrice()) and $userType != 1) $parStatus = 'Q'; // Manager (UserType =1) 
			                                                                                    // doesn't need allowance;
			                                                                                    // Q is the Request Item Status
			                                                                                    // correspondin to Quality Contro Test - priced items only
      $objReqI = new RequestItem($this->bd);
      $objReqI->fill($parReqId, $this->sceneId, $this->productId, $this->bands, $this->sceneShift,
                 $this->corretion, $this->orientation, $this->resampling, $this->datum, $this->projection,
                 $this->longOrigin, $this->latOrigin, $this->stdLat1, $this->stdLat2, $this->format,
                 $this->inter, $this->media, $this->price, $parDate, $parStatus, $parDate, $this->restoration);

      if(!$objReqI->insert()) 
      { 
         //Deletar todos os pedidos e itens
         // Modificar o estado do pedido e dos demais itens do cart
         return false;
      }
      return true;
   }

};
?>
