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
	$site=$_GET['site'];

	require "mysql_conn.php";

	$query = "select * from buildability where site_name='$site'";

	if ($part_number) $query = $query . " and part_number like '%$part_number%'";
	if ($status) {$query = $query . " and status = '$status'";}
	else {
		$query = $query . " and status = 'O'";
	}

	if ($date_from and $date_to){
		$query = $query . " and MANUFACTURING_DUE_DATE >= '$date_from 00:00:00' and MANUFACTURING_DUE_DATE <='$date_to 59:50:50'";
	} else if ($date_from) {
		$query = $query . " and MANUFACTURING_DUE_DATE >= '$date_from 00:00:00'";
	} else if ($date_to){
		$query = $query . " and MANUFACTURING_DUE_DATE <= '$date_to 00:00:00'";
	}

	if ($group_name) $query.=" and exists (select * from eng_area_status eas where buildability.part_number=eas.part_number and buildability.id=eas.part_number_id and eas.eng_area_code='$group_name' and eas.status ='RR' and buildability.status = '$status' and eas.id=(select max(id) from eng_area_status where part_number_id=eas.part_number_id and eas.eng_area_code=eng_area_code) )";

	//echo $query;
	
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

	require("oracle_conn.php");

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
		$my_query = file_get_contents("queries/bbty_dispatch.sql");
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		return $row;
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
	$req=@mysql_query($query,$db);




		header("Expires: Mon, 26 Jul 2012 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");
		$json = "";
		$json .= "{\n";
		//$json .= "page: $page,\n";
		$json .= "\"total\": \"$total\",\n";
		$json .= "\"rows\": [";
		$rc = false;
		//while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			while ($return =mysql_fetch_array($req)){
					$row = getColtData($return['part_number'],$conn);
					if ($rc) $json .= ",";
					$json .= "\n{";
					$json .= "\"id\":\"".$return['id']."\"";
					$json .= ",\"part_number\":\"".$return['part_number']."\"";
					$json .= ",\"trun_time\":\"".$row['SERVICE_LEVEL']."\"";
					$json .= ",\"hc\":\"".$row['HOLD_FLAG']."\"";
					//$json .= ",'pc':'".$row['']."'";
					$json .= ",\"cust_id\":\"".$row['CUSTOMER_ID']."\"";
					$json .= ",\"tlg_wc\":\"".$row['TLG_WC_ID']."\"";
					$json .= ",\"mfg_wc\":\"".$row['MFG_WC_ID']."\"";
					//$json .= ",'ctl':'".$row['']."'";
					$json .= ",\"pt\":\"".$row['PT']."\"";
					$json .= ",\"cust_pn\":\"".$row['CUSTOMER_PART_NUMBER']."\"";
					$json .= ",\"due_date\":\"".$return['manufacturing_due_date']."\"";
					//$json .= ",'product_tech':'"."SHIRLEY"."'";
					$json .= ",\"status\":\"".preg_replace ('/"/','\'',$return['eng_area_status'])."\"";
					//getStatus($return['part_number'],$db)
					$json .= "}";
					$rc = true;
			//}
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;

oci_close($conn);
mysql_close($db);
?>