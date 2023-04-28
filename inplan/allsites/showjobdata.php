<div style="padding:5px;">
<table border="0" cellspace="0" cellpadding="0" style="BORDER-COLLAPSE:collapse ;" width="100%">
<!--th width="25%" align="left">Field Name</th><th width="25%" align="left">Value</th>
<th width="25%" align="left">Field Name</th><th width="25%" align="left">Value</th -->

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

		.layer_table{
			width:100%;
			font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			border-collapse:collapse;border:solid 1px #99BBDD;
		
		}

		.layer_table th{
			text-align:left;
			border:solid 1px #99BBDD;
			padding-left:5px;
			font-weight:bold;
		}

		.layer_table td{
			border:solid 1px #99BBDD;
			text-align: left;
			padding-left:5px;
		}
	-->
</style>


<?php
$site = $_GET['site'];
$job_name = $_GET["job_name"];
$lang = $_GET['lang'];
require("oracle_conn.php");
require_once("lang.php");


$query = "select i.item_name,
				 c1.customer_name,
				 c2.customer_name end_customer_name,
				 users.user_name,
				 job.mrp_name,
				 job.BOARD_TYPE,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='BOARD_TYPE' and ev.enum=job.BOARD_TYPE) as BOARD_TYPE,
				 job.num_layers,
				 part.name,
				 part.cust_rev_name,
				 job.odb_job_name,
				 users.user_name,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='DELIVERABLE_TYPE' and ev.enum=part.DELIVERABLE_TYPE) as DELIVERABLE_TYPE,
				 job.pnl_size_x,
				 job.pnl_size_y,
				 job_da.sheet_size_x_,
				 job_da.sheet_size_y_,
				 job.num_pcbs,
				 part.pcb_size_x,
				 part.pcb_size_y,
				 part.NUM_PCBS_IN_ARRAY,
				 part_da.AW_REV_,
				 part_da.BP_REV_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='UL_CODE_' and ev.enum=part_da.UL_CODE_) as UL_CODE_,
			     (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SURFACE_FINISH_TYPE_' and ev.enum=job_da.SURFACE_FINISH_) as SURFACE_FINISH_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SM_TYPE_' and ev.enum=job_da.SOLDERMASK_TYPE_) as SOLDERMASK_TYPE_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SM_COLOR_TYPE_' and ev.enum=job_da.SOLDERMASK_COLOR_) as SOLDERMASK_COLOR_,
				 job.NUM_ARRAYS,
				 STACKUP.CUSTOMER_THICKNESS,
                 STACKUP.CUSTOMER_THICKNESS_TOL_PLUS,
                 STACKUP.CUSTOMER_THICKNESS_TOL_MINUS,
                 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='THICKNESS_CALC_METHOD' and ev.enum=STACKUP.THICKNESS_CALC_METHOD) as THICKNESS_CALC_METHOD,
				 job_da.SURFACE_FINISH_THK_MIN_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SELECTIVE_PLATE_TYPE_' and ev.enum=job_da.SELECTIVE_PLATE_) as SELECTIVE_PLATE_,
				 job_da.SELECTIVE_PLATE_THK_MIN_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SIDES_TYPE_' and ev.enum=part_da.PLATED_TIPS_SIDES_) as PLATED_TIPS_SIDES_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='PLATED_TIPS_STYLE_TYPE_' and ev.enum=part_da.PLATED_TIPS_STYLE_) as PLATED_TIPS_STYLE_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='PLATED_TIPS_PLATE_TYPE_' and ev.enum=part_da.PLATED_TIPS_TYPE_) as PLATED_TIPS_TYPE_,
				 part_da.GOLD_TIP_THICKNESS_MIN_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SIDES_TYPE_' and ev.enum=job_da.SOLDERMASK_PEELABLE_SIDES_) as SOLDERMASK_PEELABLE_SIDES_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='PEELABLE_MASK_APPLICATION_' and ev.enum=job_da.PEELABLE_MASK_APPLICATION_) as PEELABLE_MASK_APPLICATION_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='PEELABLE_MASK_MATERIAL_TYPE_' and ev.enum=job_da.PEELABLE_MASK_MATERIAL_TYPE_) as PEELABLE_MASK_MATERIAL_TYPE_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='PEELABLE_MASK_TYPE_' and ev.enum=job_da.SOLDERMASK_PEELABLE_TYPE_) as SOLDERMASK_PEELABLE_TYPE_,
			     (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='JOB_STATUS_TYPE_' and ev.enum=job_da.ORDER_STATUS_) as ORDER_STATUS_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='MFG_CLASS_TYPE_' and ev.enum=job_da.MFG_CLASS_) as MFG_CLASS_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='MATERIAL_FAMILY' and ev.enum=job_da.REQD_FAMILY_) as REQD_FAMILY_,
				 (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='VENDOR' and ev.enum=job_da.REQD_VENDOR_) as REQD_VENDOR_
						
				 
				 
				 
			from items i,job,job_da,customer c1,customer c2,part,part_da,users,stackup,items istackup
			where i.item_type=2 and i.item_id=job.item_id
			and job.revision_id=i.last_checked_in_rev
			and job.item_id=job_da.item_id
			and job.revision_id=job_da.revision_id
			and job.customer_id=c1.cust_id
			and job.end_customer_id=c2.cust_id
			and job.item_id=part.item_id
			and job.revision_id=part.revision_id
			and part.item_id=part_da.item_id
			and part.revision_id=part_da.revision_id
			and job.assigned_operator_id=users.user_id
			and i.root_id=istackup.root_id
            and istackup.item_id=stackup.item_id
            and stackup.revision_id=istackup.last_checked_in_rev
            and ISTACKUP.DELETED_IN_GRAPH is null
			and i.item_name='$job_name'";


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
$row = oci_fetch_array($stid, OCI_RETURN_NULLS);

/*$i = 0;
$result[] = "";
$ncols = oci_num_fields($stid );
while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
	for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($stid, $i);
		if ($row[$column_name]!=null){
			$column_name_t = ucwords(strtolower(str_ireplace('_',' ',$column_name)));
			$enum_value = getEnumValue($column_name,$row[$column_name],$conn);
			if ($enum_value!='')
			{
			  if ($column_name == 'FINISH_SURFACE_') $column_name = 'SURFACE_FINISH_';
			  if ($column_name == 'SOLDER_MASK_TYPE_') $column_name = 'SOLDERMASK_TYPE_';
			  if ($column_name == 'SOLDER_MASK_COLOR_') $column_name = 'SOLDERMASK_COLOR_';
			  $result[$column_name] = $enum_value;
			} else $result[$column_name] = $row[$column_name];
		}
        
    }
} */
echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Job Information',$lang)."<td></tr>";
echo "<tr class='inplan_attri_tr'><td width=25%>".getLang('Job Name',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['ITEM_NAME']."</td><td width=25%>".getLang('Board Type',$lang)."</td><td width=25% class='inplan_attri_td'>".$row['BOARD_TYPE']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Mrp Name',$lang)."</td><td class='inplan_attri_td'>".$row['MRP_NAME']."</td><td>".getLang('Layer Count',$lang)."</td><td class='inplan_attri_td'>".$row['NUM_LAYERS']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Customer',$lang)."</td><td class='inplan_attri_td'>".$row['CUSTOMER_NAME']."</td><td>".getLang('End Customer',$lang)."</td><td class='inplan_attri_td'>".$row['END_CUSTOMER_NAME']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Customer P/N',$lang)."</td><td class='inplan_attri_td'>".$row['NAME']."</td><td>".getLang('Customer Rev',$lang)."</td><td class='inplan_attri_td'>".$row['CUST_REV_NAME']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>T-code</td><td class='inplan_attri_td'>".$row['ODB_JOB_NAME']."</td><td>".getLang('Operator',$lang)."</td><td class='inplan_attri_td'>".$row['USER_NAME']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Deliverable Type',$lang)."</td><td class='inplan_attri_td'>".$row['DELIVERABLE_TYPE']."</td><td>".getLang('UL Type',$lang)."</td><td class='inplan_attri_td'>".$row['UL_CODE_']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Order Status',$lang)."</td><td class='inplan_attri_td'>".$row['ORDER_STATUS_']."</td><td>".getLang('Mfg Class',$lang)."</td><td class='inplan_attri_td'>".$row['MFG_CLASS_']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('A/W Rev',$lang)."</td><td class='inplan_attri_td'>".$row['AW_REV_']."</td><td>".getLang('B/P Rev',$lang)."</td><td class='inplan_attri_td'>".$row['BP_REV_']."</td></tr>";

//Stackup
echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Stackup Info',$lang)."<td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Board Thickness',$lang)."</td><td class='inplan_attri_td'>".$row['CUSTOMER_THICKNESS']." mil</td><td class='inplan_attri_tr'>".getLang('Tol ( + )',$lang)."</td><td class='inplan_attri_td'>".$row['CUSTOMER_THICKNESS_TOL_PLUS']." mil</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Calc Method',$lang)."</td><td class='inplan_attri_td'>".$row['THICKNESS_CALC_METHOD']."</td><td class='inplan_attri_tr'>".getLang('Tol ( - )',$lang)."</td><td class='inplan_attri_td'>".$row['CUSTOMER_THICKNESS_TOL_MINUS']." mil</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Mtl Vendor',$lang)."</td><td class='inplan_attri_td'>".$row['REQD_VENDOR_']."</td><td class='inplan_attri_tr'>".getLang('Mtl Family',$lang)."</td><td class='inplan_attri_td'>".$row['REQD_FAMILY_']."</td></tr>";



echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Panel Design',$lang)."<td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('PCB Size X',$lang)."</td><td class='inplan_attri_td'>".($row['PCB_SIZE_X'] / 1000)."\""."</td><td>".getLang('PCB Size Y',$lang)."</td><td class='inplan_attri_td'>".($row['PCB_SIZE_Y'] / 1000)."\""."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('PNL Size X',$lang)."</td><td class='inplan_attri_td'>".($row['PNL_SIZE_X'] / 1000)."\""."</td><td>".getLang('PNL Size Y',$lang)."</td><td class='inplan_attri_td'>".($row['PNL_SIZE_Y'] / 1000)."\""."</td></tr>";
//echo "<tr class='inplan_attri_tr'><td>".getLang('Sheet Size X',$lang)."</td><td class='inplan_attri_td'>".($result['SHEET_SIZE_X_'] / 1000)."\""."</td><td>".getLang('Sheet Size Y',$lang)."</td><td class='inplan_attri_td'>".($result['SHEET_SIZE_Y_'] / 1000)."\""."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Num PCBs In PNL',$lang)."</td><td class='inplan_attri_td'>".$row['NUM_PCBS']."</td><td>".getLang('Num Arrays In PNL',$lang)."</td><td class='inplan_attri_td'>".$row['NUM_ARRAYS']."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Num PCBs In Array',$lang)."</td><td class='inplan_attri_td'>".$row['NUM_PCBS_IN_ARRAY']."</td><td></td><td></td></tr>";

echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Ink Type',$lang)."<td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Solder Mask Type',$lang)."</td><td class='inplan_attri_td'>".$row['SOLDERMASK_TYPE_']."</td><td>".getLang('Solder Mask Color',$lang)."</td><td class='inplan_attri_td'>".$row['SOLDERMASK_COLOR_']."</td></tr>";
$silk = getLegendInk($conn,$job_name);
echo "<tr class='inplan_attri_tr'><td>".getLang('Legend Type',$lang)."</td><td class='inplan_attri_td'>".substr($silk,1,strpos($silk,"@@@@")-1)."</td><td class='inplan_attri_tr'>".getLang('Legend Color',$lang)."</td><td class='inplan_attri_td'>".substr($silk,strpos($silk,"@@@@")+4,strlen($silk))."</td></tr>";

if ($row['SOLDERMASK_PEELABLE_SIDES_'] == 'None'){
	echo "<tr class='inplan_attri_tr'><td>".getLang('Peelable Mask Req',$lang)."</td><td class='inplan_attri_td'>".$row['SOLDERMASK_PEELABLE_SIDES_']."</td><td class='inplan_attri_tr'>".getLang('Peelable Mask Application',$lang)."</td><td class='inplan_attri_td'>"."None"."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>".getLang('Peelable Mask Material Type',$lang)."</td><td class='inplan_attri_td'>"."None"."</td><td class='inplan_attri_tr'>".getLang('Peelable Mask Type',$lang)."</td><td class='inplan_attri_td'>"."None"."</td></tr>";
} else 
{
	echo "<tr class='inplan_attri_tr'><td>".getLang('Peelable Mask Req',$lang)."</td><td class='inplan_attri_td'>".$row['SOLDERMASK_PEELABLE_SIDES_']."</td><td class='inplan_attri_tr'>".getLang('Peelable Mask Application',$lang)."</td><td class='inplan_attri_td'>".$row['PEELABLE_MASK_APPLICATION_']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>".getLang('Peelable Mask Material Type',$lang)."</td><td class='inplan_attri_td'>".$row['PEELABLE_MASK_MATERIAL_TYPE_']."</td><td class='inplan_attri_tr'>".getLang('Peelable Mask Type',$lang)."</td><td class='inplan_attri_td'>".$row['SOLDERMASK_PEELABLE_SIDES_']."</td></tr>";

}


//surface finish
echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Finish',$lang)."<td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Surface Finish',$lang)."</td><td class='inplan_attri_td'>".$row['SURFACE_FINISH_']."</td><td class='inplan_attri_tr'>".getLang('Min Surface Finish Thk',$lang)."</td><td class='inplan_attri_td'>".$row['SURFACE_FINISH_THK_MIN_']." uin.</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Selective Plate',$lang)."</td><td class='inplan_attri_td'>".$row['SELECTIVE_PLATE_']."</td><td class='inplan_attri_tr'>".getLang('Min Selective Plate Thk',$lang)."</td><td class='inplan_attri_td'>".$row['SELECTIVE_PLATE_THK_MIN_']." uin.</td></tr>";
if ($row['PLATED_TIPS_SIDES_'] == "None"){
	echo "<tr class='inplan_attri_tr'><td>".getLang('Plated Tips Required',$lang)."</td><td class='inplan_attri_td'>".$row['PLATED_TIPS_SIDES_']."</td><td class='inplan_attri_tr'>".getLang('Tip Style',$lang)."</td><td class='inplan_attri_td'>"."None"."</td></tr>";
echo "<tr class='inplan_attri_tr'><td>".getLang('Tip Type',$lang)."</td><td class='inplan_attri_td'>"."None"."</td><td class='inplan_attri_tr'>".getLang('Min Gold Tip Thickness',$lang)."</td><td class='inplan_attri_td'>"."0"." uin.</td></tr>";
} else {
	echo "<tr class='inplan_attri_tr'><td>".getLang('Plated Tips Required',$lang)."</td><td class='inplan_attri_td'>".$row['PLATED_TIPS_SIDES_']."</td><td class='inplan_attri_tr'>".getLang('Tip Style',$lang)."</td><td class='inplan_attri_td'>".$row['PLATED_TIPS_STYLE_']."</td></tr>";
	echo "<tr class='inplan_attri_tr'><td>".getLang('Tip Type',$lang)."</td><td class='inplan_attri_td'>".$row['PLATED_TIPS_TYPE_']."</td><td class='inplan_attri_tr'>".getLang('Min Gold Tip Thickness',$lang)."</td><td class='inplan_attri_td'>".$row['GOLD_TIP_THICKNESS_MIN_']." uin.</td></tr>";
}




//layer attri

echo "<tr><td colspan=4 class='inplan_attri_hd'>".getLang('Layer Parameters',$lang)."<td></tr>";
$query = "select i.item_name
			,'L'||CL.LAYER_INDEX as Layer
			,CLD.LINE_MIN_
			,CLD.SPACE_MIN_
			,CLD.AR_MIN_
		from items i
			,items ic
			,copper_layer cl
			,copper_layer_da cld
		where i.item_type=2
		and i.root_id=ic.root_id
		and ic.item_id=cl.item_id
		and IC.DELETED_IN_GRAPH is null
		and cl.revision_id=ic.last_checked_in_rev
		and cl.item_id=cld.item_id
		and cl.revision_id=cld.revision_id
		and i.item_name='$job_name'";

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
	echo "<table class='layer_table'><tr><th>".getLang('Layer Index',$lang)."</th><th>".getLang('LW Min (mil)',$lang)."</th><th>".getLang('SP Min (mil)',$lang)."</th><th>".getLang('AR Min (mil)',$lang)."</th></tr>";
	while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
		echo "<tr><td>".$row["LAYER"]."</td><td>".$row["LINE_MIN_"]."</td><td>".$row["SPACE_MIN_"]."</td><td>".$row["AR_MIN_"]."</td></tr>";
	}

	echo "</table>";





function getEnumValue($column_name,$enum,$conn){
	$return = "";
	$my_query = "select cnttype from attributes where fldname='$column_name'";
	$stid = oci_parse($conn, $my_query);
	$r = oci_execute($stid, OCI_DEFAULT);
	$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
	if ($row[0] == 'ENUM'){
		$my_query = "select ev.value
						from    enum_values ev
								,enum_types et
								,attributes attr
						where   et.enum_type = ev.enum_type
								and et.enum_type=attr.enum_type
								and ev.enum = $enum
								and attr.fldname='$column_name'";
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		$return = $row[0];
	}
	return $return;
}

function isHideField($column_name){
	$txt = file_get_contents("hide_feilds.txt");
	$return= strpos($txt,$column_name);
	return $return;
}

function getLegendInk($conn,$jobname){
	$return = "";
	$my_query = "select (select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='LCM_INK_TYPES_' and ev.enum=MLD.LEGEND_MASK_TYPE_)
				 ||'@@@@'||(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
						and et.type_name='SS_COLOR_TYPE_' and ev.enum=MLD.SS_COLOR_) as SS_COLOR_
				from items i
					,items imask
					,mask_layer ml
					,mask_layer_da mld
				where i.item_type=2
				and i.root_id=imask.root_id
				and imask.item_id=ml.item_id
				and ml.revision_id=imask.last_checked_in_rev
				and imask.deleted_in_graph is null
				and ML.ITEM_ID = mld.item_id
				and ml.revision_id=mld.revision_id
				and ML.MASK_TYPE=2
				and rownum=1
				and i.item_name='$jobname'";
	$stid = oci_parse($conn, $my_query);
	$r = oci_execute($stid, OCI_DEFAULT);
	$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
	$return = $row[0];

	return $return;
}

?>
</table>
</div>