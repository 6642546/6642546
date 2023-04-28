<?php
error_reporting(0);

$page = $_POST['page'];
$rp = $_POST['rows'];
$sortname = $_POST['sort'];
$squery = strtoupper($_POST['query']);
$qtype = $_POST['qtype'];
$action = $_GET['action'];
$site = $_GET['site'];
$data =  $_GET['data'];


if ($action) {require("../oracle_conn.php");}

if ($data == 'TQ'){
	$tq_db=@mysql_connect('localhost','root','goodjob2008') or die("Can not connect mysql database." . mysql_error());
	mysql_select_db('tq',$tq_db) or die("Could not select database");
	$query = "select * from main where id is not null";

	if ($squery) $where .= " and upper($qtype) like '%$squery%'";
	$query = $query . $where;

	$my_req=@mysql_query("select count(*) from ($query) temp",$tq_db);
	while ($return = @mysql_fetch_array($my_req)){
		$total = $return[0];
	}
	if (!$sortname) $sortname = 'customer_pn';		
		$sort = "ORDER BY $sortname";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;
		$query = $query . " $sort";
		$query  = $query . " limit " . ($page-1) * $rp ." , ". $page * $rp;
		
		$my_req=@mysql_query($query,$tq_db);
		$json = "";
		$json .= "{\n";
		$json .= "\"page\": $page,\n";
		$json .= "\"list\": [";
		$rc = false;
		while ($return = @mysql_fetch_array($my_req)){
			if ($rc) $json .= ",";
			$json .= "\n{";
			$json .= "'customer_pn':'".$return[2]."'";
			$json .= ",'customer_rev':'".""."'";
			$json .= ",'site_tooling_pn':'".$return[3]."'";
			$json .= ",'viaystems_pn':'".$return[4]."'";
			$json .= ",'received_date':'".$return[5]."'";
			$json .= "}";
			$rc = true;
		}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;


} else if ($action == 'spec')
{
	$query = "select rownum,item_name,description,priority
						from items i
						,spec
					where i.item_id=spec.item_id
						and spec.revision_id=i.LAST_CHECKED_IN_REV
						and i.DELETED_IN_GRAPH is null
						and spec.OBSOLETE=0";

	$query = $query . " and upper(item_name) like '%$spec_name%'";

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
		$json .= ",'description':'".addslashes($row['DESCRIPTION'])."'";
		$json .= ",'priority':'".addslashes($row['PRIORITY'])."'";
		$json .= "}";
		$rc = true;
		}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;



} else if ($action == 'inplan'){
	$site_id = 0;
	switch ($site){
		case "HY":
			$site_id = 2;
			break;
		case "HZ":
			$site_id = 3;
			break;
		case "FG":
			$site_id = 2;
			break;
		case "SJ":
			$site_id = 1;
			break;
		case "GZ":
			$site_id = 1;
			break;
		case "ZS":
			$site_id = 2;
			break;
	}

	$query_head = "select rownum,i.item_name,part.name,job.num_layers,job.mrp_name";

	$query = " from items i
						,part
						,job
						,job_da 
						where i.item_type=2
						and i.item_id=job.item_id
						--and job.mrp_name is not null
						and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=job_da.item_id
						and job.revision_id=job_da.revision_id
						and job.item_id=part.item_id
						and job.revision_id=part.revision_id
						and job.odb_site_id=$site_id";

	$query_750_head = "select distinct i.item_name,part.name,job.num_layers,process.mrp_name";
	$query_750 = " from items i
                        ,part
                        ,job
						,job_da
                        ,items iprocess
                        ,process 
						where i.item_type=2
                        and i.item_id=job.item_id
                        --and job.mrp_name is not null
                        and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=job_da.item_id
						and job.revision_id=job_da.revision_id
                        and job.item_id=part.item_id
                        and job.revision_id=part.revision_id
                        and job.odb_site_id=2
                        and iprocess.root_id=i.root_id
                        and iprocess.item_id=process.item_id
                        and process.revision_id=iprocess.last_checked_in_rev
                        and IPROCESS.DELETED_IN_GRAPH is null
                        and PROCESS.PROC_SUBTYPE in (27,28,29,1001)";
	$query_gz="select rownum,i.item_name,job_da.customer_Pn_,Customer.customer_name,endcustomer.customer_code
                        from items i
						,part
						,job
						,job_da 
						,customer
						,customer endcustomer
						where i.item_type=2
						and i.item_id=job.item_id
						and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=job_da.item_id
						and job.customer_id=customer.cust_id
						and job.end_customer_id=endcustomer.cust_id
						and job.revision_id=job_da.revision_id
						and job.item_id=part.item_id
						and job.revision_id=part.revision_id
						and job.odb_site_id=1
                        and length(item_name)=12";
	
	if (substr($squery,0,3) == "750" || substr($squery,0,3) == "950") {
		if($site=="HY") {
			$query_750_head .=",job_da.read_me_";
		}
		$query = $query_750_head . $query_750;
		if ($site == "HY" || $site == "HZ") $where .= " and job.mrp_name is not null and length(i.item_name)>=9 and length(i.item_name)<=11";
		if($site == "HY") { $where .= " and job_da.job_approved_by_ is not null";}
		$where .= " and process.mrp_name like '%$squery%'";
	} else {
		if($site=="HY") {
			$query_head .=",job_da.read_me_";
		}
		$query = $query_head . $query;
		if ($site=="GZ") $query=$query_gz;
		if ($site == "HY" || $site == "HZ") { $where .= " and job.mrp_name is not null and length(i.item_name)>=9 and length(i.item_name)<=11"; }
		if($site == "HY") { $where .= " and job_da.job_approved_by_ is not null";}
		if ($qtype == "item_name"){
			$where .= " and (i.item_name like '%$squery%' or job.mrp_name like '%$squery%' or part.name like '%$squery%')" ;
		} else {
			if ($squery) $where .= " and upper($qtype) like '%$squery%'";
		}
		
	}

	
	
	$where .= " and rownum<50";
	
	$query = $query . $where;
    

	//echo $query ;
	$stid = oci_parse($conn, "select count(*) from ($query)");
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$total = $row[0];
	}

		if (!$sortname) {
			$sort = "ORDER BY i.item_name desc";
		} else $sort = "ORDER BY $sortname";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;

		//if ($squery) {
			$query = $query . " $sort";
		//} else {
		//	$query = $query . " order by $sortname desc";
		//}
		

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
		 if ($site=="GZ"){
		      $json .= "'item_name':'".$row['ITEM_NAME']."'";
		      $json .= ",'customer_pn_':'".addslashes($row['CUSTOMER_PN_'])."'";
		      $json .= ",'customer_name':'".addslashes($row['CUSTOMER_NAME'])."'";
		      $json .= ",'customer_code':'".addslashes($row['CUSTOMER_CODE'])."'";
		 } else{
			 $json .= "'item_name':'".$row['ITEM_NAME']."'";
		     $json .= ",'mrp_name':'".addslashes($row['MRP_NAME'])."'";
		     $json .= ",'name':'".addslashes($row['NAME'])."'";
		     $json .= ",'num_layers':'".addslashes($row['NUM_LAYERS'])."'";
		 }
		if($site == "HY") {
			$json .= ",'read_me':'".addslashes($row['READ_ME_'])."'";
		}
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