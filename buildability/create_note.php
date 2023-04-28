<?php
error_reporting(0);
$part_number = $_GET['part_number'];
$part_number_id = $_POST['part_number_id'];
$user_name = $_POST['user_name'];
$section_name = $_GET['section_name'];
$note = addslashes($_POST['note']);
$site = $_POST['site'];
if ($site == 'FG' or $site == 'SJ'){
	date_default_timezone_set('PDT');
	$date = date("Y-m-d  G:i:s",time());
} else
{
	$date = date("Y-m-d  G:i:s",time()+28800);
}

require "mysql_conn.php";

function getEngCode($section_name,$db){
	$my_query = "select eng_area_code from eng_area where eng_area_name='$section_name'";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	return $result[0];
}

$eng_code =getEngCode($section_name,$db);
if ($section_name =='General'){
	$count=@mysql_query("select count(*) from buildability_note where part_number_id='$part_number_id' and eng_area_code='$eng_code'",$db);	
	$count_nu=@mysql_fetch_array($count);
	$exist =$count_nu[0];
	if ($exist >0){
		$query ="update buildability_note set note_text='$note',update_date='$date',update_user_name='$user_name' where part_number_id='$part_number_id' and eng_area_code='GN'";
	}
}

if (!$query) $query = "insert into buildability_note (part_number,part_number_id,sequence_number,eng_area_code,note_text,update_date,update_user_name) 
		  values ('$part_number','$part_number_id','1','".getEngCode($section_name,$db)."','$note','$date','$user_name')";
//echo "$query";
if (mysql_query($query,$db)){
		echo "{\"success\":true}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to save note. Please try it again.\"}";
	} 
mysql_close($db);
?>