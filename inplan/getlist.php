<?php
session_start();
error_reporting(0);
/*$wsid='gzrac.ttm.com/PRD1';
$wdbusername='INPLAN';
$wdbpassword='qPWSvG4B';*/
$wsid='capgzrac.ttm.com/pdb';
$wdbusername='INPLAN';
$wdbpassword='qP!WSvG4B12!';
$wERP_conn = oci_connect($wdbusername, $wdbpassword, $wsid,'utf8');
$sql="select 
	  d50.pkey,d50.bom_ptr,
	  d50.BRANCH_CODE,
	  d50.customer_part_number,d50.cp_rev,
	  case d50.BRANCH_CODE when 'ZS' then '2'|| substr(d50.cp_rev,2,2) when 'F1' then '6'||substr(d50.cp_rev,2,2) else d50.cp_rev end as revision,
	  d50.customer_part_desc,
	  b.cust_code,
	  d50.end_cust_part as customer_part_pn_d50,
	  case d50.bdct_app_status when 0 then '已上网' when 1 then '待编写' when 2 then '已编写' when 3 then '已内审' when 4 then '已审核' when 5 then '已退回' else '未定义' end mi_status
	 from data0050 D50,DATA0010 B 
    Where D50.CUSTOMER_PTR=B.PKEY 
	  AND length(customer_part_number)>=10 and length(cp_rev)=3 and customer_part_number not like 'zDEL%'
	  and replace(d50.customer_part_number,substr(d50.customer_part_number,-2),'-3'||substr(d50.customer_part_number,-2))=";//'A180744A-302'
$page = $_POST['page'];
$rp = $_POST['rows'];
$sortname = $_POST['sort'];
$squery = strtoupper($_POST['query']);
$qtype = $_POST['qtype'];
$action = $_GET['action'];
$site = $_GET['site'];
$data =  $_GET['data'];
$filterType =  $_GET['filterType'];
$filterVal =  $_GET['filterVal'];

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
		case "MI":
			$site_id = 4;
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
	$query_gz="select  rownum,i.item_name,job_da.customer_Pn_,Customer.customer_name,endcustomer.customer_code
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
						and i.root_id>=1
                        and length(item_name)=12 
						and substr(item_name,1,1) in ('A','P','Q','T','E','C','D')";
	
	if (substr($squery,0,3) == "750" || substr($squery,0,3) == "950") {
		if($site=="HY") {
			$query_750_head .=",job_da.read_me_||(select issue.creation_text from issue where job_root_id=i.root_id and issue.creation_text like '作废%' and rownum=1) read_me_";
		}
		$query = $query_750_head . $query_750;
		if ($site == "HY" || $site == "HZ") $where .= " and job.mrp_name is not null and length(i.item_name)>=9 and length(i.item_name)<=11";
		if($site == "HY") { $where .= " and job_da.job_approved_by_ is not null";}
		$where .= " and process.mrp_name like '%$squery%'";
	} elseif (substr($squery,0,3) == "740") {
		$query = "select rownum,part_da.TOOL_NUMBER_ item_name
						   ,part.name
						   ,job.num_layers
						   ,job.mrp_name
						   ,itm.ITEM_NAME || ' - ' || m.mrp_rev_description read_me_
					from items i
							,job
							,job_da
							,part
							,part_da
							,bom_tool bt
							,bom_component bc
						,items ist
							,links
							,process
							,items iprocess
						,items itm
						,material m
					where i.item_type=2
						  and i.item_id=job.item_id
						  and job.revision_id=i.last_checked_in_rev
						  and job.item_id=part.item_id
						  and job.revision_id=part.revision_id
						  and job.item_id=job_da.item_id
						  and job.revision_id=job_da.revision_id
						  and part_da.item_id=job_da.item_id
						  and part_da.revision_id=job_da.revision_id
						  and ist.item_type=8
						  and iprocess.item_type=7
						  and itm.item_type=12
							and i.root_id=ist.root_id
							and bt.item_id=ist.item_id
							and bt.revision_id=ist.last_checked_in_rev
							and ist.DELETED_IN_GRAPH is null
							and bc.item_id=bt.item_id
							and bc.revision_id=bt.revision_id
							and bc.component_type=2
						AND i.root_id=iprocess.root_id
							AND iprocess.deleted_in_graph IS NULL
							AND ist.DELETED_IN_GRAPH IS NULL
							AND iprocess.item_id=process.item_id
							AND process.revision_id=iprocess.last_checked_in_rev
							AND links.link_type=14
							and process.proc_subtype in (1001,26,27,28,29)
							AND links.ITEM_ID=iprocess.item_id
						and links.TO_GRAPH is null
							AND links.points_to=bt.item_id
						and bc.RESOURCE_ITEM_ID = itm.item_id
						and bc.RESOURCE_REVISION_ID =m.revision_id
						and part_da.TOOL_NUMBER_ is not null
						and m.item_id=itm.item_id";
		$where .= " and itm.ITEM_NAME  like '%$squery%'";
		if($site == "HY") { $where .= " and job_da.job_approved_by_ is not null";}
	} else {
		if($site=="HY") {
			$query_head .=",job_da.read_me_||(select issue.creation_text from issue where job_root_id=i.root_id and issue.creation_text like '作废%' and rownum=1) read_me_";
		}
		$query = $query_head . $query;
		if ($site=="GZ" || $site=="ZS") $query=$query_gz;
		if ($site == "HY" || $site == "HZ") { $where .= " and job.mrp_name is not null and length(i.item_name)>=9 and length(i.item_name)<=11"; }
		if($site == "HY") { $where .= " and job_da.job_approved_by_ is not null";}
		//if($site == "SJ" or $site =="FG" or $site =="MI") { $where .= " and (job.mrp_name like '81%' or job.mrp_name like '005%' or job.mrp_name like '950%')";}
		if ($site=="GZ"){
				if ($filterType=='jb'){
                  $where .= " and upper(i.item_name) like '%$filterVal%'"; 
				}elseif($filterType=='cp'){
					$where .= " and upper(job_da.customer_Pn_) like '%$filterVal%'"; 
				}else{
					$where .= " and upper(endcustomer.customer_code) like '%$filterVal%'"; 
				}
		}else{
			if ($qtype == "item_name"){
				$where .= " and (upper(i.item_name) like '%$squery%' or job.mrp_name like '%$squery%' or part.name like '%$squery%')" ;
				//if ($site=="GZ" || $site=="ZS") $where .= " or upper(job_da.customer_Pn_) like '%$squery%' or upper(endcustomer.customer_code) like '%$squery%'";
				//$where .=")";
				} else {
				 if ($squery) $where .= " and upper($qtype) like '%$squery%'";
			}
		}
	}
 
	
		
	//$where .= " and rownum<50";
	
	$query = $query . $where;
    

	//echo $query ;
	$stid = oci_parse($conn, "select count(*) from ($query)");
    $r = oci_execute($stid, OCI_DEFAULT);
	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		$total = $row[0];
	}
	//$total = 50;
	

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
		$ispe=$_SESSION['ispe'];
        $ispe=$_SESSION['PEr'];
		$isTrue=false;
	   if ($ispe==1){
			$isTrue=true;	
		}
		
		if($isTrue==false){
		   while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
				
				
				  $stid1 = oci_parse($wERP_conn , $sql."'".$row['ITEM_NAME']."'");
					 // echo $sql."'".$row['ITEM_NAME']."'</br>";
					  $r1 = oci_execute($stid1, OCI_DEFAULT);
					  $wStatus='';
					  while ($row1 = oci_fetch_array($stid1, OCI_RETURN_NULLS)) {			          
						   $wStatus=$row1['MI_STATUS'];
						   if ($rc) $json .= ",";
				$json .= "\n{";
					  
					  $json .= "'item_name':'".$row['ITEM_NAME']."'";
					  $json .= ",'customer_pn_':'".addslashes($row['CUSTOMER_PN_'])."'";
					  $json .= ",'customer_name':'".addslashes($row['CUSTOMER_NAME'])."'";
					  $json .= ",'customer_code':'".addslashes($row['CUSTOMER_CODE'])."'";
					 $json .= ",'status':'".addslashes($wStatus)."'";
				 
			
				
				$json .= "}";
				$rc = true;}
		  }
	}else{
  
			while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			if ($rc) $json .= ",";
			$json .= "\n{";
			 if ($site=="GZ" || $site=="ZS"){
				  $stid1 = oci_parse($wERP_conn , $sql."'".$row['ITEM_NAME']."'");
				 // echo $sql."'".$row['ITEM_NAME']."'</br>";
				  $r1 = oci_execute($stid1, OCI_DEFAULT);
				  $wStatus='';
				  while ($row1 = oci_fetch_array($stid1, OCI_RETURN_NULLS)) {			          
					   $wStatus=$row1['MI_STATUS'];
					   //echo $wStatus;
				  }
				  $json .= "'item_name':'".$row['ITEM_NAME']."'";
				  $json .= ",'customer_pn_':'".addslashes($row['CUSTOMER_PN_'])."'";
				  $json .= ",'customer_name':'".addslashes($row['CUSTOMER_NAME'])."'";
				  $json .= ",'customer_code':'".addslashes($row['CUSTOMER_CODE'])."'";
				 $json .= ",'status':'".addslashes($wStatus)."'";
			 } else{
				 $json .= "'item_name':'".$row['ITEM_NAME']."'";
				 $json .= ",'mrp_name':'".addslashes($row['MRP_NAME'])."'";
				 $json .= ",'name':'".addslashes($row['NAME'])."'";
				 $json .= ",'num_layers':'".addslashes($row['NUM_LAYERS'])."'";
			 }
			if($site == "HY") {
				$order   = array("\r\n", "\n", "\r");
				$replace = '';
				$read_me=str_replace($order, $replace, $row['READ_ME_']);

				$json .= ",'read_me':'<span class=\'r_info\'>".str_replace('<br />','--',str_replace('\r\n','',str_replace('，',',',str_replace('。','.',addslashes(nl2br($read_me))))))."</span>'";
			}
			$json .= "}";
			$rc = true;
			}
			}
		$json .= "]\n";
		$json .= ",\"totalCount\": $total";
		$json .= "}";
		$json = str_ireplace("'",'"',$json);
		echo $json;
		}


oci_close($conn);
?>