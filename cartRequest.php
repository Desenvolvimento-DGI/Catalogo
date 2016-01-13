<?php
//------------------------------------------------------------------------------
// Author: Denise                                         id
// Date  : november/2003
//------------------------------------------------------------------------------
//include_once("../classes/request.class.php");
//include_once("../classes/cart.class.php");
include("session_mysql.php");

// Session
session_start();
sess_gc(get_cfg_var("session.gc_maxlifetime"));
include_once("globals.php");
include_once("request.class.php");
include_once("cart.class.php");
include_once("stationDB.class.php");
import_request_variables("gpc"); 


// Set Language
if (isset($SESSION_LANGUAGE))
	$_SESSION['userLang']=$SESSION_LANGUAGE;
else
	if (!isset($_SESSION['userLang'])) $_SESSION['userLang']='PT';
require ("cart_".$_SESSION['userLang'].".php");	
require ("messageCat_".$_SESSION['userLang'].".php");	

// Globals
$dbcat = $GLOBALS["dbcatalog"];
$dbusercat =  $GLOBALS["dbusercat"];

if ($GLOBALS["stationDebug"])
	echo "Session = $PHPSESSID User = ".$_SESSION['userId']." Language = ".$_SESSION['userLang']." <br>\n";

 
// Variables
$total = 0;
$nReqRefused=0;
$userid = $_SESSION['userId'];

// Serching user
$objUser = new User($dbusercat);
$objUser->selectByUserId($userid);

// Instanciating Cart Group
$objCartG = new CartGroup($dbcat);
$objCartG->fill($sesskey, $userid);
$mediaCD = false;

// Showing cart itens
for($i=0; $i < $objCartG->nItens; $i++)
{
   // cart itens
   $objCart = new Cart($dbcat);
   $objCart->search($objCartG->cart[$i]["Id"]);
   if ($objCart->media == "CD") {
     $mediaCD = true;
   }
   //Searching the scene
   $sql = "SELECT * FROM Scene WHERE SceneId='". $objCart->sceneId."'";
   $dbcat->query($sql) or $dbcat->error ($sql);
   $itens = $dbcat->numRows();

   if ($itens == 0)
      die("Erro nas Cenas");
   $row = $dbcat->fetchRow();
   $dbcat->freeResult($results);
/*
   // Veritifying user Type
   if(!$objCart->price or ($objCart->price and $objUser->getTypeOfUser()!=2))
   {
      // Calculating Total
      $total += $objCart->price;
   }
      else
         $nReqRefused++;
*/
   $total += $objCart->price;
}

//if($nReqRefused)
//  echo $msgCadComp; 
$actions = $strContinue;
$nItens = $objCartG->nItens - $nReqRefused;
// Payment Choice
//if($nReqRefused < $objCartG->nItens ) 
{

?>
<html>
<head>
<!--    <form name="cartRequestForm" method="POST" action="cartAddress.php">

      <input type="hidden" name="sesskey" value="<?=$sesskey?>">
      <input type="hidden" name="userid" value="<?=$userid?>">
      <input type="hidden" name="nItens" value="<?=$objCartG->nItens - $nReqRefused?>">
      <input type="hidden" name="mediaCD" value="<?=$mediaCD?>">
      <input type="hidden" name="total" value="<?=$total?>">
</form> -->

<script type="text/javascript">
goOn(<?="'".$sesskey."'"?>, <?="'".$userid."'"?>, <?="'".$nItens."'"?>, <?="'".$mediaCD."'"?>, <?="'".$total."'"?>, <?="'".$actions."'"?>);
function goOn(sesskey, userid, nItens, mediaCD, total, actions)
{
   url = "cartAddress.php"; 
   params = "?userid="+ userid + "&nItens="+ nItens + "&sesskey=" + sesskey + "&mediaCD=" + mediaCD + "&total=" + total + "&action=" + actions;
   
   //parent.frames["mosaico"].location.href = url+params;
   // Alterado o container da nova página
   document.location.href = url+params;
//   alert (total);
//   opener.location.href = url+params;  
//   close();
}

</script>
</head>
<body>
<?php
};
?>
</body>
</html>
