<?php
	error_reporting(0);
	$user_name = $_GET["user_name"];
	require "mysql_conn.php";
	$query = "select * from users where user_name='".strtolower(trim($user_name))."'";
		
	$req=@mysql_query($query,$db);
		while ($row = mysql_fetch_array($req)) {
			//preg_match_all('/(?<=\<)([^\}]*?)(?=\>)/' , $row['email'] , $ary);
			//$email = $ary[0][0];	
			$email =  $row['email'];	
			$json = "{\"success\":true,\"dispname\":\"" . $row['display_name']."\",\"email\":\"".$email."\",\"role\":\"".$row['role']."\"}";
		} 
		if (!$json){
			$json = "{\"success\":false,\"message\":\"You can create it.\"}";
		}
		echo $json;

mysql_close($db);
?>