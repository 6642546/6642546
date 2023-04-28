<?php
error_reporting(0);
$user_name = $_POST['user_name'];
$display_name = $_POST['display_name'];
$email = $_POST['email'];
$role = $_POST['role'];
$obsolete = $_POST['obsolete'];
$site = $_POST['site'];

$status = "created";

//$email = $display_name . " <$email>";

require "mysql_conn.php";
// check if the part number is in Build.
$my_query = "select count(*) from users where user_name='$user_name'";
$count=@mysql_query($my_query,$db);	
$count_nu=@mysql_fetch_array($count);
$total =$count_nu[0];
if ($total){
	//echo "{\"success\":false,\"message\":\"$user_name is already exist.\"}";
	//exit;
	$query = "update users set user_name='$user_name',display_name='$display_name',email='$email',role='$role',obsolete='$obsolete' where user_name='$user_name'";
	$status = "updated";
} else {

	$query = "insert into users (user_name,display_name,email,role,obsolete) 
		  values ('$user_name','$display_name','$email','$role','$obsolete')";
}


//echo "$query";
if (mysql_query($query,$db)){
		echo "{\"success\":true,\"message\":\"Account $user_name has been $status successfully.\"}";}
		else{
		echo "{\"success\":false,\"message\":\"Failed to create account. Please try it again.\"}";
	} 
mysql_close($db);
?>