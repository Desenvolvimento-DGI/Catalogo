<?php
/*
  Usertype Values
  
	=====================================
  |    Value     |    Meaning         |
  =====================================
  |     1        | Operation Manager  |
  -------------------------------------
  |     2        | Internet User      |
  -------------------------------------
  |     3        | INPE's employees   |
  -------------------------------------
  |     4        | User with CNPJ/CPF |
  -------------------------------------
  |     5        | User from abroad   |
  -------------------------------------
  
*/
include("session_mysql.php");
session_start();
//	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
require ("register_".$_SESSION['userLang'].".php");
//include_once("dbglobals.inc.php");
include_once("globals.php");
import_request_variables("gpc");
?>
<html>
<head>
<title>Registro</title>
<?php include("css.php");?>
<style type="text/css">
<!--
body { padding-left:1px;
	background-color: #000;margin-top:0px; padding-top:0px;
}
.FUNDOResp{background-image:url(../siteDgi/imagens/tabelaTitulo.jpg); color:#000; height:15px; size:12px; margin-left:3px; }
.FUNDOResp22{background-color:#000000; height:15px; size:12px; margin-left:3px; }
.style1 {color: #FFFFFF; margin-left:3px;}
.style6 {background-color: #000000; height: 15px; size: 12px; margin-left: 3px; color: #CCFF33; }
.style16 {color: #FFFFFF; margin-left: 3px; font-size: 13px; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
</head>
<body>
<div><center>
<?php
require ("validateEmail.php");
$today = date("Y-m-d");
//$dbcat = $GLOBALS["dbcadastro"];
$dbcat = $GLOBALS["dbusercat"];

if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
	
if (!isset($country)) $country = "Brazil";

// If user is logged in, get user record

$updating = false;
if(($submitted && isset($_SESSION['userId']))) $updating = true;
else if ($submitted && $action==$strBupdate && !isset($_SESSION['userId'])) die ("<h3>$strNoupdate<h3>");

if(($submitted || isset($_SESSION['userId'])))
{
	if(!isset($action) && isset($_SESSION['userId']))
	{
		$sql = "SELECT * FROM User,Address WHERE User.userId='".$_SESSION['userId']."' AND User.addressId=Address.addressId";
		$dbcat->query($sql) or $dbcat->error ($sql);
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
		$count = $dbcat->numRows();
		if ($count == 1)
		{
			$row = $dbcat->fetchRow();
			extract($row);
			$userId = $row[0]; // BEWARE !!!  The field "userId" is identically defined in both tables "User" and "Address" ;
			                   // so the function "extract" will overwrite the field "userId" with its later value from "Address" 
			                   // which has been recently added to this table and may be empty for most users !!!
		}
		$dbcat->freeResult($result);
	}

// Check if all the form entries are entered, if any form entry
// is missing then send error message.
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

/*	if( (trim($CNPJ_CPF)=="") )
	{
		$eflag = true;
		$fields .= " CPF,";
	} */

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

	if ( (trim($email)=="") )
	{
		$eflag = true;
		$fields .= " Email".",";
	}
	else
	{
		$result = ValidateMail ($email);
		if (!$result[0])
		{
			$eflag = true;
			echo ("<h4>" . $strMess5 . "$result[1]<h4><br></font>");
		}
	}
#
# Let's see if user is Internal (INPE's employee)
#
  $inpe = false;
  $_SESSION['userIP'] = $_SERVER["REMOTE_ADDR"];
  if (substr($_SESSION['userIP'],0,8) == "150.163.") $inpe = true;
  if( $inpe )
      $userType = 3;
/*  elseif( $CNPJ_CPF == "" )
      $userType = 2;
  else
      $userType = 4; */
  else $userType = 2;

	if ($eflag)
	{
		$fields = substr($fields,0,strlen($fields)-1);
		echo $strMess0."<b>".$fields."</b><br></font>";
	}
	else if (!$eflag && $action==$strBregister)
	{
		$sql = "SELECT * FROM User WHERE userId='$userId'";
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
		$dbcat->query($sql) or $dbcat->error ($sql);
		$countu = $dbcat->numRows();
		$dbcat->freeResult();
		$sql = "SELECT * FROM User WHERE email='$email'";
		$dbcat->query($sql) or $dbcat->error ($sql);
		$counte = $dbcat->numRows();
		$dbcat->freeResult();
		if ($countu == 0 && $counte == 0)
		{
//
            
			$sql = "INSERT INTO Address SET
			userid='$userId',
			street='$street',
			number='$number',
			CNPJ_CPF='$CNPJ_CPF',
			cep='$cep',
			country='$country',
			city='$city',
			state='$state'
			";
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
			$dbcat->query($sql) or $dbcat->error ($sql);
			$addressId = $dbcat->insertId();

			$sql = "INSERT INTO User SET
			userId='$userId',
			addressId='$addressId',
			password=PASSWORD('$password'),
			fullname='$fullname',
			CNPJ_CPF='$CNPJ_CPF',
			areaCode='$areaCode',
			phone='$phone',
			email='$email',
			company='$company',
			companyType='$companyType',
			activity='$activity',
			userType='$userType',
			registerDate='$today'
			";
			
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
			$dbcat->query($sql) or $dbcat->error ($sql);

			echo "<h3> $strUsr $userId $strMess2 <h3>";
			echo ("<h3><center><p>¡Hecho! Usted puede pedir imágenes de satélites.</p>
<p>volte a Pagina inicial </p></center></h3>"); 
		}
		else
		{
			if ($counte == 0)
				echo "<h3> $strMess1 <h3>";
			else if ($countu == 0)
				echo "<h3> Email $strMess3 <h3>";
			else echo "<h3> User $strMess3 <h3>";
		}
	}
	else if (!$eflag && $action==$strBupdate && isset($_SESSION['userId']))
	{
		$sql = "UPDATE User SET ";
		if ($updating && (trim($password)!=""))
			$sql .= "password=PASSWORD('$password'),";
		$sql .= "fullname='$fullname',
		areaCode='$areaCode',
		phone='$phone',
		CNPJ_CPF='$CNPJ_CPF',
		email='$email',
		company='$company',
		companyType='$companyType',
		userType='$userType',
		activity='$activity'
		WHERE userId='".$_SESSION['userId']."'";
		
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
		$dbcat->query($sql) or $dbcat->error ($sql);
		$sql = "UPDATE Address SET
		CNPJ_CPF='$CNPJ_CPF',
		street='$street',
		number='$number',
		cep='$cep',
		city='$city',
		state='$state',
		country='$country'
		WHERE addressId='$addressId'";
		if ($GLOBALS["stationDebug"])
		echo "$sql <br>";
		$dbcat->query($sql) or $dbcat->error ($sql);

		echo "<h3> $strUsr $userId $strMess4 <h3>";
    echo ("<h3><center><p>¡Hecho! Usted puede pedir imágenes de satélites.</p>
<p>Volte a Pagina inicial </p> </center></h3>"); 
	}
	else if (!isset($_SESSION['userId'])) die ("<h3>$strNoupdate<h3>");
}
else
	$password = ""; 
?>

<form method="post" action="siteDgi_register_EN.php">
<table border="0">
      <th colspan="2" class="style6">Catastro</th>
<tr><td class="FUNDOResp"><span class="style16">
<?=$strFullname?>
</span></td>
<td class="FUNDOResp"><input name="fullname"  tabindex="1" value="<?=$fullname?>" size="48"></td></tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strUsr?>
</span></td>
<td class="FUNDOResp"><input name="userId"  tabindex="2"value="<?=$userId?>" ><font color="#CCCCCC"><?=$strPalavra?></font></td></tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strPass?>
</span></td>
<td class="FUNDOResp"><input name="password" type="password" tabindex="3"></td></tr>
<tr ><td class="FUNDOResp"><span class="style16">Email</span></td>
<td class="FUNDOResp"><input name="email"  tabindex="4"value="<?=$email?>" size="48"></td></tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strPhone?>
</span></td>       
<td class="FUNDOResp"valign="middle"><span class="style1">
  <input name="areaCode" tabindex="5" size="2" value="<?=$areaCode?>">
  <input name="phone" tabindex="6" size="8" value="<?=$phone?>">
</span></td></tr>
<tr><td class="FUNDOResp">
  <span class="style16">
<? /* php if($country == "Brazil")
       if ($GLOBALS["paymentTestMode"] ) { ?>
   <?="CPF"?></td><td><input name="CNPJ_CPF" tabindex="7" size="11" value="<?=$CNPJ_CPF?>"></td></tr>
   <tr><td>
<?php } else { ?>
   <input type="hidden" name="CNPJ_CPF" tabindex="7" size="11" value="<?=$CNPJ_CPF?>">
<?php } */ ?>
<?=$strStreet?>
  </span></td>
<td class="FUNDOResp"><span class="style1">
  <input name="street" tabindex="8" size="48" value="<?=$street?>">
    <?=$strNumber?>
    <input name="number" tabindex="9" size="6" value="<?=$number?>">
</span></td></tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strZip?>
</span></td>            
<td class="FUNDOResp"><input name="cep" tabindex="10" size="9" value="<?=$cep?>"></td></tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strCity?>
</span></td>
<td class="FUNDOResp"><input name="city" size="48" tabindex="11" value="<?=$city?>"></td></tr>
<?php if($country == "Brazil")
{
?>
 
    <tr>
  
<td class="FUNDOResp"><span class="style16">
  <?=$strState?>
</span></td>
<td class="FUNDOResp">
<select name="state" tabindex="12" >
<option selected><?=$state?>
<option value="  "></option>
<option value="AC">AC</option>
<option value="AL">AL</option>
<option value="AP">AP</option>
<option value="AM">AM</option>
<option value="BA">BA</option>
<option value="DF">DF</option>
<option value="CE">CE</option>
<option value="ES">ES</option>
<option value="GO">GO</option>
<option value="MA">MA</option>
<option value="MT">MT</option>
<option value="MS">MS</option>
<option value="MG">MG</option>
<option value="PA">PA</option>
<option value="PB">PB</option>
<option value="PE">PE</option>
<option value="PI">PI</option>
<option value="PR">PR</option>
<option value="RJ">RJ</option>
<option value="RN">RN</option>
<option value="RO">RO</option>
<option value="RR">RR</option>
<option value="RS">RS</option>
<option value="SC">SC</option>
<option value="SE">SE</option>
<option value="SP">SP</option>
<option value="TO">TO</option>
</select></td>
   </tr>
  <span class="style1"></span>

<?php
}
?>
 
    <tr>
  
<td class="FUNDOResp"><span class="style16">
  <?=$strCountry?>
</span></td>
<td class="FUNDOResp">

    <select name=country tabindex="13" >
  </span>

<option selected><?=$country?>
<?php
	$sql = "SELECT DISTINCT(COUNTRY_NAME) FROM ipToCountry ORDER BY COUNTRY_NAME";
		$dbcat->query($sql) or $dbcat->error ($sql);
		while ($row = $dbcat->fetchRow())
		{
			$country = ucwords(strtolower($row["COUNTRY_NAME"]));
			echo "<option value=\"$country\">$country</option>\n";
		}
		$dbcat->freeResult($result);
?>
</select></td>  </tr>


<tr><td class="FUNDOResp"><span class="style16">
  <?=$strOrganization?>
</span></td>
<td class="FUNDOResp"><input name="company" size="48" tabindex="14" value="<?=$company?>"></td></tr>
<tr class="FUNDOResp"><td class="FUNDOResp">
  <span class="style16">
  <?=$strOrgType?>
  </span></td>
<td class="FUNDOResp">
<select name="companyType" tabindex="15">
<option selected><?=$companyType?>
<option value="Commerce"><?=$strCommerce?></option>
<option value="Government Company"><?=$strStateComp?></option>
<option value="Private Company"><?=$strPrivtComp?></option>
<option value="Higher Education"><?=$strBasicEduc?></option>
<option value="College"><?=$strHigherEduc?></option>
<option value="Specialization"><?=$strGradEduc?></option>
<option value="Local Government"><?=$strLocalGovr?></option>
<option value="Provincial Government"><?=$strStateGovr?></option>
<option value="Federal Government"><?=$strFederGovr?></option>
<option value="Consultance"><?=$strConsultant?></option>
<option value="NGO"><?=$strNGO?></option>
<option value="Other"><?=$strOther?></option>
</select></td>
</tr>
<tr><td class="FUNDOResp"><span class="style16">
  <?=$strActivity?>
</span></td>
<td class="FUNDOResp">
<select name="activity" tabindex="16">
<option selected><?=$activity?> 
<option value="Agriculture"><?=$strAgricult?></option>
<option value="Biology"><?=$strBiology?></option>
<option value="Cartography"><?=$strCartograf?></option>
<option value="Environment"><?=$strEnvironment?></option>
<option value="Education"><?=$strEducation?></option>
<option value="Forest"><?=$strForestry?></option>
<option value="Geography"><?=$strGeography?></option>
<option value="Geology"><?=$strGeology?></option>
<option value="Health"><?=$strHealth?></option>
<option value="Hidrology"><?=$strHidrology?></option>
<option value="Images Processing"><?=$strImageProc?></option>
<option value="Planning"><?=$strPlannig?></option>
<option value="Socio-economy"><?=$strSocioEcon?></option>
<option value="Transportation"><?=$strTransport?></option>
<option value="Other"><?=$strOther?></option>
</select></td>
</tr>
<tr><td style="background-color:#000" colspan="4"></td></tr>
<tr><td class="FUNDOResp" align="center" colspan="3">
	<input type="submit" tabindex="17" value=<?=$strBregister?> name="action">
	<input type="submit"  tabindex="18" value=<?=$strBupdate?> name="action">
   
          	                    
	</td>
</tr>
<input type=hidden name=submitted value=1>
<input type=hidden name=addressId value=<?=$addressId?>>
</table>
</form>
</div></center>
</body>
</html>


