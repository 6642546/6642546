<?php
if (!$site){
	if (isset($_GET['site'])){
		$site = $_GET['site'];
	}  else {
		$site = 'FG';
	}
}

if ($site == 'HY' || $site == 'HZ'){
	$server = 'asia';
} else if ($site == 'GZ' ||$site == 'ZS'){
	$server = 'gz';
}
else if ($site == 'FG' || $site == 'SJ'){
	$server = 'us';
}

if ($server=='us'){
	//$sid='oraclep.merix.com:1521/coltcorp.merix.com';
	//$sid='oraclep.merix.com:1528/coltdev1';
	$sid='oraclet.merix.com:1540/coltprep';
	$dbusername='COLTREAD_FORE';
	$dbpassword='COLTREAD_FORE';
}elseif ($server=='gz'){
	$sid='oraclep.merix.com/coltcorp.merix.com';
	$dbusername='COLTREAD_FORE';
	$dbpassword='COLTREAD_FORE';
}else{
	$sid='oraclep.merix.com/coltcorp.merix.com';
	$dbusername='COLTREAD_FORE';
	$dbpassword='COLTREAD_FORE';
}
$conn = oci_connect($dbusername, $dbpassword, $sid,'utf8'); 
?>