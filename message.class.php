<?php
//------------------------------------------------------------------------------
//
// Classe Message
//
//------------------------------------------------------------------------------
class message
{
   var $modulo;
   var $codigo;
   var $cat;
   var $language;
   var $pannel;

   function message($parLang="")
   {
      if(trim($parLang)!="")
         $this->language = $parLang;
      else
         $this->language = 'PT';
   }

   function display($parModulo, $parErro, $parCat, $parMess = "", $parPannel="")
   {

      // Fill fields
      $this->modulo = $parModulo;
      $this->codigo = $parErro;
      $this->cat = $parCat;
      $this->pannel = $parPannel;

      // Including message array
      require ("messageArrays_".$this->language.".php");
      
      // Setting message
      $auxMessage = trim($parMess)!=""?$parMess:$matMessage[$this->modulo][$this->codigo];
      $message = "<font color=" . $matMessageColor[$this->cat] . " face=verdana size=1>";
      $message .= "<br><center><b>[" . $matMessageType[$this->cat] . "-" . $auxMessage . "]</b></center></br></font>";
      echo $message;
   }
};
?>
