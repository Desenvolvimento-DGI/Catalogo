<?php
include_once ("tiffFunctions.php");
include("g2t.class.php");
include("p2u.class.php");
include("p2c.class.php");
include("m2d.class.php");
include("d2g.class.php");

	function userRequest ($userid,$reqid,$reqitem=-1) {

		$dbcat = $GLOBALS["dbcatalog"];

// Get not processed itens from request into an array
		if ($reqitem==-1)
		$sql = "SELECT * FROM RequestItem WHERE ReqId = $reqid AND Prodate IS NULL";
		else
		$sql = "SELECT * FROM RequestItem WHERE ReqId=$reqid AND NumItem=$reqitem";
		if ($GLOBALS["stationDebug"])
			echo "userRequest Command: ".$sql."<br>\n";
		$dbcat->query ($sql);
		$count = 0;
		while ($row = $dbcat->fetchRow ())
		{
			$SceneId[$count] = $row["SceneId"];
			$media[$count] = $row["Media"];
			$itemnum[$count] = $row["NumItem"];
			$count++;
		}
		$dbcat->freeResult();

// For items in request
		$sendmail = true;
		for ($i=0 ; $i < $count ; $i++)
		{
			$inproduction = true;
			if (substr ($SceneId[$i],0,2) == "CB")
				$table = "CbersScene";
			else if (substr ($SceneId[$i],0,1) == "L")
				$table = "LandsatScene";

        $table = "Scene";
        
// Get name of Gralha file related to this scene
			$sql = "SELECT * FROM $table WHERE SceneId='".$SceneId[$i]."'";
			if ($GLOBALS["stationDebug"])
				echo "userRequest Command: ".$sql."<br>\n";
			$dbcat->query($sql);
			$row = $dbcat->fetchRow() or $dbcat->error($sql);
			$gralha = $row["Gralha"];
			$dbcat->freeResult();

// Construct a p2u to deal with with item
			$argument = $userid.";".$reqid.";".$SceneId[$i].";".$gralha.";".$i;
			if ($media[$i] == "CDROM")
			{
				$p2u = new p2c ($argument);
				$p2u->setStatus ("OPERATOR");
			}
			else
				$p2u = new p2u ($argument);
			$tiffs = $p2u->getTiffs ();
			if (count ($tiffs) > 0)
// OK it is already in disk, lets make a copy for user
			{
				if ($GLOBALS["stationDebug"])
					echo "userRequest - Tiff found $argument <br>\n";
				$p2u->start ();
			}
			else
// Tiff does not exist in disk, lets build it from gralha
			{
				if ($GLOBALS["stationDebug"])
					echo "userRequest - Tiff not found, looking for $gralha <br>\n";
				$gralhas = findSimilarGralhainDisk ($gralha);
				if (count ($gralhas) > 0)
				{
					$depend = 0;
					foreach($gralhas as $file)
					{
						$g2t = new g2t (basename($file));
						if ($GLOBALS["stationDebug"])
							echo "userRequest - $file found <br>\n";
						$g2t->createWorkOrder();
						$g2t->start ($depend);
						$depend = $g2t->id();
					}
					$p2u->start ($depend);
				}
				else
// Gralha does not exist in disk, lets build it from DRD
				{
					if ($GLOBALS["stationDebug"])
						echo "userRequest - $gralha not found <br>\n";
					$status = findDRDfromSceneId ($SceneId[$i],$drd,$tapeid,$tapepath,$skip);
					if ($status == 0)
// DRD is registered in database
					{
						if ($GLOBALS["stationDebug"])
							echo "userRequest - drd = $drd  <br>\n";
						$fulldrd = findDRDinDisk ($drd);
						if (strlen($fulldrd) > 1)
						{
							$d2g = new d2g ($drd);
							if ($GLOBALS["stationDebug"])
								echo "userRequest - creating d2g for $drd<br>\n";
							$d2g->createWorkOrder();
							$d2g->start ();

							$g2t = new g2t ($gralha);
							if ($GLOBALS["stationDebug"])
								echo "userRequest - creating g2t for $gralha<br>\n"; 
							$g2t->createWorkOrder();
							$g2t->start ($d2g->id());

							$p2u->start ($g2t->id());
						}
						else
// DRD does not exist in disk, ask operator to read it from tape
						{
							$targument = $tapeid.";".$tapepath.";;".$skip; // IMPORTANT
							$m2d = new m2d ($targument);
							if ($GLOBALS["stationDebug"])
								echo "userRequest - creating m2d for $targument<br>\n";
							$m2d->setStatus ("OPERATOR");
							$m2d->start ();

							$d2g = new d2g ($drd);
							if ($GLOBALS["stationDebug"])
								echo "userRequest - creating d2g for $drd<br>\n";
							$d2g->createWorkOrder();
							$d2g->start ($m2d->id());

							$g2t = new g2t ($gralha);
							if ($GLOBALS["stationDebug"])
								echo "userRequest - creating g2t for $gralha<br>\n";
							$g2t->createWorkOrder();
							$g2t->start ($d2g->id());
							$p2u->start ($g2t->id());
							if ($sendmail)
								$p2u->sendFirstMail ();
							$sendmail = false;
						}
					}
					else
					{
					$p2u->sendErrorMail ($SceneId[$i]);
					$inproduction = false;
					}
				}
			}
			$sql = "UPDATE RequestItem SET Status=";
			$sql .= $inproduction ? "'C'" : "'Y'";
			$sql .= " WHERE ReqId=$reqid AND NumItem=".$itemnum[$i];
			$dbcat->query($sql);
		}
	}
?>
