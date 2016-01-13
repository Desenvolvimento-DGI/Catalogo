<?php
/*
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
*/

function listFiles($dir , $type) // Arremedo Provisório !
{

   // echo "\n =======> Listfiles ===========> dir = $dir   ===> type = $type \n";    
  
  if ((strlen($type) == 0) || ($type == "0") or (is_numeric($type) and $type == 0) or $type == "files") $type = "";  
      
  if($type == "dir") $aux_files = glob($dir . "/*");
	else $aux_files = glob($dir . "/" . "*" . $type . "*");
  
  $index = 0;
//  print_r($aux_files);
	foreach ($aux_files as $file)
	{
		if($type == "dir")
		 if(is_dir($file)) $result[$index] = basename($file);
		 else ;
		else $result[$index] = basename($file);
		$index ++;
	};
	
  if(isset($result))
	{
	 sort($result);
 	 reset($result);	 
 	}
	//print_r($result);
	return $result;
};

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

/*
	Lista de arquivos TIF para ZIPAR
	Esta função é usada no G2T
	Quando o a função é executada, somente os arquivos TIF devem ser listados, os TIF.ZIP não devem ser zipados novamente
	Os parametros recebidos pela função são diretorio($dir) e o tipo de arquivo($type) - exemplos de tipos de aruivos: TIF, JPEG.
*/
function listFiles3($dir , $type)
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
/*
	fim da função de listar
*/


?>
