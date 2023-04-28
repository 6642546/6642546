<html>
 <style>
	table {
		font: 14px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
	}
 </style>
<div style="padding:5px;width:780px;">
<div><center><b><font size=+2>Traveler Routing&nbsp;</font></b></center></div><br>
<?php
	if (!$site) $site = $_GET["site"];
	$job =  $_GET["job_name"];

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
		echo "<script type='text/javascript' src='". $pre_dir_scripts . "/jquery-1.4.4.min.js'></script>";
	}

	include("traveler_query.php");

	$rstrav = oci_parse($conn, $sqltrav);
	oci_execute($rstrav, OCI_DEFAULT);


	$my_query = "select i.item_name
					,job.mrp_name
					,JOB_DA.ITAR_FLAG_
					,JOB.PNL_SIZE_X/1000 || ' x '||JOB.PNL_SIZE_Y/1000 ||' inch' as pnl_size
					,JOB.NUM_LAYERS
					,JOB.NUM_PCBS
					,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='JOB_TYPE' and ev.enum=job.JOB_TYPE) as JOB_TYPE
				from items i
					,job
					,job_da
				where i.item_type=2
				and i.item_id=job.item_id
				and job.revision_id=i.last_checked_in_rev
				and job.item_id=job_da.item_id
				and job.revision_id=job_da.revision_id
				and i.item_name='$job'";
	$stid = oci_parse($conn, $my_query);
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
	$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
	$itar = $row['ITAR_FLAG_'];
	$panelSize = $row['PNL_SIZE'];
	$numLayers = $row['NUM_LAYERS'];
	$numParts = $row['NUM_PCBS'];
	$jobType = $row['JOB_TYPE'];
	$mrpName = $row['MRP_NAME'];



	$headerinfo = "<br><p style='page-break-before: always'>
	<table border=0 width=100%>
	<tr><td colspan=2><b><u><i>Job Details</i></u></b><td width=6></td><td colspan=2><b><u><i>Production Details</i></u></b></tr>
	<tr><td width='15%'><b>Job Name:</b></td><td width='15%'>@@jobname@@</td><td width='1%'></td><td width='20%'><b>Process Name:</b></td><td>@@processname@@</td></tr>
	<tr><td width='15%'><b><b>MRP Name:</b></td><td width='15%'>@@mrpname@@</td><td width='1%'></td><td width='20%'><b>Panel Size:</b></td><td>@@panelsize@@</td></tr>
	<tr><td width='15%'><b><b>ITAR:</b></td><td width='15%'>@@itar@@</td><td width='1%'></td><td width='20%'><b>Number Of Layers:</b></td><td>@@layers@@</td></tr>
	<tr><td width='15%'><b><b> </b></td><td width='15%'> </td><td width='1%'></td><td width='20%'><b>Number Of Pcbs:</b></td><td>@@pcbs@@</td></tr>
	<tr><td width='15%'><b><b> </b></td><td width='15%'> </td><td width='1%'></td><td width='20%'><b>Job Type:</b></td><td>@@jobtype@@</td></tr></TABLE></p><br>";

	$tableinfo = "<br>
	<table width=100%><tr><td style='height:25px;padding-left:10px;background-color:#ADADAD;border-bottom:5px solid red;color:white' WIDTH='90%'><b>@@section@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	@@WC@@:&nbsp;&nbsp;&nbsp;@@discription@@</b></td></tr>@@tabledata@@</table><br>";
	
	$headerinfo = str_replace("@@jobname@@",$job,$headerinfo);
	if($itar == 1){
		$headerinfo = str_replace("@@itar@@","Yes" ,$headerinfo);
	}
	else{
		$headerinfo = str_replace("@@itar@@","No" ,$headerinfo);
	}
	$headerinfo = str_replace("@@panelsize@@",$panelSize,$headerinfo);
	$headerinfo = str_replace("@@layers@@",$numLayers,$headerinfo);
	$headerinfo = str_replace("@@pcbs@@",$numParts,$headerinfo);
	$headerinfo = str_replace("@@jobtype@@",$jobType,$headerinfo);
	$headerinfo = str_replace("@@mrpname@@",$mrpName,$headerinfo);

	$ordernum = "none";
	$travindex = -1;
	$tmpInfo = "";
	$wcnotes = "";
	while(oci_fetch($rstrav)){
		if(oci_result($rstrav, 2)!==$ordernum){
			//if($ordernum > -1){
			//	$tmpInfo = $tmpInfo . "<p style='page-break-before: always'>";
			//}
			$tmpInfo=$tmpInfo . $headerinfo;
			$tmpInfo = str_replace("@@processname@@",oci_result($rstrav, 2),$tmpInfo);
			$ordernum = oci_result($rstrav, 2);
		}
		if(oci_result($rstrav, 6)<>$travindex){
			if($travindex <> -1){
				$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
			}
			$wcnotes = "";
			$tmpInfo = $tmpInfo . $tableinfo;
			$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 6),$tmpInfo);
			$tmpInfo = str_replace("@@WC@@",oci_result($rstrav, 3),$tmpInfo);
			$tmpInfo = str_replace("@@discription@@",oci_result($rstrav, 4),$tmpInfo);
			$travindex=oci_result($rstrav, 6);
		}
		$tmpfix = str_replace(chr(10),"<br>",oci_result($rstrav, 5));
		$wcnotes = $wcnotes . "<TR><TD>" . $tmpfix . "</TD></TR>";
	}
	
	if($travindex <> -1){
		$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
		$tmpInfo = preg_replace("/style='page-break-before: always'/","",$tmpInfo,1);
	}
	
	echo $tmpInfo;

?>
</div>
</html>