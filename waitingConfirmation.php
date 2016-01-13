<?php
include("session_mysql.php");

// Session
session_start();
sess_gc( get_cfg_var( "session.gc_maxlifetime" ) );

include_once( "globals.php" );
import_request_variables( "gpc" );

// Set Language
if( isset( $SESSION_LANGUAGE ) ) {
  $_SESSION['userLang'] = $SESSION_LANGUAGE;
}
else {
  if( !isset( $_SESSION['userLang'] ) ) {
    $_SESSION['userLang'] = 'PT';
  }
}

require ("cart_".$_SESSION['userLang'].".php");	


echo "<html>\n";
echo "<head>\n";
include_once( "css.php" );
echo "</head>\n";

echo "<body>\n";
echo '<hr><h3 align="center">'.$strConfirm1.$reqId.$strConfirm2."<BR>".$strConfirm3."</h3>\n";
echo '<hr><h3 align="center">'.$strNewsearch."<h3>\n";
echo "</body>\n";
echo "</html>\n";
?>
