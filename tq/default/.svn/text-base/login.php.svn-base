<?php
$username=null;
$userpass=null;
$username=$_GET['uname'];
$chknumber=$_POST['chknumber'];
$ldap_server = '10.1.1.83';

$domain="vspri";

if($username && $chknumber){
	
	$conn_ = ldap_connect($ldap_server);
	if(!$conn_){
	//die("<br>Connection LDAP server error");
	echo "{\"success\":false,\"message\":\"Connection LDAP server error.\"}";
	}	
	$bind = @ldap_bind($conn_, "$domain\\".$username, $chknumber);
	if($bind){
			require "../../admin/mysql_conn.php";
			
			$req=@mysql_query("select au.display_name,r.name,au.user_name from all_users au,role_user ru,role r where au.id=ru.user_id and r.id=ru.role_id and au.user_name='".strtolower($username)."'",$db);	
			
			$return=@mysql_fetch_array($req);
			$isMember=$return[0];
			$role = $return[1];
			$user_name = $return[2];
			if ($isMember){
					if($role == 'TQ Admin') {
						$lifeTime = 24 * 3600 * 365 ; 
						session_set_cookie_params($lifeTime); 
						session_start();
						$_SESSION['FEEWEBlogin']="ok";
						$_SESSION['FEEWEB_userName']=$isMember;
						$_SESSION['FEEWEB_uName']=$user_name;
						echo "{\"success\":true,\"message\":\"$username\"}";
					} else {
						echo "{\"success\":false,\"message\":\"You can not delete a TQ.\"}";  
					}
					
			} else {
				echo "{\"success\":false,\"message\":\"Not a valid memeber, please contact to PE Admin.\"}";  
			}
	} else{
			echo "{\"success\":false,\"message\":\"Invalid username/password; login denied.\"}";    
		}
	ldap_close($conn_);
}else{
	echo "{\"success\":false,\"message\":\"Invalid username/password; login denied.\"}";     
}

?>