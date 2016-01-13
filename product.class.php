<?php
//------------------------------------------------------------------------------
// Author: Denise
// Date  : november/2003
//------------------------------------------------------------------------------
// Class Product
//------------------------------------------------------------------------------
// Includes
class Product
{
   var $Id;
   var $sceneId;
   var $satelite;
   var $sensor;
   var $path;
   var $row;
   var $data;
   var $media;
   var $directory;
   var $format;
   var $sceneShift;
   var $correctioLevel;
   var $orientation;
   var $projection;
   var $longOrigin;
   var $latOrigin;
   var $stdLat1;
   var $stdLat2;
   var $datum;
   var $resampling;
   var $interleaving;
   var $bands;
   var $avai;
   var $bd;

   function Product($bd)
   {
      $this->sceneId = "";
      $this->satelite = "";
      $this->sensor = "";
      $this->path = 0;
      $this->row = 0;
      $this->data = 0;
      $this->media= 0;
      $this->directory = "";
      $this->format = "";
      $this->sceneShift = 0;
      $this->correctioLevel = "";
      $this->orientation = "";
      $this->projection = "";
      $this->longOrigin = 0;
      $this->latOrigin = 0;
      $this->stdLat1 = 0;
      $this->stdLat2 = 0;
      $this->datum = "";
      $this->resampling = "";
      $this->interleaving = "";
      $this->bands = "";
      $this->avai = ' ';
      $this->bd = $bd;
   }

   function fill($id)
   {

      // Monta o SQL
      $sql = "SELECT * FROM Product WHERE ";
		$sql.= "Id=" . $id;
			
      // Faz a pesquisa	
      if(!$this->bd->query($sql))
      {
			 $this->bd->error();
			 return false;
	   }
	
	   // Verifica o número de produtos
		if($this->bd->numRows()==0)
   		 return false;
	
	   // Preenche o array
	   $row = $this->bd->fetchRow();
	   
		$this->Id = $row["Id"];
      $this->sceneId = $row["SceneID"];
      $this->satelite = $row["satellite"];
      $this->sensor = $row["sensor"];
      $this->path = $row["path"];
      $this->row = $row["row"];
      $this->data = $row["data"];
      $this->media = $row["Media"];
      $this->directory = $row["Directory"];
      $this->format = $row["Format"];
      $this->sceneShift = $row["SceneShift"];
      $this->correctioLevel = $row["CorrectionLevel"];
      $this->orientation = $row["Orientation"];
      $this->projection = $row["Projection"];
      $this->longOrigin =  $row["LongitudeOrigin"];
      $this->latOrigin = $row["LatitudeOrigin"];
      $this->stdLat1 = $row["StandardLatitude1"];
      $this->stdLat2 = $row["StandardLatitude2"];
      $this->datum = $row["Datum"];
      $this->resampling = $row["Resampling"];
      $this->interleaving = $row["Interleaving"];
      $this->bands = $row["Bands"];
      $this->avai = $row["Availability"];

  		return true;
   }
/*
   // Incluir Produtos
   function incluir()
   {
      if($this->validar())
      {
         $sql  = "INSERT INTO Product "
               . "(SceneID, Media, Directory, Format, SceneShift,
               . CorrectionLevel, Orientation, Projection, ZoneNumber,
               . Datum, Resampling, Interleaving, TL_Latitude, TL_Longitude,
               . BR_Latitude, BR_Longitude, Bands, Availability) VALUES ("
             . "'" . $this->sceneId . "',"
		       . $this->media . ","
             . "'" . $this->directory . "',"
		       . "'" . $this->format . "',"
		       . $this->sceneShift . . ","
		       . "'" . $this->correctionLevel . "',"
		       . "'" . $this->orientation . "',"
		       . "'" . $this->projection . "',"
		       . $this->zone . ","
		       . "'" . $this->datum . "',"
		       . "'" . $this->resampling . "',"
             . "'" . $this->interleaving . "',"
		       . $this->TL_Lat . ","		
             . $this->TL_Long . ","	
             . $this->BR_Lat . ","
             . $this->BR_Long
             . "'" . $this->bands . "',"
		       . "'" . $this->avai . "'"
		       . ")";

         $this->qry->executa($sql);
         if(!$this->qry->res)
            return false;
		
         $this->Id = mysql_insert_id();
         return true;
      }
      else
         return false;

   }
   // Validar Usuários		
   function validar()
   {
      return true;
   }


   // Alterar Dados Produtos
   function alterar()
   {
      if($this->validar())
      {	
         $this->montaChave();
         $sql  = "UPDATE Product SET ";
         $sql .= "SceneID= '" . var $this->sceneId . "',";
         $sql .= "Media= " . $this->media . ",";
         $sql .= "Directory= '" . $this->directory . "',";
         $sql .= "Format= '" . $this->format . "',";
         $sql .= "SceneShift= " . $this->sceneShift . ",";
         $sql .= "CorrectionLevel= '" . $this->correctioLevel . "',";
         $sql .= "Orientation= '" . $this->orientation . "',";
         $sql .= "Projection= '" . $this->projection . "',";
         $sql .= "ZoneNumber= " . $this->zone . ",";
         $sql .= "Datum= '" . $this->datum . "',";
         $sql .= "Resampling= '" . $this->resampling . "',";
         $sql .= "Interleaving= '" . $this->interleaving . "',";
         $sql .= "TL_Latitude= " . $this->TL_lat . ",";
         $sql .= "TL_Longitude= " . $this->TL_long . ",";
         $sql .= "BR_Latitude= " . $this->BR_Lat . ",";
         $sql .= "BR_Longitude= " . $this->BR_Long . ",";
         $sql .= "Bands= '" . $this->bands . "',";
         $sql .= "Availability= '" . $this->avai . "')";
         $this->qry->executa($sql);
         return $this->qry->res;
	
   }
         return false;
   }
   
   // Alterar o estado de um Produto (Gratuito ou Pago)
   function alterarEstado($estado)
   {
      return true;
   }

   // Excluir Produtos
   function excluir()
   {
      //Alterar tabela Scene
      
      
      // Excluindo o Produto
      $sql = "DELETE FROM Product WHERE ";
		$sql .= "Id = " . $this->Id;
		$this->qry->executa($sql);
		return $this->qry->res;
   }
   
   // Listar Produtos
   function listaResumida($selecao, $imag=0, $link, $tipo=0,$pagina=1)
   {
   
      //Consulta ao Banco de Dados
    	$sql  = "SELECT Id FROM Product WHERE ";
    	$sql .= $selecao . "ORDER BY Id";
   	$this->qry->executa($sql);
    	
    	if($this->qry->res OR $this->qry->nrw<=0)
    	{
        echo "<p align='center'><b> Nenhum Registro</b> </p>";
        return false;
      }
     
      $pagina = ($pagina==0) ? $pagina=1 : $pagina;
				
		$row = calcula_pos($this->qry->nrw,$pagina);

      echo "<table> border = 0 cellpadding = 0 cellspacing=3 width='450'
         class='txt2' valign='top'\n";
      echo " <tr>\n";
      echo "     <td><b>Id</b></td>/n";
      echo "     <td><b>SceneId</b></td>/n";
      echo "     <td><b>Media</b></td>/n";
      echo "     <td><b>Format</b></td>/n";
      echo "     <td><b>Bands</b></td>/n";
      echo "     <td><b>Availability</b></td>/n";
      echo " </tr>\n";
     
      for($i=$row[0];$i<$row[1];$i++)
      {
         $this->qry->navega($i); //VERIFICAR
			$this->Id = $this->qry->data[0];
			$this->busca();
			
         echo "	<tr>\n";
         echo "		<td nowrap>" . $this->Id . "</td>\n";
			echo "		<td nowrap>" . $this->SceneId . "</td>\n";
			echo "		<td nowrap>" . $this->Media . "</td>\n";
			echo "		<td nowrap>" . $this->Format . "</td>\n";
			echo "		<td nowrap>" . $this->Bands . "</td>\n";
			echo "		<td nowrap>" . $this->Avaliability . "</td>\n";
			// Colocar uma imagem vinda em $imag que aponte para o $link
			echo "	</tr>\n";						
		}
			
		echo "</table>";
		echo "<p align='center' class='link2'>";
		link_paginas($row[2],$pagina,"");  // FAZER
		echo "</p>";			
  }
*/

}
?>
