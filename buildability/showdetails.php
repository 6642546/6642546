<!--startprint-->
<?php
	$part_number=$_GET["part_number"];
	$part_number_id = $_GET["id"];
	$pt=$_GET["pt"];
	require "buildability/mysql_conn.php";
	require "buildability/oracle_conn.php";
	require "buildability/functions.php" ;
	$my_query = "select * from buildability where id=$part_number_id";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	$item_status = $result['status'];
	$last_updater = $result['update_user_name'];
	$last_update_date = $result['update_date'];
	$site = $result["site_name"];
	$email_send_note = $result["email_send_note"];
	$create_date = $result['create_date'];
	$create_user = $result['create_user_name'];

	//Jira CLT-2579 Plating thickness
	$top_plating_thk = 0;
	
	function getCurSubParts($part_number,$conn){
	if (file_exists("buildability/queries/get_subs.sql")){
			$my_query = file_get_contents("buildability/queries/get_subs.sql");
			$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
			$stid = oci_parse($conn, $my_query);
			$r = oci_execute($stid, OCI_DEFAULT);
			$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
			if (trim($row['SUBS'])){
				$sub_text= split('~',trim($row['SUBS']));
			}

	}

	return $sub_text;

}
	
	function getCurEngCode($section_name,$db){
		$my_query = "select eng_area_code from eng_area where eng_area_name='$section_name'";
		$req=@mysql_query($my_query,$db);	
		$result=@mysql_fetch_array($req);
		return $result[0];
	}

	function createSectionNotes($part_number_id,$section_name,$db){
		echo '<div class="micon">
			  <div class="comment">
				<div class="comment_title"><h3 style="float:left;">Note</h3><div style="float:right;">
					<a title="Add note" class="easyui-linkbutton" id="comment_add" plain="true" icon="icon-add">Add Note</a></div>
				</div>
				<div id="comments">';

		$my_query = "select * from buildability_note where part_number_id ='".trim($part_number_id)."' and eng_area_code='$section_name'";
		//echo $my_query;
		$req=@mysql_query($my_query,$db);	
		$i=0;
		while($return=@mysql_fetch_array($req)){
		$i+=1;
		echo '<div class="comment_note">
					<div class="comment_note_title"><h5 style="float:left;">#</h5><h4 style="float:left;">'.$i.'</h4><h3 style="float:left;">User Name:'.$return["update_user_name"].'</h3>
					<div title="Delete note" style="float:right;" class="note_del_btn"></div>
					<div title="Edit note" style="float:right;" class="note_edit_btn"></div>
					<h3 style="float:right;">At:'.$return["update_date"].'</h3>
					<div class="note_number">'.$return["note_number"].'</div>
					</div>
					<div class="this_note">'
					.$return["note_text"].
					'</div>
			</div>';
		}
		echo '</div>
			</div>
		</div>';
	}


	function createAreaDom($part_number,$part_number_id,$section_name,$db,$conn,$site){
		$eng_area_code = getCurEngCode($section_name,$db);
		// get section status here:
		$my_query = "select status,update_date,update_user_name,reason from eng_area_status where part_number_id='".trim($part_number_id)."' and eng_area_code='$eng_area_code' order by id desc limit 0,1";
		//echo $my_query;
		$req=@mysql_query($my_query,$db);
		$result=@mysql_fetch_array($req);
		$status=$result[0];
		$update_date=$result[1];
		$update_user_name=$result[2];
		$reason = $result[3];
		

			echo '<div class="box_b">
					<div class="content_textarea_h">
					<div class="expandable_b">
					<div class="build_box_title"><h4 class ="title_b">'.$section_name.'</h4></div>
					<div class="box_1">
					<ul class="hot_box">
						<table border="0px" width="100%">
						<tr>
							<td width="35%" height="25px">Status</td>
							<td width="60%"><span class="right_region"></span>';
							//if ($status == 'RR'){
							//	echo '<span class="radio_red _selected"><input type="radio" checked="true" name="'.$section_name.'"></input><label>Review //Required</label></span>';
							//} else {
								//echo '<span class="radio_red"><input type="radio" name="'.$section_name.'"></input><label>Review Required</label></span>';
								
							//}
							$section_id= str_ireplace(' ','_',$section_name);
							if ($status == 'RR'){
								echo '<span class="area_status" style="background-color:red">
										<select class="my_items" id="'.$section_id.'" title="Review Required" onchange="do_change_status(this)">
											<option value="Review Required" selected="true">Review Required</option>
											<option value="Not Needed">Not Needed</option>
											<option value="Eng Complete">Eng Complete</option>
											<option value="PT Completed">PT Completed</option>
										</select>
									 </span>';
							} elseif ($status == 'NN'){
								echo '<span class="area_status" style="background-color:gray">
										<select class="my_items" id="'.$section_id.'" title="Not Needed" onchange="do_change_status(this)">
											<option value="Review Required">Review Required</option>
											<option value="Not Needed" selected="true">Not Needed</option>
											<option value="Eng Complete">Eng Complete</option>
											<option value="PT Completed">PT Completed</option>
										</select>
									 </span>';
							} elseif ($status == 'EC'){
								echo '<span class="area_status" style="background-color:yellow">
										<select class="my_items" id="'.$section_id.'" title="Eng Complete" onchange="do_change_status(this)">
											<option value="Review Required">Review Required</option>
											<option value="Not Needed">Not Needed</option>
											<option value="Eng Complete" selected="true">Eng Complete</option>
											<option value="PT Completed">PT Completed</option>
										</select>
									 </span>';
							} elseif ($status == 'PC'){
								echo '<span class="area_status" style="background-color:yellowgreen">
										<select class="my_items" id="'.$section_id.'" title="PT Completed" onchange="do_change_status(this)">
											<option value="Review Required">Review Required</option>
											<option value="Not Needed">Not Needed</option>
											<option value="Eng Complete">Eng Complete</option>
											<option value="PT Completed" selected="true">PT Completed</option>
										</select>
									 </span>';
							}
							/*if ($status == 'NN'){
								echo '<span class="radio_gray _selected"><input type="radio" checked="true" name="'.$section_name.'"/></input><label>Not Needed</label></span>';
							} else {
								echo '<span class="radio_gray"><input type="radio" name="'.$section_name.'"/></input><label>Not Needed</label></span>';
							}
							if ($status == 'EC'){
								echo '<span class="radio_yellow _selected"><input type="radio" checked="true" name="'.$section_name.'"/></input><label>Eng Complete</label></span>';
							} else {
								echo '<span class="radio_yellow"><input type="radio" name="'.$section_name.'"/></input><label>Eng Complete</label></span>';
							}
							if ($status == 'PC'){
								echo '<span class="radio_green _selected"><input type="radio" checked="true" name="'.$section_name.'"/></input><label>PT Completed</label></span>';
							} else {
								echo '<span class="radio_green"><input type="radio" name="'.$section_name.'"/></input><label>PT Completed</label></span>';
							}*/
				echo '</td>
						</tr>';
						
				echo	'<tr>
							<td>Updated by: <span class="update_uname">'.$update_user_name.'</span></td><td>Last Update:<span class="update_upass">'.$update_date.'</span></td>
						</tr>
							';
				if ($reason){
					$reasons = split('\<br\/\>',$reason);
					$reason = '<br/>';
					for ($k=0;$k<count($reasons);$k++){
						if (trim($reasons[$k])!=''){
							$reason .= $k+1 . '. '.$reasons[$k].'<br/>';
						}
					}
					echo '<tr class="micon">
							<td colspan=2 style="border: 1px solid #99bbdd;">Red status reason : '.$reason.'</td>
						</tr>';
				}
						if ($section_name == 'Inner Layer' || $section_name == 'Plating'){
							require "entry_form.php";
							createSectionTableAttr($part_number,$conn,$section_name.'.sql');
							} 
						elseif ($section_name == 'Drill'){
							drillEngAr($part_number,$conn,'registration.sql');
							require "entry_form.php";
						}
						else {createSectionAttr($part_number,$conn,$section_name.'.sql',$db);
							require "entry_form.php";};	
						if($section_name == 'Plating') {
							createSectionTableAttr($part_number,$conn,$section_name.'_3.sql');
							createSectionTableAttr($part_number,$conn,$section_name.'_2.sql');
						 } else {
							createSectionTableAttr($part_number,$conn,$section_name.'_2.sql');
						 }

						if ($section_name == 'Plating'){
							createSectionTableAttr($part_number,$conn,'registration_2.sql');
							plating_program($part_number,$part_number_id,$sub_part,$db,$conn);
							$subs= getCurSubParts($part_number,$conn);
							if (count($subs)){
								for ($i=0;$i<count($subs);$i++){
									plating_program($part_number,$part_number_id,$subs[$i],$db,$conn);
								}
								
							}
						}
						echo	'
						</table>';
						createSectionNotes($part_number_id,$eng_area_code,$db);
						echo '</ul>
							</div>
							</div>
							</div>
							</div>';
	
	
	}

	function CreateGeneralNoteDom($part_number_id,$section_name,$db){
		$my_query = "select note_text from buildability_note where part_number_id='".trim($part_number_id)."' and eng_area_code='GN'";
		$req=@mysql_query($my_query,$db);
		$result=@mysql_fetch_array($req);
		$note=$result[0];
		echo '<div class="box_b">
				<div class="build_box_title"><h3>General Notes</h3><span style="float:right; width:90px;">
				<a class="easyui-linkbutton" id="gn_comment_add" plain="true" icon="icon-add">Add Note</a></span>
			   </div>
			<div class="box_1">
			<ul class="hot_box" style="min-height:20px;">
				'.$note.'
			</ul>
			</div>
			</div>';
	}

	function CreatGNDom($part_number,$part_number_id,$conn,$query,$last_update_date,$last_updater,$pt,$item_status,$email_send_note,$site,$create_date,$create_user){
		$my_query = file_get_contents("buildability/queries/$query");
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		// Head
		echo '<div class="grid">
				<div class="box_b">
				<div class="build_box_title"><h3 style="float:left">Buildability Details for Part Number: <a id="part_number">'.$part_number.'</a></h3><span id="print" style="float:right">Convert this page to PDF</span><span id="pn_id" style="display:none;">'.$part_number_id.'</span></div>
				<div class="box_1">
				<ul class="hot_box">
					<table border="0px" width="100%">
									<tr>
									<td width="25%">Owning Customer Name:</td><td width="25%" class="attr_style">'.$row['OWNING_CUSTOMER_NAME'].'</td>
									<td width="25%">Ship to Customer Name:</td><td width="25%" class="attr_style">'.$row['DELIVERABLES_CUSTOMER_NAME'].'</td>
									</tr>
									<tr>
									<td>Part Number:</td><td class="attr_style">'.$part_number.'</td>
									<td>PT:</td><td class="attr_style">'.$pt.'</td>
									</tr>
									<tr>
									<td>Sub-part Numbers:</td><td class="attr_style">'.$row['SUBS'].'</td>
									<td>Panel size:</td><td class="attr_style">'.$row['FLAT_SIZE'].'</td>
									</tr>
									<tr>
									<td>Down Rev Part Numbers:</td><td class="attr_style">'.$row['DOWN_REV_PART_NUMBER'].'</td>
									<td>Do up rev and down rev stack-ups match?:</td><td class="attr_style">'.$row['BOM_COMPARE'].'</td>
									</tr>
									<tr>
									<td>Most recent down rev build date:</td><td class="attr_style">'.$row['DOWN_REV_LAST_ORDER_SHIP_DATE'].'</td>
									<td>Most recent down rev part number yield:</td><td class="attr_style">'.$row['DOWN_REV_6_MONTH_YIELD'].'</td>
									</tr>
									<tr>
									<td>Creation Date:</td><td id="item_create_date" class="attr_style">'.$create_date.'</td>
									<td>Created by:</td><td class="attr_style">'.$create_user.'</td>
									</tr>
									<tr>
									<td>Last Update:</td><td class="attr_style">'.$last_update_date.'</td>
									<td>Update by:</td><td class="attr_style">'.$last_updater.'</td>
									</tr>
									
					</table>
				</ul>
				</div>
				</div>';
		//general
		echo '<div class="box_b">
				<div class="build_box_title"><h3>General Infomation</h3></div>
				<div class="box_1">
				<ul class="hot_box">
					<table border="0px" width="100%">
									<tr>
									<td width="25%">Technology Type(i.e. HDI,Cavity):</td><td width="25%"  class="attr_style">'.$row['TECHNOLOGY_TYPES'].'</td>
									<td width="25%">Status:</td><td width="25%">';
									if ($item_status=='O') {
										echo '<span class="radio_open _selected"><input type="radio" checked="true" name="status"></input><label>Open</label></span>
											 <span class="radio_close"><input type="radio" name="status"></input><label>Completed</label></span>';
									
									} else {
										echo '<span class="radio_open"><input type="radio" name="status"></input><label>Open</label></span>
											 <span class="radio_close _selected"><input type="radio" checked="true" name="status"></input><label>Completed</label></span>';
									}
									
		echo '
									
									</td>
									</tr>';
		echo '<tr><td>Layer Count:</td><td  class="attr_style">'.$row['LAYER_COUNT'].'</td><td>Run Logic:</td><td><input type="button" value="Run Logic" id="run_logic" title="Run system login again."></input></td></tr>';

		if (file_exists("inplan/hy") and ($site == "HY" or $site == "HZ")){
				$folder = "hy";
			} else if (file_exists("inplan/gz") and ($site == "GZ" or $site== "ZS")){
				$folder = "gz";
			} else if (file_exists("inplan/sj") and ($site == "SJ" or $site== "FG")){
				$folder = "sj";
			} else $folder = "allsites";

		echo '<tr><td>Aspect Ratio:</td><td  class="attr_style">'.$row['ASPECT_RATIO'].'</td><td>View Stackup:</td><td><a href="inplan/'.$folder.'/stackup.php?site='.$site.'&job_name='.$part_number.'_stk" target = "_blank"><img src="images/stackup.png" alt ="View stackup"></a></td></tr>';
		if($row['MICROVIA_ASPECT_RATIO']>=0.8) {
			echo '<tr><td>Microvia Aspect Ratio:</td><td  class="attr_style"><span style="background-color:red;font-weight:bold;">'.$row['MICROVIA_ASPECT_RATIO'].'</span></td><td></td><td></td></tr>';
		} else {
			echo '<tr><td>Microvia Aspect Ratio:</td><td  class="attr_style">'.$row['MICROVIA_ASPECT_RATIO'].'</td><td></td><td></td></tr>';
		}

		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; $i++) {
			$fileName=ucwords(strtolower(str_ireplace('_',' ',oci_field_name($stid, $i))));
			if ($fileName != 'Part Number' && $fileName != 'Subs' && $fileName != 'Owning Customer Name' && $fileName != 'Flat Size' && $fileName != 'Deliverables Customer Name' && $fileName != 'Down Rev Last Order Ship Date' && $fileName != 'Down Rev Part Number' && $fileName != 'Bom Compare' && $fileName != 'Down Rev Parts' && $fileName != 'Layer Count' && $fileName != 'Technology Types' && $fileName != 'Down Rev 6 Month Yield' && $fileName != 'Aspect Ratio' && $fileName != 'Microvia Aspect Ratio') {
				echo '<tr><td>'.$fileName.':</td><td  class="attr_style">'.$row[$i-1].'</td><td></td><td></td></tr>';
			}
				
		}
												
									
			echo '
					</table>
				</ul>
				</div>
				</div>';


	
	
	
	}


function plating_program($part_number,$part_number_id,$sub_part,$db,$conn){
	global $top_plating_thk;
	$cur_part = $part_number;
	if ($sub_part) $cur_part= $sub_part;
	$dom = '<tr class="micon"><td colspan=2><table class="list"><tr><th colspan="7" style="text-align:center;">Plating Programs for <span class="cur_part_number">'.$cur_part.'</span></th></tr><tr><th>Plating Programs</th><th>WC</th><th>Override</th><th>Cu</th><th>Ni</th><th>Sn</th><th>Au</th></tr>';
	$mysql = "select * from plating_program where part_number_id='$part_number_id'";
	$mysql .= " and sub_part='$sub_part'";
	$req=@mysql_query($mysql,$db);	
	while ($result=@mysql_fetch_array($req)){
		if (file_exists("buildability/queries/plating_thickness.sql")){
		$my_query = file_get_contents("buildability/queries/plating_thickness.sql");
		$my_query .=' AND trim(ID)='."'".$result['override_id']."'";
		if ($result['plating_program_name']=='Tin'){
			$my_query = str_ireplace('@@replace_str@@',"('T',' ')",$my_query);
		} elseif ($result['plating_program_name']=='Gold: Nickel/Gold' || $result['plating_program_name']=='Gold 2'){
			$my_query = str_ireplace('@@replace_str@@',"('G','F',' ')",$my_query);
		} elseif ($result['plating_program_name']=='Gold: Copper'){
			$my_query = str_ireplace('@@replace_str@@',"('U',' ')",$my_query);
		} elseif ($result['plating_program_name']=='Cu Buildup Strike' || $result['plating_program_name']=='Strike' || $result['plating_program_name']=='Strike 2nd Pass' || $result['plating_program_name']=='Strike - Special' || $result['plating_program_name']=='VF Strike'){
			$my_query = str_ireplace('@@replace_str@@',"('S',' ')",$my_query);
		} elseif ($result['plating_program_name']=='Pattern Button-Spc' || $result['plating_program_name']=='VF Button'){
			$my_query = str_ireplace('@@replace_str@@',"('B',' ')",$my_query);
		} elseif ($result['plating_program_name']=='CuF Strike'){
			$my_query = str_ireplace('@@replace_str@@',"('C',' ')",$my_query);
		} else
			{
			$my_query ="";
		}
		//echo $my_query;

		// define wc here:
		switch ($result['plating_program_name']){
			case "Tin":
				$wc = "0200";
				break;
			case "Gold: Nickel/Gold":
				$wc = "0160,0168,0637";
				break;
			case "Gold: Copper":
				$wc = "0160,0167,0168";
				break;
			case "Gold 2":
				$wc = "0161,0169,0637";
				break;
			case "Cu Buildup Strike":
				$wc = "0114";
				break;
			case "Strike":
				$wc = "0105";
				break;
			case "Strike 2nd Pass":
				$wc = "0206";
				break;
			case "Strike - Special":
				$wc = "0113";
				break;
			case "Pattern Button-Spc":
				$wc = "0163";
				break;
			case "VF Strike":
				$wc = "0104";
				break;
			case "VF Button":
				$wc = "0162";
				break;
			case "CuF Strike":
				$wc = "0106";
				break;
			default:
				$wc = "";
		}

		if ($my_query){
			$stid = oci_parse($conn, $my_query);
			$r = oci_execute($stid, OCI_DEFAULT);
			$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
			$dom .='<tr><td width="220px">'.$result['plating_program_name'].'</td><td width="100px">'.$wc.'</td><td width="260px"><span style="float:left;">'.$result['override'].'</span><img class="plating_attr_edit" src="images/pencil.png"  alt="Edit field..." /></td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td></tr>';
			if(substr($cur_part,0,2) == '81') {
				if($result['plating_program_name'] == 'Tin' or $result['plating_program_name'] == 'Gold: Copper' or $result['plating_program_name'] == 'Gold 2'
					or $result['plating_program_name'] == 'Cu Buildup Strike' or $result['plating_program_name'] == 'Strike' or $result['plating_program_name'] == 'Strike 2nd Pass' or $result['plating_program_name'] == 'Strike - Special' or $result['plating_program_name'] == 'VF Strike') {
					$top_plating_thk += getPlatingTHk($row[2],$row[3],$result['plating_program_name']);
				}
				
			}
			
		} else {
			$dom .='<tr><td style="font-weight:bold;" width="220px">'.$result['plating_program_name'].'</td><td width="100px">'.$wc.'</td><td width="260px"><span style="float:left;">'.$result['override'].'</span><img class="plating_attr_edit" src="images/pencil.png"  alt="Edit field..." /></td><td></td><td></td><td></td><td></td></tr>';
		}
		
		}
		
	}
	$dom.='</table></tr>';

	echo $dom;
}

//Jira CLT-2579
function getPlatingTHk($cu_think,$ni_think,$plating_program_name) {
	if(intval($cu_think)>0)	$thk = intval($cu_think);
	if(intval($ni_think)>0) $thk += intval($ni_think)+500;
	if($plating_program_name == 'CuF Strike' and intval($cu_think)>0) {
		$thk += 500;
	}
	return $thk;
}



function drillEngAr($part_number,$conn,$query){
	$query = strtolower($query);
		if (file_exists("buildability/queries/$query")){
		$my_query = file_get_contents("buildability/queries/$query");
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; $i++) {
			$fileName=ucwords(strtolower(str_ireplace('_',' ',oci_field_name($stid, $i))));
			$fileName=str_ireplace(' Ol',' OL',$fileName);
			$fileName=str_ireplace('Ol ','OL ',$fileName);
			if ($fileName != 'Part Number' && $fileName != 'Drill Programs' ) {
				echo '<tr class="micon"><td>'.$fileName.':</td><td class="attr_style">'.$row[$i-1].'</td></tr>';
			}	
		}
		}
}

function createSectionAttr($part_number,$conn,$query,$db){
		global $top_plating_thk;
		$query = strtolower($query);
		if (file_exists("buildability/queries/$query")){
		$my_query = file_get_contents("buildability/queries/$query");
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		$ncols = oci_num_fields($stid);
		date_default_timezone_set('PDT');
		$date = date("Y-m-d  G:i:s",time());
		for ($i = 1; $i <= $ncols; $i++) {
			$fileName=ucwords(strtolower(str_ireplace('_',' ',oci_field_name($stid, $i))));
			$fileName=str_ireplace(' Ol',' OL',$fileName);
			$fileName=str_ireplace('Ol ','OL ',$fileName);
			if ($fileName != 'Part Number' && $fileName != 'Subs' && $fileName != 'Owning Customer Name') {
				if($query == "soldermask.sql") {
					$total_cu_thk = $row[$i-1]+$top_plating_thk/1000;
					if( $fileName=="Top OL Thickness") {
						echo '<tr class="micon"><td>'.$fileName.':</td><td class="attr_style">'.$row[$i-1].' + '.($top_plating_thk/1000) .' = '.$total_cu_thk ;
						if($total_cu_thk > 3) {
							echo "<font color=red>&nbsp&nbsp Metal thickness is greater than 3 mils, see web optimization table for soldermask. Retool for SM webs may be required.</font>";
							//if the status is gray then update status here
							$my_query = "select status from eng_area_status where part_number='$part_number' and eng_area_code='SM' order by id desc limit 0,1";
							$req=@mysql_query($my_query,$db);	
							$result=@mysql_fetch_array($req);
							$cur_status=$result[0];
							if($cur_status == 'NN') {
								$my_query = "select id from buildability where part_number='$part_number' and status='O'";
								$my_req=@mysql_query($my_query,$db);
								$return = @mysql_fetch_array($my_req);
								$part_number_id = $return[0];
								$reason = addslashes('OL thickness > 3 mil.');
								//$query = "update eng_area_status set status='EC',reason='$reason' where part_number_id=$part_number_id and eng_area_code='SM'";	
								$query = "insert into eng_area_status (part_number,part_number_id,eng_area_code,status,update_date,update_user_name,reason) 
										  values ('$part_number','$part_number_id','SM','EC','$date','Logic','$reason')";
								if (mysql_query($query,$db)){
									$_status ="<div class=\"status\">";
									$my_query = "select eas.status,eas.eng_area_code,ea.display_sequence from eng_area_status eas,eng_area ea where part_number ='".trim($part_number)."' and eas.part_number_id='$part_number_id' and eas.eng_area_code=ea.eng_area_code and eas.id = (select max(id) from eng_area_status where part_number_id=eas.part_number_id and eas.eng_area_code=eng_area_code) order by ea.display_sequence";
									$my_req=@mysql_query($my_query,$db);
									while ($return = @mysql_fetch_array($my_req)){
										if ($return['status'] =='RR') $_status .= "<span style=\"background:red;\">".$return['eng_area_code']."</span>";
										if ($return['status'] =='NN') $_status .= "<span style=\"background:gray;\">".$return['eng_area_code']."</span>";
										if ($return['status'] =='EC') $_status .= "<span style=\"background:yellow;\">".$return['eng_area_code']."</span>";
										if ($return['status'] =='PC') $_status .= "<span style=\"background:yellowgreen;\">".$return['eng_area_code']."</span>";
										if (!$return['status']) $_status .= "<span>".$return['eng_area_code']."</span>";
									}
								
									$_status .="</div>";
									$final_status = $_status; 
									$my_query = "update buildability set eng_area_status='$final_status' where part_number='$part_number' and id='$part_number_id'";
										if (mysql_query($my_query,$db)){
											
										}
									} 
								
							}
						}
						echo '</td></tr>';
					} elseif ( $fileName=="Bottom OL Thickness"){
						echo '<tr class="micon"><td>'.$fileName.':</td><td class="attr_style">'.$row[$i-1].' + '.($top_plating_thk/1000) .' = '.$total_cu_thk .'</td></tr>';
					} else {
						echo '<tr class="micon"><td>'.$fileName.':</td><td class="attr_style">'.$row[$i-1].'</td></tr>';
					}
				} else {
					echo '<tr class="micon"><td>'.$fileName.':</td><td class="attr_style">'.$row[$i-1].'</td></tr>';
				}
				
			}	
		}
		}
	
	}

function createSectionTableAttr($part_number,$conn,$query){
		$query = strtolower($query);
		$has_recode=0;
		if (file_exists("buildability/queries/$query")){
		$my_query = file_get_contents("buildability/queries/$query");
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$ncols = oci_num_fields($stid);
		$text = '<tr class="micon"><td colspan=2><table class="list"><tr>';
		for ($i = 1; $i <= $ncols; $i++) {
				$fileName=ucwords(strtolower(str_ireplace('_',' ',oci_field_name($stid, $i))));
				$fileName=str_ireplace('Ol','OL',$fileName);
				if($query=='plating.sql' || $query=='plating_3.sql'  ) {
						$text .='<th>'.$fileName.'</th>';
				} elseif ($fileName != 'Part Number' && $fileName != 'Layup Sequence'){
					$text .='<th>'.$fileName.'</th>';
				}
				
			}
		$text .='</tr>';
		while ($return = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			$text .='<tr>';
			for ($i = 1; $i <= $ncols; $i++) {
				if (trim($return[$i-1]) != trim($part_number) ){
					if($query=='plating.sql' || $query=='plating_3.sql'  ) {
						$text .='<td>'.$return[$i-1].'</td>';
					} elseif ($i==2 && ($query=='plating.sql' || $query=='plating_2.sql')){
						$text =$text ;
					} else	{
						$text .='<td>'.$return[$i-1].'</td>';
					}
				} else {
					if($query=='plating.sql' || $query=='plating_3.sql'  ) {
						$text .='<td>'.$return[$i-1].'</td>';
					}
				}
				
			}
			$text .='</tr>';
			$has_recode = 1;
		}
		$text .= '</table></td></tr>';
		if ($has_recode) echo $text ;
		}
	}

function getEntryValue($part_number_id,$site,$feildName,$db){
		$mysql = "select * from eng_area_entry_data where part_number_id='$part_number_id' and feild_title_name='$feildName' and site_name='$site'";
		//echo $mysql;
		$req=@mysql_query($mysql,$db);	
		$result=@mysql_fetch_array($req);
		$text = $result['feild_value'];
		$feildName = str_ireplace('.','_',str_ireplace('+/-','_',str_ireplace(' ','_',$feildName)));
		$feildName = str_ireplace('&lt;','_',$feildName);
		$feildName = str_ireplace(',','_',$feildName);
		$feildName = str_ireplace('/','_',$feildName);
		if ($text){
			if ($result['updated_by']){
				$text = '<span class="td_value" id="'.$feildName.'">'.$text.'</span><span style="padding-left:30px;" id="'.$feildName.'_1'.'">Updated by '.$result['updated_by'].' at '.$result['update_date'].'</span>';
			} else {
				$text = '<span class="td_value" id="'.$feildName.'">'.$text.'</span><span style="padding-left:30px;" id="'.$feildName.'_1'.'"></span>';
			}
			
		} else {
			$text = '<span class="td_value" id="'.$feildName.'"></span><span style="padding-left:30px;" id="'.$feildName.'_1"></span>';
		}
		return $text;
	
	}
		
		CreatGNDom($part_number,$part_number_id,$conn,'general.sql',$last_update_date,$last_updater,$pt,$item_status,$email_send_note,$site,$create_date,$create_user);
		CreateGeneralNoteDom($part_number_id,'General',$db,$conn);
		$my_query = 'select * from eng_area order by display_sequence';
		$req=@mysql_query($my_query,$db);	
		while ($result=@mysql_fetch_array($req)){
			if ($result['eng_area_name']!='Header' && $result['eng_area_name']!='General'){
				createAreaDom($part_number,$part_number_id,$result['eng_area_name'],$db,$conn,$site);
			}
		}
		mysql_close($db);
		oci_close($conn);
	?>

	

	
	<div class="box_b">
	</div>
</div>
<!---endprint-->