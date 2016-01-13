<?php	
include_once ("basicModule.php");
include_once ("tiffFunctions.php");
include_once ("forceFilename.php"); 
include_once("globals.inc.php");


	class p2u extends basicModule { 

	var $userid;
	var $reqid;
	var $gralha;
	var $quicklook;
	var $sceneid;
	var $tiffs;
	var $ftpdir;
	var $link;
	var $numitem;
	var $restoration;  
	var $dbcad;

// Constructor may be called with a request (userid;reqid;sceneid;gralha) or Module Id as argument 
		function p2u ($arg) {
		
			basicModule::basicModule ($arg);


// Get userid, reqid and tiff from argument
			$result = explode (";",$this->argument);
			$this->userid = $result[0];
			$this->reqid = $result[1];
			$this->sceneid = $result[2];
			$this->gralha = $result[3];
			$this->numitem = $result[4];
			if (isset($result[5]))
			$this->restoration = $result[5];
			if (isset($result[6]))
			$this->quicklook = $result[6];
			$forcedname = forceFilename ($this->userid);
			$this->link = $GLOBALS["wwwserver"].$forcedname.$this->reqid;
			$this->ftpdir = $GLOBALS["ftpdir"].$forcedname.$this->reqid;
			$this->dbcad = $GLOBALS["dbusercat"];
// Check if this Tiff file is present in disk system
			$this->tiffs = findTiffinDiskFromGralha ($this->gralha,$this->restoration,"*"); 
			if ($GLOBALS["stationDebug"])
			{
				echo "p2u.p2u tiffs : <br>\n";
				print_r ($this->tiffs);
				echo "<br>\n";
			}
			
		}

		function getTiffs ()
		{
		 return $this->tiffs;
		}

		function tiffExists ()
		{
			if (count ($this->tiffs) > 0)
				return true;
			else
				return false;
		}

		function saveTiffforUser ($tiffFullName)
		{ 
			$ftptiff = $this->ftpdir."/".basename($tiffFullName);
//Zip Tiff files into user ftpdir
echo "** teste $tiffFullName";
//			$cmd = "ln -s /home/prodtmp1$tiffFullName $ftptiff";
			$cmd = "cp -r $tiffFullName $ftptiff";
			$res = exec($cmd);
//			$res = exec("zip -j $ftptiff.zip $tiffFullName");
//			copy ($tiffFullName,$ftptiff);
			if ($GLOBALS["stationDebug"])
				echo "p2u.saveTiffforUser $cmd <br>\n";
//				echo "p2u.saveTiffforUser copy $tiffFullName to  $ftptiff <br>\n";
		}

		function updateRequest ()
		{
// Update requestitem table with production date and status
			$now = date("Y-m-d H:i:s");
			$sql = "UPDATE RequestItem SET
			Prodate='$now',
			Status='F'
			WHERE ReqId=$this->reqid AND SceneId='$this->sceneid' AND NumItem='$this->numitem'"; 
			if ($GLOBALS["stationDebug"])
				echo "p2u.updateRequest sql= $sql <br>\n";
			$result = $this->dbcat->query($sql);
			
			if (!$result)
		  {
				$this->dbman->endModule ($this->moduleId,10,$this->dbcat->errorMessage());
				die ("\n ==========> Error updating Database ! \n");
			}
		
		}

		function sendErrorMail ($scene="") 
		{

// Email the file links to the user.
    		$message = "Operador,
 Houve erro no processamento do pedido $this->reqid do usuario $this->userid, cenas $scene.
 Não há registro de DRD no Banco.
  
Production Manager
";

			if ($GLOBALS["stationDebug"])
				echo "p2u.sendErrorMail message= $message <br>\n";  
			$status = true;
//      $email =  "produtos@dgi.inpe.br";
      $email = $GLOBALS["contactmail"];

			$status = mail($email,"Erro no pedido de imagens - $this->reqid",
			$message, "From: Atus - INPE <$GLOBALS[contactmail]> ");

		}

		function sendMail ()
		{
		
// Get the Language that lead this request, for due mail message language
     include ("globals.php");
     
     $dbcat = $GLOBALS["dbcatalog"];
     $sql = "SELECT Language from Request WHERE ReqId = '$this->reqid'";
     if (!$dbcat->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $dbcat->fetchRow()) $lang = $row['Language'];
			else  
				{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ("<br> ============> Error querying Data Base $dbcat->db_name ! <br>");
		   	}
     
	    if($lang == 'ES') $lang = "EN";
      require ("p2u_" . $lang . ".php");	
		
		
// Get email for this user
			$sql = "SELECT * FROM User WHERE userId='$this->userid'";
			if ($GLOBALS["stationDebug"])
				echo "p2u.sendMail sql= $sql <br>\n";
			if (!$this->dbcad->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $this->dbcad->fetchRow())
			{
				$fullname = $row["fullname"];
				$email = $row["email"];
				$this->dbcad->freeResult();
			}
			else
			{
				$this->dbman->endModule ($this->moduleId,10,$sql); 
				die ();
			}

// Email the file links to the user.
			$boundary='--' . md5( uniqid("myboundary") );
			$charset="iso-8859-9";
			$ctencoding="8bit";
			$message ="This is a multi-part message in MIME format.\n--$boundary\n"; 
			$message .= "Content-Type: text/plain; charset=$charset\n";
			$message .= "Content-Transfer-Encoding: $ctencoding\n\n";
    	$message .= "$strDear $fullname, " . "\n\n";

      if ($this->quicklook != "") // subscription request - Let's change the beginnig of message.
          $message .= $strQuick;
      else $message .= $strNoquick;
      $message .= $strRest1;
      
      foreach ($this->tiffs as $tiff)
      {
			 if(strstr($tiff,"CCD2") && strstr($tiff,"BAND3"))
			 continue; 
			 $message .= basename($tiff)."\n";
			};
			
			$message .= $strRest2;
			if(!(strpos($this->sceneid,"CB") === false)) $message .= $strRest3; // Spectral information for bands only por CBERS' scenes.
      
			if ($this->quicklook != "") 
			{
				if ($GLOBALS["stationDebug"])
				echo "p2u.sendMail quick look = $this->quicklook <br>\n";	
				$basename=basename($this->quicklook);
				$sep= chr(13).chr(10);
		
				$message .="--$boundary\nContent-type: image/jpeg;\n name=\"$basename\"\n";
				$message .="Content-Transfer-Encoding: base64\nContent-Disposition: inline;\n  filename=\"$basename\"\n";
				$linesz= filesize($this->quicklook)+1;
				$fp= fopen( $this->quicklook, 'r' );
				$content = chunk_split(base64_encode(fread($fp,$linesz)));
				fclose($fp);
				$message .= $sep.$content;
			}
			else if ($GLOBALS["stationDebug"])
				echo "p2u.sendMail message= $message <br>\n"; 
			$status = true;

			$header.="From: Atus - INPE <$GLOBALS[contactmail]> \n";
			$header.="Mime-Version: 1.0\nContent-Type: multipart/mixed;\n boundary=\"$boundary\"\n";
			$header.="Content-Transfer-Encoding: $ctencoding\nX-Mailer: Php/libMailv1.3\n";
			$status = mail($email,$strSubject . " - $this->reqid",$message, $header);

			if (!$status)
			{
				$this->dbman->endModule ($this->moduleId,3,"Error sending mail to ".$email);
				die ();
			}
			$this->dbman->endModule ($this->moduleId);
		}

		function sendIntermediateMail ()
		{
		
		// Get the Language that lead this request, for due mail message language
     include ("globals.php");
     
     $dbcat = $GLOBALS["dbcatalog"];
     $sql = "SELECT Language from Request WHERE ReqId = '$this->reqid'";
     if (!$dbcat->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $dbcat->fetchRow()) $lang = $row['Language'];
			else  
				{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ("<br> ============> Error querying Data Base $dbcat->db_name ! <br>");
		   	}
     
	    if($lang == "ES") $lang = "EN";
      require ("p2u_" . $lang . ".php");	
		
		
// Get email for this user
			$sql = "SELECT * FROM User WHERE userId='$this->userid'";
			if ($GLOBALS["stationDebug"])
				echo "p2u.sendIntermediateMail sql= $sql <br>\n";
			if (!$this->dbcad->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $this->dbcad->fetchRow())
			{
				$fullname = $row["fullname"];
				$email = $row["email"];
				$this->dbcad->freeResult();
			}
			else
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}

// Email the file links to the user.
    	$message = "$strDear $fullname,\n $strInter1 ";

			foreach ($this->tiffs as $tiff)
			{
			 	if(strstr($tiff,"CCD2") && strstr($tiff,"BAND3"))
				continue;
			  $message .= basename($tiff)."\n";
			};

			$message .= $strInter2;


			if ($GLOBALS["stationDebug"])
				echo "p2u.sendIntermediateMail message= $message <br>\n";
			$status = true;

			$status = mail($email,"$strSubject - $this->reqid",
			$message, "From: Atus - INPE <$GLOBALS[contactmail]> ");

			if (!$status)
			{
				$this->dbman->endModule ($this->moduleId,3,"Error sending mail to ".$email);
				die ();
			}
			$this->dbman->endModule ($this->moduleId);
		}


		function sendFirstMail ()
		{
// Get email for this user
			$sql = "SELECT * FROM User WHERE userId='$this->userid'";
			if ($GLOBALS["stationDebug"])
				echo "p2u.sendFirstMail sql= $sql <br>\n";
			if (!$this->dbcad->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $this->dbcad->fetchRow())
			{
				$fullname = $row["fullname"];
				$email = $row["email"];
				$this->dbcad->freeResult();
			}
			else
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}

// Email the file links to the user.

// Get the Language that lead this request, for due mail message language
     include ("globals.php");
     
     $dbcat = $GLOBALS["dbcatalog"];
     $sql = "SELECT Language from Request WHERE ReqId = '$this->reqid'";
     if (!$dbcat->query($sql))
			{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ();
			}
			if ($row = $dbcat->fetchRow()) $lang = $row['Language'];
			else  
				{
				$this->dbman->endModule ($this->moduleId,10,$sql);
				die ("<br> ============> Error querying Data Base $dbcat->db_name ! <br>");
		   	}
     
      if($lang == "ES") $lang = "EN";
      require ("p2u_" . $lang . ".php");	
		
    	$message = $strFirstMail;

			if ($GLOBALS["stationDebug"])
				echo "p2u.sendFirstMail recipient = $email message  = $message <br>\n";
			$status = true;

			$status = mail($email,"$strSubject - $this->reqid",
			$message, "From: Atus - INPE <$GLOBALS[contactmail]> ");

			if (!$status)
			{ 
				$this->dbman->endModule ($this->moduleId,3,"Error sending mail to ".$email);
				die ();
			}
		}

		function run () {
// Create area where files will be placed if it is the first item;
			if (!is_dir($this->ftpdir))
			{ 
				mkdir ($this->ftpdir);
// Create .htacess for each user ftpdir
				$fd = fopen("$this->ftpdir/.htaccess","w") or die("Cannot open file $this->ftpdir\n");
				fwrite($fd,"Options Indexes FollowSymLinks");
				fclose($fd);
				if ($GLOBALS["stationDebug"]) echo "p2u.run creating directory  $this->ftpdir <br>\n";
			}

// Check if this Tiff file is present in disk system
			if (count ($this->tiffs) == 0)
// Tiff does not exist in disk, send an error
			{
				$this->dbman->endModule ($this->moduleId,5,"tiffs were not found on disk");
				die ();
			}

#
# Code Segment for Quality Control Test executions
#
      include_once("globalsFunctions.php");
			include_once ("request.class.php");
      
      $reqItem = new RequestItem($this->dbcat);
      $reqItem->searchItem($this->numitem);
      $reqItem_status = $reqItem->getItemStatus();
#
# end of segment
#

			foreach ($this->tiffs as $tiff)
			{
				if (strlen ($tiff) == 0)
// Tiff does not exist in disk, send an error
				{
					$this->dbman->endModule ($this->moduleId,5,"$tiff was not found on disk"); 
					die ();
				}
// OK tiff is already in disk, lets make a copy for user

//Don't zip BAND3 from CCD2 Intrument
				if(strstr($tiff,"CCD2") && strstr($tiff,"BAND3"))
				continue;
#
# Code Segment for Quality Control Test executions 
# 				
			if ($reqItem_status == 'Q') 
			{
/* 
			  decodeTiff(basename($tiff),$satellite,$number,$instrument,$channel,$type,$date);
				$qlt_dir = getQualityDir($satellite.$number); 
				$qlt_dir_usreq = $qlt_dir . $this->userid . $this->reqid; 
// Create area where files will be placed if it is the first item;
			  if (!is_dir($qlt_dir_usreq)) mkdir ($qlt_dir_usreq);
		    $cmd = "cp $tiff $qlt_dir_usreq";
			  $res = exec($cmd);
*/
			}
			else $this->saveTiffforUser ($tiff);
#
# end of segment
#				
			}
			
     include_once ("listFiles.php");     // Code Segment for dispatching "h1" LANDSAT-5 radiometric parameters file (amongst tiff files). 
		  $dir = dirname($this->tiffs[0]) . "/";
			$h1_files = listFiles($dir,"h1");
		  foreach ($h1_files as $h1_file)
		  {
		   if($h1_file != "") $this->saveTiffforUser($dir . "/" . $h1_file);
		  };                                 // End Code Segment for LANDSAT-5
		  
#
# Code Segment for dispatching "scenario" files (under directory "scenario")
#     
      $cmd = "find $dir -name \"*scenario\" ";
	    $output =  shell_exec($cmd);
	    $output = substr($output,0,strlen($output)-1);
      $scenario_fullname = explode("\n",$output); 
      $this->saveTiffforUser($scenario_fullname[0]); 
#
# End Code Sement
#
	  
#
# Code Segment for Quality Control Test executions
# 				
			if ($reqItem_status == 'Q') 
			{ 
			  $this->dbman->endModule ($this->moduleId);
			  $reqItem->updateStatus('A'); 
			  return;
			} 
			else $this->updateRequest ();	
			
#
# end of segment
#

// Check how many items have been requested and how many are done
//			$sql = "SELECT * FROM request WHERE reqid = $this->reqid";
        $sql = "SELECT COUNT(*) as nitem, sum(Status='F' or Status='E') as nitemdone from RequestItem where ReqId = $this->reqid";
			$this->dbcat->query ($sql);
			if ($row = $this->dbcat->fetchRow ())
			{
				$nitem = $row["nitem"];
				$nitemdone = $row["nitemdone"];
			}
			else
			{
				$this->dbman->endModule ($this->moduleId,4,"$this->reqid was not found in DB");
			}
			$this->dbcat->freeResult();

// If it is last item, send user a mail and terminate function run
			if ($GLOBALS["stationDebug"])
				echo "p2u.run nitemdone $nitemdone nitem $nitem<br>\n";
			
			if ($nitemdone == $nitem)
				$this->sendMail ();
			else
				$this->sendIntermediateMail ();
		}
}
?>
