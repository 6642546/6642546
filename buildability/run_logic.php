<?php
$site = $_POST['site'];
require "functions.php";
$part_number = $_GET['part_number'];
$part_number_id = $_POST['part_number_id'];
$user_name = $_POST['user_name'];
if ($site == 'FG' or $site == 'SJ'){
	date_default_timezone_set('PDT');
	$date = date("Y-m-d  G:i:s",time());
} else
{
	$date = date("Y-m-d  G:i:s",time()+28800);
}

try
{
require "mysql_conn.php";
$mysql = 'select * from eng_area';
		$req=@mysql_query($mysql,$db);
		while ($return = mysql_fetch_array($req)){
			$eng_code = $return['eng_area_code'];
			if ($eng_code!='GN' && $eng_code!='HD'){
				$this_status = 'NN';
				if ($eng_code == 'DRN' || $eng_code == 'SD'){
					$this_status = 'NN';
				} else {
					require "core.php";
				}
				//UpdateStatus($part_number,$part_number_id,$eng_code,$this_status,$db,$reason);
				InsertStatus($part_number,$part_number_id,$eng_code,$this_status,$db,$reason,$date,$user_name);
			}
		}
		$final_status = getStatus($part_number,$part_number_id,$db);
		set_last_update($part_number,$part_number_id,$db,$final_status);
		echo "{\"success\":true}";
		} catch (Exception $e) {
			echo "{\"success\":false,\"message\":\"".$e->getMessage()."\"}";
			exit;
		}
	mysql_close($db);	
?>