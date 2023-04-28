<?php
   //session_start();
   $task = '';
   
   if ( isset($_POST['task'])){
     $task = $_POST['task'];   // Get this from Ext
   }
 
    switch($task){
     case "LIST":              // Give the entire list
       getList($patt);
       break;
     default:
       echo "{failure:true}";  // Simple 1-dim JSON array to tell Ext the request failed.
       break;
   }
	

	function getList()
	{
		// execute remote command on Genesis server
		$jobs = array();
		$send = "ssh genesis@fgcam01 dbutil list jobs";
		exec($send,$output,$status);
		foreach ($output as $value) {
		   $tokens = explode("in DB", $value);
		   
		   if(trim($tokens[1]) == "live") {
		      // set a filter to weed out junk jobs
			  $jname = trim($tokens[0]);
			  if (preg_match($patt, $jname)) {
				$item = array("name"=>$jname);
				$jobs[] = $item;
			  }
		   }
		}
		
		$numjobs = count($jobs);
		if($numjobs > 0){
			$jsonresult = json_encode($jobs);
			echo '({"total":"'.$numjobs.'","results":'.$jsonresult.'})';
		} else {
			echo '({"total":"0", "results":""})';
		}
	}
?>
