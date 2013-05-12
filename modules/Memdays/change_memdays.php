<?php
include("config.php");
global $root_directory;
set_time_limit(0);
////////////////////////////////////////////////////////////////////////////////
//CHANGE STRING IN THIS FILE
//-----------------------------------------------------------------------------
function strChangeInThisFile ($thisFile, $thisString, $thatString){
    // Open thisFile file
	$handle_r = fopen($thisFile,        "r");
	$handle_w = fopen($thisFile.".tmp", "w");

	if ($handle_r && $handle_w) {
		while (!feof($handle_r)) {
			$Buffer   = fgets($handle_r, 4096);
			$Buffer   =  str_replace($thisString, $thatString, $Buffer);
			$result_w = fwrite ($handle_w, $Buffer);
		}

		//  Delete original and rename .tmp
		fclose($handle_r);
		fclose($handle_w);
		if (unlink($thisFile)){
			if (!(rename($thisFile.".tmp",$thisFile))){
				echo "ERROR - Could not rename file:".$thisFile.".tmp<br>";
			}
		}else{
			echo "ERROR - could not delete file:".$thisFile."<br>";
		}
		chmod ($thisFile, 0755);
	}else{
		echo "ERROR - Could not process '$thisFile' file<br>";
		fclose($handle_r);
		fclose($handle_w);
		return;
	}
} // END of function strChangeInThisFile
//Change string in this file

function rename_file($fromDir,$new_module)
{	
	$handle = opendir($fromDir);
	$exceptions=array('.','..');
	while (false != ($item = readdir($handle)))
	{
	   if(is_dir($fromDir."/".$item)) {
		   if (!in_array($item,$exceptions))
		   {
			  $new_module_lower = strtolower($new_module);
			  $new_item = str_replace("Memday",$new_module,$item);
		      $new_item = str_replace("memday",$new_module_lower,$new_item);
			  echo "newItem:".$new_item."<br>";
			  rename($fromDir."/".$item,$fromDir."/".$new_item);
			  rename_file($fromDir."/".$new_item,$new_module); 		   
		   }
	   } else {
		  $new_module_lower = strtolower($new_module);
		  $new_module_upper = strtoupper($new_module);
		  $new_item = str_replace("Memday",$new_module,$item);
		  $new_item = str_replace("memday",$new_module_lower,$new_item);
		  rename($fromDir."/".$item,$fromDir."/".$new_item);
		  strChangeInThisFile($fromDir."/".$new_item,"Memdays",$new_module."s");
		  strChangeInThisFile($fromDir."/".$new_item,"Memday",$new_module);
		  strChangeInThisFile($fromDir."/".$new_item,"memdays",$new_module_lower."s");
		  strChangeInThisFile($fromDir."/".$new_item,"memday",$new_module_lower);
		  strChangeInThisFile($fromDir."/".$new_item,"MEMDAYS",$new_module_upper."S");
		  strChangeInThisFile($fromDir."/".$new_item,"MEMDAY",$new_module_upper);
		  //strChangeInThisFile($fromDir."/".$new_item,"ec_","vtiger_");
		  strChangeInThisFile($fromDir."/".$new_item,"纪念日","客户回访");
		  

	   }
	}
	closedir($handle);	
}
rename_file($root_directory."modules/Memdays","Followup");


echo "Change Memdays default data successfully!<br>";
?>