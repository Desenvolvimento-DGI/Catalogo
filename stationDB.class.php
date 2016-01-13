<?
	class stationDB {

		var $db_name;
		var $db_user;
		var $db_password;
		var $db_host;
		var $db_port;
		var $db_link_ptr;
		var $db_result;
		var $db_isopened;

		function errorMessage() {
			return mysql_error($this->db_link_ptr); 
		}

		function error($where="") {
//			echo "Error executing $where -";
			if (isset ($this->db_link_ptr))
			{
				$error = mysql_error($this->db_link_ptr);
				$errno = mysql_errno($this->db_link_ptr);
				die($error." - ".$errno);
			}
			else
				die ();
		}

		function stationDB($host, $port, $user, $passwd, $db) { 
			$this->db_name = $db;
			$this->db_user = $user;
			$this->db_passwd = $passwd;
			$this->db_host = $host;
			$this->db_port = $port; 
			$count=0;

			while (!($this->db_link_ptr = @mysql_connect($host . ":" . $port,$user,$passwd,true)) && $count < 3)
			{
				sleep (1);
				$count++;
			}
			if (!$this->db_link_ptr)
				$this->error("mysql_connect");
			$this->db_isopened = false;
			$this->selectDB();
		}

 		function selectDB() { 
			$this->dbhandler = @mysql_select_db($this->db_name,$this->db_link_ptr) or $this->error("selectDB - ".$this->db_name);
			$this->db_isopened = true;
		}

		function closeDB() {
			if ($this->db_isopened)
			@mysql_close($this->db_link_ptr) or $this->error("closeDB - ".$this->db_name);
			$this->db_isopened = false;
		}

		function query($sql_stat) {
			$this->db_result = @mysql_query($sql_stat,$this->db_link_ptr);
//			if (!$this->db_result) $this->error("dbresult");
			return $this->db_result;
		}

		function insertId() {
			return mysql_insert_id($this->db_link_ptr);
		}

		function freeResult($result = null) {
			if ($result == null)
				@mysql_free_result($this->db_result);
			else
				@mysql_free_result($result);
		}

		function fetchRow($rownum = null) {
			if ($rownum !== null)
				mysql_data_seek ($this->db_result,$rownum);
			return mysql_fetch_array($this->db_result); 
		}
		
		function nextRow($result = null) {
			if ($result == null)
				return mysql_fetch_array($this->db_result);
			else
				return mysql_fetch_array($result);
		}

		function numRows() {
			return mysql_num_rows($this->db_result);
		}
	}
?>