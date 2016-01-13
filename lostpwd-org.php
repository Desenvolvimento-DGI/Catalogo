<?php
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
import_request_variables("gpc");
$dbusercat = $GLOBALS["dbusercat"];
?>
<html>
<head>
<?php include("css.php");?>
</head>
<body>
<?php
$userChecked = false;
$emailChecked = false;
if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
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
<form method="GET" action="lostpwd.php">
<tr>
<td align="\center\"><?=$strEmail?></td><td><input name="email"  size="30"value="<?=$email?>"></td></tr>
<tr><td colspan="2" align="right"><input type="submit" value="<?=$strExecute?>" name="action"></td></tr>
</form>
</tbody>
</table>
<?php
if ($action == $strExecute && $userChecked)
{
    $newpass = substr(md5(time()),0,6);

    $sql = "UPDATE User SET
              password = PASSWORD('$newpass')
              WHERE email = '$email'";

	$dbusercat->query($sql) or $dbusercat->error ($sql);

// Email the file links to the user.
    		$message = "$fullname,  Sua nova senha e : $newpass
	Se voce encontrar algum problema, entre em contato com
<product@dpi.inpe.br>.

CBERS Production Manager
";

	if ($GLOBALS["stationDebug"])
		echo "message= $message <br>\n";
	$status = true;

      $status = mail($email,"Sua nova senha",
		$message, "From:CBERS PRODUCTION <product@dpi.inpe.br>");

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
