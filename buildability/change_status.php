<?php
error_reporting(0);
require "functions.php";
$part_number = $_GET['part_number'];
$part_number_id = $_POST['part_number_id'];
$section_name = $_GET['section_name'];
$status=$_POST['status'];
$user_name = $_POST['user_name'];
$site = $_POST["site"];

if ($site == 'FG' or $site == 'SJ'){
	date_default_timezone_set('PDT');
	$date = date("Y-m-d  G:i:s",time());
} else
{
	$date = date("Y-m-d  G:i:s",time()+28800);
}


switch ($status){
	case "Review Required":
		$status = "RR";
		break;
	case "Not Needed":
		$status = "NN";
		break;
	case "Eng Complete":
		$status = "EC";
		break;
	case "PT Completed":
		$status = "PC";
		break;
	default:
		$status="";
}

require "mysql_conn.php";



function set_last_update_2($part_number,$part_number_id,$update_user,$update_date,$db,$status){
	$my_query = "update buildability set update_date='$update_date',update_user_name='$update_user',eng_area_status='$status' where part_number='$part_number' and id='$part_number_id'";
	if (mysql_query($my_query,$db)){
		return true;}
		else{
		return false;
	} 
}


$eng_code =getEngCode($section_name,$db); 


//$count=@mysql_query("select count(*) from eng_area_status where part_number='$part_number' and part_number_id='$part_number_id' and eng_area_code='$eng_code'",$db);	
//$count_nu=@mysql_fetch_array($count);
//$exist =$count_nu[0];

if ($status == 'RR'){

	$reason = 'Status changed to Red by ' . $user_name .' at ' . $date ;
} else $reason ='';

//alwasy insert the status.
//if ($exist==0)
//{
	$query = "insert into eng_area_status (part_number,part_number_id,eng_area_code,status,update_date,update_user_name,reason) 
			 values ('$part_number','$part_number_id','$eng_code','$status','$date','$user_name','$reason')";
//} else
//{
//	$query = "update eng_area_status set status = '$status',update_date='$date',update_user_name='$user_name',reason='$reason' where 
//		 part_number = '$part_number' and part_number_id='$part_number_id' and eng_area_code ='$eng_code'";
//}

//echo "$query";
if (mysql_query($query,$db)){
		set_last_update_2($part_number,$part_number_id,$user_name,$date,$db,getStatus($part_number,$part_number_id,$db));
		updateColt($part_number,$part_number_id,$db,$site,'');
		echo "{\"success\":true}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to change status. Please try it again.\"}";
	} 

mysql_close($db);
?>