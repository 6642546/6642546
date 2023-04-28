<?php
error_reporting(0);
$part_number_id = $_GET['part_number_id'];
$section_name = $_GET['section_name'];
$note_number=$_POST['note_number'];
$note = $_POST['note'];
$date = date("Y-m-d  G:i:s",time()+28800);
$site = $_POST['site'];

require "mysql_conn.php";

function getEngCode($section_name,$db){
	$my_query = "select eng_area_code from eng_area where eng_area_name='$section_name'";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	return $result[0];
}


$query = "update buildability_note set  note_text = '$note',update_date='$date' where part_number_id = '$part_number_id' and eng_area_code='".getEngCode($section_name,$db)."' and		      note_number=$note_number";
//echo "$query";
if (mysql_query($query,$db)){
		echo "{\"success\":true}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to save note. Please try it again.\"}";
	} 
mysql_close($db);
?>