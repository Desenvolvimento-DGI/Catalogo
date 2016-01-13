<?php
//Verifica se o arquivo gralha existe no disco.
include_once ("globalsFunctions.php"); 

function decodeGralha($filename,&$satellite,&$number,&$instrument,&$channel,&$type,&$date,&$path,&$row)
{
	if ($GLOBALS["stationDebug"])
		echo "decodeGralha - filename = $filename <br>\n";
	$fname=str_replace(".","_",$filename);
	$result=explode("_",$fname);

	$satellite=$result[0];
	$number=$result[1];
	$instrument=$result[2];
	$date=$result[3];
	$path=$result[4];
	$row=$result[5];

  
  if(substr($result[2],0,3)=="HRC") // HRC has special treatment due its grid.
  {
    $path=$result[4]."_".$result[5];
    $row=$result[6]."_".$result[7];    
  }
	if(substr($result[2],0,3)=="CCD")
	{
    	$channel=substr($result[2],3,1);
    	$instrument=substr($result[2],0,3);
    	$type=substr($result[2],4);
	}
	if(substr($result[2],0,3)=="ETM")
	{
    	$channel=0;
    	$instrument=substr($result[2],0,3);
    	$type=substr($result[2],3);
	}
/*  if(substr($result[0],0,5)=="CREFL")
	{
	 $number="1";
	 $instrument="MODIS";
   $date1 = substr($result[1],4,3);
   $date2 = substr($result[1],0,4);
   $date3 = mktime(0,0,0,1,$date1,$date2,-1); // convertion to standard format YYYYMMDD
   $date=date("Ymd",$date3);                 // on these two command lines.
   $path=substr($result[4],0);
	 $row=substr($result[3],0);
   $channel=0;
   $type="";
	} */
	if($instrument == "MODIS")
	{
	 $path=$result[5];
	 $row=$result[6];
	}
	
	
	if ($GLOBALS["stationDebug"]) echo "decodeGralha - parameters :  $satellite,$number,$instrument,$channel,$type,$date,$path,$row <br>\n";
}





function decodeGralha2($filename,&$satellite,&$number,&$instrument,&$channel,&$type,&$date,&$path,&$row,&$hora)
{
	if ($GLOBALS["stationDebug"])
		echo "decodeGralha - filename = $filename <br>\n";
	$fname=str_replace(".","_",$filename);
	$result=explode("_",$fname);

	$satellite=$result[0];
	$number=$result[1];
	$instrument=$result[2];
	$date=$result[3];
	$path=$result[4];
	$row=$result[5];
	$hora=$result[4];

  
  if(substr($result[2],0,3)=="HRC") // HRC has special treatment due its grid.
  {
    $path=$result[4]."_".$result[5];
    $row=$result[6]."_".$result[7];    
  }
	if(substr($result[2],0,3)=="CCD")
	{
    	$channel=substr($result[2],3,1);
    	$instrument=substr($result[2],0,3);
    	$type=substr($result[2],4);
	}
	if(substr($result[2],0,3)=="ETM")
	{
    	$channel=0;
    	$instrument=substr($result[2],0,3);
    	$type=substr($result[2],3);
	}
/*  if(substr($result[0],0,5)=="CREFL")
	{
	 $number="1";
	 $instrument="MODIS";
   $date1 = substr($result[1],4,3);
   $date2 = substr($result[1],0,4);
   $date3 = mktime(0,0,0,1,$date1,$date2,-1); // convertion to standard format YYYYMMDD
   $date=date("Ymd",$date3);                 // on these two command lines.
   $path=substr($result[4],0);
	 $row=substr($result[3],0);
   $channel=0;
   $type="";
	} */
	if($instrument == "MODIS")
	{
	 $path=$result[5];
	 $row=$result[6];
	}
	
	
	
	if ( ( strtoupper($satellite) == "AQUA"  || strtoupper($satellite) == "TERRA" ) and ( strtoupper($instrument) == "MODIS" ) )
	{		
		$hora=$result[4];

		if ($GLOBALS["stationDebug"]) echo "%%%%% decodeGralha2 - parameters :  $satellite,$number,$instrument,$date,$hora <br>\n";		
	}
	
	
	
	
	if ( ( strtoupper($satellite) == "NPP"  or strtoupper($satellite) == "SNPP"  or strtoupper($satellite) == "S-NPP" ) and strtoupper($instrument) == "VIIRS"  )
	{		
		$hora=$result[4];

		if ($GLOBALS["stationDebug"]) echo "%%%%% decodeGralha2 - parameters :  $satellite,$number,$instrument,$date,$hora <br>\n";		
	}
	
	
	

	
	//if ($GLOBALS["stationDebug"]) echo "decodeGralha - parameters :  $satellite,$number,$instrument,$channel,$type,$date,$path,$row <br>\n";
}




function findGralhainDisk ($gralha)
{
	decodeGralha($gralha,$satellite,$number,$instrument,$channel,$type,$date,$path,$row);

  $dname = substr("$date",0,4)."_".substr("$date",4,2);

	$path = getGralhaDir ($satellite.$number).$dname;
  
  
	$com= "find $path -name $gralha";
	if ($GLOBALS["stationDebug"])
		echo "findGralhainDisk - command = $com <br>\n";

  $fullname =  shell_exec($com);
	$fullname = substr($fullname,0,strlen($fullname)-1);
	if ($GLOBALS["stationDebug"])
		echo "findGralhainDisk - name = $fullname name size = ".strlen($fullname)."<br>\n";
	return $fullname;
}

function findSimilarGralhainDisk ($gralha)
{
	decodeGralha($gralha,$satellite,$number,$instrument,$channel,$type,$date,$path,$row);

if ($GLOBALS["stationDebug"])
		echo "findSimilarGralhainDisk: satellite = $satellite <br>\n";

// Build path based on Date
//  if($satellite == "CREFL") 
  if($instrument == "MODIS") 
  {
   include_once ("globals.php");
   $dbcat = $GLOBALS["dbcatalog"];
	 $sql = "SELECT DRD FROM ModisScene WHERE Gralha='$gralha'";
	 $dbcat->query($sql) or $dbcat->error ($sql);
	 $row = $dbcat->fetchRow();
	 $drd = $row["DRD"];
	 $pieces = explode(".",$drd);
	 $satellite = $pieces[0];
	 if($satellite == "aquadb") $satellite = "AQUA";
	 else $satellite = "TERRA"; 
  }
	$dname = substr("$date",0,4)."_".substr("$date",4,2);
	$path = getGralhaDir ($satellite.$number).$dname;

// Lets find the generic name for similar CCD gralhas (CCD1XS,CCD2XS,CCD2PAN) CBERS_2_CCD1XS_20040130_153_126.h5
	$target = $gralha;
	if ($instrument=="CCD")
	{
		$pieces = explode ("_",$gralha);
		$target = $pieces[0]."_".$pieces[1]."_CCD*_".$pieces[3]."_".$pieces[4]."_".$pieces[5];
	}
  if ($instrument == "MODIS")
  {
   $target = "CREFL*.hdf" ;
   $path = $path . "/" . $drd;
  };
  if($instrument == "ETM")
	{
	 $pieces = explode ("_",$gralha);
	 $target = $pieces[0]."_".$pieces[1]."_ETM*_".$pieces[3]."_".$pieces[4]."_".$pieces[5]; 
	}          
	$com= "find $path -name $target";
	if ($GLOBALS["stationDebug"])
		echo "findSimilarGralhainDisk - command = $com <br>\n";
	$output =  shell_exec($com);
	$output = substr($output,0,strlen($output)-1);

// No file was found

	if (strlen($output) <= 1) 
	{
		if ($GLOBALS["stationDebug"])
			echo "findSimilarGralhainDisk - No file found <br>\n";
		$fullname = Array ();
		return $fullname;
	}
	$fullname = explode("\n",$output);
	if ($GLOBALS["stationDebug"])
	{
		echo "findSimilarGralhainDisk - Files found : ".count($fullname)." (".strlen($output).")<br>\n";
		foreach($fullname as $file)
			echo "$file <br>\n";
	}
	return $fullname;
}
?>
