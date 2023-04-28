<html>
<div style="padding:10px;">
<?php 
	$site = $_GET['site'];
	$job =  $_GET["job_name"];
?>
   <style>
    .tq_table {
	border-collapse:collapse;
	border:solid 1px #A7C5FF;
	width:100%;
}

.tq_table th {
	font-size: medium;
	background:#ADADAD;
	color:white;
	height:25px;
	font-weight:bold;
	padding-left:5px;
	border-bottom:1px solid #A7C5FF;
}

.tq_table td {
	font-size:14px;
	background:#FFF8D7;
	padding-left:5px;
	border-bottom:1px solid #A7C5FF;
	font-weight:bold;
	text-align: left;
}

.tq_table a {
	color:#2C6AA9;
	line-height:25px;
}

.tq_table .size {
	font-size:12px;
}

    </style>   

	<?php 
	      $wsid='gzrac.ttm.com/PRD1';
          $wdbusername='INPLAN';
          $wdbpassword='qPWSvG4B';
          $wERP_conn = oci_connect($wdbusername, $wdbpassword, $wsid,'utf8');
	       //get folder size 
           function dir_size($dir) 
           {     
           if (!preg_match('#/$#', $dir))
           {        
           $dir .= '/';     
           }    
           $totalsize = 0;    
           //transfer file list    
           foreach (get_file_list($dir) as $name) 
           {
           $totalsize += (@is_dir($dir.$name) ? dir_size("$dir$name/") :             (int)@filesize($dir.$name));     
           }    
           return $totalsize; 
           }  
           //get file list 
           function get_file_list($path) 
           {    
           $f = $d = array();     
           //get all file    
           foreach (get_all_files($path) as $name) 
           {       if (@is_dir($path.$name)) 
           {
           $d[] = $name;         
           } 
           else if (@is_file($path.$name)) 
           {            
           $f[] = $name;         
           }    
           }     
           natcasesort($d);    
           natcasesort($f);     
           return array_merge($d, $f); 
           } 
           
           function get_all_files($path) 
           {     
           $list = array();     
           if (($hndl = @opendir($path)) === false) 
           {        
           return $list;     
           }    
           while (($file=readdir($hndl)) !== false) 
           { 
           if ($file != '.' && $file != '..') 
           { 
           $list[] = $file;
           }
           }     
           closedir($hndl);    
           return $list; 
           } 
           
           //change unit 
           function setupSize($fileSize) 
           {     
           $size = sprintf("%u", $fileSize);     
           if($size == 0) 
           {
           return("0 Bytes");     
           }     
           $sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");    
           return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i]; 
           } 
	echo "<div class='header'><center><b><font size=+2>Drawing: $job</font></b></center><br><br>";
	echo "<center><table class='tq_table'><tr><th width='30%'>Drawing Name</th><th width='20%'>Size</th><th width='20%'>Date Modified</th></tr>";   
	//$file = "\\\\10.120.238.82\\MI_Graph\\$job.pdf";
	//echo '<script> alert("'.$file.'");</script>';
    // $fileExists = @file_get_contents($file,null,null,-1,1) ? true : false;
	$job=str_replace('-3','',$job);
	$sql="select a.* from mtg_upload_file a, DATA0050 b where a.div_code='GZ' and  a.d50ptr=b.pkey and file_desc like '%电子MI' and  b.customer_part_number='".$job."'";
	 $stid1 = oci_parse($wERP_conn , $sql);
	 $r1 = oci_execute($stid1, OCI_DEFAULT);
	 $jobpdf='';
	 //echo '<script> alert("'.$sql.'");</script>';
	 while ($row1 = oci_fetch_array($stid1, OCI_RETURN_NULLS)) {			          
					   $jobpdf=$row1['FILE_NAME'];
					  
				  }
	$file = "\\\\10.120.238.82\\MI_Graph\\$jobpdf";
    if($jobpdf){
       $size = setupSize(filesize($file)); 
	   $moddate = date ("Y/m/d", filemtime($file));
	   echo "<tr><td><a href='file:\\\\10.120.238.82\\MI_Graph\\$jobpdf'   target='_top' style='color:red;font-weight:bold;'>$job</a></td><td>$size</td><td>$moddate</td></tr>";
	}else{
	   echo "<tr><td colspan=3><span style='color:red;font-weight:bold;'>No Drawing found for this part number.</span></td></tr>";
	   //echo "<tr><td><a href='file:\\\\10.120.238.82\MI_Graph\\A122436C-302.pdf'   target='_top' style='color:red;font-weight:bold;'>$foldername</a></td><td>$size</td><td>$moddate</td></tr>";
	}
	 echo "</table></center></div>";

	// select a.* from mtg_upload_file a, DATA0050 b where a.div_code='GZ' and  a.d50ptr=b.pkey and b.customer_part_number='P04F344A00'
 ?>
</div>
</html>