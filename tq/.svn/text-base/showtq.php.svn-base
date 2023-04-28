<?php
	// config this file to link to site pages.

	if (file_exists("tq/hy") and ($_GET['site'] == "HY" or $_GET['site'] == "HZ")){
		require "tq/hy/index.php";
	} else if (file_exists("tq/gz") and ($_GET['site'] == "GZ" or $_GET['site'] == "ZS")){
		require "tq/gz/index.php";
	} else if (file_exists("tq/sj") and ($_GET['site'] == "SJ" or $_GET['site'] == "FG")){
		require "tq/sj/index.php";
	} else require "tq/default/index.php";

?>