<?php
error_reporting(0);
try {
	require "functions.php";
	$part_number_id = $_POST['part_number_id'];
	$plating_program_name = $_POST['plating_program_name'];
	$override = addslashes($_POST['new_value']);
	$override_id = addslashes($_POST['new_id']);
	$site = $_POST['site'];
	$sub_part = $_POST['sub_part'];
	
	require "mysql_conn.php";

	update_plating_data($part_number_id,$plating_program_name,$sub_part,$override,$override_id,$db);

	echo "{\"success\":true}";

} catch (Exception $e){
	echo "{\"success\":false,\"message\":\"".$e->getMessage()."\"}";
}
mysql_close($db);
?>