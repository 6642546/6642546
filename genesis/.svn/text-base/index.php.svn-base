<?php
	// config this file to link to site pages.

	if (file_exists("genesis/hy") and ($site == "HY" or $site == "HZ")){
		require "genesis/hy/index.php";
	} else if (file_exists("genesis/gz") and ($site== "GZ" or $site == "ZS")){
		require "genesis/gz/index.php";
	} else if (file_exists("genesis/us") and ($site== "SJ" or $site == "FG")){
		require "genesis/us/index.php";
	} else require "genesis/default/index.php";

?>

<script type="text/javascript">
var i =0;
function reinitIframe(){
	
var iframe = document.getElementById("frame_content");
if (iframe){
	try{
	if(iframe.contentWindow.document.readyState=="complete"){
	var bHeight = iframe.contentWindow.document.body.scrollHeight;
	var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
	var height = Math.max(bHeight, dHeight);
	iframe.height =  height;
	i = i +2;
	}
	if (i>=4) {
		clearInterval(sh);
	}
	}catch (ex){}

}

}
var sh;
sh = window.setInterval("reinitIframe()", 200);

</script>