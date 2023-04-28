<?php
	error_reporting(1);

	$page = $_POST['page'];
	$rp = $_POST['rows'];
	$sortname = $_POST['sort'];
	$sortorder = $_POST['order'];
	$keyword =  strtoupper($_POST['keyword']);
	$criteria = $_POST['criteria'];
	$colt_criteria = $_POST['colt_criteria'];
	$entry_criteria = $_POST['entry_criteria'];
	//$colt_criteria = 'true';
	$colt_feild = $_POST['colt_feild'];
	$entry_feild = $_POST['entry_feild'];
	//$colt_feild = 'Layer count';
	$feild_relation = $_POST['feild_relation'];
	$feild_relation_1 = $_POST['feild_relation_1'];

	$site=$_GET['site'];

	if (!$db) require "mysql_conn.php";
	$query = "select distinct b.part_number,b.id,b.manufacturing_due_date,b.eng_area_status from buildability b,buildability_note bn where b.site_name='$site' and b.id=bn.part_number_id";

	if ($criteria== 'true' ){

		if ($keyword) $query = $query . " and bn.note_text like '%$keyword%'";

		//echo $query;
		
		$count=@mysql_query("select count(*) from ($query) db",$db);	
		$count_nu=@mysql_fetch_array($count);
		$count_nu=$count_nu[0];


		if (!$sortorder) $sortorder = 'asc';
					
		$sort = "ORDER BY b.$sortname $sortorder";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;

		$start = (($page-1) * $rp);

		$query = $query . " $sort";

		$limit = " limit $start, ".$rp*$page;

		$query = $query . " $limit";

		} else if ($entry_criteria == 'true' and $keyword != '') {
			$total=0;
			$my_query = "select feild_value from eng_area_entry_data where part_number_id = '@@id@@' and feild_title_name = '$entry_feild' ";
			$my_query = whatRelation($keyword,$my_query,'feild_value',$feild_relation_1);
			//echo $my_query;
		
		
		}  else if ($colt_criteria =='true' and $keyword != ''){
			$total=0;
			if (!$conn) require("oracle_conn.php");
			$general_feilds = file_get_contents("queries/general_feilds.txt");
			$general_feilds = split(',',$general_feilds);
			$isGeneral_feilds = false;
			for ($i=0;$i<count($general_feilds);$i++){
				if ($colt_feild == $general_feilds[$i]) {
					//echo  $general_feilds[$i];
					$isGeneral_feilds = true;
					break;
				}
			
			}
			if ($isGeneral_feilds){

			//if ($colt_feild == 'Layer count' || $colt_feild == 'Part Number' || $colt_feild == 'Customer Part Number' || $colt_feild == 'Owning Customer Name'
			//	|| $colt_feild == 'Deliverables Customer Name' || $colt_feild == 'Flat Size' || $colt_feild == 'Aspect Ratio' || $colt_feild == '' || $colt_feild == ''
			//	|| $colt_feild == '' || $colt_feild == '' || $colt_feild == '' || $colt_feild == '' || $colt_feild == ''
			//	){
				$colt_feild =  str_ireplace(' ','_',$colt_feild);
				$my_query = file_get_contents("queries/general.sql");
				$my_query = whatRelation($keyword,$my_query,$colt_feild,$feild_relation);
				//echo $my_query ;
			} else {
				$general_feilds = file_get_contents("queries/registration_feilds.txt");
				$general_feilds = split(',',$general_feilds);
				$isGeneral_feilds = false;
				for ($i=0;$i<count($general_feilds);$i++){
					if ($colt_feild == $general_feilds[$i]) {
						//echo  $general_feilds[$i];
						$isGeneral_feilds = true;
						break;
					}
				}
				if ($isGeneral_feilds){
					$colt_feild =  str_ireplace(' ','_',$colt_feild);
					$my_query = file_get_contents("queries/registration.sql");
					$my_query = whatRelation($keyword,$my_query,$colt_feild,$feild_relation);
				} else {
					$general_feilds = file_get_contents("queries/registration_feilds_2.txt");
					$general_feilds = split(',',$general_feilds);
					$isGeneral_feilds = false;
					for ($i=0;$i<count($general_feilds);$i++){
						if ($colt_feild == $general_feilds[$i]) {
							//echo  $general_feilds[$i];
							$isGeneral_feilds = true;
							break;
						}
					}
					if ($isGeneral_feilds){
						$colt_feild =  str_ireplace(' ','_',$colt_feild);
						$my_query = file_get_contents("queries/registration_2.sql");
						$my_query = whatRelation($keyword,$my_query,$colt_feild,$feild_relation);
					} else {
						$general_feilds = file_get_contents("queries/etchback.txt");
						$general_feilds = split(',',$general_feilds);
						$isGeneral_feilds = false;
						for ($i=0;$i<count($general_feilds);$i++){
							if ($colt_feild == trim($general_feilds[$i])) {
								//echo  $general_feilds[$i];
								$isGeneral_feilds = true;
								break;
							}
						}
						if ($isGeneral_feilds){
							$colt_feild =  str_ireplace(' ','_',$colt_feild);
							$my_query = file_get_contents("queries/etchback.sql");
							$my_query = whatRelation($keyword,$my_query,$colt_feild,$feild_relation);
						} else {
							$general_feilds = file_get_contents("queries/soldermask.txt");
							$general_feilds = split(',',$general_feilds);
							$isGeneral_feilds = false;
							for ($i=0;$i<count($general_feilds);$i++){
								if ($colt_feild == trim($general_feilds[$i])) {
									//echo  $general_feilds[$i];
									$isGeneral_feilds = true;
									break;
								}
							}
							if ($isGeneral_feilds){
								$colt_feild =  str_ireplace(' ','_',$colt_feild);
								$my_query = file_get_contents("queries/soldermask.sql");
								$my_query = whatRelation($keyword,$my_query,$colt_feild,$feild_relation);
							}
						}
					}
				
				}
			}
	}

	function whatRelation($keyword,$my_query,$colt_feild,$feild_relation){
		if (strpos($colt_feild,'(')>0 or strpos($colt_feild,'<')>0 ) $colt_feild = "\"".$colt_feild."\"";
		$colt_feild = "upper($colt_feild)";
		switch ($feild_relation) {
					case "is":
						$my_query = "select count(*) from ($my_query) test where $colt_feild='$keyword'";
						break;
					case "contains":
						$my_query = "select count(*) from ($my_query) test where $colt_feild like '%$keyword%'";
						break;
					case "start with":
						$my_query = "select count(*) from ($my_query) test where $colt_feild like '$keyword%'";
						break;
					case "end with":
						$my_query = "select count(*) from ($my_query) test where $colt_feild like '$keyword%'";
						break;
					case "great than":
						$my_query = "select count(*) from ($my_query) test where $colt_feild >= $keyword";
						break;
					case "less than":
						$my_query = "select count(*) from ($my_query) test where $colt_feild <= $keyword";
						break;
				}
		return $my_query;
	}
	function hasColtData($part_number,$my_query,$conn){
		$my_query = str_ireplace('@@part_number@@',$part_number,$my_query);
		$stid = oci_parse($conn, $my_query);
		//echo $my_query;
		$r = oci_execute($stid, OCI_DEFAULT);
		$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
		return $row;
	}
	function hasEntryData($part_number_id,$my_query,$db){
		$my_query = str_ireplace('@@id@@',$part_number_id,$my_query);
		//echo $my_query;
		$req=@mysql_query($my_query,$db);
		$return =mysql_fetch_array($req);
		return $return;
		//echo $return[0];
	}
	
	//echo $query;
	

	$req=@mysql_query($query,$db);

		$json = "";
		$json .= "{\n";
		//$json .= "page: $page,\n";
		//$json .= "\"total\": \"$total\",\n";
		$json .= "\"rows\": [";
		$rc = false;
		//while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			while ($return =mysql_fetch_array($req)){
				//echo $colt_criteria ;
				//echo $my_query;
				if ($entry_criteria =='true' and $keyword !=''){
					$row = hasEntryData($return['id'],$my_query,$db);

				} 	
				else if($colt_criteria =='true' and $keyword !='') {
					//echo $my_query;
					$row = hasColtData($return['part_number'],$my_query,$conn);
					
				} else { $row[0] = 1;}
					if ($row[0]>0){
						if ($rc) $json .= ",";
						$json .= "\n{";
						$json .= "\"id\":\"".$return['id']."\"";
						$json .= ",\"part_number\":\"".$return['part_number']."\"";
						$json .= ",\"due_date\":\"".$return['manufacturing_due_date']."\"";
						$json .= ",\"status\":\"".preg_replace ('/"/','\'',$return['eng_area_status'])."\"";
						$json .= "}";
						$rc = true;
						$total+=1;
					}
					
			//}
		}
		if($count_nu >0) {
			$total = $count_nu;
		}
		$json .= "]\n";
		$json .= ",\"total\": \"$total\"\n";
		$json .= "}";
		echo $json;

oci_close($conn);
mysql_close($db);
?>