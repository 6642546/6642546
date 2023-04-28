<script type="text/javascript" src="../../../scripts/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../../../plugins/Calendar.js"></script>
<div id="formhere"></div>
<style>
	<!-- 
	#layer_table {
		font: 14px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px #99BBDD;
	}
	#layer_table td{
		/*	border:solid 1px blue; */
			text-align: left;
		}

	#layer_table th{
			border:solid 2px #99BBDD;
		
	}

	#StartDate,#ToDate {
		width:200px;
	}
	
	-->
    </style> 
<br><br>
<center>
<form action="run_layers_rpt.inc.php" method="post" name="fForm">
<table id="layer_table" border=1 rules="rows">
	<tr><th colspan=3>Genesis Layers Report</th></tr>
	<tr><td width="100px">Site:</td><td width="200px"><input type="radio" id="site_fg" checked name="site" value="1">Forest Grove</input></td><td width="100px"><input type="radio" id="site_sj" name="site" value="2">San Jose</input></td></tr>
	<tr><td>Start Date:</td><td><input type="text" id="StartDate"  name="startdate" onclick="javascript:ShowCalendar(fForm.StartDate)" ></input></td><td></td></tr>
	<tr><td>End Date:</td><td><input type="text" id="ToDate"  name="enddate" onclick="javascript:ShowCalendar(fForm.ToDate)" ></input></td><td></td></tr>
	<tr><td>Job Category:</td><td><input type="radio" id="fttjobs"  name="jobscat" value="1" checked >FTT jobs</input></td><td></td></tr>
	<tr><td></td><td><input type="radio" id="alljobs"  name="jobscat" value="2" >All jobs</input></td><td></td></tr>
	<tr><td colspan=3 style="text-align: center;"><input type="submit" value="Get Report" id="get_report"></input></td></tr>
</table>
</form>
<br><br><br><br><br><br><br>
<h3>Only click Get Report once.  This may take a few minutes</h3>
</center>
