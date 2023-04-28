<?php
	$url = $_POST['url'];
	$part_number =  $_POST['part_number'];

	// delete old files:
	$dir = getcwd() . '/temp/';
	$dh = opendir($dir);
	while ($file = readdir($dh)){
		if ($file !="." && $file != ".."){
			$fullpath = $dir."/".$file;
			if (!is_dir($fullpath)){
				if (time() - filemtime($fullpath)>3600) // caching time, in seconds.
				{
					unlink($fullpath);
				}
			}
		}
	}
	closedir('temp/');


    wkhtmltox_convert('pdf', 
        array('out' => 'temp/'.$part_number.'-buildability.pdf',
			  'page-size' =>'A4'), // global settings
        array(
            array('page' => $url)
            ));
	echo "{\"success\":true}";

?>