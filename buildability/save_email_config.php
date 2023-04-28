<?php
error_reporting(0);
$site = $_POST['site'];
$email_cc_list = $_POST['email_cc_list'];
$email_new_list = $_POST['email_new_list'];
$email_del_list = $_POST['email_del_list'];
$email_close_list = $_POST['email_close_list'];
$email_status_list = $_POST['email_status_list'];

$array = array('email_cc_list', 'email_new_list','email_del_list','email_close_list','email_status_list');
$array_ = array($email_cc_list, $email_new_list,$email_del_list,$email_close_list,$email_status_list);
$count = count($array);


require "mysql_conn.php";

function ConfigExist($site,$config_name,$db){
	$my_query = "select count(*) from buildability_config where site='$site' and config_name='$config_name' and config_type='email'";
	$req=@mysql_query($my_query,$db);	
	$result=@mysql_fetch_array($req);
	return $result[0];
}

$success = true;

for ($i = 0; $i < $count; $i++) {
	if (ConfigExist($site,$array[$i],$db)){
		$query = "update buildability_config set config_text='$array_[$i]' where config_name='$array[$i]' and site='$site' and config_type='email'";
	} else {
		$query = "insert into buildability_config (config_name,config_type,config_text,site) values ('$array[$i]','email','$array_[$i]','$site')";
	}
	if (!mysql_query($query,$db)){
		$success =false;	
	}

}

if ($success){
		echo "{\"success\":true,\"message\":\"Config saved successfully.\"}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to save config. Please try it again.\"}";
	} 
mysql_close($db);
?>