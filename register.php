<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

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
<!--?php include("css.php");?-->

<!-- Estilos -->
<link href="/catalogo/css/bootstrap.css" rel="stylesheet">
<!--link href="/catalogo/css/style.css" rel="stylesheet"-->
<!--link href="/catalogo/css/camera.css" rel="stylesheet"-->
<link href="/catalogo/css/icons.css" rel="stylesheet">


<!-- 
O arquivo abaixo define o configuração de cores a serem utilizadas
As opções disponíveis são:

skin-blue.css				skin-green.css			skin-red.css
skin-bluedark.css			skin-green2.css			skin-red2.css
skin-bluedark2.css			skin-grey.css			skin-redbrown.css
skin-bluelight.css			skin-khaki.css			skin-teal.css
skin-bluelight2.css   		skin-lilac.css			skin-teal2.css
skin-brown.css				skin-orange.css			skin-yellow.css
skin-brown2.css				skin-pink.css			
-->
<link href="/catalogo/css/skin-blue.css" rel="stylesheet">
<link href="/catalogo/css/bootstrap-responsive.css" rel="stylesheet">
<!--link href="/catalogo/css/bootstrap.3.3.min.css" rel="stylesheet"-->    

</head>
<body style="background-color:#FDFDFD">
<div  class="row-fluid" style="background-color:#FDFDFD">
<center>
<?php
require ("validateEmail.php");
$today = date("Y-m-d");
//$dbcat = $GLOBALS["dbcadastro"];
$dbcat = $GLOBALS["dbusercat"];

// --- if ($GLOBALS["stationDebug"])
// --- echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
	
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
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
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
		$fields .= " E-mail".",";
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
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
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
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
			$dbcat->query($sql) or $dbcat->error ($sql);
			$addressId = $dbcat->insertId();

			$sql = "INSERT INTO User SET
			userId='$userId',
			addressId='$addressId',
			password=OLD_PASSWORD('$password'),
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
			
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
			$dbcat->query($sql) or $dbcat->error ($sql);

			echo "<h3> $strUsr $userId $strMess2 <h3>";
			echo ("<h3><center><a href=first_" . $_SESSION['userLang'] . ".php>OK</a></center></h3>"); 
		}
		else
		{
			if ($counte == 0)
				echo "<h3> $strMess1 <h3>";
			else if ($countu == 0)
				echo "<h3> E-mail $strMess3 <h3>";
			else echo "<h3> Usu&aacute;rio $strMess3 <h3>";
		}
	}
	else if (!$eflag && $action==$strBupdate && isset($_SESSION['userId']))
	{
		$sql = "UPDATE User SET ";
		if ($updating && (trim($password)!=""))
			$sql .= "password=OLD_PASSWORD('$password'),";
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
		
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
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
		// --- if ($GLOBALS["stationDebug"])
		// --- echo "$sql <br>";
		$dbcat->query($sql) or $dbcat->error ($sql);

		echo "<h3> $strUsr $userId $strMess4 <h3>";
    echo ("<h3><center><a href=first_" . $_SESSION['userLang'] . ".php>OK</a></center></h3>"); 
	}
	else if (!isset($_SESSION['userId'])) die ("<h3>$strNoupdate<h3>");
}
else
	$password = ""; 
?>

<br>

<form method="post" action="register.php">

<table border="0" cellpadding="0" cellspacing="0" width="98%" bgcolor="#FDFDFD"> 
<!--th colspan="2"><?=$strRegister?></th-->
<tr>
	<td valign="middle">
    <font size="2"><?=$strFullname?></font>
    </td>
    
    <td>
    <input type="text" name="fullname" tabindex="1" value="<?=$fullname?>" style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strFullname?>">
    </td>
    
</tr>

<tr>
    <td>
    <font size="2"><?=$strUsr?></font>
    </td>
    
    <? 	
        if (isset($_SESSION['userId']))
        {
            echo "<td tabindex=2 height=\"35\"><font size=\"2\"><b>$userId</b></font></td>";  
        }
        else 
        {
            echo "<td><input type=\"text\" name=userId tabindex=2 value=\"$userId\" style=\"width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;\"  placeholder=\"$strUsr\"></td>";
        }
    ?>
    
</tr>


<tr>
	<td>
    <font size="2"><?=$strPass?></font>
    </td>
    
    <td>
    <input type="password" name="password" tabindex="3" style="width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strPass?>">
    </td>
</tr>


<tr>
	<td>
    <font size="2">E-mail</font>
    </td>
    
    <td>
    <input type="email" name="email" tabindex="4" size="48"value="<?=$email?>" style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="E-mail">
    </td>
</tr>


<tr>
	<td>
    <font size="2"><?=$strPhone?></font>
    </td>       
    
    <td>
    <input type="text" name="areaCode" tabindex="5" size="2" value="<?=$areaCode?>" style="width:40px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="(  )">
     <input type="text" name="phone" tabindex="6" size="8" value="<?=$phone?>" style="width:155px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strPhone?>">
	</td>
</tr>


                            
<tr>
	<td>
<? /* php if($country == "Brazil")
       if ($GLOBALS["paymentTestMode"] ) { ?>
   <?="CPF"?></td><td><input name="CNPJ_CPF" tabindex="7" size="11" value="<?=$CNPJ_CPF?>"></td></tr>
   <tr><td>
<?php } else { ?>
   <input type="hidden" name="CNPJ_CPF" tabindex="7" size="11" value="<?=$CNPJ_CPF?>">
<?php } */ ?>

	<font size="2"><?=$strStreet?></font>
    </td>
    <td><input type="text" name="street" tabindex="8" size="48" value="<?=$street?>" style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strStreet?>">&nbsp;
    <input type="text" name="number" tabindex="9" size="6" value="<?=$number?>" style="width:70px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strNumber?>">
    </td>
</tr>
    
    
<tr>
	<td>
    <font size="2"><?=$strZip?></font>
    </td> 
    
    <td><input type="text" name="cep" tabindex="10" size="9" value="<?=$cep?>" style="width:100px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strZip?>">
	</td>
</tr>


<tr>
	<td>
    <font size="2"><?=$strCity?></font>
    </td>
    
	<td>
    <input type="text" name="city" size="48" tabindex="11" value="<?=$city?>"  style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strCity?>">
    </td>
</tr>

<?php if($country == "Brazil")
{
?>
<tr>
	<td>
    <font size="2"><?=$strState?></font>
    </td>
    
	<td>
    <select name="state" tabindex="12" style="width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strState?>">
    <option selected><?=$state?>
    <option value="  "></option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="DF">Distrito Federal</option>
    <option value="CE">Ceará</option>
    <option value="ES">Espírito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MT">Mato Grosso</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MG">Mimas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="PR">Paraná</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="SC">Santa Catarina</option>
    <option value="SE">Sergipe</option>
    <option value="SP">São Paulo</option>
    <option value="TO">Tocantins</option>
    </td>
</tr>

<?php
}
?>

<tr>
	<td>
    <font size="2"><?=$strCountry?></font></td><td>
    <select name=country tabindex="13" style="width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strCountry?>">
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
    </select>
    </td>
</tr>


<tr>
	<td>
    <font size="2"><?=$strOrganization?></font>
    </td>
    
	<td>
    <!--input type="text" name="company" size="48" tabindex="14" value="<?=$company?>" style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strOrganization?>"-->
    <input type="text" class="ajax-typeahead"  data-link="./instituicoes.json" name="company" size="48" tabindex="14" value="<?=$company?>" style="width:350px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strOrganization?>">
    </td>
</tr>



<tr>
	<td><font size="2"><?=$strOrgType?></font>
    </td>
    
    <td>
    <select name="companyType" tabindex="15" style="width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strOrgType?>">
    <option selected><?=$companyType?>
    <option value="Comercio"><?=$strCommerce?></option>
    <option value="Empresa Estatal"><?=$strStateComp?></option>
    <option value="Empresa Privada"><?=$strPrivtComp?></option>
    <option value="Educacao Basica"><?=$strBasicEduc?></option>
    <option value="Educacao Superior"><?=$strHigherEduc?></option>
    <option value="Pos Graduacao"><?=$strGradEduc?></option>
    <option value="Governo Municipal"><?=$strLocalGovr?></option>
    <option value="Governo Estadual"><?=$strStateGovr?></option>
    <option value="Governo Federal"><?=$strFederGovr?></option>
    <option value="Consultoria"><?=$strConsultant?></option>
    <option value="ONG"><?=$strNGO?></option>
    <option value="Outro"><?=$strOther?></option>
    </select>
    </td>
</tr>


<tr>
	<td>
    <font size="2"><?=$strActivity?></font>
    </td>
    
    <td>
    <select name="activity" tabindex="16" style="width:200px; height:28px; font-family:Tahoma, Geneva, sans-serif;font-size:14px;border-radius:4px;"  placeholder="<?=$strActivity?>">
    <option selected><?=$activity?> 
    <option value="Agricultura"><?=$strAgricult?></option>
    <option value="Biologia"><?=$strBiology?></option>
    <option value="Cartografia"><?=$strCartograf?></option>
    <option value="Meio Ambiente"><?=$strEnvironment?></option>
    <option value="Educacao"><?=$strEducation?></option>
    <option value="Floresta"><?=$strForestry?></option>
    <option value="Geografia"><?=$strGeography?></option>
    <option value="Geologia"><?=$strGeology?></option>
    <option value="Saude"><?=$strHealth?></option>
    <option value="Hidrologia"><?=$strHidrology?></option>
    <option value="Processamento de Imagens"><?=$strImageProc?></option>
    <option value="Planejamento"><?=$strPlannig?></option>
    <option value="Socioeconomia"><?=$strSocioEcon?></option>
    <option value="Transportes"><?=$strTransport?></option>
    <option value="Outro"><?=$strOther?></option>
    </select>
    </td>
</tr>


<tr>
	<td colspan="4">
    <hr color="#FFFFFF">
    </td>    
</tr>

<tr>
	<td align="center" colspan="3">
	<input class="btn btn-info" type="submit" tabindex="17" value=<?=$strBregister?> name="action" style="border-radius:4px;width:150px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:14px;">&nbsp;&nbsp;
	<input class="btn btn-success" type="submit" tabindex="18" value=<?=$strBupdate?> name="action" style="border-radius:4px;width:150px;height:28px;font-family:Arial, Helvetica, sans-serif;font-size:14px;">
    <!--input type="button" tabindex="19" value=<?=$strOut?> onClick=window.location.href='first_<?=$_SESSION['userLang']?>.php'-->
    </td>
</tr>

<input type=hidden name=submitted value=1>
<input type=hidden name=addressId value=<?=$addressId?>>
</table>
</form>

</div></center>


<!-- 
Inicio da seção de importação de arquivos e definição de
códigos inline Javascript e jQuery
-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="/catalogo/js/jquery.js"></script>
<script src="/catalogo/js/bootstrap.js"></script>
<script src="/catalogo/js/plugins.js"></script>
<script src="/catalogo/js/custom.js"></script>
<script src="/catalogo/js/bootstrap-typeahead.js"></script>


<script>

$('.ajax-typeahead').typeahead({
    source: function(query, process) {
        return $.ajax({
            url: $(this)[0].$element[0].dataset.link,
            type: 'get',
            data: {query: query},
            dataType: 'json',
            success: function(json) {
                return typeof json.options == 'undefined' ? false : process(json.options);
            }
        });
    }
});

</script>


</body>
</html>


