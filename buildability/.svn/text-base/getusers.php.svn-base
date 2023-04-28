<?php
	error_reporting(0);
	require "mysql_conn.php";
	$query = "select * from users";
		
	$req=@mysql_query($query,$db);
		
		$json = "";
		$json .= "[";
		$rc = false;
		$id =1;
		while ($row = mysql_fetch_array($req)) {
					if ($rc) $json .= ",";
					$json .= "{";
					$json .= "\"id\":\"".$id."\"";
					$json .= ",\"text\":\"".trim($row['user_name'])."\"";
					//if ($id==1) $json .=",\"selected\":true";
					$json .= "}";
					$rc = true;
					$id++;
		}
		$json .= "]";
		echo $json;

mysql_close($db);
?>