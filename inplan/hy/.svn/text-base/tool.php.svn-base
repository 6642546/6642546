<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	<!-- 
	.header {
			width:720px;
		}
    td{
      text-align: center;
    }
	#tool_table {
		font: 12px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px black;
	}
	#tool_table td{
			border:solid 1px black;
		
		}

	#tool_table th{
			border:solid 1px black;
		
	}

	
	-->
    </style> 
<div style="padding:10px;">
<?php
	$site = $_GET['site'];
	$job =  $_GET["job_name"];
	$process = $_GET["process"];
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
?> 

<div class='header'><center><b><font size=+2>Tooling Table: <?php echo $job . " - " . $process ?></font></b></center><br><br>
<center>
	<table id='tool_table' border=1>
		<tr><th width="80px">工具</th><th width="120px">版本</th><th width="80px">工具</th><th width="120px">版本</th></tr>
		<?php
			
			$my_query = "select count(itool.item_name)
							from items i
								,items itool
								,drill_tool dt
							where i.item_type=2
								and i.item_name='$job'
								and itool.root_id=i.root_id
								and itool.item_id=dt.item_id
								and dt.revision_id=itool.last_checked_in_rev
								and itool.deleted_in_graph is null
								and itool.item_name like 'D%(%)%'";
			 $rsmy = oci_parse($conn, $my_query);
			 oci_execute($rsmy, OCI_DEFAULT);
			 oci_fetch($rsmy);
			 $new_tool = oci_result($rsmy, 1);
			 


			$query = "SELECT i.item_name job_name
						,process.mrp_name proc_mrp_name
						,ev.value tool_type_t
						,ITOOL.ITEM_NAME tool_name
						,SUBSTR (itool.item_name,
											  1,
											  INSTR (itool.item_name, '_') - 1
											 ) tool_name_substr
						,tool.mrp_name || TOOL.MRP_REVISION tool_rev
						,TOOL.MRP_REVISION
						,tool.mrp_name
					FROM items i
						,items itool
						,tool tool
						,items ip
						,process process
						,links links
						,enum_values ev
						,enum_types et
					WHERE i.item_type=2
						and i.item_name ='$job'
						and itool.root_id=i.root_id
						and itool.item_id=tool.item_id
						and tool.revision_id=itool.last_checked_in_rev
						and itool.deleted_in_graph is null
						and ip.root_id=i.root_id
						and ip.item_id=process.item_id
						and process.revision_id=IP.LAST_CHECKED_IN_REV
						and IP.DELETED_IN_GRAPH is null
						and process.item_id=LINKS.ITEM_ID
						and LINKS.POINTS_TO = tool.item_id
						and LINKS.LINK_TYPE=14
						and EV.ENUM_TYPE = et.enum_type
						and et.type_name='TOOL_TYPE'
						and ev.enum = TOOL.TOOL_TYPE
						AND ev.value NOT IN ('BOM', 'Material Cut', 'Traveler')
						AND tool.mrp_name || tool.mrp_revision IS NOT NULL
						and process.mrp_name like '$process%'
						AND INSTR (itool.item_name, '_') > 0";


			$orderby1 = "ORDER BY CASE 
							 WHEN SUBSTR (tool_name, 1, 2) = 'IL'
								THEN 'A'||LPAD(SUBSTR (tool_name_substr, 3, 2),3,0)
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSO'
								THEN 'B001'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSO'
								THEN 'B002'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSN'
								THEN 'B003'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSN'
								THEN 'B004'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSS'
								THEN 'B005'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSS'
								THEN 'B006'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSC'
								THEN 'B007'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSC'
								THEN 'B008'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSD'
								THEN 'B009'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSD'
								THEN 'B010'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CIC'
								THEN 'B011'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SIC'
								THEN 'B012'                                                
							 WHEN SUBSTR (tool_name, 1, 3) = 'CIS'
								THEN 'B013'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SIS'
								THEN 'B014'
							 WHEN SUBSTR (tool_name, 1, 3) = 'CSP'
								THEN 'B015'
							 WHEN SUBSTR (tool_name, 1, 3) = 'SSP'
								THEN 'B016'
							 WHEN SUBSTR (tool_name, 2, 1) = 'P'
								THEN 'C'||tool_rev
							 ELSE 'D'
							END ASC";
				$orderby2 = "ORDER BY CASE 
							 WHEN SUBSTR (tool_name, 1, 2) = 'IL'
								THEN 'A'||LPAD(SUBSTR (tool_name_substr, 3, 2),3,0)
							 WHEN tool_type_t = 'Drill'
								THEN 'B'||LPAD(SUBSTR (tool_name_substr, 1, 2),3,0)
							 WHEN tool_type_t = 'Copper'
								THEN 'C001' 
							 WHEN INSTR(tool_name,'csn')>0
								THEN 'E004'
							 WHEN INSTR(tool_name,'ssn')>0
								THEN 'E005'
							 WHEN INSTR(tool_name,'t2dp')>0
								THEN 'E006'
							 WHEN INSTR(tool_name,'b2dp')>0
								THEN 'E007'
							 WHEN INSTR(tool_name,'csp')>0
								THEN 'E008'
							 WHEN INSTR(tool_name,'ssp')>0
								THEN 'E009'
							 WHEN INSTR(tool_name,'Dummy L1')>0
								THEN 'E010'
							 WHEN INSTR(tool_name,'Dummy')>0
								THEN 'E011'
							 WHEN INSTR(tool_name,'Blank IL1')>0
								THEN 'E012'
							 WHEN INSTR(tool_name,'Blank')>0
								THEN 'E013'
							 WHEN INSTR(tool_name,'csd')>0
								THEN 'E014'
							 WHEN INSTR(tool_name,'ssd')>0
								THEN 'E015'
							 WHEN tool_type_t = 'Solder Mask'
								THEN 'E002'
							 WHEN tool_type_t = 'Silk Screen'
								THEN 'E003'
							 WHEN tool_type_t = 'Custom'
								THEN 'E016'
							 WHEN tool_type_t = 'Rout'
								THEN 'E017'
							 ELSE 'F'
							END ASC";

			if ($new_tool ==0){
				$query .= $orderby1;
			} else {
				$query .= $orderby2;
			}

			$rsTool = oci_parse($conn, $query);
			oci_execute($rsTool, OCI_DEFAULT);
			$i = 0;
			
			$echo_text = "<tr>";
			while(oci_fetch($rsTool)){
				$tool_name = oci_result($rsTool, 5);
				switch($tool_name){
					case 'CSO':
						$tool_name = "CS线路";
						break;
					case 'SSO':
						$tool_name = "SS线路";
						break;
					case 'CSC':
						$tool_name = "CS印字";
						break;
					case 'SSC':
						$tool_name = "SS印字";
						break;
					case 'CSS':
						$tool_name = "CS绿油曝光";
						break;
					case 'SSS':
						$tool_name = "SS绿油曝光";
						break;
					case 'CSN':
						$tool_name = "CS绿油印油";
						break;
					case 'SSN':
						$tool_name = "SS绿油印油";
						break;
					case 'CSD':
						$tool_name = "CS塞孔";
						break;
					case 'SSD':
						$tool_name = "SS塞孔";
						break;
					case 'CSP':
						$tool_name = "CS蓝胶";
						break;
					case 'SSP':
						$tool_name = "SS蓝胶";
						break;
					case 'CIC':
						$tool_name = "CS碳油";
						break;
					case 'CIS':
						$tool_name = "SS碳油";
						break;
				
				
				
				}

				$i++;
				$echo_text .= "<td>".$tool_name."</td><td>".oci_result($rsTool, 6)."</td>";
				if ($i==2) {
					$echo_text .="</tr><tr>";
					$i=0;
				}
			}
			$echo_text .="</tr>";
			echo $echo_text;

		?>

	</table>
</center>
</div>
</div>
</html>