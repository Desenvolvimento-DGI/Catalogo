<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.conteudo111 {overflow-x: hidden;
}
body,td,th {
	color: #FFFFFF;
}
body {
	background-color: #2E3E3E;
}
-->
</style>
</head>
<body>
<?
$lang = $_GET["lang"];
	if($lang != "EN"){
		$lang = PT;
	}



echo "<iframe class='conteudo111' style=' overflow-x:hidden;' src='siteDgi_register.php?lang=$lang' name='palco' width='582' height='275' scrolling='yes'  frameborder='0' ></iframe>";
?>
</body>
</html>



