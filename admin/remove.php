<?php
	$uid = $_GET['user_id'];
	$role_id = $_GET['role_id'];
	$role_name = $_GET['role_name'];
	$user_name = $_GET['user_name'];
	$site = $_GET['site'];


	if(!$db) require "mysql_conn.php";

		$query = "delete from role_user where user_id=$uid and role_id=$role_id";
		
		if (mysql_query($query,$db)){
			echo "$user_name has been removed from the role successfully.";}
			else{
			echo "Failed to removed role. Please try it again.";
		} 

	mysql_close($db);

?>
<br/>
<div><a href="adduser.php?role_id=<?php echo "$role_id" ?>&role_name=<?php echo "$role_name" ?>&site=<?php echo $site ?>&random=<?php echo rand(1,1000) ?>"><u>Go back.</u></a></div>