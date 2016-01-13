<?php
include_once ("gralhaFunctions.php");
include_once ("globalsFunctions.php");
include_once ("globals.php");
include_once ("drdFunctions.php");
//Verifica se o arquivo tiff existe no disco.

function decodeTiff($filename,&$satellite,&$number,&$instrument,&$channel,&$type,&$date)
{
	if ($GLOBALS["stationDebug"])
		echo "decodeTiff - filename = $filename <br>\n";
	$fname=str_replace(".","_",$filename);
	$result=explode("_",$fname);

	$satellite=$result[0];
	$number=$result[1];
	$instrument=$result[2];
	$date=$result[3];

	if(substr($result[2],0,3)=="CCD")
	{
    	$channel=substr($result[2],3,1);
    	$instrument=substr($result[2],0,3);
    	$type=substr($result[2],4);
	}
	if ($GLOBALS["stationDebug"])
		echo "**-- decodeTiff - $satellite,$number,$instrument,$channel,$type,$date <br>\n";
}

function findTiffinDisk ($tiff)
{ 
	decodeTiff($tiff,$satellite,$number,$instrument,$channel,$type,$date);

	$dname = substr("$date",0,4)."_".substr("$date",4,2);

	$path = getTiffDir($satellite.$number).$dname;

	$com= "find $path -name $tiff";
	if ($GLOBALS["stationDebug"])
		echo "findTiffinDisk - command = $com <br>\n";
	$fullname =  shell_exec($com);
	$fullname = substr($fullname,0,strlen($fullname)-1);
	if ($GLOBALS["stationDebug"])
		echo "findTiffinDisk - name = $fullname name size = ".strlen($fullname)."<br>\n";
	return $fullname;
}

function findTiffinDiskFromGralha ($gralha,$restoration,$bands="")
{ 
  if ($GLOBALS["stationDebug"]) echo "\n ====> ** teste TiffFunctions:findTiffinDiskFromGralha =====> gralha = $gralha \n";
  
	if($restoration == "P6" or $restoration == "GLS" or $restoration == "UKDMC") // It concerns IRS-P6 satellite and GLS programm (Landsat 5 and 7 satellites) - anomalous proceeding
	{
	 $fullname = Array ();
	 if($restoration == "GLS")
	 {
	  $fullname[0] = $gralha;
	  print_r($fullname);
	  return $fullname;
	 }	  
	 
	 
	 
	 $result=explode('_',strtoupper($gralha));
	 $satellite=$result[0];
	 
	echo "\n\nP6 result = \n";	 
	print_r($result);
	echo "\n\n";
	 
	 
	 if ( $satellite == "AQUA"  or $satellite == "TERRA"  )
	 {
	 	 
		 $numero=$result[1];
		 $sensor=$result[2];
		 $data=$result[3];
		 $hora=$result[4];
	 
	 	 
	 	 if ($GLOBALS["stationDebug"])
		 {
			 echo "\n É AQUA OU TERRA \n\n";
			 print_r($result);
			 echo "\n\n";
		 }
		 

		 $fullname = Array ();
         $fullname[0] = $gralha;
		 print_r($fullname);
		 return $fullname;
		 
	 
	 }
	 
	 
	 echo "\n\nP6 gralha = $gralha\n";
	 
	 
	 include_once ("listFiles.php"); 
	 
	 $tiffs = listFiles($gralha,"0");
//	 print_r ($tiffs);
	 $fullname = Array (); 
	 $index = 0;
	 foreach($tiffs as $file)
	 {
	  $fullname[$index] = "$gralha/$file";
	  $index ++;
	 } 
		 
	 
	 echo "\n\nP6 gralha = $gralha\n";
	 
	 return $fullname;
	
	}
	
	
	
	
	//decodeGralha($gralha,$satellite,$number,$instrument,$channel_gralha,$type,$date,$path,$row); 
	decodeGralha2($gralha,$satellite,$number,$instrument,$channel_gralha,$type,$date,$path,$row,$hora,$quadrante,$areaorbita, $areaponto); 



	if ($GLOBALS["stationDebug"])
	{
		echo "\n***###\n$gralha";
		echo "\ninstrument =  $instrument\n";
		echo "\nchannel_gralha =  $channel_gralha\n";
		echo "\ntype=  $type\n";
		echo "\ndate =  $date\n\n";
		echo "\nhora =  $hora\n\n";
		echo "\n\n";
	}
	
#
# Gralha CCD1XS provides Band-2, Band-3 and Band-4 Tiffs
# Gralha CCD2XS provides Band-1, Band-3 Tiffs
# Gralha CCD2PAN provides Band-5 (Pan) Tiff
#
	
  if($type == "PAN") $gralha = str_replace("CCD2PAN","CCD1XS",$gralha);
	else if($channel_gralha == "2") $gralha = str_replace("CCD2XS","CCD1XS",$gralha);
	
	if ($GLOBALS["stationDebug"])
  echo "\n ====> TiffFunctions:findTiffinDiskFromGralha - Gralha = $gralha Satellite = $satellite Number = $number Instrument = $instrument Channel_gralha = $channel_gralha Type = $type Date = $date Path = $path Row = $row  \n";
	
	$dbcat = $GLOBALS["dbcatalog"];
	switch ($satellite)
  {
    case "CBERS":
     $sat_prefix = "Cbers";
     break;

    case "AQUA":
     $sat_prefix = "Modis"; 
     break;

     case "TERRA":
     $sat_prefix = "Modis";
     break;

     case "LANDSAT":
      $sat_prefix = "Landsat"; 
      break;
	  
	  
	  case "NPP":
	  case "SNPP":
	  case "S-NPP":
	      $sat_prefix = "Npp"; 
		break;  
	  
	  case "UKDMC":
	  case "UKDMC2":
	  case "UK-DMC2":
		  $sat_prefix = "UKDMC"; 
		  break;
	  
	  
	  
	  case "RS2":
	  case "RES2":
	  case "RESOURCESAT2":
	  case "RESOURCESAT-2":
		  $sat_prefix = "RES2"; 
		  break;
	  
	  
	  case "CB2":
	  case "CBERS2":
	  case "CBERS-2":
		  $sat_prefix = "Cbers"; 
		  break;
		  
	  case "CB4":
	  case "CB04":
	  case "CBERS4":
	  case "CBERS-4":
		  $sat_prefix = "Cbers"; 
		  break;
		  

     case "L8":
     case "LANDSAT8":
     case "LANDSAT-8":
      $sat_prefix = "Landsat"; 
      break;
	  
	  
	  
	  case "RE1":
	  case "RE2":
	  case "RE3":
	  case "RE4":
	  case "RE5":
	  case "RAPIDEYE":
	  case "RAPIDEYE1":
	  case "RAPIDEYE2":
	  case "RAPIDEYE3":
	  case "RAPIDEYE4":
	  case "RAPIDEYE5":
      $sat_prefix = "Rapideye"; 
	  break;
	  
	  
  };
	
	
	
	if ($sat_prefix != "Landsat" ) 
	{     
	
		if ($GLOBALS["stationDebug"]) echo "BBB -> findTiffinDiskfromGralha - Antes do Teste 9999 <br>\n";
		
		if ( ($sat_prefix != "Modis"  and $sat_prefix != "Npp" ) and ( strlen($gralha) >= 30 ))
		{
	
			if ($GLOBALS["stationDebug"]) echo "BBB -> findTiffinDiskfromGralha - Depois do Teste 9999 -- AQUA e TERRA Antigo <br>\n";
	
	
		
			 $sql = "SELECT DRD FROM $sat_prefix" . "Scene WHERE GRALHA = '$gralha'";
			 
			 if ($GLOBALS["stationDebug"]) echo "QQQQQ -> findTiffinDiskfromGralha - sql = $sql <br>\n";
			 $dbcat->query($sql) or $dbcat->error ($sql);
			 $row_array = $dbcat->fetchRow();
		   $drdName = $row_array[DRD];
			 decodeDRD($drdName,$satellite,$number,$instrument,$year,$month,$day,$hour,$minute,$second,$channel);
			 
			 if ($GLOBALS["stationDebug"])
		   echo "\n ====> MMMM TiffFunctions:findTiffinDiskFromGralha - DRDname = $drdName Satellite = $satellite Number = $number Instrument = $instrument Year = $year Month = $month Day = $day Hour = $hour Minute = $minute Second = $second Channel = $channel  \n";
				 
			 $date = sprintf("%4d%02d%02d.%02d%02d%02d",$year,$month,$day,$hour,$minute,$second);
		}
	}
	
	
	
	$dname = substr("$date",0,4)."_".substr("$date",4,2);
	$dir = getTiffDir($satellite.$number).$dname;
	if ($sat_prefix == "Landsat") $date = $date . "*";
	$dir = $dir."/".$satellite.$number."_".$instrument."_".$date."/";
	if($restoration == 1) $restor = "_rest";      
	if ($sat_prefix !== "Modis") $dir = $dir . $path . "_$row" . "_0" . $restor . "/"; // Modis PATH and ROW have formats not due to 
                                                                                     // other satellites formats 
                                                                                     // (e.g. LANDSAT, CBERS : path = nnn and row = nnn
    
	                                                                                 // MODIS : path = vn or vnn and row = hn or hnn 
      
	                                                                               // where "v" and "h" are tags for vetical (path)

	if ($GLOBALS["stationDebug"]) echo "\n****************** <br>\n";
	if ($GLOBALS["stationDebug"]) echo "WWW** -> findTiffinDiskfromGralha - dir = $dir <br>\n";


//	echo "\n ==============> dir = $dir \n";

// Lets find the generic name for similar CCD gralhas (CCD1XS,CCD2XS,CCD2PAN) CBERS_2_CCD1XS_20040130_153_126.h5
	
	 $target = $gralha;

//	echo " target = $gralha \n ";  

	if ($instrument=="CCD")
	{
		$pieces = explode ("_",$gralha);
		if(isset($bands) and is_numeric($bands)) $channel_gralha = $bands; // whish to verify the existence of specific band Tiffs (e.g. CCD1 or CCD2 Tiffs)
		else 
		{
		 $channel_gralha = "*" ; 
		 $type = "*";
		}
		$target = $pieces[0]."_".$pieces[1]."_CCD".$channel_gralha.$type."_". $pieces[3]."_".$pieces[4]."_".$pieces[5]; 
	}
	else if($instrument == "ETM") $target = str_replace("ETMXS_","ETM*_",$target); // For Landsat-7 ETM : just to include the PAN band (ETMPAN)

//echo " p0 = $pieces[0] p1 = $pieces[1] p3 = $pieces[3] p4 = $pieces[4] p5 = $pieces[5] \n";
	                                                           
  if ($sat_prefix == "Modis" ) $tiff = basename ($target,".h5") . "*.tif.zip";
	else $tiff = basename ($target,".h5")."_L?_BAND*.tif.zip"; // Added : 1) Level indicator (L2, by default)	                                                           //         2) Suffix ".zip"    
	
		if ($GLOBALS["stationDebug"]) echo "\n\nSatellite : $satellite";
		if ($GLOBALS["stationDebug"]) echo "\nInstrument : $instrument";
		if ($GLOBALS["stationDebug"]) echo "\ntiff : $tiff \n\n";
	
	   
	   
	   
	   
	$com= "find $dir -name $tiff";
	
	if ($GLOBALS["stationDebug"])
	{
		echo "\n\nAQUA e TERRA Normal";
		echo "\nsat_prefix =  $sat_prefix\n";
		echo "\ndir =  $dir\n";
		echo "\niff =  $tiff\n";
		echo "\ncom =  $com\n\n";
	}
		
		

	
	    
	// AQUA e TERRA   
	// ============                                                                                                    
	if ( ( $satellite == "AQUA"  or $satellite == "TERRA" ) and strtoupper($instrument) == "MODIS" and ( strlen($gralha) == 28 or strlen($gralha) == 29 ) )
	{
		//$com= "find $dir -name $tiff";
		
		$ano = substr($date,0,4);
		$mes = substr($date,4,2);
		$dia = substr($date,6,2);
		
		$hra= substr($hora,0,2);
		$min= substr($hora,2,2);
		$seg= substr($hora,4,2);
		
		$dir_base='/Level-2/';
		$dir_satelite="$satellite$number";
		$dir_periodo="$ano" . '_' . "$mes";
		$dir_arquivo="$satellite" . '_' . "$instrument" . '.' . "$ano" . '_' . "$mes" . '_' . "$dia" . '.' . "$hra" . '_' . "$min" . '_' . "$seg";
		
		$aqua_file='AQUA.MYDcrefl_TrueColor.' . $ano . '_' . $mes . '_'  . $dia . '.' . $hra . '_' . $min . '_' . $seg . '.tif.zip';
		$terra_file='TERRA.MODcrefl_TrueColor.' . $ano . '_' . $mes . '_' . $dia . '.' . $hra . '_' . $min . '_' . $seg . '.tif.zip';
		
		$dir=$dir_base . $dir_satelite . '/' . $dir_periodo . '/' . $dir_arquivo .'/';
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nAQUA e TERRA novos";
			echo "\nData : $ano  $mes  $dia   -   Horario : $hra  $min  $seg";
			echo "\naqua_file =  $aqua_file\n";
			echo "\nterra_file =  $terra_file\n";
			echo "\ndir =  $dir\n\n";
		}
		
		
		if ( $satellite == "AQUA")
		{			
			$tiff=$aqua_file;
		}
		else
		{
			$tiff=$terra_file;
		}
		
		$com= "find $dir -name $tiff";
		
	}
	// FIM AQUA e TERRA   
	// ================                                                                                                    


		
	if ($GLOBALS["stationDebug"])
	{
		echo "\nsatellite : $satellite";
		echo "\ninstrument =  $instrument\n\n";
	}


	
	    
	// S-NPP   
	// ============                                                                                                    
	if ( ( $satellite == "NPP"  or $satellite == "SNPP"  or $satellite == "S-NPP" ) and strtoupper($instrument) == "VIIRS" )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de NPP SNPP";
		}


		
		$ano = substr($date,0,4);
		$mes = substr($date,4,2);
		$dia = substr($date,6,2);
		
		$hra= substr($hora,0,2);
		$min= substr($hora,2,2);
		$seg= substr($hora,4,2);
						
		$data_amd="$ano-$mes-$dia";
		$comando="echo $(date +'%j' -d $data_amd)";		
		
		$ano_juliano=substr($ano,2,2);		
		$dia_juliano=shell_exec($comando);
		
		$dia_juliano = substr($dia_juliano,0,strlen($dia_juliano)-1);	
		$dia_juliano = rtrim($dia_juliano);


		
		$dir_base='/L2_NPP/';
		$dir_periodo="$ano" . '_' . "$mes";
		$dir_arquivo="$satellite" . '_' . "$instrument" . '.' . "$ano" . '_' . "$mes" . '_' . "$dia" . '.' . "$hra" . '_' . "$min" . '_' . "$seg";
		
		//$npp_file='NPP_TCOLOR_SDR.' . "$ano_juliano" . "$dia_juliano"  . "$hra"  . "$min" .  "$seg" . '.tif.zip';		
		$npp_file='*TCOLOR*.tif.zip';		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/';
		
		$tiff="$npp_file";
		$com= "find $dir -name $tiff";

		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia   -   Horario : $hra  $min  $seg";
			echo "\nnpp_file =  $npp_file\n";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM S-NPP 
	// ================                                                                                                    






	
	    
	// UK-DMC2   
	// ============                                                                                                    
	if ( ( $satellite == "UKDMC"  or $satellite == "UKDMC2"  or $satellite == "UK-DMC2" ) and strtoupper($instrument) == "SLIM" )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de UK-DMC2 - **********";
			echo "\n\channel_gralha = $channel_gralha **********";
		}


		
		$ano = substr($channel_gralha,0,4);
		$mes = substr($channel_gralha,4,2);
		$dia = substr($channel_gralha,6,2);
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L2_UKDMC2/';
		$dir_periodo="$ano" . '_' . "$mes";		
		$dir_arquivo="$satellite" . '_' . "$instrument" . '_' . "$channel_gralha";
		$dir_coordenada="$type" . '_' . "$hora";
		
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' . $dir_coordenada .'/';
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n88888 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n77777777 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";
					
			
		}
		
		
		
		$tiff="$gralha";
		$com= "find $dir -name $tiff";

		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM UK-DMC2 
	// ================                                                                                                    





		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nRES2******************";
			echo "\n instrument =  $instrument\n\n";
			echo "\n type =  $type";
			echo "\n data =  $data";
			echo "\n hora =  $hora";
			echo "\n path =  $path";
			echo "\n row  =  $row\n\n";
		}




	
	
	echo "\n ** Antes do RS2 - LISS3\n\n";
	    
	// RESOURCESAT-2
	// =============
	if ( ( $satellite == "RES2"  or $satellite == "RS2"  or $satellite == "RESOURCESAT2" ) and ( strtoupper($instrument) == "LIS3"  ) )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de RESOURCESAT-2 - **********";
			echo "\n\channel_gralha = $channel_gralha **********";
		}

		$aux_instrument="";

		if ( $instrument == "LIS3") $aux_instrument="LISS3";
		if ( $instrument == "AWIF") $aux_instrument="AWIFS";
		
		
		$ano = substr($channel_gralha,0,4);
		$mes = substr($channel_gralha,4,2);
		$dia = substr($channel_gralha,6,2);
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L4_RESOURCESAT2/';
		$dir_periodo="$ano" . '_' . "$mes";		
		$dir_arquivo="$satellite" . '_' . "$aux_instrument" . '_' . "$channel_gralha";
		
		$dir_coordenada="$path" . '_' . "$row";
		
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' . $dir_coordenada .'/';
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n0101010101 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n1212121212 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";


			echo "\npath = $path\n";
			echo "row = $row\n\n";

					
			
		}
		
		
		
		//$com= "find $dir -name $tiff";



		$tiff="*.tif.zip";
		$arqtxt="*.txt";
		$arqmeta="*.meta";
		$arqgeom="*.geom";
		$arqqlgrd="*_GRD.png";
		
		$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqmeta\"  -o -name \"$arqgeom\"  -o -name \"$arqqlgrd\" ";


		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM RESOURCESAT-2 - LISS3
	// =========================











	
	echo "\n ** Antes do RS2 - AWIFS\n\n";
	    
	// RESOURCESAT-2
	// =============
	if ( ( $satellite == "RES2"  or $satellite == "RS2"  or $satellite == "RESOURCESAT2" ) and ( strtoupper($instrument) == "AWIF" ) )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de RESOURCESAT-2 - **********";
			echo "\n\nchannel_gralha = $channel_gralha **********";
		}

		$aux_instrument="";

		if ( $instrument == "LIS3") $aux_instrument="LISS3";
		if ( $instrument == "AWIF") $aux_instrument="AWIFS";
		
		
		$ano = substr($channel_gralha,0,4);
		$mes = substr($channel_gralha,4,2);
		$dia = substr($channel_gralha,6,2);
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L4_RESOURCESAT2/';
		$dir_periodo="$ano" . '_' . "$mes";		
		$dir_arquivo="$satellite" . '_' . "$aux_instrument" . '_' . "$channel_gralha";
		
		$dir_coordenada="$path" . '_' . "$row";
		
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' . $dir_coordenada .'/' . $quadrante . '/';
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n0101010101 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n1212121212 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";


			echo "\npath = $path\n";
			echo "row = $row\n\n";
			echo "Quadrante = $quadrante\n\n";
			

					
			
		}
		
		
		
		//$com= "find $dir -name $tiff";



		$tiff="*.tif.zip";
		$arqtxt="*.txt";
		$arqmeta="*.meta";
		$arqgeom="*.geom";
		$arqqlgrd="*_GRD.png";
		
		$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqmeta\"  -o -name \"$arqgeom\"  -o -name \"$arqqlgrd\" ";


		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM RESOURCESAT-2 - AWIFS
	// =========================








	
	
	
	


	
	echo "\n ** Antes do L8 - OLI\n\n";
	    
	// LANDSAT-8
	// =============
	if ( ( $satellite == "L8"  or $satellite == "LANDSAT8"  or $satellite == "LANDSAT-8" ) and ( strtoupper($instrument) == "OLI" ) )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de LANDSAT-8 - **********";
			echo "\n\nchannel_gralha = $channel_gralha **********";
		}

		$aux_instrument="";

		if ( $instrument == "OLI") $aux_instrument="OLI";
		
		
		$ano = substr($channel_gralha,0,4);
		$mes = substr($channel_gralha,4,2);
		$dia = substr($channel_gralha,6,2);
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L1_LANDSAT8/L1T/';
		$dir_periodo="$ano" . '_' . "$mes";		
		$dir_arquivo="$areaorbita";
		
		$dir_coordenada="$areaponto";
		
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' . $dir_coordenada .'/';
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n0101010101 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n1212121212 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";


			echo "\npath = $path\n";
			echo "row = $row\n\n";
			echo "Quadrante = $quadrante\n\n";
			

					
			
		}
		
		
		
		//$com= "find $dir -name $tiff";



		$tiff="*.TIF.zip";
		$arqtxt="*.txt";
		$arqtar="*.gz";
		$arqqlgrd="*_GRD.png";
		
		//$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqtar\"  -o -name \"$arqqlgrd\" ";
		$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqqlgrd\" ";


		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM LANDSAT-8 - OLI
	// =========================







	
	echo "\n ** Antes do CB4\n\n";
	    
	// CBERS-4
	// =============
	if ( $satellite == "CB4"  or $satellite == "CBERS4"  or $satellite == "CBERS-4" ) 
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de CBERS-4 - **********";
			echo "\n\nchannel_gralha = $channel_gralha **********";
		}

		$aux_instrument="";

		if ( $instrument == "WFI") $aux_instrument="WFI";
		
		
		$ano = substr($date,0,4);
		$mes = substr($date,4,2);
		$dia = substr($date,6,2);
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L2_CBERS4/';
		$dir_periodo="$ano" . '_' . "$mes";		
		$dir_arquivo="CBERS4_" . $instrument . '_' . $date . '.' . $hora;
		$dir_coordenada="$path" . '_' . "$row" . '_0';
		$dir_final='';
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' . $dir_coordenada .'/' . $dir_final;
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n0101010101 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n1212121212 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";


			echo "\npath = $path\n";
			echo "row = $row\n\n";
			echo "Quadrante = $quadrante\n\n";
			

					
			
		}
		
		
		
		//$com= "find $dir -name $tiff";



		$tiff="*.TIF.zip";
		$arqxml="*.XML";
		$arqqlgrd="*_GRD.png";
		
		//$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqtar\"  -o -name \"$arqqlgrd\" ";
		$com= "find $dir -name \"$tiff\" -o -name \"$arqxml\" -o -name \"$arqqlgrd\" ";


		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM CBERS-4 
	// =========================





	
	    







	
	    
	// RAPIDEYE   
	// ============                                                                                                    
	if ( ( $satellite == "RE1"  or $satellite == "RE2"  or $satellite == "RE3"   or $satellite == "RE4"   or $satellite == "RE5" ) and strtoupper($instrument) == "REIS" )
	{
		//$com= "find $dir -name $tiff";

		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nDentro de RAPIDEYE - **********";
			echo "\n\channel_gralha = $channel_gralha **********";
		}


		
		$ano = substr($channel_gralha,0,4);
		$mes = substr($channel_gralha,4,2);
		$dia = substr($channel_gralha,6,2);
		
		
		$hra= substr($type,0,2);
		$min= substr($type,2,2);
		$seg= substr($type,4,2);
						
		
		
		
		$data_amd="$ano-$mes-$dia";
		
		$dir_base='/L4_RAPIDEYE/' . $satellite . '/';
		$dir_periodo="$ano" . '_' . "$mes";				
		$dir_arquivo="$satellite" . '_' . "$instrument" . '.' . "$ano" . '_' . "$mes" . '_' . "$dia" . '.' . "$hra" . '_' . "$min" . '_' . "$seg" . '.' . $hora;
		
		
		$dir=$dir_base . $dir_periodo . '/' . $dir_arquivo .'/' ;
		
		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\n88888 **********\n";
			echo "ano = $ano\n";
			echo "mes = $mes\n";
			echo "dia = $dia\n\n";
			echo "dir_periodo = $dir_periodo\n";
			echo "dir_arquivo = $dir_arquivo\n";
			echo "dir_coordenada = $dir_coordenada\n\n";
			
			echo "\n\n77777777 **********\n";
			echo "type = $type\n";
			echo "date = $date\n";
			echo "hora = $hora\n";
					
			
		}
		
		
		
		$tiff="*.tif.zip";
		$arqtxt="*.txt";
		$arqxml="*.xml";
		
		$com= "find $dir -name \"$tiff\" -o -name \"$arqtxt\" -o -name \"$arqxml\" ";

		
		if ($GLOBALS["stationDebug"])
		{
			echo "\n\nData : $ano  $mes  $dia ";
			echo "\ndir =  $dir\n\n";
			echo "\ncom =  $com\n\n";
		}
						
		
		
	}
	// FIM RAPIDEYE 
	// ================                                                                                                    











	if ($GLOBALS["stationDebug"])
		echo "findTiffinDiskFromGralha - command = $com <br>\n";
	$output =  shell_exec($com);
	$output = substr($output,0,strlen($output)-1);
	
	$fullname = explode("\n",$output);

// No file was found
 
	if (strlen($output) <= 1) 
	{  
		if ($GLOBALS["stationDebug"])
			echo "findTiffinDiskFromGralha - No file found <br>\n";
		$fullname = Array ();	
		return $fullname;
	}
	if ($instrument == "CCD" and is_numeric($bands)) // In case of an specific CCD (CCD1XS, CCD2XS or CCD2PAN), if we find that some Tiff band is lacking (e.g. PAN, BAND 1, BAND 2 etc.) 
	{                                                // i.e. the number of Tiff bands found for this Gralha is less than 3 (2 in case of CCD2XS or 1 in case of CCD2PAN), we have to produce all Tiffs band from this Grallha again !!! 
	  if($bands == 1) $limit = 3; 
		else 
		 if($type == "PAN") $limit = 1;
		 else $limit = 2;
		 
	  if( count($fullname) < $limit)
	  {
		 if ($GLOBALS["stationDebug"])
			 echo "findTiffinDiskFromGralha - Not all Tiff bands found <br>\n";
		 $fullname = Array ();	
		 return $fullname;
		}
	}
	
// Tiffs were found 
	
	if ($GLOBALS["stationDebug"])
	{
		echo "findTiffinDiskFromGralha - Files found : ".count($fullname)." (".strlen($output).")<br>\n";
		print_r($fullname);
		foreach($fullname as $file)
			echo "$file <br>\n";
	}

	return $fullname;
}
?>
