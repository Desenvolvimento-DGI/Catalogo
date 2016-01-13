<?php
function printlabel($SceneId)
{
include("globals.php");
include("drdFunctions.php");  
$dbcat = $GLOBALS["dbcatalog"];
import_request_variables("gpc");

//$SceneId = 'CB2CCD15812720040428'; // for test only 

#
# Get Scene parameters (Path, Row, Projection, Format) from Data Base
#

$sql = "SELECT * from Scene WHERE SceneId = '$SceneId'";
if (!$dbcat->query($sql)) die ($dbcat->errorMessage());
if ($dbcat->numRows() == 0)
{
  echo "An error occured : $sql";
	exit;
};
$row_array = $dbcat->fetchRow();
$satellite = $row_array[Satellite];
$sensor = $row_array[Sensor];
$date = $row_array[Date];
$path = $row_array[Path];
$row = $row_array[Row];
$projection = "UTM" ; // $row_array[Projection] is empty in Data Base ! 
$format = "GeoTiff"; 

switch ($satellite)
    {
     case "CB1":
		 case "CB2":
      $tab_sat_prefix = "Cbers";
	   break;
	   case "L1":
	   case "L2":
	   case "L3":
     case "L5":
     case "L7":
      $tab_sat_prefix = "Landsat";
    };
    
#
# Get Satellite serial number and name (UPPERCASE) 
#

decodeSceneId($SceneId,&$sat_name,&$number,&$instrument,&$year,&$month,&$day,&$shortsat);
$sat_name .= $number;          
$dbcat->freeResult();

#
# Open output PostScript file (nuno.ps) and begin recording PostScript statements 
# to draw the frame (inner & outter circles and legend) for the CD label. 
#

if (!($fp = fopen("../cdlabels/cdlabel_$SceneId.ps", 'w'))) die("Erro na abertura do arquivo para a impressão PostScript !");

$cmd = "%!PS-Adobe-3.0
/inch {72 mul} def
/inner_radius { 1.496 inch 2 div } def
/outer_radius { 4.567 inch 2 div } def
/Helvetica-Oblique findfont
10 scalefont
setfont
/line_height 11 def
/show_at {
  dup show stringwidth pop neg 0 rmoveto
} def
/base_point_x 300 def
/base_point_y 500 def
 base_point_x base_point_y  translate
newpath
 0 0 inner_radius 0 360 arc closepath
stroke
newpath
 0 0 outer_radius 0 360 arc closepath
stroke 

/Data_Items [
(Satelite : $sat_name)
(Sensor : $sensor)
(Path : $path)
(Row : $row)
(Data : $date)
(Projecao : $projection)
(Formato : $format)
] def
newpath
%155 435 moveto
-45 -65 moveto 
Data_Items  {
%show data items at due position
 0 line_height neg rmoveto
 show_at
 }
forall
gsave \n";
fwrite ($fp,$cmd);

#
# Get the Thumbnail image from Data Base, put it in the appropriate format and write it
# to the output PostScript file beeing created.
#

$sql = "SELECT Thumbnail FROM $tab_sat_prefix" . "Thumbnail WHERE SceneId = '$SceneId'";  
if (!$dbcat->query($sql)) die ($dbcat->errorMessage());

if ($dbcat->numRows() == 0)
{
  echo "An error occured : $sql";
	exit;
};

$data = $dbcat->fetchRow();

$browse = imagecreatefromstring($data[0]);
$sx = imagesx($browse);
$sy = imagesy($browse);

$cmd = "%$sx $sy scale
$sx 2.0 div $sy 2.0 div scale
%$sx $sy 8 [$sx 0 0 -$sy 0 $sy]
$sx $sy 8 [$sx 0 0 -$sy -150 $sy 50 sub] 
{currentfile 3 $sx mul string readhexstring pop} bind
false 3 colorimage  
"; 

for ($lin=0;$lin<$sy;$lin++)
for ($col=0;$col<$sx;$col++)
{
	$v = ImageColorAt($browse, $col, $lin);
	$r = ($v&0xFF0000)>>16;
	$g = ($v&0x00FF00)>>8;
	$b = $v&0x0000FF;
	$cmd .= sprintf ("%02x%02x%02x",$r,$g,$b);
}; 

$cmd .= "\n%%EOF \n";  
fwrite ($fp,$cmd);

#
# Get the logo-symbol image file, put it in the appropriate format and write it
# to the output PostScript file beeing created.
#

$browse = ImageCreateFromJpeg("../Suporte/images/inpe_logo1.jpg");

$sx = imagesx($browse);
$sy = imagesy($browse);

$cmd = "\ngrestore
gsave
%$sx $sy scale
$sx 2.0 div $sy 2.0 div scale
%$sx $sy 8 [$sx 0 0 -$sy 0 $sy]
$sx $sy 8 [$sx 0 0 -$sy 65 $sy 170 add] 
{currentfile 3 $sx mul string readhexstring pop} bind
false 3 colorimage
";

for ($lin=0;$lin<$sy;$lin++)
for ($col=0;$col<$sx;$col++)
{
	$v = ImageColorAt($browse, $col, $lin);
	$r = ($v&0xFF0000)>>16;
	$g = ($v&0x00FF00)>>8;
	$b = $v&0x0000FF;
	$cmd .= sprintf ("%02x%02x%02x",$r,$g,$b);
}

$cmd .= "\n%%EOF \n 
grestore \n
showpage";
fwrite ($fp,$cmd);  
fclose ($fp);

#
# Execute command for printing the complete CD label (frame + thumbnail + logo-symbol)
# at a PostScript Printer device.
#
/*
$retval = 0;
$cmd = "ssh nuno@phobos.dpi.inpe.br lp nuno.ps";
exec($cmd,$output,$retval);
echo "cmd = $cmd \n retval = $retval";
if ($retval != 0) die (" =========> Erro na impressão da etiqueta do CD !");
*/
}
?> 