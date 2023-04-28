<?php
	error_reporting(0);
	$part_number_id = $_GET['part_number_id'];
	$site = $_POST['site'];

	$red_status = '';
	$yellow_status = '';
	require "mysql_conn.php";
	$my_query ="select * from eng_area_status eas where eas.part_number_id='$part_number_id' and eas.id = (select max(id) from eng_area_status where part_number_id=eas.part_number_id and eas.eng_area_code=eng_area_code)";
	$req=@mysql_query($my_query,$db);	
	while ($result=@mysql_fetch_array($req)){
		if ($result['status'] == 'RR'){
			$red_status .= $result['eng_area_code'] . ',';
		}
		if ($result['status'] == 'EC'){
			$yellow_status .= $result['eng_area_code'] . ',';
		}
	}
	$red_status = rtrim($red_status,',');
	$yellow_status = rtrim($yellow_status,',');



	if ($red_status <>'' or $yellow_status <>''){
		if ($red_status <>'' and $yellow_status <>''){
			echo "{\"success\":false,\"message\":\"Red Engineer Area:$red_status,yellow Engineer Area:$yellow_status\"}";
		} elseif ($red_status <>''){
			echo "{\"success\":false,\"message\":\"Red Engineer Area:$red_status\"}";
		} else {
			echo "{\"success\":false,\"message\":\"Yellow Engineer Area:$yellow_status\"}";
		}
		
	} else	echo "{\"success\":true}";
mysql_close($db);
?>