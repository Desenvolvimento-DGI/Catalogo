<?php
include_once ( "stationDB.class.php" );

if (version_compare(phpversion(), '5.0') < 0) {
    eval('
    function clone($object) {
      return $object;
    }
    ');
  }
	class managerDB extends stationDB { 

		function lock ()
		{
		  			$this->query("LOCK TABLES modulestatus WRITE, hosts WRITE, hostmodule WRITE") or $this->error("lock");
		}

		function unlock ()
		{
			$this->query("UNLOCK TABLES") or $this->error("unlock");
		}

		function restartModule ($id,$argument,$host,$status) {
			$this->lock();
// Check to see if this module is already inserted with status equal OPERATOR or ERROR
			$sql = "SELECT * FROM modulestatus WHERE
			(status='OPERATOR' OR status='ERROR') AND id=$id";
			$this->query($sql) or $this->error("startModule - $sql");
			if (! $myrow = $this->fetchRow())
			{
				if ($GLOBALS["stationDebug"])
					echo "restartModule  - Module $id is not in status OPERATOR or ERROR <br>\n";
				$this->unlock();
				return;
			}
// Get module name from table
			$module = $myrow["module"]; 

			$sql = "UPDATE modulestatus SET
			host = '$host',
			status = '$status',
			argument = '$argument'
			WHERE id = $id";
			if ($GLOBALS["stationDebug"])
				echo "restartModule - $sql <br>\n";
			$this->query($sql) or $this->error("startModule");

// Execute the module associated script when it does not depend on other module
			if ($status !== "OPERATOR")
				$this->executeModule ($module,$id,$host);

			$this->unlock();
		}


		function startModule ($module,$argument,$dependid,$host,$status,$msg="") {
			$this->lock(); 						

#
# Delete AUTHORIZE recods if any
#
#		 $pieces = explode(";",$argument);
#		 $previous_id = $pieces[4];
#		 $sql = "DELETE FROM modulestatus WHERE (argument LIKE '%Item $previous_id%' AND status='AUTHORIZE')";
#	   $this->query($sql) or $this->error("endModule");
#	
	
			 
// Check to see if there is another module already registered, queued or running
// that is meant to execute the same work order
			$sql = "SELECT * FROM modulestatus WHERE
			(status='REGISTERED' OR status='RUNNING' OR status='QUEUED' OR status='OPERATOR') 
			AND argument LIKE '$argument%'";
			$this->query($sql) or $this->error("startModule - $sql");
			if ($myrow = $this->fetchRow())
			{
				$id = $myrow["id"];
				if ($GLOBALS["stationDebug"])
					echo "startModule  - $module ($id) was already registered <br>\n";
				$this->unlock();
				return $id;
			}

			$register = date("Y-m-d H:i:s");
			$sql = "INSERT INTO modulestatus SET
			module = '$module',
			argument = '$argument',
			register = '$register',
			dependid = $dependid,
			host = '$host',
			status = '$status',
			message = '$msg'";
		
			if ($GLOBALS["stationDebug"])
				echo "startModule - $sql <br>\n";
			$this->query($sql) or $this->error("startModule");
			$id = $this->insertId();

// Execute the module associated script when it does not depend on other module

			if ($dependid == 0 && $status !== "OPERATOR") // && $status !== "AUTHORIZE")
			$this->executeModule ($module,$id,$host);

			$this->unlock();
			return $id;
		}

		function bootstrapModule ($id) {
			$this->lock();
// Check to see if there is another module already registered, queued or running
// that is meant to execute the same work order
			$sql = "SELECT * FROM modulestatus WHERE id=$id";
			$this->query($sql) or $this->error("bootstrapModule - $sql");
			if ($myrow = $this->fetchRow())
			{
				$module = $myrow["module"];
				if ($GLOBALS["stationDebug"])
					echo "bootstrapModule  - $module ($id) was found <br>\n";
			}
			else
			{
				if ($GLOBALS["stationDebug"])
					echo "bootstrapModule  - Process ($id) was not found<br>\n";
				$this->unlock();
				return $id;
			}

// Execute the module associated script
			$this->executeModule ($module,$id,"");

			$this->unlock();
			return $id;
		}

		function endModule ($id,$retval=0,$message="") {
// Check if this function has been called by a module that is not registered. If so, just return
			if ($id == 0)
				return;
                        log_msg("Ending module with id $id");
			$this->lock();
			
			$status = "UNKNOWN";
			if ($retval == 0)
				$status = "FINISHED";
			else
				$status = "ERROR";
			$end = date("Y-m-d H:i:s");
			$sql = "UPDATE modulestatus SET
			end = '$end',
			status = '$status',
			message = '$message'
			WHERE id = $id";
			if ($GLOBALS["stationDebug"])
				echo "endModule ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("endModule");

// Get the host which has executed this module
			$sql = "SELECT * FROM modulestatus WHERE id = $id";
			if ($GLOBALS["stationDebug"])
				echo "endModule ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("endModule");
			if ($myrow = $this->fetchRow())
				$host = $myrow["host"];
			else
			{
				$this->unlock();
				return;
			}
			$this->freeResult();

// Update the number of modules running on this host
			$sql = "UPDATE hosts SET tasks=tasks-1 WHERE name='$host' AND tasks>0";
			if ($GLOBALS["stationDebug"])
				echo "endModule ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("endModule");

// Get the number of tasks that may be executed
			$sql = "SELECT SUM(maxtasks-tasks) FROM hosts ";
			if ($GLOBALS["stationDebug"])
				echo "endModule ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("endModule");
			$myrow = $this->fetchRow();
			$maxcount = $myrow[0];
			$this->freeResult();
			if ($GLOBALS["stationDebug"])
				echo "endModule ($id) - available tasks = $maxcount <br>\n";
			$count = 0;
// Check to see if there are queued p2u modules that do not depend on other modules
			if ($maxcount>0)
			{
				$sql = "SELECT * FROM modulestatus WHERE module='p2u' AND status='QUEUED' ORDER BY register LIMIT $maxcount";
				if ($GLOBALS["stationDebug"])
					echo "endModule ($id) - $sql <br>\n";
				$this->query($sql) or $this->error("endModule");
				while ($myrow = $this->fetchRow())
				{
					$module[$count] = $myrow["module"];
					$mid[$count] = $myrow["id"];
					$mhost[$count] = $myrow["host"];
					if ($GLOBALS["stationDebug"])
						echo "endModule ".$module[$count]."(".$mid[$count].") was found <br>\n";
					$count++;
					$maxcount--;
					if ($maxcount == 0)
					break;
				}
				$this->freeResult();
			}
// Check to see if there are modules that depend on this module, if it has finished successfully
			if ($retval == 0)
			{
				$sql = "SELECT * FROM modulestatus WHERE status='REGISTERED' AND dependid=$id";
				if ($GLOBALS["stationDebug"])
					echo "endModule ($id) - $sql <br>\n";
				$this->query($sql) or $this->error("endModule");
				while ($myrow = $this->fetchRow())
				{
					$module[$count] = $myrow["module"];
					$mid[$count] = $myrow["id"];
					$mhost[$count] = $myrow["host"];
					if ($GLOBALS["stationDebug"])
						echo "endModule ".$module[$count]."(".$mid[$count].") was found <br>\n";
					$count++;
				}
				$this->freeResult();
			}
			else
			{
/* Nao abortar os dependentes - O modulo que deu erro pode ser restartado
				if ($GLOBALS["stationDebug"])
					echo "endModule $module ($id) is aborting its dependents. <br>\n";
				$sql = "UPDATE modulestatus SET
				status = 'ABORTED'
				WHERE dependid = $id";
				if ($GLOBALS["stationDebug"])
					echo "endModule $module ($id) - $sql <br>\n";
				$this->query($sql) or $this->error("endModule");
*/
			}

// Check to see if there are queued modules that do not depend on other modules
			if ($maxcount>0)
			{
				$sql = "SELECT * FROM modulestatus WHERE module!='p2u' AND status='QUEUED' ORDER BY register";
				if ($GLOBALS["stationDebug"])
					echo "endModule ($id) - $sql <br>\n";
				$this->query($sql) or $this->error("endModule");
				while ($myrow = $this->fetchRow())
				{
					$module[$count] = $myrow["module"];
					$mid[$count] = $myrow["id"];
					$mhost[$count] = $myrow["host"];
					if ($GLOBALS["stationDebug"])
						echo "endModule ".$module[$count]."(".$mid[$count].") was found <br>\n";
					$count++;
//					$maxcount--;
//					if ($maxcount == 0)
//					break;
				}
				$this->freeResult();
			}
// Execute the module associated script
			for ($i=0;$i<$count;$i++)
				$this->executeModule ($module[$i],$mid[$i],$mhost[$i]);
			$this->unlock();
			if ($GLOBALS["stationDebug"])
				echo "endModule returning <br>\n";
		}

		function restartDependents ($id) {
// Check if this function has been called by a module that is not registered. If so, just return
			if ($id == 0)
				return;
			$this->lock();
			
// Update this module status
			$end = date("Y-m-d H:i:s");
			$sql = "UPDATE modulestatus SET
			end = '$end',
			status = 'FINISHED',
			message = 'Restarted Dependents'
			WHERE id = $id";
			if ($GLOBALS["stationDebug"])
				echo "restartDependents ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("restartDependents");

// Check to see if there are modules that depend on this module
			$sql = "SELECT * FROM modulestatus WHERE (status='REGISTERED' OR status='ERROR') AND dependid=$id";
			if ($GLOBALS["stationDebug"])
				echo "restartDependents ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("endModule");
			$count = 0;
			while ($myrow = $this->fetchRow())
			{
				$module[$count] = $myrow["module"];
				$mid[$count] = $myrow["id"];
				$mhost[$count] = "";
				if ($GLOBALS["stationDebug"])
					echo "restartDependents ".$module[$count]."(".$mid[$count].") was found <br>\n";
				$count++;
			}
			$this->freeResult();

// Execute the module associated script
			for ($i=0;$i<$count;$i++)
				$this->executeModule ($module[$i],$mid[$i],$mhost[$i]);
			$this->unlock();
			if ($GLOBALS["stationDebug"])
				echo "restartDependents returning <br>\n";
		}

		function isRunning ($id) {
			$sql = "SELECT * FROM modulestatus WHERE id=$id AND
			(status='RUNNING' OR status='QUEUED')";
			$this->query($sql) or $this->error("isRunning - $sql"); 
			if ($this->numRows() > 0)
				return true;
			return false;
		}

          

          function recreateObjectWithNewId($id) {
            //When it is necessary to restart a module with other parameters
            //(e.g., when level 4 fails, automatically try level3)
            //this method will create a copy of the module, and update the dependencies
            //from the old module to the new one
            log_msg("cloning object $id");
            $this->lock();
            $consultSql = "SELECT * FROM modulestatus WHERE id=$id";
            log_msg( $consultSql) ;
            $this->query($consultSql) or 
              $this->error("clone querying - $sql"); 
            $row = $this->fetchRow();
            $cloneSql = 'INSERT INTO modulestatus (host, module, argument, 
                        register,status) 
                        VALUES ( "'.$row['host'].'", "'.$row['module'].'", 
                        "'.$row['argument'].'", "'.$row['register'].'", 
                        "QUEUED")';

            log_msg("clone sql: $cloneSql");
            $this->query($cloneSql) or $this->error("clone inserting - $sql"); 
            $newId = $this->insertId();
            log_msg( "object $newId is a clone of $id");
            $this->updateDependencies($id, $newId);
            $this->unlock();
            return $newId;
          }
          
          function updateDependencies($fromId, $toId) {
            $sql = "UPDATE modulestatus SET dependid=$toId WHERE dependid=$fromId";
            $this->query($sql) or $this->error("updating module dependencies - $sql"); 
          }

		function executeModule ($module,$id,$host) {

// Get an available host to execute this module
			$sql = "SELECT * FROM hosts,hostmodule WHERE tasks<maxtasks AND module='$module' AND name=host"; 
			if ($host != "")
				$sql .= " AND name='$host'";
			$sql .= " ORDER BY (maxtasks-tasks) DESC";
			if ($GLOBALS["stationDebug"])
				echo "executeModule $module ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("executeModule");
			$hosts = $this->numRows();
			if ($hosts > 0)
			{
				$row = $this->fetchRow();
				$this->freeResult();
				$host = $row["name"];
				$hostip = $row["ip"];
				$this->freeResult();
			}
			else // No host is available - tell module is queued and return
			{
				$this->freeResult();
				if ($GLOBALS["stationDebug"])
					echo "executeModule $module ($id) is being queued. <br>\n"; 
				$sql = "UPDATE modulestatus SET
				status = 'QUEUED',
				message = ''
				WHERE id = $id";
				if ($GLOBALS["stationDebug"])
					echo "executeModule $module ($id) - $sql <br>\n";
				$this->query($sql) or $this->error("executeModule"); 
				return $id;
			}

// Update host number of tasks
			$sql = "UPDATE hosts SET tasks=tasks+1 WHERE name='$host'";
			if ($GLOBALS["stationDebug"])
				echo "executeModule $module ($id) - $sql <br>\n";
			$this->query($sql) or $this->error("executeModule");
			
// Build and execute required module calling it by wget
        log_msg("Remote calling module $module using wget");
        $path = "wget -T 0 http://$hostip" . $GLOBALS["executemodule"] . "manager/$module" . ".php?id=$id -o " . $GLOBALS["wgetlogdir"] . "wget_log_$module";

  			if ($GLOBALS["stationDebug"])
//  				$path .= " -c -b -P " . $GLOBALS["systemLog"] . "stDebug &"; // -c Wget option is causing duplicating d2g (and g2q) execution ! (must be clarified)
            $path .= " -b -P " . $GLOBALS["systemLog"] . "stDebug &";
  			else
//  			$path .= " -c -b -q -nv  >&- <&- > /dev/null &";

 //         $path .= " -c -b -q -P " . $GLOBALS["systemLog"] . "stDebug &";   // -c Wget option is causing duplicating d2g (and g2q) execution ! (must be clarified)    
 
      $path .= " -b -q -P " . $GLOBALS["systemLog"] . "stDebug &";      

			exec($path,$output,$retval); 

			if ($GLOBALS["stationDebug"])
				echo "executeModule $module ($id) Command: ".$path." <br>\n";

			if ($retval != 0 && $GLOBALS["stationDebug"])
				echo "executeModule Error $module ($id) : ".nl2br ( $output[0]) ."<br>\n";

// On success, update current module status
			if ($GLOBALS["stationDebug"])
				echo "executeModule $module ($id) is running <br>\n";
			$start = date("Y-m-d H:i:s");
			$sql = "UPDATE modulestatus SET
			host = '$host',
			start = '$start',
			status = 'RUNNING',
			message = ''
			WHERE id = $id";
			if ($GLOBALS["stationDebug"])
				echo "executeModule $module ($id) - $sql <br>\n";

			$this->query($sql) or $this->error("executeModule");

			return $retval;
		}

		function getArgument ($id) {
			$sql = "SELECT * FROM modulestatus WHERE id = $id";
			$this->query($sql);
			$argument= "";
			if ($myrow = $this->fetchRow())
				$argument = $myrow["argument"];
			return $argument;
		}

		function showStatus ($mod="",$state="") {

			$sql = "SELECT * FROM hosts ORDER BY name ASC";
			$this->query($sql);
			print "
			<TABLE WIDTH=\"100%\">
			<CAPTION align=\"center\">Hosts</CAPTION>
			<TH></TH>
			<TH>Host</TH>
			<TH>ip</TH>
			<TH>Tasks</TH>
			<TH>Max Tasks</TH>
			\n";
			$n =1;
			while ($myrow = $this->fetchRow())
			{
				printf ("<TR><TD>%d</TD><TD>%s</TD><TD>%s</TD><TD>%d</TD><TD>%d</TD></TR>\n",
				$n,$myrow["name"],$myrow["ip"],$myrow["tasks"],$myrow["maxtasks"]);
				$n ++;
			}
			print "</TABLE><BR>";

			if ($state=="")
				$sql = "SELECT * FROM modulestatus WHERE 1";
			else
			{
				$states = explode (";",$state);
				if (count($states)>1)
				{
					$sql = "SELECT * FROM modulestatus WHERE (";
					for ($i=0;$i<count($states);$i++)
					{
						if ($i != 0)
							$sql .= " OR ";
						$sql .= "status='".$states[$i]."'";
					}
					$sql .= ")";
				}
				else
				$sql = "SELECT * FROM modulestatus WHERE status='$state'";
			}
			if ($mod != "")
				$sql .= " AND module='$mod'";
			if ($state=="")
			$sql .= " ORDER BY id DESC";
			else
			$sql .= " ORDER BY id ASC";

			$this->query($sql);
			$tasks = $this->numRows();
			$date = date("H:i:s");
			print "
			<TABLE WIDTH=\"100%\">
			<CAPTION>Operation Status at $date</CAPTION>
			<TH>Host</TH>
			<TH>Id</TH>
			<TH>Module</TH>
			<TH>Register</TH>
			<TH>Start</TH>
			<TH>End</TH>
			<TH>Status</TH>
			<TH>Depend</TH>
			<TH>Work Order</TH>
			<TH>Message</TH>
			\n";
			while ($myrow = $this->fetchRow())
			{
				$class = "vermelho";
				if ($myrow["status"] == "REGISTERED")
					$class = "azul";
				else if ($myrow["status"] == "QUEUED")
					$class = "ciano";
				else if ($myrow["status"] == "RUNNING")
					$class = "verde";
				else if ($myrow["status"] == "FINISHED")
					$class = "preto";

				if (isset($myrow["end"]))
					$end = $myrow["end"];
				else
					$end = "";
				
				
				if($myrow["module"] == "p2u" and $myrow["status"] <> "FINISHED") $modclass = "verde";
				else $modclass = "";
				
				printf ("<TR><TD>%s</TD><TD>%d</TD><TD class=\"%s\">%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD class=\"%s\">%s</TD><TD>%d</TD><TD>%s</TD><TD>%s</TD></TR>\n",
				$myrow["host"],$myrow["id"],$modclass,$myrow["module"],$myrow["register"],$myrow["start"],$end,$class,$myrow["status"],$myrow["dependid"],$myrow["argument"],$myrow["message"]);
			}
			print "</TABLE>";
		}

		function displayHostsOnTable ($host,$device=0) {
			$sql = "SELECT * FROM hosts WHERE maxtasks>0";
			if ($device == 1)
				$sql .= " AND tape=1";
			if ($device == 2)
				$sql .= " AND cd=1";
			$this->query($sql);
			echo "<TH colspan=6>Hosts</TH>\n
			<TR><TD>Host</TD><TD colspan=3><SELECT name=\"host\">";
			if ($device == 0)
			echo "<OPTION value=\"\"></OPTION>\n";
			while ($myrow = $this->fetchRow())
			{
				$name = $myrow["name"];
				if (isset ($host) && $host == $name)
				printf ("<OPTION selected value=\"%s\">%s</OPTION>\n",$name,$name);
				else
				printf ("<OPTION value=\"%s\">%s</OPTION>\n",$name,$name);
			}
			echo "</SELECT></TD></TR>\n";
		}

	}
?>