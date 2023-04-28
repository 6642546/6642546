<?php
$site = $_POST['site'];
require "functions.php";


$part_number = $_GET['part_number'];
$user_name= $_POST['user_name'];
try {

require("oracle_conn.php");
$my_query = file_get_contents("queries/bbty_dispatch.sql");
$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
$stid = oci_parse($conn, $my_query);
$r = oci_execute($stid, OCI_DEFAULT);
$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
$due_date = $row['MANUFACTURING_DUE_DATE'];
$turn_time = $row['SERVICE_LEVEL'];
$hc = $row['HOLD_FLAG'];
$cust_id = $row['CUSTOMER_ID'];
$tlg_wc = $row['TLG_WC_ID'];
$mfg_wc = $row['MFG_WC_ID'];
$pt = $row['PT'];
$cust_pn = $row['CUSTOMER_PART_NUMBER'];
$cust_name = $row['OWNING_CUSTOMER_NAME'];
$date = date("Y-m-d  G:i:s",time()+28800);

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

		require "../config.php";
		require "mysql_conn.php";
		$part_number_id = getId($part_number,$db);
		$final_status = getStatus($part_number,$part_number_id,$db);

		$final_status = str_ireplace('span','td',$final_status);

		$final_status = substr($final_status,0,20). '<table cellspacing=0><tr>'.substr($final_status,20,strlen($final_status)).'</tr></table>';

		$body = "<body><table class='list'><tr><th>Part Number</th><th>Customer P/N</th><th>Turn Time</th><th>HC</th><th>Cust ID</th><th>TLG WC</th><th>MFC WC</th><th>PT</th><th>Due Date</th><th>Status</th></tr><tr><td>$part_number</td><td>$cust_pn</td><td>$turn_time</td><td>$hc</td><td>$cust_id</td><td>$tlg_wc</td><td>$mfg_wc</td><td>$pt</td><td>$due_date</td><td>$final_status</td></tr></table><h3><a href='$webaddress?site=$site&action=buildability&part_number=$part_number&id=$part_number_id'> Click here to view details</a></h3><div>Please do not 'Reply' to this email.</div>";

		$end = '</body></html>';
		
	
			$Subject='New Buildability has been created for '.$part_number.' Owning Customer:'.$cust_name;
			sendMail($site,'email_new_list',$part_number,$db,$user_name,$head.$body.$end,$Subject);
			$email_send_note = 'By '.$user_name.' at '.$date;
			$my_query = "update buildability set email_send_note='$email_send_note' where part_number='$part_number' and id='$part_number_id'";
			mysql_query($my_query,$db);
			echo "{\"success\":true,\"send_note\":\"$email_send_note\",\"message\":\"Creation email has been sent successfully.\"}";
		} catch (Exception $e) {
			echo "{\"success\":false,\"message\":\"".$e->getMessage()."\"}";
			exit;
		}
	mysql_close($db);	
?>