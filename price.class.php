<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Class Price
//------------------------------------------------------------------------------
include_once("product.class.php");
class Price
{
   var $sat;
   var $sensor;
   var $date;
   var $real1;
   var $dollar;
   var $disc;
   var $bd;

   function Price($bd)
   {
      $this->sat = "";
      $this->sensor = "";
      $this->date="";
      $this->real1 = 0;
      $this->dollar = 0;
	   $this->disc =0;		
	   $this->bd = $bd;
   }

   function searchPrice_orig($parSat, $parSensor, $parDate, $parProd, $parLang) 
   {
      // Verifica se produto
      $sql  = "SELECT * FROM priceorig WHERE Satellite  = '" . $parSat . "'";
      $sql .= " and Sensor = '" . $parSensor . "' and Date < '" . $parDate;
      $sql .= "' order by Date desc";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return 0;
      }

      $num =  $this->bd->numRows(); 
      if($this->bd->numRows()>=1)
      {

         $row = $this->bd->fetchRow(); 
         $this->sat = $row["Satellite"];
         $this->sensor = $row["Sensor"];
         $this->date = $row["Date"];
         $this->real1 = $row["Real"];
         $this->dollar = $row["Dollar"];
         $this->disc = $row["DiscountProduct"];

         if($parLang == "PT")
            $price = $this->real1;
         else
            $price = $this->dollar;

         if(trim($parProd)!="")
         { 
            $prod = new Product($this->bd);
            $prod->fill($parProd);
            if($prod->avai=='F')
               $price = 0;
            else
               $price -= $price * $this->disc/100;
         }
         return $price;
      }
      else
         return 0;
   }
   
   function searchPrice($parSat, $parSensor, $parDate, $parProd, $parLang,$parCor,$parUserType)
   { 
     include_once ("globals.php");

     $dbcat = $GLOBALS["dbcatalog"]; 
     $usertype = $parUserType;
 
     if ($usertype == 0) return 0;
        
     $sql = "SELECT * FROM price inner join DutyUser on (DutyUser.Satellite = price.Satellite and DutyUser.Sensor = price.Sensor) WHERE DutyUser.Satellite = '$parSat' and DutyUser.Sensor = '$parSensor' and  DutyUser.DutyClass = price.Dutyclass AND userType = '$usertype'";
  
     if (!$dbcat->query($sql)) die ($dbcat->error ($sql));
     $numrows = $dbcat->numrows();
//     echo " Sat = " . $parSat . " Sensor = " . $parSensor . " Corr = " . $parCor . "<br>";
     if (!$numrows) return 0;

     $scene_date = strtotime(trim($parDate));
     $i = -1;
     while($myrow = $dbcat->fetchRow()) // if we have more than one register returned from the query,
                                        // we'll look for the cheapest price being calculated from these data ;
																				// that's why the variable $price is actually an array ; 
																				// at the end of the procedure, we execute a sort (array_multisort($price)) to
                                        // provide the cheapest desired price (if any).
     { 
       $policy = $myrow[Policy];
       $date = $myrow[Date];
       $date = strtotime(trim($date)); 
       $period = $myrow[Period];
       $dutyclass = $myrow[DutyClass];
//  echo " date = " . $myrow[Date] . " date = " . $date . " scene-date = ".$scene_date."<br>";
//  echo " policy = " . $policy . " period = " . $period . " dutyclass = " . $dutyclass .  " Price = " .$myrow[Price] . "<br><br>" ;
    
         if (trim($policy) == "A")  // Absolute Policy 
         {
           if ($scene_date <= $date) $price[$i += 1] = $myrow[Price];
	       } else if (trim($policy) == "R") // Relative Policy
           {  
				     $today_time = time();
					   $threshold = $today_time - $period * (365 * 24 * 60 * 60);  // não considerei os dias a mais dos bissestos 
             $difference = $threshold - $scene_date;
					   if ($difference >= 0) $price[$i += 1] = $myrow[Price];
					 } else $price[$i += 1] = $myrow[Price];
      };
//      echo " o preco é " . $price . "<br>";
      if (!$price)
			{ 
				?> <script> // alert( 'Atributos inexistentes na tabela price !')</script> <?;
				$price[0] = 0; 
			}; 

			array_multisort($price);
//			var_dump($price);
			return $price[0];
      
 
   }
   
};

?>
