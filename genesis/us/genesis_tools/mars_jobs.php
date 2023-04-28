<?php
/*
 * populate data stores for MARS data
 *
 *
 */
include("./lib/login.php");

$task = 'LISTJOBS';
$patt = "81%";
if ( isset($_POST['task'])){
	$task = $_POST['task'];   // Get this from Ext
}

if ( isset($_POST['query'])){
	$patt = $_POST['query'];   // Get this from Ext
	$patt = $patt."%";
}

$db = new MarsConnect();

switch($task){
	case "LISTJOBS":              // Give the entire list
		getJobs($patt);
		break;
	default:
		echo "{failure:true}";  // Simple 1-dim JSON array to tell Ext the request failed.
		break;
}


	function getJobs($patt) {
		global $db;
		$con = $db->connect();
		$query = "SELECT id, name FROM drawer WHERE siteid = 1 AND";
		$query .= " name LIKE '".$patt."' ORDER BY name";
		$result = $con->query($query);
		if ($result->num_rows > 0) {
			while( $row = $result->fetch_assoc()) {
				$arr[] = $row;
			}
			$jsonresult = json_encode($arr);
			echo '({"total":"'.$result->num_rows.'","results":'.$jsonresult.'})';
		} else {
			echo '({"total":"0", "results":""})';
		}
	}

?>
