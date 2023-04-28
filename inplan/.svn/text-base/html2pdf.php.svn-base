<?php
	$url = $_POST['url'];
	$part_number =  $_POST['part_number'];
	$type = $_POST['type'];
	$site = $_POST['site'];

	// delete old files:
	$dir = getcwd() . '/temp';
	$dh = opendir($dir);
	while ($file = readdir($dh)){
		if ($file !="." && $file != ".."){
			$fullpath = $dir."/".$file;
			if (!is_dir($fullpath)){
				if (time() - filemtime($fullpath)>3600) // caching time, in seconds.
				{
					@unlink($fullpath);
				}
			}
		}
	}
	closedir($dh);


 /*   wkhtmltox_convert('pdf', 
        array('out' => 'temp/'.$part_number.'-stackup.pdf',
			  'page-size' =>'A4'), // global settings
        array(
            array('page' => $url)
            )); */
	// 
	if ($site == "FG" or $site == "SJ")
	{
		$send = "/srv/www/htdocs/feeweb/wkhtmltopdf --footer-right \"Page [page] of [topage]        [date]\" \"$url\" ". $dir.'/'.$part_number.'-'.$type.'.pdf';
	} else {
		$send = "/usr/local/bin/wkhtmltopdf --footer-right \"Page [page] of [topage]        [date]\" \"$url\" ". $dir.'/'.$part_number.'-'.$type.'.pdf';
	}
	
	exec($send,$output,$status);
	if ($status == 0 || $status == 2 ) {
		echo "{\"success\":true}";
	} else {
		echo "{\"success\":false,\"message\":\"" . "Failed to convert PDF." ."\"}";
	}
?>