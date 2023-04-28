<?php
	$lifeTime = 240 * 3600 * 365; 
	session_set_cookie_params($lifeTime); 
	session_start();
	$site = $_GET['site'];
	
	if (empty($_SESSION['Adminlogin']) || $_SESSION['Adminlogin']!="ok")
	{
		if(empty($_COOKIE['Admin_userName'])){
			header("Location: login.php?site=$site");
			exit;
		} else {
			$user_name = $_COOKIE['Admin_userName'];
		}
	} else {
		$user_name = $_SESSION['Admin_userName'];
	}
	
	$login=1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>FEE WEB Admin</title>
<link href="styles/base.css" type="text/css" rel="Stylesheet" />
<script type="text/javascript" src="../scripts/jquery-1.4.4.min.js"></script>
</head>
<body>
	<div id="main-div">
		<div id="top"><a href="../index.php">Home</a> -> <a href="index.php">Admin</a> &nbsp Login user is: <?php echo	$user_name ?> &nbsp&nbsp&nbsp&nbsp Login site is: <?php echo	$site ?> &nbsp&nbsp&nbsp&nbsp <a href="logout.php">Logout</a></div>
		<br/>
		<div>
			<?php
				if(!$db) require "mysql_conn.php";
				$req=@mysql_query("select * from role r");
				$html = "<table width=100%><tr><td colspan=4 class='table_header'>Add user to group</td></tr><tr><th>Role ID</th><th>Role Group Name</th><th>Remark</th><th>Action</th></tr>";
				while ($return =mysql_fetch_array($req)){
					$html .="<tr><td>".$return[0]."</td><td>".$return[1]."</td><td>".$return[2]."</td><td><a href='adduser.php?role_id=".$return[0]."&role_name=".$return[1]."&site=".$site."'>Add user</a></td></tr>";
				}
				$html .= "<table>";
				echo $html;
				

			?>
		</div>
		<div></div>
	</div>
</body>
</html>