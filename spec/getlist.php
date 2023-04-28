<?php
error_reporting(0);

$page = $_POST['page'];
$rp = $_POST['rows'];
$sortname = $_POST['sort'];
$squery = strtoupper($_POST['query']);
$qtype = $_POST['qtype'];
$action = $_GET['action'];
$site = $_GET['site'];


if ($action) {require("../oracle_conn.php");}

if ($action == 'specification')
{
	$query_header = "select rownum,item_name,description,priority";
	$site_filter = "'FG', 'ALL'";
	if($site == "SJ" or $site == "MI") {
		$site_filter = "'SJ','MI', 'ALL', 'Not Set'";
		$query_header .=",decode(spec_da.ITAR_FLAG_,0,'No','Yes') ITAR_FLAG_";
	}
	$query = $query_header ." from items i
						,spec
						,spec_da
					where i.item_type=20 and i.item_id=spec.item_id
						and spec.revision_id=i.LAST_CHECKED_IN_REV
						and i.DELETED_IN_GRAPH is null
						and spec.item_id=spec_da.item_id
						and spec.revision_id=spec_da.revision_id
						and spec.OBSOLETE=0";
	if($site == "SJ" or $site == "MI" or $site == "FG") {
		$query .= " and (select value from enum_values where enum = spec_da.spec_site_ and enum_type = 2030) in ($site_filter)";
	}
						
	$query = $query . " and upper(item_name) like '%$spec_name%'";

	if ($squery) {
		if (substr($squery,0,7)=='PREFIX-') {
			$where .= " and upper($qtype) like '".substr($squery,7,100)."%'";
		} else {
			$where .= " and upper($qtype) like '%$squery%'";
		}
	   
	}
	$query = $query . $where;
	$stid = oci_parse($conn, "select count(*) from ($query)");
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$total = $row[0];
	}

		if (!$sortname) $sortname = 'item_name';
				
		$sort = "ORDER BY $sortname";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;


		$query = $query . " $sort";

		$query = "select * from 
					(select rownum r, a.* from 
						(select * from  ($query)
					) a 
				 where rownum < $page * $rp 
				) 
				where r > ($page - 1) * $rp";


		//echo $query;
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);

		$json = "";
		$json .= "{\n";
		$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$rc = false;
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "'item_name':'".$row['ITEM_NAME']."'";
		$description = preg_replace('/[^\p{L}\s]/u','',$row['DESCRIPTION']);
		$json .= ",'description':'".str_replace("\n", "", addslashes($description)) ."'";
		$json .= ",'priority':'".addslashes($row['PRIORITY'])."'";
		if($site == "SJ" or $site == "MI") {
			$json .= ",'itar_flag_':'".addslashes($row['ITAR_FLAG_'])."'";
		}
		$json .= "}";
		$rc = true;
		}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;



} else if ($action == 'inplan'){
	$query = "select rownum,i.item_name,part.name,job.num_layers,job.mrp_name
						from items i
						,part
						,job
					where i.item_type=2
						and i.item_id=job.item_id
						and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=part.item_id
						and job.revision_id=part.revision_id";

	if ($squery) $where .= " and upper($qtype) like '%$squery%'";
	$query = $query . $where;
	$stid = oci_parse($conn, "select count(*) from ($query)");
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$total = $row[0];
	}

		if (!$sortname) $sortname = 'item_name';
				
		$sort = "ORDER BY $sortname";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;


		$query = $query . " $sort";

		$query = "select * from 
					(select rownum r, a.* from 
						(select * from  ($query)
					) a 
				 where rownum < $page * $rp 
				) 
				where r > ($page - 1) * $rp";


		//echo $query;
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);

		$json = "";
		$json .= "{\n";
		$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$rc = false;
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "'item_name':'".$row['ITEM_NAME']."'";
		$json .= ",'mrp_name':'".addslashes($row['MRP_NAME'])."'";
		$json .= ",'name':'".addslashes($row['NAME'])."'";
		$json .= ",'num_layers':'".addslashes($row['NUM_LAYERS'])."'";
		$json .= "}";
		$rc = true;
		}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;
		}


oci_close($conn);
?>
