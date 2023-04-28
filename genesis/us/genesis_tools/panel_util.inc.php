<script type="text/javascript" src="../../../scripts/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../../../plugins/Calendar.js"></script>
<script language="javascript" type="text/javascript" src="../../../plugins/jsuggest/jquery.jSuggest.1.0.js"></script>
<link href="../../../plugins/jsuggest/style.css" type="text/css" rel="Stylesheet" />
<div id="formhere"></div>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#by_job").bind("click",function(){
			$("#archive_job").attr("disabled","");
			$("#archive_job").css("background-color","white");
			$("#StartDate").attr("disabled","disabled");
			$("#StartDate").val("");
			$("#StartDate").css("background-color","gray");
			$("#ToDate").attr("disabled","disabled");
			$("#ToDate").val("");
			$("#ToDate").css("background-color","gray");
		
		
	});

	$("#by_date").bind("click",function(){
			$("#archive_job").attr("disabled","disabled");
			$("#archive_job").val("");
			$("#archive_job").css("background-color","gray");
			$("#StartDate").attr("disabled","");
			$("#StartDate").css("background-color","white");
			$("#ToDate").attr("disabled","");
			$("#ToDate").css("background-color","white");
		
		
	});

	$("#archive_job").jSuggest({
			url: "mars_jobs.php",
			type: "POST",
			data: "query",
			height:200,
			autoChange: true
		});
})
</script>
<style>
	<!-- 
	#panel_table {
		font: 14px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px #99BBDD;
	}
	#panel_table td{
		/*	border:solid 1px blue; */
			text-align: left;
		}

	#panel_table th{
			border:solid 2px #99BBDD;
		
	}

	#archive_job {
		width:200px;
		background-color:gray;
	}
	#StartDate,#ToDate {
		width:200px;
	}
	-->
    </style> 
<br><br>
<center>
<form action="run_panel_util.inc.php" method="post" name="fForm">
<table id="panel_table" border=1 rules="rows">
	<tr><th colspan=3>Genesis Panel Utilization Report</th></tr>
	<tr><td width="100px">Source:</td><td width="200px"><input type="radio" id="by_date" checked name="source">By Date</input></td><td width="100px"><input type="radio" id="by_job" name="source">By Job</input></td></tr>
	<tr><td>Start Date:</td><td><input type="text" id="StartDate"  name="startdate" onclick="javascript:ShowCalendar(fForm.StartDate)" ></input></td><td></td></tr>
	<tr><td>End Date:</td><td><input type="text" id="ToDate"  name="enddate" onclick="javascript:ShowCalendar(fForm.ToDate)" ></input></td><td></td></tr>
	<tr><td>Archived Job:</td><td><input type="text" id="archive_job"  name="jobname" disabled="disabled" ></input></td><td></td></tr>
	<tr><td colspan=3 style="text-align: center;"><input type="submit" value="Get Report" id="get_report"></input></td></tr>
</table>
</form>
<br><br><br><br><br><br><br>
<h3>Only click Get Report once.  This may take a few minutes</h3>
</center>
