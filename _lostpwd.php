<?php
// Informa ao servidor que é necessário compactar a código resultante antes de enviá-lo
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 



require_once("class_ocr_captcha.inc.php");
include("session_mysql.php");
session_start();

    
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
} 

require ("lostpwd_".$_SESSION['userLang'].".php");

if (isset($HTTP_POST_VARS['public_key']))
	$public=$HTTP_POST_VARS['public_key'];

if (isset($HTTP_POST_VARS['private_key']))
	$private=$HTTP_POST_VARS['private_key'];

$p=new ocr_captcha(); 

if (isset($HTTP_POST_VARS['private_key']))
{ 
	if ($p->check_captcha($public,$private))
	  $captcha = true;
	else
	{
		$captcha = false;
		echo "<h4><font color=\"#CC0000\">$strMismatch</font></h4>";
	}
	
}

import_request_variables("gpc");
include_once("globals.inc.php"); 
include_once("globals.php");
$dbusercat = $GLOBALS["dbusercat"];

?> 
<html>
<head>
<!--?php include("css.php");?-->
</head>
<body>
<?php 

$userChecked = false;
$emailChecked = false;


if ($action == $strExecute)
{
	if ($email !== "") // email is set
	{
		// Check if email is a valid one
		include ("validateEmail.php");
		$result = ValidateMail($email);
		if ($result[0] == true) // If it is valid, check if user is already registered
		{
			$sql = "SELECT * FROM User WHERE email='$email'";
			$dbusercat->query($sql) or $dbusercat->error ($sql);
			$users = $dbusercat->numRows();
			if ($users > 0) // user is registered
			{
				$userChecked = true;
				$row = $dbusercat->fetchRow();
				$fullname = $row['fullname'];
				$userIdent =  $row['userId'];
			}
			$emailChecked = true;
		}
	}
	if (!$emailChecked)
		echo "<h4><font color=\"#CC0000\">$strInvalidEmail</font></h4>";
		
	if (!$userChecked)
		echo "<h4><font color=\"#CC0000\">$strInvaliUser</font></h4>";
		
}
 ?>
<table>
<form method="POST" action="lostpwd.php">
<tr>
<td align="center"><?=$strEmail?></td><td><input name="email"  size="30"value="<?=$email?>"></td></tr>
<td align="center"><?=$strRewrite?></td><td align="center"><?echo $p->display_captcha(true);?></td>
</tr>
<tr>
<td><?=$strField?></td><td align="center"><input type=text name=private_key value='<?=$private_key?>' maxlength=4 size=12></td>
</tr>
<tr>
<td align="center" colspan="2"><input type="submit" value="<?=$strExecute?>" maxsize="4" name="action"></td>
</tr>

</form>
</tbody>
</table>

<?php

if ($action == $strExecute && $userChecked && $captcha)
{
    $newpass = substr(md5(time()),0,6);
    require ("lostpwd_".$_SESSION['userLang'].".php");
    $sql = "UPDATE User SET
              password = OLD_PASSWORD('$newpass')
              WHERE email = '$email'";

	$dbusercat->query($sql) or $dbusercat->error ($sql);


	// Email the file links to the user.
	if ($GLOBALS["stationDebug"])
		echo "message= $strPasswdrecover <br>\n";
	$status = true;

	$status = mail($email,$strYournewpaswd,
	$strPasswdrecover, "From:<$GLOBALS[contactmail]>");

	if ($status)
	{
		$_SESSION['userTry'] = 0;
		echo $strSuccess;
	}
}
else if (!isset($_SESSION['userId']))
	echo $strInformation;
 ?>
</body>
</html>
