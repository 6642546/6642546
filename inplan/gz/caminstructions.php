 <html>
  <style>
		/*table{
		font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;

	}*/
	.tab {
		font: 12px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
      	    border:solid 2px black;
      	    padding:0;
      	    border-spacing:0;
      	    border-collapse:collapse;
      	    width:98%;
			margin-top:5px;
			margin-left:10px;

			}
   .title {
      text-align:center;
	  font-size:120%;
   }
  #top_tab th
       {
         border:solid 1px black;
         height:20px;
		 font-size:91%;
         font-style:bold;
		 font-weight:bold;
		 text-align:center;
		/* width:10%;
	     padding-left:4px;
		 margin-left:20px;*/
       }
   #top_tab td 
       {
         border:solid 1px black;
         height:20px;
         font-size:90%;
		 text-align:center;
		/* width:10%;
	     padding-left:4px;
		 margin-left:20px;*/
       }
	  #top_tab1 th
       {
         border:solid 1px black;
         height:20px;
		 font-size:91%;
         font-style:bold;
		 font-weight:bold;
		 text-align:center;
		/* width:10%;
	     padding-left:4px;
		 margin-left:20px;*/
       }
   #top_tab1 td 
       {
         border:solid 1px black;
         height:20px;
         font-size:90%;
		 text-align:left;
		/* width:10%;
	     padding-left:4px;
		 margin-left:20px;*/
       }
	  .head_bar{
		    width:100%;
			height:25px;
			margin-top:3px;
	        background-color:gray;
			font-size:200%;
			color:white;
			padding-top:5px;
			margin-left:20px;
	  }
	    .head_bar1{
		    width:auto;
			height:20px;
			margin-top:2px;
	        /*background-color:gray;*/
			font-size:200%;
			color:black;
			padding-top:5px;
			margin-left:20px;
	  }
 </style>
<br/><div class='header'><center><b><font size=+2>工具制作指示单 CAM Instructions&nbsp;</font></b></center><br></div>
<?php

	$site = $_GET['site'];
	$job =  $_GET["job_name"];

	$sqltrav="select job_name
			      ,cil.description
			      , ci_set_name
			      , cin.ci_sequential_index
			      ,cin.sequential_index
			      , cin.pre_instantiated_string 
			from " . $schema . "RPT_JOB_CAM_INSTRUCTION_LIST cil 
			  inner join " . $schema . "ci_note cin 
			  on cil.item_id = cin.item_id 
			  and cil.revision_id = cin.revision_id 
			  and cil.sequential_index = cin.ci_sequential_index 
			where job_name = '" . $job . "'
			order by ci_set_name, cin.ci_sequential_index, cin.sequential_index, ci_set_name";
	
	if (file_exists("oracle_conn.php")){
		require("oracle_conn.php");
	
	} else {
		require("../../oracle_conn.php");
	}
	$rstrav = oci_parse($conn, $sqltrav);
	oci_execute($rstrav, OCI_DEFAULT);

	$headerinfo = "<table border=0>
	<tr><td colspan=2><b><u><i>Job Details</i></u></b><td width=6></td><td colspan=2><b><u><i>Production Details</i></u></b></tr>
	<tr><td><b>Job Name:</b></td><td>@@jobname@@</td><td width=6></td><td><b>Panel Size:</b></td><td>@@panelsize@@</td></tr>
	<tr><td><b><b>MRP Name:</b></td><td>@@mrpname@@</td><td width=6></td><td><b><b>Number Of Layers:</b></td><td>@@layers@@</td></tr>
	<tr><td><b><b>ITAR:</b></td><td>@@itar@@</td><td width=6></td><td><b><b>Number Of Pcbs:</b></td><td>@@pcbs@@</td></tr>
	<tr><td><b><b> </b></td><td> </td><td width=6></td><td><b><b>Job Type:</b></td><td>@@jobtype@@</td></tr></TABLE>";
	$tableinfo = "<br>&nbsp;
	<table BORDER COLS=1 WIDTH='100%'; ><tr><td BGCOLOR='#58ACFA'; WIDTH='90%';><b>@@section@@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	@@WC@@:</b></td></tr>@@tabledata@@</table>";
	

	$headerinfo = str_replace("@@jobname@@",$job,$headerinfo);
	if($itar == 1){
		$headerinfo = str_replace("@@itar@@","Yes" ,$headerinfo);
	}
	else{
		$headerinfo = str_replace("@@itar@@","No" ,$headerinfo);
	}
	$headerinfo = str_replace("@@panelsize@@",$panelSize,$headerinfo);
	$headerinfo = str_replace("@@layers@@",$numLayers,$headerinfo);
	$headerinfo = str_replace("@@pcbs@@",$numParts,$headerinfo);
	$headerinfo = str_replace("@@jobtype@@",$jobType,$headerinfo);
	$headerinfo = str_replace("@@mrpname@@",$mrpName,$headerinfo);

	$ordernum = -1;
	$travindex = -1;
	$tmpInfo = "";
	$wcnotes = "";
	$tmpInfo=$tmpInfo . $headerinfo;
	while(oci_fetch($rstrav)){
		//		$tmpInfo = $tmpInfo . "<p style='page-break-before: always'>";
		if(oci_result($rstrav, 2)!==$travindex){
			if($travindex <> -1){
				$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
			}
			$wcnotes = "";
			$tmpInfo = $tmpInfo . $tableinfo;
			$tmpInfo = str_replace("@@section@@",oci_result($rstrav, 3),$tmpInfo);
			$tmpInfo = str_replace("@@WC@@",oci_result($rstrav, 2),$tmpInfo);
			$tmpInfo = str_replace("@@discription@@",oci_result($rstrav, 6),$tmpInfo);
			$travindex=oci_result($rstrav, 2);
		}
		$wcnotes = $wcnotes . "<TR><TD>" . oci_result($rstrav, 6) . "</TD></TR>";
	}
	
	if($travindex <> -1){
		$tmpInfo = str_replace("@@tabledata@@",$wcnotes,$tmpInfo);
	}
	echo $tmpInfo;
	echo '<hr>';
	echo "<div class='header' style='aglig:left'><b><font size=+1>一、菲林修改</font></b><br></div>";
    echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>1.0生产菲林要求(Unit Mil)</font></b><br></div>";
    $table1_1_head='<table id="top_tab" class="tab" border=0 >
	                <tr>
					<th>Layer No.</th>	
					<th>底铜(oz)</th>
					<th>PTH AR<br> 最小连接位</th>	
					<th>PTH AR<br> 最小其它位</th>
					<th>PTH其它位<br>允许崩孔条件</th>	
					<th>VIA AR<br> 最小连接位</th>
					<th>VIA AR<br> 最小其它位</th>	
					<th>VIA其它位<br>允许崩孔条件</th>
					<th>MICROVIA AR<br> 最小连接位</th>	
					<th>MICROVIA AR<br> 最小其它位</th>
                    <th>MICROVIA其它位<br>允许崩孔条件</th>	
					</tr>';
				$arSql="select cp.item_name,cla.ar_cust_pth_joint_,cla.ar_cust_pth_other_,cla.ar_cust_broken_ ,round(cl.required_cu_weight/28.34,2) as cu_weight
				,cla.ar_cust_via_joint_,cla.ar_cust_via_other_,cla.min_line_to_line_,cla.min_line_width_
				from items jb , items cp ,copper_layer cl ,copper_layer_da cla
				where jb.item_type=2 and cp.item_type=3
				and jb.root_id=cp.root_id and jb.last_checked_in_rev is not null and jb.deleted_in_graph is null
				and cp.item_id=cl.item_id and cp.last_checked_in_rev=cl.revision_id and cl.item_id=cla.item_id and cl.revision_id=cla.revision_id
				and jb.item_name='$job' order by cl.layer_index";
				$ar = oci_parse($conn, $arSql);
				oci_execute($ar, OCI_DEFAULT);
           while ($row = oci_fetch_array($ar, OCI_RETURN_NULLS)){
      	     $table1_1_head.="<tr>
				<td>".$row['ITEM_NAME']."</td>
				<td>".$row['CU_WEIGHT']."</td>
		        <td>".$row['AR_CUST_PTH_JOINT_']."</td>
				<td>".$row['AR_CUST_PTH_OTHER_']."</td>
				<td>".$row['AR_CUST_PTH_BROKEN_']."</td>
				<td>".$row['AR_CUST_VIA_JOINT_']."</td>
				<td>".$row['AR_CUST_VIA_OTHER_']."</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			   </tr>";
			 $table1_1_head_d.="<tr>
				<td>".$row['ITEM_NAME']."</td>
				<td>".$row['CU_WEIGHT']."</td>
		        <td>"."</td>
				<td>".$row['MIN_LINE_TO_LINE_']."</td>
				<td>".$row['MIN_LINE_WIDTH_']."</td>
				<td>"."</td>
			   </tr>";
			   
			   }
    echo  $table1_1_head.'</table>';
    $table1_1_head='<table id="top_tab" class="tab" border=0 >
	                <tr>
					<th style="width:7%;">Layer No.</th>	
					<th style="width:6%;">底铜(oz)</th>
					<th style="width:10%;">线宽补偿<br>(Mil)</th>	
					<th style="width:10%;">最小菲林线宽<br>(Mil)</th>
					<th style="width:10%;">最小菲林线隙<br>(Mil)</th>	
					<th >特殊要求</th>
					</tr>';
   echo  $table1_1_head. $table1_1_head_d.'</table>';
   echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>2.0 内层菲林修改</font></b><br></div>";
   $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>2.1 内层独立PAD</td>
				<td>允许最大4mil.除与SMD相连线长度小于16mil的孔对应pad</td>
		        <td>"."</td>
				<td rowspan='16'>"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>2.2 内层线路连结位加Teardrop </td>
				<td>BGA及LGA内via PAD</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>									
				<td style=' font-weight:bold;'>2.3 内层加 Dummy Pad </td>
				<td>允许</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.3.1 内层Dummy Pad位置:</td>
				<td>Breakaway位	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.3.2 内层Dummy Pad形式:</td>
				<td>本厂规则</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td  style=' font-weight:bold;'>2.4 内层露铜要求</td>
				<td>按本厂正常能力谷大	测量位置 	成品测Pad底	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.4.1 外围成型后</td>
				<td>按下面列表要求</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.4.2 NPTH钻后</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>2.5 成品线宽/线隙</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				<td style='padding-left:8px;'>2.5.1 成品线宽/线隙公差:</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   

				<td style='padding-left:8px;'>2.5.2 成品线宽测量位置</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   

				<td style='padding-left:8px;'>2.5.3 阻抗线宽控制</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   

				<td  style=' font-weight:bold;'>2.6 最小DTM：</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
				<td  style=' font-weight:bold;'>2.7 铜桥制作要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   

				<td style=' font-weight:bold;'>2.8 处理Stub</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'></td>
		       
				<td>"."</td>
			    </tr>		
			   ";
   echo  $table1_1_head.'</table>';

      echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>3.0 外层菲林修改</font></b><br></div>";
   $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>3.1 外层线路连结位加Teardrop</td>
				<td>允许最大4mil.除与SMD相连线长度小于16mil的孔对应pad、BGA及LGA内via PAD</td>
		        <td>"."</td>
				<td rowspan='22'>"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>3.2 外层加 Dummy Pad </td>
				<td>允许</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>										
				<tr>
				<td style='padding-left:8px;'>3.2.1 外层Dummy Pad位置:</td>
				<td>Breakaway位	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>3.2.2 外层Dummy Pad形式:</td>
				<td>本厂规则</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td  style=' font-weight:bold;'>3.3 生产菲林PAD谷大要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>3.3.1 生产菲林SMD PAD/BALL PAD谷大要求</td>
				<td>按本厂正常能力谷大	测量位置 	成品测Pad底</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>3.3.2 SMD PAD/BALL PAD成品要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>3.3.3 生产菲林BGA PAD谷大要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>3.3.4 BGA PAD 成品要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>3.4 阻抗线宽控制</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				<tr>
				<td style='padding-left:8px;'>3.4.1 成品线宽/线隙公差:</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
                <tr>
				<td style='padding-left:8px;'>3.4.2 成品线宽测量位置</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				<tr>
    			<td  style=' font-weight:bold;'>3.5 Fiducial Mark</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				<tr>
				<td style='padding-left:8px;'>3.5.1 Fiducial Mark 外层对位光点</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
                <tr>
				<td style='padding-left:8px;'>3.5.2 阻焊对位光点</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
                <tr>
				<td  style=' font-weight:bold;'>3.6 外层露铜要求</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
				<tr>
				<td style='padding-left:8px;'>3.6.1 外围成型后</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>			   
                <tr>
				<td style='padding-left:8px;'>3.6.2 外层NPTH封孔</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
                <tr>
				<td style=' font-weight:bold;'>3.7 金手指设计</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				 <tr>
				<td style='padding-left:8px;'>3.7.1 电金引线</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				 <tr>
				<td style=' font-weight:bold;'>3.8 处理Stub</td>
				<td></td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>	
				<tr>
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'></td>
		       
				<td>"."</td>
			    </tr>		
			   ";
     echo  $table1_1_head.'</table>';
    echo "<div class='header' style='aglig:left;padding-top:5px;'><b><font size=+1>二、绿油菲林/绿油塞孔工具/白字菲林修改及要求</font></b><br></div>";
    echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>1.0 绿油菲林修改</font></b><br></div>";
    $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>1.1 绿油桥要求</td>
				<td>需做出且SMD Pad间CAD未设计绿油桥(特别指出位置除外)也需要做出绿油桥</td>
		        <td>"."</td>
				<td rowspan='9'>"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>1.2 绿油要求 </td>
				<td>不允许露线露铜及渗油上PAD，并且未开窗的NPTH加开绿油窗.</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>									
				<td style=' font-weight:bold;'>1.3 绿油菲林塞孔状态要求  </td>
				<td>允许</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>1.3.1 单面未开窗的VIA孔, W/F做</td>
				<td>可入孔不塞孔.	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>1.3.2 双面未开窗的VIA孔, W/F做</td>
				<td>本厂规则</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>1.3.3 开窗与原装孔等大的VIA, W/F做</td>
				<td>N/A	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>1.3.4 开窗比孔大比PAD小的VIA, W/F做 </td>
				<td>N/A</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>1.3.5 开窗不完整的孔, W/F做</td>
				<td>可入孔不塞孔.	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'>为NPTH孔、NPT槽阻焊加开窗
MARK点、PTH孔和槽及其盘以及与之连接的盘按ENIG制作 角线全部按OSP和见备注</td>
		       <td></td>
			    </tr>		
			   ";
       echo  $table1_1_head.'</table>';
     echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>2.0 绿油塞孔工具要求</font></b><br></div>";
     $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>2.1绿油塞孔工具塞孔状态要求 </td>
				<td></td>
		        <td>"."</td>
				<td rowspan='9'>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.1.1 单面未开窗的Via</td>
				<td>N/A	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.1.2 双面未开窗的Via</td>
				<td>W/F时做可入孔不塞孔，绿油后表面完成前塞孔.</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.1.3 开窗与原装孔等大的VIA</td>
				<td>N/A	</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
				<tr>
				<td style='padding-left:8px;'>2.1.4 开窗比孔大比PAD小的VIA </td>
				<td>N/A</td>
		        <td>"."</td>
				<td>"."</td>
			    </tr>
			<td style=' font-weight:bold;'>2.2 背钻孔要求 </td>
				<td>表面处理后塞孔</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<td style=' font-weight:bold;'>2.3 按客户要求塞孔菲林做</td>
				<td>绿油前塞孔，非塞孔面绿油Follow CAD状态</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<tr>
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'></td>
		       <td></td>
			    </tr>		
			   ";
   echo  $table1_1_head.'</table>';
        echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>3.0 白字要求</font></b><br></div>";
     $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>3.1 字符标记完整性:</td>
				<td>上PAD及入孔字符可稍移/稍削至清晰可辩</td>
		        <td>"."</td>
				<td rowspan='9'>"."</td>
			    </tr>
				<tr>
			    <td style=' font-weight:bold;'>3.2 大GND位上字符是否保留 </td>
				<td>表面处理后塞孔</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>3.3 字符线宽：</td>
				<td>绿油前塞孔，非塞孔面绿油Follow CAD状态</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<tr>
			    <td style=' font-weight:bold;'>3.4 BGA线框： </td>
				<td>BGA处角线或示意方向的符号或极性符号（若有）需可辨认</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<tr>
				<td style=' font-weight:bold;'>3.5 字符对位</td>
				<td>不允许字符入孔，字符离孔边≥6.0mil； 允许字符上绿油塞孔和绿油入孔的焊盘</td>
		        <td>"."</td>
				<td >"."</td>
			    </tr>
				<tr>
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'></td>
		       <td></td>
			    </tr>		
			   ";
  echo  $table1_1_head.'</table>';
       echo "<div class='header' style='aglig:left;padding-left:10px;padding-top:2px;'><b><font size=2.5>4.0 其他 </font></b><br></div>";
     $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;"></th>	
					<th style="width:30%;">一般要求</th>
					<th style="width:30%;">特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				<td style=' font-weight:bold;'>4.1 碳油菲林修改要求</td>
				<td></td>
		        <td>"."</td>
				<td rowspan='9'>"."</td>
			    </tr>
				<tr>
			    <td style=' font-weight:bold;'>4.2 蓝胶菲林修改要求</td>
				<td</td>
		        <td>"."</td>
				<td >"."</td>
				</tr>
				<tr>
                <td style=' font-weight:bold;'>特殊要求</td>
				<td colspan='2'></td>
		       <td></td>
			    </tr>					
			   ";
   echo  $table1_1_head.'</table>';
      echo "<div class='header' style='aglig:left;padding-top:5px;'><b><font size=+1>三、LOGO及其它MARKING ( LOGO and other Marking )</font></b><br></div>";
     $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:30%;">项目</th>	
					<th style="width:10%;">标记</th>
				    <th style="width:15%;" colspan="2">位置</th> 
					<th style="width:20%;" >特殊要求</th>	
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				     <td style=' font-weight:bold;'>特殊符号</td>
				     <td>▲</td>
		             <td rowspan='5'>于C/S面印白字</td>
				     <td rowspan='5'>每单元加</td>
				     <td></td>
				     <td rowspan='18'>"."</td>
			        </tr>
                  <tr>
				   <td style=' font-weight:bold;'>制造厂Logo</td>
				   <td>本厂商标</td>					    
			       <td >"."</td>
			     </tr>
				   <tr>
				   <td style=' font-weight:bold;'>UL Type</td>
				   <td>26</td>
				   <td >"."</td>
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>UL符号(UL mark)</td>
				   <td>94V-0</td>			
			       <td >"."</td>
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>UL等级(UL grade)</td>
				   <td>兄</td>	
			       <td >"."</td>
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>Date Code(UL mark)</td>
				   <td>WWYY</td>	
			       <td >于C/S面印白字</td>
				   <td >每单元加</td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>Lot Code(UL mark)</td>
				   <td>WWYY</td>	
			       <td >于C/S面印白字</td>
				   <td >每单元加</td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>位置序号(Position ID)(UL mark)</td>
				   <td>WWYY</td>	
			       <td >于C/S面印白字</td>
				   <td >每单元加</td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>本厂p/n. (Manufacture P/N)</td>
				   <td>WWYY</td>	
			       <td >于C/S面印白字</td>
				   <td >每单元加</td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>加客户编号/版本号 (Add Cust P/N or Rev)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>产地(Manufacture Site)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>防静电标记(Protective Static)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>Lead Free标记 (Lead Free Mark)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
			  </tr>
			  	<tr>
				   <td style=' font-weight:bold;'>FQC数字章 (FQC digit mark)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>E-T印 (ET Mark)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>Hi-Pot印( Hi-Pot Mark)</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style=' font-weight:bold;'>Laser mark</td>
				   <td></td>	
			       <td ></td>
				   <td ></td>
				   <td ></td>
				  
			    </tr>
				<tr>
				   <td style='font-weight:bold;' rowspan='4'>特殊要求</td>
				 
			  </tr>
			   ";
   echo  $table1_1_head.'</table>';
     echo "<div class='header' style='aglig:left;padding-top:5px;'><b><font size=+1>四、Coupon List</font></b><br></div>";
	 $table1_1_head='<table id="top_tab1" class="tab" border=0 >
	                <tr>
					<th style="width:15%;">Coupon</th>	
					<th style="width:15%;">Model</th>
				  <th style="width:10%;" >位置（Location)（Quantity）</th>
					<th style="width:10%;" >数量（Quantity）</th>
					<th style="width:30%;" >特殊要求(Special requirements)</th>
					<th style="width:10%;">图纸编号</th>
					</tr>';
   $table1_1_head.="<tr>
				     <td style=' font-weight:bold;'>阻抗Coupon</td>
				     <td>本厂Coupon</td>
		             <td r></td>
				     <td ></td>
				     <td></td>
				     <td rowspan='4'>"."</td>
			        </tr>
                  <tr>
				      <td style=' font-weight:bold;'>IST Coupon</td>
				      <td>GS40000A-HW</td>					    
			          <td >"."</td>
				      <td >"."</td>
					  <td >"."</td>
		
			     </tr>
				   <tr>
				     <td style=' font-weight:bold;'>SET2Drill Coupon</td>
				     <td>SET2Drill Coupon</td>
				     <td >"."</td>
				     <td >"."</td>
					 <td >"."</td>
		
			    </tr>
			   ";
   echo  $table1_1_head.'</table>';
?>

</html>