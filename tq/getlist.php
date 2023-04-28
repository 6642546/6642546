<?php
error_reporting(0);

$page = $_POST['page'];
$rp = $_POST['rows'];
$sortname = $_POST['sort'];
$squery = strtoupper($_POST['query']);
$qtype = $_POST['qtype'];
$action = $_GET['action'];
$site = $_GET['site'];
$global = $_GET['global'];


if ($action == 'tq'){
	$tq_db=@mysql_connect('10.1.176.180','kylej','kylej') or die("Can not connect mysql database." . mysql_error());
	mysql_select_db('feeweb',$tq_db) or die("Could not select database");
	if (isset($global)) {$query = "select id,customer_name,customer_pn,customer_rev,site_tooling_pn,viasystems_pn,max(received_date) received_date,site_name
									from tq_main where id is not null ";} 
									else $query = "select id,customer_name,customer_pn,customer_rev,site_tooling_pn,viasystems_pn,max(received_date) received_date,site_name,end_cust_name from tq_main where site_name='$site'";

	if ($squery) {
		if ($qtype == "customer_pn") {
			$where .= " and (upper(customer_pn) like '%$squery%' or upper(end_cust_name) like '%$squery%' or upper(customer_name) like '%$squery%' or upper(site_tooling_pn) like '%$squery%' or upper(viasystems_pn) like '%$squery%')";
		} else {
			$where .= " and upper($qtype) like '%$squery%'";
		}
	
	}
	$query = $query . $where;

	$query .= " group by customer_name,customer_pn,customer_rev,site_tooling_pn,viasystems_pn,site_name ";

	$my_req=@mysql_query("select count(*) from ($query) temp",$tq_db);
	while ($return = @mysql_fetch_array($my_req)){
		$total = $return[0];
	}

	if (!$sortname) { $sort = 'ORDER BY received_date desc'; } else $sort = "ORDER BY $sortname";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;
		$query = $query . " $sort";
		$query  = $query . " limit " . ($page-1) * $rp ." , ". $page * $rp;
		
		$query = "select * from (".$query.") a limit " .$rp;
		
		$my_req=@mysql_query($query,$tq_db);
		$json = "";
		$json .= "{\n";
		$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$rc = false;
		while ($return = @mysql_fetch_array($my_req)){
			if ($rc) $json .= ",";
			$json .= "\n{";
			$json .= "'customer_name':'".$return[1]."'";
			$json .= ",'customer_pn':'".$return[2]."'";
			$json .= ",'customer_rev':'".trim($return[3])."'";
			$json .= ",'site_tooling_pn':'".$return[4]."'";
			$json .= ",'viasystems_pn':'".$return[5]."'";
			$json .= ",'received_date':'".$return[6]."'";
			$json .= ",'site_name':'".$return[7]."'";
			$json .= ",'end_cust_name':'".$return[8]."'";
			$json .= "}";
			$rc = true;
		}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;
} 
?>