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
	#drill_table {
		font: 12px/17px times,Arial,sans-serif;
	}
	#drill_table td{
			border:solid 1px black;
		
		}

	#drill_table th{
			border:solid 1px black;
			text-align: center;
			font-weight:bold;
		
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

<div class='header'><center><b><font size=+2>Drilling Table: <?php echo $job . " - " . $process  ?></font></b></center><br><br>
<center>
	<table id='drill_table' style='border-collapse:collapse;border:solid 2px black;width:85%;' border=1>
		<tr><th colspan=8>钻孔要求</th></tr>
		<tr><th width="40px">数量</th><th width="40px">代号</th><th width="120px">成品孔径要求</th><th width="60px">钻嘴号</th><th width="80px">钻咀直径</th><th width="40px">不电孔</th><th width="40px">二钻</th><th width="80px">备注</th></tr>
		<tr><th>Qty</th><th> Code</th><th>(Requirement)</th><th>Drill No</th><th>Drill Size</th><th>NPTH</th><th>2ND</th><th>Remark</th></tr>
		<?php
			
			$npart_num_in_array = 1;
			$job_query = "select i.item_name
                                ,JOB_DA.GERBER_UNITS_
                                ,decode(part.DELIVERABLE_TYPE,1,'Array',2,'PCB') as Deliver_Type
                                ,part.NUM_PCBS_IN_ARRAY
                          from items i,
                            job_da,
                            part
                          where i.item_type=2
                          and i.item_id=job_da.item_id
                          and job_da.revision_id=i.last_checked_in_rev
                          and job_da.item_id=part.item_id
                          and job_da.revision_id=part.revision_id
                          and i.item_name='$job'";

			$rsJob= oci_parse($conn, $job_query);
			oci_execute($rsJob, OCI_DEFAULT);
			oci_fetch($rsJob);
			if (oci_result($rsJob, 3) == 'PCB'){
				$qty = 'PCB' ;
			} else {
				$qty = oci_result($rsJob, 4)." UP";
				$npart_num_in_array = $qty;
			}

			$gerber_unit = oci_result($rsJob, 2); // 1 -- mm, 0 -- inch
		?>
		<tr><th><?php echo $qty ?></th><th> </th><th><?php echo $gerber_unit==1?'mm':'inch' ;?></th><th>(mm)</th><th>(inch)</th><th></th><th></th><th></th></tr>
		<?php
			include("drill_query.php");
			$rsDrill = oci_parse($conn, $sqlDrill);
			oci_execute($rsDrill, OCI_DEFAULT);

			while(oci_fetch($rsDrill)){
			
				$drillname = oci_result($rsDrill, 9);
				$remark =  oci_result($rsDrill, 10);
				
			    $vdrillqty = oci_result($rsDrill, 3) * $npart_num_in_array + oci_result($rsDrill, 5) + oci_result($rsDrill, 6);

				$vt_code =  oci_result($rsDrill, 20);
				if (substr($drillname,0,3) == 'TH_') $vt_code = "";

				if ($drillname == "TH_" || $remark == '阻抗测试孔' || $remark == '锣管位孔/SSGH' || $remark == 'Beep coupon' || $drillname == 'TH_XSECT'
					|| strpos($remark,'D/F T/H'==true) || $vdrillqty ==0 || $remark =='丝印挂板孔') {
					$vdrillqty ="";
				}
		

				$drill_type = oci_result($rsDrill, 25);
				$drill_type_t = "";
				if ($drill_type == 2 || $drill_type == 5 || $drill_type == 7) $drill_type_t = 'V';
				
				if ($gerber_unit ==0){
					$drill_size =oci_result($rsDrill, 11) / 1000;
					$finished_size_tol_plus = oci_result($rsDrill, 12) / 1000;
					$finished_size_tol_minus = oci_result($rsDrill, 13)/ 1000;
					$finished_length_tol_plus = oci_result($rsDrill, 14)/ 1000;
					$finished_length_tol_minus = oci_result($rsDrill, 15)/ 1000;
					$finished_length = oci_result($rsDrill, 16)/ 1000;
				} else {
					$drill_size =oci_result($rsDrill, 11) / 39.37;
					$finished_size_tol_plus = oci_result($rsDrill, 12) / 39.37;
					$finished_size_tol_minus = oci_result($rsDrill, 13)/ 39.37;
					$finished_length_tol_plus = oci_result($rsDrill, 14)/ 39.37;
					$finished_length_tol_minus = oci_result($rsDrill, 15)/ 39.37;
					$finished_length = oci_result($rsDrill, 16)/ 39.37;
				}
				

				if ($finished_size_tol_plus == $finished_size_tol_minus){
					if ($finished_length >0 ) {
						$v_drill_size = "(".sprintf("%.4f",$drill_size)."x".sprintf("%.4f",$finished_length).")". "+/-".sprintf("%.4f",$finished_size_tol_plus);;
					} 
					 else $v_drill_size = sprintf("%.4f",$drill_size) . "+/-".sprintf("%.4f",$finished_size_tol_plus);
				} else {
					if ($finished_size_tol_plus == 0){
						if ($finished_length >0 ) {
							$v_drill_size = "(".sprintf("%.4f",$drill_size)."x".sprintf("%.4f",$finished_length).")" . "+0/-".sprintf("%.4f",$finished_size_tol_minus);
						}else $v_drill_size = sprintf("%.4f",$drill_size) . "+0/-".sprintf("%.4f",$finished_size_tol_minus);
					} else if ($finished_size_tol_minus == 0){
						if ($finished_length >0 ) {
							$v_drill_size = "(".sprintf("%.4f",$drill_size)."x".sprintf("%.4f",$finished_length).")" . "+".sprintf("%.4f",$finished_size_tol_plus)."/-0";
						} else $v_drill_size = sprintf("%.4f",$drill_size) . "+".sprintf("%.4f",$finished_size_tol_plus)."/-0";
					} else {
						if ($finished_length >0 ) {
							 $v_drill_size = "(".sprintf("%.4f",$drill_size)."x".sprintf("%.4f",$finished_length).")" . "+".sprintf("%.4f",$finished_size_tol_plus)."/-".sprintf("%.4f",$finished_size_tol_minus);
						} else $v_drill_size = sprintf("%.4f",$drill_size) . "+".sprintf("%.4f",$finished_size_tol_plus)."/-".sprintf("%.4f",$finished_size_tol_minus);
					}
					
				}

				

				if (substr($drillname,0,3) == 'TH_') {
					$v_drill_size = $remark;
					if ($drill_type == 2) {
						$remark = "DF封孔";
					} else {
						$remark = "";
					}
					
				}

				$drill_bit_size =  substr(oci_result($rsDrill, 7),0,strlen(oci_result($rsDrill, 7))-2);
				$drill_bit =  sprintf("%.4f",oci_result($rsDrill, 8) /1000);
				

				$npt_type = oci_result($rsDrill, 17);
				$_2nd = "";
				if ($npt_type == '2nd Drill') {
					$_2nd = 'V';
				} else if ($npt_type == 'Tent'){
					$remark = "DF封孔";
				}
				
				if (strpos($remark ,'Laser')==true || $drill_size ==0 || $drillname == 'TH_XSECT' || $drillname == "TH_"){
					$drill_bit_size = '/';
					$drill_bit = '/';
				}


				$drill_program_drill_technology = oci_result($rsDrill, 26);

				if ($drill_program_drill_technology == 5){
					$counter_sink_angle_ = oci_result($rsDrill, 21);
					$drill_program_start_index = oci_result($rsDrill, 22);
					if ($drill_program_start_index == 1){
						$start_index = 'C.S';
					} else $start_index = 'S.S';
					$counter_bore_depth_req_ = oci_result($rsDrill, 23);

					if ($drill_type == 2 || $drill_type == 5 || $drill_type == 7) {$drill_type_t = "NPTH";} else $drill_type_t = "PTH";

					if ($finished_size_tol_plus == $finished_size_tol_minus){
							$v_drill_size ="Ø". sprintf("%.3f",$drill_size*1000/39.37) . "+/-".sprintf("%.3f",$finished_size_tol_plus*1000/39.37) . "mm $drill_type_t";
						} else {
							if ($finished_size_tol_plus == 0){
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size*1000/39.37) . "+0/-".sprintf("%.4f",$finished_size_tol_minus*1000/39.37). "mm $drill_type_t";;
							} else if ($finished_size_tol_minus == 0){
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size*1000/39.37) . "+".sprintf("%.4f",$finished_size_tol_plus*1000/39.37)."/-0". "mm $drill_type_t";;
							} else {
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size*1000/39.37) . "+".sprintf("%.4f",$finished_size_tol_plus*1000/39.37)."/-".sprintf("%.4f",$finished_size_tol_minus*1000/39.37). "mm $drill_type_t";;
							}
						}
					if ($finished_length_tol_plus == $finished_length_tol_minus){
							$v_drill_length ="Ø". sprintf("%.3f",$finished_length*1000/39.37) . "+/-".sprintf("%.3f",$finished_length_tol_plus*1000/39.37) . "mm $drill_type_t";
						} else {
							if ($finished_length_tol_plus == 0){
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length*1000/39.37) . "+0/-".sprintf("%.4f",$finished_length_tol_minus*1000/39.37). "mm $drill_type_t";;
							} else if ($finished_size_tol_minus == 0){
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length*1000/39.37) . "+".sprintf("%.4f",$finished_length_length_plus*1000/39.37)."/-0". "mm $drill_type_t";;
							} else {
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length*1000/39.37) . "+".sprintf("%.4f",$finished_length_tol_plus*1000/39.37)."/-".sprintf("%.4f",$finished_length_tol_minus*1000/39.37). "mm $drill_type_t";;
							}
						}

					$drill_bit_size = "A= $counter_sink_angle_<BR>"."B= $start_index<BR>"."C= $counter_bore_depth_req_<BR>"."D= $v_drill_size<BR>"."E= $v_drill_length<BR>";

					echo "<tr><td>$vdrillqty</td><td>$vt_code</td><td><img src='images/sink.PNG'></img></td><td style='text-align:left;' colspan=3>$drill_bit_size</td><td style='text-align:left;' colspan=2>$remark</td></tr>";
				} else if ($drill_program_drill_technology == 6){
					$counter_sink_angle_ = oci_result($rsDrill, 21);
					$drill_program_start_index = oci_result($rsDrill, 22);
					if ($drill_program_start_index == 1){
						$start_index = 'C.S';
					} else $start_index = 'S.S';
					$counter_bore_depth_req_ = oci_result($rsDrill, 23);

					if ($drill_type == 2 || $drill_type == 5 || $drill_type == 7) {$drill_type_t = "NPTH";} else $drill_type_t = "PTH";

					if ($finished_size_tol_plus == $finished_size_tol_minus){
							$v_drill_size ="Ø". sprintf("%.3f",$drill_size) . "+/-".sprintf("%.3f",$finished_size_tol_plus) . "inch $drill_type_t";
						} else {
							if ($finished_size_tol_plus == 0){
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size) . "+0/-".sprintf("%.4f",$finished_size_tol_minus). "inch $drill_type_t";;
							} else if ($finished_size_tol_minus == 0){
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size) . "+".sprintf("%.4f",$finished_size_tol_plus)."/-0". "inch $drill_type_t";;
							} else {
								$v_drill_size ="Ø". sprintf("%.3f",$drill_size) . "+".sprintf("%.4f",$finished_size_tol_plus)."/-".sprintf("%.4f",$finished_size_tol_minus). "inch $drill_type_t";;
							}
						}
					if ($finished_length_tol_plus == $finished_length_tol_minus){
							$v_drill_length ="Ø". sprintf("%.3f",$finished_length) . "+/-".sprintf("%.3f",$finished_length_tol_plus) . "inch $drill_type_t";
						} else {
							if ($finished_length_tol_plus == 0){
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length) . "+0/-".sprintf("%.4f",$finished_length_tol_minus). "inch $drill_type_t";;
							} else if ($finished_size_tol_minus == 0){
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length) . "+".sprintf("%.4f",$finished_length_length_plus)."/-0". "inch $drill_type_t";;
							} else {
								$v_drill_length ="Ø". sprintf("%.3f",$finished_length) . "+".sprintf("%.4f",$finished_length_tol_plus)."/-".sprintf("%.4f",$finished_length_tol_minus). "inch $drill_type_t";;
							}
						}

					$drill_bit_size = "A= $v_drill_size<BR>"."B= $counter_bore_depth_req_<BR>"."C= $v_drill_length<BR>"."D= $start_index<BR>";

					echo "<tr><td>$vdrillqty</td><td>$vt_code</td><td><img src='images/bore.PNG'></img></td><td style='text-align:left;' colspan=3>$drill_bit_size</td><td style='text-align:left;' colspan=2>$remark</td></tr>";
				} else {
					echo "<tr><td>$vdrillqty</td><td>$vt_code</td><td>$v_drill_size</td><td>$drill_bit_size</td><td>$drill_bit</td><td>$drill_type_t</td><td>$_2nd </td><td style='text-align:left;'>$remark</td></tr>";
				}

		}


		?>
	</table>
</center>
</div>
</div>
</html>