<div style="padding:5px;">
<table border="0" cellspace="0" cellpadding="0" style="BORDER-COLLAPSE:collapse ;" width="100%">
<style>
	<!-- 
		/* InPlan Job attribute css   */
		.inplan_attri_hd{
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

		.inplan_attri_td{
			font: 14px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			font-style:italic;
		}
	-->
</style>

<?php
$site = $_GET['site'];
$job_name = $_GET["job_name"];
require("oracle_conn.php");

$pre_job_name = substr($job_name,0,6);

$query = "select i.item_name,job.mrp_name
		  from items i,job,job_da
		  where i.item_type=2
		  and i.item_id=job.item_id
		  and job.revision_id=i.last_checked_in_rev
		  and job.item_id=job_da.item_id
		  and job.revision_id=job_da.revision_id
		  and (i.item_name = '".$job_name."'  or i.item_name = job_da.down_rev_)";


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

while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
	$i++;
	echo "<tr><td colspan=4 class='inplan_attri_hd'>"."#".$i."    ".$row['ITEM_NAME']."<td></tr>";
	echo "<tr class='inplan_attri_tr'><td width=25%>Job Name</td><td width=25% class='inplan_attri_td'><a href='../index.php?site=".$site."&action=inplan&job_name=".$row['ITEM_NAME']."&data=Job Attributes' target='_top'>".$row['ITEM_NAME']."</a></td><td width=25%>Mrp Name</td><td width=25% class='inplan_attri_td'>".$row['MRP_NAME']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>ECN History</td><td class='inplan_attri_td' colspan=3></td></tr>";
} 

?>
</table>
</div>