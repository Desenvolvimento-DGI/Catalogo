<?php
// Verifica sess�
include("session_mysql.php"); 
session_start();
header("Cache-Control: no-cache, must-revalidate");
include("globals.php");
//include_once("request.class.php");
//include_once("user.class.php");
include_once("price.class.php");
//include_once("media.class.php");

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>"; 

#
# Set values for page displaying control
# 
if(isset($BASE)) $base = $BASE; else $base = 0;
if(isset($PAGE)) $page = $PAGE; else $page = 0;
if(isset($BASE_PAGE)) $base_page = $BASE_PAGE; else $base_page = 0;
#

if(isset($TOTAL)) $totalscenes = $TOTAL;
else $totalscenes = 0;

 
// Verifica o idioma para exibir a pagina corretamente
if (isset($SESSION_LANGUAGE))
	  $_SESSION['userLang']=$SESSION_LANGUAGE;
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}
require ("mantab_".$_SESSION['userLang'].".php");
require ("product_".$_SESSION['userLang'].".php");
define("BASEDIR", "../Suporte/");
$ntimes = $ntimes + 1;
?>
<html>
<head>
<title>Cadastro de Cenas</title>
<script language="javascript">
function openwin(url) {
	nwin=window.open(url,'MW','');
	nwin.focus();
}
function priceAlert(price)
{
 msg1 = "<?=$strRestriction2?>" ;
 msg2 = "<?=$strComplement?>"; 
 msg = msg1+msg2+price;
 alert(msg); 
}
</script>
<?php
require("css.php");
?>
</head>
<body topmargin="0" marginwidth="0">

<?php
// Pega o handle para a base de dados

include("cart.class.php");
if ($GLOBALS["stationDebug"])
	// ---- echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";
$dbcat = $GLOBALS["dbcatalog"];
$dbcat1 = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];

// Serching User and his attributes

$userid = $_SESSION['userId'];
$objUser = new User($dbusercat);
$objUser->selectByUserId($userid);

// Find if User is able for purchasing

$OK_to_purchase = 0;
$cnpj = $objUser->CNPJ_CPF;
//if (trim($cnpj) != "") $OK_to_purchase = 1;

if($_SESSION["userType"] == 1 or $_SESSION["userType"] == 3 or $_SESSION["userType"] == 4) $OK_to_purchase = 1;

// Define a tabela
$table="Scene";

// Define linhas e colunas e calcula o numero de cenas por pagina
$numcol=3;
$numlin=2;
if ($TAM=="G")
{
	$numcol=5;
	$numlin=4;
}
if ($TAM=="M")
{
	$numcol=4;
	$numlin=3;
} 

$numscenesperpage = $numcol*$numlin; 
#
# Set value for MySQL querying and retrieving control
#
$limit = floor(1000/$numscenesperpage)*$numscenesperpage; // Set limits for the number of records to be retrieved each time a query is addressed to the database.
#

#
# Begining Function
#

function query_for_standard_path_row ()
{
#
# Checking Path and Row values
#
 
 global $sql1,$sql,$base_page,$table,$condition,$complement,$base,$limit,$ORBITAI,$ORBITAF,$PONTOI,$PONTOF,$SATELITE,$SENSOR,$LAT1,$LAT2,$LON1,$LON2,$IDATEM,$FDATEM,$IDATEY,$FDATEY,$IDATE,$FDATE,$Q1,$Q2,$Q3,$Q4;
 
 // ---- echo "\n<br> mantab : query_for_standard_path_row =====> $ORBITAI,$ORBITAF,$PONTOI,$PONTOF,$SATELITE,$condition,$complement,$SENSOR,$LAT1,$LAT2,$LON1,$LON2,$IDATEM,$FDATEM,$IDATEY,$FDATEY,$IDATE,$FDATE,$Q1,$Q2,$Q3,$Q4;    \n <br>";
  
if(isset($ORBITAI))
{
	if(ereg("([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})(_[[:alpha:]]){1}|[[:space:]]{0,}|([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})",$ORBITAI,$result))
   if(trim($result[0]) != trim($ORBITAI)) die ("<br><h3> Invalid Path : $ORBITAI</h3><br>");
   else ;
  else die ("<br><h3> Invalid Path : $ORBITAI</h3><br>");
  $ORBITAI = trim($ORBITAI);
  $ORBITAI = strtoupper($ORBITAI);
}

if(isset($ORBITAF))
{ 
	if(ereg("([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})(_[[:alpha:]]){1}|[[:space:]]{0,}|([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})",$ORBITAF,$result))
   if(trim($result[0]) != trim($ORBITAF)) die ("<br><h3> Invalid Path : $ORBITAF</h3><br>");
   else ;
  else die ("<br><h3> Invalid Path : $ORBITAF</h3><br>");
  $ORBITAF = trim($ORBITAF);
  $ORBITAF = strtoupper($ORBITAF);
}

if(isset($PONTOI))
{
	if(ereg("([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})(_[0-9]){1}|[[:space:]]{0,}|([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})",$PONTOI,$result))
   if(trim($result[0]) != trim($PONTOI)) die ("<br><h3> Invalid Row : $PONTOI</h3><br>");
   else ;
  else die ("<br><h3> Invalid Row : $PONTOI</h3><br>");
  $PONTOI = trim($PONTOI);
}

if(isset($PONTOF))
{
	if(ereg("([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})(_[0-9]){1}|[[:space:]]{0,}|([[:space:]]{0,}[0-9]{1,3}[[:space:]]{0,})",$PONTOF,$result))
   if(trim($result[0]) != trim($PONTOF)) die ("<br><h3> Invalid Row : $PONTOF</h3><br>");
   else ;
  else die ("<br><h3> Invalid Row : $PONTOF</h3><br>");
  $PONTOF = trim($PONTOF);
} 
#
# End checking Path and Row values
#

// Monta o sql de pesquisa - caso venha sql em branco
if ($sql == "")
{
// $sql = "SELECT * FROM $table WHERE Deleted=0 AND   ";
   $sql = "SELECT * FROM $table WHERE Deleted='0' AND CloudCoverMethod='M' AND   ";
//  $sql = "SELECT * FROM $table WHERE 1 AND ";
	if ($SATELITE)
		$sql .= "Satellite $condition '".$SATELITE."' AND $complement  ";
	if ($SENSOR)
		$sql .= "Sensor $condition '".$SENSOR."' AND   ";
	if ($LAT1) 
		$sql .= "CenterLatitude >= $LAT1 AND   ";
	if ($LAT2)
		$sql .= "CenterLatitude <= $LAT2 AND   ";
	if ($LON1) 
		$sql .= "CenterLongitude >= $LON1 AND   ";
	if ($LON2)
		$sql .= "CenterLongitude <= $LON2 AND   ";
		
	$result_orbitaI = explode("_",$ORBITAI);

	$result_orbitaF = explode("_",$ORBITAF);

	// Esta linha remove as imagens que deviram ser mostradas somente na pesquisa de GLS //
	if ( $SATELITE == 'L1' || $SATELITE == 'L2' || $SATELITE == 'L3' || $SATELITE == 'L5' || $SATELITE == 'L7' ){ $sql .= "SceneId NOT LIKE '%GLS%' AND ";}
	
	if ($ORBITAI && !isset($ORBITAF))
		$sql .= "SUBSTRING_INDEX(Path,'_',1) + 0 = SUBSTRING_INDEX('$ORBITAI','_',1) + 0 AND    ";
	else
	{
		if ($ORBITAI)
		{
		  if($result_orbitaI[1] == "") $result_orbitaI[1] = 'A';
	  
//			$sql .= "SUBSTRING_INDEX(Path,'_',1) + 0 >= SUBSTRING_INDEX('$ORBITAI','_',1) + 0 AND   ";
     $sql .= "10*SUBSTRING_INDEX(Path,'_',1) + ASCII(ELT(left(Path regexp '[[:alpha:]]+$',1)+1,'$result_orbitaI[1]',SUBSTRING_INDEX(Path,'_',-(left(Path regexp '[[:alpha:]]+$',1))))) >= 10*SUBSTRING_INDEX($result_orbitaI[0],'_',1) + ASCII('$result_orbitaI[1]')  AND   ";
		}
		if ($ORBITAF) 
		{
		 if($result_orbitaF[1] == "") $result_orbitaF[1] = 'E';
//			$sql .= "SUBSTRING_INDEX(Path,'_',1) + 0 <= SUBSTRING_INDEX('$ORBITAF','_',1) + 0 AND   ";
    $sql .= "10*SUBSTRING_INDEX(Path,'_',1) + ASCII(ELT(left(Path regexp '[[:alpha:]]+$',1)+1,'$result_orbitaF[1]',SUBSTRING_INDEX(Path,'_',-(left(Path regexp '[[:alpha:]]+$',1))))) <= 10*SUBSTRING_INDEX($result_orbitaF[0],'_',1) + ASCII('$result_orbitaF[1]') AND   ";
	  }
	}
	if ($PONTOI && !$PONTOF)
		$sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 = SUBSTRING_INDEX('$PONTOI','_',1) + 0 AND   ";
	else
	{
		if ($PONTOI)
			$sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 >= SUBSTRING_INDEX('$PONTOI','_',1) + 0 AND   ";
		if ($PONTOF)
			$sql .= "SUBSTRING_INDEX(Row,'_',1) + 0 <= SUBSTRING_INDEX('$PONTOF','_',1) + 0 AND   ";
	}

	if ($IDATEM && $FDATEM)
	{
		if ($IDATEM > $FDATEM)
		{
			$sql .= "(month(Date)>=" . $IDATEM . " OR   ";
			$sql .= "month(Date)<=" . $FDATEM . ") AND   ";
		}
		else if ($IDATEM == $FDATEM)
		{
			$sql .= "month(Date)=" . $IDATEM . " AND   ";
		}
		else
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	}
	else if ($IDATEM)
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
		}
	else if ($FDATEM)
		{
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	if ($IDATEY && $FDATEY)
	{
		if ($IDATEY == $FDATEY)
		{
			$sql .= "year(Date)=" . $IDATEY . " AND   ";
		}
		else
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	}
	else if ($IDATEY)
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
		}
	else if ($FDATEY)
		{
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	 		 
	if ($IDATE)
      $sql .= "Date>='" . $IDATE . "' AND   ";
	if ($FDATE)
      $sql .= "Date<='" . $FDATE . "' AND   ";
	if (isset($Q1))
		$sql .= "CloudCoverQ1<=$Q1 AND   ";
	if (isset($Q2))
		$sql .= "CloudCoverQ2<=$Q2 AND   ";
	if (isset($Q3))
		$sql .= "CloudCoverQ3<=$Q3 AND   ";
	if (isset($Q4))
		$sql .= "CloudCoverQ4<=$Q4 AND   ";
	$sql = substr($sql,0,strlen($sql)-6);
	$sql1 = $sql . "ORDER BY Date Desc, StartTime ASC";
	$sql .="ORDER BY Date Desc, StartTime ASC LIMIT $base, $limit"; 
	$base_page = 1;
}
else 
{
	$sql = stripslashes($sql); 
//	if(isset($END) and $END == 1)
//	{
//	 $total_pages = ceil($totalscenes/$numscenesperpage);
//	 $base = ($total_pages -1) * $numscenesperpage; 
//	};
	$sql = substr_replace($sql, "LIMIT $base, $limit",strpos($sql,"LIMIT"));
}
 
 $aux = $sql . ";" . $sql1 . ";" . $base_page;
 return $aux; 
  
}

#
# End Function
#

#
# Begining Function
#

function query_for_non_standard_path_row ()
{

  global $sql1,$sql,$base_page,$table,$condition,$complement,$base,$limit,$ORBITAI,$ORBITAF,$PONTOI,$PONTOF,$SATELITE,$SENSOR,$LAT1,$LAT2,$LON1,$LON2,$IDATEM,$FDATEM,$IDATEY,$FDATEY,$IDATE,$FDATE,$Q1,$Q2,$Q3,$Q4;
 
  if(isset($ORBITAI)) $ORBITAI = trim($ORBITAI); 
  if(isset($ORBITAF)) $ORBITAF = trim($ORBITAF); 
  if(isset($PONTOI)) $PONTOI = trim($PONTOI); 
  if(isset($PONTOF)) $PONTOF = trim($PONTOF); 
 
 // Monta o sql de pesquisa - caso venha sql em branco
if ($sql == "")
{
// $sql = "SELECT * FROM $table WHERE Deleted=0 AND   ";
   $sql = "SELECT * FROM $table WHERE Deleted='0' AND CloudCoverMethod='M' AND   ";
//  $sql = "SELECT * FROM $table WHERE 1 AND ";
	if ($SATELITE)
		$sql .= "Satellite $condition '".$SATELITE."' AND $complement  ";
	if ($SENSOR)
		$sql .= "Sensor $condition '".$SENSOR."' AND   ";
	if ($LAT1) 
		$sql .= "CenterLatitude >= $LAT1 AND   ";
	if ($LAT2)
		$sql .= "CenterLatitude <= $LAT2 AND   ";
	if ($LON1) 
		$sql .= "CenterLongitude >= $LON1 AND   ";
	if ($LON2)
		$sql .= "CenterLongitude <= $LON2 AND   ";
		
	if ($ORBITAI && !isset($ORBITAF))
		$sql .= "Path='$ORBITAI' AND   ";
	else
	{
		if ($ORBITAI)
			$sql .= "Path>='$ORBITAI' AND   ";
		if (trim($ORBITAF) != "")
			$sql .= "Path<='$ORBITAF' AND   ";
	}
	if ($PONTOI && !$PONTOF)
		$sql .= "Row='$PONTOI' AND   ";
	else
	{
		if ($PONTOI)
			$sql .= "Row>='$PONTOI' AND   ";
		if (trim($PONTOF) != "")
			$sql .= "Row<='$PONTOF' AND   ";
	}	
		
		if ($IDATEM && $FDATEM)
	{
		if ($IDATEM > $FDATEM)
		{
			$sql .= "(month(Date)>=" . $IDATEM . " OR   ";
			$sql .= "month(Date)<=" . $FDATEM . ") AND   ";
		}
		else if ($IDATEM == $FDATEM)
		{
			$sql .= "month(Date)=" . $IDATEM . " AND   ";
		}
		else
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	}
	else if ($IDATEM)
		{
			$sql .= "month(Date)>=" . $IDATEM . " AND   ";
		}
	else if ($FDATEM)
		{
			$sql .= "month(Date)<=" . $FDATEM . " AND   ";
		}
	if ($IDATEY && $FDATEY)
	{
		if ($IDATEY == $FDATEY)
		{
			$sql .= "year(Date)=" . $IDATEY . " AND   ";
		}
		else
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	}
	else if ($IDATEY)
		{
			$sql .= "year(Date)>=" . $IDATEY . " AND   ";
		}
	else if ($FDATEY)
		{
			$sql .= "year(Date)<=" . $FDATEY . " AND   ";
		}
	 		 
	if ($IDATE)
      $sql .= "Date>='" . $IDATE . "' AND   ";
	if ($FDATE)
      $sql .= "Date<='" . $FDATE . "' AND   ";
	if (isset($Q1))
		$sql .= "CloudCoverQ1<=$Q1 AND   ";
	if (isset($Q2))
		$sql .= "CloudCoverQ2<=$Q2 AND   ";
	if (isset($Q3))
		$sql .= "CloudCoverQ3<=$Q3 AND   ";
	if (isset($Q4))
		$sql .= "CloudCoverQ4<=$Q4 AND   ";
	$sql = substr($sql,0,strlen($sql)-6);
	$sql1 = $sql . "ORDER BY Date Desc, StartTime ASC";
	$sql .="ORDER BY Date Desc, StartTime ASC LIMIT $base, $limit"; 
	$base_page = 1;
}
else 
 {
	$sql = stripslashes($sql); 
//	if(isset($END) and $END == 1)
//	{
//	 $total_pages = ceil($totalscenes/$numscenesperpage);
//	 $base = ($total_pages -1) * $numscenesperpage; 
//	};
	$sql = substr_replace($sql, "LIMIT $base, $limit",strpos($sql,"LIMIT"));
 }
 
 $aux = $sql . ";" . $sql1 . ";" . $base_page;
 return $aux; 
}	
	
#
# End of Function
#

// Se acao de adicionar ao carrinho, insere na tabela cart
if ($action == "cart")
{
  $sqlaux = "SELECT * FROM Cart WHERE Cart.sesskey='$PHPSESSID' AND SceneId = '$INDICE'";
  if (!$dbcat->query($sqlaux)) $dbcat->error($sqlaux); 

  $nscenes = $dbcat->numRows();

  if($nscenes == 0)  // We only insert scenes that are still not in database 
  { 
	 $objCart = new Cart($dbcat); 
	 $objCart->fill($PHPSESSID,$INDICE, $PRODUTO);
	 $objCart->insert();
	}
}

if($SATELITE == "GLS") 
{
 $SATELITE = "L[5,7]";
 $SENSOR = "[ETM,TM]";
 $condition = "REGEXP";
 $complement = " SceneId LIKE \"%GLS\" AND ";
}
else 
{
 $condition = "=";
 $complement = "";
}

switch ($SATELITE)
{
 case "UKDMC":
      $res_aux = query_for_non_standard_path_row ();
      $aux = explode(";",$res_aux);
      $sql = $aux[0];
      $sql1 = $aux[1];
      $base_page = $aux[2];
      break;
 default:
      $res_aux = query_for_standard_path_row ();
      $aux = explode(";",$res_aux);
      $sql = $aux[0];
      $sql1 = $aux[1];
      $base_page = $aux[2];
      break;
}
 
// --- if ($GLOBALS["stationDebug"]) echo "\n<br> ======> mantab : SATELITE = $SATELITE <br>\n";
// --- if ($GLOBALS["stationDebug"]) echo "\n<br> ======> mantab : sql = $sql <br>\n";

// realiza a pesquisa
if (!$dbcat->query($sql)) $dbcat->error($sql);

$numscenes = $dbcat->numRows();

if(isset($sql1) and $sql1 != "")
{
 if (!$dbcat->query($sql1)) $dbcat->error($sql1);
 $totalscenes = $dbcat->numRows();
// echo "<br> sql1 = $sql1 totalscenes = $totalscenes<br>";
};

$total_pages = ceil($totalscenes/$numscenesperpage);
$aux_end = floor($total_pages/($limit/$numscenesperpage)) * ($limit/$numscenesperpage) + 1; 
//$base_end = ($aux_end - 1)* $numscenesperpage; 
if($aux_end > $total_pages)
{
 $aux_end = $total_pages - ($limit/$numscenesperpage) + 1;
 $base_end = $base_end - 1;
};
$base_end = ($aux_end - 1)* $numscenesperpage;
if ($GLOBALS["stationDebug"])
// ---- echo "<br> ====> total_pages = $total_pages totalscenes = $totalscenes numscenesperpage = $numscenesperpage base_end = $base_end aux_end = $aux_end <br>";

if ($GLOBALS["stationDebug"])
  // ---- echo "$numscenes <br>";

if ($numscenes == 0)
{
	echo $strNoScene;
	exit;
}

#
# Number of Pages to be diplayed  
#
$pages_displayed = ceil($numscenes/$numscenesperpage);
#


//if ($page >= $pages_displayed) $page = $pages_displayed; 
if ($page==0 && $pages_displayed>0)$page = 1;

if ($page == ($pages_displayed + $base_page - 1) && $numscenes % $numscenesperpage > 0)
	$numscenesinpage = $numscenes % $numscenesperpage;
else
	$numscenesinpage = $numscenesperpage;

// ---- echo "\n$strPage \n";

$pages_back = ceil($limit/$numscenesperpage); // for backwards moving on pages list

if ($GLOBALS["stationDebug"])
// ---- echo "<br> pages_back = $pages_back base_page = $base_page page = $page numscenesperpage = $numscenesperpage numscenes = $numscenes limit = $limit totalscenes = $totalscenes pages_displayed = $pages_displayed base = $BASE<br>";

if ($base_page > 1)
{
  //printf ("<a title=\"$strPlus\" href=\"mantab.php?TAM=%s&sql=%s&BASE=%d&BASE_PAGE=%d&PAGE=%d\"><font size=4>%s</font> \n",$TAM,urlencode($sql),$base - $limit,$base_page - $pages_back,$base_page - $pages_back,"&nbsp;&nbsp; << + &nbsp;"); 	
  $aux1 = $base - $limit;
  $aux2 = $base_page - $pages_back;
  if($aux1 <= 0) $aux1 = 1;
  if($aux2 <= 0) $aux2 = 1;
  echo "<input type=button name=scroll value=\" BEGIN \" onclick=window.location.href=\"mantab.php?TAM=$TAM&sql=" . urlencode($sql) . "&BASE=1&BASE_PAGE=1&PAGE=1&TOTAL=$totalscenes&BEGIN=1\">";
	echo "<input type=button name=scroll value=\" < \" onclick=window.location.href=\"mantab.php?TAM=$TAM&sql=" . urlencode($sql) . "&BASE=$aux1&BASE_PAGE=$aux2&PAGE=$aux2&TOTAL=$totalscenes\">";  
	echo " \n";
}

// Display page numbers with links
//	echo "<br> ====> base = $base aux_end = $aux_end base_page = $BASE_PAGE<br>";
for ($i=$base_page;$i<=$pages_displayed + $base_page;$i++)  
{	

	if ($i == $pages_displayed + $base_page ) 
		echo " \n";
	else
	 if($i == $page) // printf ("<a href=\"mantab.php?TAM=%s&PAGE=%d&BASE_PAGE=%d&BASE=%d&sql=%s\" style=\"background:#bbbbbb;color:#004477;font-size:15\">%d</a> \n",$TAM,$i,$base_page,$base,urlencode($sql),$i);
	 	printf ("<span style=\"background:#bbbbbb;color:#004477;font-size:15\">%d</span> \n",$i);
	 else printf ("<a href=\"mantab.php?TAM=%s&PAGE=%d&BASE_PAGE=%d&BASE=%d&sql=%s&TOTAL=%s\">%d</a> \n",$TAM,$i,$base_page,$base,urlencode($sql),$totalscenes,$i);
  $totalpages = ceil($totalscenes/$numscenesperpage);
// echo "<br> i = $i base_page = $base_page numscenesperpage = $numscenesperpage  totalpages = $totalpages " . " (i-base_page)*numscenesperpage = " . ($i-$base_page)*$numscenesperpage . " i*numscenesperpage = " . $i*$numscenesperpage . " limit = $limit totalscenes = $totalscenes pages_displayed = $pages_displayed base = $BASE<br>";
  $totalpages = ceil($totalscenes/$numscenesperpage);
	if (($i-$base_page)*$numscenesperpage >= $limit and ($i <= $totalpages))
	{
//	  printf ("<a  title=\"$strPlus\" href=\"mantab.php?TAM=%s&sql=%s&BASE=%d&BASE_PAGE=%d&PAGE=%d\"><font size=4>%s</font> \n",$TAM,urlencode($sql),$base + $limit,$i,$i,"&nbsp; + >>"); 
   $aux1 = $base + $limit;
	 echo "<input type=button name=scroll value=\" > \" onclick=window.location.href=\"mantab.php?TAM=$TAM&sql=" . urlencode($sql) . "&BASE=$aux1&BASE_PAGE=$i&PAGE=$i&TOTAL=$totalscenes\">";
//	 echo "<input type=button name=scroll value=\" END \" onclick=window.location.href=\"mantab.php?TAM=$TAM&sql=" . urlencode($sql) . "&BASE=$base&BASE_PAGE=$total_pages&PAGE=$total_pages&TOTAL=$totalscenes&END=1\">";
	 echo "<input type=button name=scroll value=\" END \" onclick=window.location.href=\"mantab.php?TAM=$TAM&sql=" . urlencode($sql) . "&BASE=$base_end&BASE_PAGE=$aux_end&PAGE=$total_pages&TOTAL=$totalscenes\">";
	}
}
//echo "<br/>$strCurPage = $page<br/>";
echo "\n<hr>\n"; 

echo "<div><center><table border=4 cellspacing=0>\n";

printf( "<td style=\"background:#bbbbbb;color:#004477\"><center>$strCurPage : $page</center></td>\n" ); 

$priced = array();
$objPrice = new Price($dbcat);

if (isset($_SESSION['userId'])) $usertype = $_SESSION['userType'] ;
else $usertype = 2;

$page_aux = $page - $base_page ; // This is so (for displaying quicklooks) because the MYSQL query takes into to account a progressive retrieving (LIMIT $base, $limit) 

for ($lin=0;$lin<$numlin;$lin++)
{
	echo "<tr>\n"; 
	$priced[] = 0;
	for ($col=0;$col<$numcol;$col++)
	{ 
//		$res = ($page-1)*$numscenesperpage+$lin*$numcol+$col ; echo "<br> page = $page numscenesperpage = $numscenesperpage index = $res <br>";
		if (($lin*$numcol+$col)>=$numscenesinpage) 
		break;
		$row_array = $dbcat->fetchRow(($page_aux)*$numscenesperpage+$lin*$numcol+$col);
		$savid[$col] = $row_array["SceneId"]; 
		$savprod[$col] = $row_array["Id"];
		printf("<td nowrap align=\"center\">\n<b>%s%s %s/%s-%s</b>\n</td>\n",
		$row_array["Satellite"],$row_array["Sensor"],$row_array["Path"],$row_array["Row"],$row_array["Date"]);
		switch ($row_array["Satellite"])
		{
			case "CB1":
		 	case "CB2":  
		 	case "CB2B":
				$tab_sat_prefix[$col] = "Cbers";
			  break;
			case "T1":
		 	case "A1":
				$tab_sat_prefix[$col] = "Modis";
			  break;
			case "L1":
			case "L2":
			case "L3":
			case "L5":
			case "L7":
			case "L8":
				$tab_sat_prefix[$col] = "Landsat";
			  break;
			case "P6":
				$tab_sat_prefix[$col] = "P6";
			  break;
			case "UKDMC":
			      $tab_sat_prefix[$col] = "UKDMC";
			  break;
		} 
//  	$sql1 = "SELECT * FROM price WHERE Satellite ='".$row_array["Satellite"]."' AND Sensor ='".$row_array["Sensor"]."' "; 
//    if (!$dbcat1->query($sql1)) $dbcat1->error($sql1); 
//    $rowaux = $dbcat1->fetchRow();
//    $priced[$col] = $objPrice->searchPrice($row_array[Satellite],$row_array[Sensor],$row_array[Date],"0",$_SESSION['userLang'],"Systematic");  
    $priced[$col] = $objPrice->searchPrice($row_array[Satellite],$row_array[Sensor],$row_array[Date],"0",$_SESSION['userLang'],"2",$usertype); 

	} 
	echo "</tr>\n";
	echo "<tr>\n";
	for ($col=0;$col<$numcol;$col++) 
	{
	 if (($lin*$numcol+$col)>=$numscenesinpage) 
	 break;
	 echo "<td nowrap align=\"center\">\n";
# 
#	Exibe carrinho, detalhes e tarifa�o 
#
   $dontshow = 0; 

log_msg( __FILE__.":".__LINE__.":".$priced[$col]." ".$OK_to_purchase." ".$_SESSION['openArea'] );

   if ($priced[$col] > 0)
	  if (!$OK_to_purchase == 1) $dontshow = 1;
	  else ;
	 else if (!$_SESSION['openArea']) $dontshow = 1;
	 
#
#
   if($dontshow == 0) printf("<a href=\"mantab.php?TAM=%s&sql=%s&INDICE=%s&PRODUTO=%s&action=cart&ntimes=%d&BASE=%d&BASE_PAGE=%d&PAGE=%d&TOTAL=%d\"><img alt=\"%s\" src=\"%simages/carrinho.gif\" border=\"0\"></a>\n",$TAM,urlencode($sql),$savid[$col],$savprod[$col],$ntimes,$base,$base_page,$page,$totalscenes,$strCart, BASEDIR); 
   printf("<a href=\"javascript:openwin('manage.php?INDICE=%s&DONTSHOW=%d')\"><img alt=\"%s\" src=\"%simages/status.gif\" border=\"0\"></a>\n",$savid[$col],$dontshow,$strDetail, BASEDIR);
   if ($priced[$col] > 0 ) printf("<img src=\"../Suporte/images/dollar4.gif\" border=\"0\" alt=\"%s\" onClick=priceAlert('%s')>\n",$strPriced,$priced[$col]);
	 echo "</td>";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	for ($col=0;$col<$numcol;$col++)
	{
		if (($lin*$numcol+$col)>=$numscenesinpage)  
		break;
		
		$dontshow = 0;
    if ($priced[$col] > 0)
	   if (!$OK_to_purchase == 1) $dontshow = 1;
	   else ;
	  else if (!$_SESSION['openArea']) $dontshow = 1;
		
//		echo "<br> strDetail = $strDetail INDICE = $savid[$col] DONTSHOW = $dontshow PREFIXO = $tab_sat_prefix[$col] INDICE = $savid[$col] <br>";
?>
<td align="center">

<!-- A constru�o "iframe", abaixo", foi comentada em fun�o de uma maneira alternativa de visualiza�o dos detalhes de uma cena, buscando facilitar o apontamento da fun�o "detalhes" pelo usuario-->
     
<!-- <iframe src='display.php?TABELA=Thumbnail&PREFIXO=<?=$tab_sat_prefix[$col]?>&INDICE=<?=$savid[$col]?>' name='image' width='128' height='128' scrolling='no' marginwidth='0' frameborder='0'> 
</iframe> -->

<!--  Esta �a maneira alternativa de visualiza�o dos detalhes (mais f�il p/ o usu�io) -->

<a href=javascript:openwin('manage.php?INDICE=<?=$savid[$col]?>&DONTSHOW=<?=$dontshow?>')>
<img alt='<?=$strDetail?>' src='display.php?TABELA=Thumbnail&PREFIXO=<?=$tab_sat_prefix[$col]?>&INDICE=<?=$savid[$col]?>' name='image' width='128' height='128' scrolling='no' marginwidth='0' frameborder='0'> </a> 
</td>
<?php
	}
	echo "</tr>"; 
}
echo "<td align=\"center\" colspan=$numcol> <input type=\"button\" value=$strClose onClick = \"window.history.go(-$ntimes) \" ></td>" ;
?>
</table>
</body>
</html>