<?php

function getEngCode($section_name,$db){
	$my_query = "select eng_area_code from eng_area where eng_area_name='$section_name'";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	return $result[0];
}


function setStatus($part_number,$part_number_id,$eng_code,$status,$db,$reason){
	$reason = addslashes($reason);
	$query = "insert into eng_area_status (part_number,part_number_id,eng_area_code,status,reason) 
			 values ('$part_number','$part_number_id','$eng_code','$status','$reason')";	
	if (mysql_query($query,$db)){
		
	} 

}

function UpdateStatus($part_number,$part_number_id,$eng_code,$status,$db,$reason){
	$reason = addslashes($reason);
	$query = "update eng_area_status set status='$status',reason='$reason' where part_number_id=$part_number_id and eng_area_code='$eng_code'";	
	if (mysql_query($query,$db)){
		
	} 

}

function InsertStatus($part_number,$part_number_id,$eng_code,$status,$db,$reason,$date,$user_name){
	$reason = addslashes($reason);
	$query = "insert into eng_area_status (part_number,part_number_id,eng_area_code,status,update_date,update_user_name,reason) 
			 values ('$part_number','$part_number_id','$eng_code','$status','$date','$user_name','$reason')";
	if (mysql_query($query,$db)){
		
	} 

}

function set_last_update($part_number,$part_number_id,$db,$status){
	$my_query = "update buildability set eng_area_status='$status' where part_number='$part_number' and id='$part_number_id'";
	if (mysql_query($my_query,$db)){
		return true;}
		else{
		return false;
	} 
}

function getStatus($part_number,$part_number_id,$db){
		$_status ="<div class=\"status\">";
		$my_query = "select eas.status,eas.eng_area_code,ea.display_sequence from eng_area_status eas,eng_area ea where part_number ='".trim($part_number)."' and eas.part_number_id='$part_number_id' and eas.eng_area_code=ea.eng_area_code and eas.id = (select max(id) from eng_area_status where part_number_id=eas.part_number_id and eas.eng_area_code=eng_area_code) order by ea.display_sequence";
		$my_req=@mysql_query($my_query,$db);
		while ($return = @mysql_fetch_array($my_req)){
			if ($return['status'] =='RR') $_status .= "<span style=\"background:red;\">".$return['eng_area_code']."</span>";
			if ($return['status'] =='NN') $_status .= "<span style=\"background:gray;\">".$return['eng_area_code']."</span>";
			if ($return['status'] =='EC') $_status .= "<span style=\"background:yellow;\">".$return['eng_area_code']."</span>";
			if ($return['status'] =='PC') $_status .= "<span style=\"background:yellowgreen;\">".$return['eng_area_code']."</span>";
			if (!$return['status']) $_status .= "<span>".$return['eng_area_code']."</span>";
		}
	
		$_status .="</div>";
		return $_status; 
}

function getId($part_number,$db){
	$my_query = "select id from buildability where part_number='$part_number' and status='O'";
	$my_req=@mysql_query($my_query,$db);
	$return = @mysql_fetch_array($my_req);
	return $return[0];
}

function updateColt($part_number,$part_number_id,$db,$site,$new_status){
	if ($new_status){
		$status = $new_status;
	} else {
	// what's the worst case?
	$query = "select eas.status from eng_area_status eas where eas.part_number_id='$part_number_id' and eas.id = (select max(id) from eng_area_status where part_number_id=eas.part_number_id and eas.eng_area_code=eng_area_code)";
	$my_req=@mysql_query($query,$db);
	$status = 'G';	//R Y G A
	while ($return = @mysql_fetch_array($my_req)){
		$return_status = $return['status'];
		if ($return_status == 'RR'){
			$status = 'R';
			break;
		}
		if (($status == 'G' or $status == 'Y' or $status == 'A' )&& $return_status == 'EC'){
			$status = 'Y';
		}
		if (($status == 'G')&& $return_status == 'PC'){
			$status = 'G';
		}
		if (($status == 'G' or $status == 'A' )&& $return_status == 'NN'){
			$status = 'G';
		}
	}
	}
	

	require "oracle_conn.php";
	$stmt = oci_parse($conn,"begin P_PART_BBTY_STATUS_TRIGGER(:part_number, :status); end;"); 

	oci_bind_by_name($stmt,":part_number",$part_number); 
	oci_bind_by_name($stmt,":status",$status); 
	
	$executed = oci_execute($stmt);
	if (!$executed) {
        $error = oci_error($stmt);
		echo "{\"success\":false,\"message\":\"Oracle error:".str_ireplace("\n","",str_ireplace('"',"'",$error['message'])).".\"}";
		exit;
    }
	oci_close($conn);

}

function update_input_data($part_number,$part_number_id,$site,$title,$value,$date,$user_name,$db,$m_query){
	if (!$m_query){
		$count=@mysql_query("select count(*) from eng_area_entry_data where part_number_id='$part_number_id' and feild_title_name='$title' and site_name='$site'",$db);	
		$count_nu=@mysql_fetch_array($count);
		$exist =$count_nu[0];
		if ($exist >0){
			$query ="update eng_area_entry_data set feild_value='$value',update_date='$date',updated_by='$user_name' where part_number_id='$part_number_id' and feild_title_name='$title' and site_name='$site'";
		}

		if (!$query) $query = "insert into eng_area_entry_data (part_number,part_number_id,feild_title_name,feild_value,site_name) 
			  values ('$part_number','$part_number_id','$title','$value','$site')";
	
	} else {
		
		$query = $m_query;
	}
	
	//echo "$query";
	if (!mysql_query($query,$db)){
			echo "{\"success\":false,\"message\":\"Failed to save data. Please try it again.\"}";
			exit;
		}
}

function update_plating_data($part_number_id,$plating_program_name,$sub_part,$override,$override_id,$db){
		//echo "select count(*) from plating_program where part_number_id='$part_number_id' and plating_program_name='$plating_program_name' and sub_part='$sub_part'";
		$count=@mysql_query("select count(*) from plating_program where part_number_id='$part_number_id' and plating_program_name='$plating_program_name' and sub_part='$sub_part'",$db);	
		$count_nu=@mysql_fetch_array($count);
		$exist =$count_nu[0];
		if ($exist >0){
			$query ="update plating_program set override='$override',override_id='$override_id' where part_number_id='$part_number_id' and plating_program_name='$plating_program_name' and sub_part='$sub_part'";
		}

		if (!$query) $query = "insert into plating_program (part_number_id,plating_program_name,sub_part,override,override_id) 
			  values ('$part_number_id','$plating_program_name','$sub_part','$override','$override_id')";

	
	//echo "$query";
	if (!mysql_query($query,$db)){
			echo "{\"success\":false,\"message\":\"Failed to save data. Please try it again.\"}";
			exit;
		}
}

function create_plating_date($part_number_id,$sub_part,$db)
{
	$query = "insert into plating_program (part_number_id,plating_program_name,sub_part) 
			  values ('$part_number_id','Tin','$sub_part'),
					 ('$part_number_id','Gold: Nickel/Gold','$sub_part'),
					 ('$part_number_id','Gold: Copper','$sub_part'),
					 ('$part_number_id','Gold 2','$sub_part'),
					 ('$part_number_id','Cu Buildup Strike','$sub_part'),
					 ('$part_number_id','Strike','$sub_part'),
					 ('$part_number_id','Strike 2nd Pass','$sub_part'),
					 ('$part_number_id','Strike - Special','$sub_part'),
					 ('$part_number_id','Pattern Button-Spc','$sub_part'),
					 ('$part_number_id','VF Strike','$sub_part'),
					 ('$part_number_id','VF Button','$sub_part'),
					 ('$part_number_id','CuF Strike','$sub_part'),
					 ('$part_number_id','Front etch comp','$sub_part'),
					 ('$part_number_id','Back etch comp','$sub_part')
			  ";

	//echo "$query";
	if (!mysql_query($query,$db)){
			echo "{\"success\":false,\"message\":\"Failed to save data. Please try it again.\"}";
			exit;
		}

}

function getSubParts($part_number,$site){
	require "oracle_conn.php";
	if (file_exists("queries/get_subs.sql")){
			$my_query = file_get_contents("queries/get_subs.sql");
			$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
			$stid = oci_parse($conn, $my_query);
			$r = oci_execute($stid, OCI_DEFAULT);
			$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
			//$row['SUBS'] = '75011863~75011864';
			if (trim($row['SUBS'])){
				//echo trim($row['SUBS']);
				$sub_text= split('~',trim($row['SUBS']));
			} 

	}

	return $sub_text;

	}


?>