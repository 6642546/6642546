<?php
	$userid = $_POST['userid'];
	$pwd = $_POST['pwd'];
	$site = $_POST['site'];

	$ldap_server = '10.1.1.83';

	$domain="vspri";

	if($userid && $pwd){
		$conn_ = ldap_connect($ldap_server);
		if(!$conn_){
		echo "{\"success\":false,\"message\":\"Connection LDAP server error.\"}";
		}	
		$bind = @ldap_bind($conn_, "$domain\\".$userid, $pwd);
		if($bind){
			require "mysql_conn.php";
			
			$req=@mysql_query("select au.display_name,r.name from all_users au,role_user ru,role r where au.id=ru.user_id and r.id=ru.role_id and ru.site='$site' and au.user_name='".strtolower($userid)."'",$db);	
			
			$return=@mysql_fetch_array($req);
			$isMember=$return[0];
			$role = $return[1];
			if ($isMember){
				if($role  == 'Admin') {
					$lifeTime = 24 * 3600 * 365 ; 
					session_set_cookie_params($lifeTime); 
					session_start();
					$_SESSION['Adminlogin']="ok";
					$_SESSION['Admin_userName']=$isMember;
					setcookie("Admin_userName", $userid, time()+$lifeTime);  
					header("Location: index.php?site=$site");
				} else {
					echo "<div>You don't have enough the Admin right to access it.</div>";
				}
			} else {
				echo "<div>Not a memeber, please contact to Admin.</div>";
			}
			mysql_close($db);
	}else{
		echo "<div>Invalid username/password; login denied.</div>";
	}
	ldap_close($conn_);
	}

?>