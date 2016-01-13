<?php
/*
  Usertype Values
  
  =================================
  |    Value     |    Meaning     |
  =================================
  |     1        | Internal User  |
  ---------------------------------
  |     2        | Internet User  |
  ---------------------------------
  |     3        | User with CPF  |
  ---------------------------------
  |     4        | User with CNPJ |
  ---------------------------------
  
  
*/
include("session_mysql.php");
session_start();
//------------------------------------------------------------------------------
//  Denise
//
// >>>v1
//------------------------------------------------------------------------------
include ("globals.php");
include_once("user.class.php");
include_once("message.class.php");

echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']. " <br>\n";

// Setting Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
// Including Files
require ("register_".$_SESSION['userLang'].".php");
require ("arrays_". $_SESSION['userLang'].".php");
import_request_variables("gpc");
require ("validateEmail.php");

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat = $GLOBALS["dbusercat"];
if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
	
// Variables
$objErr = new message($_SESSION['userLang']);
if (!isset($country)) $country = "Brazil";
if (!isset($userType)) $userType = 2;

// Setting Flags
$logged = false;
if(isset($_SESSION['userId'])) $logged = true;
$updating = false;
if(($submitted && $logged)) $updating = true;

// If logged, get the fields
if($logged and !$action==$strBupdate)
{
   searchByUserid($dbusercat, $matU, $matA, $matC, $order, $_SESSION['userId'] , 1);
   $matU[0]->getFields($parUserid, $parAddressId, $password, $fullname,$parCNPJ_CPF, $parCompCNPJ,
         $email,  $areaCode, $phone, $parFax, $company, $companyType, $activity, $userType, $parUserStatus);

   if($userType == 2)
      $matA[0]->getFields($addressType, $CNPJ_CPF, $compCNPJ, $parDig, $cep, $street, $number,
         $parcomplement, $pardistrict, $city, $state, $country);
   for($i=1; $i <= $matCompanyType[0]; $i++) if($matCompanyType[$i]==$companyType) $selCompany = $i;
   for($i=1; $i <= $matActivity[0]; $i++) if($matActivity[$i]==$activity) $selActivity = $i;
   for($i=1; $i <= $matState[0]; $i++) if($matState[$i]==$state) $selState = $i;
}
// If submitted, take the action
if($submitted)
{
	$eflag = false;
	$fields = "";
	if ( (trim($fullname)=="") )
	{
		$eflag = true;
		$fields .= " ".$strFullname.",";
	}

	if ( (trim($userId)=="") )
	{
		$eflag = true;
		$fields .= " ".$strUsr.",";
	}

	if (!$updating && (trim($password)==""))
	{
		$eflag = true;
		$fields .= " ".$strPass.",";
	}
	
		if ( (trim($areaCode)=="") || (trim($phone)==""))
	{
		$eflag = true;
		$fields .= " ".$strPhone.",";
	}
	
	if ( (trim($email)=="") )
	{
		$eflag = true;
		$fields .= " E-mail".",";
	}
	else
	{
		$result = ValidateMail ($email);
		if (!$result[0])
		{
			$eflag = true;
			$objErr->display("","", 2, $strMess5.$result[1]);
		}
	}	
	
	if($userType==2)
  {
      if ( (trim($street)=="") )
      {
         $eflag = true;
         $fields .= " ".$strStreet.",";
      }

      if ( (trim($number)==""))
      {
         $eflag = true;
         $fields .= " ".$strNumber.",";
      }

      if ( (trim($cep)==""))
      {
         $eflag = true;
         $fields .= " ".$strZip.",";
      }
      if ( (trim($city)==""))
      {
         $eflag = true;
         $fields .= " ".$strCity.",";
      }

      if ( (trim($country)==""))
      {
         $eflag = true;
         $fields .= " ".$strCountry.",";
      }

      if ($country=="Brazil" && (trim($state)==""))
      {
         $eflag = true;
         $fields .= " ".$strState.",";
      }
      
      	if ( (trim($company)==""))
    	{
		     $eflag = true;
		     $fields .= " ".$strOrganization.",";
	    }
	
	      if ( (trim($companyType)==""))
	    {
		     $eflag = true;
		     $fields .= " ".$strOrgType.",";
	    }
	
	      if ( (trim($activity)==""))
	    {
		     $eflag = true;
		     $fields .= " ".$strActivity.",";
	    }

   }
   // Error
  	if ($eflag)
	{
		$fields = substr($fields,0,strlen($fields)-1);
		echo $strMess0."<b>".$fields."</b><br></font>";
	}
	// Inserting User
   else if (!$eflag && $action==$strBregister && !$logged)
	{
      $objAddr = new Address($dbusercat);
      $objAddr->setFields('S', "", "", "", $cep, $street, $number, "", "", $city, $state, $country);
      $objUser = new User($dbusercat);
      $objUser->setFields($userId, $objAddr->addressId, $password, $fullname, "", "",
            $email,  $areaCode, $phone, "", $company , $companyType, $activity, 2 , 1);
      if($objUser->insertPF($objAddr))
         echo " $strUsr $userId $strMess2 ";
      else
         echo $strMess6;
   }
	// Modifying User
	else if (!$eflag && $action==$strBupdate && $logged)
	{
      searchByUserid($dbusercat, $matU, $matA, $matC, $order, $_SESSION['userId'] , 1);
      $matU[0]->setFields($userId, 0, $password, $fullname, "", "",
            $email,  $areaCode, $phone, "", $company , $companyType, $activity);
      if(!$matU[0]->modify()) $eflag = true;
      if(!$eflag and $password!="") if(!$matU[0]->modifyPassword()) $eflag = true;
      if(!$eflag and $userType==2) 
      {
         $matA[0]->setFields('', "", "", "", $cep, $street, $number, "", "", $city, $state, $country);
         if(!$matA[0]->modify()) $eflag = true;
      }
      if($eflag)
         echo $strMess7;
      else
         echo " $strUsr $userId $strMess4 ";
	}
}
else
	$password = "";
?>
<html>
<head>
<title>Registro</title>
<?php include("css.php");?>
</head>
<body>
<div><center>
<form method="post" action="register.php">
<table border="0">
<th colspan="2"><?=$strRegister?></th>
<tr><td><?=$strFullname?></td><td><input name="fullname" tabindex="1" size="48" value="<?=$fullname?>"></td></tr>
<tr><td><?=$strUsr?></td><td><input name="userId" tabindex="2"value="<?=$userId?>" ></td></tr>
<tr><td><?=$strPass?></td><td><input type="password" name="password" tabindex="3"></td></tr>
<tr><td>E-mail</td><td><input name="email" tabindex="4" size="48"value="<?=$email?>"></td></tr>
<tr><td><?=$strPhone?></td>      <td valign="middle">(<input name="areaCode" tabindex="5" size="2" value="<?=$areaCode?>">)
                            <input name="phone" tabindex="6" size="8" value="<?=$phone?>"></td></tr>
<?php

if($userType==2)
{
?>
<tr><td><?=$strStreet?></td><td><input name="street" tabindex="7" size="48" value="<?=$street?>">
    <?=$strNumber?><input name="number" tabindex="8" size="6" value="<?=$number?>"></td></tr>
<tr><td><?=$strZip?></td>            <td><input name="cep" tabindex="9" size="9" value="<?=$cep?>">
</td>
</tr>
<tr><td><?=$strCity?></td>
<td><input name="city" size="48" tabindex="10" value="<?=$city?>"></td></tr>
<tr><td><?=$strState?></td>
<td>
<select name="state" tabindex="11" >
<option selected><?=$state?>
<?php
      for($j = 2; $j <= $matState[0]; $j++)
      {
?>
         <option value="<?=$matState[$j]?>" <?=($selState==$j)?"selected" : ""?>><?=$matState[$j]?></option>
<?
      }
?>
</select>
</td>
</tr>
<tr><td><?=$strCountry?></td><td>
<select name=country tabindex="11" >
<option selected><?=$country?>
<?php
	$sql = "SELECT DISTINCT(COUNTRY_NAME) FROM ipToCountry ORDER BY COUNTRY_NAME";
		$dbusercat->query($sql) or $dbusercat->error ($sql);
		while ($row = $dbusercat->fetchRow())
		{
			$country = ucwords(strtolower($row["COUNTRY_NAME"]));
			echo "<option value=\"$country\">$country</option>\n";
		}
		$dbusercat->freeResult($result);
?>
</select>
</td></tr>
<tr><td><?=$strOrganization?></td>
<td><input name="company" size="48" tabindex="12" value="<?=$company?>"></td></tr>
<tr><td><?=$strOrgType?></td>
<td>
<select name="companyType" tabindex="13">
<option selected><?=$companyType?>
<?php
   for($j = 1; $j <= $matCompanyType[0]; $j++)
   {
?>
      <option value="<?=$matCompanyType[$j]?>" <?=($selCompany==$j)?"selected" : ""?>><?=$matCompanyType[$j]?></option>
<?
   }
?>
</select>
</td>
</tr>
<?
}
?>
<tr><td><?=$strActivity?></td>
<td>
<select name="activity" tabindex="14">
<option selected><?=$activity?>
<?php
   for($j = 1; $j <= $matActivity[0]; $j++)
   {
?>
      <option value="<?=$matActivity[$j]?>" <?=($selActivity==$j)?"selected" : ""?>><?=$matActivity[$j]?></option>
<?
   }
?>
</select>
</td>
</tr>
<tr><td colspan="4"></td></tr>
<tr><td align="center" colspan="2">
  <?if(!$logged) { ?>	<input type="submit" value=<?=$strBregister;?> name="action"> <? };?>
	<input type="submit" value=<?=$strBupdate?> name="action">
	<input type="button" value=<?=$strOut?> onClick="window.location.href = 'first.php'">

	</td>
</tr>
<input type=hidden name=submitted value=1>
<input type=hidden name=parAddressId value=<?=$parAddressId?>>
<input type=hidden name=userType value=<?=$userType?>>
</table>
</form>
</div></center>
</body>
</html>
