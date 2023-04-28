<?php
if ($site == 'HY' or $site == 'HZ'){
	$server = 'asia';
} else if ($site == 'GZ' or $site == 'ZS'){
	$server = 'gz';
}
else if ($site == 'FG' or $site == 'SJ' or $site == 'MI'){
	$server = 'us';
}

if ($server=='us'){
	$sid='//10.5.1.214:1521/sjplanp.viasystems.pri';
	$dbusername='MSJ_CEDB_PROD';
	$dbpassword='cedb';
} elseif ($server=='gz'){
	$sid='//10.120.1.30:1521/inmind';
	$dbusername='VIA_GZ';
	$dbpassword='cedb';
} elseif ($server=='zs'){
	$sid='//10.120.1.30:1521/inmind';
	$dbusername='VIA_ZS';
	$dbpassword='cedb';
} else{
	$sid='//10.65.8.198:1521/hyplanp.viasystems.pri';
	$dbusername='merix_asia';
	$dbpassword='cedb';
}
if(!$conn) $conn = oci_connect($dbusername, $dbpassword, $sid,'utf8'); 
?>