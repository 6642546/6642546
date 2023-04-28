<?php
	error_reporting(0);
	$site = $_POST["site"];
	$config_type = $_POST["config_type"];
	require "mysql_conn.php";
	$query = "select * from buildability_config where site='$site' and config_type='$config_type'";
	
	$json = "{\"success\":true";
	$req=@mysql_query($query,$db);
		while ($row = mysql_fetch_array($req)) {
			$json .= ",\"".$row['config_name']."\":\"" . $row['config_text']."\"";
		}
		$json .= "}";
		echo $json;

mysql_close($db);
?>