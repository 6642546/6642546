<?php
error_reporting(0);
require "functions.php";

$part_number = $_GET['part_number'];
$user_name = $_GET['user_name'];
$site = $_GET['site'];

if ($site == 'FG' or $site == 'SJ'){
	date_default_timezone_set('PDT');
	$create_date = date("Y-m-d  G:i:s",time());
} else
{
	$create_date = date("Y-m-d  G:i:s",time()+28800);
}
$due_date = $_POST['due_date'];
$turn_time = $_POST['turn_time'];
$hc = $_POST['hc'];
$cust_id = $_POST['cust_id'];
$tlg_wc = $_POST['tlg_wc'];
$mfg_wc = $_POST['mfg_wc'];
$pt = $_POST['pt'];
$cust_pn = $_POST['cust_pn'];
$cust_name = $_POST['cust_name'];

$arrayData = $_POST['arrayData'];
$arrayTitle = $_POST['arrayTitle'];
$inputData = split(',',$arrayData);
$inputTitle = split(',',$arrayTitle);


if (!$due_date){
	//echo "{success:false,message:\"This part number has no due date in Colt. Please check it.\"}";
	//exit;
	$query = "insert into buildability (part_number,sequence_number,status,create_date,create_user_name,manufacturing_due_date,site_name) 
		  values ('$part_number','1','O','$create_date','$user_name',NULL,'$site')";
} else {
	$query = "insert into buildability (part_number,sequence_number,status,create_date,create_user_name,manufacturing_due_date,site_name) 
		  values ('$part_number','1','O','$create_date','$user_name','$due_date','$site')";
}

require "../config.php";
require "mysql_conn.php";


//echo "$query";
if (mysql_query($query,$db)){
		//setting status....
		$part_number_id = getId($part_number,$db);

		//update input data:
		$m_query = 'insert into eng_area_entry_data (part_number,part_number_id,feild_title_name,feild_value,site_name) values ';
		for ($i=0;$i<count($inputData);$i++){
			$value = $inputData[$i];
			$title = addslashes($inputTitle[$i]);
			$m_query .="($part_number,$part_number_id,'$title','$value','$site'),";
		}
		$m_query =rtrim($m_query,',');
		//echo $m_query;
		update_input_data($part_number,$part_number_id,$site,$title,$value,$create_date,$user_name,$db,$m_query);
		//
		$mysql = 'select * from eng_area';
		$req=@mysql_query($mysql,$db);
		while ($return = mysql_fetch_array($req)){
			$eng_code = $return['eng_area_code'];
			if ($eng_code!='GN' && $eng_code!='HD'){
				$this_status = 'NN';
				if ($eng_code == 'DRN' || $eng_code == 'SD'){
					$this_status = 'NN';
					$reason ='';
				} else {
					require "core.php";
				}
				//echo $eng_code . " - " . $this_status;
				//setStatus($part_number,$part_number_id,$eng_code,$this_status,$db,$reason);
				InsertStatus($part_number,$part_number_id,$eng_code,$this_status,$db,$reason,$create_date,$user_name);
			}
		}
		$final_status = getStatus($part_number,$part_number_id,$db);
		set_last_update($part_number,$part_number_id,$db,$final_status);
		updateColt($part_number,$part_number_id,$db,$site,'');
		create_plating_date($part_number_id,'',$db);
		$subs= getSubParts($part_number,$site);
		
			if (count($subs)){
				for ($i=0;$i<count($subs);$i++){
					create_plating_date($part_number_id,$subs[$i],$db);
				}
								
			}

		
		//send email here:
		require "email.php";

		$head = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
					<head>
					<style>
					<!-- 
					.list{
						width:70%;
						line-height:20px;
						background-color:White;
						border-color:#AAAAAA; 
						border-width:1px; 
						border-style:solid; 
						border-collapse:collapse;
					}
					.list td{
						font-size:12px;
						border-right:1px dotted #ccc;
						border-bottom:1px dotted #ccc;
						overflow:hidden;
						padding:0;
						margin:0;
						empty-cells:show;
						text-align:center;
							}   
					.list th{
						border-right:1px dotted #ccc;
						font-size:12px;
						font-weight:bold;
						background:#fafafa repeat-x left bottom;
						border-bottom:1px dotted #ccc;
						/*border-top:1px dotted #fff;*/
							
					}

					.status {
						width:100%;
						text-align:center;
					}

					.status td{
						border:2px outset white;
						width:25px;
						float:left;
						font-weight: bold;

					}
					-->
					</style>
					</head>
					';

		$part_number_id = getId($part_number,$db);
		$final_status = getStatus($part_number,$part_number_id,$db);

		$final_status = str_ireplace('span','td',$final_status);

		$final_status = substr($final_status,0,20). '<table cellspacing=0><tr>'.substr($final_status,20,strlen($final_status)).'</tr></table>';

		$body = "<body><table class='list'><tr><th>Part Number</th><th>Customer P/N</th><th>Turn Time</th><th>HC</th><th>Cust ID</th><th>TLG WC</th><th>MFC WC</th><th>PT</th><th>Due Date</th><th>Status</th></tr><tr><td>$part_number</td><td>$cust_pn</td><td>$turn_time</td><td>$hc</td><td>$cust_id</td><td>$tlg_wc</td><td>$mfg_wc</td><td>$pt</td><td>$due_date</td><td>$final_status</td></tr></table><h3><a href='$webaddress?site=$site&action=buildability&part_number=$part_number&id=$part_number_id'> Click here to view details</a></h3><div>Please do not 'Reply' to this email.</div>";

		$end = '</body></html>';
		$Subject='New Buildability has been created for '.$part_number.' Owning Customer:'.$cust_name;
		sendMail($site,'email_new_list',$part_number,$db,$user_name,$head.$body.$end,$Subject);
		
		echo "{\"success\":true,\"id\":\"$part_number_id\",\"pt\":\"$pt\",\"message\":\"Buildability of $part_number has been created successfully.\"}";
		}
		else{
		echo "{\"success\":false,\"message\":\"Failed to create Buildability. Please try it again.\"}";
	}

mysql_close($db);
?>