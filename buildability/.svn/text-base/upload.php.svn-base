<?php

header('Content-Type: text/html; charset=UTF-8');

$inputName='filedata';//name
$attachDir='upload';// uploaded file path
$dirType=1;//1:day 2:month 3:file ext
$maxAttachSize=2097152;//max file size:2M
$upExt='txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid';//ext
$msgType=2;//1，return url，2，return array
$immediate=isset($_GET['immediate'])?$_GET['immediate']:0;
ini_set('date.timezone','Asia/Shanghai');// time zone

$err = "";
$msg = "''";
$tempPath=$attachDir.'/'.date("YmdHis").mt_rand(10000,99999).'.tmp';
$localName='';

if(isset($_SERVER['HTTP_CONTENT_DISPOSITION'])&&preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info)){//HTML5 upload
	file_put_contents($tempPath,file_get_contents("php://input"));
	$localName=$info[2];
}
else{//standard
	$upfile=@$_FILES[$inputName];
	if(!isset($upfile))$err='file name error';
	elseif(!empty($upfile['error'])){
		switch($upfile['error'])
		{
			case '1':
				$err = 'file size is bigger than upload_max_filesize in php.ini';
				break;
			case '2':
				$err = 'file size is bigger than MAX_FILE_SIZE in HTML';
				break;
			case '3':
				$err = 'file does not upload complete.';
				break;
			case '4':
				$err = 'can not upload';
				break;
			case '6':
				$err = 'no tmp file folder';
				break;
			case '7':
				$err = 'write file fail.';
				break;
			case '8':
				$err = 'upload fail.';
				break;
			case '999':
			default:
				$err = 'error code';
		}
	}
	elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')$err = 'no file uploaded';
	else{
		move_uploaded_file($upfile['tmp_name'],$tempPath);
		$localName=$upfile['name'];
	}
}

if($err==''){
	$fileInfo=pathinfo($localName);
	$extension=$fileInfo['extension'];
	if(preg_match('/'.str_replace(',','|',$upExt).'/i',$extension))
	{
		$bytes=filesize($tempPath);
		if($bytes > $maxAttachSize)$err='Please do not upload the size of the file bigger than '.formatBytes($maxAttachSize);
		else
		{
			switch($dirType)
			{
				case 1: $attachSubDir = 'day_'.date('ymd'); break;
				case 2: $attachSubDir = 'month_'.date('ym'); break;
				case 3: $attachSubDir = 'ext_'.$extension; break;
			}
			$attachDir = $attachDir.'/'.$attachSubDir;
			if(!is_dir($attachDir))
			{
				@mkdir($attachDir, 0777);
				@fclose(fopen($attachDir.'/index.htm', 'w'));
			}
			PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
			$newFilename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
			$targetPath = $attachDir.'/'.$newFilename;
			
			rename($tempPath,$targetPath);
			@chmod($targetPath,0755);
			$targetPath=jsonString($targetPath);
			if($immediate=='1')$targetPath='!'.$targetPath;
			if($msgType==1)$msg="'$targetPath'";
			else $msg="{'url':'".'buildability/'.$targetPath."','localname':'".jsonString($localName)."','id':'1'}";//id参数固定不变，仅供演示，实际项目中可以是数据库ID
		}
	}
	else $err='ext must be：'.$upExt;

	@unlink($tempPath);
}

echo "{'err':'".jsonString($err)."','msg':".$msg."}";


function jsonString($str)
{
	return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
}
function formatBytes($bytes) {
	if($bytes >= 1073741824) {
		$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
	} elseif($bytes >= 1048576) {
		$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
	} elseif($bytes >= 1024) {
		$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
	} else {
		$bytes = $bytes . 'Bytes';
	}
	return $bytes;
}
?>