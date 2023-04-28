<?php
error_reporting(0);

$part_number = $_GET['part_number'];
$part_number_id = $_POST['part_number_id'];
$status=$_POST['status'];
$user_name = $_POST['user_name'];
$date = date("Y-m-d  G:i:s",time()+28800);
$site = $_POST['site'];

switch ($status){
	case "Completed":
		$status = "C";
		break;
	case "Open":
		$status = "O";
		break;
	default:
		$status="O";
}

require "mysql_conn.php";


$query = "update buildability set status = '$status',close_date='$date',close_user_name='$user_name' where id = '$part_number_id'";

//echo "$query";
if (mysql_query($query,$db)){
		echo "{\"success\":true}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to change status. Please try it again.\"}";
	} 
mysql_close($db);
?>