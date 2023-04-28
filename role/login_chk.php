<?php
	$userid = $_POST['userid'];
	$pwd = $_POST['pwd'];
	$site = $_POST['site'];
	$role_id = $_POST['role_id'];
	if(isset($_POST['url']) && $_POST['url']!="") {
		$this_url = $_POST['url'];
	} else {
		$this_url = "../index.php";
	}
	

	$ldap_server = '10.1.1.83';

	$domain="vspri";

	if($userid && $pwd){
		$conn_ = ldap_connect($ldap_server);
		if(!$conn_){
		echo "{\"success\":false,\"message\":\"Connection LDAP server error.\"}";
		}	
		$bind = @ldap_bind($conn_, "$domain\\".$userid, $pwd);
		if($bind){
			require "../admin/mysql_conn.php";
			
			$req=@mysql_query("select au.display_name,r.name,au.user_name from all_users au,role_user ru,role r where au.id=ru.user_id and r.id=ru.role_id and au.user_name='".strtolower($userid)."' and ru.site='$site' and r.id=$role_id",$db);	
			
			$return=@mysql_fetch_array($req);
			$isMember=$return[0];
			$role = $return[1];
			$user_name = $return[2];
			if ($isMember){
					$lifeTime = 24 * 3600 * 365 ; 
					session_set_cookie_params($lifeTime); 
					session_start();
					$_SESSION['FEEWEBlogin']="ok";
					$_SESSION['FEEWEB_userName']=$isMember;
					$_SESSION['FEEWEB_uName']=$user_name; 
					setcookie("FEEWEB_uName", $userid, time()+$lifeTime , "/");  
					header("Location: $this_url");
			} else {
				echo "<div>Not a memeber, please contact to Admin. <a href='login.php?site=$site&role_id=$role_id'>Go back</a></div>";
			}
			mysql_close($db);
	}else{
		echo "<div>Invalid username/password; login denied. <a href='login.php?site=$site&role_id=$role_id'>Go back</a></div>";
	}
	@ldap_close($conn_);
	} else {
		echo "<div>Invalid username/password; login denied. <a href='login.php?site=$site&role_id=$role_id'>Go back</a></div>";
	}

?>