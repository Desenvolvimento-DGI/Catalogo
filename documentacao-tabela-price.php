<?php
/*
  Documentação do procedimento de tarifação das imagens do Catálogo de Imagens
  
  1. Concepção da Tabela Price na Base de Dados Catálogo
  
     A Tabela Price foi concedida segundo o layout:
     
     ==================================================================================================================
     | Satellte |  Sensor   |   Policy   |    Date     |   Period    | Dutyclass  | Corrlevel |   Price    |  Discount |
     |          |           |            |             |             |            |           |            |           |
     ===================================================================================================================
     
  2. Descrição dos Campos e procedimentos relativos implícitos
  
     . Satellite - CBERS1, CBERS2, LANDSAT-1, LANDSAT-2 ...
     
     . Sensor - CCD, WFI, IRM, TM ...
     
     . Policy - Política para isencão/desconto na tarifação. 
                Absoluta (A) : a partir, regressivamente, da data expressa no campo "Date", as imagens são 
                               tarifadas com  o valor "Price".
                Relativa (R) : imagens com datas anteriores ao número de anos expresso pelo campo "Period",
                               em relação à data da requisição da imagem, serão tarifadas com o valor "Price".
                               
     . Date - Data, a partir da qual as imagens são tarifadas segundo a Política Absoluta (A).
     
     . Period - Número de anos a ser deduzido da data de requisição da imagem, que estabelece a data cujas imagens
                de datas anteriores são tarifadas pelo valor "Price", segundo a Política Relativa (R).
                
     . Dutyclass - Estabelece classes de tarifação (valor default = 0).
                   
     . Corrlevel - Expressa o nível de correção da imagem, em relação ao qual a tarifação pode variar.
                   Presentemente, não esta sendo considerado para a tarifação.
     
     . Price - Expressa o preço da imagem.
 
    
		 . Discount - Contem a fórmula para o cálculo do desconto aplicavel. A fórmula para desconto é expressa por 
		              3 sequências de números de 2 digitos, separados por um caracter "-". Por exemplo, se tivermos uma
		              fórmula para desconto expressa por 15-24-10-25-49-15-50-@@-20, isto significará que quantidades 
		              entre 15 e 24 cenas pedidas, terão um desconto de 10% ; quantidades entre 25 e 49 cenas pedidas, 
		              terão um desconto de 15% ; finalmente, quantidades de censa pedidas maiores que 50, terão um desconto
		              de 20%. O flag "@@" significa que, presentemente, não há outra categoria de desconto.
		              
		 
  3. Hierarquia no critério seletivo dos campos para identificação da tarifação de uma imagem.
  
     a) Sattelite, Sensor e Corrlevel identificam a imagem.
     b) Dutyclass remete a uma classe específica (Não esta sendo utilizado).
     c) Policy (Absoluta ou Relativa) complementa a formação do preço, levando em conta, nas modalidades
        Absoluta ou Relativa, os campos "Period" e "Date" conforme se apliquem.
        
        
 ======================================================================================================================
 
   1. Concepção da Tabela DutyUser na Base de Dados Catálogo.
	 
	    Conceituação : a tarifa (para um dado usuário) de um determinado produto do catálogo, é função de uma relação entre 
			               o produto em questão e um atributo (tipo) do usuário (e.g. imagens CBERS são gratuitas para 
			               usuários Internet nacionais, mas podem ser tarifadas para usuários com atributos específicos
										 (e.g. fora da América do Sul).
										 

		  				 					 
		A Tabela DutyUser foi concebida segundo o layout:
				
		     
     ===================================================
     | Satellte |  Sensor   |  userType  |  DutyClass  |  
     |          |           |            |             |            
     ===================================================   
		 
		 
		Os campos da Tabela DutyUser

		 									 
		 	. Satellite e Sensor: de significãncia óbvia.								 
		 									  																		 
		  .	Os tipos de Usuário (userType):
			
			       1) Tipo 1 - Gerente de Operação 
						 2) Tipo 2 - Usuário Cadastrado pela Internet - atualmente tendo acessado o catálogo com IP do Brasil
						 3)	Tipo 3 - Usuário Interno - funcionário do INPE ou com tais privilégios.
						 4) Tipo 4 - Usuário nacional habilitado (CPF ou CNPJ) a efetuar compras.
						 5) Tipo 5 - Usuário internacional habilitado a efetuar compras.	
						             		               	    						 
		  . As Classes de Tarifação (Dutyclass):
			
			       1) Não tarifado (código 1)
						 2) Tarifado (código 2)				 
						 
    Cálculo do preço de um produto (cena) individual.
    
    O cálculo do preço de um produto (cena com Satellite e Sensor definidos) para um dado usuário com um userType 
		determinado, é estabelecido consoante o procedimento abaixo:
    
    i) recupera-se na tabela DutyUser o valor DutyClass correspondente ao Satélite, ao Sensor e ao userType envolvidos 
		
		ii) busca-se na tabela price o(s) registro(s) em que comparecem os valores em questão, de Satellite, Sensor e DutyClass (este,
		    recuperado da tabela DutyUser) e seleciona-se (levando-se em conta os critérios Relativo (R), ou Absoluto (A) e
				a eventual fórmula de desconto, exprressos neste(s) registro(s)) o menor valor. Veja abaixo a função (da classe
				price.class.php do módulo catalogo) onde se calcula o preço de uma cena - o SELECT construido para a busca e as 
				computações do loop "while" implementam os item i) e ii) aqui descritos.   
					
		            
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

?>