<?php
/*
 * populate data stores for MARS data
 *
 *
 */
	include("./lib/login.php");

	$task = '';

	if ( isset($_POST['task'])){
		$task = $_POST['task'];   // Get this from Ext
	}

	$myfile = "/tmp/testfile";
	$fh = fopen($myfile, 'w');
	fwrite($fh, $task);
	fclose($fh);

	$db = new MarsConnect();


	function getRevs($task) {
		global $db;
		$con = $db->connect();
		$query = "SELECT rev.id, rev.cre_date FROM rev, folder, drawer WHERE drawer.siteid = 1 AND";
		$query .= " rev.prod_status != 'O' AND rev.id_folder = folder.id AND folder.name = 'GENESIS_JOB' AND";
		$query .= " folder.id_drawer = drawer.id AND drawer.name = '".$task."' ORDER BY cre_date DESC";
		
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

	getRevs($task);

?>
