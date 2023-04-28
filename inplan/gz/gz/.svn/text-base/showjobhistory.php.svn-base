<div style="padding:5px;">
<table border="0" cellspace="0" cellpadding="0" style="BORDER-COLLAPSE:collapse ;" width="100%">
<style>
	<!-- 
		/* InPlan Job attribute css   */
		.inplan_attri_hd{
			font: 14px/19px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			background:#ADADAD;
			color:white;
			height:25px;
			font-weight:bold;
			padding-left:10px;
			border-bottom:5px solid red;
		}

		.inplan_attri_tr{
			font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			line-height:200%;
			font-size:14px;
		}

		.inplan_attri_tr td{
			padding-left:10px;
		}

		.inplan_attri_td{
			font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			/*font-style:italic;*/
			font-weight:bold;
		}
	-->
</style>

<?php
$site = $_GET['site'];
$job_name = $_GET["job_name"];
$lang = $_GET['lang'];
if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/allsites";
		$logo_dir = "images";
		$image_dir = ".";
		require("oracle_conn.php");
		require_once("lang.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = ".";
		$image_dir = "../..";
		$logo_dir = "stackup";
		require("../../oracle_conn.php");
		require_once("../../lang.php");
	}

$pre_job_name = substr($job_name,0,7);


$query = "select i.item_name,job.mrp_name
		  from items i,job,job_da
		  where i.item_type=2
		  and i.item_id=job.item_id
		  and job.revision_id=i.last_checked_in_rev
		  and job.item_id=job_da.item_id
		  and job.revision_id=job_da.revision_id
		  and i.item_name like '$pre_job_name%'";


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
	echo "<tr class='inplan_attri_tr'><td width=25%>".getLang('Job Name',$lang)."</td><td width=25% class='inplan_attri_td'><a href='../../index.php?site=".$site."&action=inplan&job_name=".$row['ITEM_NAME']."&data=Job Attributes' target='_top'>".$row['ITEM_NAME']."</a></td><td width=25%>".getLang('Mrp Name',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['MRP_NAME']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>".getLang('ECN History',$lang)."</td><td class='inplan_attri_td' colspan=3>".$row['ECN_HISTORY_']."</td></tr>";
} 

?>
</table>
</div>