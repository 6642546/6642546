<?php
error_reporting(0);
try {
	require "functions.php";
	$part_number_id = $_POST['part_number_id'];
	$part_number = $_GET['part_number'];
	$value = addslashes($_POST['value']);
	$title = addslashes($_POST['title']);
	$site = $_POST['site'];
	$user_name = $_POST['user_name'];
	
	if ($site == 'FG' or $site == 'SJ'){
		date_default_timezone_set('PDT');
		$date = date("Y-m-d  G:i:s",time());
	} else
	{
		$date = date("Y-m-d  G:i:s",time()+28800);
	}
	
	require "mysql_conn.php";
	
	if ($part_number && !$part_number_id){
		$part_number_id = getId($part_number,$db);
	}

	$title = rtrim($title,':');

	update_input_data($part_number,$part_number_id,$site,$title,$value,$date,$user_name,$db,'');

	echo "{\"success\":true}";

} catch (Exception $e){
	echo "{\"success\":false,\"message\":\"".$e->getMessage()."\"}";
}
mysql_close($db);
?>