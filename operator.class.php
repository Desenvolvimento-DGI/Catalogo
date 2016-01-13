<?php
//------------------------------------------------------------------------------
//
// Classe Operator
//
//------------------------------------------------------------------------------
class Operator
{
   var $operId;
   var $password;
   var $name;
   var $priv;
   var $blocked;
   var $try;
   var $bd;

   function Operator($bd)
   {
      $this->operId = "";
      $this->password="";
      $this->name="";
      $this->priv=0;
      $this->blocked=0;
      $this->try=0;
      $this->bd = $bd;
   }
   
   function getOperator ($parName, $parPass="")
   {
   	$sql = "SELECT * FROM Operator WHERE OperatorId='$parName'";
   	if(trim($parPass)!="")
   	  $sql .= " AND Password = PASSWORD('$parPass')";
		$this->bd->query($sql);
		$count = $this->bd->numRows();
		if ($count == 0)
      {
         $this->try++;
         return false;
      }

      // Set fields
      $row = $this->bd->fetchRow();
      $this->operId = $row["OperatorId"];
      $this->name = $row["Fullname"];
      $this->priv = $row["Privilege"];
      $this->blocked = $row["Blocked"];

      return true;
   }

   function login()
   {
      // Session  Start
      //if (version_compare(PHP_VERSION, '4.3.3') == -1) setcookie(session_name(), session_id());
		session_set_save_handler(
					"sess_open",
					"sess_close",
					"sess_read",
					"sess_write",
					"sess_destroy",
					"sess_gc");
		session_start();
		
		// Session Sets
   	$_SESSION['userLang'] = 'PT';
   	$_SESSION['operIP'] = $_SERVER["REMOTE_ADDR"];
      $_SESSION['operatorId'] = $this->operId;
 		$_SESSION['userTry'] = 0;
  }
  function logout ()
  {
      // Destroying Session
   	$_SESSION = array();
		session_destroy();
		//session_regenerate_id();

  }
};
?>
