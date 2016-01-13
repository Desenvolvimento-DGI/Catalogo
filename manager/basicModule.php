<?php
include_once ("managerDB.class.php");
include_once ("globals.php");
	class basicModule {

		var $dbman;
		var $dbcat;
		var $moduleName;
		var $moduleId; // Module id in table gerente.modulestatus
		var $argument;
		var $host;
/* Status may be :
		REGISTERED
		OPERATOR
when used in this context
*/
		var $status;

// Basic constructor
//	$name - module name
//	$arg  - may be a string containing a file name (work order, gralha etc.)
//			    or a numeric that contains a module id in database

		function basicModule ($arg) {
			$this->dbman = $GLOBALS["dbmanager"];
			$this->dbcat = $GLOBALS["dbcatalog"];
			$this->moduleName = get_class($this);
			$this->moduleId = 0;
			$this->host = "";
			$this->status = "REGISTERED";
			if (is_numeric($arg) && $arg !== 0)
			{
				$this->moduleId = $arg;
				$this->argument = $this->dbman->getArgument($this->moduleId);
			}
			else
				$this->argument = $arg;
		}

		function id () {
			return $this->moduleId;
		}

		function argument () {
			return $this->argument;
		}

		function setHost ($host) {
			$this->host = $host;
		}

		function setStatus ($status) {
			$this->status = $status;
		}

		function name () {
			return $this->moduleName;
		}
		
		function start ($dependid=0,$msg="") {
			$this->moduleId = $this->dbman->startModule ($this->moduleName,$this->argument,$dependid,$this->host,$this->status,$msg);
		}

		function restart ($id,$arg="") {
			if (isset ($arg))
			$this->argument = $arg;
			$this->moduleId = $id;
			$this->dbman->restartModule ($this->moduleId,$this->argument,$this->host,$this->status);
		}
	}
?>
