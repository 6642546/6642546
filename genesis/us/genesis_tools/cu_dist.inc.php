<script type="text/javascript" src="../../../scripts/jquery-1.4.4.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../plugins/jsuggest/jquery.jSuggest.1.0.js"></script>
<link href="../../../plugins/jsuggest/style.css" type="text/css" rel="Stylesheet" />
<div id="formhere"></div>
<script type="text/javascript">

// new scripts by using jquery.

	$(document).ready(function(){ 
		$("#live_job").bind("click",function() {
			if ($("#live_job option").length==0)
			{
			$.ajax({ 
					type: "POST", 
					url: "genesis_jobs.php", 
					data:{task:"LIST",query:""},
					dataType: "text", 
					success: function(data) {
						var vData="";
						vData  = eval( "(" + data + ")" );// change return data to json;
						var Result = vData.total;
						if (Result > 0) {
								//bind data  
								var vlist = "";
								jQuery.each(vData.results, function(i, n) {  
									vlist += "<option value=" + n.name + ">" + n.name + "</option>";  
								});  
								$("#live_job").append(vlist); 	
							 return;
						}
						else 
						if (Result == 0) {
							 return;
							}
					    } 
				  });
			}
		});

		$("#revision").bind("click",function() {
			if ($("#revision option").length==0)
			{
			$.ajax({ 
					type: "POST", 
					url: "mars_revs.php", 
					data:{task:$("#archive_job").val()},
					dataType: "text", 
					success: function(data) {
						var vData="";
						vData  = eval( "(" + data + ")" );// change return data to json;
						var Result = vData.total;
						if (Result > 0) {
								//bind data  
								var vlist = "";
								jQuery.each(vData.results, function(i, n) {  
									vlist += "<option value=" + n.cre_date.replace(' ','&#10;') + ">" + n.cre_date + "</option>";  
								});  
								$("#revision").append(vlist); 	
							 return;
						}
						else 
						if (Result == 0) {
							 return;
							}
					    } 
				  });
			}
		});
		

		$("#live_dbase").bind("click",function(){
			$("#archive_job").attr("disabled","disabled");
			$("#archive_job").css("background-color","gray");
			$("#revision").attr("disabled","disabled");
			$("#revision").css("background-color","gray");
			$("#live_job").attr("disabled","");
			$("#live_job").css("background-color","white");
		
		
		});

		$("#mars").bind("click",function(){
			$("#live_job").attr("disabled","disabled");
			$("#live_job").css("background-color","gray");
			$("#archive_job").attr("disabled","");
			$("#archive_job").css("background-color","white");
			$("#revision").attr("disabled","");
			$("#revision").css("background-color","white");
			$("#archive_job").focus();
		
		
		});

		$("#archive_job").jSuggest({
			url: "mars_jobs.php",
			type: "POST",
			data: "query",
			height:200,
			autoChange: true
		});
			 
	});

</script>

<br><br>
<style>
	<!-- 
	table {
		font: 14px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px #99BBDD;
	}
	table td{
		/*	border:solid 1px blue; */
			text-align: left;
		}

	table th{
			border:solid 2px #99BBDD;
		
	}

	select {
		width:200px;
	}

	#archive_job {
		width:200px;
		background-color:gray;
	}

	#revision {
		width:200px;
		background-color:gray;
	}
	-->
    </style> 
<center>
<form action="run_copper.inc.php" method="post">
<table border=1 rules="rows">
	<tr><th colspan=3>Inner Copper Distribution Report</th></tr>
	<tr><td width="100px">Source:</td><td width="200px"><input type="radio" id="live_dbase" checked name="database">Live Database</input></td><td width="100px"><input type="radio" id="mars" name="database">Mars</input></td></tr>
	<tr><td>Live Job:</td><td><select id="live_job" name="jobname"></select></td><td></td></tr>
	<tr><td>Archived Job:</td><td><input type="text" id="archive_job" disabled="disabled" name="jobname"></input></td><td></td></tr>
	<tr><td>Revision:</td><td><select id="revision" disabled="disabled" name="marsrev"></select></td><td></td></tr>
	<tr><td>Step:</td><td><select name="step"><option value="cust">cust</option><option value="edit">edit</option><option value="panel">panel</option></select></td><td></td></tr>
	<tr><td>Grid size:</td><td><select name="gridsize"><option value=".25">.25</option><option value=".5">.50</option><option value="1">1.0</option><option value="2">2.0</option></select></select></td><td></td></tr>
	<tr><td>Report as:</td><td><input type="radio" name="format" checked value="excel">Excel</input></td><td><input type="radio" name="format" value="html">HTML</input></td></tr>
	<tr><td colspan=3 style="text-align: center;"><input type="submit" value="Get Report" id="get_report"></input></td></tr>
</table>
</form>

<br><br><br><br><br><br><br>
<h3>Only click Get Report once.  This may take a few minutes</h3>
</center>
