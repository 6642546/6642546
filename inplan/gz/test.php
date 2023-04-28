<?php 
clearstatcache();
$file = "\\\\10.120.238.82\MI_Graph\A122436C-302.pdf";
$fileExists = @file_get_contents($file,null,null,-1,1) ? true : false;

if($fileExists){
   echo "File Exists!";
}else{
   echo "Sorry, we couldn't find the file.";
}

?>