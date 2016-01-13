<?php
include("session_mysql.php");
session_start();
require("css.php");
include("globals.php");

if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE; 
else
{
	if (!isset($_SESSION['userLang']))
		$_SESSION['userLang']='PT';
}; 

require ("mantab_".$_SESSION['userLang'].".php");

global $njumps;  // $njumps counts the number of jumps to get back to the calling script (starting point) 
$njumps = $njumps + 1;

// echo  $_SERVER['QUERY_STRING'];

?>
<html>
<head>
<title>Mosaico do Brasil</title>


<script type="text/javascript">
var sensors = new Array();

sensors[0] = new Array("");
sensors[1] = new Array("","CCD","IRM","WFI"); 
sensors[2] = new Array("","CCD","HRC","WFI"); 
sensors[3] = new Array("MSS");
sensors[4] = new Array("MSS");
sensors[5] = new Array("MSS");
sensors[6] = new Array("TM");
sensors[7] = new Array("ETM");
sensors[8] = new Array("MODIS");
sensors[9] = new Array("MODIS");
sensors[10] = new Array("LIS3","AWIF");
sensors[11] = new Array("TM,ETM");
sensors[12] = new Array("SLIM");

function goNext(lat,lon,mylat,mylon,res,ima,tem,tam,mes)
{
  njumps = <?=$njumps?>; // added to control the jump back to the starting point
	est = parent.panel.document.general.ESTADO.value;
	mun = parent.panel.document.general.MUNICIPIO.value;
	sat = parent.panel.document.general.SATELITE.value;
	sens = parent.panel.document.general.SENSOR.value;
	isat = parent.panel.document.general.SATELITE.selectedIndex;
	isen = parent.panel.document.general.SENSOR.selectedIndex;
	sen = sensors[isat][isen];

	timeint = parent.panel.document.general.TIMEINTERVAL.checked;
	params = "?";

	quick = parent.panel.document.general.quicklook[0].checked;
	if (quick)
		params = params+"QUICK=S&";
	else
		params = params+"QUICK=B&";

	if (tam.length > 0)
		params = params+"TAM="+tam+"&";
	if (sat.length > 0)
		params = params+"SATELITE="+sat+"&";
	if (sen.length > 0)
		params = params+"SENSOR="+sen+"&";

	if (parent.panel.document.general.Q1.value.length > 0)
		params = params+"Q1="+parent.panel.document.general.Q1.value+"&";
	if (parent.panel.document.general.Q2.value.length > 0)
		params = params+"Q2="+parent.panel.document.general.Q2.value+"&";
	if (parent.panel.document.general.Q3.value.length > 0)
		params = params+"Q3="+parent.panel.document.general.Q3.value+"&";
	if (parent.panel.document.general.Q4.value.length > 0)
		params = params+"Q4="+parent.panel.document.general.Q4.value+"&";

	if (timeint)
	{
		if (parent.panel.document.general.IDATEM.value.length > 0)
		{
			var idatem = parseInt(parent.panel.parent.panel.document.general.IDATEM.value,10);
			params = params+"IDATEM="+idatem+"&";
		}
		if (parent.panel.document.general.IDATEY.value.length > 0)
		{
			var idatey = parseInt(parent.panel.document.general.IDATEY.value,10);
			params = params+"IDATEY="+idatey+"&";
		}
		if (parent.panel.document.general.FDATEM.value.length > 0)
		{
			var fdatem = parseInt(parent.panel.document.general.FDATEM.value,10);
			params = params+"FDATEM="+fdatem+"&";
		}
		if (parent.panel.document.general.FDATEY.value.length > 0)
		{
			var fdatey = parseInt(parent.panel.document.general.FDATEY.value,10);
			params = params+"FDATEY="+fdatey+"&";
		}
	}
	else
	{
		idatem = parent.panel.document.general.IDATEM.value;
		idatey = parent.panel.document.general.IDATEY.value;
		fdatem = parent.panel.document.general.FDATEM.value;
		fdatey = parent.panel.document.general.FDATEY.value;
		if (idatey.length > 0)
		{
			idate = idatey+"-";
			if (idatem.length > 0)
				idate = idate+idatem+"-1";
			else
				idate = idate+"-1-1";
			params = params+"IDATE="+idate+"&";
		}
		if (fdatey.length > 0)
		{
			fdate = fdatey+"-";
			if (fdatem.length > 0)
				fdate = fdate+fdatem+"-31";
			else
				fdate = fdate+"-1-1";
			params = params+"FDATE="+fdate+"&";
		}
	}
// 
//   Code segment for another approach to latitude continuous navigation (crossing the Poles and continuing navigation 180 degrees west ahead)
//   res = 0 -> lat = 62 ; res = 1 -> lat = 85 ; res = 2 -> lat = 90 ; (turning points and respective resolutions)
//
  switch (res)
  {
   case 0:
    if(lat < -62 )
	  {
	   lat = -62;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
	   alert("<?= $strSPoleCrossed ?>");
	  }
	  else
	  if(lat >= 73)
	  {
	   lat = 73;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
	   alert("<?= $strNPoleCrossed ?>");
	  }
      break
   case 1:
    if(lat < -85 )
	  {
	   lat = -85;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
		 alert("<?= $strSPoleCrossed ?>"); 
	  }
	  else
	  if(lat > 85)
	  {
	   lat = 85;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
	   alert("<?= $strNPoleCrossed ?>");
	  }
      break
   case 2:
    if(lat < -90 )
	  {
	   lat = -90;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
	   alert("<?= $strSPoleCrossed ?>");
	  }
	  else
	  if(lat > 90)
	  {
	   lat = 90;
	   lon += 180;
	   if(lon > 180) lon = -(2*180 - lon);
	   alert("<?= $strNPoleCrossed ?>");
	  }
      break 
  }
//
// End segment
//
	params = params+"njumps="+njumps+"&"; // added to control the jump back to the starting point
	params = params+"LAT="+lat+"&";
	params = params+"LON="+lon+"&";
	params = params+"RES="+res+"&";
	params = params+"IMA="+ima+"&";
	params = params+"TEM="+tem+"&";
	params = params+"MYLON="+mylon+"&";
	params = params+"MYLAT="+mylat+"&";
	location="mosaico.php"+params;
}
window.onload=function()
{
	parent.panel.document.general.LAT.value = <?=$LAT?>;
	parent.panel.document.general.LON.value = <?=$LON?>;
}
</script>
<?php
// General initialization
/*
define ("LATMIN",-45.);
define ("LATMAX",10.);
define ("LONMIN", -84.);
define ("LONMAX", -30.); 
*/

define ("LATMIN",-90.);
define ("LATMAX",90.);
define ("LONMIN",-180.);
define ("LONMAX",180.); 

// define ("$PHP_SELF","mosaico.php");
//define ("BASEDIR","/home/www/catalogo/");
//define ("BASEDIR","/web/htdocs/Cdsr/catalogov2.0/");
define("BASEDIR", "../Suporte/");
define("BASEDIRA", $GLOBALS["basedir"]."Suporte/"); 


$resolution	= array (150000,40000,10000,2000,600,230,45,20,5,2);

$layers		= array(
    "Generica"		=> array("Gen&eacute;rica",0,9),
    "Landsat97"		=> array("Landsat 1997",0,5),
    "Landsat99"		=> array("Landsat 1999",0,5),
//    "esad"		=> array("Landsat 1990",1,3),
    "esad"		  => array("Landsat 1990",0,3),
    "intersat"		=> array("Mosaico Landsat",0,3),
    "spotveg"		=> array("Spot Vegeta&ccedil;&atilde;o",0,3),
    "Jers95"		=> array("Jers 1995",0,4),
    "dmsp"		=> array("Luzes",0,2),
    "wfi"		=> array("CBERS-WFI",0,3),
    ""			=> array("Nenhum",0,8),
    "politico"		=> array("Pol&iacute;tico",1,3),
    "politico-novo"		=> array("Pol&iacute;tico-novo",0,2),
    "desflorestamento"  => array("Desmatamento97",0,5),
    "floresta"		=> array("Floresta",0,5),
    "vegetacao"  	=> array("Vegeta&ccedil;&atilde;o",0,3),
//    "cenas"		=> array("Cenas",1,3),
    "cenas"		=> array("Cenas",0,3),
//    "Mss"		=> array("Mss",1,3),
    "Mss"		=> array("Mss",0,3),  
//    "cbers"		=> array("Cbers",1,3),
    "cbers"		=> array("Cbers",0,3),
    "cbers2b"		=> array("Cbers 2B",1,2),
    "cartas"		=> array("Cartas",2,2)
); 

// Check if file exists in BASEDIR
function FileStatus($filespec)
{
	$files = BASEDIRA . $filespec;
 	if (file_exists($files))
    		return 1;
	else
		return 0;
}

// Transform DMS <-> decimal seconds
function DMS2Sec ($res,&$ressec)
{
	$resg = (int)($res/10000);
	$resm = (int)(($res - $resg*10000)/100);
	$ress = (int)($res-($resg*10000+$resm*100));
	$ressec = $resg*3600+$resm*60+$ress;
}

function AbsPosButton($id,$text,$x,$y,$lat,$lon,$mylat,$mylon,$res,$tam,$tem,$ima,$mes)
{
	global $OFFX,$OFFY;
	
	printf ("<div id=\"%s\" style=\"position:absolute;left:%4d;top:%4d;\">\n<a",$id,$OFFX+$x,$OFFY+$y);
	printf (" onClick=\"goNext(%f,%f,%f,%f,%d,'%s','%s','%s','%s')\"",$lat/3600.,$lon/3600.,$mylat,$mylon,$res,$ima,$tem,$tam,$mes);
	printf (">\n<img src=\"%simages/%s.gif\" title=\"%s\" border=\"0\"></a></div>\n",BASEDIR, $id,$text);
}

function AbsPos($id,$x,$y)
{
	global $OFFX,$OFFY;
	printf("<div nowrap id=\"%s\" STYLE=\"position:absolute;LEFT:%d;TOP:%d;\">",$id,$OFFX+$x,$OFFY+$y);
}

function AbsPosEnd()
{
		printf ("</div>\n");
}

function Sec2DMS ($v , &$d, &$m, &$s)
{
	$l = abs ($v);
	$d = (int)($l/3600);
	$m = (int)(($l - $d*3600)/60);
	$s = (int)($l-($d*3600+$m*60));
}

// Truncate a decimal seconds value  to an integer number of resolution 
function GenerateBase ($val,$ressec,&$valbase)
{
	if ($val < 0)
	{
		if ($val%$ressec)
			$val = $val-$ressec;
	}

	$valbase = (int)($val/$ressec)*$ressec;
}
function FormatLatLon ($lat,$lon,$res)
{
  global $resolution;
	$resi = $resolution[$res];

	DMS2Sec ($resi,$ressec);

	$hemis = $lat>=0 ? "N" : "S";
	$hemiss = $lon>=0 ? "L" : "O";

	GenerateBase ($lat,$ressec,$latbase);
	GenerateBase ($lon,$ressec,$lonbase);

	Sec2DMS ($latbase,$latd,$latm,$lats);
	Sec2DMS ($lonbase,$lond,$lonm,$lons);

	printf("%s%02d:%02d:%02d %s%02d:%02d:%02d",$hemis,$latd,$latm,$lats,$hemiss,$lond,$lonm,$lons);
}

function GenerateFileName ($latbase,$lonbase,$res,&$dir,&$file)
{
  global $resolution;
	$resi = $resolution[$res];

	DMS2Sec ($resi,$ressec);

	$hemis = $latbase>=0 ? "N" : "S";
	$hemiss = $lonbase>=0 ? "L" : "O";

	Sec2DMS ($latbase,$latd,$latm,$lats);
	Sec2DMS ($lonbase,$lond,$lonm,$lons);

	Sec2DMS ($ressec,$resd,$resm,$ress);
	$file =	sprintf("%s%02d%02d%02d%s%02d%02d%02d%02d%02d%02d",
	$hemis,$latd,$latm,$lats,$hemiss,$lond,$lonm,$lons,$resd,$resm,$ress);

	if ($resi==10000)
		$dir = sprintf("%d/%s%02d",$resi,$hemis,(int)($latd/10)*10);
	else if ($resi==2000)
		$dir = sprintf("%d/%s%02d",$resi,$hemis,$latd);
	else if ($resi==600)
		$dir = sprintf("%d/%s%02d%s%03d",$resi,$hemis,(int)($latd/2)*2,$hemiss,(int)($lond/2)*2);
	else if ($resi==230)
		$dir = sprintf("%d/%s%02d%s%03d",$resi,$hemis,$latd,$hemiss,$lond);
	else if ($resi == 45)
		$dir = sprintf("%d/%s%02d%s%03d/%s%02d%02d%s%03d",
		$resi,$hemis,$latd,$hemiss,$lond,$hemis,$latd,(int)($latm/5)*5,$hemiss,$lond);
	else if ($resi == 20)
		$dir = sprintf("%d/%s%02d%s%03d/%s%02d%02d%s%03d%02d",
		$resi,$hemis,$latd,$hemiss,$lond,$hemis,$latd,(int)($latm/5)*5,$hemiss,$lond,(int)($lonm/5)*5);
	else if ($resi == 5 || $resi == 2)
		$dir = sprintf("%d/%s%02d%s%03d/%s%02d%02d%s%03d%02d",
		$resi,$hemis,$latd,$hemiss,$lond,$hemis,$latd,$latm,$hemiss,$lond,(int)($lonm/5)*5);
	else
		$dir = sprintf("%d",$resi);
}

function WriteCell ($lat,$lon,$res,$resnext,$tam,$tem,$ima,$mes)
{
	allow_call_time_pass_reference;
	global $resolution;
	global $dx,$dy,$OFFX,$OFFY;
	$resi = $resolution[$res];
	DMS2Sec ($resi,$ressec);
	
	if($lon < -648000) $lon = 648000 + fmod($lon,648000); //  // Just to allow continually navigate longitudinaly
  else if($lon > 648000) $lon = fmod($lon,648000) - 648000; 
  
//  if($lat < -324000) $lat = 324000 + fmod($lat,324000); //  // Just to allow continually navigate latitudinaly
//  else if($lat > 324000) $lat = fmod($lat,324000) - 324000; 
 
	GenerateBase ($lat,$ressec,$latbase);
	GenerateBase ($lon,$ressec,$lonbase);
//	GenerateFileName ($latbase,$lonbase,$res,&$dir,&$file);
  GenerateFileName ($latbase,$lonbase,$res,$dir,$file);

//	$imagefile = "imagens/".$ima."/".$dir."/imag".$file.".jpg";
  if($ima == "") $imagefile = "imagens/".$ima."/".$dir."/imag".$file.".jpg";
  else $imagefile = "imagens/esad/" . $dir . "/imag" . $file . ".jpg";
	$ImageExist = FileStatus ($imagefile);

	if ($ImageExist == 0)
		$imagefile = BASEDIR . "images/blank.jpg";
   else
      $imagefile = BASEDIR . $imagefile;
      
	printf ("<DIV id=\"imagem\" STYLE=\"position:absolute;LEFT:%d;TOP:%d;\">\n",$dx+$OFFX,$dy+$OFFY); 
	printf ("<IMG SRC=\"%s\"></div>\n",$imagefile);

//	$mapfile = "mapas/".$tem."/".$mes."/".$dir."/mapa".$file.".gif";
	if($lat >= -56*3600 and $lat <= 15*3600 and $lon >= -83*3600 and $lon <= -27*3600) ; else $tem = "politico-novo" ; // Out of South America Continental
                                                                                                                     // limits, we change $tem to "politico-novo"
                                                                                                                     // in order to have all levels of resolution
                                                                                                                     // on segments' maps (they are not present on 
                                                                                                                     // directories other than "politico-novo"

  $mapfile = "mapas/".$tem."/" . $dir."/mapa".$file.".gif";
	$MapExist = FileStatus ($mapfile);
	if ($MapExist == 0)
	{
		if ($tem)
			$mapfile = BASEDIR . "images/grade.gif";
		else
			$mapfile = BASEDIR . "images/blank.gif";
	}else
	  $mapfile = BASEDIR . $mapfile;
	
	printf ("<DIV id=\"%s\" STYLE=\"position:absolute;LEFT:%d;TOP:%d;\"><a",$file,$dx+$OFFX,$dy+$OFFY);
	printf (" onClick=\"zoomIn(event)\"");
	printf (" onMouseLeave=\"leaveIt()\"");
	printf (" onMouseMove=\"displayLL(event)\">\n");
	printf ("<IMG SRC=\"%s\"BORDER=\"0\" WIDTH=\"150\" HEIGHT=\"150\"></a></DIV>\n\n",$mapfile); 

}
// BEGIN

// echo "<br>" . $_SERVER['QUERY_STRING'] . " <br>";

// Get browser information
	$dy = 15;
	$dx = 15;

	if (browser_is_ie())
	{
		$OFFX=1;
//		$OFFY=20;
    $OFFY=50;
//    $OFFY=150;
	}
	else
	{
		$OFFX=1;
//		$OFFY=20;
    $OFFY=50;
//    $OFFY=150;
	}

// Start processing

//	if (! $IMA) $IMA = "esad";
  if(!isset($IMA)) $IMA = "";

	if (! $TEM)
//		$TEM = "politico";
    $TEM = "politico-novo";

	if (!isset($RES)) $RES = 0; 
	
	if($LAT >= -56 and $LAT <= 15 and $LON >= -83 and $LON <= -27) ;
	else $TEM = "politico-novo" ; // Out of South America Continental
                                // limits, we change $tem to "politico-novo"
                                // in order to have all levels of resolution
                                // on segments' maps (they are not present on 
                                // directories other than "politico-novo"


	$res = min (max ($RES,$layers[$IMA][1]),$layers[$IMA][2]);
	$res = min (max ($res,$layers[$TEM][1]),$layers[$TEM][2]);

	DMS2Sec ($resolution[$res],$ressec);

	$resprev = max ($res-1,$layers[$IMA][1]);
	$resprev = max ($resprev,$layers[$TEM][1]);

	$resnext = min ($res+1,$layers[$IMA][2]);
	$resnext = min ($resnext,$layers[$TEM][2]);
	
  if($LON <= -180) $LON = 180 + fmod($LON,180);  //  // Just to allow continually navigate longitudinaly
  else if($LON >= 180) $LON = fmod($LON,180) - 180;
  
//  if($LAT <= -90) $LAT = 90 + fmod($LAT,90);  //  // Just to allow continually navigate latitudinaly
//  else if($LAT >= 90) $LAT = fmod($LAT,90) - 90;


	$latcenter = round($LAT * 3600);
	$loncenter = round($LON * 3600);	

	switch ($TAM)
	{
		case "G":
		$xu = 3;
		$xl = 2;
		$yu = 2;
		$yl = 1;
		$fx = 6./3.;
		$fy = 4./2.;
		break;
		
		case "P":
		$xu = 1;
		$xl = 1;
		$yu = 1;
		$yl = 0;
		$fx = 1.;
		$fy = 1.;
		break;

		case "M":
		default :
		$TAM = "M";
		$xu = 2;
		$xl = 1;
		$yu = 1;
		$yl = 1;
		$fx = 4./3.;
		$fy = 3./2.;
		break;
	} 

	GenerateBase ($latcenter-$yl*$ressec,$ressec,$latbase); // Evaluate lower left latitude
	GenerateBase ($loncenter-$xl*$ressec,$ressec,$lonbase); // Evaluate lower left longitude
	GenerateBase ($latcenter+($yu+1)*$ressec,$ressec,$lattop); // Evaluate upper right latitude
	GenerateBase ($loncenter+($xu+1)*$ressec,$ressec,$lontop); // Evaluate upper right longitude
?>
<input style="position:absolute;left:<?=215*$fx?>;top:10;font-size:15;" type=button value=<?=$strClose?> onclick=history.go(<?= -$njumps?>) >  
<?php 


	echo "</head>\n";

	echo "<body>\n";

	printf ("<div id=\"moldura\" style=\"position:absolute;left:%d;top:%d;\">",$OFFX,$OFFY);
	printf ("<img src=\"%simages/moldura%s.png\"></div>\n",BASEDIR,$TAM);
// Javascript to evaluate mouse current position and zoom in
?>

<script language="JavaScript">
function zoomIn(e)
{
	var lat,lon,posx,posy;
	var nav4 = window.Event ? true : false;

	if (nav4)

	{ // Navigator 4.0x

		posx = e.pageX;

		posy = e.pageY;

	}

	else

	{ // Internet Explorer 4.0x

		posx = event.clientX + document.body.scrollLeft;

		posy = event.clientY + document.body.scrollTop;

	}

<?php
if (($SATELITE == 'CB2') OR ($SATELITE == 'CB2B'))
  	$TEM = 'cbers';
elseif ($SENSOR == 'MSS')
  	$TEM = 'Mss';
elseif (($SENSOR == 'TM') OR ($SENSOR == 'ETM') OR ($SENSOR == "TM,ETM"))
	  $TEM = 'cenas';
else 
//	$TEM= 'politico';
    $TEM = "politico-novo";

if($res == 0) $TEM = "politico-novo"; // To display the "mapa-mundi"
	
 	printf ("	var sizey = %d;\n",150*($yu+$yl+1));
	printf ("	lon = (%d + (posx-%d-%d)/150*%d)/3600.;\n",$lonbase,$dx,$OFFX,$ressec);
	printf ("	lat = (%d + (sizey - 1 - (posy-%d-%d))/150*%d)/3600.;\n",$latbase,$dy,$OFFY,$ressec);
	printf("\n	goNext(lat,lon,%f,%f,%d,'%s','%s','%s','%s');\n",$MYLAT,$MYLON,$resnext,$IMA,$TEM,$TAM,$MES);
	echo "\n}\n";
	
// Javascript to evaluate mouse current position and display coordinates
?>
function displayLL(e)
{
	var lat,lon,lath,lata,latd,latm,lats,lonh,lona,lond,lonm,lons,posx,posy;
	var nav4 = window.Event ? true : false;

	if (nav4)

	{ // Navigator 4.0x 

		posx = e.pageX;

		posy = e.pageY;

	}

	else 

	{ // Internet Explorer 4.0x

		posx = event.clientX + document.body.scrollLeft;

		posy = event.clientY + document.body.scrollTop;

	}

<?php
	printf ("	var sizey = %d;\n",150*($yu+$yl+1));
	printf ("	lon = (%d + (posx-%d-%d)/150.*%d)/3600.;\n",$lonbase,$dx,$OFFX,$ressec);
	printf ("	lat = (%d + (sizey - (posy-%d-%d))/150.*%d)/3600.;\n",$latbase,$dy,$OFFY,$ressec); 
	printf ("	if (lat<0.) lath = 'S'; else lath = 'N';\n");
	printf ("	lata = Math.abs(lat);\n");
	printf ("	latd = Math.floor(lata);\n");
	printf ("	latm = Math.floor((lata-latd)*60);\n");
	printf ("	lats = Math.floor(lata*3600 - latd*3600 - latm*60);\n");
	printf ("	if (lon<0.) lonh = 'O'; else lonh = 'L';\n");
	printf ("	lona = Math.abs(lon);\n");
	printf ("	lond = Math.floor(lona);\n");
	printf ("	lonm = Math.floor((lona-lond)*60);\n"); 
	printf ("	lons = Math.floor(lona*3600 - lond*3600 - lonm*60);\n");
	printf ("	if(latd<10) latd = \"0\" + latd;\n");
	printf ("	if(latm<10) latm = \"0\" + latm;\n");
	printf ("	if(lats<10) lats = \"0\" + lats;\n");
	printf ("	if(lond<10) lond = \"0\" + lond;\n");
	printf ("	if(lonm<10) lonm = \"0\" + lonm;\n");
	printf ("	if(lons<10) lons = \"0\" + lons;\n");
	echo "	document.getElementById(\"currpos\").innerHTML='<font size=2 color =\"#FFFFFF\"><strong><em>'+lath+':'+latd+':'+latm+':'+lats+'&nbsp;&nbsp;&nbsp;'+lonh+':'+lond+':'+lonm+':'+lons+'</em></strong></font>';";
	echo "\n}\n";
	
// Javascript to erase coordinates display
	echo "	function leaveIt()\n{\n";
	echo "	currpos.innerHTML='<font size=1>&nbsp;</font>';";
	echo "\n}\n";
	echo "\n</script>\n";

	AbsPosButton ("menos",$strReduce, 8+75*$fx,  0,$latcenter,$loncenter,$MYLAT,$MYLON,$resprev,$TAM,$TEM,$IMA,$MES);

	AbsPosButton ("mais",$strExtend, 8+375*$fx,  0,$latcenter,$loncenter,$MYLAT,$MYLON,$resnext,$TAM,$TEM,$IMA,$MES);


	AbsPosButton ("noroeste",$strSeeNorthwest,  -2,  -2,$latcenter+$ressec,$loncenter-$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("norte",  $strSeeNorth,   8+225*$fx,  1,$latcenter+$ressec,$loncenter,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("nordeste",$strSeeNortheast,17+450*$fx,  -2,$latcenter+$ressec,$loncenter+$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("oeste",   $strSeeWest,     0,8+150*$fy,$latcenter,$loncenter-$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("leste",   $strSeeEast,   14+450*$fx,8+150*$fy,$latcenter,$loncenter+$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("sudoeste",$strSeeSouthwest,  -2,17+300*$fy,$latcenter-$ressec,$loncenter-$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);	
	AbsPosButton ("sul",     $strSeeSouth,     8+225*$fx,14+300*$fy,$latcenter-$ressec,$loncenter,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);
	AbsPosButton ("sudeste", $strSeeSoutheast, 17+450*$fx,17+300*$fy,$latcenter-$ressec,$loncenter+$ressec,$MYLAT,$MYLON,$res,$TAM,$TEM,$IMA,$MES);

	AbsPosButton ("backgoff",$strImageDisable, 148+225*$fx,14+300*$fy,$latcenter,$loncenter,$MYLAT,$MYLON,$res,$TAM,$TEM,"",$MES);
	AbsPosButton ("backgon",$strImageEnable, 48+225*$fx,14+300*$fy ,$latcenter,$loncenter,$MYLAT,$MYLON,$res,$TAM,$TEM,"esad",$MES);

	
	echo "\n<font size=\"1\"  color =\"#502814\">\n";
	AbsPos ("lln",450*$fx-120,-15);
	FormatLatLon ($latcenter+($yu+1)*$ressec,$loncenter+($xu+1)*$ressec,$res);
	AbsPosEnd ();
	echo "\n";
	AbsPos ("lls",16,30+300*$fy);
	FormatLatLon ($latcenter-$yl*$ressec,$loncenter-$xl*$ressec,$res);
	AbsPosEnd ();
	echo "\n</font>\n";

	AbsPos ("currpos",430*$fx-120,16+300*$fy);
	AbsPosEnd ();

	for ($j=$yu;$j>=-$yl;$j-=1)
	{
		$dx = 15;
		for ($i=-$xl;$i<=$xu;$i++)
		{ 
			WriteCell ($latcenter+$j*$ressec,$loncenter+$i*$ressec,$res,$resnext,$TAM,$TEM,$IMA,$MES);
			$dx += 150;
		}
		$dy += 150;
	}
	$dx += 100;
	$dy = 15;
	
	if ($MYLON && $MYLAT)
	{
		$sizey = 150*($yu+$yl+1);
		$x = 150*($MYLON*3600.-$lonbase)/$ressec-8;
		$y = $sizey - 150*($MYLAT*3600.-$latbase)/$ressec-8;
		
//		printf ("<div id=\"target\" style=\"position:absolute;left:%4d;top:%4d;\">\n",$OFFX+15+$x,$OFFY+15+$y);
	  printf ("<div id=\"target\" style=\"position:absolute;left:%4d;top:%4d;\">\n",$OFFX+11+$x,$OFFY-20+$y);
	  printf ("<img src=\"%simages/arrow-blue.png\" border=\"0\" width=\"25\" height=\"35\"></div>\n", BASEDIR);
	}

//require("dbglobals.inc.php");
$dbcat = $GLOBALS["dbcatalog"];
$table="Scene";
$sql = "SELECT Satellite,Path,Row,CenterLongitude,CenterLatitude,Date FROM $table WHERE Deleted=0";
$sql .= " AND CenterLatitude <= " . $lattop/3600 . " AND CenterLatitude >= " . $latbase/3600;
$sql .= " AND CenterLongitude >= " . $lonbase/3600 . " AND CenterLongitude <= " . $lontop/3600;
$params = "&";

if ($SATELITE)
{ 
  $complement = "";
  if($SATELITE == "GLS") 
  {
   $SATELITE = "L[5,7]";
   $condition = "REGEXP";
   $sql .= " AND SceneId LIKE '%GLS' ";
  }
//  else if($SATELITE == "L5" or $SATELITE == "L7") { $condition = "=" ; $complement = " AND SceneId NOT LIKE '%GLS' ";}
  else $condition = "=";
	$sql .= " AND Satellite $condition '" . $SATELITE . "'" . $complement ;
	
	if($SATELITE == "L[5,7]")
	{
	 $SATELITE = "GLS";
	 $SENSOR = "";
	}
	
	$params .= "SATELITE=".$SATELITE."&";
}
if ($SENSOR)
{
	if($SENSOR == "TM,ETM") 
  {
   $SENSOR = "[ETM,TM]";
   $condition = "REGEXP";
  }
  else $condition = "=";
	$sql .= " AND Sensor $condition '" . $SENSOR . "'";

	$params .= "SENSOR=".$SENSOR."&";
}
if ($IDATEM && $FDATEM)
{
	$params .= "IDATEM=".$IDATEM."&"."FDATEM=".$FDATEM."&"; 
	if ($IDATEM > $FDATEM)
	{
		$sql .= " AND (month(Date)>=" . $IDATEM . " OR   ";
		$sql .= "month(Date)<=" . $FDATEM . ")";
	}
	else if ($IDATEM == $FDATEM)
	{
		$sql .= " AND month(Date)=" . $IDATEM;
	}
	else
	{
		$sql .= " AND month(Date)>=" . $IDATEM;
		$sql .= " AND month(Date)<=" . $FDATEM; 
	}
}
else if ($IDATEM)
	{
		$params .= "IDATEM=".$IDATEM."&";
		$sql .= " AND month(Date)>=" . $IDATEM;
	}
else if ($FDATEM)
	{
		$params .= "FDATEM=".$FDATEM."&";
		$sql .= " AND month(Date)<=" . $FDATEM;
	}
if ($IDATEY && $FDATEY)
{
	$params .= "IDATEY=".$IDATEY."&"."FDATEY=".$FDATEY."&";
	if ($IDATEY == $FDATEY)
	{
		$sql .= " AND year(Date)=" . $IDATEY;
	}
	else
	{
		$sql .= " AND year(Date)>=" . $IDATEY;
		$sql .= " AND year(Date)<=" . $FDATEY;
	}
}
else if ($IDATEY)
	{
		$params .= "IDATEY=".$IDATEY."&";
		$sql .= " AND year(Date)>=" . $IDATEY;
	}
else if ($FDATEY)
	{
		$params .= "FDATEY=".$FDATEY."&";
		$sql .= " AND year(Date)<=" . $FDATEY;
	}
if ($IDATE)
{
	$params .= "IDATE=".$IDATE."&";
	$sql .= " AND Date >= '" . $IDATE . " 00:00:00"."'";
}
if ($FDATE)
{
	$params .= "FDATE=".$FDATE."&";
	$sql .= " AND Date <= '" . $FDATE . " 23:59:59"."'";
}

if (isset($Q1))
{
	$params .= "Q1=".$Q1."&";
	$sql .= " AND CloudCoverQ1<=$Q1";
}
if (isset($Q2))
{
	$params .= "Q2=".$Q2."&";
	$sql .= " AND CloudCoverQ2<=$Q2";
}
if (isset($Q3))
{
	$params .= "Q3=".$Q3."&";
	$sql .= " AND CloudCoverQ3<=$Q3";
}
if (isset($Q4))
{
	$params .= "Q4=".$Q4."&";
	$sql .= " AND CloudCoverQ4<=$Q4";
}

$manager = "mantab";
if (isset($QUICK) && $QUICK=="B")
	$manager = "manage";

// echo $sql;
$params .= "TAM=".$TAM;
$dbcat->query($sql);
$sizey = 150*($yu+$yl+1);
while ($myrow = $dbcat->fetchRow())
{
	$mylon = $myrow["CenterLongitude"];
	$mylat = $myrow["CenterLatitude"];
	$x = 150*($mylon*3600.-$lonbase)/$ressec-8;
	$y = $sizey - 150*($mylat*3600.-$latbase)/$ressec-8;
	$path = $myrow["Path"];
	$row = $myrow["Row"];
	$sat = $myrow["Satellite"];
	
	if (!isset ($pathrowsat[$sat][$path][$row]))
	{
	printf ("<div id=\"target\" style=\"position:absolute;left:%4d;top:%4d;\">\n<a",$OFFX+15+$x,$OFFY+15+$y);
	printf(" href=\"%s.php?ORBITAI=%s&PONTOI=%s%s\"",$manager,$path,$row,$params);
	printf (">\n<img src=\"%simages/%s.gif\" title=\"%s-%s/%s-%s\" border=\"0\"></a></div>\n", BASEDIR,
         $myrow["Satellite"],$myrow["Satellite"],$path,$row,$myrow["Date"] );
	$pathrowsat[$sat][$path][$row] = 1;
	}
}
?>
<!-- <input type="button" value=$strClose onClick = "window.location.href = 'first.php'" > <br> -->
</body>
</html>
