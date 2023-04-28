<?php
error_reporting(0);

$page = $_POST['page'];
$rp = $_POST['rows'];
$sortname = $_POST['sort'];
$squery = strtoupper($_POST['query']);
$qtype = $_POST['qtype'];
$action = $_GET['action'];
$site = $_GET['site'];


function getSpecResult($site,$i,$squery,$qtype){
	require("oracle_conn.php");
	$query = "select rownum,item_name,description,priority,'$site' as site
						from items i
						,spec
					where i.item_id=spec.item_id
						and spec.revision_id=i.LAST_CHECKED_IN_REV
						and i.DELETED_IN_GRAPH is null
						and spec.OBSOLETE=0";
	if ($squery) $query .= " and upper($qtype) like '%$squery%'";

	//echo $query;
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$my_json .= "\n{";
		$my_json .= '"item_name":"'.$row['ITEM_NAME'].'"';

		$description =  str_ireplace('"',"'",$row['DESCRIPTION']);
		$description =	preg_replace('/\r|\n/', '', $description);

		$my_json .= ',"description":"'.$description .'"';
		$my_json .= ',"priority":"'.addslashes($row['PRIORITY']).'"';
		$my_json .= ',"site":"'.addslashes($row['SITE']).'"';
		$my_json .= "},";
		$i++;
		}
		//$my_json = str_ireplace('\"',"'",$my_json);
		oci_close($conn);
		return array("json" => $my_json, "count" => $i);
}


function getInPlanResult($site,$i,$squery,$qtype){
	require("oracle_conn.php");
	$query = "select rownum,i.item_name,part.name,job.num_layers,job.mrp_name,site.site_name
						from items i
						,part
						,job
						,site
					where i.item_type=2
						and i.item_id=job.item_id
						and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=part.item_id
						and job.revision_id=part.revision_id
						and rownum<=10
						and job.odb_site_id=site.site_id";
	if ($squery) $query .= " and (upper($qtype) like '%$squery%' or part.name like '%$squery%')";

	//echo $query;
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$my_json .= "\n{";
		$my_json .= '"item_name":"'.$row['ITEM_NAME'].'"';
		$my_json .= ",'mrp_name':'".addslashes($row['MRP_NAME'])."'";
		$my_json .= ",'name':'".addslashes($row['NAME'])."'";
		$my_json .= ",'num_layers':'".addslashes($row['NUM_LAYERS'])."'";
		$site_name = $row['SITE_NAME'];
		if ($site_name == 'MerixFG') $site_name = "FG";
		if ($site_name == 'MerixSJ') $site_name = "SJ";
		if ($site_name == 'VIA_GZ') $site_name = "GZ";
		if ($site_name == 'VIA_ZS') $site_name = "ZS";
		$my_json .= ',"site":"'.$site_name.'"';
		$my_json .= "},";
		$i++;
		}

		//$my_json = str_ireplace('\"',"'",$my_json);
		oci_close($conn);
		return array("json" => $my_json, "count" => $i);
}


if ($action == 'specification')
{
		$i = 0;
		$json = "";
		$json .= "{\n";
		//$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$r_text =  getSpecResult("HY",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$r_text =   getSpecResult("SJ",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$r_text =   getSpecResult("GZ",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$json = rtrim($json,',');
		$json .= "]\n";
		$json .= ",\"totalCount\": $i";
		$json .= "}";
		//$json = str_ireplace("'",'"',$json);
		echo $json;

} else if ($action == 'inplan'){
	
	$i = 0;
		$json = "";
		$json .= "{\n";
		//$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$r_text =  getInPlanResult("HY",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$r_text =   getInPlanResult("SJ",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$r_text =   getInPlanResult("GZ",$i,$squery,$qtype);
		$json .= $r_text["json"];
		$i = $r_text["count"];
		$json = rtrim($json,',');
		$json .= "]\n";
		$json .= ",\"totalCount\": $i";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;
}
?>