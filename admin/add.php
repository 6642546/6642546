<?php
	$uid = $_POST['uid'];
	$role_id = $_POST['role_id'];
	$role_name = $_POST['role_name'];
	$user_name = $_POST['uname'];
	$site = $_POST['site'];

	if(!$db) require "mysql_conn.php";

	$my_query = "select count(*) from role_user where user_id=$uid and role_id=$role_id and site='$site'";
	$count=@mysql_query($my_query,$db);	
	$count_nu=@mysql_fetch_array($count);
	$total =$count_nu[0];
	if ($total){
		echo "$user_name is already has this role.";
	} else {
		$query = "insert into role_user (user_id,role_id,site) values ($uid,$role_id,'$site')";
		if (mysql_query($query,$db)){
			echo "$user_name has been added the role successfully.";}
			else{
			echo "Failed to add role. Please try it again.";
		} 
	}

	mysql_close($db);

?>
<br/>
<div><a href="adduser.php?role_id=<?php echo "$role_id" ?>&role_name=<?php echo "$role_name" ?>&random=<?php echo rand(1,1000) ?>"><u>Go back to add another one.</u></a></div>