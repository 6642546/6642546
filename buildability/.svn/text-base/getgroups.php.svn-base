<?php
	error_reporting(0);
	require "mysql_conn.php";
	$query = "select eng_area_code from eng_area where eng_area_code not in ('GN','HD')";
		
	$req=@mysql_query($query,$db);
		
		$json = "";
		$json .= "[";
		$rc = false;
		$id =1;
		while ($row = mysql_fetch_array($req)) {
					if ($rc) $json .= ",";
					$json .= "{";
					$json .= "\"id\":\"".$id."\"";
					$json .= ",\"text\":\"".trim($row[0])."\"";
					//if ($id==1) $json .=",\"selected\":true";
					$json .= "}";
					$rc = true;
					$id++;
		}
		$json .= "]";
		echo $json;

mysql_close($db);
?>