 <style>
	table {
		font: 14px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
	}
 </style>
<div style="padding:0px;width:746px; border:1px solid black;margin:0px;">
<br/><div class='header'><center><b><font size=+2>CAM Instructions&nbsp;</font></b></center><br></div>
<?php

	$site = $_GET['site'];
	$job =  $_GET["job_name"];

	$sqltrav="select job_name
			      ,cil.description
			      , ci_set_name
			      , cin.ci_sequential_index
			      ,cin.sequential_index
			      , cin.pre_instantiated_string 
			from " . $schema . "RPT_JOB_CAM_INSTRUCTION_LIST cil 
			  inner join " . $schema . "ci_note cin 
			  on cil.item_id = cin.item_id 
			  and cil.revision_id = cin.revision_id 
			  and cil.sequential_index = cin.ci_sequential_index 
			where job_name = '" . $job . "'
			order by ci_set_name, cin.ci_sequential_index, cin.sequential_index, ci_set_name";
	
	if (file_exists("oracle_conn.php")){
		require("oracle_conn.php");
	
	} else {
		require("../../oracle_conn.php");
	}
	$rstrav = oci_parse($conn, $sqltrav);
	oci_execute($rstrav, OCI_DEFAULT);

	$headerinfo = "<table border=0>
	<tr><td colspan=2><b><u><i>Job Details</i></u></b><td width=6></td><td colspan=2><b><u><i>Production Details</i></u></b></tr>
	<tr><td><b>Job Name:</b></td><td>@@jobname@@</td><td width=6></td><td><b>Panel Size:</b></td><td>@@panelsize@@</td></tr>
	<tr><td><b><b>MRP Name:</b></td><td>@@mrpname@@</td><td width=6></td><td><b><b>Number Of Layers:</b></td><td>@@layers@@</td></tr>
	<tr><td><b><b>ITAR:</b></td><td>@@itar@@</td><td width=6></td><td><b><b>Number Of Pcbs:</b></td><td>@@pcbs@@</td></tr>
	<tr><td><b><b> </b></td><td> </td><td width=6></td><td><b><b>Job Type:</b></td><td>@@jobtype@@</td></tr></TABLE>";
	$tableinfo = "<br>&nbsp;
	<table BORDER COLS=1 WIDTH='100%'; ><tr><td BGCOLOR='#58ACFA'; WIDTH='90%';><b>@@section@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	@@WC@@:</b></td></tr>@@tabledata@@</table>";
	

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

	$ordernum = -1;
	$travindex = -1;
	$tmpInfo = "";
	$wcnotes = "";
	$tmpInfo=$tmpInfo . $headerinfo;
	while(oci_fetch($rstrav)){
		//		$tmpInfo = $tmpInfo . "<p style='page-break-before: always'>";
		if(oci_result($rstrav, 2)!==$travindex){
			if($travindex <> -1){
				$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
			}
			$wcnotes = "";
			$tmpInfo = $tmpInfo . $tableinfo;
			$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 3),$tmpInfo);
			$tmpInfo = str_replace("@@WC@@",oci_result($rstrav, 2),$tmpInfo);
			$tmpInfo = str_replace("@@discription@@",oci_result($rstrav, 6),$tmpInfo);
			$travindex=oci_result($rstrav, 2);
		}
		$wcnotes = $wcnotes . "<TR><TD>" . oci_result($rstrav, 6) . "</TD></TR>";
	}
	
	if($travindex <> -1){
		$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
	}
	
	echo $tmpInfo;

?>
</div>