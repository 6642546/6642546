<?php
	$file_name = $_POST['file_name'];
	$folder_name= $_POST['folder_name'];
	$old_folder = $_POST['old_folder'];
	$startdir = "../tq_data/" .$folder_name;

	$tq_db=@mysql_connect('10.1.176.180','kylej','kylej') or die("Can not connect mysql database." . mysql_error());
	mysql_select_db('feeweb',$tq_db) or die("Could not select database");
	$query = "delete from tq_main where folder_name='$old_folder' and file_name='$file_name'";

	if (mysql_query($query,$tq_db) and unlink($startdir . "/".$file_name)){
		if(file_exit('',$startdir)==false) {
			rmdir($startdir);
		}
		echo "{\"success\":true,\"message\":\"$file_name has been deleted.\"}";
	} else
		echo "{\"success\":false,\"message\":\"Failed to delete this file!\"}";


	function file_exit($filelastname = '',$path){
		if($filelastname != ''){
		   $handle = opendir($path.$filelastname);
		}else{
		   $handle = opendir($path); 
		}
		while (false !== ($file = readdir($handle))) {
		   if($file == '.' || $file == '..'){
			continue;
		   }
		   $file_array[] = $file;
		}
		if($file_array == NULL){//没有文件
		   closedir($handle);
		   return false;
		}
		closedir($handle);
		return true;//有文件
}


	
				
?>