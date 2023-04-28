<?php
error_reporting(0);
if (!$site){
	if (isset($_GET['site'])){
		$site = $_GET['site'];
	}else {
		//require "../config.php";
		$site = $defaultSite;
	}
}

if ($site == 'HY' || $site == 'HZ'){
	$server = 'asia';
} else if ($site == 'GZ' || $site == 'ZS'){
	$server = 'gz';
}
else if ($site == 'FG' || $site == 'SJ'){
	$server = 'us';
}

if ($server=='us'){
	$servername='localhost';
	$dbusername='root';
	$dbpassword='goodjob2008';
}elseif ($server=='gz'){
	$servername='localhost';
	$dbusername='root';
	$dbpassword='goodjob2008';
}else{
	$servername='localhost';
	$dbusername='root';
	$dbpassword='goodjob2008';
}
$dbname='feeweb';
$db=@mysql_connect($servername,$dbusername,$dbpassword) or die("Can not connect mysql database." . mysql_error());
mysql_select_db($dbname,$db) or die("Could not select database");
?>