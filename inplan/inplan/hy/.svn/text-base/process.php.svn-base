<style>
	<!-- 

		.res-block {
			padding-top:5px;
			background:transparent url(images/block-top-1.gif) no-repeat;
			width:278px;
			margin-bottom:5px;
		}

		.inheritance {
			margin-top:10px;
			float:left;
			width:278px;
			clear:right;
			overflow:hidden;
		}

		 pre {
			text-align:left;
			border: 0 none;
			margin: 0;
			font-size: 11px;
			line-height:18px !important;
			background:transparent url(images/block-bottom-1.gif) no-repeat left bottom;
			padding:0 8px 5px!important;
		}

		.proc {
			cursor:pointer;
			text-decoration :underline ;
		}


	
	-->
</style>
<?php
	$subparts = "";
	$process_text = "<div class='inheritance res-block'><pre>";
	$sub_query = "select distinct i.item_name
						,job.mrp_name
					,process.MRP_NAME  sub_mrp_name
					,PROCESS.PROC_SUBTYPE 
					,iprocess.item_name
				from items i
					,job
					,process
					,items iprocess
				where i.item_type=2
					  and i.item_id=job.item_id
					  and job.revision_id=i.last_checked_in_rev
					  and i.root_id=iprocess.root_id
					  and iprocess.ITEM_ID=process.item_id
					  and process.REVISION_ID=iprocess.last_checked_in_rev
					  and iprocess.deleted_in_graph is null
					  and PROCESS.PROC_SUBTYPE in (27,28,29,1001)  
					  and i.item_name='$job' 
					  order by PROCESS.PROC_SUBTYPE desc,process.mrp_name";
			$rsSub = oci_parse($conn, $sub_query);
			oci_execute($rsSub, OCI_DEFAULT);
			$i = 0;
			while(oci_fetch($rsSub)){
				if ($i == 0) {
					if ($process == "" or $process == oci_result($rsSub, 3)) 
					{
						$process = oci_result($rsSub, 3);
						$process_text .= "<span class='proc' style='color:blue;font-weight:bold;'>".oci_result($rsSub, 3)."</span> ".oci_result($rsSub, 5);
					} else
					$process_text .= "<span class='proc'>".oci_result($rsSub, 3)."</span> ".oci_result($rsSub, 5);
					
				} else {
					if ($process == oci_result($rsSub, 3))
					{
						$process_text .= "&#13&nbsp;&nbsp;<img src='$image_dir/images/elbow-end.gif'/><span class='proc' style='color:blue;font-weight:bold;'>" . oci_result($rsSub, 3)."</span> ".oci_result($rsSub, 5);
					} else
					$process_text .= "&#13&nbsp;&nbsp;<img src='$image_dir/images/elbow-end.gif'/><span class='proc'>" . oci_result($rsSub, 3)."</span> ".oci_result($rsSub, 5);
					$subparts .="<tr><td>Subpart</td><td>".oci_result($rsSub, 3)."</td><td colspan=2>".oci_result($rsSub, 5)."</td></tr>";
					$subpart_item_name = oci_result($rsSub, 5);
				}

				$i++;
			}
			if ($i >1 ) echo $process_text . "</pre></div>";
?>
<!-- script type="text/javascript" src="scripts/jquery-1.4.4.min.js"></script -->
<script type="text/javascript">
	$(document).ready(function(){
		$(".proc").bind("click",function(){
			var top_url = window.parent.location.href;
			var pre_url = top_url.substring(0,top_url.indexOf("?"));
			var url = pre_url + "?site=" + request('site') + "&action=" + request('action') + '&data=' + request('data') ;
			if ( request('action') == "inplan") {
				url = url + '&job_name=' +  request('job_name');
			}
			if (request('lang') !='')
			{
				url = url + '&lang='+request('lang');
			}
			url = url + '&process=' + $(this).html();
			window.parent.location.href = url;
		})
	})

	function request(paras)
    { 
        var url = window.parent.location.href; 
        var paraString = url.substring(url.indexOf("?")+1,url.length).split("&"); 
        var paraObj = {} 
        for (i=0; j=paraString[i]; i++){ 
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length); 
        } 
        var returnValue = paraObj[paras.toLowerCase()]; 
        if(typeof(returnValue)=="undefined"){ 
        return ""; 
        }else{ 
        return returnValue; 
        } 
    }
</script>