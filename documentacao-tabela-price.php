<?php
/*
  Documenta��o do procedimento de tarifa��o das imagens do Cat�logo de Imagens
  
  1. Concep��o da Tabela Price na Base de Dados Cat�logo
  
     A Tabela Price foi concedida segundo o layout:
     
     ==================================================================================================================
     | Satellte |  Sensor   |   Policy   |    Date     |   Period    | Dutyclass  | Corrlevel |   Price    |  Discount |
     |          |           |            |             |             |            |           |            |           |
     ===================================================================================================================
     
  2. Descri��o dos Campos e procedimentos relativos impl�citos
  
     . Satellite - CBERS1, CBERS2, LANDSAT-1, LANDSAT-2 ...
     
     . Sensor - CCD, WFI, IRM, TM ...
     
     . Policy - Pol�tica para isenc�o/desconto na tarifa��o. 
                Absoluta (A) : a partir, regressivamente, da data expressa no campo "Date", as imagens s�o 
                               tarifadas com  o valor "Price".
                Relativa (R) : imagens com datas anteriores ao n�mero de anos expresso pelo campo "Period",
                               em rela��o � data da requisi��o da imagem, ser�o tarifadas com o valor "Price".
                               
     . Date - Data, a partir da qual as imagens s�o tarifadas segundo a Pol�tica Absoluta (A).
     
     . Period - N�mero de anos a ser deduzido da data de requisi��o da imagem, que estabelece a data cujas imagens
                de datas anteriores s�o tarifadas pelo valor "Price", segundo a Pol�tica Relativa (R).
                
     . Dutyclass - Estabelece classes de tarifa��o (valor default = 0).
                   
     . Corrlevel - Expressa o n�vel de corre��o da imagem, em rela��o ao qual a tarifa��o pode variar.
                   Presentemente, n�o esta sendo considerado para a tarifa��o.
     
     . Price - Expressa o pre�o da imagem.
 
    
		 . Discount - Contem a f�rmula para o c�lculo do desconto aplicavel. A f�rmula para desconto � expressa por 
		              3 sequ�ncias de n�meros de 2 digitos, separados por um caracter "-". Por exemplo, se tivermos uma
		              f�rmula para desconto expressa por 15-24-10-25-49-15-50-@@-20, isto significar� que quantidades 
		              entre 15 e 24 cenas pedidas, ter�o um desconto de 10% ; quantidades entre 25 e 49 cenas pedidas, 
		              ter�o um desconto de 15% ; finalmente, quantidades de censa pedidas maiores que 50, ter�o um desconto
		              de 20%. O flag "@@" significa que, presentemente, n�o h� outra categoria de desconto.
		              
		 
  3. Hierarquia no crit�rio seletivo dos campos para identifica��o da tarifa��o de uma imagem.
  
     a) Sattelite, Sensor e Corrlevel identificam a imagem.
     b) Dutyclass remete a uma classe espec�fica (N�o esta sendo utilizado).
     c) Policy (Absoluta ou Relativa) complementa a forma��o do pre�o, levando em conta, nas modalidades
        Absoluta ou Relativa, os campos "Period" e "Date" conforme se apliquem.
        
        
 ======================================================================================================================
 
   1. Concep��o da Tabela DutyUser na Base de Dados Cat�logo.
	 
	    Conceitua��o : a tarifa (para um dado usu�rio) de um determinado produto do cat�logo, � fun��o de uma rela��o entre 
			               o produto em quest�o e um atributo (tipo) do usu�rio (e.g. imagens CBERS s�o gratuitas para 
			               usu�rios Internet nacionais, mas podem ser tarifadas para usu�rios com atributos espec�ficos
										 (e.g. fora da Am�rica do Sul).
										 

		  				 					 
		A Tabela DutyUser foi concebida segundo o layout:
				
		     
     ===================================================
     | Satellte |  Sensor   |  userType  |  DutyClass  |  
     |          |           |            |             |            
     ===================================================   
		 
		 
		Os campos da Tabela DutyUser

		 									 
		 	. Satellite e Sensor: de signific�ncia �bvia.								 
		 									  																		 
		  .	Os tipos de Usu�rio (userType):
			
			       1) Tipo 1 - Gerente de Opera��o 
						 2) Tipo 2 - Usu�rio Cadastrado pela Internet - atualmente tendo acessado o cat�logo com IP do Brasil
						 3)	Tipo 3 - Usu�rio Interno - funcion�rio do INPE ou com tais privil�gios.
						 4) Tipo 4 - Usu�rio nacional habilitado (CPF ou CNPJ) a efetuar compras.
						 5) Tipo 5 - Usu�rio internacional habilitado a efetuar compras.	
						             		               	    						 
		  . As Classes de Tarifa��o (Dutyclass):
			
			       1) N�o tarifado (c�digo 1)
						 2) Tarifado (c�digo 2)				 
						 
    C�lculo do pre�o de um produto (cena) individual.
    
    O c�lculo do pre�o de um produto (cena com Satellite e Sensor definidos) para um dado usu�rio com um userType 
		determinado, � estabelecido consoante o procedimento abaixo:
    
    i) recupera-se na tabela DutyUser o valor DutyClass correspondente ao Sat�lite, ao Sensor e ao userType envolvidos 
		
		ii) busca-se na tabela price o(s) registro(s) em que comparecem os valores em quest�o, de Satellite, Sensor e DutyClass (este,
		    recuperado da tabela DutyUser) e seleciona-se (levando-se em conta os crit�rios Relativo (R), ou Absoluto (A) e
				a eventual f�rmula de desconto, exprressos neste(s) registro(s)) o menor valor. Veja abaixo a fun��o (da classe
				price.class.php do m�dulo catalogo) onde se calcula o pre�o de uma cena - o SELECT construido para a busca e as 
				computa��es do loop "while" implementam os item i) e ii) aqui descritos.   
					
		            
*/


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
     while($myrow = $dbcat->fetchRow()) // if we have more than one register returned from the query
                                        // we'll choose the cheapest price being calculated ; that's why the
                                        // variable $price is actually an array ; at the end of the 
                                        // procedure, we execute a sort (array_multisort($price)) to
                                        // provide the cheapest price.
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
					   $threshold = $today_time - $period * (365 * 24 * 60 * 60);  // n�o considerei os dias a mais dos bissestos 
             $difference = $threshold - $scene_date;
					   if ($difference >= 0) $price[$i += 1] = $myrow[Price];
					 } else $price[$i += 1] = $myrow[Price];
      };
//      echo " o preco � " . $price . "<br>";
      if (!$price)
			{ 
				?> <script> // alert( 'Atributos inexistentes na tabela price !')</script> <?;
				$price[0] = 0; 
			}; 

			array_multisort($price);
//			var_dump($price);
			return $price[0];     
 
   }

?>