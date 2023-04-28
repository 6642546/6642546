<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	<!-- 
	.header {
			width:760px;
		}
    td{
      text-align: center;
    }
	table {
		font: 12px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px black;
	}
	table td{
			border:solid 1px black;
		
		}

	table th{
			border:solid 1px black;
		
	}

	img {
		cursor:pointer;
	}

	.inplan_attri_td{
			font: 12px/17px "Helvetica Neue",Helvetica,Arial,sans-serif;
			/*font-style:italic;*/
			font-weight:bold;
		}
	-->
    </style> 
<?php 
	$site = $_GET['site'];
	$job =  $_GET["job_name"];
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


			$my_query = "SELECT decode(JOB_DA.VIEW_FROM_SIDE_,4002,'CS',4003,'SS','None') as VIEW_FROM_SIDE_,
							decode(JOB_DA.GERBER_UNITS_,0,'mil','mm') as GERBER_UNITS_,
							JOB_DA.DRY_FILM_SIZE_OUTER_,
							JOB_DA.CUTTING_SIZE_,
							JOB_DA.TARGET_DISTANCE_,
							JOB_DA.SHEET_UTILIZATION_,
							decode(EV1.VALUE,'Dry Film',JOB_DA.DRY_FILM_SIZE_INNER_||'',EV1.VALUE) DF,
							EV1.VALUE
					FROM    ITEMS I,
							JOB,
							JOB_DA,
							PROCESS PROCESS1,
							ITEMS I1,
							PROCESS_DA ,
							ENUM_TYPES ET1,
							ENUM_VALUES EV1
					WHERE I.ITEM_ID=JOB.ITEM_ID
							AND I.ITEM_TYPE=2
							AND JOB.REVISION_ID=I.LAST_CHECKED_IN_REV
							AND JOB.ITEM_ID = JOB_DA.ITEM_ID
							AND JOB.REVISION_ID=JOB_DA.REVISION_ID
							AND I1.ITEM_ID=PROCESS1.ITEM_ID
							AND PROCESS1.REVISION_ID=I1.LAST_CHECKED_IN_REV
							AND I1.ROOT_ID=I.ROOT_ID
							AND I1.DELETED_IN_GRAPH IS NULL
							AND PROCESS1.PROC_SUBTYPE=29
							AND PROCESS1.ITEM_ID=PROCESS_DA.ITEM_ID
							AND PROCESS1.REVISION_ID=PROCESS_DA.REVISION_ID
							AND ET1.ENUM_TYPE=EV1.ENUM_TYPE
							AND ET1.TYPE_NAME='DF_TYPE_'
							AND EV1.ENUM=PROCESS_DA.DF_TYPE_
							AND i.item_name='$job'";
			 $rsmy = oci_parse($conn, $my_query);
			 oci_execute($rsmy, OCI_DEFAULT);
			 oci_fetch($rsmy);
			 $view_from = oci_result($rsmy, 1);
			 $gerber_units = oci_result($rsmy, 2);
			 $outer_df = oci_result($rsmy, 3)/1000 . "\"";
			 $cutting_size = oci_result($rsmy, 4);
			 $t_ = oci_result($rsmy, 5)/1000 . "\"";
			 $u_ = round(oci_result($rsmy, 6),3)."%";
			 if (oci_result($rsmy, 8) != 'Dry Film'){
				$inner_df = oci_result($rsmy, 7);
			 } else {
				$inner_df = oci_result($rsmy, 7)/1000 . "\"";
			 }
			 
?> 
<center>
<div id="enlarge_images"></div>
	<table border=1 style="width:788px">
		<tr><td width="16%">View From</td><td width="16%" class="inplan_attri_td"><?php echo $view_from ?></td><td width="16%">Inner Dry Film Size</td><td width="16%" class="inplan_attri_td"><?php echo $inner_df ?></td>
		<td width="16%">Array/Sheet</td><td width="16%" class="inplan_attri_td"><?php echo $u_ ?></td></tr>
		<tr><td width="16%">Demension in</td><td width="16%" class="inplan_attri_td"><?php echo $gerber_units ?></td><td width="16%">Outer Dry Film Size</td><td width="16%" class="inplan_attri_td"><?php echo $outer_df ?></td>
		<td width="16%"></td><td width="16%"></td></tr>
		<tr><td width="16%">Cutting Size</td><td width="16%" class="inplan_attri_td"><?php echo $cutting_size ?></td><td width="16%">T = </td><td width="16%" class="inplan_attri_td"><?php echo $t_ ?></td>
		<td width="16%"></td><td width="16%"></td></tr>
		<tr><td colspan=6 id="demo"><img width="720px" height="800px" alt="<?php echo getLang("Left click to view big picture",$lang)?>" src="<?php echo $pre_dir ?>/getpic.php?job_name=<?php echo $job ?>&pic_name=Dimensions" onclick="javascript:window.open (this.src, 'newwindow2', 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=no')"/></td></tr>
		<tr><td colspan=2><img width="235px" height="200px" src="<?php echo $pre_dir ?>/getpic.php?job_name=<?php echo $job ?>&pic_name=CutTool_Core" onclick="javascript:window.open (this.src, 'newwindow2', 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=no')"/></td>
			<td colspan=2><img width="235px" height="200px" src="<?php echo $pre_dir ?>/getpic.php?job_name=<?php echo $job ?>&pic_name=CutTool_Prepreg" onclick="javascript:window.open (this.src, 'newwindow2', 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=no')"/></td>
			<td colspan=2><img width="235px" height="200px" src="<?php echo $pre_dir ?>/getpic.php?job_name=<?php echo $job ?>&pic_name=CutTool_Foil" onclick="javascript:window.open (this.src, 'newwindow2', 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=no')"/></td>
		</tr>
		
	</table>
</center>
</html>
