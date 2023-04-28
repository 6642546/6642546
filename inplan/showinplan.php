<?php
	// config this file to link to site pages.

	if (file_exists("inplan/hy") and ($_GET['site'] == "HY" or $_GET['site'] == "HZ")){
		if (isset($_GET['tq_pn'])){
			require "tq/default/index.php";
		} else require "inplan/hy/index.php";
	} else if (file_exists("inplan/gz") and ($_GET['site'] == "GZ" or $_GET['site'] == "ZS")){
		require "inplan/gz/index.php";
	} else if (file_exists("inplan/sj") and ($_GET['site'] == "SJ" or $_GET['site'] == "FG")){
		require "inplan/sj/index.php";
	} else require "inplan/allsites/index.php";

?>