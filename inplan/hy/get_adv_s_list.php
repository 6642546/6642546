<?php
		//error_reporting(0);

		$site=$_GET['site'];
		$params = $_POST['params'];

		function eval_query($params,$site){
			$site_id = 0;
			switch ($site){
				case "HY":
					$site_id = 2;
					break;
				case "HZ":
					$site_id = 3;
					break;
				case "FG":
					$site_id = 2;
					break;
				case "SJ":
					$site_id = 1;
					break;
				case "GZ":
					$site_id = 1;
					break;
				case "ZS":
					$site_id = 2;
					break;
			}
			$query_header = "select i.item_name,job.mrp_name";
			$query_middle = " from items i,job";
			$query_end = " where i.item_type=2 and i.item_id=job.item_id and job.revision_id=i.last_checked_in_rev and job.odb_site_id=$site_id";


			$query_end .=" and rownum<=10";
			$my_query = $query_header . $query_middle . $query_end;
			return $my_query;
		}

		

		require "../../oracle_conn.php";

		$query = eval_query($params,$site);
		
		$stid = oci_parse($conn, $query);
		$r = oci_execute($stid, OCI_DEFAULT);
		$ncols = oci_num_fields($stid);
	
		header("Expires: Mon, 26 Jul 2012 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");

		$json = "{\"success\":true,\"message\":\"";
		$json .="<tr>";
		for ($i = 1; $i <= $ncols; $i++) {
				$json .="<th>".oci_field_name($stid, $i)."</th>";
			}
		$json .="</tr>";
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
			$json .="<tr>";
			for ($i = 1; $i <= $ncols; $i++) {
				$json .="<td>".$row[$i-1]."</td>";	
			}
			$json .="</tr>";
			$count++;
		}
		$json .="\",\"count\":$count,\"column_count\":$ncols}";
		echo $json;

oci_close($conn);
?>