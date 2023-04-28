<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>FEE WEB Admin - Add user</title>
<link href="styles/base.css" type="text/css" rel="Stylesheet" />
<script type="text/javascript" src="../scripts/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select").bind("click",
			function() {
				opener.document.getElementById("uid").value=$(this).parent().parent().find("TD:first").html();
				opener.document.getElementById("uname").value=$(this).parent().parent().find(".cname").html();
				window.close();
			}
		)
})
</script>
</head>
<body>
<form name="form1" method="post" action="getuser.php">
<div>User Name:<input name="uname"> &nbsp <button type="submit" name="sm1" onclick="this.form.submit();">Search</button></div>
</form>
<?php
	$uname = $_POST['uname'];
	if(!$uname ) {
		$uname = $_GET['wd'];	
	} 
	$wd =$uname;
	if($uname) {
		$where = " where user_name like '%".strtolower($uname)."%'";
	}
	/*
		Place code to connect to your DB here.
	*/
	if(!$db) require "mysql_conn.php";	// include your code to connect to DB.

	$tbl_name="all_users";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = "SELECT COUNT(*) as num FROM $tbl_name".$where;
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	
	/* Setup vars for query. */
	$targetpage = "getuser.php"; 	//your file name  (the name of this file)
	$limit = 10; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM $tbl_name $where LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev&wd=$wd\">«</a>";
		else
			$pagination.= "<span class=\"disabled\">«</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter&wd=$wd\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&wd=$wd\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&wd=$wd\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&wd=$wd\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1&wd=$wd\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&wd=$wd\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&wd=$wd\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&wd=$wd\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&wd=$wd\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1&wd=$wd\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&wd=$wd\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&wd=$wd\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next&wd=$wd\">»</a>";
		else
			$pagination.= "<span class=\"disabled\">»</span>";
		$pagination.= "</div>\n";		
	}
?>

	<?php
		$html = "<table id=role_table border=0 width=100%><tr><th>ID</th><th>User Name</th><th>Display Name</th><th>Action</th></tr>";
		while($row = mysql_fetch_array($result))
		{
	
		$html .="<tr><td>".$row[0]."</td><td class='cname'>".$row[1]."</td><td>".$row[2]."</td><td><span class='select'>Select</span></td></tr>";
	
		}
		$html .= "<table>";
		echo $html;
		echo $pagination;
		
	?>
</body>
</html>