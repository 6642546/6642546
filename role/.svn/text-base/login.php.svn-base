<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="styles/base.css" rel="stylesheet" type="text/css" />
<title>Login</title>
<script type="text/javascript">

</script>
</head>

<body>
<div id="login-box">
   <div class="login-top"><H1 style="line-height:48px;">FEE Web Login</H1></div>
   <div class="login-main">
    <form name="form1" method="post" action="login_chk.php">
	  <input type="text" name="site" style="display:none" value="<?php echo $_GET['site'] ?>"/>
	  <input type="text" name="role_id" style="display:none" value="<?php echo $_GET['role_id'] ?>"/>
	  <input type="text" name="url" style="display:none" value="<?php echo str_ireplace("@","&",$_GET['url'])?>"/>
      <dl>
	   <dt>User Name:</dt>
	   <dd><input type="text" name="userid"/></dd>
	   <dt>Password:</dt>
	   <dd><input type="password" class="alltxt" name="pwd"/></dd>
		<dt>&nbsp;</dt>
		<dd><button type="submit" name="sm1" class="login-btn" onclick="this.form.submit();">Login</button></dd>
	 </dl>
	</form>
   </div>
   <div><a href="../index.php?site=<?php echo $_GET['site'] ?>" title="Return to home page">Return to home page</a></div>
   <div class="login-power">Powered by<strong> Global Software Team</strong> &copy; 2011-2012 Viasystems Inc.</div>
</div>
</body>
</html>