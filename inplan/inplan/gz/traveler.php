<html>
 <style>
	table{
		font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;

	}
	.tab {
      	    border:solid 2px black;
      	    padding:0;
      	    border-spacing:0;
      	    border-collapse:collapse;
      	    width:100%;
			margin-top:5px;

			}
   td
       {
         border:solid 1px black;
         height:20px;
	     padding-left:4px;
		
       }
	.head_left{
		 float:left;
		 padding-left:100px;
		 margin-top:10px;
		 margin-bottom:70px;
	}
	.head_right{
	       float:right;
		   margin-bottom:70px;
		   padding-right:100px;
		   margin-top:10px;
	/*  border:solid 1px #99BBDD; */
		   }
	   .head_box_1{
		       width:auto;
			   height:50px;
	           border-bottom:solid 1px black;
            }
      .head_bar{
		    width:auto;
			height:25px;
			margin-top:2px;
	        background-color:gray;
			font-size:200%;
			color:white;
			padding-top:5px;
	  }
	   .head_bar1{
		    width:auto;
			height:25px;
			margin-top:2px;
	        /*background-color:gray;*/
			font-size:200%;
			color:black;
			padding-top:5px;
	  }
      .head_box{
		     width:auto;
			 height:100px;
		     border-top:solid 1px black;
			 margin-top:2px;
			 border-bottom:solid 1px black;
	  }
	  .find_caption{
		   float:left;
		   font-size:120%;
		   line-height:25px;
		   text-align:right;
		   width:200px;
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
		   
	   }
	  .ci_note{
		  border-bottom: solid 2px black;
		  border-left: solid 2px black;
		  border-right: solid 2px black;
		  width:auto;
		  height:50px;
	  }
	  .ci_note_1{
	     margin-left:20px;
		 padding-top:30px;
	  }
	  .process_bar{
	        width:auto;
			height:25px;
			margin-top:5px;
	        /*background-color:gray;*/
			border:solid 1px black;
			font-size:150%;
			color:black;
			padding-top:10px;
			text-align:center;
			clear:both;
      }
      .trav_code{
             float:left;
	         width:91%;
			 height:20px;
			 border-top:solid 1px black;
			 border-bottom:solid 1px black;
			 border-left:solid 1px black;
			 margin-top:5px; 
	         clear:left;
			 padding-top:5px;
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
	    width:auto;
		clear:both;
		border-left:solid 1px black;
		border-right:solid 1px black;
		border-bottom:solid 1px black;
	  }
	
	  .attr_des{
		   float:left;
		   text-align:left;
		   width:320px;
		   border:none;
		   padding-left:5px;
		   clear:both;
		   border-left:solid 1px black;
		   border-bottom:solid 1px black; 
	  }
	  .attr_value{
           float:right;
		   text-align:left;
		   width:412px;
		   border:none;
		   clear:both;
           border-right:solid 1px black;
		   border-bottom:solid 1px black;
	  }
	  .trav_attr{

	  }
	  .conner{
	  }
 </style>
 <div style="padding:5px;width:740px;">
	<!--<div><center><b><font size=+2>Traveler Routing&nbsp;</font></b></center></div><br>-->
	<div class="head_box_1">
         <div class="head_left">
	          <p><b>Kalex&nbsp;     Circuit &nbsp;    Board &nbsp;   (Guangzhou)  &nbsp;   Ltd</b></p>
		       <p></p>
		       <p></p>
		       <p>&nbsp;&nbsp;&nbsp;A Subsidiary of Viasystems Group, Inc.</p>
	     </div>
	     <div class="head_right">
	           <p><b>皆   利   士   电   脑   版（广  州） 有   限   公   司</b></p>
		       <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;           惠  &nbsp; 亚 &nbsp;  集 &nbsp;  团 &nbsp;  附 &nbsp;  属 &nbsp;  公 &nbsp;  司</p>
	     </div>
	</div>
	<div  class="head_bar">
		<p><center>Manufacturing Instruction</center></p>
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
	$PPAP_LEVEL_ = $row['PPAP_LEVEL_'];
	$DATA_SHARE_ = $row['DATA_SHARE_'];
    $PRODUCT_LEVEL_ = $row['PRODUCT_LEVEL_'];
    $proc_info = str_replace("@@SO_UNIT_@@",$SO_UNIT_ ,$proc_info);
	$proc_info = str_replace("@@FINISH_SURFACE_ERP_@@",$FINISH_SURFACE_ERP_ ,$proc_info);
	$proc_info = str_replace("@@PROFILE_ERP_@@",$PROFILE_ERP_ ,$proc_info);
	$proc_info = str_replace("@@LAMINATION_@@",$LAMINATION_ ,$proc_info);
	$proc_info = str_replace("@@ERP_TECHNOLOGY_LEVEL_@@",$ERP_TECHNOLOGY_LEVEL_,$proc_info);
	$proc_info = str_replace("@@INPUT_NUMBER_@@",$INPUT_NUMBER_ ,$proc_info);
	$proc_info = str_replace("@@PPAP_LEVEL_@@",$PPAP_LEVEL_ ,$proc_info);
	$proc_info = str_replace("@@DATA_SHARE_@@",$DATA_SHARE_ ,$proc_info);
	$proc_info = str_replace("@@PRODUCT_LEVEL_@@",$PRODUCT_LEVEL_ ,$proc_info);
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
$CORNER1_=$row['CORNER1_'];
$CORNER2_=$row['CORNER2_'];
$CORNER3_=$row['CORNER3_'];
$CORNER4_=$row['CORNER4_'];
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
echo $cust_r;
$stid = oci_parse($conn, $ci_note);
oci_execute($stid, OCI_DEFAULT);
echo"<div class='ci_note'> ";
echo "<p><b><font-size=150%>出货成品比较:</font></b></p>";
echo "<div class='ci_note_1'>";
while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS)){
   echo $row[0]."\n";
   echo $row[1];
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
		 echo "<div class='trav_code'><b>step:".$row['TRAVELER_ORDERING_INDEX']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['DESCRIPTION']."</B></div>";
		 echo "<div class='trav_code1'><b>".$row['OPERATION_CODE']."</b></div>";
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
		 while ($row = oci_fetch_array($t_attr, OCI_RETURN_NULLS)){
		 $description.="<div >".$row['DESCRIPTION']."</div>";
		 $value_as_string.="<div >".$row['VALUE_AS_STRING']."</div>";
		}
	    if ($description!=''){
		$description="<div class='attr_des'>".$description."</div>";
		$value_as_string="<div class='attr_value'>".$value_as_string."</div>";
		echo $description;
		echo $value_as_string;
		}
		echo "</div>";
   }
   	$bar='<div  class="head_bar">
		<p><center>Part Layup 叠板图</center></p>
	</div>';
	echo $bar;
   //print ('<img width="100%" src="data:image/pjpeg;base64,'.base64_encode($img).'"/>');
   $stackup_img="<img width='100%' style='margin-top:5px;margin_left:10px;' src=\"inplan/gz/getpic_t.php?root_id=$root_id\" />";
   print  $stackup_img;
  $remark='<table class="tab" border=0 >
  <tr><td width =15% >Remark 备注：</td><td>@@BASIC_MATERIAL_REMARK_@@</td></tr>
  </table>';
  $remark = str_replace("@@BASIC_MATERIAL_REMARK_@@",$BASIC_MATERIAL_REMARK_ ,$remark);
  echo $remark;

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
?>
</div>
</html>