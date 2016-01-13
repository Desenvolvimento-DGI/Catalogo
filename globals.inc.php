<?php
#
# Flag for debugging system code
#
$GLOBALS["stationDebug"] = false;
#
# Host and directory address for "Dispatcher" script 
#
$GLOBALS["dispatcher"] =    "http://cbers5.dpi.inpe.br/newcatalog/manager/";
#
# Directory address for system logs
#
$GLOBALS["systemLog"] = "/www/newcatalog/logs/";
$GLOBALS["wgetlogdir"] = "/www/newcatalog/logs/wgetExec_";      // Wget log files directory for specific processes
                                                                // started by Manager.
#
# Directories used by Production Manager
#
$GLOBALS["cadudir"] = "/dados2/%s/CADU/";    // MODIS archives storage
$GLOBALS["drddir"] =    "/home/cbers2/%s/DRD/";     // DRD archives storage
$GLOBALS["orderdir"] =  "/home/cbers2/%s/order/";     // WORKORDER files storage
$GLOBALS["gralhadir"] = "/home/cbers2/%s/GRALHA/";    // GRALHA archives storage
$GLOBALS["quickdir"] =  "/home/cbers2/%s/quicklook/";   // QUICKLOOK archives storage
$GLOBALS["rawdir"] =    "/www/calibra/";    // Not used 
$GLOBALS["tiffdir"] =   "/home/cbers2/%s/TIFF/";     // TIFF archives storage
$GLOBALS["ftpdir"] =    "/www/ftp/";     //  FTP archives storage - users' download area (for products)
$GLOBALS["wwwserver"] =    "http://cbers5.dpi.inpe.br/ftp/";     // WWW server address for downloading products 
#
# Directory to store cdlabels Post Script commands to print out CD labels
#
$GLOBALS["cdlabels"]  = "/www/newcatalog/cdlabels";     
#
# Directory where site dependent information (for first catalog home page "first.phh") are stored.
#
$GLOBALS["localinfo"] = "/catalogo/localinfo/";
# 
$GLOBALS["basedir"] = "/www/newcatalog/";      // Base directory for Catalog Modules
$GLOBALS["executemodule"] = "/newcatalog/";     // Relative address for a Manager specific script (where directory "manager" is in)
#
#Directories used locally for increasing performance
#
$GLOBALS["tempdir"] =   "/DRD/tmp/";
#
# Error Files definitions
#
$GLOBALS["errfile"] = "/cbersdisk/tmp/tape_process_01.tmp";
#
# Database variables 
#
$GLOBALS["dbcatname"] = "catalogo";
$GLOBALS["dbusercatname"] = "cadastro"; 
$GLOBALS["dbmanname"] = "gerente";
$GLOBALS["dbusername"] = "root";
$GLOBALS["dbpassword"] =  ""; 
$GLOBALS["dbhostname"] = "cbers5.dpi.inpe.br"; 
$GLOBALS["dbport"] = "3306";

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
$GLOBALS["contactmail"] = "product@dpi.inpe.br";
#
# Quality Control Staff E-mail address
#
$GLOBALS["qualitycontrolmail"] = "product@dpi.inpe.br";
#
# Environment variables used by Processing Station Software
#
putenv("STATION_CONF_DIR=/usr/local/etc");
putenv('LD_LIBRARY_PATH=/usr/local/lib:$LD_LIBRARY_PATH');

#
# Log functions
#

$GLOBALS["log_die"] = false;

function log_msg( $message, $level=LOG_DEBUG ) 
{
  if ( $GLOBALS["stationDebug"] or $level < LOG_DEBUG ) {
    error_log( $message."\n", 3, 
               $GLOBALS["systemLog"].'/catalog_system.log');
  }
  if( $GLOBALS["log_die"] and $level == LOG_ERR ){
    die( " Aborting. Unexpected error: ".$message );
  }
}

#
# This file is used for local environment configurations
#
if( file_exists( "locals_conf.inc.php" ) )
{
 include( "locals_conf.inc.php" );
}
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