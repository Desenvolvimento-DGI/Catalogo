<?php
function listFiles($dir , $type)
{ 
	if (strlen($type) == 0) $type = "all"; 
	$x = 0; 
	if(is_dir($dir)) 
	{ 
		$thisdir = dir($dir); 
		while($entry=$thisdir->read()) 
		{
			if(($entry!='.')&&($entry!='..'))
			{ 
				if ($type == "all") {$result[$x] = $entry; $x++; continue;} 
				$isFile = is_file("$dir$entry");
				$isDir = is_dir("$dir$entry"); 
				if (($type == "files") && ($isFile))
				{
					$result[$x] = $entry; $x++; continue;
				} 
				if (($type == "dir") && ($isDir))
				{
					$result[$x] = $entry; $x++; continue;
				} 
  
				$temp = explode(".", $entry); 
  
				if (($type == "noext") && (strlen($temp[count($temp) - 1]) == 0))
					{$result[$x] = $entry; $x++;continue;}

				if (($isFile) && (strtolower($type) == strtolower($temp[count($temp) - 1])))
					{$result[$x] = $entry; $x++;continue;}
			}
		}
		if ($x > 0)
		{
			sort($result);
			reset($result);
		}
	}
	return $result;
}

function listFiles2($dir , $type)
{
	if (strlen($type) == 0) $type = "all";
	$x = 0;
	if(is_dir($dir))
	{
		$thisdir = opendir($dir);
		while($entry=readdir($thisdir))
		{
			if(($entry=='.') or ($entry=='..'))
				continue;
			$result[$x] = $entry;
			$x++;
		}
		if ($x>0)
		{
			sort($result);
			reset($result);
		}
        }
        return $result;
}



?>
