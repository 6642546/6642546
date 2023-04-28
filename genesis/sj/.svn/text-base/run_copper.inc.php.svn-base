<?php
  include("./lib/login.php");

  $jobname = $_REQUEST['jobname'];
  if (!isset($_REQUEST['marsrev'])) {
	  $jobrev = "live";
  } else {
	  $jobrev = $_REQUEST["marsrev"];
  }
  $step = $_REQUEST['step'];
  $gridsize = $_REQUEST['gridsize'];
  $format = $_REQUEST['format'];

 /*  echo "$jobname";
   echo "<br>";
   echo "$step";
   echo "<br>";
   echo "$gridsize";
   echo "<br>";
   echo "$jobrev";
   echo "<br>";
   echo "$format";  */

   //exit; 

   // if live job then make sure it is not not checked out by someone else
   // make sure it not lock up
   if ($jobrev == 'live') {
		$send = "ssh genesis@fgcam01 dbutil lock test ".$jobname;
		exec($send,$output,$status);
		$islocked = explode(" ", $output[0]);
		if (trim($islocked[0]) == 'yes') {
			//echo "<h3>Job is locked by another user. Try again later </h3>\n";
			exit('Job is locked by another user. Try again later');
		}
		$Revision = $jobrev;
		// print_r($output);
   }
   // if archived job then get the name of the actual revision from the rev date
   if ($jobrev != 'live') {
	   $db = new MarsConnect();
	   $con = $db->connect();
	   $query = "SELECT rev.name FROM rev, folder, drawer WHERE drawer.siteid = 1 AND";
	   $query .= " rev.id_folder = folder.id AND folder.name = 'GENESIS_JOB' AND";
	   $query .= " folder.id_drawer = drawer.id AND drawer.name = '".$jobname."' AND rev.cre_date = '".$jobrev."'";
	   //echo $query;
       $result = $con->query($query);
       if ($result->num_rows != 1) {
		 exit('Error could not find revision name from Mars');
	   }
	   $row = $result->fetch_assoc();
	   $Revision = $row['name'];
   }
  // get pid of this process. this is passed to Genesis
  $mypid = getmypid();
  $mypid = strval($mypid);
  $reportFile = "/home/genesis/report/" . $mypid . "/" . $jobname . ".rpt";
  $errorFile = "/home/genesis/report/" . $mypid . "/" . "Error";
  // execute remote script on Genesis server
  $send = "ssh genesis@fgcam01 /cam/genesis/edir/linux/e92/get/get -x -s/cam/genesis/sys/scripts/tools/inner_cu_dist.py";
  $send = $send . " " . "$jobname $Revision $step $gridsize $mypid";
  $send = $send . " " . "> /dev/null";
  
  exec($send,$output,$status);
  //print_r($output);  exit;
  if ($status == 1) {
	  // unknown error occured
	  if ($jobrev == 'live') {
		$send = "ssh genesis@fgcam01 /cam/genesis/edir/linux/e92/get/get -x -s/cam/genesis/sys/scripts/tools/checkinWebjob.py";
		$send = $send . " " . "$jobname";
		$send = $send . " " . "> /dev/null";
		exec($send,$output,$status);
	  }
	  
	  echo "<h3>Step name does not exist in job or unknown error occurred On Genesis server </h3>\n";
   
  } elseif (file_exists($errorFile)) {
	  // parse report file and put in table
	  // check for a known error 
	  $genError = file_get_contents($errorFile);
	  echo "<h3>$genError</h3>\n";
  } else {
	//echo    $reportFile; exit;
    header("Location: copper_report.php?report=$reportFile&format=$format");

  }
?>