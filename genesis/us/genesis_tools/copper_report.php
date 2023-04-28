<style>
	<!-- 
	table {
		font: 14px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px #99BBDD;
	}
	table th{
			border:solid 2px #99BBDD;
		
	}
	-->
    </style> 
<?php
	$Format = $_GET['format'];
	if ($Format == 'excel') {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=copper_report.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	$reportFile = $_GET['report'];
  
  
	//$reportFile = "/home/genesis/report/" . $mypid . "/" . $jobname + ".rpt";
  
	$ptr = fopen($reportFile,"r");
	//echo "<table width=\"65%\" cellpadding=\"1\" border=\"1\">\n";
	echo "<center><table width=\"65%\" border=\"1\">\n";
	while(! feof($ptr)) {
		$allItems = fgetcsv($ptr, 2000, ",");
		echo "<tr>\n";
		if (count($allItems) < 3) {
			echo "<td colspan=\"3\"><b>$allItems[0]</b></td>\n";
		} else {
			foreach ($allItems as $field) {
				echo "<td align=\"left\">$field</td>\n";
			}
		}
		echo "</tr>\n";

	}
	echo "</table></center>\n";   
?>
<script type="text/javascript">
var i =0;
function reinitIframe(){
	
var iframe = window.parent.document.getElementById("frame_content");
if (iframe){
	try{
	if(iframe.contentWindow.document.readyState=="complete"){
	var bHeight = iframe.contentWindow.document.body.scrollHeight;
	var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
	var height = Math.max(bHeight, dHeight);
	iframe.height =  height;
	i = i +2;
	}
	if (i>=4) {
		clearInterval(sh);
	}
	}catch (ex){}

}

}
var sh;
sh = window.parent.setInterval("reinitIframe()", 200);

</script>

