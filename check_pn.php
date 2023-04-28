<?php
error_reporting(0);

$action = $_POST['action'];
$site = $_GET['site'];
$kw = strtoupper($_POST['kw']);


if ($action) {require("oracle_conn.php");}

if ($action == 'inplan')
{
	$query = "select i.item_name from items i,job 
				where i.item_type=2
				and i.item_id=job.item_id
				and job.revision_id=i.last_checked_in_rev
				and (upper(i.item_name)='$kw' or job.mrp_name='$kw')";
	$i = 0;
	$stid = oci_parse($conn, $query);
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$i++;
		$job_name = $row[0];
	}

	if ($i == 1) {
		echo "{\"success\":true,\"message\":\"".$job_name."\"}";
	} else {
		echo "{\"success\":false}";
	}

} else if ($action == 'specification') {
	$query = "select item_name
						from items i
						,spec
					where i.item_id=spec.item_id
						and spec.revision_id=i.LAST_CHECKED_IN_REV
						and i.DELETED_IN_GRAPH is null
						and spec.OBSOLETE=0
						and (upper(i.item_name)='$kw' or upper(description) like '$kw')";
	$i = 0;
	$stid = oci_parse($conn, $query);
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$i++;
		$job_name = $row[0];
	}

	if ($i == 1) {
		echo "{\"success\":true,\"message\":\"".$job_name."\"}";
	} else {
		echo "{\"success\":false}";
	}

} else if ($action == 'tq') {
	$tq_db=@mysql_connect('localhost','root','goodjob2008') or die("Can not connect mysql database." . mysql_error());
	mysql_select_db('tq',$tq_db) or die("Could not select database");
	$my_query = "select customer_pn from main where customer_pn ='".trim($kw)."' or site_tooling_pn='".trim($kw)."'";
	$my_req=@mysql_query($my_query,$tq_db);
	$i = 0;
	while ($return = @mysql_fetch_array($my_req)){
		$i++;
		$job_name = $return[0];
	}

	if ($i == 1) {
		echo "{\"success\":true,\"message\":\"".$job_name."\"}";
	} else {
		echo "{\"success\":false}";
	}

}

?>