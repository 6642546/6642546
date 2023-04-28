<?php
	error_reporting(0);

	$page = $_POST['page'];
	$rp = $_POST['rows'];
	$sortname = $_POST['sort'];
	$sortorder = $_POST['order'];
	$group_name = strtoupper($_POST['group_name']);
	$part_number =  strtoupper($_POST['part_number']);
	$date_from =  $_POST['date_from'];
	$date_to =  $_POST['date_to'];
	$status = $_POST['status'];
	$current_date = date("Y-m-d",time()+28800);

	$rp=12;
	$page=1;
	$sortname='MANUFACTURING_DUE_DATE';

	require "buildability/mysql_conn.php";

	$query = "select * from buildability where part_number is not null";

	if ($part_number) $query = $query . " and part_number like '%$part_number%'";
	if ($status) $query = $query . " and status = '$status'";

	if ($date_from and $date_to){
		$query = $query . " and MANUFACTURING_DUE_DATE >= '$date_from 00:00:00' and MANUFACTURING_DUE_DATE <='$date_to 59:50:50'";
	} else if ($date_from) {
		$query = $query . " and MANUFACTURING_DUE_DATE >= '$date_from 00:00:00'";
	} else if ($date_to){
		$query = $query . " and MANUFACTURING_DUE_DATE <= '$date_to 00:00:00'";
	}
	
	$count=@mysql_query("select count(*) from ($query) temp",$db);	
	$count_nu=@mysql_fetch_array($count);
	$total =$count_nu[0];

	if (!$sortorder) $sortorder = 'asc';
				
	$sort = "ORDER BY $sortname $sortorder,part_number $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 10;

	$start = (($page-1) * $rp);

	$query = $query . " $sort";

	$limit = " limit $start, $rp";

	$query = $query . " $limit";

	require("buildability/oracle_conn.php");

	function isNewBuild($part_number,$db){
		$my_query = "select count(*) from buildability where part_number ='".trim($part_number)."'";
		$count=@mysql_query($my_query,$db);	
		$count_nu=@mysql_fetch_array($count);
		//echo "aa".$count_nu[0].$my_query ;
		if ($count_nu[0]==0) {return true;}
		else
		{
			return false;
		}
	}

	function getColtData($part_number,$conn){
		$my_query = file_get_contents("buildability/bbty_dispatch.sql");
		$my_query .=" and WOR.part_number='$part_number'";
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		return $row;
	}

	function getStatus($part_number,$db){
		$_status ="<div class='_status'>";
		$my_query = "select * from eng_area_status where part_number ='".trim($part_number)."'";
		$my_req=@mysql_query($my_query,$db);
		while ($return = @mysql_fetch_array($my_req)){
			if ($return['status'] =='RR') $_status .= "<span style='background:red;'>".$return['eng_area_code']."</span>";
			if ($return['status'] =='NN') $_status .= "<span style='background:gray;'>".$return['eng_area_code']."</span>";
			if ($return['status'] =='EC') $_status .= "<span style='background:yellow;'>".$return['eng_area_code']."</span>";
			if ($return['status'] =='PC') $_status .= "<span style='background:yellowgreen;'>".$return['eng_area_code']."</span>";
			if (!$return['status']) $_status .= "<span>".$return['eng_area_code']."</span>";
		}
	
		$_status .="</div>";
		return $_status; 
	}

/*
	require("oracle_conn.php");

	$query = file_get_contents("bbty_dispatch.sql");

	if ($part_number) $query = $query . " and WOR.part_number like '%$part_number%'";
	if ($date_from and $date_to){
		$query = $query . " and WOR.MANUFACTURING_DUE_DATE between to_date('$date_from 00:00:00','mm/dd/yyyy hh24:mi:ss') and to_date('$date_to 23:59:59','mm/dd/yyyy hh24:mi:ss')";
	} else if ($date_from) {
		$query = $query . " and WOR.MANUFACTURING_DUE_DATE >= to_date('$date_from 00:00:00','mm/dd/yyyy hh24:mi:ss')";
	} else if ($date_to){
		$query = $query . " and WOR.MANUFACTURING_DUE_DATE <= to_date('$date_to 00:00:00','mm/dd/yyyy hh24:mi:ss')";
	}
	//echo $query;
	$stid = oci_parse($conn, "select count(*) from ($query)");
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$total = $row[0];
	}

		if (!$sortname) $sortname = 'WOR.MANUFACTURING_DUE_DATE';
		if (!$sortorder) $sortorder = 'asc';
				
		$query = $query . $where;
		$sort = "ORDER BY $sortname $sortorder";

		if (!$page) $page = 1;
		if (!$rp) $rp = 16;
		$start = (($page-1) * $rp);

		$query = $query . " $sort";

		$query = "select * from 
					(select rownum r, a.* from 
						(select * from  ($query)
					) a 
				 where rownum < $page * $rp 
				) 
				where r > ($page - 1) * $rp";


		
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);

		
		//mysql database data.
		$mysql_query = "select part_number from buildability where status<>'C'";
		
		//$build_pn=;
*/
	//echo $query;
	$req=@mysql_query($query,$db);


		//header("Expires: Mon, 26 Jul 2012 05:00:00 GMT" );
		//header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		//header("Cache-Control: no-cache, must-revalidate" );
		//header("Pragma: no-cache" );
		//header("Content-type: text/x-json");
		$json = "";
		$json .= "<table class='list'><tr><th>Part Number</th><th>Turn Time</th><th>HC</th><th>Cust ID</th><th>TLG WC</th><th>MFG WC</th><th>PT</th><th>Due Date</th><th>Status</th></tr>";
		$rc = false;
		//while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			while ($return =mysql_fetch_array($req)){
					//$row = getColtData($return['part_number'],$conn);
					if ($rc) $json .= "</tr>";
					$json .= "<tr>";
					$json .= "<td>".$return['part_number']."</td>";
					$json .= "<td>".$row['SERVICE_LEVEL']."</td>";
					$json .= "<td>".$row['HOLD_FLAG']."</td>";
					//$json .= ",'pc':'".$row['']."'";
					$json .= "<td>".$row['CUSTOMER_ID']."</td>";
					$json .= "<td>".$row['TLG_WC_ID']."</td>";
					$json .= "<td>".$row['MFG_WC_ID']."</td>";
					//$json .= ",'ctl':'".$row['']."'";
					$json .= "<td>".$row['PT']."</td>";
					$json .= "<td>".$return['manufacturing_due_date']."</td>";
					//$json .= ",'product_tech':'"."SHIRLEY"."'";
					$json .= "<td>".preg_replace ('/"/','\'',$return['eng_area_status'])."</td>";
					//getStatus($return['part_number'],$db)
					$rc = true;
			//}
		}
		$json .= "</table>";
		echo $json;

oci_close($conn);
mysql_close($db);
?>