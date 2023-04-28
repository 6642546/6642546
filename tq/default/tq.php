<?php
	date_default_timezone_set(PRC);
	$job_name = $_GET['job_name'];
	$site = $_GET['site'];
	$lang = $_GET['lang'];
	$this_site = $site;
	if ($site == 'GZ') $this_site="VIA_GZ";
	
	if (strpos($job_name,"Rev")==true){
		$job_name = trim(substr($job_name,0,strpos($job_name,"Rev")-1));
	}

	$tq_db=@mysql_connect('10.1.176.180','kylej','kylej') or die("Can not connect mysql database." . mysql_error());
	mysql_select_db('feeweb',$tq_db) or die("Could not select database");
	$my_query = "select customer_pn,customer_rev,site_tooling_pn,customer_name from tq_main where (site_tooling_pn='".$job_name."' or customer_pn ='".$job_name."') and site_name='$this_site'";
	//echo $my_query ;
	$my_req=@mysql_query($my_query,$tq_db);
	while ($return = @mysql_fetch_array($my_req)){
		$customer_pn = $return[0];
		$customer_rev=$return[1];
		$site_tooling_pn = $return[2];
		$customer_name = $return[3];
	}

	if ($customer_pn){
		if ($customer_rev!='') {$job_name= $customer_pn . " Rev " . $customer_rev;} else 	$job_name= $customer_pn;
	}

	function getTQDate($file_name,$tq_db){
		$tq_date = "";
		$my_query = "select received_date from tq_main where file_name='$file_name'";
		$my_req=@mysql_query($my_query,$tq_db);
		while ($return = @mysql_fetch_array($my_req)){
			$tq_date = $return[0];
		}	
		return $tq_date;
	}


if ($job_name =='') $job_name= $_GET['job_name'];
$old_folder = $job_name;
$job_name = str_replace('/','',$job_name);
$job_name = str_replace('\\','',$job_name);
$job_name = str_replace('*','',$job_name);
$job_name = str_replace('<','',$job_name);
$job_name = str_replace('>','',$job_name);
$job_name = str_replace('|','',$job_name);
$job_name = str_replace('"','',$job_name);
$job_name = str_replace('$','',$job_name);

$startdir = "tq/tq_data/" .$job_name;
$showthumbnails = false; 
$showdirs = true;
$forcedownloads = false;
$hide = array(
				'dlf',
				'images',
				'public_html',
				'index.php',
				'Thumbs',
				'.htaccess',
				'.htpasswd'
			);
$displayindex = false;
$allowuploads = false;
$overwrite = false;

$indexfiles = array (
				'index.html',
				'index.htm',
				'default.htm',
				'default.html'
			);
			
$filetypes = array (
				'png' => 'jpg.gif',
				'jpeg' => 'jpg.gif',
				'bmp' => 'jpg.gif',
				'jpg' => 'jpg.gif', 
				'gif' => 'gif.gif',
				'zip' => 'archive.png',
				'rar' => 'archive.png',
				'exe' => 'exe.gif',
				'setup' => 'setup.gif',
				'txt' => 'text.png',
				'htm' => 'html.gif',
				'html' => 'html.gif',
				'php' => 'php.gif',				
				'fla' => 'fla.gif',
				'swf' => 'swf.gif',
				'xls' => 'xls.gif',
				'doc' => 'doc.gif',
				'sig' => 'sig.gif',
				'fh10' => 'fh10.gif',
				'pdf' => 'pdf.gif',
				'psd' => 'psd.gif',
				'rm' => 'real.gif',
				'mpg' => 'video.gif',
				'mpeg' => 'video.gif',
				'mov' => 'video2.gif',
				'avi' => 'video.gif',
				'eps' => 'eps.gif',
				'gz' => 'archive.png',
				'asc' => 'sig.gif',
				'msg' => 'mail.png',
			);
			
error_reporting(1);
if(!function_exists('imagecreatetruecolor')) $showthumbnails = false;
$leadon = $startdir;
if($leadon=='.') $leadon = '';
if((substr($leadon, -1, 1)!='/') && $leadon!='') $leadon = $leadon . '/';
$startdir = $leadon;

if($_GET['dir']) {
	//check this is okay.
	
	if(substr($_GET['dir'], -1, 1)!='/') {
		$_GET['dir'] = $_GET['dir'] . '/';
	}
	
	$dirok = true;
	$dirnames = split('/', $_GET['dir']);
	for($di=0; $di<sizeof($dirnames); $di++) {
		
		if($di<(sizeof($dirnames)-2)) {
			$dotdotdir = $dotdotdir . $dirnames[$di] . '/';
		}
		
		if($dirnames[$di] == '..') {
			$dirok = false;
		}
	}
	
	if(substr($_GET['dir'], 0, 1)=='/') {
		$dirok = false;
	}
	
	if($dirok) {
		 $leadon = $leadon . $_GET['dir'];
	}
}



$opendir = $leadon;
if(!$leadon) $opendir = '.';
if(!file_exists($opendir)) {
	$no_tq= "<a href='index.php?site=$site&action=tq&lang=$lang' target='_top' style='color:red;font-weight:bold;'>No TQ found for this part number. Click here to search?</a>";
	//exit;
}

clearstatcache();
if ($handle = opendir($opendir)) {
	while (false !== ($file = readdir($handle))) { 
		//first see if this file is required in the listing
		if ($file == "." || $file == "..")  continue;
		$discard = false;
		for($hi=0;$hi<sizeof($hide);$hi++) {
			if(strpos($file, $hide[$hi])!==false) {
				$discard = true;
			}
		}
		
		if($discard) continue;
		if (@filetype($leadon.$file) == "dir") {
			if(!$showdirs) continue;
		
			$n++;
			if("date"=="date") {
				$key = @filemtime($leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			$dirs[$key] = $file . "/";
		}
		else {
			$n++;
			if("date"=="date") {
				$key = @filemtime($leadon.$file) . ".$n";
			}
			elseif($_GET['sort']=="size") {
				$key = @filesize($leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			$files[$key] = $file;
			
			if($displayindex) {
				if(in_array(strtolower($file), $indexfiles)) {
					header("Location: $file");
					die();
				}
			}
		}
	}
	closedir($handle); 
}

//sort our files
if("date"=="date") {
	@ksort($dirs, SORT_NUMERIC);
	@ksort($files, SORT_NUMERIC);
}
elseif($_GET['sort']=="size") {
	@natcasesort($dirs); 
	@ksort($files, SORT_NUMERIC);
}
else {
	@natcasesort($dirs); 
	@natcasesort($files);
}

//order correctly
if($_GET['order']=="desc" && $_GET['sort']!="size") {$dirs = @array_reverse($dirs);}
if("desc"=="desc") {$files = @array_reverse($files);}
$dirs = @array_values($dirs); $files = @array_values($files);


?>
<link href="tq/default/images/styles.css" type="text/css" rel="Stylesheet" />
<script type="text/javascript">
	$(document).ready(function(){ 
		$(".delete_btn").bind("click",
		function()  {
			if ($("#tq_login").html()=="No")
			{
				if($("body").find("#tq_login_window").length==0) {
					$append_text = '<div id="tq_login_window">'+
					'<table>'+
					'	<tr><td>User Name:</td><td><input type="text" id="uname"></input></td></tr>'+
					'	<tr><td>Password:</td><td><input type="password" id="chknumber"></input></td></tr>'+
					'</table></div>';
					$("body").append($append_text);
				}
				

				$('#tq_login_window').dialog({  
		         hide:'',
		         autoOpen:false, 
				 resizable:true, 
		         height:187,
		         width:350,  
		         modal:true,
		         title:'Login...',   
		         buttons:{ 
				 	'OK':function(){  
		                login();  
		             },  
				 	'Cancel':function(){  
		              $('#tq_login_window').dialog('close');  
		             }
		              
		             
		         }  
				});
				$('#tq_login_window').dialog('open');
				return;
			}
			 

			if ( confirm('Are you sure want to delete this TQ file?'))
			{
			var file_name = $(this).parent().parent().find(".headimg").attr("alt");
			var folder_name =  $(this).parent().parent().find(".headimg").attr("folder_name");
			var old_folder = $(this).parent().parent().find(".headimg").attr("old_folder");
			if (file_name){
				$.ajax({ 
                    type: "POST", 
                    url: "tq/default/delete.php",
					data:{file_name: file_name,folder_name:folder_name,old_folder:old_folder},
                    dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);
						   return;
                         }
                        else 
                        if (Result == true) {
							alert(msg.message);
							window.location.href=window.location.href;
                        }
                       } 
				});
			}
			}
			
		});


		  function login(){
		  
			$.ajax({ 
						type: "POST", 
						url: "tq/default/login.php?uname="+$("#uname").val(),
						data:{chknumber:$("#chknumber").val()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   alert(msg.message);
							   return;
							 }
							else 
							if (Result == true) {
								window.location.href = window.location.href;
							}
						   } 
			  });
		  
		  }


	})

</script>
<?php 
	$lifeTime = 24 * 3600 * 365; 
	session_set_cookie_params($lifeTime); 
	session_start();
	
	if ($_SESSION['FEEWEBlogin'] == "ok" and hasFEEUserRole($_SESSION['FEEWEB_uName'],'TQ Admin')==1) 
	{
		echo "<div id='tq_login' style='display:none'>Yes</div>"; 
	}
	else { 
		echo "<div id='tq_login' style='display:none'>No</div>"; 
	}
?>
<div style="padding:5px;">
<div id="folder_name" style="display:none;"><?php echo $job_name ;?></div>
<div id="container">
  <div><br/><font size=+1>TQ Emails <?php if ($site_tooling_pn) { echo " for Site Tooling #: <a style='color:blue' href='index.php?site=$site&action=inplan&job_name=$site_tooling_pn&data=Job Attributes&lang=$lang' target='_top'>$site_tooling_pn</a>";}?></div></font></br>
	<table class="tq_table">
	<tr><th width="64%">File Name</th><th width="8%">Size</th><th width="20%">Received Date</th><th width="8%">Action</th></tr>
	<?php
		$arsize = sizeof($files);
		for($i=0;$i<$arsize;$i++) {
			$icon = 'unknown.png';
			$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
			$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
			$thumb = '';
					
			if($filetypes[$ext]) {
				$icon = $filetypes[$ext];
			}
			
			$filename = $files[$i];
			if(strlen($filename)>50) {
				$filename = substr($files[$i], 0, 50) . '...';
			}
			
			$fileurl = $leadon . $files[$i];
			$file_time = getTQDate($files[$i],$tq_db);
		?>
		<tr><td><a href="tq/default/download.php?filename=<?php echo "../../".$fileurl;?>" ><img class="headimg" src="tq/default/images/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" folder_name="<?php echo $job_name;?>" old_folder="<?php echo $old_folder;?>"/><strong><?php echo $filename;?></strong></td>
		<td><span class="size"><?php echo round(filesize($leadon.$files[$i])/1024);?> KB</span></td>
		<td><span class="size"><?php echo $file_time;?></span></td>
		<td><img class="delete_btn" style="cursor:pointer;" alt="Delete TQ file" src="tq/default/images/ico-del.png"/></td>
		</tr>

		<?php
			}	
			if($arsize ==0) {
				echo "<tr><td colspan=4>$no_tq</td></tr>";
			}
		?>
  </table>
  </div>



<?php

if($customer_name) {
			$files=null;
			$startdir ="tq/tq_data/" .$customer_name;
			$leadon = $startdir ;

		$opendir = $leadon;
		if(!$leadon) $opendir = '.';
		if(file_exists($opendir)) {
			clearstatcache();
		if ($handle = opendir($opendir)) {
			while (false !== ($file = readdir($handle))) { 
				//first see if this file is required in the listing
				if ($file == "." || $file == "..")  continue;
				$discard = false;
				for($hi=0;$hi<sizeof($hide);$hi++) {
					if(strpos($file, $hide[$hi])!==false) {
						$discard = true;
					}
				}
				
				if($discard) continue;
				if (@filetype($leadon.$file) == "dir") {
					if(!$showdirs) continue;
				
					$n++;
					if("date"=="date") {
						$key = @filemtime($leadon.$file) . ".$n";
					}
					else {
						$key = $n;
					}
					$dirs[$key] = $file . "/";
				}
				else {
					$n++;
					if("date"=="date") {
						$key = @filemtime($leadon.$file) . ".$n";
					}
					elseif($_GET['sort']=="size") {
						$key = @filesize($leadon.$file) . ".$n";
					}
					else {
						$key = $n;
					}
					$files[$key] = $file;
					
					if($displayindex) {
						if(in_array(strtolower($file), $indexfiles)) {
							header("Location: $file");
							die();
						}
					}
				}
			}
			closedir($handle); 
		}

		//sort our files
		if("date"=="date") {
			@ksort($dirs, SORT_NUMERIC);
			@ksort($files, SORT_NUMERIC);
		}
		elseif($_GET['sort']=="size") {
			@natcasesort($dirs); 
			@ksort($files, SORT_NUMERIC);
		}
		else {
			@natcasesort($dirs); 
			@natcasesort($files);
		}

		//order correctly
		if($_GET['order']=="desc" && $_GET['sort']!="size") {$dirs = @array_reverse($dirs);}
		if("desc"=="desc") {$files = @array_reverse($files);}
		$dirs = @array_values($dirs); $files = @array_values($files);
		
		$arsize = sizeof($files);
		if($arsize>0) {
			echo '<div><br/><br/><font size=+1>TQ Emails for Customer:<span style="color:blue;">'. $customer_name. '</span></div></font></br>
					<table class="tq_table">
					<tr><th width="64%">File Name</th><th width="8%">Size</th><th width="20%">Received Date</th><th width="8%">Action</th></tr>';
			
			for($i=0;$i<$arsize;$i++) {
			$icon = 'unknown.png';
			$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
			$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
			$thumb = '';
					
			if($filetypes[$ext]) {
				$icon = $filetypes[$ext];
			}
			
			$filename = $files[$i];
			if(strlen($filename)>50) {
				$filename = substr($files[$i], 0, 50) . '...';
			}
			
			$fileurl = $leadon . $files[$i];
			//<?php echo $fileurl;
		?>
		
		<tr><td><a href="tq/default/download.php?filename=<?php echo "../../".$fileurl;?>" ><img id="<?php echo $item_id;?>" class="headimg" src="tq/default/images/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" folder_name="<?php echo $customer_name;?>" old_folder="<?php echo $old_folder;?>"/><strong><?php echo $filename;?></strong></td>
		<td><span class="size"><?php echo round(filesize($leadon.$files[$i])/1024);?> KB</span></td>
		<td><span class="size"><?php echo date ("M d Y h:i:s A", filemtime($leadon.$files[$i]));?></span></td>
		<td><img class="delete_btn" style="cursor:pointer;" alt="Delete TQ file" src="tq/default/images/ico-del.png"/></td>
		</tr>

		<?php
			}	
		?>
		</table>


		<?php }
}
}
?>
		
</div>
</div>