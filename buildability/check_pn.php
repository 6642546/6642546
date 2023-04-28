<?php
error_reporting(0);
$part_number = $_GET['part_number'];
$site = $_POST['site'];
// check if the part number is in COLT.
require("oracle_conn.php");

$my_query = "select count(*) from part where trim(part_number) ='$part_number'";
$stid = oci_parse($conn, $my_query);
$r = oci_execute($stid, OCI_DEFAULT);
$row = oci_fetch_array($stid, OCI_RETURN_NULLS);

if ($row[0]>0){
	$my_query = file_get_contents("queries/bbty_dispatch.sql");
	$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
	//echo $my_query;
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
} else {
	echo "{\"success\":false,\"message\":\"$part_number does not exist in COLT, please verify.\"}";
	exit;
}

	require "mysql_conn.php";
	$my_query ="select count(*) from buildability where part_number='$part_number' and status='O'";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	if ($result[0]){
		echo "{\"success\":false,\"message\":\"$part_number already exists in Buildability, can not re-create it.\"}";
	} else	echo "{\"success\":true,\"due_date\":\"$due_date\",\"turn_time\":\"$turn_time\",\"hc\":\"$hc\",\"cust_id\":\"$cust_id\",\"tlg_wc\":\"$tlg_wc\",\"mfg_wc\":\"$mfg_wc\",\"pt\":\"$pt\",\"cust_pn\":\"$cust_pn\",\"cust_name\":\"$cust_name\"}";
mysql_close($db);
oci_close($conn);
?>