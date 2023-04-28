<?php
require "functions.php";
error_reporting(0);
$part_number = $_GET['part_number'];
$part_number_id = $_GET['part_number_id'];
$site = $_POST['site'];

require "mysql_conn.php";

$query = "delete from buildability where id = '$part_number_id'";
//echo "$query";
if (mysql_query($query,$db)){
		$query = "delete from buildability_note where part_number_id = '$part_number_id'";
		mysql_query($query,$db);
		$query = "delete from eng_area_entry_data where part_number_id = '$part_number_id'";
		mysql_query($query,$db);
		$query = "delete from eng_area_status where part_number_id = '$part_number_id'";
		mysql_query($query,$db);
		$query = "delete from plating_program where part_number_id = '$part_number_id'";
		mysql_query($query,$db);

		updateColt($part_number,$part_number_id,$db,$site,'N');
		echo "{\"success\":true,\"message\":\"Buildability deleted successfully.\"}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to delete buildability. Please try it again.\"}";
	} 
mysql_close($db);
?>