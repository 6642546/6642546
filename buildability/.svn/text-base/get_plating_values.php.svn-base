<?php
	error_reporting(0);
	$site = $_GET['site'];
	$plating_program_name = addslashes($_GET['plating_program_name']);
	require("oracle_conn.php");
		if (file_exists("queries/plating_thickness.sql")){
			$my_query = file_get_contents("queries/plating_thickness.sql");
			$my_query .=' order by description';
		if ($plating_program_name=='Tin'){
			$my_query = str_ireplace('@@replace_str@@',"('T',' ')",$my_query);
		} elseif ($plating_program_name==addslashes('Gold: Nickel_Gold') || $plating_program_name=='Gold 2'){
			$my_query = str_ireplace('@@replace_str@@',"('G','F',' ')",$my_query);
		} elseif ($plating_program_name=='Gold: Copper'){
			$my_query = str_ireplace('@@replace_str@@',"('U',' ')",$my_query);
		} elseif ($plating_program_name=='Cu Buildup Strike' || $plating_program_name=='Strike' || $plating_program_name=='Strike 2nd Pass' || $plating_program_name=='Strike - Special' || $plating_program_name=='VF Strike'){
			$my_query = str_ireplace('@@replace_str@@',"('S',' ')",$my_query);
		} elseif ($plating_program_name=='Pattern Button-Spc' || $plating_program_name=='VF Button'){
			$my_query = str_ireplace('@@replace_str@@',"('B',' ')",$my_query);
		} elseif ($plating_program_name=='CuF Strike'){
			$my_query = str_ireplace('@@replace_str@@',"('C',' ')",$my_query);
		}
		}
		//echo $my_query;
		$stid = oci_parse($conn, $my_query);
		$r = oci_execute($stid, OCI_DEFAULT);
		
		$json = "";
		$json .= "{\n";
		$json .= "\"total\": \"100\",\n";
		$json .= "\"rows\": [";
		$rc = false;
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			$description = str_ireplace('\n',' ',trim($row['DESCRIPTION']));
			//$description = str_ireplace('\r',' ',trim($row['DESCRIPTION']));
			// $description="";
					if ($rc) $json .= ",";
					$json .= "{";
					$json .= "\"id\":\"".trim($row['ID'])."\"";
					$json .= ",\"text\":\"".$description."\"";
					$json .= ",\"cu\":\"".trim($row['SURFACE_CU'])."\"";
					$json .= ",\"sn\":\"".trim($row['SURFACE_SN'])."\"";
					$json .= ",\"ni\":\"".trim($row['SURFACE_NI'])."\"";
					$json .= ",\"au\":\"".trim($row['SURFACE_AU'])."\"";
					$json .= "}";
					$rc = true;
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;

oci_close($conn);
?>