<html>
 <style>
	
	#top_div table{
		font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;

	}
	 
	.tab {
      	    border:solid 2px black;
      	    padding:0;
      	    border-spacing:0;
      	    border-collapse:collapse;
      	    width:100%;
			margin-top:5px;
			margin-left:20px;

			}
  #top_div td ,#top_div th
       {
         border:solid 1px black;
         height:20px;
	     padding-left:4px;
		margin-left:20px;
       }
	.head_left{
		 float:left;
		 padding-left:150px;
		 margin-top:1px;
		 margin-bottom:10px;
		 margin-left:20px;
	}
	.head_right{
	       float:right;
		   margin-bottom:70px;
		   padding-right:20px;
		   margin-top:10px;
		   margin-left:20px;
	/*  border:solid 1px #99BBDD; */
		   }
	   .head_box_1{
		       width:100%;
			   height:50px;
	           border-bottom:solid 1px black;
			   margin-left:20px;
            }
      .head_bar{
		    width:100%;
			height:25px;
			margin-top:3px;
	        background-color:gray;
			font-size:150%;
			color:white;
			padding-top:5px;
			margin-left:20px;
	  }
	   .head_bar1{
		    width:auto;
			height:25px;
			margin-top:2px;
	        /*background-color:gray;*/
			font-size:200%;
			color:black;
			padding-top:5px;
			margin-left:20px;
	  }
      .head_box{
		     width:100%;
			 height:100px;
		     border-top:solid 1px black;
			 margin-top:2px;
			 border-bottom:solid 1px black;
			 text-align:right;
			 margin-left:20px;
	  }
	  .find_caption{
		   float:left;
		   font-size:120%;
		   line-height:25px;
		   text-align:right;
		   width:200px;
		   margin-left:20px;
	  }
	    .caption_value{
		   float:right;
		   font-size:120%;
		   line-height:25px;
		   text-align:left;
		   width:500px;
		   
	  }
	  .td_c{
	       text-align:center;
	        font-size:120%;
		   margin-left:20px;
	   }
	  .ci_note{
		  border-bottom: solid 2px black;
		  border-left: solid 2px black;
		  border-right: solid 2px black;
		  width:99.5%;
		  height:50px;
		  margin-left:20px;
	  }
	  .ci_note_1{
	     margin-left:20px;
		 padding-top:1px;
		 margin-left:20px;
	  }
	  .process_bar{
	        width:100%;
			height:25px;
			margin-top:5px;
	        /*background-color:gray;*/
			border:solid 1px black;
			font-size:150%;
			color:black;
			padding-top:10px;
			text-align:center;
			clear:both;
			margin-left:20px;
      }
      .trav_code{
             float:left;
	         width:100%;
			 height:20px;
			 border:solid 1px black;
			 margin-top:5px; 
	         clear:left;
			 padding-top:5px;
			 margin-left:20px;
	  }
	  .trav_code1{
		     float:right;
	         width:55px;
			 height:20px;
			 margin-top:5px;
             border-top:solid 1px black;
			 border-bottom:solid 1px black;
			 border-right:solid 1px black;
             clear:right;
			 text-align:right;
			 padding-right:10px;
			 padding-top:5Px;

	  }
	  .trav_note{
	    width:100%;
		clear:both;
		border-left:solid 1px black;
		border-right:solid 1px black;
		border-bottom:solid 1px black;
		margin-left:20px;
	  }
	
	  .attr_des{
		 
		   width:99.4%;
		   padding-left:5px;
		  
		   border-left:solid 1px black;
		   border-right:solid 1px black;
		   border-bottom:solid 1px black;
		   margin-left:20px;
	
	  }
	  .attr_value{
           float:right;
		   text-align:left;
		   width:412px;
		   border:none;
		   clear:both;
           border-right:solid 1px black;
		   border-bottom:solid 1px black;
		   display:inline-block;
		  
	  }
	  .trav_attr{

	  }
	  .conner{
	  }
 </style>
 <div id='top_div' style="padding:5px;width:740px;">
	<!--<div><center><b><font size=+2>Traveler Routing&nbsp;</font></b></center></div><br>-->
	<div class="head_box_1">
         <div class="head_left">                               
	          <p><b>Guangzhou Termbray Electronics Technology Company Limited </b></p>
		       <p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;广&nbsp;州&nbsp;添&nbsp;利&nbsp;电&nbsp;子&nbsp;科&nbsp;技&nbsp;有&nbsp;限&nbsp;公&nbsp;司</b></p>
		       <p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A member of TTM Technologies Group</b></p>
		      <!-- <p>&nbsp;&nbsp;&nbsp;A Subsidiary of Viasystems Group, Inc.</p>-->
	     </div>
	    <!-- <div class="head_right">
	           <p><b>&nbsp;&nbsp;&nbsp;&nbsp;广&nbsp;州&nbsp;添&nbsp;利&nbsp;电&nbsp;子&nbsp;科&nbsp;技&nbsp;有&nbsp;限&nbsp;公&nbsp;司</b></p>
		       A member of TTM Technologies Group
	     </div>-->
	</div>
	            
                                                      
                               
	<div class="head_bar">
		<p align="center"><center>Manufacturing Instruction</center></p>
	</div>


<?php   
    $headerinfo='<div class="head_box">
	    <div class="find_caption">
	        <p><b>Customer:</b></p>
    	    <p>Part Number:</p>
	        <p>Part Description:</p>
	        <p>End Customer:</p>
		 </div>
	   <div  class="caption_value">
	        <p><b>@@customer_name@@</b></p>
    	    <p>@@job_name@@</p>
	        <p>@@customer_pn@@</p>
	        <p>@@customer_code@@</p>
		</div>	
	</div>';
   

	$site = $_GET['site'];
	$job =  $_GET["job_name"];

	if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/allsites";
		$logo_dir = "images";
		$image_dir = ".";
		require("oracle_conn.php");
		require_once("lang.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = ".";
		$image_dir = "../..";
		$logo_dir = "stackup";
		require("../../oracle_conn.php");
		require_once("../../lang.php");
		echo "<script type='text/javascript' src='". $pre_dir_scripts . "/jquery-1.4.4.min.js'></script>";
	}
	include("traveler_query.php");
	$stid = oci_parse($conn, $sqltrav_header);
	if (!$stid) {
	  $e = oci_error($conn);
	  print htmlentities($e['message']);
	  exit;
	}

	$r = oci_execute($stid, OCI_DEFAULT);
	if(!$r) {
	  $e = oci_error($stid);
	  echo htmlentities($e['message']);
	  exit;
	}
	$row = oci_fetch_array($stid, OCI_RETURN_NULLS);
	$root_id=$row['ROOT_ID'];
	$cust_name = $row['CUSTOMER_NAME'];
	$cust_pn = $row['CUSTOMER_PN_'];
	$job_name = $row['JOB_NAME'];
	$cust_code = $row['CUSTOMER_CODE'];
	$DFM_CREATOR_ = $row['DFM_CREATOR_'];
	$DFM_CHECKER_ = $row['DFM_CHECKER_'];
	$DFM_CREATED_DATE_ = $row['DFM_CREATED_DATE_'];
	$DFM_CHECK_DATE_ = $row['DFM_CHECK_DATE_'];
    $headerinfo = str_replace("@@customer_name@@",$cust_name ,$headerinfo);
    $headerinfo = str_replace("@@job_name@@",$job_name ,$headerinfo);
    $headerinfo = str_replace("@@customer_pn@@",$cust_pn ,$headerinfo);
    $headerinfo = str_replace("@@customer_code@@",$cust_code ,$headerinfo);
    echo $headerinfo;
    $c_user=	'<table border=0 class="tab">
	<tr><td width=15%>Create By:</td><td width=30%>@@DFM_CREATOR_@@</td><td width=15%>Checked By:</td><td >@@DFM_CHECKER_@@</td></tr>
	<tr><td >Create Date:</td><td>  @@DFM_CREATED_DATE_@@ </td><td>Checked Date:</td><td>@@DFM_CHECK_DATE_@@</td></tr> 
	</table>';
   $c_user = str_replace("@@DFM_CREATOR_@@",$DFM_CREATOR_ ,$c_user);
   $c_user = str_replace("@@DFM_CHECKER_@@",$DFM_CHECKER_ ,$c_user);
   $c_user = str_replace("@@DFM_CREATED_DATE_@@",$DFM_CREATED_DATE_ ,$c_user);
   $c_user = str_replace("@@DFM_CHECK_DATE_@@",$DFM_CHECK_DATE_ ,$c_user);
	echo $c_user;
  
	$proc_info='<table class="tab" border=0 >
  <tr><td width =50% colspan="3" class="td_c">Production Part Parameters  产品参数</td><td colspan="3" class="td_c">Other Information  其他信息</td></tr>
  <tr><td width=15%>IN Quotation</td><td width=15%>计价单位</td><td>@@SO_UNIT_@@</td><td width=15% rowspan="2">Input No</td><td width=15% rowspan="2">Input 编号</td>
	<td rowspan="2">@@INPUT_NUMBER_@@</td>
  </tr>
  <tr><td>Board Type</td><td>成品类型</td><td>@@FINISH_SURFACE_ERP_@@</td> </tr>
  <tr><td>Profile</td><td>外型加工</td><td>@@PROFILE_ERP_@@</td><td>PPAP Level</td><td>PPAP 等级</td><td>@@PPAP_LEVEL_@@</td></tr>
  <tr><td>Pressing</td><td>压板工艺</td><td>@@LAMINATION_@@</td><td>Data Share</td><td>共用资料</td><td>@@DATA_SHARE_@@</td></tr>
  <tr><td>A/P</td><td>A/P</td><td>@@ERP_TECHNOLOGY_LEVEL_@@</td><td>Product Level</td><td>板等级</td><td>@@PRODUCT_LEVEL_@@</td></tr>
  <tr><td>Special Item</td><td>特别要求项</td><td>@@SPECIAL_ITEM_@@</td><td></td><td></td><td></td></tr>
  </table>
  <div  class="head_bar1">
	<p><center>Customer Part Specification  客户要求</center></p>
  </div>';
   // $maxcu= oci_parse($conn, $max_cu);
	//oci_execute($maxcu, OCI_DEFAULT);
    //$row_cu = oci_fetch_array($maxcu, OCI_RETURN_NULLS);
	
  	$SO_UNIT_ = $row['SO_UNIT_'];
	$FINISH_SURFACE_ERP_ = $row['FINISH_SURFACE_ERP_'];
	$PROFILE_ERP_ = $row['PROFILE_ERP_'];
	//if ($row_cu['MAX_CU']>=113.3 and $row['NUM_LAYERS']>=6)
	//    $LAMINATION_ = "PINLAM-R";
	//else
	   $LAMINATION_ = $row['LAMINATION_'];
	$ERP_TECHNOLOGY_LEVEL_ = $row['ERP_TECHNOLOGY_LEVEL_'];
	$INPUT_NUMBER_ = $row['INPUT_NUMBER_'];
	$PPAP_LEVEL_ = $row['PPAP_LEVEL__'];
	$DATA_SHARE_ = $row['DATA_SHARE_'];
    $PRODUCT_LEVEL_ = $row['PRODUCT_LEVEL_'];
	$SPECIAL_ITEM_= $row['SPECIAL_ITEM_'];
    $proc_info = str_replace("@@SO_UNIT_@@",$SO_UNIT_ ,$proc_info);
	$proc_info = str_replace("@@FINISH_SURFACE_ERP_@@",$FINISH_SURFACE_ERP_ ,$proc_info);
	$proc_info = str_replace("@@PROFILE_ERP_@@",$PROFILE_ERP_ ,$proc_info);
	$proc_info = str_replace("@@LAMINATION_@@",$LAMINATION_ ,$proc_info);
	$proc_info = str_replace("@@ERP_TECHNOLOGY_LEVEL_@@",$ERP_TECHNOLOGY_LEVEL_,$proc_info);
	$proc_info = str_replace("@@INPUT_NUMBER_@@",$INPUT_NUMBER_ ,$proc_info);
	$proc_info = str_replace("@@PPAP_LEVEL_@@",$PPAP_LEVEL_ ,$proc_info);
	$proc_info = str_replace("@@DATA_SHARE_@@",$DATA_SHARE_ ,$proc_info);
	$proc_info = str_replace("@@PRODUCT_LEVEL_@@",$PRODUCT_LEVEL_ ,$proc_info);
	$proc_info = str_replace("@@SPECIAL_ITEM_@@",$SPECIAL_ITEM_ ,$proc_info);
    echo $proc_info;
    $cust_r='<table class="tab" border="0" >
<tr><td>Drawing</td><td>图纸编号</td><td>@@DRAWING_REF_@@</td></tr>
<tr><td>Fabrication Spec</td><td>制作标准</td><td>@@FABRICATION_SPEC_@@</td></tr>
<tr><td>Material Resource</td><td>板料来源</td><td>@@Material_s@@</td></tr>
<tr><td>Inner LW/LS</td><td>内层线粗隙</td><td>@@ERP_INNER_MIN_LW_SPC_@@</td></tr>
<tr><td>Outer LW/LS</td><td>外层线粗隙</td><td>@@ERP_OUTER_MIN_LW_SPC_@@</td></tr>
<tr><td>Min Hole Size</td><td>最小孔径(机械)</td><td>@@ERP_HOLE_MIN_SIZE_TOL_@@</td></tr>
<tr><td>Min Hole Size</td><td>最小孔径(镭射)</td><td>@@ERP_HOLE_MIN_SIZE_TOL_LASER_@@</td></tr>
<tr><td>Others</td><td>其它特别项</td><td>@@ERP_PROD_SPEC_07_OTHER_@@</td></tr>
<tr><td>Technology</td><td>工艺等级</td><td>@@BOARD_TYPE_SPECIAL_@@</td></tr>
<tr><td>RoHS Compliance</td><td>RoHS要求</td><td>@@ROHS_COMPLIANCE_@@</td></tr>
<tr><td>Product Code</td><td>产品代码</td><td>@@PRODUCT_CODE_@@</td></tr>
<tr><td>CATEGORY</td><td>样板等级</td><td>@@CATEGORY_@@</td></tr>
<tr><td width=18%>Remark</td><td width=12%>备注</td><td>@@BASIC_JOB_DATA_REMARK_@@</td></tr>
</table>';
$DRAWING_REF_ = $row['DRAWING_REF_'];
$FABRICATION_SPEC_ = $row['FABRICATION_SPEC_'];
$ERP_INNER_MIN_LW_SPC_ = $row['ERP_INNER_MIN_LW_SPC_'];
$ERP_OUTER_MIN_LW_SPC_ = $row['ERP_OUTER_MIN_LW_SPC_'];
$ERP_HOLE_MIN_SIZE_TOL_ = $row['ERP_HOLE_MIN_SIZE_TOL_'];
$ERP_HOLE_MIN_SIZE_TOL_LASER_ = $row['ERP_HOLE_MIN_SIZE_TOL_LASER_'];
$ERP_PROD_SPEC_07_OTHER_ = $row['ERP_PROD_SPEC_07_OTHER_'];
$BOARD_TYPE_SPECIAL_ = $row['BOARD_TYPE_SPECIAL_'];
$ROHS_COMPLIANCE_ = $row['ROHS_COMPLIANCE_'];
$PRODUCT_CODE_ = $row['PRODUCT_CODE_'];
$BASIC_JOB_DATA_REMARK_ = $row['BASIC_JOB_DATA_REMARK_'];
$CUST_REQ_VENDOR_ = $row['CUST_REQ_VENDOR_'];
$CUST_REQ_FAMILY_ = $row['CUST_REQ_FAMILY_'];
$CUST_REQ_VENDOR_2 = $row['CUST_REQ_VENDOR_2_'];
$CUST_REQ_FAMILY_2 = $row['CUST_REQ_FAMILY_2_'];
$CUST_REQ_VENDOR_3 = $row['CUST_REQ_VENDOR_3_'];
$CUST_REQ_FAMILY_3 = $row['CUST_REQ_FAMILY_3_'];
$BASIC_MATERIAL_REMARK_=$row['BASIC_MATERIAL_REMARK_'];
$CI_IMP_REMARK_=$row['CI_IMP_REMARK_'];
$CORNER1_=$row['CORNER1_'];
$CORNER2_=$row['CORNER2_'];
$CORNER3_=$row['CORNER3_'];
$CORNER4_=$row['CORNER4_'];
$DRILL_UNIT_=$row['DRILL_UNIT_'];
$ERP_UNIT_PER_ARRAY_=$row['ERP_UNIT_PER_ARRAY_'];
$CATEGORY_=$row['CATEGORY_'];
if ($cust_code==="HWT") {
      $Material_s="华为指定";
}else{
	if ($CUST_REQ_VENDOR_==="Unknown"){
         $Material_s="UL APPROVAL";
	}else{
		$Material_s=$CUST_REQ_VENDOR_;
		if ($CUST_REQ_FAMILY_!="Mixed" ) $Material_s.=",".$CUST_REQ_FAMILY_;
		if ($CUST_REQ_VENDOR_2!="Unknown" ) $Material_s.=",".$CUST_REQ_VENDOR_2;
		if ($CUST_REQ_FAMILY_2!="Mixed" ) $Material_s.=",".$CUST_REQ_FAMILY_2;
		if ($CUST_REQ_VENDOR_3!="Unknown" ) $Material_s.=",".$CUST_REQ_VENDOR_3;
		if ($CUST_REQ_FAMILY_3!="Mixed" ) $Material_s.=",".$CUST_REQ_FAMILY_3;
		};
};

$cust_r = str_replace("@@DRAWING_REF_@@",$DRAWING_REF_ ,  $cust_r);
$cust_r = str_replace("@@FABRICATION_SPEC_@@",$FABRICATION_SPEC_,  $cust_r);
$cust_r = str_replace("@@Material_s@@",$Material_s ,  $cust_r);
$cust_r = str_replace("@@ERP_INNER_MIN_LW_SPC_@@",$ERP_INNER_MIN_LW_SPC_ ,  $cust_r);
$cust_r = str_replace("@@ERP_OUTER_MIN_LW_SPC_@@",$ERP_OUTER_MIN_LW_SPC_ ,  $cust_r);
$cust_r = str_replace("@@ERP_HOLE_MIN_SIZE_TOL_@@",$ERP_HOLE_MIN_SIZE_TOL_ ,  $cust_r);
$cust_r = str_replace("@@ERP_HOLE_MIN_SIZE_TOL_LASER_@@",$ERP_HOLE_MIN_SIZE_TOL_LASER_ ,  $cust_r);
$cust_r = str_replace("@@ERP_PROD_SPEC_07_OTHER_@@",$ERP_PROD_SPEC_07_OTHER_ ,  $cust_r);
$cust_r = str_replace("@@BOARD_TYPE_SPECIAL_@@",$BOARD_TYPE_SPECIAL_,  $cust_r);
$cust_r = str_replace("@@ROHS_COMPLIANCE_@@",$ROHS_COMPLIANCE_,  $cust_r);
$cust_r = str_replace("@@PRODUCT_CODE_@@",$PRODUCT_CODE_ ,  $cust_r);
$cust_r = str_replace("@@BASIC_JOB_DATA_REMARK_@@",$BASIC_JOB_DATA_REMARK_ ,  $cust_r);
$cust_r = str_replace("@@CATEGORY_@@",$CATEGORY_ ,  $cust_r);

echo $cust_r;
$stid = oci_parse($conn, $ci_note);
oci_execute($stid, OCI_DEFAULT);
echo"<div class='ci_note'> ";
echo "<p><b><font-size=150%>出货成品比较:</font></b></p>";
echo "<div class='ci_note_1'>";
while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
   echo trim($row[0])."\n";
   echo trim($row[1]);
}
echo "</div> </div>";
    $rstrav = oci_parse($conn, $trav_gz);
	oci_execute($rstrav, OCI_DEFAULT);
    $process_name='';
   while ($row = oci_fetch_array($rstrav, OCI_RETURN_NULLS)){
         	/* $item_id=$row['ITEM_ID'];
			 $revision_id=$row['REVISION_ID'];
			 $squential_index=$row['SQUENTIAL_INDEX']; */
			 
		 if ($process_name!=$row['PROCESS_NAME']){
			 $process_name=$row['PROCESS_NAME'];
			 $item_id=$row['ITEM_ID'];
			 $revision_id=$row['REVISION_ID'];
		     echo "<div class='process_bar'><b>Process Flow Chart 工艺流程&nbsp;&nbsp-&nbsp;&nbsp;&nbsp;$process_name</b></div>" ;
		 } 
		 echo "<div class='trav_code'><b>step:".$row['TRAVELER_ORDERING_INDEX']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['DESCRIPTION']."</B> <B style='float:right; padding-right:10px;'>".$row['OPERATION_CODE']."</B> </div>";
		// echo "<div class='trav_code1'><b>".$row['OPERATION_CODE']."</b></div>";
		 $index=$row['SEQUENTIAL_INDEX'];
		 $trav_note1='';
		 $trav_note1=$trav_note;
		 $trav_note1 = str_replace("@@item_id@@",$item_id ,  $trav_note1);
		 $trav_note1 = str_replace("@@revision_id@@",$revision_id ,  $trav_note1);
		 $trav_note1 = str_replace("@@SEQUENTIAL_INDEX@@",$row['SEQUENTIAL_INDEX'] ,  $trav_note1);
	    // echo "<div>". $trav_note1."</div>";
         $t_note1 = oci_parse($conn, $trav_note1);
	     oci_execute($t_note1, OCI_DEFAULT);
		 echo "<div class='trav_note'>";
		 while ($row = oci_fetch_array($t_note1, OCI_RETURN_NULLS)){
		 echo"<div >".$row['NOTE_STRING']."</div>";
		}
		echo "</div>";
        $trav_attr1='';
	    $trav_attr1=$trav_attr;
	    $trav_attr1 = str_replace("@@item_id@@",$item_id ,  $trav_attr1);
	    $trav_attr1 = str_replace("@@revision_id@@",$revision_id ,  $trav_attr1);
	    $trav_attr1 = str_replace("@@SEQUENTIAL_INDEX@@",$index , $trav_attr1);
	   // echo "<div>". $trav_attr1."</div>";
	    $description='';
		$value_as_string='';
	    $t_attr = oci_parse($conn, $trav_attr1);
	    oci_execute($t_attr, OCI_DEFAULT);
		 echo "<div class='trav_attr'>";
		/* while ($row = oci_fetch_array($t_attr, OCI_RETURN_NULLS)){
		 $description.="<div >".$row['DESCRIPTION']."</div>";
		 $value_as_string.="<div >".$row['VALUE_AS_STRING']."</div>";
		}*/
		while ($row = oci_fetch_array($t_attr, OCI_RETURN_NULLS)){
			$description.='<p>'. $row['DESCRIPTION'].'</p>';
			$value_as_string.='<p>'.$row['VALUE_AS_STRING']."</p>";
		   }
	    if ($description!=''){
		$description="<div class='attr_des'><div style='display:inline-block;width:60%'>".$description."</div><div style='display:inline-block;padding-left:20px;'>".$value_as_string."</div></div>";
		//$value_as_string="<div class='attr_value'>".$value_as_string."</div>";
		echo $description;
		//echo $value_as_string;
		}
		echo "</div>";
   }
   	$bar='<div  class="head_bar">
		<p><center>Part Layup 叠板图</center></p>
	</div>';
	echo $bar;
   //print ('<img width="100%" src="data:image/pjpeg;base64,'.base64_encode($img).'"/>');
   $stackup_img="<img width='100%' style='margin-top:5px;margin_left:10px;' src=\"inplan/gz/getpic_t.php?root_id=$root_id&pic_type=layup\" />";
   echo "<div style='margin-left:20px'>";
   print  $stackup_img;
   echo "</div>";
  $remark='<table class="tab" border=0 >
  <tr><td width =15% >Remark 备注：</td><td>@@BASIC_MATERIAL_REMARK_@@</td></tr>
  </table>';
  $remark = str_replace("@@BASIC_MATERIAL_REMARK_@@",$BASIC_MATERIAL_REMARK_ ,$remark);
  echo $remark;
//切板角
if ($CORNER1_!='' or $CORNER2_!='' or $CORNER3_!='' or $CORNER4_ !=''){ 
  $C1='';
  $C2='';
  $C3='';
  $C4='';
if ($CORNER1_ !='')
{
    $C1=$CORNER1_;
    if ($CORNER2_!='')
    {
        $C2= $CORNER2_;
        if ($CORNER3_!='')
        {   
            $C3= $CORNER3_;
            if ($CORNER4_!='') $C4= $CORNER4_;
        }
        else if ($CORNER4_ !='') $C3= $CORNER4_;
    }
    else if ($CORNER4_!='') 
    {
        $C2= $CORNER4_;
        if ($CORNER3_!='') $C3=$CORNER3_;
    }
}
else if ($CORNER4_!='')
{
    $C1= $CORNER4_;
    if ($CORNER3_!='')
    {
        $C2= $CORNER3_;
        if ($CORNER2_ !='')  $C3= $CORNER2_;
    }
}

$remark1='';
$remark2='';
$remark3='';
if  ($C1==='' or ($C1 === $CORNER4_)) $remark1='';
else if (substr($C1,(strpos($C1,"(")+1), (strpos($C1,"/",strpos($C1,"-"))-strpos ($C1,"(") -1)) != substr ($C1,strpos ($C1,"/")+1, Strpos ($C1,")") -1))  $remark1='较薄铜面向上切左下角';
else $remark1='';

if ($C2 ==='') $remark2='';
else if ($C2===$CORNER4_) $remark2='';
else if ($C2 === $CORNER3_) $remark2='';
else if (substr($C2,(strpos ($C2,"(")+1), ( strpos($C2,"/",strpos($C2,"-"))-strpos ($C2,"(") -1)) <> substr ($C2,(strpos ($C2,"/")+1), (strpos ($C2,")") -1))) $remark2="较薄铜面向上切三个角";
($job_name!="A180485A-300")? $remark3='':$remark3="较薄铜面向上,切左下角和右上角";
$C_P1='';
$C_P2='';
$C_P3='';
$C_P4='';


   if ($C1!='') $C_P1='<img  style="height:120px;" src="images/CuttingConer_DiffCopperTwo.jpg" />';
   if ($C2!='') $C_P2='<img  style="height:120px;" src="images/CuttingConer_DiffCopperThree.jpg" />';
   if ($C3!='' and $job_name=="A180485A-300") $C_P3='<img  style="height:120px;" src="images/CuttingConer_DiffCopperTwo1.jpg" />';
   if ($C3!='' and $job_name!="A180485A-300") $C_P3='<img  style="height:120px;" src="images/CuttingConer_DiffCoreTwo.jpg" />';
   if ($C4!='') $C_P4='<img  style="height:120px;" src="CuttingConer_DiffCoreThree.jpg" />';
	$bar='<div  class="head_bar">
		<p><center>Inner Layer Cutting Corner 切角图</center></p>
	</div>';
	echo $bar;
  echo "<div class='conner'>";
  $remark='<table class="tab" style="border:0;" >
 <tr><td width =25% align="center" style="border:0;"><b>'.$C1.'</b></td><td width =25% align="center" style="border:0;"><b>'.$C2.'</b></td><td width =25% align="center" style="border:0;"><b>'.$C3.'</b></td><td  width =25% align="center" style="border:0;"><b>'.$C4.'</b></td></tr>
   <tr>
   <td align="center" style="height:100px;" style="border:0;">@@P1@@</td>
   <td align="center" style="height:100px;" style="border:0;">@@P2@@</td>
   <td align="center" style="height:100px;" style="border:0;">@@P3@@</td>
   <td align="center" style="height:100px;" style="border:0;">@@P4@@</td>
   </tr>
     <tr><td width =25% align="center" style="border:0;">'.$remark1.'</td><td width =25% align="center" style="border:0;">'.$remark2.'</td><td width =25% align="center" style="border:0;">'.$remark3.'</td><td  width =25% align="center" style="border:0;"></td></tr>
  </table>';
 $remark = str_replace("@@P1@@",$C_P1 ,$remark);
 $remark = str_replace("@@P2@@",$C_P2 ,$remark);
 $remark = str_replace("@@P3@@",$C_P3 ,$remark);
 $remark = str_replace("@@P4@@",$C_P4 ,$remark);
  echo $remark;
  echo "</div>";
}
//介电层厚度
$thick="select it.root_id, th.calculated_thickness, th.calculated_thickness_tol_minus, th.calculated_thickness_tol_plus, th.first_index, th.last_index, th.target_thickness, th.target_thickness_tol_minus, th.TARGET_THICKNESS_TOL_PLUS, th.whole_stackup 
from items it , thickness_constraint th
where it.item_type=9 
and it.item_id= th.item_id
and it.last_checked_in_rev=th.revision_id 
and it.root_id=$root_id";
$thk = oci_parse($conn, $thick);
oci_execute($thk, OCI_DEFAULT);
if ($row = oci_fetch_array($thk, OCI_RETURN_NULLS)) {
    $bar='<div  class="head_bar">
		<p><center>Thickness Constraints 介电层厚要求</center></p>
	</div>';
	echo $bar;
	$thk_press="压板后板厚范围:".number_format(($row['TARGET_THICKNESS']-$row['TARGET_THICKNESS_TOL_MINUS']),1)."------".number_format(($row['TARGET_THICKNESS']+$row['TARGET_THICKNESS_TOL_PLUS']),1)."mil";
	$thk_t='<table class="tab">
	  <tr><th><b><center>FirstLayer</center></b></th><th><b><center>LastLay</b></center></th><th><b><center>Target Thickness 要求厚度 (mil)</b></center></th><th><b><center>Tolerance + 正公差 (mil)</b></center></th><th><b><center>Tolerance - 负公差 (mil)</b></center></th></tr>';	
   while ($row = oci_fetch_array($thk, OCI_RETURN_NULLS)){
       $thk_t.='<tr><td><Center>L'.number_format($row['FIRST_INDEX'],0).'</Center></td><td><Center>L'.number_format($row['LAST_INDEX'],0).'</Center></td><td><Center>'.number_format($row['TARGET_THICKNESS'],2).'</Center></td><td><Center>'.number_format($row['TARGET_THICKNESS_TOL_PLUS'],2).'</Center></td><td><Center>'.number_format($row['TARGET_THICKNESS_TOL_MINUS'],2).'</Center></td></tr>';
	   }
	  $thk_t.='<tr><td colspan="5"><b>'.$thk_press.'</b></td></tr>';
     $thk_t.='</table>';
	 echo $thk_t;
	// echo $thk_press;

}
	//阻抗
$ic_para="select  ic.artwork_trace_width
       , ic.model_name
	   ,ic.solver_name
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.trace_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as layer_index
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.top_model_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as top_layer_index
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.bottom_model_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as bot_layer_index
       , ic.original_trace_width
       , ic.orig_trace_width_tol_plus
       ,ic.orig_trace_width_tol_minus
       , ic.calculated_trace_width
	   , ic.MFG_LINE_WIDTH_TOL_PLUS
	   ,ic.MFG_LINE_WIDTH_TOL_MINUS
        ,ic.artwork_trace_width
       , ic.customer_required_impedance
       , ic.cust_required_imped_tol_plus
	   ,ic.CUST_REQUIRED_IMPED_TOL_MINUS
       ,ic.impedance_comment
       ,(select icp.double_value from impedance_constraint_parameter icp 
       where lower(icp.name)='original_s' and icp.item_id=ic.item_id 
       and icp.revision_id=ic.revision_id 
       and icp.constraint_sequential_index=ic.sequential_index) as value1
from items root , impedance_constraint ic 
where item_type=9
      and root.item_id= ic.item_id
      and  root.last_checked_in_rev= ic.revision_id
      and root.root_id=$root_id
  order by ic.trace_layer_item_id ,ic.sequential_index";
	$ic_t='<table class="tab">
	  <tr><th><b><center>阻抗模块</center></b></th><th><b><center>层号</b></center></th><th><b><center>阻抗类型</b></center></th><th><b><center>参考层</b></center></th><th><b><center>CAD线宽/线隙</b></center></th><th><b><center>成品线宽</b></center></th><th><b><center>生产菲林</b></center></th><th><b><center>阻抗值</b></center></th><th><b><center>阻抗值</b></center></th></tr>';
   $has_ic=0;
   $ic_t.='<tr>
		<td> <center>(IMP Module)</center></td>
		<td><center>(Layer)</center></td>
		<td><center>单线/差分</center></td>
		<td><center>(Ref. Layer)</center></td>
		<td><center>(Mil)</center></td>
		<td><center>(Mil)</center></td>
		<td><center>(Mil)</center></td>
		<td><center><div>(成品 Finished Goods)</div><div>OHMS</div></center></td>
		<td><center><div>(蚀刻后 After Etch)</div><div>OHMS</div></center></td>
	    </tr>';
		$ic= oci_parse($conn, $ic_para);
        oci_execute($ic, OCI_DEFAULT);
       while ($row = oci_fetch_array($ic, OCI_RETURN_NULLS)){	     
          $mode=$row['MODEL_NAME'];
		  if ($row['SOLVER_NAME']=='InSolver'){
		      $ic_pic=' <img  src="images/InSolver/small_' .$mode .'.png "/>';
		  }else{
		      $ic_pic=' <img  src="images/SI-8000/small_' .$mode .'.png "/>';
		  }
		  
		  $layer_id="L".number_format($row['LAYER_INDEX'],0);
		  if ($row['VALUE1']) $ic_type='差分';
          else if ( $row['MODEL_NAME']==="broadside_coupled_stripline_2s") $ic_type='异面差分';
		  else $ic_type='单线';
	   if ( $row['MODEL_NAME']=== "embedded_microstrip_1b1a" or $row['MODEL_NAME']=== "edge_coupled_embedded_microstrip_1b1a" or $row['MODEL_NAME']=== "embedded_microstrip_2b1a"
	   or $row['MODEL_NAME']=== "embedded_microstrip_1b2a"  or $row['MODEL_NAME']=== "embedded_microstrip_1e1b2a") $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0);
	   else{
		   if (is_null($row['TOP_LAYER_INDEX']))
			   $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0);
		   else
              $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0)."/".$ref_layer="L".number_format($row['TOP_LAYER_INDEX'],0);
	   }
	   if($row['VALUE1'])
		   $space=number_format($row['ORIGINAL_TRACE_WIDTH'],2)."/".number_format($row['VALUE1'],1);
	   else
		   $space=number_format($row['ORIGINAL_TRACE_WIDTH'],2);

	       $finish_line_width=number_format($row['CALCULATED_TRACE_WIDTH'],2)."+".number_format($row['MFG_LINE_WIDTH_TOL_PLUS'],2)."/-".number_format($row['MFG_LINE_WIDTH_TOL_MINUS'],2);
		   $art_work=number_format($row['ARTWORK_TRACE_WIDTH'],2);
		   $f_ic=number_format($row['CUSTOMER_REQUIRED_IMPEDANCE'],2)."+".number_format($row['CUST_REQUIRED_IMPED_TOL_PLUS'],2)."/-".number_format($row['CUST_REQUIRED_IMPED_TOL_MINUS'],2);
		   $ic_comm=$row['IMPEDANCE_COMMENT'];
      $ic_t.="
	   <tr>
	 	<td style='word-break:break-all;word-wrap:break-word ' width='110px'>$ic_pic.$mode</td>
		<td><center>$layer_id</center></td>
		<td><center>$ic_type</center></td>
		<td><center>$ref_layer</center></td>
		<td><center>$space</center></td>
		<td><center>$finish_line_width</center></td>
		<td><center>$art_work</center></td>
		<td><center>$f_ic</center></td>
		<td><center>$ic_comm</center></td>
	    </tr>";
       $has_ic=1 ;
	  }
	 $bar='<div  class="head_bar">
		<p><center>阻抗控制要求 ( Impedance control required)</center></p>
	    </div>';
	if ($has_ic===1){
	 echo $bar;
     $ic_t.="<tr><td colspan='4'><b>阻抗控制备注(Impedance control Remark):</b></td><td colspan='5'>$CI_IMP_REMARK_</td></tr>";
	 echo $ic_t.'</table>';
	}
	  //********************************************阻抗
//钻孔表
 	$bar='<div  class="head_bar">
		<p><center>Drilling Information 钻孔表</center></p>
	</div>';
	echo $bar;
 $drill_t="select  distinct  da.*,dh.*
from rpt_job_drill_bit_list da, drill_hole_da dh 
where root_id=$root_id and da.drill_program_item_id= dh.item_id and da.drill_program_revision_id= dh.revision_id 
and da.drill_hole_sequential_index= dh.sequential_index
and da.DRILL_PROGRAM_DRILL_TECHNOLOGY <> 2 
and da.DRILL_PROGRAM_DRILL_TECHNOLOGY<>1
ORDER by da.drill_program_name, da.drill_hole_name,da.pilot_number";
$DRILL_UNIT_==0?$unit="Mil":$unit="MM";
$tc = oci_parse($conn, $drill_t);
oci_execute($tc, OCI_DEFAULT);
$dp_name='';

while ($row = oci_fetch_array($tc, OCI_RETURN_NULLS)){
       if ($dp_name!=$row['DRILL_PROGRAM_NAME']	){
		     if ($dp_name!='') echo $nc.="</table>";
			 $row['DRILL_PROGRAM_DRILL_TECHNOLOGY']===2?$size_unit='Mil':$size_unit='MM';
	         $dp_name=$row['DRILL_PROGRAM_NAME'];
		     $nc="<table class='tab'>
             <tr><td colspan='8'><B>Drill Progrm Name:&nbsp;&nbsp;&nbsp;$dp_name</B> </td></tr>
             <tr>
	            <td rowspan='2' width ='30'><center><b>Tool</b></center></td>
	            <td rowspan='2' width='100'><center><b><div><B>Finished Hole</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2'><center><b><div><b>Slot Length</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2' width='50'><center><b><div><b>Drill Size</b></div><div><b>$size_unit</b></div></b></center></td>
                <td colspan='2' ><center><b>Count</b></center></td>
            	<td rowspan='2' width='60'><center><b>Drill Type</b></center></td>
                <td rowspan='2'><center><b>Remark</b></center></td>
           </tr>
          <tr>
            	<td><center><b>Unit</b></center></td>
	           <td><center><b>Up-Panel</b></center></td>
          </tr>";
           
			}
	   
	  $dh_name =$row['DRILL_HOLE_NAME'];
	  if ($row[IS_FINISH_SIZE_HIDE_] ===1) $finish_hole="/";
	  else{
		if (($row['PRESS_FIT']===1 or $row['UNIT_MM_']===1) and $cust_code==="HWT")
                  $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3).'MM';
	         else{
				 if ($unit==='MM')
				     $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3);
			     else
					 $finish_hole=number_format($row['FINISHED_SIZE'],2).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS'],2);
			 }
	}
	if ($row[IS_FINISH_SIZE_HIDE_] ===1) $finish_length="/";
	else if (!$row['FINISHED_LENGTH']) $finish_length='';
	else{
		 if ($unit==='MM')
				     $finish_length=number_format($row['FINISHED_LENGTH']*0.0254,3).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS']*0.0254,3);
			     else
					 $finish_length=number_format($row['FINISHED_LENGTH'],2).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS'],2);

	}
	     if ($row['ROUTDRILL_']) $bis_size='';
		 else 
			$row['DRILL_PROGRAM_DRILL_TECHNOLOGY']===2?$bit_size=number_format($row['BIT_SIZE'],1):$bit_size=number_format($row['BIT_SIZE']*0.0254,3);
		if ($SO_UNIT_==='UNIT'and $ERP_UNIT_PER_ARRAY_==1 )
		   {$unit_factor=1;
	       $panel_factor=0;
           }
      else if ($SO_UNIT_==='UNIT' and $ERP_UNIT_PER_ARRAY_>1)
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
       else if ($SO_UNIT_==='UP-PANEL' and $ERP_UNIT_PER_ARRAY_==1)
          {$unit_factor=0;
	       $panel_factor=1;
           }
	  else 
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
        if ($row['PCB_COUNT']===0) $unit_count="/";
		else number_format($row['PCB_COUNT']*$unit_factor,2)==0?$unit_count="":$unit_count=number_format($row['PCB_COUNT']*$unit_factor,2);
        if ($row['PCB_COUNT']===0) $panel_count="/";
		else number_format($row['PCB_COUNT']*$panel_factor,2)==0?$panel_count="":$panel_count=number_format($row['PCB_COUNT']*$panel_factor,2); 
        $dh_type=$row['DRILL_HOLE_TYPE_T'];
		$remark=$row['MI_REMARK_'];
	$nc.="<tr>
       <td ><center>$dh_name</center></td>
       <td><center>$finish_hole</center></td>
       <td><center>$finish_length</center></td>
       <td><center>$bit_size</center></td>
       <td><center>$unit_count<i></i></center></td>
       <td><center>$panel_count</center></td>
       <td><center>$dh_type</center></td>
       <td><center>$remark</center></td>
      </tr>";

}
echo $nc.="</table>";
$nc='';

//背钻孔表
 	$bar='<div  class="head_bar">
		<p><center>Back Drilling Information 钻孔表</center></p>
	</div>';
	
 $drill_t="select  distinct  da.*,dh.*
from rpt_job_drill_bit_list da, drill_hole_da dh 
where root_id=$root_id and da.drill_program_item_id= dh.item_id and da.drill_program_revision_id= dh.revision_id 
and da.drill_hole_sequential_index= dh.sequential_index
and dh.backdrill_=1
ORDER by da.drill_program_start_index, da.drill_hole_name,da.pilot_number";
$DRILL_UNIT_==0?$unit="Mil":$unit="MM";
$tc = oci_parse($conn, $drill_t);
oci_execute($tc, OCI_DEFAULT);
$dp_name='';
$b_drill=0;
$b_drill1=0;
while ($row = oci_fetch_array($tc, OCI_RETURN_NULLS)){
	   $b_drill=1;
       if ($dp_name!=$row['DRILL_PROGRAM_START_INDEX']){
		     if ($dp_name!='')
		     {   echo $bar;
				 echo $nc.="</table>";
				 $b_drill1=1;
		     }
			 $row['DRILL_PROGRAM_DRILL_TECHNOLOGY']===2?$size_unit='Mil':$size_unit='MM';
	         $dp_name=$row['DRILL_PROGRAM_START_INDEX'];
		     $nc="<table class='tab'>
             <tr><td colspan='15'><B>Drill Progrm Name:&nbsp;&nbsp;&nbsp;从 L$dp_name 层开始 </B> </td></tr>
             <tr>
	            <td rowspan='2' ><center><b>Tool</b></center></td>
	            <td rowspan='2' ><center><b><div><B>Finished Hole</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2'><center><b><div><b>Slot Length</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2'><center><b><div><b>Drill Size</b></div><div><b>$size_unit</b></div></b></center></td>
                <td colspan='2' ><center><b>Count</b></center></td>
            	<td rowspan='2' ><center><b>Drill Type</b></center></td>
				<td rowspan='2'><center><b><div>客户要求</div><div>MCL</div></b></center></td>
				<td rowspan='2'><center><b><div>客户要求</div><div>MNCL</div></b></center></td>
				<td rowspan='2'><center><b><div>客户要求</div><div>Depth</div><div>(Mil)</div></b></center></td>
				<td rowspan='2'><center>客户特别要求</center></td>
				<td rowspan='2'><center><b><div>参考</div><div>非生产用</div><div>MCL</div></b></center></td>
				<td rowspan='2'><center><b><div>参考</div><div>非生产用</div><div>MNCL</div></b></center></td>
				<td rowspan='2'><center><b><div>参考</div><div>非生产用</div><div>Depth(Mil)</div></b></center></td>
                <td rowspan='2'><center><b>Remark</b></center></td>
           </tr>
          <tr>
               <td><center><b>Unit</b></center></td>
	           <td><center><b>Up-Panel</b></center></td>
          </tr>";
           
			}
	  $dh_name =$row['DRILL_HOLE_NAME'];
	  if ($row[IS_FINISH_SIZE_HIDE_] ===1) $finish_hole="/";
	  else{
		if (($row['PRESS_FIT']===1 or $row['UNIT_MM_']===1) and $cust_code==="HWT")
                  $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3).'MM';
	         else{
				 if ($unit==='MM')
				     $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3);
			     else
					 $finish_hole=number_format($row['FINISHED_SIZE'],2).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS'],2);
			 }
	}
	if ($row[IS_FINISH_SIZE_HIDE_] ===1) $finish_length="/";
	else if (!$row['FINISHED_LENGTH']) $finish_length='';
	else{
		 if ($unit==='MM')
				     $finish_length=number_format($row['FINISHED_LENGTH']*0.0254,3).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS']*0.0254,3);
			     else
					 $finish_length=number_format($row['FINISHED_LENGTH'],2).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS'],2);

	}
	     if ($row['ROUTDRILL_']) $bis_size='';
		 else 
			$row['DRILL_PROGRAM_DRILL_TECHNOLOGY']===2?$bit_size=number_format($row['BIT_SIZE'],1):$bit_size=number_format($row['BIT_SIZE']*0.0254,3);
		if ($SO_UNIT_==='UNIT'and $ERP_UNIT_PER_ARRAY_==1 )
		   {$unit_factor=1;
	       $panel_factor=0;
           }
      else if ($SO_UNIT_==='UNIT' and $ERP_UNIT_PER_ARRAY_>1)
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
       else if ($SO_UNIT_==='UP-PANEL' and $ERP_UNIT_PER_ARRAY_==1)
          {$unit_factor=0;
	       $panel_factor=1;
           }
	  else 
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
        if ($row['PCB_COUNT']===0) $unit_count="/";
		else number_format($row['PCB_COUNT']*$unit_factor,2)==0?$unit_count="":$unit_count=number_format($row['PCB_COUNT']*$unit_factor,2);
        if ($row['PCB_COUNT']===0) $panel_count="/";
		else number_format($row['PCB_COUNT']*$panel_factor,2)==0?$panel_count="":$panel_count=number_format($row['PCB_COUNT']*$panel_factor,2); 
	    $dh_type=$row['DRILL_HOLE_TYPE_T'];
		$row['MUST_CUT_LAYER_CUST_']==0?$c_mcl='N/A':$c_mcl='L'+$row['MUST_CUT_LAYER_CUST_'];
        $row['MUST_NOT_CUT_LAYER_']==0?$c_mncl='N/A':$c_mncl='L'+$row['MUST_NOT_CUT_LAYER_'];
		if ($row['MUST_CUT_LAYER_CUST_']!=0 and $row['MUST_NOT_CUT_LAYER_']!=0)
			$c_depth='N/A';
		else if ($row['MUST_CUT_LAYER_CUST_']==0 and $row['MUST_NOT_CUT_LAYER_']!=0)
    	{        
		if ($row['DRILL_DEPTH_CUST_'])
			$c_depth='N/A';
		else
			if ($row['DRILL_DEPTH_TOL_PLUS_']==0 and $row['DRILL_DEPTH_TOL_MINUS_']==0) $c_depth=$row['DRILL_DEPTH_CUST_'];
		    else if ($row['DRILL_DEPTH_TOL_PLUS_']!=0 and $row['DRILL_DEPTH_TOL_MINUS_']==0) $c_depth=$row['DRILL_DEPTH_CUST_'].'+'.$row['DRILL_DEPTH_TOL_PLUS_'];
		    else if ($row['DRILL_DEPTH_TOL_PLUS_']==0 and $row['DRILL_DEPTH_TOL_MINUS_']!=0) $c_depth=$row['DRILL_DEPTH_CUST_'].'-'.$row['DRILL_DEPTH_TOL_MINUS_'];
			else if ($row['DRILL_DEPTH_TOL_PLUS_']!=0 and $row['DRILL_DEPTH_TOL_MINUS_']!=0 and $row['DRILL_DEPTH_TOL_PLUS_']==$row['DRILL_DEPTH_TOL_MINUS_']) $c_depth=$row['DRILL_DEPTH_CUST_'].'±'.$row['DRILL_DEPTH_TOL_MINUS_'];
			else  $c_depth=$row['DRILL_DEPTH_CUST_'].'+'.$row['DRILL_DEPTH_TOL_PLUS_'].'/-'.$row['DRILL_DEPTH_TOL_MINUS_'];
         }
       else 
		    if ($row['DRILL_DEPTH_TOL_PLUS_']==0 and $row['DRILL_DEPTH_TOL_MINUS_']==0) $c_depth=$row['DRILL_DEPTH_CUST_'];
	        else if ($row['DRILL_DEPTH_TOL_PLUS_']!=0 and $row['DRILL_DEPTH_TOL_MINUS_']==0) $c_depth=$row['DRILL_DEPTH_CUST_'].'+'.$row['DRILL_DEPTH_TOL_PLUS_'];
		    else if ($row['DRILL_DEPTH_TOL_PLUS_']==0 and $row['DRILL_DEPTH_TOL_MINUS_']!=0) $c_depth=$row['DRILL_DEPTH_CUST_'].'-'.$row['DRILL_DEPTH_TOL_MINUS_'];
			else if ($row['DRILL_DEPTH_TOL_PLUS_']!=0 and $row['DRILL_DEPTH_TOL_MINUS_']!=0 and $row['DRILL_DEPTH_TOL_PLUS_']==$row['DRILL_DEPTH_TOL_MINUS_']) $c_depth=$row['DRILL_DEPTH_CUST_'].'±'.$row['DRILL_DEPTH_TOL_MINUS_'];
			else  $c_depth=$row['DRILL_DEPTH_CUST_'].'+'.$row['DRILL_DEPTH_TOL_PLUS_'].'/-'.$row['DRILL_DEPTH_TOL_MINUS_'];
     $c_depth=number_format($c_depth,2);
	
     if ($row['MUST_CUT_LAYER_CUST_']!=0 and $row['MUST_NOT_CUT_LAYER_']!=0) $ref_mcl='N/A';
	 else  $ref_mcl='L'.$row['DRILL_HOLE_END_INDEX'];
     
     if ($row['MUST_CUT_LAYER_CUST_']!=0 and $row['MUST_NOT_CUT_LAYER_']!=0 ) $ref_mncl='N/A';
	 else if ($row['MUST_CUT_LAYER_CUST_']==0 and $row['MUST_NOT_CUT_LAYER_']!=0 ) $ref_mncl='N/A';
	 else $ref_mncl='L'.$row['MUST_NOT_CUT_LAYER_REF_'];
	if ($row['MUST_CUT_LAYER_CUST_']==0 and $row['MUST_NOT_CUT_LAYER']==0) $ref_depth='N/A';
	else if ($row['DRILL_DEPTH_CUST_']!=0 )$ref_depth='N/A';
	else $ref_depth=number_format($row['DRILL_DEPTH_REF_'],2);
	
	 $c_special=$row['REMARK_CUST_'];
	 $remark=$row['MI_REMARK_'];
	$nc.="<tr>
       <td ><center>$dh_name</center></td>
       <td><center>$finish_hole</center></td>
       <td><center>$finish_length</center></td>
       <td><center>$bit_size</center></td>
       <td><center>$unit_count<i></i></center></td>
       <td><center>$panel_count</center></td>
       <td><center>$dh_type</center></td>
	   <td><center>$c_mcl</center></td>
	   <td><center>$c_mncl</center></td>
	   <td><center>$c_depth</center></td>
	   <td><center>$c_special</center></td>
	   <td><center>$ref_mcl</center></td>
	   <td><center>$ref_mncl</center></td>
	   <td><center>$ref_depth</center></td>
       <td><center>$remark</center></td>
      </tr>";

}
if ($b_drill==1 and $b_drill1==0) echo $bar;
if ($b_drill==1) echo $nc.="</table>";//背钻孔
$nc='';
//镭射
 	$bar='<div  class="head_bar">
		<p><center>Laser Drilling Information 镭射钻孔表</center></p>
	</div>';
	
 $drill_t="select  distinct  da.*,dh.*
from rpt_job_drill_bit_list da, drill_hole_da dh 
where root_id=$root_id and da.drill_program_item_id= dh.item_id and da.drill_program_revision_id= dh.revision_id 
and da.drill_hole_sequential_index= dh.sequential_index
and da.DRILL_PROGRAM_DRILL_TECHNOLOGY =2
ORDER by da.drill_program_start_index, da.drill_hole_name,da.pilot_number";
$DRILL_UNIT_==0?$unit="Mil":$unit="MM";
$tc = oci_parse($conn, $drill_t);
oci_execute($tc, OCI_DEFAULT);
$dp_name='';
$l_drill=0;
$l_drill1=0;
while ($row = oci_fetch_array($tc, OCI_RETURN_NULLS)){
       $l_drill=1;
	   if ($dp_name!=$row['DRILL_PROGRAM_START_INDEX']	){
		     if ($dp_name!='')
		   {     echo $bar;
				 echo $nc.="</table>";
				 $l_drill1=1;
			 }
			 $row['DRILL_PROGRAM_DRILL_TECHNOLOGY']==2?$size_unit='Mil':$size_unit='MM';
	         $dp_name=$row['DRILL_PROGRAM_START_INDEX'];
		     $nc="<table class='tab'>
             <tr><td colspan='8'><B>Drill Progrm Name:&nbsp;&nbsp;&nbsp;Laser  Drill From L$dp_name</B> </td></tr>
             <tr>
	            <td rowspan='2' width ='30'><center><b>Tool</b></center></td>
	            <td rowspan='2' width='100'><center><b><div><B>Finished Hole</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2'><center><b><div><b>Slot Length</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2' width='50'><center><b><div><b>Drill Size</b></div><div><b>$size_unit</b></div></b></center></td>
                <td colspan='2' ><center><b>Count</b></center></td>
            	<td rowspan='2' width='60'><center><b>Drill Type</b></center></td>
                <td rowspan='2'><center><b>Remark</b></center></td>
           </tr>
          <tr>
            	<td><center><b>Unit</b></center></td>
	           <td><center><b>Up-Panel</b></center></td>
          </tr>";
           
			}
	   
	  $dh_name =$row['DRILL_HOLE_NAME'];
	  if ($row[IS_FINISH_SIZE_HIDE_] ==1) $finish_hole="/";
	  else{
		if (($row['PRESS_FIT']==1 or $row['UNIT_MM_']==1) and $cust_code==="HWT")
                  $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3).'MM';
	         else{
				 if ($unit==='MM')
				     $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3);
			     else
					 $finish_hole=number_format($row['FINISHED_SIZE'],2).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS'],2);
			 }
	}
	if ($row[IS_FINISH_SIZE_HIDE_] ==1) $finish_length="/";
	else if (!$row['FINISHED_LENGTH']) $finish_length='';
	else{
		 if ($unit==='MM')
				     $finish_length=number_format($row['FINISHED_LENGTH']*0.0254,3).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS']*0.0254,3);
			     else
					 $finish_length=number_format($row['FINISHED_LENGTH'],2).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS'],2);

	}
	     if ($row['ROUTDRILL_']) $bis_size='';
		 else 
			$row['DRILL_PROGRAM_DRILL_TECHNOLOGY']==2?$bit_size=number_format($row['BIT_SIZE'],1):$bit_size=number_format($row['BIT_SIZE']*0.0254,3);
		if ($SO_UNIT_==='UNIT'and $ERP_UNIT_PER_ARRAY_==1 )
		   {$unit_factor=1;
	       $panel_factor=0;
           }
      else if ($SO_UNIT_==='UNIT' and $ERP_UNIT_PER_ARRAY_>1)
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
       else if ($SO_UNIT_==='UP-PANEL' and $ERP_UNIT_PER_ARRAY_==1)
          {$unit_factor=0;
	       $panel_factor=1;
           }
	  else 
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
        if ($row['PCB_COUNT']===0) $unit_count="/";
		else number_format($row['PCB_COUNT']*$unit_factor,2)==0?$unit_count="":$unit_count=number_format($row['PCB_COUNT']*$unit_factor,2);
        if ($row['PCB_COUNT']===0) $panel_count="/";
		else number_format($row['PCB_COUNT']*$panel_factor,2)==0?$panel_count="":$panel_count=number_format($row['PCB_COUNT']*$panel_factor,2); 
        $dh_type=$row['DRILL_HOLE_TYPE_T'];
		$remark=$row['MI_REMARK_'];
	$nc.="<tr>
       <td ><center>$dh_name</center></td>
       <td><center>$finish_hole</center></td>
       <td><center>$finish_length</center></td>
       <td><center>$bit_size</center></td>
       <td><center>$unit_count<i></i></center></td>
       <td><center>$panel_count</center></td>
       <td><center>$dh_type</center></td>
       <td><center>$remark</center></td>
      </tr>";

}
  if ($l_drill==1 and $l_drill1=0) echo $bar;
  if ($l_drill==1) echo $nc.="</table>";//镭射
  $nc='';
//控制深度钻
 	$bar='<div  class="head_bar">
		<p><center>Control Depth Drilling Information 控制深度钻孔表表</center></p>
	</div>';
 $drill_t="select  distinct  da.*,dh.*,
 CASE
   WHEN  dh.SECOND_DRILL_=1
        THEN 'B'
   WHEN dh.THIRD_DRILL_=1
       THEN 'C'
   WHEN dh.FOURTH_DRILL_=1
      THEN 'D'
   WHEN dh.FIFTH_DRILL_=1
       THEN 'E'
   WHEN dh.SIXTH_DRILL_=1
      THEN 'F'
　　ELSE 'A'
END d_grp
from rpt_job_drill_bit_list da, drill_hole_da dh 
where root_id=$root_id and da.drill_program_item_id= dh.item_id and da.drill_program_revision_id= dh.revision_id 
and da.drill_hole_sequential_index= dh.sequential_index
and da.DRILL_PROGRAM_DRILL_TECHNOLOGY =1
and dh.backdrill_=0
ORDER by  d_grp, da.drill_hole_name,da.pilot_number";
$DRILL_UNIT_==0?$unit="Mil":$unit="MM";
$tc = oci_parse($conn, $drill_t);
oci_execute($tc, OCI_DEFAULT);
$dp_name='';
$l_drill=0;
$l_drill1=0;
while ($row = oci_fetch_array($tc, OCI_RETURN_NULLS)){
       $l_drill=1;
	   if ($dp_name!=$row['D_GRP']	){
		     if ($dp_name!='')
		   {     echo $bar;
				 echo $nc.="</table>";
				 $l_drill1=1;
			 }
			 $row['DRILL_PROGRAM_DRILL_TECHNOLOGY']==2?$size_unit='Mil':$size_unit='MM';
	         switch ($dp_name=$row['D_GRP'])
		     {
				 case 'A':
					 $dp_name1='Control Depth Drill 1ST';
				     break;
				 case 'B':
					 $dp_name1='Control Depth Drill 2ND';
				     break;
				 case 'C':
					 $dp_name1='Control Depth Drill 3RD';
				     break;
				case 'D':
					 $dp_name1='Control Depth Drill 4TH';
				     break;
                case 'E':
					 $dp_name1='Control Depth Drill 5TH';
				     break;
				case 'F':
					 $dp_name1='Control Depth Drill 6th';
				     break;
			 }
		     $nc="<table class='tab'>
             <tr><td colspan='8'><B>Drill Progrm Name:&nbsp;&nbsp;&nbsp;$dp_name1</B> </td></tr>
             <tr>
	            <td rowspan='2' width ='30'><center><b>Tool</b></center></td>
	            <td rowspan='2' width='100'><center><b><div><B>Finished Hole</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2'><center><b><div><b>Slot Length</b></div><div><b>$unit</b></div></b></center></td>
	            <td rowspan='2' width='50'><center><b><div><b>Drill Size</b></div><div><b>$size_unit</b></div></b></center></td>
                <td colspan='2' ><center><b>Count</b></center></td>
            	<td rowspan='2' width='60'><center><b>Drill Type</b></center></td>
                <td rowspan='2'><center><b>Remark</b></center></td>
           </tr>
          <tr>
            	<td><center><b>Unit</b></center></td>
	           <td><center><b>Up-Panel</b></center></td>
          </tr>";
           
			}
	   
	  $dh_name =$row['DRILL_HOLE_NAME'];
	  if ($row[IS_FINISH_SIZE_HIDE_] ==1) $finish_hole="/";
	  else{
		if (($row['PRESS_FIT']==1 or $row['UNIT_MM_']==1) and $cust_code==="HWT")
                  $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3).'MM';
	         else{
				 if ($unit==='MM')
				     $finish_hole=number_format($row['FINISHED_SIZE']*0.0254,3).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS']*0.0254,3);
			     else
					 $finish_hole=number_format($row['FINISHED_SIZE'],2).'+'.number_format($row['FINISHED_SIZE_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_SIZE_TOL_MINUS'],2);
			 }
	}
	if ($row[IS_FINISH_SIZE_HIDE_] ==1) $finish_length="/";
	else if (!$row['FINISHED_LENGTH']) $finish_length='';
	else{
		 if ($unit==='MM')
				     $finish_length=number_format($row['FINISHED_LENGTH']*0.0254,3).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS']*0.0254,3).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS']*0.0254,3);
			     else
					 $finish_length=number_format($row['FINISHED_LENGTH'],2).'+'.number_format($row['FINISHED_LENGTH_TOL_PLUS'],2).'/-'.number_format($row['FINISHED_LENGTH_TOL_MINUS'],2);

	}
	     if ($row['ROUTDRILL_']) $bis_size='';
		 else 
			$row['DRILL_PROGRAM_DRILL_TECHNOLOGY']==2?$bit_size=number_format($row['BIT_SIZE'],1):$bit_size=number_format($row['BIT_SIZE']*0.0254,3);
		if ($SO_UNIT_==='UNIT'and $ERP_UNIT_PER_ARRAY_==1 )
		   {$unit_factor=1;
	       $panel_factor=0;
           }
      else if ($SO_UNIT_==='UNIT' and $ERP_UNIT_PER_ARRAY_>1)
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
       else if ($SO_UNIT_==='UP-PANEL' and $ERP_UNIT_PER_ARRAY_==1)
          {$unit_factor=0;
	       $panel_factor=1;
           }
	  else 
          {$unit_factor=1/$ERP_UNIT_PER_ARRAY_;
	       $panel_factor=1;
           }
        if ($row['PCB_COUNT']===0) $unit_count="/";
		else number_format($row['PCB_COUNT']*$unit_factor,2)==0?$unit_count="":$unit_count=number_format($row['PCB_COUNT']*$unit_factor,2);
        if ($row['PCB_COUNT']===0) $panel_count="/";
		else number_format($row['PCB_COUNT']*$panel_factor,2)==0?$panel_count="":$panel_count=number_format($row['PCB_COUNT']*$panel_factor,2); 
        $dh_type=$row['DRILL_HOLE_TYPE_T'];
		$remark=$row['MI_REMARK_'];
	$nc.="<tr>
       <td ><center>$dh_name</center></td>
       <td><center>$finish_hole</center></td>
       <td><center>$finish_length</center></td>
       <td><center>$bit_size</center></td>
       <td><center>$unit_count<i></i></center></td>
       <td><center>$panel_count</center></td>
       <td><center>$dh_type</center></td>
       <td><center>$remark</center></td>
      </tr>";

}
  if ($l_drill==1 and $l_drill1=0) echo $bar;
  if ($l_drill==1) echo $nc.="</table>";
//生产板拼图
	$bar='<div  class="head_bar">
		<p><center>Production Panel S&R 生产板拼图</center></p>
	</div>';
	echo $bar;
   //print ('<img width="100%" src="data:image/pjpeg;base64,'.base64_encode($img).'"/>');
   $stackup_img="<img width='80%' style='margin-top:5px;margin_left:20px;' src=\"inplan/gz/getpic_t.php?root_id=$root_id&pic_type=panel\" />";
  // print  $stackup_img;
 $sql="select a.text ,p.blob_data from rpt_job_snap_note_list a, rev_controlled_lob p where  a.annotated_picture=p.lob_id and a.root_id=$root_id and snap_note_name like '%Dimensions_Material%' ";
 $stid = oci_parse($conn, $sql);
 oci_execute($stid);      
 $row = oci_fetch_array($stid, OCI_RETURN_NULLS);

 echo "<div style='border:solid 2px black;margin-top:10px;margin-left:35px;' >".$stackup_img."<div style='border-top:solid 1px black;height:25px;padding-top:10px;'><b>".$row['TEXT']."</b></div></div>";

 //echo "<div padding-left:20px;>Description:".$row['TEXT']."</div>";
 //开料图
 $bar='<div  class="head_bar">
		<p><center>板料/铜箔/P片/铝片/底板开料图开料图</center></p>
	</div>';
	echo $bar;
    $sql="select a.item_name, pic.blob_data ,pic.lob_id,
          CASE
              WHEN  a.item_name like '%Cut%'
                THEN 'A'
              WHEN a.item_name like '%PREPREG%'
                  THEN 'B'
              WHEN a.item_name like '%COPPER%'
                  THEN 'C'
              WHEN a.item_name like '%ALUMINIUM%'
                  THEN 'D'
           　ELSE 'E'
            END ord_pic
        from public_items a, material_cut_tool ct, rev_controlled_lob pic 
        where a.item_id=ct.item_id 
        and  a.revision_id=ct.revision_id
        and ct.image=pic.lob_id
	    and  a.root_id=$root_id
        order by ord_pic";
		
 $lb_id='';
 $p_pic = oci_parse($conn, $sql);
 oci_execute($p_pic);      
 while ($row = oci_fetch_array($p_pic, OCI_RETURN_NULLS)){
	
	   if ($row['ORD_PIC']=='A') $pic_name='CORE - 板料开料图';
	   else  $pic_name=$row['ITEM_NAME'];
       $lb_id=$row['LOB_ID'];
	   $cut_img="<img width='80%' style='margin-top:5px;margin_left:10px;' src=\"inplan/gz/getpic_t.php?root_id=$lb_id&pic_type=cut_pic\"/>";
	   echo "<div style='border:solid 2px black;margin-top:10px;margin-left:35px;' ><div style='border-bottom:solid 1px black;height:25px;padding-top:10px;'><b>".$pic_name."</b></div>".$cut_img."</div>";
	   }
?>
</div>
</html>