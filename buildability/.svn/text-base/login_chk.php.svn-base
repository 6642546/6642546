<?php
$username=null;
$userpass=null;
$username=$_GET['uname'];
$chknumber=$_POST['chknumber'];
$ldap_server = '10.1.1.83';
$remember = $_GET['remember'];

$domain="vspri";

if($username && $chknumber){
	
	$conn_ = ldap_connect($ldap_server);
	if(!$conn_){
	//die("<br>Connection LDAP server error");
	echo "{\"success\":false,\"message\":\"Connection LDAP server error.\"}";
	}	
	$bind = @ldap_bind($conn_, "$domain\\".$username, $chknumber);
	if($bind){
		require "mysql_conn.php";
		$req=@mysql_query("select display_name,obsolete,role from users where user_name='".strtolower($username)."'",$db);	
		$return=@mysql_fetch_array($req);
		$isMember=$return[0];
		$obsolete = $return[1];
		$role = $return[2];
		if ($obsolete){
			echo "{\"success\":false,\"message\":\"Your account has been locked, please contact to Adamin.\"}";
		} elseif ($isMember){
			if ($remember){
				$lifeTime = 240 * 3600; 
				session_set_cookie_params($lifeTime); 
			}
			session_start();
			$_SESSION['buildlogin']="ok";
			$_SESSION['build_userName']=$isMember;
			//$_SESSION['build_role']=$role;
			//setcookie("build_dispName", "$isMember");
			echo "{\"success\":true,\"message\":\"$isMember\",\"role\":\"$role\"}";
		} else {
			echo "{\"success\":false,\"message\":\"Not a memeber, please contact to Admin.\"}";   
		}
		mysql_close($db);

}else{
	echo "{\"success\":false,\"message\":\"Invalid username/password; login denied.\"}";     
}
ldap_close($conn_);
}

?>