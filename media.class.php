<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Classe Media
//------------------------------------------------------------------------------
class Media
{
   var $media;
   var $moedaReal;
   var $moedaDollar;
   var $execClass;

   function Media($bd)
   {
      $this->media = "";
      $this->moedaReal = 0;
      $this->moedaDollar = 0;
      $this->execClass = "";
 	    $this->bd = $bd;
   }

   function fillAttribFromDB($row)
   {
     	$this->media = $row["Media"];
      $this->moedaReal = $row["Real"];
      $this->moedaDollar = $row["Dollar"];
      $this->execClass = $row["Classe"];
   }
   
   function getPrice($parLang="")
   {

      if(trim($parLang) == "") $parLang = "PT";
      if($parLang == "PT")
         return $this->moedaReal;
      else
         return $this->moedaDollar;
   }
   
   function getExecClass()
   {
      return $this->execClass;
   }
   
   function getMedia()
   {
      return $this->media;
   }
   
   function show()
   {
      echo "<br> Midia = " . $this->media;
      echo " - " . $this->moedaReal;
      echo " - " . $this->moedaDollar;
      echo " - " . $this->execClass;
      echo "<br>";
   }
};
//------------------------------------------------------------------------------
//
// Generic Functions
//
//------------------------------------------------------------------------------
// SEARCH REQUESTS 
function searchMedia(&$bd, &$matMedia, $media="") 
{
   $sql  = "SELECT * FROM Media";
   if(trim($media)!="") $sql .= " WHERE Media  = '" . $media . "'";

   if(!$bd->query($sql))
   {
      $bd->error();
      return 0;
   }

   // Number of regs
   $nReg = $bd->numRows();
   
   // Fil matMedia and its fields
//   for($i=0; $i < $nReg; $i++)
   $i = 0;
   do {
   {
      $row = $bd->fetchRow();
      $matMedia[$i] = new Media($bd);
      $matMedia[$i]->fillAttribFromDB($row);
   } 
      } while ( ++$i < $nReg);

   return $nReg;
}
?>
