<?php
function findCountry()
{
  $dbusercat =  $GLOBALS["dbusercat"];
  
	$country = "";
	$hostip = $_SERVER['REMOTE_ADDR'];
	
//  $hostip ="292.96.34.12";  // An USA address for testing
  
	$sql = sprintf("SELECT * FROM ipToCountry WHERE IP_FROM <= %u AND IP_TO >=
%u",ip2long($hostip),ip2long($hostip));

// $sql = sprintf("SELECT * FROM ipToCountry WHERE IP_FROM <= %u AND IP_TO >=
// %u",1078738944,1078739455); // A South America country (Venezuela) for testing

	$dbusercat->query($sql);
	if ($dbusercat->numRows() > 0)
	{
		$row = $dbusercat->fetchRow();
		$country = ucwords(strtolower($row["COUNTRY_NAME"]));
	}
	$dbusercat->freeResult();
//	echo "<br> country = $country <br>";
	return $country; 
}
?>