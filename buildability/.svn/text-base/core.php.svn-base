<?php
if (!$conn) require("oracle_conn.php");
$reason ='';
switch ($eng_code)
	{
		case "EB":
			if (file_exists("queries/etchback.sql")){
				$my_query = file_get_contents("queries/etchback.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				if (trim(strtoupper($row[2])) == 'NONE' || trim($row[2]) == ''){
					$this_status = 'NN';
				} else
				{
					$this_status = 'RR';
					$reason .= 'Etchback Type is '.$row[2].'<br/>';
				}

			}
			break;
		case "IL":
				//$my_query = 'select F_GET_LAYER_CNT(part_number, 10) from part';
				//$my_query .= " where part_number='$part_number'" ;
				//$stid = oci_parse($conn, $my_query);
				//$r = oci_execute($stid, OCI_DEFAULT);
				//$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				//if ($row[0] <= 2){
					$this_status = 'NN';
				//}
				break;
		case "SM":
				$this_status = 'NN';
				$my_query = file_get_contents("queries/surface_finish.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				$sf = trim($row[0]);
				$sm = trim($row[1]);
				if ($sf == 'SP/Silver'){
					$this_status = 'RR';
					$reason .= 'Surface finish is '.$sf.'<br/>';
					//break;
				} elseif ($sm =='Taiyo (BN) Green' || $sm =='Taiyo (MP) Green'){
					$this_status = 'RR';
					$reason .= 'Soldermask is '.$sm.'<br/>';
					//break;
				}
				$my_query ="select feild_value,feild_title_name from eng_area_entry_data where part_number_id=$part_number_id and (feild_title_name='Are soldermask SMD annular ring check rules violated' or feild_title_name='Multi-layer/multicolor SM or nomen')";
				$my_req=@mysql_query($my_query,$db);	
				while ($result=@mysql_fetch_array($my_req)){
					if ($result[0] == 'Yes'){
						$this_status = 'RR';
						$reason .= $result[1].'<br/>';
						//break;
					}
				}
				break;
		case "DR":
				$this_status = 'NN';
				//Part has CDD:
				$my_query = file_get_contents("queries/get_cdd.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
					$cdd = trim($row[0]);
					$drill_stack_high = $row[1];
					$Drill_Entry = $row[2];
					$Drill_Backer = $row[3];
					if ($cdd== 'Yes'){
						$this_status = 'RR';
						$reason .= 'This part number has CCD drill;';
						//break;
					} elseif ($drill_stack_high<>1){
						$this_status = 'RR';
						$reason .= 'This Drill stack high is '.$drill_stack_high.'<br/>';
						//break;
					}
				}
				//Etchback Spec:
				$my_query = file_get_contents("queries/etchback.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
					$etchback = trim($row[0]);
					if ($etchback== 'Special #2 (min 0.2, max 0.7-1.19)' || $etchback== 'Special #1 (min 0.2, max 1.2-1.4)' || $etchback== 'IPC6012 Class3A (0.2 - 1.5)' || $etchback== 'Special #3 (other); actual undefined'){
						$this_status = 'RR';
						$reason .= 'Etchback is '.$etchback.'<br/>';
						//break;
					}
				}
				//customer,class,material: Material type = 370HR or S1000-2, and thickness > 0.075, and entry/backer = composite/standard
				$my_query = file_get_contents("queries/drill_core.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				$owning_cust = trim($row[1]);
				$deliver_cust = trim($row[2]);
				$build_class = trim($row[3]);
				$inspection_class = trim($row[4]);
				$material_types = trim($row[5]);
				$thk = $row[6];
				$layer_count = $row[7];
				if ($owning_cust == 'L-3 CYTERRA CORP' || $owning_cust== 'SILICON GRAPHICS, INC.'){
					$this_status = 'RR';
					$reason .= 'Owning customer is '.$owning_cust.'<br/>';
					//break;
				} elseif ($deliver_cust == 'L-3 CYTERRA CORP'){ 
					$this_status = 'RR';
					$reason .= 'Deliver customer is '.$deliver_cust.'<br/>';
					//break;
				} elseif (strpos($material_types,'Taconic')){
					$this_status = 'RR';
					$reason .= 'Material type is '.$material_types.'<br/>';
				} elseif ($layer_count == 2 && $thk>0.24){
					$this_status = 'RR';
					$reason .= 'Layer count is 2, and board thickness > '.$thk.' inch<br/>';
				} elseif (strpos($material_types,'Taconic') && strpos($material_types,'~') ){
					$this_status = 'RR';
					$reason .= 'Material type is '.$material_types.'<br/>';
					//break;
				} elseif ((strpos($material_types,'370HR') || strpos($material_types,'S1000-2')) && $thk>0.075){
					//&& trim($Drill_Entry)=='Composite' && trim($Drill_Backer)=='Standard'
					$this_status = 'RR';
					$reason .= 'Material type:'.$material_types." Board thickness:$thk ".'<br/>';
					//Drill Entry:".trim($Drill_Entry).'/'.trim($Drill_Backer)
					//break;
				}
					
				// min of IL and OL annular ring < 4:
				$my_query = file_get_contents("queries/registration.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				$ol_ar = trim($row[1]);
				$il_ar = trim($row[2]);
				if (($build_class == 'Class III' || $inspection_class =='Class III' ) && ($ol_ar <0.004 && $ol_ar>0) || ($il_ar<0.004 && $il_ar>0)){
					$this_status = 'RR';
					$reason .= 'Build Class:'.$build_class.' and OL Annular Ring:'.$ol_ar.' IL Annular Ring:'.$il_ar.'<br/>';
					//break;
				}

				//Drill Program Types uses DF or DB
				$my_query = file_get_contents("queries/registration_2.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
					if (trim($row[1]) =='DF' || trim($row[1]) =='DB'){
						$this_status = 'RR';
						$reason .= 'Drill Program Type is '.$row[1].'<br/>';
						//break;
					}
				}

				//drill, slot, or finishing PT entered check boxes:
				$my_query ="select feild_value,feild_title_name from eng_area_entry_data where part_number_id=$part_number_id and 
									(feild_title_name='Score offset tolerance less than +/- .003 inches'
									or feild_title_name='Remaining score web less than .006 inches'
									or feild_title_name='Remaining web tolerance less than +/- .004 inches'
									or feild_title_name='Score edge to edge tolerance less than +/- .005 inches'
									or feild_title_name='Datum to score edge tolerance less than +/- .005 inches'
									or feild_title_name='Angle tolerance less than +/- 2 degrees'
									or feild_title_name='Remaining material thickness tolerance less than +/- .007 inches'
									or feild_title_name='Board edge to mill edge tolerance less than +/- .005 inches'
									or feild_title_name='Z-axis cleanrance to Cu feature less than .007 inches'
									or feild_title_name='Is the slot length &lt; 2 X bit diameter')";
				$my_req=@mysql_query($my_query,$db);	
					while ($result=@mysql_fetch_array($my_req)){
						if ($result[0] == 'Yes'){
						$this_status = 'RR';	
						$reason .= $result[1].'<br/>';
						//break;
					}
				}
				break;
		case "PL":
			$this_status = 'RR';
			$reason .= 'Plating is always Red'.'<br/>';
				$my_query = file_get_contents("queries/general.sql");
				$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
				$stid = oci_parse($conn, $my_query);
				$r = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
				if($row['MICROVIA_ASPECT_RATIO']>=0.8) {
					$reason .= 'Microvia Aspect Ratio >= 0.8 : 1'.'<br/>';
				}
			break;
		case "REG":
			$my_query ="select feild_value from eng_area_entry_data where part_number_id=$part_number_id and feild_title_name='Xact values status color'";
			$my_req=@mysql_query($my_query,$db);	
			$result=@mysql_fetch_array($my_req);
			if ($result[0] == 'Red' || $result[0] == 'Yellow'){
				$this_status = 'RR';
				$reason .= 'Xact values status color is '.$result[0].'<br/>' ;
			} else {
				$this_status = 'NN';
			}
			break;
		default:
			$this_status = 'NN';
			$reason ='';

	}
	
oci_close($conn);
?>