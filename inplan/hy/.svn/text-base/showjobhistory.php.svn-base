<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<div style="padding:5px;">
<table border="0" cellspace="0" cellpadding="0" style="BORDER-COLLAPSE:collapse ;" width="100%">
<tr><th colspan=4 class="his_th">Job History Report</th></tr>
<style>
	<!-- 
		/* InPlan Job attribute css   */
		.his_th {
			font: 18px "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-weight:bold;
			text-align:center;
			height:30px;
		
		}
		.inplan_attri_hd{
			font: 14px/19px "Helvetica Neue",Helvetica,Arial,sans-serif;
			background:#ADADAD;
			color:white;
			height:25px;
			font-weight:bold;
			padding-left:10px;
			border-bottom:5px solid red;
		}

		.inplan_attri_tr{
			line-height:200%;
			font-size:14px;
			padding-left:10px;
		}

		.inplan_attri_tr td{
			padding-left:10px;
		}

		.inplan_attri_td{
			font: 12px/17px "Helvetica Neue",Helvetica,Arial,sans-serif;
			/*font-style:italic;*/
			font-weight:bold;
			padding-left:10px;
		}
		.inplan_attri_td a{
			color:blue;
			text-decoration :underline ;
		}
	-->
</style>

<?php
$site = $_GET['site'];
$job_name = $_GET["job_name"];
$lang = $_GET['lang'];

if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/hy";
		$logo_dir = "images";
		$image_dir = ".";
		if (!$conn) require("oracle_conn.php");
		require_once("lang.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = ".";
		$image_dir = "../..";
		$logo_dir = "stackup";
		if (!$conn) require("../../oracle_conn.php");
		require_once("../../lang.php");
	}

$pre_job_name = substr($job_name,0,6);

$query = "select i.item_name,job.mrp_name,job_da.ecn_history_,job.obsolete,users.user_name,
		  (select max(R.RELEASE_DATE)
			from items ir
				,release r
			where i.root_id=ir.root_id
			and ir.item_id=r.item_id
			and ir.deleted_in_graph is null) release_date
          from items i,job,job_da,users
          where i.item_type=2
          and i.item_id=job.item_id
          and job.revision_id=i.last_checked_in_rev
          and job.item_id=job_da.item_id
          and job.revision_id=job_da.revision_id
		  and job.ASSIGNED_OPERATOR_ID=users.user_id
		  and job_da.job_approved_by_ is not null
		  and job.mrp_name is not null
          and i.item_name like '$pre_job_name%'
          order by       
          release_date desc,i.item_name desc";


$stid = oci_parse($conn, $query);
if (!$stid) {
  $e = oci_error($conn);
  print htmlentities($e['message']);
  exit;
}

$r = oci_execute($stid, OCI_DEFAULT);
if(!$r) {
  $e = oci_error($stid);
  echo htmlentities($e['message']);
  exit;
}
$i = 0;

$stid_count = oci_parse($conn, "select count(*) from ($query)");
$r = oci_execute($stid_count, OCI_DEFAULT);
if(!$r) {
  $e = oci_error($stid);
  echo htmlentities($e['message']);
  exit;
}

$count = oci_fetch_array($stid_count, OCI_RETURN_NULLS);
$i = $count[0];

while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
	echo "<tr><td colspan=4 class='inplan_attri_hd'><span style='color:green'>"."#".$i."</span>    ".$row['ITEM_NAME']."<td></tr>";
	echo "<tr class='inplan_attri_tr'><td width=25%>".getLang('Job Name',$lang)."</td><td width=25% class='inplan_attri_td'><a href='index.php?site=".$site."&action=inplan&job_name=".$row['ITEM_NAME']."&data=Job Attributes";
	if ($lang) echo "&lang=$lang";
	echo "' target='_top'>".$row['ITEM_NAME']."</a></td><td width=25%>".getLang('Mrp Name',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['MRP_NAME']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td width=25%>".getLang('PT',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['USER_NAME']."</td><td width=25%>".getLang('Date',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['RELEASE_DATE']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>".getLang('ECN History',$lang)."</td><td class='inplan_attri_td' colspan=3>".$row['ECN_HISTORY_']."</td></tr>";
	$i--;
} 

?>
</table>
</div>