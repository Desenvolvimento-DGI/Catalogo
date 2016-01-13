<?php
//------------------------------------------------------------------------------
//
// Classe Company
//
//------------------------------------------------------------------------------
// Includes
//
include_once("message.class.php");
class Company
{
   var $CNPJ;
   var $name;
   var $IE;
   var $type;
   var $payment;
   var $status;
   var $bd;

   function Company($bd)
   {
      $this->CNPJ = "";
      $this->name = "";
      $this->IE="";
      $this->type = "";
      $this->payment = "";
	   $this->status = '';		
	   $this->bd = $bd;
   }

   function setFields($parCNPJ_CPF, $parName="", $parIE="", $parType="", $parPayment="", $parStatus='')
   {
      $this->CNPJ = $parCNPJ_CPF;
      if($parName!="") $this->name = $parName;
      if($parIE!="") $this->IE = $parIE;
      if($parType!="") $this->type = $parType;
      if($parPayment!="") $this->payment = $parPayment;
      if($parStatus !='') $this->status = $parStatus;
   }

    function getFields(&$parCNPJ_CPF, &$parName, &$parIE, &$parType, &$parPayment, &$parStatus)
   {
      $parCNPJ_CPF = $this->CNPJ;
      $parName = $this->name;
      $parIE = $this->IE;
      $parType = $this->type;
      $parPayment = $this->payment;
	   $parStatus = $this->status;	
   }

   
   function insert()
   {
      if($this->status=='')  $this->status == 'A';
    
         $sql  = "INSERT INTO Company " ;
         $sql .= "(CNPJ, name, IE, type, payment, status) VALUES ('";
         $sql .= $this->CNPJ . "','" ;
         $sql .= $this->name . "','";
			$sql .= $this->IE . "','" ;
			$sql .= $this->type . "','";
			$sql .= $this->payment . "','";
			$sql .= $this->status . "')";
      
			if(!$this->bd->query($sql))
         {
            $this->bd->error();
            return false;
         }
         else
           return true;
	}

   function modify()
   {
      $sql  = "UPDATE Company SET ";
      $sql .= "name= '" . $this->name . "', ";
      $sql .= "IE= '" . $this->IE . "',";
      $sql .= "type= '" . $this->type . "',";
      $sql .= "payment= '" . $this->payment . "'";
      $sql .= " WHERE CNPJ = '". $this->CNPJ . "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

   function remove()
   {
      $sql  = "DELETE FROM Company WHERE ";
      $sql .= " CNPJ = '". $this->CNPJ . "'";
      
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

	function selectByCNPJ($parCNPJ)
   {
      $sql  = "SELECT * FROM Company WHERE CNPJ= '";
      $sql .= $parCNPJ .  "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }


      if($this->bd->numRows()>=1)
      {
         $row = $this->bd->fetchRow();
         $this->CNPJ = $row["CNPJ"];
         $this->name = $row["name"];
         $this->IE = $row["IE"];
         $this->type = $row["type"];
         $this->payment = $row["payment"];
         $this->status = $row["satus"];
         return true;
      }
      else
         return false;
   }
	
   function changeStatus($parStatus)
   {
      $sql  = "UPDATE Company SET ";
      $sql .= "status= '" .  $this->parStatus . "'";
      if(!$this->bd->query($sql))
      {
      	$this->bd->error();
        return false;
      }
      else
             return true;
   }
   function attribFromDB($row, $par="")
   {
      //$row = $this->bd->fetchRow($par);
      $this->CNPJ = $row["CNPJ"];
      $this->name = $row["name"];
      $this->IE = $row["IE"];
      $this->type = $row["type"];
   	$this->payment = $row["payment"];
   	$this->status = $row["status"];
   }

    function blocked()
		{
			if($this->status == 'N')
				return false;
			else
				return true;				
		}
   
    function show()
   {

      echo "<br> COMPANY -> ";
      echo $this->CNPJ . " ";
      echo $this->name . " ";
      echo $this->IE . " ";
      echo $this->type . " ";
      echo $this->payment . " ";
      echo $this->status . " ";
   }
};
//------------------------------------------------------------------------------
//
// Classe Address
//
//------------------------------------------------------------------------------
class Address
{
   var $addressId;
   var $userId;
   var $addressType;
   var $CNPJ_CPF;
   var $compCNPJ;
   var $dig;
   var $cep;
   var $street;
   var $number;
   var $complement;
   var $district;
   var $city;
   var $state;
   var $country;
   var $NUsers;
   var $bd;

   function Address($bd)
   {
      $this->$addressId = 0;
      $this->userId="";
      $this->addressType="";
      $this->CNPJ_CPF="";
      $this->compCNPJ="";
      $this->dig="";
      $this->cep="";
      $this->street="";
      $this->number="";
      $this->complement="";
      $this->district="";
      $this->city="";
      $this->state="";
      $this->country="";
      $this->delivery=0;
      $this->payment=0;
      $this->NUsers=0;
      $this->bd = $bd;
   }
   
   function setFields($parAddressType='', $parCNPJ_CPF="", $parcompCNPJ="", $parDig = "", $parCep="", $parstreet="", $parnumber="",
      $parcomplement="", $pardistrict="", $parcity="", $parstate="", $parcountry="")
   {

      if($parAddressType!='') $this->addressType= $parAddressType;
      if($parCNPJ_CPF!="") $this->CNPJ_CPF= $parCNPJ_CPF;
      if($parcompCNPJ!="")$this->compCNPJ= $parcompCNPJ;
      if($parDig!="")$this->dig= $parDig;
      if($parCep!="")$this->cep= $parCep;
      if($parstreet!="")$this->street= $parstreet;
      if($parnumber!="")$this->number= $parnumber;
      if($parcomplement!="")$this->complement= $parcomplement;
      if($pardistrict!="")$this->district= $pardistrict;
      if($parcity!="")$this->city= $parcity;
      if($parstate!="")$this->state= $parstate;
      if($parcountry!="")$this->country= $parcountry;

   }

   function getFields(&$parAddressType, &$parCNPJ_CPF, &$compCNPJ, &$parDig, &$parCep, &$parstreet, &$parnumber,
      &$parcomplement, &$pardistrict, &$parcity, &$parstate, &$parcountry)
   {
      $parAddressType = $this->addressType;
      $parCNPJ_CPF = $this->CNPJ_CPF;
      $parcompCNPJ = $this->compCNPJ;
      $parDig = $this->dig;
      $parCep = $this->cep;
      $parstreet = $this->street;
      $parnumber = $this->number;
      $parcomplement = $this->complement;
      $pardistrict = $this->district;
      $parcity = $this->city;
      $parstate = $this->state;
      $parcountry = $this->country;
   }

   function setUserId( $parUserId="" )
   {

      if( $parUserId != "" )
        $this->userId= $parUserId;
   }

   function insert()
   {
      $sql  = "INSERT INTO Address " ;
      $sql .= "(addressId, userId, addressType, CNPJ_CPF, compCNPJ, digitCNPJ, cep, street, number, complement, ";
      $sql .= " district, city, state, country) VALUES ('','";
      $sql .= $this->userId . "', '" ;
      $sql .= $this->addressType . "', '" ;
      $sql .= $this->CNPJ_CPF . "', '" ;
      $sql .= $this->compCNPJ . "', '";
      $sql .= $this->dig . "', '" ;
		$sql .= $this->cep . "', '" ;
		$sql .= $this->street . "', '" ;
      $sql .= $this->number . "', '" ;
		$sql .= $this->complement . "', '" ;
		$sql .= $this->district . "', '" ;
		$sql .= $this->city . "', '" ;
		$sql .= $this->state . "', '" ;
		$sql .= $this->country . "')";	
		
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
      {
            $this->addressId = $this->bd->insertId();
            return true;
      }
   }

   function modify()
   {
      $sql  = "UPDATE Address SET ";
      $sql .= "addressType= '" . $this->addressType . "'," ;
      //$sql .= "CNPJ_CPF= '" . $this->CNPJ_CPF . "'," ;
      //$sql .= "compCNPJ= '" . $this->compCNPJ . "',";
      $sql .= "cep= '" 			. $this->cep . "'," ;
		$sql .= "street= '" 	. $this->street . "'," ;
		$sql .= "number= '" 	. $this->number . "'," ;
		$sql .= "complement= '" . $this->complement . "'," ;
		$sql .= "district= '" . $this->district . "'," ;
		$sql .= "city= '" 		. $this->city . "'," ;
		$sql .= "state= '" 		. $this->state . "'," ;
		$sql .= "country= '" 	. $this->country . "'" ;
      $sql .= " WHERE addressId = ". $this->addressId;
      // . " AND CNPJ_CPF = ' " . $this->CNPJ_CPF;
      //$sql .= " ' AND compCNPJ = '". $this->compCNPJ . "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }

   function remove()
   {
      $sql  = "DELETE FROM Address WHERE ";
      $sql .= "addressId= " .  $this->addressId;

      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
    }

   function selectAnAddress($parCNPJ, $parComp)
   {
		$sql  = "SELECT * FROM Address WHERE CNPJ_CPF= '";
      $sql .= $parCNPJ .  "' and compCNPJ = '" . $parComp . "'";
      if(!$this->bd->query($sql))
      {
      	$this->bd->error();
        return false;
      }

      if($this->bd->numRows()>=1)
      {
         $row = $this->bd->fetchRow();
         $this->attribFromDB($row);
         return true;
      }
      else
         return false;
   }
   function selectByCNPJ($parCNPJCPF, $parComp="")
   {
		$sql  = "SELECT * FROM Address WHERE CNPJ_CPF = '" .$parCNPJCPF .  "'";
		if(trim($parComp)!="") $sql .= " and compCNPJ = '" . $parComp . "'";
      if(!$this->bd->query($sql))
      {
     	  $this->bd->error();
        return false;
      }

      $nrow = $this->bd->numRows();
      return $nrow;
   }
  	function selectByPars($parCNPJCPF="", $parCity="", $parState="")
   {
      $sql = "SELECT * FROM Address WHERE ";
      $and = "";
      if(trim($parCNPJCPF)!="")
      {
         $sql .= $and . "CNPJ_CPF =  '" . $parCNPJCPF . "'";
         $and = " AND ";
      }
      if(trim($parNomeIni)!="")
      {
         $sql .= $and . "fullname >= '" . $parNomeIni . "'";
         $and = " AND ";
      }
      if(trim($parNomeFim)!="")
      {
         $sql .= $and . "fullname <= '" . $parNomeFim . "'";
         $and = " AND ";
      }
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }

      $nrow = $this->bd->numRows();
      return $nrow;
   }

   function attribFromDB($row="", $par="")
   {
      //$row = $this->bd->fetchRow($par);
      $this->userId = $row["userId"];
      $this->addressId = $row["addressId"];
      $this->addressType = $row["addressType"];
      $this->CNPJ_CPF = $row["CNPJ_CPF"];
      $this->compCNPJ = $row["compCNPJ"];
      $this->dig = $row["digitCNPJ"];
      $this->cep = $row["cep"];
      $this->street = $row["street"];
      $this->number = $row["number"];
      $this->complement = $row["complement"];
      $this->district = $row["district"];
      $this->city = $row["city"];
      $this->state = $row["state"];
      $this->country = $row["country"];
   }
   
   function getNUsers($parCNPJ="", $parComp="")
   {
      if(trim($parCNPJ)=="") $parCNPJ = $this->CNPJ_CPF;
      if(trim($parComp)=="") $parComp = $this->compCNPJ;
      $this->selectByCNPJ($parCNPJ, $parComp);
      return $this->NUsers;
   }
   
   function addNUsers($parCNPJ="", $parComp="")
   {
      if(trim($parCNPJ)=="") $parCNPJ = $this->CNPJ_CPF;
      if(trim($parComp)=="") $parComp = $this->compCNPJ;
      $this->selectByCNPJ($parCNPJ, $parComp);
      ++$this->NUsers;
   }
	function show()
	{
			echo "<br> ADDRESS -> ";
         echo $this->addressId . " ";
         echo $this->userId . " ";
         echo $this->addressType . " ";
   		echo $this->CNPJ_CPF. " ";
			echo $this->compCNPJ. " ";
			echo $this->dig . " ";
			echo $this->cep . " ";
			echo $this->street. " ";
			echo $this->number. " ";
			echo $this->complement. " ";
			echo $this->district. " ";
			echo $this->city. " ";
			echo $this->state. " ";
			echo $this->country. " ";
   }
};
//------------------------------------------------------------------------------
//
// Classe User
//
//------------------------------------------------------------------------------
class User
{
	var $userId;
  var $addressId;
	var $password;
	var $fullname;
	var $CNPJ_CPF;
	var $compCNPJ;   	
  var $email;
	var $areaCode;    
	var $phone;    
	var $fax;
	var $company;
	var $companyType;
	var $activity;
	var $userType;    
	var $userStatus;    
	var $registerDate;
  var $address;
	var $bd;

   function User($parBd)
   {
			$this->userId = "";
			$this->addressId = "";
			$this->password = "";
			$this->fullname = "";
			$this->CNPJ_CPF = "";
			$this->compCNPJ = "";
			$this->email = ""; 
			$this->areaCode = ""; 
			$this->phone = "";
			$this->fax = "";
			$this->company= "";
	      $this->companyType= "";
         $this->activity= "";
			$this->userType = 0;
			$this->userStatus = ''; 
			$this->registerDate = 0; 
 		   $this->bd = $parBd;
   }
   
   function getTypeOfUser()
   {
        return $this->userType;
   }

   function getFields(&$parUserid, &$parAddressId, &$parPassword, &$parFullName,
         &$parCNPJ_CPF, &$parCompCNPJ, &$parEmail,  &$parAreaCode, &$parPhone,
         &$parFax, &$parCompany, &$parCompanyType, &$parActivity, &$parUserType,
         &$parUserStatus )
   {
			$parUserid = $this->userId;
			$parAddressId = $this->addressId;
			$parPassword = $this->password;
			$parFullName = $this->fullname;
			$parCNPJ_CPF = $this->CNPJ_CPF;
			$parCompCNPJ = $this->compCNPJ;
			$parEmail = $this->email;
			$parAreaCode = $this->areaCode;
			$parPhone = $this->phone;
			$parFax = $this->fax;
			$parCompany = $this->company;
	      $parCompanyType = $this->companyType;
         $parActivity = $this->activity;
			$parUserType = $this->userType;
			$parUserStatus = $this->userStatus;
   }

   function getFieldsSimp(&$parUserid, &$parPassword, &$parEmail, &$parFullName)
   {
			$parUserid = $this->userId;
			$parPassword = $this->password;
			$parEmail = $this->email;
			$parFullName = $this->fullname;
   }
   function setFields($parUserid, $parAddressId=0, $parPassword="", $parFullName="",
         $parCNPJ_CPF="", $parCompCNPJ="", $parEmail="",  $parAreaCode="",
         $parPhone="", $parFax="", $parCompany = "", $parCompanyType = "",
         $parActivity = "", $parUserType='' , $parUserStatus = '')
   {
			$this->userId = $parUserid;
			if($parAddressId!=0)$this->addressId = $parAddressId;
			if($parPassword!="")$this->password = $parPassword;
			if($parFullName!="")$this->fullname = $parFullName;
			if($parCNPJ_CPF!="")$this->CNPJ_CPF = $parCNPJ_CPF;
			if($parCompCNPJ!="")$this->compCNPJ = $parCompCNPJ;
			if($parEmail!="")$this->email = $parEmail;
			if($parAreaCode!="")$this->areaCode = $parAreaCode;
			if($parPhone!="")$this->phone = $parPhone;
			if($parFax!="")$this->fax = $parFax;
			if($parCompany!="")$this->company = $parCompany;
 	      if($parCompanyType!="")$this->companyType = $parCompanyType;
         if($parActivity!="")$this->activity = $parActivity;
         if($parUserType!='')$this->userType = $parUserType;
			if($parUserStatus!='')$this->userStatus = $parUserStatus;

   }
   function verifySimp ()
   {	

      $eflag = false;

      if ( (trim($this->fullname)=="") )
         $eflag = true;

      if ( (trim($this->userId)=="") )
         $eflag = true;

      if ( (trim($this->password)==""))
		    $eflag = true;
		    
      //validar o email
	   if ( (trim($this->email)=="") )
		    $eflag = true;

      return $eflag;
   }
   
   function insert()
   {
      //Se correto
		if (!$this->verifyInsert())
      {
         $dataReg = (trim($this->registerDate)=="")? date( "Y-m-d H:i:s") : trim($this->registerDate);
         $sql  = "INSERT INTO User " ;
         $sql .= "(userId, addressId, password, fullname, CNPJ_CPF, compCNPJ, email,   ";
			$sql .= "areaCode, phone, fax, company, companyType, activity, userType,  ";
         $sql .= "userStatus, registerDate, unblockDate) VALUES ('";
			$sql .= $this->userId . "'," . $this->addressId ;
			$sql .= ", PASSWORD('" . $this->password . "'), '" . $this->fullname . "','" ;
			$sql .= $this->CNPJ_CPF . "','" ;
			$sql .= $this->compCNPJ . "','" ;
   	   $sql .= $this->email . "','" ;
			$sql .= $this->areaCode .  "','" ;
			$sql .= $this->phone . "','" ;
			$sql .= $this->fax .  "','" ;
			$sql .= $this->company . "', '";
			$sql .= $this->companyType . "', '";
			$sql .= $this->activity . "', ";
			$sql .= $this->userType .  "," ;
			$sql .= $this->userStatus .  ",'" ;
			$sql .= $dataReg . "', 0)";
			
		   if(!$this->bd->query($sql))
         {
				  $this->bd->error();
              return false;
         }
         else
               return true;
      }
      return false;
   }

   function verifyInsert()     //**verificar para o novo banco
   {
      // Erro
      $objM = new message($_SESSION['userLang']);
   
      // Verificando se já existe usuário
		$sql = "SELECT * FROM User WHERE userId='$this->userId '";
		$this->bd->query($sql) or $this->bd->error($sql);
      if($count = $this->bd->numRows())
      {
			$objM->display("Usu", 1, 2);
         return true;
      }
      $this->bd->freeResult();
		
      // Verificando email
      $sql = "SELECT * FROM User WHERE email='$this->email'";
		$this->bd->query($sql) or $this->bd->error ($sql);
		if($this->bd->numRows())
      {
         $objM->display("Usu", 2, 2);
			return true;
		}
		$this->bd->freeResult();
		
		// Para pessoa física completa  - verifica CPF
		if($this->userType==3)
		{
		   $sql = "SELECT * FROM User WHERE CNPJ_CPF='$this->CNPJ_CPF '";
		   $this->bd->query($sql) or $this->bd->error ($sql);
         if($this->bd->numRows())
         {
            $objM->display("Usu", 3, 2);
            return true;
         }
	     	$this->bd->freeResult();
      }
      return false;
   }
		   
		
   function modify()
   {

      if(!$this->verifyMod())
      {
      
         //Atualizando a base de dados de usuário
         $sql  = "UPDATE User SET ";
         //$sql .= "addressId = " . $this->addressId . ", " ;
         //$sql .= "password = PASSWORD('" 	. $this->password . "'), ";
         $sql .= "fullname = '" 	. $this->fullname . "', " ;
         //$sql .= "CNPJ_CPF = '" 	. $this->CNPJ_CPF . "'," ;
         //$sql .= "compCNPJ = '" 	. $this->compCNPJ . "'," ;
	      $sql .= "email = '" 		. $this->email . "'," ;
   		$sql .= "areaCode = '" 	. $this->areaCode .  "'," ;
	     	$sql .= "phone = '" 		. $this->phone . "'," ;
       	$sql .= "fax = '" 		. $this->fax.  "'," ;	
   		$sql .= "company = '" 	. $this->company .  "'," ;
   		$sql .= "companyType = '" 	. $this->companyType .  "',";
   		$sql .= "activity = '" 	. $this->activity .  "'," ;
   		$sql .= "userType = " 	. $this->userType .  "," ;
   		$sql .= "userStatus = '" . $this->userStatus .  "'," ;
   		$sql .= "registerDate = '" . $this->registerDate .  "'" ;
   		$sql .= " WHERE userId = '" . $this->userId . "'";
         if(!$this->bd->query($sql))
         {
  	         $this->bd->error();
            return false;
         }else
            return true;
      } else
         return false;
   }
	
   function verifyMod()
   {
      // Erro
      $objM = new message($_SESSION['userLang']);
      
      // Verificando se usuário inexistente
		$sql = "SELECT * FROM User WHERE userId='$this->userId '";
		$this->bd->query($sql) or $this->bd->error($sql);
		$count = $this->bd->numRows();
      if($count == 0)
      {
         $objM->display("Usu", 4, 2);
         return true;
      }

      // Catching email
      $row = $this->bd->fetchRow();
		
      // Verificando email
      if($row["email"] != $this->email)
      {
         $sql = "SELECT * FROM User WHERE email='$this->email'";
		   $this->bd->query($sql) or $this->bd->error ($sql);
         if($this->bd->numRows())
         {
			   $objM->display("Usu", 2, 2);
			   return true;
		   }
      }
		return false;
   }
		
	// Exclui Usuário 
   function remove()
   {
      $sql  = "DELETE FROM User";
      $sql .= " WHERE userId = '". $this->userId . "'";
      
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
            return true;
   }
   
   function modifyPassword()
   {

      if(!$this->verifyMod())
      {

         //Atualizando a base de dados de usuário
         $sql  = "UPDATE User SET ";
         $sql .= "password = PASSWORD('" 	. $this->password . "') ";
   		$sql .= " WHERE userId = '" . $this->userId . "'";
         if(!$this->bd->query($sql))
         {
  	         $this->bd->error();
            return false;
         }else
            return true;
      } else
         return false;
   }
   
	function selectByUserId($parUser)
   {
      $sql  = "SELECT * FROM User WHERE userid = '";
      $sql .= $parUser .  "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }

      $nrow = $this->bd->numRows();
      if($this->bd->numRows()==1)
      {
         $row = $this->bd->fetchRow($par);
         $this->attribFromDB($row);
         return $nrow;
      }
      else
         return false;
   }

   function insertPJ($objComp, $objAdd)      // melhorar
   {
      // Verifying if User Data is correct
      if($this->verifyInsert()) return false;

      // Verify if company already exists
      $sql = "select * from Company WHERE CNPJ = '" . $this->CNPJ_CPF . "'";
      if(!$this->bd->query($sql))
      {
         $this->bd->error();
         return false;
      }
      $nrow = $this->bd->numRows();
      if(!$nrow) if(!$objComp->insert()) return false;

      // Verify if this address already exists
      $sql = "select * from Address WHERE CNPJ_CPF = '" . $this->CNPJ_CPF . "' AND compCNPJ = '" . $this->compCNPJ . "'";
      if(!$this->bd->query($sql))
      {
         $this->bd->error();
         return false;
      }
      $nrow = $this->bd->numRows();
      if(!$nrow)
      {
         if(!$objAdd->insert()) return false;
      }
      else
      {
         // If address exists, get the addressId
         $row = $this->bd->fetchRow();
         $objAdd->attribFromDB($row);
      }
      
      // AdressId
      $this->addressId = $objAdd->addressId;

      // Inserting
      if(!$this->insert()) return false;

      return true;
   }

   function insertPF($objAdd)      // melhorar- deletar endereço ou company caso tenha sido inserido
   {
      // Verifying if User Data is correct
      if($this->verifyInsert()) return false;

      // Inserting Address
      if(!$objAdd->insert()) return false;

      // AdressId
      $this->addressId = $objAdd->addressId;

      // Inserting
      if(!$this->insert()) return false;
      return true;
   }

	function selectByCNPJCPF($parCNPJ_CPF, $parComp="")
   {
      $sql  = "SELECT * FROM User WHERE CNPJ_CPF = '" . $parCNPJ_CPF .  "'";
      if(trim($parComp)!="") $sql .= "AND compCNPJ = '" . $parComp .  "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }

      $nrow = $this->bd->numRows();
      return $nrow;
   }
  	function selectByNames($parCNPJCPF="", $parNomeIni="", $parNomeFim="")
   {

      $sql = "SELECT * FROM User";
      $and = " Where ";
      if(trim($parCNPJCPF)!="")
      {
         $sql .= $and . "CNPJ_CPF =  '" . $parCNPJCPF . "'";
         $and = " AND ";
      }
      if(trim($parNomeIni)!="")
      {
         $sql .= $and . "fullname >= '" . $parNomeIni . "'";
         $and = " AND ";
      }
      if(trim($parNomeFim)!="")
      {
         $sql .= $and . "fullname <= '" . $parNomeFim . "'";
         $and = " AND ";
      }
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }

      $nrow = $this->bd->numRows();
      return $nrow;
   }
   
   function attribFromDB($row, $par="")
   {
      //$row = $this->bd->fetchRow($par);
		$this->userId = $row["userId"];
		$this->addressId = $row["addressId"];
		$this->password =$row["password"];
		$this->fullname = $row["fullname"];
		$this->CNPJ_CPF = $row["CNPJ_CPF"];
		$this->compCNPJ = $row["compCNPJ"];
		$this->email = $row["email"];;
		$this->areaCode = $row["areaCode"];
		$this->phone = $row["phone"];
		$this->fax = $row["fax"];
		$this->company = $row["company"];
		$this->companyType = $row["companyType"];
		$this->activity = $row["activity"];
		$this->userType = $row["userType"];
		$this->userStatus = $row["userStatus"];
		$this->registerDate = $row["registerDate"];
   }

   function changeStatus($parStatus)
   {
      $sql  = "UPDATE User SET ";
      $sql .= "userStatus= '" .  $this->parStatus . "'";
      if(!$this->bd->query($sql))
      {
            $this->bd->error();
            return false;
      }
      else
             return true;
   }
 
    function blocked()
		{
			if($this->userStatus == 'N')
				return false;
			else
				return true;				
		}
   function sendMailInsert($country="")
   {

      if(trim($country) == "" || trim($country) == "Brazil")
         $language = "PT";
      else
         $language = "EN";
      require ("../include/mailMessage_".$language.".php");
      $message = sprintf($mailMsgUsu1,  $this->fullname, $this->userId, $this->password);
      $message .= $mailMsgAtus;
      if(!mail ($this->email, $mailSubUsu1 , $message, $mailSender))
      {
         echo "Problema ao enviar o e-mail";
         return false;
      }
	 }
   function sendMailChange($country="")
   {

      if(trim($country) == "" || trim($country) == "Brazil")
         $language = "PT";
      else
         $language = "EN";
      require ("mailMessage_".$language.".php");
      $message = sprintf($mailMsgUsu2,  $this->fullname,  $this->userId, $this->password);
      $message .= $mailMsgAtus;
      if(!mail ($this->email, $mailSubUsu2 , $message, $mailSender))
      {
         echo "Problema ao enviar o e-mail";
         return false;
      }
	 }
    function showUser()
    {
         echo "<br> USER -> ";
			echo $this->userId . " ";
			echo $this->addressId . " ";
			echo $this->password . " ";
			echo $this->fullname . " ";
			echo $this->CNPJ_CPF . " ";
			echo $this->compCNPJ . " ";
			echo $this->email . " ";
			echo $this->areaCode . " ";
			echo $this->phone . " ";
			echo $this->fax . " ";
         echo $this->company . " ";
         echo $this->companyType . " ";
         echo $this->activity . " ";
			echo $this->userType . " ";
			echo $this->userStatus . " ";
			echo $this->registerDate . " ";
   }

};
//------------------------------------------------------------------------------
//
// Funções Genéricas
//
//------------------------------------------------------------------------------
// PESQUISAS
function searchByCNPJ(&$bd, &$matU, &$matA, &$matC, $order, $parCNPJCPF="", $parCompCNPJ="")
{
   $and = " WHERE ";

   $sql  = "SELECT * FROM User inner join Address on User.addressId = Address.addressId ";
   $sql .= "inner join Company on Company.CNPJ = User.CNPJ_CPF ";
   if(trim($parCNPJCPF)!="")
   {
      $sql .= $and . "User.CNPJ_CPF =  '" . $parCNPJCPF . "'";
      $and = " AND ";
   }
   if(trim($parCompCNPJ)!="")
   {
      $sql .= $and . "User.compCNPJ =  '" . $parCompCNPJ . "'";
      $and = " AND ";
   }
   if(trim($order)!="")  $sql .= " ORDER By " . $order;

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }

   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matU[$i] = new User($bd);
      $matA[$i] = new Address($bd);
      $matC[$i] = new Company($bd);
      $matU[$i]->attribFromDB($row);
      $matA[$i]->attribFromDB($row);
      $matC[$i]->attribFromDB($row);
   }
   return $nrow;
}
function searchByCPF(&$bd, &$matU, &$matA, &$matC, $order, $parCNPJCPF)
{
//   $sql  = "SELECT * FROM User, Address WHERE User.userType=3 AND User.addressId = Address.addressId ";
   $sql  = "SELECT * FROM User, Address WHERE User.userType=4 AND User.addressId = Address.addressId ";
   if(trim($parCNPJCPF)!="") $sql .= " AND User.CNPJ_CPF =  '" . $parCNPJCPF . "'";
   if(trim($order)!="" and $order!="Company.name")  $sql .= " ORDER By " . $order;

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }

   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matU[$i] = new User($bd);
      $matA[$i] = new Address($bd);
      $matC[$i] = new Company($bd);
      $matU[$i]->attribFromDB($row);
      $matA[$i]->attribFromDB($row);
   }

   return $nrow;
}
function searchByUserid(&$bd, &$matU, &$matA, &$matC, $order, $parUserId="", $tpPesq=0)
{

   // Search all user types
   $sql  = "SELECT * FROM User";
   if(trim($parUserId)!="")
   {
      if(!$tpPesq)
      {
         $parUserId = "%" . trim($parUserId) . "%";
         $sql .= " WHERE userId LIKE '" . $parUserId . "'";
      }
      else
      {
         $sql .= " WHERE userId = '" . $parUserId . "'";
      }
   }
   if(trim($order)!="" and $order!="Address.city") $sql .= " ORDER By " . $order;
   //echo "SQL Userid - " . $sql;
   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matU[$i] = new User($bd);
      $matA[$i] = new Address($bd);
      $matC[$i] = new Company($bd);
      $matU[$i]->attribFromDB($row);
   }

   // For each user, search address and company depending on user type
   for($i=0; $i <= $nrow; $i++)
   {
      if($matU[$i]->userType>1)
      {
         $sql  = "SELECT * FROM Address WHERE addressId =  '" . $matU[$i]->addressId . "'";
         if(!$bd->query($sql))
         {
            $bd->error();
            return false;
         }
         $row = $bd->fetchRow();
         $matA[$i]->attribFromDB($row);
      }
      
      if($matU[$i]->userType>3)   //***???
      {
         $sql  = "SELECT * FROM Company WHERE CNPJ =  '" . $matU[$i]->CNPJ_CPF . "'";
         if(!$bd->query($sql))
         {
            $bd->error();
            return false;
         }
         $row = $bd->fetchRow();
         $matC[$i]->attribFromDB($row);
      }
   }

   return $nrow;
}
function searchByGeneric(&$bd, &$matU, &$matA, &$matC, $order, $parUserSearch, $parUserStatus,
   $nameFrom, $nameTo, $city, $state, $country, $email, $compNameFrom, $compNameTo)
{
   // And
   $and = " WHERE ";

    // UserStatus
   for($i=1, $selStatus=0; $i <= $matStatus[0]; $i++)
      if($matStatus[$i]==$parUserStatus) $selStatus = $i;
      
   // Setting Search Criteria

   if($parUserSearch==4)
   {
      $sql  = $sql  = "SELECT * FROM User inner join Address on User.addressId = Address.addressId ";
//      $sql .= "inner join Company on Company.CNPJ = User.CNPJ_CPF ";
   }
   else if(trim($city)!="" or trim($state)!="" or trim($country)!="" )
   {
      $sql  = "SELECT * FROM User inner join Address on User.addressId = Address.addressId";
      if($order=="Company.name") $order = "";
   }
   else
   {
      $sql  = "SELECT * FROM User ";
      if($order=="Company.name" or $order=="Address.city") $order = "";
   }

   // Seaching for userType
   if($parUserSearch)
   {
      $sql .= $and . "User.userType = " . $parUserSearch;
      $and = " AND ";
   }
      
   // Seaching for userStatus
   if($parUserStatus)
   {
      $sql .= $and . "User.userStatus = " . $parUserStatus;
      $and = " AND ";
   }

   // Seaching for nameFrom
   if(trim($nameFrom)!="")
   {
      $sql .= $and . "User.fullname >= '" . $nameFrom . "'";
      $and = " AND ";
   }

   // Seaching for nameTo
   if(trim($nameTo)!="")
   {
      $nameTo = trim($nameTo) . "z";
      $sql .= $and . "User.fullname <= '" . $nameTo . "'";
      $and = " AND ";
   }
   
   // Seaching for city
   if(trim($city)!="")
   {
      $sql .= $and . "Address.city LIKE '%" . trim($city) . "%'";
      $and = " AND ";
   }

   // Seaching for city
   if(trim($state)!="")
   {
      $sql .= $and . "Address.state = '" . $state. "'";
      $and = " AND ";
   }

   // Seaching for country
   if(trim($country)!="")
   {
      $sql .= $and . "Address.country = '" . trim($country) . "'";
      $and = " AND ";
   }
   // Seaching for emial
   if(trim($email)!="")
   {
      $sql .= $and . "User.email LIKE '%" . trim($email) . "%'";
      $and = " AND ";
   }

  // Seaching for compNameFrom
   $field = ($parUserSearch==4)?"Company.name":"User.company";
   if(trim($compNameFrom)!="")
   {
      $sql .= $and . $field . " >= '" . $compNameFrom . "'";
      $and = " AND ";
   }

   // Seaching for compNameFrom
   if(trim($compNameTo)!="")
   {
      $compNameTo = trim($compNameTo) . "z";
      $sql .= $and . $field . " <= '" . $compNameTo . "'";
      $and = " AND ";
   }
   
   if(trim($order)!="")  $sql .= " ORDER By " . $order;
   //echo "SQL Generico - " . $sql;
   
   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();

   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matU[$i] = new User($bd);
      $matA[$i] = new Address($bd);
      $matC[$i] = new Company($bd);
      $matU[$i]->attribFromDB($row);
   }
   for($i=0; $i <= $nrow; $i++)
   {
      if($matU[$i]->userType>1)
      {
         $sql  = "SELECT * FROM Address WHERE addressId =  '" . $matU[$i]->addressId . "'";
         if(!$bd->query($sql))
         {
            $bd->error();
            return false;
         }
         $row = $bd->fetchRow();
         $matA[$i]->attribFromDB($row);
      }

      if($matU[$i]->userType>3)
      {
         $sql  = "SELECT * FROM Company WHERE CNPJ =  '" . $matU[$i]->CNPJ_CPF . "'";
         if(!$bd->query($sql))
         {
            $bd->error();
            return false;
         }
         $row = $bd->fetchRow();
         $matC[$i]->attribFromDB($row);
      }
   }
     
   return $nrow;
}

// Address
function searchAddrByUserid( &$bd, &$matU, &$matA, $parUserId="" )
{

   // Search all user types
   $sql  = "SELECT * FROM User inner join Address on User.addressId = Address.addressId";


   if( trim($parUserId) != "" )
   {
      $sql .= " WHERE User.userId = '" . $parUserId . "'";
   }

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }

   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matU[$i] = new User($bd);
      $matA[$i] = new Address($bd);
      $matU[$i]->attribFromDB($row);
      $matA[$i]->attribFromDB($row);
   }

   return $nrow;
}

// This function finds all addresses related to user '$parUserId' and stores
// it into '$matA' vector.
function searchAddressesByUserid( &$bd, &$matA, $parUserId="" )
{
  // Search all user types
  $sql  = "SELECT * FROM Address";

  if( trim($parUserId) != "" ) {
    $sql .= " WHERE Address.userId = '" . $parUserId . "'";
  }

  if( !$bd->query( $sql ) ) {
    $bd->error();
    return false;
  }
  
  $nrow = $bd->numRows();

  for( $i = 0; $i <= $nrow; $i++ ) {
    $row        = $bd->fetchRow();
    $matA[ $i ] = new Address( $bd );
    $matA[ $i ]->attribFromDB( $row );
  }

  return $nrow;
}

function searchAddrByCNPJ_CPF(&$bd, &$matA, $parCNPJ="", $parComp="")
{

   // Search all user types
   $sql  = "SELECT * FROM Address";
   if(trim($parCNPJ)!="")
      $sql .= " WHERE CNPJ_CPF = '" . $parCNPJ . "'";
   else 
   {
     $nrow = 0;
     return $nrow;
   }
   if(trim($parComp)!="")
      $sql .= " and compCNPJ = '" . $parComp . "'";

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matA[$i] = new Address($bd);
      $matA[$i]->attribFromDB($row);
   }

   return $nrow;
}
function searchAddrById(&$bd, &$matA, $parId="")
{

   // Search all user types
   $sql  = "SELECT * FROM Address";
   if(trim($parId)!="") $sql .= " WHERE addressId = " . $parId;

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matA[$i] = new Address($bd);
      $matA[$i]->attribFromDB($row);
   }
   return $nrow;
}
function searchAddrType(&$bd, &$matA, $parId="", $type="")
{

   // Search all user types
   $sql  = "SELECT * FROM User inner join Address on User.addressId = Address.addressId";
   if(trim($parUserId)!="")
   {
      $sql .= " WHERE User.userId = '" . $parUserId . "' and Address.addressType = '" . $type . "'";
   }

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matA[$i] = new Address($bd);
      $matA[$i]->attribFromDB($row);
   }
   return $nrow;
}
// Search Functions - Company
function searchCompanyByCNPJ(&$bd, &$matC, $parCNPJ="")
{

   // Search all user types
   $sql  = "SELECT * FROM Company";
   if(trim($parCNPJ)!="") $sql .= " WHERE CNPJ = '" . $parCNPJ . "'";

   if(!$bd->query($sql))
   {
         $bd->error();
         return false;
   }
   $nrow = $bd->numRows();
   for($i=0; $i <= $nrow; $i++)
   {
      $row = $bd->fetchRow();
      $matC[$i] = new Company($bd);
      $matC[$i]->attribFromDB($row);
   }
   return $nrow;
}
?>
