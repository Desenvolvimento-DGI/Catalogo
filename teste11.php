<?php

$ANO='2014';
$MES='07';
$DIA='30';

$DATA="$ANO-$MES-$DIA";
echo "\n$DATA\n";

$com="echo $(date +'%j' -d $DATA)";
echo "\n$com\n\n";

$RET=shell_exec($com);


echo "\n\n\n$RET\n";


?>

