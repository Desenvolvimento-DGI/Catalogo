<?php
function getDRDDir ($satellite)
{
	$drds = sprintf ($GLOBALS["drddir"],$satellite);
	return $drds;
} 
function getHRCDecompressDir ($satellite)
{
	$raws = sprintf ($GLOBALS["hrcdecompdir"],$satellite);
	return $raws;
} 
function getHRCCompressDir ($satellite)
{
	$compress = sprintf ($GLOBALS["hrccompdir"],$satellite);
	return $compress;
}
function getGralhaDir ($satellite)
{
	$gralhas = sprintf ($GLOBALS["gralhadir"],$satellite);
	return $gralhas;
}
function getQuickLookDir ($satellite)
{
	$quicks = sprintf ($GLOBALS["quickdir"],$satellite);
	return $quicks;
}
function getWorkOrderDir ($satellite)
{
	$orders = sprintf ($GLOBALS["orderdir"],$satellite);
	return $orders;
}
function getTiffDir ($satellite)
{
	$tiffs = sprintf ($GLOBALS["tiffdir"],$satellite);
	return $tiffs;
}
function getCADUDir ($satellite)
{
	$cadus = sprintf ($GLOBALS["cadudir"],$satellite);
	return $cadus;
}
function getHWDTDir( $satellite )
{
	$drds = sprintf( $GLOBALS[ "hwdtdir" ], $satellite );
	return $drds;
} 
function getQualityDir ($satellite) 
{
	$qualitys = sprintf ($GLOBALS["qualitydir"],$satellite);
	return $qualitys;
}
function getMTADir( $satellite )
{
	$mtas = sprintf( $GLOBALS[ "mtadir" ], $satellite );
	return $mtas;
}
?>
