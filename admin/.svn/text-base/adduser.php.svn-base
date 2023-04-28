<?php
	session_start();
	if ($_SESSION['Adminlogin']!="ok") {
		header("Location: login.php");
		exit;
	}
	$login=1;
	$user_name = $_SESSION['Admin_userName'];
	$role_id = $_GET['role_id'];
	$role_name = $_GET['role_name'];
	$site = $_GET['site'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>FEE WEB Admin - Add user</title>
<link href="styles/base.css" type="text/css" rel="Stylesheet" />
<script type="text/javascript">
	function openwindow(url,name,iWidth,iHeight)
		{
		var url;
		var name; 
		var iWidth; 
		var iHeight; 
		var iTop = (window.screen.availHeight-30-iHeight)/2; 
		var iLeft = (window.screen.availWidth-10-iWidth)/2; 
		window.open(url,name,'height='+iHeight+',,innerHeight='+iHeight+',width='+iWidth+',innerWidth='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
		}

</script>
</head>
<body>
	<div id="main-div">
		<div id="top"><a href="../index.php">Home</a> -> <a href="index.php">Admin</a> -> <a href="adduser.php?role_id=<?php echo $role_id ?>&role_name=<?php echo $role_name ?>">Add user</a> &nbsp Login user is: <?php echo	$user_name ?> &nbsp&nbsp&nbsp&nbsp Login site is: <?php echo	$site ?> &nbsp&nbsp&nbsp&nbsp <a href="logout.php?site=<?php echo $site ?>">Logout</a></div>
		<br/>
		<form name="form1" method="post" action="add.php">
		<input name="site" style="display:none" value="<?php echo $site ?>">
		<div>Get user name: <input name="uname" id="uname" readonly><input name="uid" id="uid" style="display:none"><input name="role_id" id="role_id" value=<?php echo $role_id ?> style="display:none"><input name="role_name" id="role_name" value=<?php echo $role_name ?> style="display:none">&nbsp<input type="button" value="Choose user" id="choose_user" onclick="javascript:openwindow('getuser.php','',450,500);">&nbsp<button type="submit" name="sm1" onclick="this.form.submit();">Add</button></div>
		</form>
		<div>List all of users in the <span style="font-weight:bold"><?php echo $role_name ?> </span>group:</div>
		<br/>
		<div>
			<?php
				if(!$db) require "mysql_conn.php";
				$req=@mysql_query("select ru.id,au.id,au.user_name from role_user ru,all_users au where ru.user_id=au.id and role_id=".$role_id);
				$html = "<table id=role_table border=0 width=100%><tr><th>ID</th><th>User ID</th><th>User Name</th><th>Action</th></tr>";
				while ($return =mysql_fetch_array($req)){
					$html .="<tr><td>".$return[0]."</td><td>".$return[1]."</td><td>".$return[2]."</td><td><a href='remove.php?user_id=".$return[1]."&role_id=".$role_id."&role_name=".$role_name."&user_name=".$return[2]."&site=".$site."'>Remove</a></td></tr>";
				}
				$html .= "<table>";
				echo $html;
				

			?>
		</div>
		<div></div>
	</div>
</body>
</html>