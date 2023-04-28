<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- div style="color:red;font-weight:bold">请注意:此网上工作单仅供内部参考，对客户数据以原装或oracle工单为准.</div -->
 <style>
	#traveler_page table {
		font: 13px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
	}

	.inplan_attri_hd{
			background:#D0D0D0;
			height:25px;
			font-weight:bold;
			padding-left:10px;
		}
	.traveler_table td{
		width:50%;
		vertical-align:top;
	}

	#traveler_page td {
		vertical-align:top;
	}

	.traveler_table {
		border-collapse:collapse;
		border-top:solid 0px black;
		border-left:solid 2px black; 
		border-right:solid 2px black;
		border-bottom:solid 1px black;
		width:100%;
	}
	.traveler_table_1{
		border-collapse:collapse;
		border-top:solid 2px black;
		border-left:solid 2px black; 
		border-right:solid 2px black;
		border-bottom:solid 1px black;
		width:100%;
	}

	.hearder_m {
		border-collapse:collapse;
		border:solid 1px black; 
		width:100%;
	}

	.hearder_m td {
		border:solid 1px black; 
		vertical-align:top;
	}
	.mat_table td{
		border:solid 0px black;
	}

	.wip_box {
		border:solid 1px black; 
		width:100%;
	}

	.wip_box td{
		width:50%;
	}

	.right_box {
		background-color:#99BBDD;
		width:150px;
		height:150px;
		
	}
	.process {
		font: 13px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
		background-color:#FFDC35;
		border:solid 1px black; 
		font-weight:bold;
		margin-top:5px;
		padding: 5px 5px 5px 5px;

	
	}
	.section_img {
		cursor:pointer;
	}

	.page_top_type {
		padding: 15px 5px 5px 5px;
		font-weight:bold;
		font-size:22px;
		float:left;
	}
 </style>
 <div id="traveler_page" style="padding:5px;width:780px;">
<?php 
	$site = $_GET['site'];
	$job =  $_GET["job_name"];
	$process = $_GET["process"];
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
		echo "<script type='text/javascript' src='". $pre_dir_scripts . "/jquery-1.4.4.min.js'></script>";
	}


	require "process.php";
	
	include("traveler_query.php");
	
	$rstrav = oci_parse($conn, $sqltrav);
	oci_execute($rstrav, OCI_DEFAULT);

	$my_query = "select i.item_name
                    ,job.mrp_name
                    ,JOB_DA.ITAR_FLAG_
                    ,JOB.PNL_SIZE_X/1000 || ' x '||JOB.PNL_SIZE_Y/1000 ||' inch' as pnl_size
                    ,JOB.NUM_LAYERS
                    ,JOB.NUM_PCBS
					,part.name
					,decode(job.odb_site_id,2,'HY',3,'HZ') site
					,part.cust_rev_name
					,customer.customer_name
					,customer.customer_code
					,end_customer.customer_name customer_name_1
                    ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
                                        and et.type_name='JOB_TYPE' and ev.enum=job.JOB_TYPE) as JOB_TYPE
                    ,job_da.ecn_history_,
                     CASE
                       WHEN part_da.bp_rev_ IS NOT NULL
                          THEN CASE
                                 WHEN part_da.bp_rev_temp_ IS NOT NULL
                                    THEN    part_da.bp_rev_
                                         || ','
                                         || bp_rev_temp_
                                 ELSE part_da.bp_rev_
                              END
                       ELSE part_da.bp_rev_temp_
                    END customer_drawing_rev
                    ,JOB.ODB_JOB_NAME
                    ,CASE
                   WHEN job_da.mfg_class_= 1000
                      THEN job_da.oem_spec_no_
                   ELSE CASE
                   WHEN job_da.oem_spec_no_ IS NULL
                      THEN decode(job_da.mfg_class_,1000,'None',1001,'IPC-6012 / Class 2',1002,'IPC-6012 / Class 3',1003,'Mil - 5510F','MIL-PRF-31032')
                   ELSE    decode(job_da.mfg_class_,1000,'None',1001,'IPC-6012 / Class 2',1002,'IPC-6012 / Class 3',1003,'Mil - 5510F','MIL-PRF-31032')
                        || ';'
                        || TO_CHAR (job_da.oem_spec_no_)
                END
                END customer_spec_number
                ,PART_DA.TCODE_OA_DATE_
                ,case when PART.DELIVERABLE_TYPE =2 then
                    round(PART.PCB_SIZE_X /1000,4) || '\" x ' || round(PART.PCB_SIZE_Y,4) /1000 || '\"'
                 else
                    (select round(panel.outline_size_x,4) / 1000 || '\" x ' || round(panel.outline_size_y /1000,4) || '\"'
                    from    items i2,
                            panel
                    where   i2.item_type=11
                            and i.root_id=i2.root_id
                            and i2.ITEM_ID=panel.item_id
                            and i2.item_name='Array'
                            and i2.DELETED_IN_GRAPH is null
                            and panel.revision_id=i2.LAST_CHECKED_IN_REV
                            )
                end DELIVERABLE_SIZE
                ,decode(PART.DELIVERABLE_TYPE,1,'Array',2,'PCB',3,'Panel') DELIVERABLE_TYPE
                ,round(job.pnl_size_x,4) / 1000 || '\" x ' || round(job.pnl_size_y /1000,4) || '\"' pnl_size
                ,round(job_da.sheet_size_y_,4) / 1000 || '\" x ' || round(job_da.sheet_size_x_ /1000,4) || '\"' sheet_size
                ,decode(PART_da.DELIVERY_PRICE_TYPE_,5001,'UNIT Price',5002,'ARRAY Price',5003,'PANEL Price') DELIVERABLE_price
                ,PART.NUM_PCBS_IN_ARRAY
                ,JOB.NUM_ARRAYS
                ,PART_DA.OUT_FOIL_SIZE_
                ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=PART_DA.UL_CODE_ and et.type_name='UL_CODES_') UL
			    ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=job.BOARD_TYPE and et.type_name='BOARD_TYPE') BOARD_TYPE
                ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=JOB_DA.SURFACE_FINISH_ and et.type_name='SURFACE_FINISH_TYPE_') surface_finish
                ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=JOB_DA.SELECTIVE_PLATE_ and et.type_name='SELECTIVE_PLATE_TYPE_') SELECTIVE_PLATE
                ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=JOB_DA.FIRST_LOT_CONTROL_ and et.type_name='FIRST_LOT_CONTROL_') FIRST_LOT_CONTROL
                ,decode(part_da.high_reliability_metlab_,1020,'High Reliability Board','') high_reliability
                ,(select ev.value from enum_values ev,enum_types et where et.enum_type=ev.enum_type 
                    and ev.enum=job_da.mfg_class_ and et.type_name='MFG_CLASS_TYPE_') MFG_CLASS
                ,(select STACKUP.NUM_IMPEDANCE_CONSTRAINTS from items ist,stackup where ist.root_id=i.root_id 
                    and ist.item_id=stackup.item_id and stackup.revision_id=ist.last_checked_in_rev and ist.deleted_in_graph is null) num_impedance
                ,job_da.carbon_ink_
                ,job_da.soldermask_peelable_sides_
                ,job_da.lead_free_req_
                ,JOB_DA.ENVIRONMENT_REQT_
                ,JOB_DA.CUSTOMER_CAF_REQ_
				,PART_DA.CUSTOMER_ROHS_REQ_
				,part_da.SCORING_WEB_THICKNESS_
				,part_da.SCORING_WEB_THKNS_PLUS_TOL_
				,part_da.SCORING_WEB_THKNS_MINUS_TOL_
                ,part_da.SCORING_ANGLE_
				,part_da.VCUT_ANGLE_PLUS_TOL_
				,part_da.VCUT_ANGLE_MINUS_TOL_
				,part_da.V_CUT_OFFSET_MAX_
				,part_da.V_CUT_OFFSET_NOM_
				,part_da.V_CUT_OFFSET_TOL_MINUS_
				,part_da.V_CUT_OFFSET_TOL_PLUS_
				,part_da.BEVEL_ANGLE_
				,part_da.BEVEL_ANGLE_PLUS_TOL_
				,part_da.BEVEL_ANGLE_MINUS_TOL_
				,part_da.BEVEL_DEPTH_
				,part_da.BEVEL_DEPTH_PLUS_TOL_
				,part_da.BEVEL_DEPTH_MINUS_TOL_
				,part_da.BEVELING_REMAIN_THK_
				,part_da.BEVELING_REMAIN_THK_PLUS_
				,part_da.BEVELING_REMAIN_THK_MINUS_
				,part_da.BEVEL_REQUIRED_
				,users.user_name
				,job_da.JOB_APPROVED_BY_
				,job_da.JOB_APPROVED_DATE_
                from items i
                    ,job
                    ,job_da
                    ,part
                    ,part_da
					,customer
					,customer end_customer
					,users
                where i.item_type=2
                and i.item_id=job.item_id
                and job.revision_id=i.last_checked_in_rev
                and job.item_id=job_da.item_id
                and job.revision_id=job_da.revision_id
                and i.item_id=part.item_id
                and part.revision_id=i.last_checked_in_rev
                and part.item_id=part_da.item_id
                and part.revision_id=part_da.revision_id
				and customer.cust_id=job.customer_id
				and end_customer.cust_id=job.end_customer_id
				and users.user_id=job.assigned_operator_id
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
	$ecn = $row['ECN_HISTORY_'];
	$cust_rev = $row['CUSTOMER_DRAWING_REV'];
	$t_code = $row['ODB_JOB_NAME'];
	$spec = $row['CUSTOMER_SPEC_NUMBER'];
	$t_code_oa = $row['TCODE_OA_DATE_'];
	$deliver_size = $row['DELIVERABLE_SIZE'];
	$deliver_type = $row['DELIVERABLE_TYPE'];
	$pnl_size = $row['PNL_SIZE'];
	$sheet_size = $row['SHEET_SIZE'];
	$deliver_price = $row['DELIVERABLE_PRICE'];
	$unit_in_array = $row['NUM_PCBS_IN_ARRAY'];
	$array_in_panel= $row['NUM_ARRAYS'];
	$foil_size= $row['OUT_FOIL_SIZE_'];
	$ul= $row['UL'];
	$cust_pn =  $row['NAME'];
	$cust_pn_rev =  $row['CUST_REV_NAME'];
	$customer =  $row['CUSTOMER_NAME'];
	$customer_code = $row['CUSTOMER_CODE'];
	$end_customer =  $row['CUSTOMER_NAME_1'];
	$site = $row['SITE'];
	$board_type = $row['BOARD_TYPE'];
	$surface_finish = $row['SURFACE_FINISH'];
	$selective_plate = $row['SELECTIVE_PLATE'];
	$first_lot = $row['FIRST_LOT_CONTROL'];
	$high_reliability = $row['HIGH_RELIABILITY'];
	$mfg_class = $row['MFG_CLASS'];
	$num_impedance = $row['NUM_IMPEDANCE'];
	$carbon_ink = $row['CARBON_INK_'];
	$peelable_sides_ = $row['SOLDERMASK_PEELABLE_SIDES_'];
	$lead_free = $row['LEAD_FREE_REQ_'];
	$env_req = $row['ENVIRONMENT_REQT_'];
	$caf_req = $row['CUSTOMER_CAF_REQ_'];
	$drill_type = $row['DRILL_TYPE'];
	$cust_rosh =  $row['CUSTOMER_ROHS_REQ_'];

	$vcut_web_thk = $row['SCORING_WEB_THICKNESS_'];
	$vcut_tol_plus = $row['SCORING_WEB_THKNS_PLUS_TOL_'];
	$vcut_tol_minus = $row['SCORING_WEB_THKNS_MINUS_TOL_'];
	$vcut_angle = $row['SCORING_ANGLE_'];
	$vcut_angle_plus = $row['VCUT_ANGLE_PLUS_TOL_'];
	$vcut_angle_minus = $row['VCUT_ANGLE_MINUS_TOL_'];
	$vcut_offset_max = $row['V_CUT_OFFSET_MAX_'];
	$vcut_offset_nom = $row['V_CUT_OFFSET_NOM_'];
	$vcut_offset_tol_plus = $row['V_CUT_OFFSET_TOL_PLUS_'];
	$vcut_offset_tol_minus = $row['V_CUT_OFFSET_TOL_MINUS_'];

	$bevel_angle = $row['BEVEL_ANGLE_'];
	$bevel_angle_plus = $row['BEVEL_ANGLE_PLUS_TOL_'];
	$bevel_angle_minus = $row['BEVEL_ANGLE_MINUS_TOL_'];
	
	$bevel_req = $row['BEVEL_REQUIRED_'];
	$bevel_depth = $row['BEVEL_DEPTH_'];
	$bevel_tol_plus =$row['BEVEL_DEPTH_PLUS_TOL_'];
	$bevel_tol_minus = $row['BEVEL_DEPTH_MINUS_TOL_'];

	$bevel_remain_thk = $row['BEVELING_REMAIN_THK_'];
	$bevel_remain_thk_plus = $row['BEVELING_REMAIN_THK_PLUS_'];
	$bevel_remain_thk_minus = $row['BEVELING_REMAIN_THK_MINUS_'];

	$user_name = $row['USER_NAME'];
	$job_approved_by = $row['JOB_APPROVED_BY_'];
	$job_approved_date = $row['JOB_APPROVED_DATE_'];



	$headerinfo = "<p style='page-break-before: always'>
	<div class='page_top_type'>@@page_top_type@@</div>
	<table border=0 width=100%>
	<tr><td width='15%'></td><td width='15%'></td><td width='1%'></td><td width='20%'><b>Merix P/N</b></td><td>: @@mrpname@@</td><td rowspan=2><div class='process'>Process: @@processname@@</div></td></tr>
	<tr><td width='15%'></td><td width='15%'></td><td width='1%'></td><td width='20%'><b>Tooling No.</b></td><td>: @@jobname@@</td></tr>
	<tr><td width='15%' colspan=2><font size=+2><b>&nbsp;&nbsp;&nbsp;&nbsp;$site TRAVELER</b></font></td><td width='1%'></td><td width='20%'><b>Customer P/N</b></td><td colspan=2>: @@cust_pn@@</td></tr>
	<tr><td width='15%'><b><b> </b></td><td width='15%'> </td><td width='1%'></td><td width='20%'><b>Customer Rev</b></td><td colspan=2>: @@cust_pn_rev@@</td></tr>
	<tr><td width='15%'><b><b> </b></td><td width='15%'> </td><td width='1%'></td><td width='20%'><b>Customer</b></td><td colspan=2>: @@customer@@</td></tr>
	<tr><td width='15%'><b><b> </b></td><td width='15%'> </td><td width='1%'></td><td width='20%'><b>End Customer</b></td><td colspan=2>: @@end_customer@@</td></tr></TABLE></p><br>";

	$hearder_m = "<table class='hearder_m'><td width='35%'><b>工程更改历史</b><br/>$ecn</td><td width='75%'><b>物料清单</b><br/>";
	
	/*$my_query = "select decode(M.TYPE,1,'Foil',0,'Core',3,'Prepreg') as mat_type
							,M.MRP_NAME
							,SM.USER_INFORMATION
							,count(M.MRP_NAME) || 'x' as cou
					from items i
						,items ism
						,segment_material sm
						,material m
					where i.item_type=2
						and ism.root_id=i.root_id
						and ism.item_id=sm.item_id
						and ISM.LAST_CHECKED_IN_REV=SM.REVISION_ID
						and ISM.DELETED_IN_GRAPH is null
						and SM.MATERIAL_ITEM_ID=m.item_id
						and SM.MATERIAL_REVISION_ID=M.REVISION_ID
						and i.item_name='$job'
						group by M.TYPE,M.MRP_NAME,SM.USER_INFORMATION
						order by mat_type"; */
	$my_query = "SELECT  DECODE
                                     (segment_type_t,
                                      'Isolator', 'Prepreg',
                                      segment_type_t
                                     ) material_type,mrp_name,user_information material_info,
                         COUNT (0) || ' x' material_count
                   
                         FROM (
                          --select materials which under subpart
                          SELECT i.item_name job_name,
                                 m.mrp_name,
                                 process.mrp_name process_name, sm.user_information,
                                 decode(ss.segment_type,3,'Foil',1,'Isolator',0,'Core') segment_type_t,
                                 process.mrp_name p_mrp_name,
                                 num_impedance_constraints
                            FROM items i,
                                 items iprocess,
                                 process process,
                                 links links,
                                 segment_material sm,
                                 material m
                                 ,items ist
                                 ,stackup stackup
                                 ,items iss
                                 ,stackup_seg ss
                           WHERE i.item_type = 2
                             AND i.root_id = iprocess.root_id
                             AND i.root_id = iss.root_id
                             and i.root_id = ist.root_id
                             and ist.item_id=stackup.item_id
                             and stackup.revision_id=ist.last_checked_in_rev
                             and ist.deleted_in_graph is null
                             AND iprocess.deleted_in_graph IS NULL
                             AND iss.deleted_in_graph IS NULL
                             AND iprocess.item_id = process.item_id
                             AND process.revision_id = iprocess.last_checked_in_rev
                             AND links.link_type = 21
                             AND links.item_id = iprocess.item_id
                             AND links.points_to = sm.item_id
                             AND iss.item_id = ss.item_id
                             AND ss.revision_id = iss.last_checked_in_rev
                             and ss.item_id=sm.item_id
                             and ss.revision_id=sm.revision_id
                             AND m.item_id = sm.material_item_id
                             AND m.revision_id = sm.material_revision_id
                             AND i.item_name = '$job'
                              AND process.mrp_name='$process'
                         UNION ALL
                          SELECT i.item_name job_name,
                                 m.mrp_name,
                                 linkprocess.mrp_name process_name, sm.user_information,
                                 decode(ss.segment_type,3,'Foil',1,'Isolator',0,'Core') segment_type_t,
                                 subprocess.mrp_name p_mrp_name,
                                 num_impedance_constraints
                            FROM items i,
                                 items isubpro,
                                 items ilinkpro,
                                 process subprocess,
                                 process linkprocess,
                                 links sub_links,
                                 links links,
                                 segment_material sm,
                                 material m
                                 ,items ist
                                 ,stackup stackup
                                 ,items iss
                                 ,stackup_seg ss
                           WHERE i.item_type = 2
                             AND i.root_id = isubpro.root_id
                             AND i.root_id = iss.root_id
                             and i.root_id = ist.root_id
                             and ist.item_id=stackup.item_id
                             and stackup.revision_id=ist.last_checked_in_rev
                             and ist.deleted_in_graph is null
                             AND i.root_id = ilinkpro.root_id
                             AND isubpro.deleted_in_graph IS NULL
                             AND ilinkpro.deleted_in_graph IS NULL
                             AND subprocess.revision_id = isubpro.last_checked_in_rev
                             AND ilinkpro.item_id = linkprocess.item_id
                             AND linkprocess.revision_id = ilinkpro.last_checked_in_rev
                             AND sub_links.points_to = linkprocess.item_id
                             AND sub_links.item_id = subprocess.item_id
                             AND linkprocess.proc_subtype = 26
                             AND sub_links.link_type = 8
                              AND i.item_name = '$job'
                              AND subprocess.mrp_name='$process'
                             AND links.link_type = 21
                             AND links.item_id = linkprocess.item_id
                             AND links.points_to = sm.item_id
                             AND iss.item_id = ss.item_id
                             AND ss.revision_id = iss.last_checked_in_rev
                             AND iss.deleted_in_graph IS NULL
                             and ss.item_id=sm.item_id
                             and ss.revision_id=sm.revision_id
                             AND m.item_id = sm.material_item_id
                             AND m.revision_id = sm.material_revision_id
                             
                          )
                         GROUP BY job_name, p_mrp_name, mrp_name, segment_type_t, user_information
                         ORDER BY segment_type_t, user_information";

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
	$mat_table = "<table class='mat_table'>";
	while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
		$mat_table .= "<tr><td>".$row['MATERIAL_TYPE']."</td><td>".$row['MRP_NAME']."</td><td>".$row['MATERIAL_COUNT']."</td><td>".$row['MATERIAL_INFO']."</td></tr>";
	
	}
	if ($subparts and substr($process,0,3) !="750" ) $mat_table  .=$subparts;
	$mat_table  .= "</table>";
	
	$hearder_m .= $mat_table . "</td></table>";

	$wip_box = "<table class='wip_box'><tr><td>工单</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>工具工单</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>工单类型</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>测量单位</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>开始数量</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>数量</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>开始日期</td><td>:&nbsp;&nbsp;</td></tr>
									   <tr><td>结束日期</td><td>:&nbsp;&nbsp;</td></tr>
	
				</table>";
	if($board_type == "Automotive") {
		$board_type = "汽车板";
	}
	$right_box = "<table class='right_box'><tr><td>板类型</td><td>: $board_type</td></tr><tr><td>表面处理</td><td>: $surface_finish</td></tr>";
	if ($selective_plate != 'None') {
		if ($selective_plate == 'OS - Hard Gold (Type 1)') $selective_plate = "Dip Gold";
		$right_box .="<tr><td>Selective Plate</td><td>: $selective_plate</td></tr>";
		}
		if ($first_lot !='None') $right_box .="<tr><td colspan=2>First Lot:$first_lot</td></tr>";
		if ($high_reliability != '') $right_box .="<tr><td colspan=2>High Reliability Board</td></tr>";
		if ($mfg_class =='IPC-6012 / Class 3')  $right_box .="<tr><td colspan=2>IPC Class 3</td></tr>";
		if ($num_impedance>0) $right_box .="<tr><td colspan=2>阻抗板</td></tr>";
		if ($carbon_ink!=1000) $right_box .="<tr><td colspan=2>碳油板</td></tr>";
		if ($peelable_sides_!=1000) $right_box .="<tr><td colspan=2>蓝胶板</td></tr>";
		if ($lead_free==1) $right_box .="<tr><td colspan=2>无铅板</td></tr>";
		if ($cust_rosh ==1 ){
			if ($env_req==4002) 
			$right_box .="<tr><td colspan=2>RoHS5</td></tr>";
		    if ($env_req==4003) 
			$right_box .="<tr><td colspan=2>RoHS/RoHS6</td></tr>";
			if ($env_req==5001) 
			$right_box .="<tr><td colspan=2>RoHS</td></tr>";

		}
		if ($caf_req ==1) $right_box .="<tr><td colspan=2>CAF</td></tr>";
		$right_box .= getDrillType($job,$conn);

	
	
	
	$right_box .= "</table>";

	$prepreg_size = getPpSize($job,$conn);

	if ($numLayers <= 2) $foil_size = "";

	if (substr($process,0,3) == "750") {
		$numLayers = "";
	}
	$center_box = "<table style='table-layout:fixed; word-break:break-all;width:100%'><tr><td width=17%>图纸/版本</td><td width=35%>: $cust_rev</td><td width=26%>T-Code</td><td>: $t_code</td></tr>
						  <tr><td>规格书</td><td>: $spec</td><td>TCode OA Date</td><td>: $t_code_oa</td></tr>
						  <tr><td>交货尺寸</td><td>: $deliver_size</td><td>交货形式</td><td>: $deliver_type</td></tr>
						  <tr><td>开料尺寸</td><td>: $pnl_size</td><td>计价单位</td><td>: $deliver_price</td></tr>
						  <tr><td>大料尺寸</td><td>: $sheet_size</td><td>Array 中的 Unit 数</td><td>: $unit_in_array</td></tr>
						  <tr><td>纤维尺寸</td><td>: $prepreg_size</td><td>Panel中的Array数</td><td>: $array_in_panel</td></tr>
						  <tr><td>外层铜箔尺寸</td><td>: $foil_size</td><td>Panel中的 Unit 数</td><td>: $numParts</td></tr>
						  <tr><td>层数</td><td>: $numLayers</td><td>UL 标记</td><td>: $ul</td></tr>
					</table>";

	$attri_table = "<table width=100%><tr><td width=18% >$wip_box</td><td width=62%>$center_box</td><td width=20%>$right_box </td></tr></table>";

	$headerinfo = $headerinfo .$attri_table. $hearder_m . "<br/>";

	$tableinfo = "<table class='traveler_table'><tr><td class='inplan_attri_hd' WIDTH='90%' colspan =2><b>@@section@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@@discription@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	@@WC@@</b></td></tr>@@tabledata@@</table>";

	$tableinfo1 = "<table class='traveler_table_1'><tr><td class='inplan_attri_hd' WIDTH='90%' colspan =2><b>@@section@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@@discription@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	@@WC@@</b></td></tr>@@tabledata@@</table>";
	
	$headerinfo = str_replace("@@jobname@@",$job,$headerinfo);
	if($itar == 1){
		$headerinfo = str_replace("@@itar@@","Yes" ,$headerinfo);
	}
	else{
		$headerinfo = str_replace("@@itar@@","No" ,$headerinfo);
	}
	$headerinfo = str_replace("@@cust_pn@@",$cust_pn,$headerinfo);
	$headerinfo = str_replace("@@cust_pn_rev@@",$cust_pn_rev,$headerinfo);
	$headerinfo = str_replace("@@customer@@",$customer.'('.$customer_code.')',$headerinfo);
	$headerinfo = str_replace("@@end_customer@@",$end_customer,$headerinfo);
	if(substr($process,0,3) == '950') {
		$headerinfo = str_replace("@@mrpname@@",$process,$headerinfo);
	} elseif (substr($process,0,3) == '750') {
		$headerinfo = str_replace("@@mrpname@@",$process.'('.$mrpName.')',$headerinfo);
	}
	else {
		$headerinfo = str_replace("@@mrpname@@",$mrpName,$headerinfo);
	}
	$top_page_type =" &nbsp  &nbsp &nbsp &nbsp &nbsp ";
	if ($high_reliability != ''){
		$top_page_type .= "【高可靠性板】";
	}
	if($mfg_class =='IPC-6012 / Class 3') {
		$top_page_type .= "【Class 3】";
	}
	if($board_type == "汽车板") {
		$top_page_type .= "【汽车板】";
	}	

	$headerinfo = str_replace("@@page_top_type@@",$top_page_type,$headerinfo);
	


	

	$ordernum = "none";
	$travindex = -1;
	$tmpInfo = "";
	$wcnotes = "";
	$has_special = 0;
	$i = 0;
	while(oci_fetch($rstrav)){
		if(oci_result($rstrav, 2)!==$ordernum){
			//if($ordernum > -1){
			//	$tmpInfo = $tmpInfo . "<p style='page-break-before: always'>";
			//}
			$tmpInfo=$tmpInfo  . $headerinfo;
			$tmpInfo = str_replace("@@processname@@",oci_result($rstrav, 2),$tmpInfo);
			$ordernum = oci_result($rstrav, 2);
			$has_special = 0;
		}
		if(oci_result($rstrav, 6)<>$travindex){
			if($travindex <> -1){
				
				if ($vcut_img) $wcnotes.=$vcut_img;
				if ($beve_img) $wcnotes.=$beve_img;
				if ($section_img) $wcnotes.=$section_img;
				if (substr($wcnotes,-5,5)=='</TD>') $wcnotes.='<TD> &nbsp </TD></TR>';
				$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);

				if ($vcut_img) $vcut_img ="";
				if ($beve_img) $beve_img="";
				if ($section_img) $section_img="";
			}
			$wcnotes = "";
			$i=0;
			if (oci_result($rstrav, 6) == 1){
				$tmpInfo = $tmpInfo  . $tableinfo1;
			} else {
				$tmpInfo = $tmpInfo  . $tableinfo;
			}
			
			if (trim(oci_result($rstrav, 3)) == "SPECIAL PROCESSES"){
				$tmpInfo = str_replace("@@WC@@","特殊要求",$tmpInfo);
				$has_special = 1;
			} else	$tmpInfo = str_replace("@@WC@@",oci_result($rstrav, 3),$tmpInfo);
			if ($has_special == 1){
				if (oci_result($rstrav, 6) ==1) {
					$tmpInfo = str_replace("@@section@@","",$tmpInfo);
				} else {
					$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 6) -1 ."0",$tmpInfo);
				}
				
			} else {
				$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 6)."0",$tmpInfo);
			}

			$tmpInfo = str_replace("@@discription@@",oci_result($rstrav, 8),$tmpInfo);
			$travindex=oci_result($rstrav, 6);
			$ts_item_id = oci_result($rstrav, 9);
			$traveler_ordering_index = oci_result($rstrav, 6);
			$sequential_index = oci_result($rstrav, 12);

			// v-cut image
			if (trim(oci_result($rstrav, 4)) == "VCUTINPL"){
				
				if ($vcut_web_thk >0 ){
					if ( $vcut_tol_plus == $vcut_tol_minus){
						$vcut_web_thk_print =round($vcut_web_thk,2) . "+/-" . round($vcut_tol_plus,2) . "mil";
					} else {
						$vcut_web_thk_print = round($vcut_web_thk,2)  . "+" . round($vcut_tol_plus,2) . "/-" .round($vcut_tol_minus,2). "mil";
					}
				}
				if ($vcut_angle >0 ){
					if ( $vcut_angle_plus == $vcut_angle_minus){
						$vcut_angle_print =$vcut_angle . "+/-" . round($vcut_angle_plus,2) . "°";
					} else {
						$vcut_angle_print = $vcut_angle  . "+" . round($vcut_angle_plus,2) . "/-" .round($vcut_angle_minus,2). "°";
					}
				}
				if ($vcut_offset_max >0 ){
					if ( $vcut_offset_tol_plus == $vcut_offset_tol_minus){
						$vcut_offset_print =round($vcut_offset_max,2) . "+/-" . round($vcut_offset_tol_plus,2) . "mil";
					} else {
						$vcut_offset_print = round($vcut_offset_max,2)  . "+" . round($vcut_offset_tol_plus,2) . "/-" .round($vcut_offset_tol_minus,2). "mil";
					}
				}
				if ($vcut_offset_nom >0 ){
					if ( $vcut_offset_tol_plus == $vcut_offset_tol_minus){
						$vcut_offset_print =round($vcut_offset_nom,2) . "+/-" . round($vcut_offset_tol_plus,2) . "mil";
					} else {
						$vcut_offset_print = round($vcut_offset_nom,2)  . "+" . round($vcut_offset_tol_plus,2) . "/-" .round($vcut_offset_tol_minus,2). "mil";
					}
				}
				$vcut_img ="<tr><td><table><tr><td style='text-align:center'>$vcut_offset_print</td><td></td></tr>
								   <tr><td><img src='images/vcut.PNG' /></td><td style='vertical-align:middle'>$vcut_web_thk_print</td></tr>
								   <tr><td style='text-align:center'>$vcut_angle_print</td><td></td></tr>
							</table></td></tr>";



			//$wcnotes .= $vcut_img;
			}

			// bevel image
			if (trim(oci_result($rstrav, 4)) == "BEVEINPL"){
				switch ($bevel_angle){
					case 1001:
						$bevel_angle_print = 45;
						break;
					case 1005:
						$bevel_angle_print = 20;
						break;
					case 1010:
						$bevel_angle_print = 30;
						break;
				}

				if ($bevel_angle >0 ){
					if ( $bevel_angle_plus == $bevel_angle_minus){
						$bevel_angle_p =round($bevel_angle_print,2) . "+/-" . round($bevel_angle_plus,2) . "°";
					} else {
						$bevel_angle_p = round($bevel_angle_print,2)  . "+" . round($bevel_angle_plus,2) . "/-" .round($bevel_angle_minus,2). "°";
					}
				}

				if ($bevel_remain_thk >0 ){
					if ( $bevel_remain_thk_plus == $bevel_remain_thk_minus){
						$bevel_remain_thk_print =round($bevel_remain_thk,2) . "+/-" . round($bevel_remain_thk_plus,2) . "mil";
					} else {
						$bevel_remain_thk_print = round($bevel_remain_thk,2)  . "+" . round($bevel_remain_thk_plus,2) . "/-" .round($bevel_remain_thk_minus,2). "mil";
					}
				}

				if ($bevel_depth >0 ){
					if ( $bevel_tol_plus == $bevel_tol_minus){
						$bevel_depth_print =round($bevel_depth,2) . "+/-" . round($bevel_tol_plus,2) . "mil";
					} else {
						$bevel_depth_print = round($bevel_depth,2)  . "+" . round($bevel_tol_plus,2) . "/-" .round($bevel_tol_minus,2). "mil";
					}
				}
				switch ($bevel_req){
					case 1000:
						$bevel_req_print = "None";
						break;
					case 1001:
						$bevel_req_print = "NC";
						break;
					case 1002:
						$bevel_req_print = "Manual";
						break;
				}
				$beve_print = "Angle= ".$bevel_angle_p."<br/>" . "T= ".$bevel_remain_thk_print."<br/>"."Depth= ".$bevel_depth_print."<br/>Method= ".$bevel_req_print;

				$beve_img ="<tr><td><table><tr><td><img src='images/bevel.PNG' /></td><td style='vertical-align:middle'>$beve_print</td></tr>
							</table></td></tr>";
			
				//$wcnotes .= $beve_img;
			}
			
			if (hasImg($job,$ts_item_id,$sequential_index,$conn) == "Yes") $section_img = "<tr><td colspan=2><img class='section_img' width='750px' alt=". getLang("Left click to view big picture",$lang)." src=\"$pre_dir/getpic.php?job_name=$job&ts_item_id=$ts_item_id&sequential_index=$sequential_index\" onclick=\"javascript:window.open (this.src, 'newwindow2', 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=no')\"/></td></tr>";
		}
		$tmpfix = str_replace(chr(10),"<br>",oci_result($rstrav, 5));
		if ($tmpfix){
			$i++;
			if ($i == 1) $wcnotes = $wcnotes . "<TR><TD>&nbsp;&nbsp;&nbsp;- " . $tmpfix . "</TD>";
			if ($i == 2) {
				$wcnotes = $wcnotes . "<TD>&nbsp;&nbsp;&nbsp;- " . $tmpfix . "</TD></TR>";
				$i =0;
			}
		
		
		} else	{
				$wcnotes = $wcnotes . "<TR><TD><BR>";
				$wcnotes .="</TD><TD> &nbsp </TD></TR>";			
		}


	}
	
	if($travindex <> -1){
		if (substr($wcnotes,-5,5)=='</TD>') $wcnotes.='<TD> &nbsp </TD></TR>';
		$tmpInfo = str_replace("@@tabledata@@",$wcnotes ,$tmpInfo);
		$tmpInfo = str_replace("merixlogo","<img src='images/merixlogo.PNG'></img>",$tmpInfo);
		$tmpInfo = preg_replace("/style='page-break-before: always'/","",$tmpInfo,1);
	}
	
	echo  $tmpInfo ;

	
	$page_footer = "<table width=100%><tr><td>Prepared By: $user_name</td><td>Approved By: $job_approved_by</td><td>Approved Date: $job_approved_date</td></tr></table>";

	echo $page_footer;
	

	function hasImg($job,$ts_item_id,$sequential_index,$conn){
		$result="No";
		$query = "select lob.blob_data
            from items i
                    ,items its
                    ,TRAV_SECT ts
                    ,TRAV_SECT_SNAP_NOTE tsn
                    ,snap_note sn
                    ,rev_controlled_lob lob
            where i.item_type=2 and i.root_id=its.root_id
            and its.item_id=ts.item_id
            and its.last_checked_in_rev=ts.revision_id
            and its.DELETED_IN_GRAPH is null
            and ts.item_id=tsn.item_id(+)
            and ts.revision_id=tsn.revision_id(+)
            and ts.sequential_index=tsn.SECTION_SEQUENTIAL_INDEX(+)
            and (sn.revision_id=(select max(revision_id) from revisions where item_id=sn.item_id)
                    or sn.revision_id is null)
            and sn.item_id(+)=tsn.snap_note_item_id
            and sn.ANNOTATED_PICTURE=lob.lob_id(+)
            and rownum=1
            and i.item_name='$job'
			and ts.sequential_index = $sequential_index
            and TS.item_id = $ts_item_id";
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

	function getPpSize($job,$conn){
		$result="";
		$query = "SELECT i.item_name,
                      mc.panel_size_1_x,
                      mc.panel_size_1_y,
                      mcs.side_for_measurement,
                      mcs.distance_from_corner
                 FROM items i
                     ,material_cut_tool mc
                     ,items i2
                     ,material_cut_stage mcs
                WHERE i.item_type=2
                  AND i.root_id=i2.root_id
                  AND mc.item_id=i2.item_id
                  AND mc.revision_id=i2.last_checked_in_rev
                  AND i2.deleted_in_graph is null
                  AND mc.item_id=mcs.item_id(+)
                  AND mc.revision_id=mcs.revision_id(+)
                  AND i2.item_name='CutTool_Prepreg'
                  AND i.item_name='$job'";
		
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			if ($row['SIDE_FOR_MEASUREMENT'] === null){
				$y = $row['PANEL_SIZE_1_Y'];
				$x = $row['PANEL_SIZE_1_X'];
				
			} else{
				if ($row['SIDE_FOR_MEASUREMENT'] ==1){
					$y = $row['DISTANCE_FROM_CORNER'];
				} else {
					$x = $row['DISTANCE_FROM_CORNER'];
				}
				
			}
		}
		if ($x>0  and $y>0) $result = $y/1000 . "\" (Warp) x " . $x/1000 . "\" (Fill)";
		return $result;
	
	}

	function getDrillType($job,$conn){
		$result="";
		$query = "SELECT DISTINCT ev.value
                     FROM   items i,
                            items i2,
                            drill_program_da dd,
                            enum_types et,
                            enum_values ev
                      WHERE i.item_type = 2
                        AND i2.root_id = i.root_id
                        AND dd.item_id = i2.item_id
                        AND dd.revision_id = i2.last_checked_in_rev
                        AND i2.deleted_in_graph IS NULL
                        AND et.type_name='PROGRAM_TYPE_'
                        AND et.enum_type=ev.enum_type
                        AND ev.enum=dd.drill_program_type_
                        AND i.item_name = '$job'";
		
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			if ($row['VALUE'] == 'Back Drill') {
				$result .= "<tr><td colspan=2>背钻板</td></tr>";
			} else if ($row['VALUE'] == 'Blind Vias') {
				$result .= "<tr><td colspan=2>肓孔板</td></tr>";
			} else if ($row['VALUE'] == 'BURIED_VIAS') {
				$result .= "<tr><td colspan=2>埋孔板</td></tr>";
			}
		}
		return $result;
	}

?>
</div>
<script type='text/javascript'>
	$(document).ready(function(){
		$('img').each(function(){
			if ($(this).attr('width')>700){
				$(this).attr('width',700);
			}
		})
	})
</script>
</html>