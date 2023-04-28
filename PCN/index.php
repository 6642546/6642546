<?php
// +----------------------------------------------------------------------
// | Author:sheng.zhang 2019-10-14
// +----------------------------------------------------------------------
//date_default_timezone_set('PRC'); 
//$lifeTime = 24 * 3600 * 7;  
//session_set_cookie_params($lifeTime); 
//session_save_path('./sessions');
session_start(); 
define('THINK_PATH', '../ThinkPHP/');
define('APP_NAME', 'PCN');
define('APP_PATH', './');
define('APP_DEBUG',true);
define('WEB_PUBLIC_PATH','./Public');
define('SHOW_ERROR_MSG',true);
require(THINK_PATH."/ThinkPHP.php");
//require(THINK_PATH."/ThinkPHP.php");
/*$targetDir ='D:\wamp64\www\PCN/Uploads';
$fileName="0206.pdf";
$targetFile=$targetDir.'/'.$fileName;
echo $targetFile.'</br>';
$chunks = glob("{$targetDir}/{$fileName}_*"); 
          combineChunks($chunks,$targetFile);

				 function combineChunks($chunks, $targetFile) {
				// open target file handle
				$handle = fopen($targetFile, 'a+');
				foreach ($chunks as $file) {
					fwrite($handle, file_get_contents($file));
				}
				
				// you may need to do some checks to see if file 
				// is matching the original (e.g. by comparing file size)
				
				// after all are done delete the chunks
				foreach ($chunks as $file) {
					@unlink($file);
				}
				
				// close the file handle
				fclose($handle);
				
			}*/
			?>