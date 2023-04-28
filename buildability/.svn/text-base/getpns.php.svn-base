<?php
	error_reporting(0);
	$site = $_POST['site'];
	require("oracle_conn.php");
	$query = file_get_contents("queries/bbty_dispatch.sql");
	$query = $query . " and rownum<3";	
	$stid = oci_parse($conn, $query);
	$r = oci_execute($stid, OCI_DEFAULT);

		$json = "";
		$json .= "[";
		$rc = false;
		$id =1;
		while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
					if ($rc) $json .= ",";
					$json .= "{";
					$json .= "\"id\":\"".$id."\"";
					$json .= ",\"text\":\"".trim($row['PART_NUMBER'])."\"";
					$json .= "}";
					$rc = true;
					$id++;
		}
		$json .= "]";
		echo $json;

oci_close($conn);
?>