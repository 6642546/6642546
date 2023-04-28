<style> 
<!-- 
	table {
		font: 12px/17px times,Arial,sans-serif;
		border-collapse:collapse;border:solid 2px #99BBDD;
	}
	table td{
		/*	border:solid 1px blue; */
			text-align: left;
		}

	table th{
			border:solid 1px #99BBDD;
		
	}
	
	-->
    </style> 
<?php
	/*
	 * here we will generate a report of Genesis layer Info
	 */
	 include("./lib/login.php");

	//This function encodes a (mm/dd/YYYY) to (YYYY-mm-dd)
	function encodeDate ($date) {
		$tab = explode ("/", $date);
		$r = $tab[0]."-".$tab[1]."-".$tab[2];
		return $r;
	}

	 $db = new MarsConnect();

	 $site = $_REQUEST['site'];
	 $start = $_REQUEST['startdate'];
	 $end = $_REQUEST['enddate'];
	 $jobscat = $_REQUEST['jobscat'];

	 $start = encodeDate($start);
	 $end = encodeDate($end);

	if ($site == 1){
		$title = "Forest Grove Layers Summary";
	} else {
		$title = "San Jose Layers Summary";
	}
	$title .= " ".$start." to ".$end;
	$condition = " AND drawer.name REGEXP '^[5678][0-9]+$'";
	$Jobs = "All Jobs";
	if ($jobscat == 1){
		$condition .= " AND folder.cre_date >= rev.cre_date";
		$Jobs = "FTT Jobs";

	}
	
	$summary = "SELECT count(rev.name) AS Total_Jobs, sum(num_total) AS Total_Board_Layers, sum(num_drill) AS Total_Drill_Layers, sum(num_rout) AS Total_Rout_Layers";
	$summary .= " FROM rev, layers WHERE layers.id_rev = rev.id AND rev.cre_date BETWEEN '".$start."' AND '".$end."' AND rev.id in";
	$summary .= "(SELECT rev.id FROM rev, folder, drawer WHERE rev.id_folder = folder.id AND folder.id_drawer = drawer.id AND drawer.siteid = ".$site.$condition.")";
	//echo $summary;
	$con = $db->connect();
	$summary_result = $con->query($summary);


	//echo "<hr size=\"1\" noshade=\"noshade\"/>\n";
	echo "<center><table width=\"80%\">\n";
	echo "<tr>\n";
	echo "<th align=\"center\" colspan=\"4\"><h2>$title</h2></th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" align=\"center\"><h3>$Jobs</h3></td>\n";
	echo "<td valign=\"top\" align=\"center\"><h3>Board Layers</h3></td>\n";
	echo "<td valign=\"top\" align=\"center\"><h3>Drill Layers</h3></td>\n";
	echo "<td valign=\"top\" align=\"center\"><h3>Rout Layers</h3></td>\n";
	echo "</tr>\n";
	while( $row = $summary_result->fetch_assoc()) {
		$numjobs = $row['Total_Jobs'];
		$num_board = $row['Total_Board_Layers'];
		$num_drill = $row['Total_Drill_Layers'];
		$num_rout = $row['Total_Rout_Layers'];
		$layers_per_job = sprintf("%.2f",$num_board / $numjobs);
		$drills_per_job = sprintf("%.2f",$num_drill / $numjobs);
		$routs_per_job = sprintf("%.2f",$num_rout / $numjobs);

		echo "<tr>\n";
		echo "<td valign=\"top\" align=\"center\"><h3>$numjobs</h3></td>\n";
		echo "<td valign=\"top\" align=\"center\"><h3>$num_board</h3></td>\n";
		echo "<td valign=\"top\" align=\"center\"><h3>$num_drill</h3></td>\n";
		echo "<td valign=\"top\" align=\"center\"><h3>$num_rout</h3></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td&nbsp</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td&nbsp</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" align=\"left\"><h3>Layers per job</h3></td><td valign=\"top\" align=\"left\"><h3>$layers_per_job</h3></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" align=\"left\"><h3>Drill layers per job</h3></td><td valign=\"top\" align=\"left\"><h3>$drills_per_job</h3></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" align=\"left\"><h3>Rout layers per job</h3></td><td valign=\"top\" align=\"left\"><h3>$routs_per_job</h3></td>\n";
	echo "</tr>\n";

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

