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

require ("panel_".$_SESSION['userLang'].".php"); 
?>
<html>
<head>
<title></title>
<script type="text/javascript">

var satellites = new Array("","CB2","CB2B","L1","L2","L3","L5","L7","L8","T1","A1","P6","GLS","DMC"); 
var sensors = new Array();
/*
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
*/
sensors[0] = new Array(["",""]);
sensors[1] = new Array(["",""],["CCD","CCD"],["IRM","IRM"],["WFI","WFI"]); 
sensors[2] = new Array(["",""],["CCD","CCD"],["HRC","HRC"],["WFI","WFI"]);
sensors[3] = new Array(["MSS","MSS"]);
sensors[4] = new Array(["MSS","MSS"]);
sensors[5] = new Array(["MSS","MSS"]);
sensors[6] = new Array(["TM","TM"]);
sensors[7] = new Array(["ETM","ETM"]);
sensors[8] = new Array(["OT","OLI_TIRS"]);
sensors[9] = new Array(["MODIS","MODIS"]);
sensors[10] = new Array(["MODIS","MODIS"]);
sensors[11] = new Array(["LIS3","LISS3"],["AWIF","AWIFS"]);
sensors[12] = new Array(["TM,ETM","TM,ETM"]);
sensors[13] = new Array(["SLIM","SLIM6"]);
/*
var satellites = new Array("","CB2","L1","L2","L3"); 
var sensors = new Array();
sensors[0] = new Array("");
sensors[1] = new Array("","CCD","IRM","WFI"); 
sensors[2] = new Array("MSS");
sensors[3] = new Array("MSS");
sensors[4] = new Array("MSS");
*/
var countries = new Array("","ARGENTINA","BOLIVIA","BRASIL","CHILE","COLOMBIA","ECUADOR",
                          "FRENCH GUIANA","GUYANA","PARAGUAY","PERU","SURINAME","URUGUAY","VENEZUELA","ALGERIA","ANGOLA","BENIN","BOTSWANA","BURKINA FASO","BURUNDI","CAMEROON","CENT AF REP","CHAD","CONGO","DJIBOUTI","EGYPT","EQ GUINEA","ERITREA","ETHIOPIA","GABON","GAMBIA","GHANA","GUINEA","GUINEABISSAU","IVORY COAST","KENYA","LESOTHO","LIBERIA","LIBYA","MADAGASCAR","MALAWI","MALI","MAURITANIA","MOROCCO","MOZAMBIQUE","NAMIBIA","NIGER","NIGERIA","RWANDA","SENEGAL","SIERRA LEONE","SOMALIA","SOUTH AFRICA","SUDAN","SWAZILAND","TANZANIA","TOGO","TUNISIA","UGANDA","W SAHARA","ZAIRE","ZAMBIA","ZIMBABWE");

var states = new Array();
states[0] = new Array("");
states[1] = new Array("");
states[2] = new Array("");
states[3] = new Array("","AC","AL","AM","AP","BA","CE","DF","ES","GO","MA","MG","MS","MT","PA","PB",
                      "PE","PI","PR","RJ","RN","RO","RR","RS","SC","SE","SP","TO");
states[4] = new Array("");
states[5] = new Array("");
states[6] = new Array("");
states[7] = new Array("");
states[8] = new Array("");
states[9] = new Array("");
states[10] = new Array("");
states[11] = new Array("");
states[12] = new Array("");
states[13] = new Array("");

//States for Africa
states[14] = states[15] = states[16] = states[17] = states[18] = states[19] = states[20] = states[21] = states[22] = states[23] = states[24] = states[25] = states[26] = states[27] = states[28] = states[29] = states[30] = states[31] = states[32] = states[33] = states[34] = states[35] = states[36] = states[37] = states[38] = states[39] = states[40] = states[14] = states[41] = states[42] = states[43] = states[44] = states[45] = states[46] = states[47] = states[48] = states[49] = states[50] = states[51]= states[52] = states[53] = states[54] = states[55] = states[56] = states[57] = states[58] = states[59] = states[60] = states[61] = states[62] = states[63] = new Array("");

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

function goOn(url)
{
	tam = document.general.TAM.value;
	tem = document.general.TEM.value;
	mun = document.general.MUNICIPIO.value;
	pais =document.general.PAIS.value;
 	iest = document.general.ESTADO.selectedIndex;
 	ista = document.general.PAIS.selectedIndex;
 	est = states[ista][iest];
//  	alert(pais + " " + est);

	quick = document.general.quicklook[0].checked;
	if (quick)
		manager = "mantab.php";
	else
		manager = "manage.php";
		
	sat = document.general.SATELITE.value;
	isat = document.general.SATELITE.selectedIndex;
	isen = document.general.SENSOR.selectedIndex;
//	sen = sensors[isat][isen];
	sen = sensors[isat][isen][0];
//	alert(sat);
	
	params = "?";
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

	if (document.general.Q1.value.length > 0)
		params = params+"Q1="+document.general.Q1.value+"&";
	if (document.general.Q2.value.length > 0)
		params = params+"Q2="+document.general.Q2.value+"&";
	if (document.general.Q3.value.length > 0)
		params = params+"Q3="+document.general.Q3.value+"&";
	if (document.general.Q4.value.length > 0)
		params = params+"Q4="+document.general.Q4.value+"&";

	timeint = document.general.TIMEINTERVAL.checked;
	if (timeint)
	{ 
	  idated = document.general.IDATED.value.trim();
	  fdated = document.general.FDATED.value.trim();
	  idatem = document.general.IDATEM.value.trim();
	  fdatem = document.general.FDATEM.value.trim();
	  idatey = document.general.IDATEY.value.trim();
	  fdatey = document.general.FDATEY.value.trim();
	  
//	  if(isNaN(idated) || isNaN(fdated)) alert ("Invalid Date !");
//	  else 
//		 if(idated < 1 || idated > 31 || fdated < 1 || fdated > 31) alert ("Invalid Date !");
//	   else
//	   {
		  if (idatem > 0)
		  { 
			  idatem = parseInt(idatem,10);
			  if ( idatem < 1 || idatem > 12 )
				 params = params+"IDATEM=&";
			  else
				 params = params+"IDATEM="+idatem+"&";
		  } else params = params+"IDATEM=&";
		  if (idatey > 0)
		  {
			  idatey = parseInt(idatey,10);
			  params = params+"IDATEY="+idatey+"&";
		  } else params = params+"IDATEY=&";
		  if (fdatem > 0)
		  {
			  fdatem = parseInt(fdatem,10);
			  if ( fdatem < 1 || fdatem > 12 )
				  params = params+"FDATEM=&";
			  else
				  params = params+"FDATEM="+fdatem+"&";
		  } else params = params+"FDATEM=&";
		  if (fdatey > 0)
		  {
			  fdatey = parseInt(fdatey,10);
			  params = params+"FDATEY="+fdatey+"&";
		  } else params = params+"FDATEY=&";
		  if(idated.length == 0) idated = 01;
		  if(fdated.length == 0) fdated = 31;
		  params = params+"IDATED="+idated+"&FDATED="+fdated+"&"; 
//		} 
	}
	else
	{
	  idated = document.general.IDATED.value.trim();
		idatem = document.general.IDATEM.value.trim();
		idatey = document.general.IDATEY.value.trim();
		fdated = document.general.FDATED.value.trim();
		fdatem = document.general.FDATEM.value.trim();
		fdatey = document.general.FDATEY.value.trim();
		
//		if(isNaN(idated) || isNaN(fdated) || isNaN(idatem) || isNaN(fdatem) || isNaN(idatey) || isNaN(fdatey)) alert ("Invalid Date !");
//	  else 
//		 if(idated < 1 || idated > 31 || fdated < 1 || fdated > 31) alert ("Invalid Date !");
//	    else 
//	     if(idatem < 1 || idatem > 12 || fdatem < 1 || fdatem > 12 || idatey < 0 || fdatey < 0 ) alert ("Invalid Date !");
//			 else   
//			 {		
		    if (idatey.length > 0)
		    {
			    idate = idatey+"-";
			    if (idatem.length > 0)
			    {
				   idate = idate+idatem;
           if (idated.length > 0) idate = idate+"-"+idated;
           else idate = idate+"-01";
			    }
			    else
			      if (idated.length > 0) idate = idate+"01-"+idated; 
			      else idate = idate+"01-01";
		    } 
//		    else alert("Invalid Date !");
		    params = params+"IDATE="+idate+"&";
		    if (fdatey.length > 0)
		    {
			    fdate = fdatey+"-";
			    if (fdatem.length > 0)
			    {
				    fdate = fdate+fdatem;
            if (fdated.length > 0) fdate = fdate+"-"+fdated;
            else  fdate = fdate+"-31";
			    }
			    else
			      if (fdated.length > 0) fdate = fdate+"01-"+fdated; 
			      else fdate = fdate+"01-31"; 
		    }
//		    else alert("Invalid Date !");
		    params = params+"FDATE="+fdate+"&";
//		 }
	}
	if (url == 1)
	{ 
	  if (sat == 'DMC')  alert('<?=$strDMCAlert2?>')
	  else
	  {
	   pathi = document.general.ORBITAI.value;
		 pathf = document.general.ORBITAF.value;
		 rowi = document.general.PONTOI.value;
		 rowf = document.general.PONTOF.value;
		 if (pathi.length > 0)
			params = params+"ORBITAI="+pathi+"&";
		 if (pathf.length > 0)
			params = params+"ORBITAF="+pathf+"&";
		 if (rowi.length > 0)
			params = params+"PONTOI="+rowi+"&";
		 if (rowf.length > 0)
			params = params+"PONTOF="+rowf+"&";
		 parent.mosaico.location.href=manager+params;
		}
	}
	else if (url == 2)
	{
		lat1 = document.general.LAT1.value;
		if (lat1.length > 0)
			params = params+"LAT1="+lat1+"&";
		lat2 = document.general.LAT2.value;
		if (lat2.length > 0)
			params = params+"LAT2="+lat2+"&";
		lon1 = document.general.LON1.value;
		if (lon1.length > 0)
			params = params+"LON1="+lon1+"&";
		lon2 = document.general.LON2.value;
		if (lon2.length > 0)
			params = params+"LON2="+lon2+"&";
			
		parent.mosaico.location.href=manager+params;
	}
	else if (url == 3)
	{
		lat = document.general.LAT.value;
		if (lat.length > 0)
			params = params+"LAT="+lat+"&";
		lon = document.general.LON.value;
		if (lon.length > 0)
			params = params+"LON="+lon+"&";
		params = params+"IMA=esad&";
		params = params+"RES=1&";
		params = params+"TEM="+tem;
//    alert('<?= $strNavigation1 ?>');
		parent.mosaico.location.href="mosaico.php"+params; 
	}
	else if (url == 4)
	{ 
		params = params+"IMA=esad&";
		params = params+"TEM="+tem+"&";
 
//    alert('<?= $strNavigation1 ?>');
    if (est == "") est = "*"; 
		if (est.length > 0)
			params = params+"ESTADO="+est+"&";
		if (mun.length > 0)
			params = params+"MUNICIPIO="+mun+"&";
		if (pais.length > 0)
			params = params+"PAIS="+pais;
		parent.mosaico.location.href='municipios.php'+params;
	}
	else if (url == 5)
	{ 
	  if (sat.length > 0 && sen.length > 0 && sat != 'DMC')
	  {
	   mpath = document.general.MPATH.value.trim();
	   mdated = document.general.MDATED.value.trim();
		 mdatem = document.general.MDATEM.value.trim();
		 mdatey = document.general.MDATEY.value.trim();
		 
//		 if (sat != "T1" && sat != "A1" && sat != "CB2" && sat != "GLS") // && sat != "CB2B")
    if (sat != "CB2" && sat != "GLS")
		 {	 
		  if (isNaN(mdated) || isNaN(mdatem) || isNaN(mdatey)) alert('<?=$strMosaicAlert5?>')
		  else if(mpath.length > 0 && (mdated > 0 || mdatem > 0 || mdatey > 0))alert ('<?=$strMosaicAlert2?>')
		  else
		  if (mpath.length > 0)
		   if (sat == "A1" || sat == "T1") alert('<?=$strMosaicAlert6?>')	
		   else
		   if (!isNaN(mpath))     // is Not a Number
		   {
			  params = params+"PATH="+mpath+"&";
			  url = 'passagens.php'+params;
		    parent.mosaico.location.href=url;		 
       }
		   else alert('<?=$strMosaicAlert4?>')
		  else
		  if(mdated > 0 && mdatem > 0 && mdatey > 0)
//		  if(sat != "CB2")
		  { 
		   date = mdatey + "-" + mdatem + "-" + mdated;
		   params = params + "DATE=" + date + "&"; 

       if(sen == "MODIS") url = 'mosaico_passagens_modis.php'+params;
       else url = 'mosaico_passagens.php'+params;
//     window.open(url,'Mosaic','resizable=yes,scrollbars=yes,'); 
		   parent.mosaico.location.href=url;
		  } // else alert('<?=$strMosaicAlert3?>') 
		  else alert('<?=$strMosaicAlert5?>')		 
		 } else alert('<?=$strMosaicAlert3?>') 		 
		} else if (sat == 'DMC')  alert('<?=$strDMCAlert1?>') 
		       else alert('<?=$strMosaicAlert1?>');
		
		
	}
	
}

function displaySatellite(entry)
{ 
//	if (satellites[entry] == "DMC") alert('<?=$strDMCAlert1?>');
	if (satellites[entry] == "L1" || satellites[entry] == "L2" || satellites[entry] == "L3")
		document.general.TEM.value='Mss';
	else if (satellites[entry] == "L5" || satellites[entry] == "L7" || satellites[entry] == "GLS")
		document.general.TEM.value='cenas';
	else if (satellites[entry] == "CB2")
		document.general.TEM.value='cbers';
	else if (satellites[entry] == "CB2B")
		document.general.TEM.value='cbers';
	else
		document.general.TEM.value='politico-novo'; 
		
	while (sensors[entry].length < document.general.SENSOR.options.length)
	{
		document.general.SENSOR.options[(document.general.SENSOR.options.length - 1)] = null;
	} 
	for (y=0;y<sensors[entry].length;y++)
	{
//		document.general.ESTADO[y]=new Option(states[entry][y]);
		document.general.SENSOR[y]=new Option(sensors[entry][y][1]);
	} 
	
//	if (document.general.SATELITE.value == "L1" || document.general.SATELITE.value == "L2" || document.general.SATELITE.value == "L3") 
	//parent.location.href='http://www.dgi.inpe.br/CatalogoMSS';	
	
//	if (document.general.SATELITE.value == "L5" || document.general.SATELITE.value == "L7")
//	alert("Acesse www.dgi.inpe.br/catalogo");
//  parent.location.href='http://www.dgi.inpe.br/catalogo';
}

function displayStates(entry)
{
	while (states[entry].length < document.general.ESTADO.options.length)
	{
		document.general.ESTADO.options[(document.general.ESTADO.options.length - 1)] = null;
	}
	for (y=0;y<states[entry].length;y++)
	{
		document.general.ESTADO[y]=new Option(states[entry][y]);
	}
}

window.onload=function()
{
	if(screen.width>1024)
	{
		document.general.TAM.value='G';
	}
	else if(screen.width>800)
	{
		document.general.TAM.value='M';
	}
	else
	{
		document.general.TAM.value='P';
	}
}
</script>
<?php 
require("css.php");
$today   = date( "Y-m-d", strtotime ("-1 day" ) ) ;
$todayy   = date( "Y", strtotime ("-1 day" ) ) ;
$oneyeary = date( "Y", strtotime ("-31 year", strtotime($today ) )) ;
$year_beginning = "1973";
$todaym   = date( "m", strtotime ("-1 day" ) ) ;
$oneyearm = date( "m", strtotime ("-31 year", strtotime($today ) )) ;
$month_beginning = "05";
$day_beginning = "29";
$todayd   = date( "d", strtotime ("-1 day"));
$oneyeard = date( "d", strtotime ("-31 year", strtotime($today ) )) ;

?>
</head>
<body leftmargin="0" rightmargin="0" topmargin="0">
<form name="general">
<table width="100%" border="0" cellspacing="1">
<th colspan="2"><?=$strBasicParam?></th>
	<tr>
		<td width="50%" align="right" nowrap><?=$strSatellite?></td>
		<td width="50%" align="center">
			<select name="SATELITE" onChange="displaySatellite(this.selectedIndex);">
			<option selected value=""></option>
			<option value="CB2">CBERS 2</option>
<!-- CBERS2B: comentar a seguinte opcao para desabilitar o suporte ao satelite -->
			<option value="CB2B">CBERS 2B</option>
			<option value="L1">Landsat 1</option>
			<option value="L2">Landsat 2</option>
			<option value="L3">Landsat 3</option>
			<option value="L5">Landsat 5</option> 
			<option value="L7">Landsat 7</option>
      <option value="L8">Landsat 8</option>
			<option value="T1">Terra 1</option> 
			<option value="A1">Aqua 1</option>  
			<option value="P6">ResourceSat-1</option>  
			<option value="GLS">GLS-Landsat</option>
			<option value="DMC">UK-DMC2</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="50%" align="right" nowrap><?=$strInstrument?></td>
		<td width="50%" align="center">
			<select name="SENSOR">
			<option value=""></option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" ><?=$strTimeInterval?></td>
		<td width="50%" align="center" nowrap><input type="checkbox" name="TIMEINTERVAL"> <?=$strTimeSeasonal?></td>
	</tr>
	<tr>
		<td width="50%" align="right" nowrap><?=$strInitial?></td>
		<td width="50%" align="center"nowrap>
		<input name="IDATED" value="<?=$day_beginning; ?>" size="2"> /
		<input name="IDATEM" value="<?=$month_beginning; ?>" size="2"> /
		<input name="IDATEY" value="<?=$year_beginning; ?>" size="4">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap><?=$strFinal?></td>
		<td align="center" nowrap>
		<input name="FDATED" value="<?=$todayd; ?>" size="2"> /
		<input name="FDATEM" value="<?=$todaym; ?>" size="2"> /
		<input name="FDATEY" value="<?=$todayy; ?>" size="4">
		</td>
	</tr>
	<tr><td colspan="2" align="center" ><?=$strCloudCover?></td></tr>
	<tr>
		<td width="50%" align="center" nowrap><b>Q1</b>  
			<select name="Q1">
			<option value="" selected ></option> 
			<option value="0">0%</option>
			<option value="10">10%</option>
			<option value="20">20%</option>
			<option value="30">30%</option>
			<option value="40">40%</option>
			<option value="50">50%</option>
			<option value="60">60%</option>
			<option value="70">70%</option>
			<option value="80">80%</option>
			<option value="90">90%</option>
			</select>
		</td>
		<td width="50%" align="center" nowrap><b>Q2</b> 
			<select name="Q2">
			<option value="" selected ></option> 
			<option value="0">0%</option>
			<option value="10">10%</option>
			<option value="20">20%</option>
			<option value="30">30%</option>
			<option value="40">40%</option>
			<option value="50">50% </option>
			<option value="60">60%</option>
			<option value="70">70%</option>
			<option value="80">80%</option>
			<option value="90">90%</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center" nowrap><b>Q3</b> 
			<select name="Q3">
			<option value="" selected></option>
			<option value="0">0%</option>
			<option value="10">10%</option>
			<option value="20">20%</option>
			<option value="30">30%</option>
			<option value="40">40%</option>
			<option value="50">50% </option>
			<option value="60">60%</option>
			<option value="70">70%</option>
			<option value="80">80%</option>
			<option value="90">90%</option>
			</select>
		</td>
		<td width="50%" align="center" nowrap><b>Q4</b> 
			<select name="Q4">
			<option value="" selected ></option>
			<option value="0">0%</option>
			<option value="10">10%</option>
			<option value="20">20%</option>
			<option value="30">30%</option>
			<option value="40">40%</option>
			<option value="50">50% </option>
			<option value="60">60%</option>
			<option value="70">70%</option>
			<option value="80">80%</option>
			<option value="90">90%</option>
			</select>
		</td>
	</tr>
    <tr>
      <td align="center"><?=$strQuickLook?></td>
	  <td nowrap >
	  	<INPUT type=RADIO name="quicklook" value="S" CHECKED ><?=$strSmall?>
		<INPUT type=RADIO name="quicklook" value="B"><?=$strBig?>
		</td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="1">
<th align="center" colspan="4"><?=$strMosaic?></th>
    <tr>
      <td align=center>
			<?=$strDate?> : &nbsp;
			<input name="MDATED" value="<?=//$oneyeard?>" size="1"> /
		  <input name="MDATEM" value="<?=//$oneyearm?>" size="1"> /
		  <input name="MDATEY" value="<?=//$todayy?>" size="2">
      &nbsp; <?=$strOr?> &nbsp; <?=$strPath?> : &nbsp; 
			<input name="MPATH" value="" size=7>
			</td>	   
   </tr>
   <tr>
     <td align="center" colspan="4">
     <input type="button" value="<?=$strExecute?>" onClick="JavaScript:goOn(5)"></td>
   </tr>
</table>
<table width="100%" border="0" cellspacing="1">
<th><?=$strCountry?></th><th><?=$strCity?></th><th><?=$strState?></th>
    <tr>
       <td width="33%" align="left" nowrap>
	  <select name="PAIS" onChange="displayStates(this.selectedIndex);">
        <option value="*"></option>
        <option value="ARGENTINA">ARGENTINA</option>
        <option value="BOLIVIA">BOLIVIA</option>
        <option value="BRASIL">BRASIL</option>
        <option value="CHILE">CHILE</option>
        <option value="COLOMBIA">COLOMBIA</option>
        <option value="ECUADOR">ECUADOR</option>
        <option value="FRENCH GUIANA">FRENCH GUIANA</option>
        <option value="GUYANA">GUYANA</option>      
        <option value="PARAGUAY">PARAGUAY</option>
        <option value="PERU">PERU</option>
        <option value="SURINAME">SURINAME</option>
        <option value="URUGUAY">URUGUAY</option>   
        <option value="VENEZUELA">VENEZUELA</option>
	<option value="ALGERIA">ALGERIA</option>
	<option value="ANGOLA">ANGOLA</option>
	<option value="BENIN">BENIN</option>
	<option value="BOTSWANA">BOTSWANA</option>
	<option value="BURKINA FASO">BURKINA FASO</option>
	<option value="BURUNDI">BURUNDI</option>
	<option value="CAMEROON">CAMEROON</option>
	<option value="CENT AF REP">CENT AF REP</option>
	<option value="CHAD">CHAD</option>
	<option value="CONGO">CONGO</option>
	<option value="DJIBOTI">DJIBOUTI</option>
	<option value="EGYPT">EGYPT</option>
	<option value="EQ GUINEA">EQ GUINEA</option>
	<option value="ERITREA">ERITREA</option>
	<option value="ETHIOPIA">ETHIOPIA</option>
	<option value="GABON">GABON</option>
	<option value="GAMBIA">GAMBIA</option>
	<option value="GHANA">GHANA</option>
	<option value="GUINEA">GUINEA</option>
	<option value="GUINEABISSAU">GUINEABISSAU</option>
	<option value="IVORY COAST">IVORY COAST</option>
	<option value="KENYA">KENYA</option>
	<option value="LESOTHO">LESOTHO</option>
	<option value="LIBERIA">LIBERIA</option>
	<option value="LIBYA">LIBYA</option>
	<option value="MADAGASCAR">MADAGASCAR</option>
	<option value="MALAWI">MALAWI</option>
	<option value="MALI">MALI</option>
	<option value="MAURITANIA">MAURITANIA</option>
	<option value="MOROCCO">MOROCCO</option>
	<option value="MOZAMBIQUE">MOZAMBIQUE</option>
	<option value="NAMIBIA">NAMIBIA</option>
	<option value="NIGER">NIGER</option>
	<option value="NIGERIA">NIGERIA</option>
	<option value="RWANDA"> RWANDA</option>
	<option value="SENEGAL">SENEGAL</option>
	<option value="SIERRA LEONE">SIERRA LEONE</option>
	<option value="SOMALIA">SOMALIA</option>
	<option value="SOUTH AFRICA">SOUTH AFRICA</option>
	<option value="SUDAN">SUDAN</option>
	<option value="SWAZILAND">SWAZILAND</option>
	<option value="TANZANIA">TANZANIA</option>
	<option value="TOGO">TOGO</option>
	<option value="TUNISIA">TUNISIA</option>
	<option value="UGANDA">UGANDA</option>
	<option value="W SAHARA">W SAHARA</option>
	<option value="ZAIRE">ZAIRE</option>
	<option value="ZAMBIA">ZAMBIA</option>
	<option value="ZIMBABWE">ZIMBABWE</option>
		</select> </td>
    
      <td width="33%" align="center" nowrap><input size=14 type="text" name="MUNICIPIO"></td> 

<!--      <td width=33% align="center" nowrap> 
	      <select name="ESTADO">
        <option value="*"></option>
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
      </select> </td>  -->
      
      <td width="33%" align="center" nowrap>
			<select name="ESTADO">
			<option value="*"></option>
			</select>
	  	</td> 
  
    </tr>
    <tr>
      <td align="center" colspan="3">
      <input type="button" value="<?=$strExecute?>" onClick="JavaScript:goOn(4)"></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="1">
<th><?=$strPath?></th><th><?=$strRow?></th>
	<tr>
		<td align="center" nowrap><?=$strInitial?>
		<input type="text" name="ORBITAI" size="2">&nbsp;<?=$strFinal?> 
		<input type="text" name="ORBITAF" size="2">  
		</td>
		<td align="center" nowrap><?=$strInitial?>
		<input type="text" name="PONTOI" size="2">&nbsp;<?=$strFinal?>
		<input type="text" name="PONTOF" size="2">  
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
		<input type="button" value="<?=$strExecute?>" name="ORBPON" onClick="goOn(1)"></td>
	</tr>
</table>
<table border="0" width="100%" cellspacing="1">
<th colspan="2"><?=$strByRegion?></th>
    <tr>
      <td align="center" colspan="2"><?=$strNorth?> <input type="text" size="6" name="LAT2" value="10."></td>
    </tr>
    <tr>
      <td align="center"><?=$strWest?> <input type="text" size="6" name="LON1" value="-90."></td>
      <td align="center"><?=$strEast?>  <input type="text" size="6" name="LON2" value="-30."></td>
    </tr>
    <tr>
      <td align="center" colspan="2"><?=$strSouth?> <input type="text" size="6" name="LAT1" value="-40."></td>
    </tr>
    <tr>
      <td align="center" colspan="2">
      <input type="button" value="<?=$strExecute?>" onClick="JavaScript:goOn(2)"></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="1">
<th colspan="2"><?=$strInterface?></th>
    <tr>
      <td align="center"><b>Lat <input type="text" size="6" name="LAT" value="-17."></td>
      <td align="center"><b>Lon <input type="text" size="6" name="LON" value="-48."></td>
    </tr>
    <tr>
      <td align="center" colspan="2">
      <input type="button" value="<?=$strNavigate?>" onClick="JavaScript:goOn(3)"></td>
    </tr>
	<input type="hidden" name="TAM" value="P">
	<input type="hidden" name="IMA" value="esad">
	<input type="hidden" name="TEM" value="cenas">
</table>
</form>
<script>
displaySatellite(0);
</script>
</body>
</html>
