<?php
	error_reporting(0);
	$site = $_POST['site'];
	require "mysql_conn.php";
	$query = "select email from users where role='Admin'";
	$req=@mysql_query($query,$db);
	$to = "";
	$rc = false;
		while ($row = mysql_fetch_array($req)) {
					if ($rc) $to  .= ";";
					$to .= $row['email'];
					$rc = true;
					$id++;
		}
		
	echo "{\"success\":true,\"message\":\"$to\"}";
	

mysql_close($db);
?>