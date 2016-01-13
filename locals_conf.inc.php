<?php
#
# Flag for debugging system code
#
$GLOBALS["stationDebug"] = false;
#
# Host and directory address for "Dispatcher" script 
#
$GLOBALS["dispatcher"] =    "http://intranet.dgi.inpe.br/producao/manager/";
#
# Directory address for system logs
#
$GLOBALS["systemLog"] = "/htdocs/CDSR/logs/";
$GLOBALS["wgetlogdir"] = "/htdocs/CDSR/logs/wgetExec_";      // Wget log files directory for specific processes
                                                                // started by Manager.
#
# Directories used by Production Manager
#
#$GLOBALS["cadudir"] = "/dados2/%s/CADU/";    // MODIS archives storage
$GLOBALS["drddir"] =    "/DRD/%s/";     // DRD archives storage
$GLOBALS["orderdir"] =  "/adeos/producao/manager/%s/order/";     // WORKORDER files storage
$GLOBALS["gralhadir"] = "/GRALHA/%s/";    // GRALHA archives storage
$GLOBALS["quickdir"] =  "/adeos/producao/manager/%s/quicklook/";   // QUICKLOOK archives storage
$GLOBALS["rawdir"] =    "/adeos/producao/calibra/";    // Not used 
$GLOBALS["tiffdir"] =   "/TIFF/%s/";     // TIFF archives storage
$GLOBALS["ftpdir"] =    "/download/cdsr/";     //  FTP archives storage - users' download area (for products)
$GLOBALS["wwwserver"] =    "http://imagens.dgi.inpe.br/cdsr/";     // WWW server address for downloading products 
$GLOBALS["cdlabels"]  = "/htdocs/cdlabels";    // Directory where Post Script files for printing CD labels are placed. 
$GLOBALS["basedir"] = "/htdocs/";      // Base directory for Catalog Modules
$GLOBALS["executemodule"] = "/";     // Relative address for a Manager specific script
#
#Directories used locally for increasing performance
#
$GLOBALS["tempdir"] =   "/intranet/producao/tmp/";
#
# Error Files definitions
#
$GLOBALS["errfile"] = "/intranet/producao/tmp/tape_process_01.tmp";
#
# Database variables 
#
$GLOBALS["dbcatname"] = "catalogo";
$GLOBALS["dbusercatname"] = "cadastro"; 
$GLOBALS["dbmanname"] = "gerente";
$GLOBALS["dbusername"] = "cdsr";
$GLOBALS["dbpassword"] =  "cdsr.200408"; 
$GLOBALS["dbhostname"] = "ers.dgi.inpe.br"; 
#$GLOBALS["dbhostname"] = "150.163.134.96";
$GLOBALS["dbport"] = "3333";

$GLOBALS["urlRetornoVisaPath"] = "http://gisplanvelox.dyndns.org:8080/~fduarte/head/catalogo";

#Set this var to test for ecommerce payments
#this will make the total cart price be always 1,00 
#if there is any charged item in it
$GLOBALS["paymentTestMode"] = true;

#Turn on the eletronic payment system (visa, mastercard and boleto)
$GLOBALS['turnOnEletronicPayment'] = true;

#number of days a boleto takes to expire
$GLOBALS["boletoDaysToExpire"] = 30;

#Default processing level
$GLOBALS['g2tDefaultLevel']=2;

#
# Product Manager contact E-mail address
#
$GLOBALS["contactmail"] = "atus@dgi.inpe.br";
#
# Environment variables used by Processing Station Software
#
putenv("STATION_CONF_DIR=/usr/local/etc");
putenv('LD_LIBRARY_PATH=/usr/local/lib:$LD_LIBRARY_PATH');

#
# Log functions
#

$GLOBALS["log_die"] = false;

#function log_msg( $message, $level=LOG_DEBUG ) 
#{
#  if ( $GLOBALS["stationDebug"] or $level < LOG_DEBUG ) {
#    error_log( $message."\n", 3, 
#               $GLOBALS["systemLog"].'/catalog_system.log');
#  }
#  if( $GLOBALS["log_die"] and $level == LOG_ERR ){
#    die( " Aborting. Unexpected error: ".$message );
#  }
#}

#
# This file is used for local environment configurations
#
#if( file_exists( "locals_conf.inc.php" ) ) {
#    include( "locals_conf.inc.php" );
#}
#
# Check if Logs directory exists. If not, create it.
#    
$dir = $GLOBALS["systemLog"];
if (!is_dir($dir)) $dh = mkdir($dir,0775);  

#
# Check if cdlabels directory exists. If not, create it.
#
$dir = $GLOBALS["cdlabels"];
if (!is_dir($dir)) $dh = mkdir($dir,0775);  

?>
