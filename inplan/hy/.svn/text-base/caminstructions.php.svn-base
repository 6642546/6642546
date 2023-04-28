<?php 
	if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/hy";
		$logo_dir = "images";
		if (!$conn) require("oracle_conn.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = ".";
		$logo_dir = "stackup";
		if (!$conn) require("../../oracle_conn.php");
	}
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	#cam_ic {
		font: 14px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
	}
 </style>

<div style="padding:0px;width:786px; border:1px solid black;margin:0px;">
<div class='header'>
<table style="width:100%">
	<tr><td width="175px"><img src="<?php echo $logo_dir ?>/logo.gif" /></td><td style="line-height:35px;"><b><font size=+2>惠州美锐电子科技有限公司</font></b><br/><b><font size=+1>Merix printed Circuit Technology Limited</font></b></td></tr>
	<tr><td></td><td></td></tr>
	<tr><td></td><td style="line-height:35px;"><b><font size=+3>CAM Instructions&nbsp;</font></b></td></tr>
</table>
<br/>
</div>
 
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
	
	$rstrav = oci_parse($conn, $sqltrav);
	oci_execute($rstrav, OCI_DEFAULT);

	$headerinfo = "<table border=0 width=100%>
	<tr><td width=15%><b>P/N:</b><td width=35%><u>@@PN@@</u></td><td width=15%><b>Code:</b><td width=35%><u>@@job@@</u></td></tr></TABLE>";
	$endinfo = "<br/><br/><table border=0 width=100%>
	<tr><td width=15%><b>Prepared by:</b><td width=35%><u>@@user@@</u></td><td width=15%><b>Checked by:</b><td width=35%><u>@@job_approved_by@@</u></td></tr></TABLE>";
	$tableinfo = "<br>&nbsp;
	<table id='cam_ic' WIDTH='100%' ><tr><td style='background-color:#ADADAD;border-bottom:5px solid red;color:white;line-height:24px;' WIDTH='90%'><b><font size=+1>@@WC@@:</font></b></td></tr>@@tabledata@@</table>";
	
	$tmpsql = "select mrp_name,name,users.user_name,job_da.job_approved_by_ from items i,job,part,users,job_da
					where i.item_type=2
					and i.item_id=job.item_id
					and job.revision_id=i.last_checked_in_rev
					and job.item_id=part.item_id
					and users.user_id=job.assigned_operator_id
					and job.revision_id=part.revision_id
					and job.item_id=job_da.item_id
					and job.revision_id=job_da.revision_id
					and i.item_name ='$job'";
	$stid = oci_parse($conn, $tmpsql);
	$r = oci_execute($stid, OCI_DEFAULT);
	$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
	



	$headerinfo = str_replace("@@PN@@",$row[1],$headerinfo);
	$headerinfo = str_replace("@@job@@",$job."(".$row[0].")",$headerinfo);
	$endinfo = str_replace("@@user@@",$row[2],$endinfo);
	$endinfo = str_replace("@@job_approved_by@@",$row[3],$endinfo);

	$ordernum = -1;
	$travindex = -1;
	$tmpInfo = "";
	$wcnotes = "";
	$tmpInfo=$tmpInfo . $headerinfo;
	while(oci_fetch($rstrav)){
		//		$tmpInfo = $tmpInfo . "<p style='page-break-before: always'>";
		if(oci_result($rstrav, 2)!==$travindex){
			// check if there is snap note:
			if ($ci_description){
				if($ci_description == "特殊要求") {
					$tmpInfo .= getspecialNote($conn,$job,$index);
				}
				if (hasImg($job,$ci_description,$conn) == "Yes")
				$tmpInfo .="<img style='padding-left:5px;' src=\"$pre_dir/getpic.php?job_name=$job&ci_description=$ci_description\"/>";
			}
			
			$ci_description = htmlspecialchars(oci_result($rstrav, 2));
			if($travindex <> -1){
				$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
			}
			$wcnotes = "";
			$tmpInfo = $tmpInfo . $tableinfo;
			//$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 3),$tmpInfo);
			$tmpInfo = str_replace("@@WC@@",oci_result($rstrav, 2),$tmpInfo);
			$tmpInfo = str_replace("@@discription@@",oci_result($rstrav, 6),$tmpInfo);
			$travindex=oci_result($rstrav, 2);
			$index = 0;
		}
		$index += 1;
		$wcnotes = $wcnotes . "<TR><TD><span>&nbsp&nbsp&nbsp</span>". $index . ". " . oci_result($rstrav, 6) . "</TD></TR>";
	}
	// check if the last one has img.
	if ($ci_description){
				if (hasImg($job,$ci_description,$conn) == "Yes")
				$tmpInfo .="<img src=\"$pre_dir/getpic.php?job_name=$job&ci_description=$ci_description\"/>";
	}

	

	if($travindex <> -1){
		$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
		$tmpInfo = str_replace("merixlogo","<img src='images/merixlogo.PNG'></img>",$tmpInfo);
	}
	echo $tmpInfo;

	// end
	echo $endinfo;

	function getspecialNote($conn,$job,$index) {
		$result = "";
		$query = "SELECT i.item_name,ns.note_string
					  FROM merix_asia.items i, merix_asia.items its, merix_asia.trav_sect ts, merix_asia.mrp_step ms, merix_asia.note_trav_sect ns
					 WHERE i.item_type=2 
					   AND i.root_id = its.root_id
					   AND its.item_id = ts.item_id
					   AND ts.revision_id = its.last_checked_in_rev
					   AND its.deleted_in_graph IS NULL
					   AND ts.mrp_step_item_id = ms.item_id
					   AND ts.mrp_step_revision_id = ms.revision_id
					   AND ts.item_id = ns.item_id
					   AND ts.revision_id = ns.revision_id
					   AND ts.sequential_index = ns.section_sequential_index
					   AND ms.operation_code in ( 'ROUTINPL','BLANINPL')
					   and i.item_name='$job'";
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			//header("Content-type: image/pjpeg");
			if (substr($row['NOTE_STRING'],0,6)!="TOOL #")	
			{	
				$index +=1;
				$result .= "<div><span>&nbsp&nbsp&nbsp&nbsp&nbsp</span>".$index . ". " . $row['NOTE_STRING'] . "</div>";
			}
		}
		return $result;
		
	}

	function hasImg($job,$ci_description,$conn){
		$result="No";
		$query = "select i.item_name
					,ci.description
					,cin.PRE_INSTANTIATED_STRING
					,lob.blob_data
					,ci.SEQUENTIAL_INDEX
			from items i
					,items ici
					,ci_set cs
					,cam_instruction ci
					,ci_note cin
					,ci_snap_note csn
					,snap_note sn
					,rev_controlled_lob lob
			where i.item_type=2 and i.root_id=ici.root_id
			and ici.item_id=cs.item_id
			and ici.last_checked_in_rev=cs.revision_id
			and cs.item_id=ci.item_id
			and cs.revision_id=ci.revision_id
			and ci.item_id=cin.item_id
			and ci.revision_id=cin.revision_id
			and ci.sequential_index=cin.ci_sequential_index
			and ici.DELETED_IN_GRAPH is null
			and ci.item_id=csn.item_id(+)
			and ci.revision_id=csn.revision_id(+)
			and ci.sequential_index=csn.ci_sequential_index(+)
			and (sn.revision_id=(select max(revision_id) from revisions where item_id=sn.item_id)
					or sn.revision_id is null)
			and sn.item_id(+)=csn.snap_note_item_id
			and sn.ANNOTATED_PICTURE=lob.lob_id(+)
			and rownum=1
			and i.item_name='$job'
			and ci.description = '$ci_description'";
		//echo $query;
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			//header("Content-type: image/pjpeg"); 
			if ($row['BLOB_DATA'])	
			{
				$result = "Yes";
				break;
			}
		}
		return $result;
	}
?>
</div>
</html>