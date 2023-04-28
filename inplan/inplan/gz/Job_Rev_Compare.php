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
      	    width:100%;
			margin-top:5px;
			/*margin-left:20px;*/

			}
   .title {
      text-align:center;
	  font-size:120%;
   }
   td ,th
       {
         border:solid 1px black;
         height:20px;
		 width:50%;
	    /* padding-left:4px;
		 margin-left:20px;*/
       }

    /*表单 */
.form{

margin: 0px auto;

width: 450px;

border: solid 1px #CCCCCC;

}
.bo{

border-bottom: solid 1px #CCCCCC;

}
label{

float: left;

padding: 10px 0px 4px 30px;

}
input{

padding: 1px;

}
input,textarea{

border: 1px solid #CCCCCC;

margin: 5px 0px;

}
textarea{

padding: 2px;

}
.bt{

height: 20px;

font-size: 11px;

border: solid 1px #CCCCCC;

background: #FBFBFB;

text-align: center;

}

.btcenter{

text-align: center;

clear: left;

padding: 4px 0px 0px;

}

/*----下面是选中表单时的变化效果，大家可以自由发挥的---*/

.sffocus {

background: #F0F9FB; /*----for IE----*/

border: 1px solid #1D95C7;

}

textarea:focus, input:focus {

background: #F0F9FB; /*----for firefox......----*/

border: 1px solid #1D95C7;

}
 </style>
<?php

echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
if (!$site) $site = $_GET["site"];
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/gz/stackup";
		$logo_dir = "images";
		require("oracle_conn.php");
		echo "<script type='text/javascript' src='". $pre_dir_scripts . "/jquery-1.4.4.min.js'></script>";	
	echo "<script type='text/javascript' src='". $pre_dir . "/Compare_job.js'></script>";
echo "<center>Only compare diffrent revesion</center>";
$first_job=$_GET['first_job'];
$second_job=$_GET['second_job'];
/*echo "<center>
        <table width=400 bgcolor=#2f4f4f cellspacing=1 cellpadding=0 border=0 align=center>
           <tr bgcolor=#ffffff>
                <td height=30 width=100 align=right bgcolor=#dfdfdf>Job Name:</td><td>&nbsp;<input type=text size=24 height=10 id='first_job' value=$first_job></td>
           </tr>
           <tr bgcolor=#ffffff>
              <td height=30 bgcolor=#dfdfdf align=right>Job Name:</td><td>&nbsp;<input type=text size=24 id='second_job' value=$second_job ></td>
           </tr>
           <tr bgcolor=#ffffff>
              <td height=30 bgcolor=#dfdfdf align=center colspan=2><input type='submit' value='Compare' onclick='submit_ok();'>&nbsp;<input type='reset' value='Reset' onclick='reset();'></td>
           </tr>
         </table>
	     </center>";
		 echo "<hr>";*/
echo "<div class='form'>

<div class='bo'><label for='name'>Job Name：</label>
<input type='text' size='24'  id='first_job' value=$first_job></div>

<div class='bo'><label for='tel'>Job Name：</label>
<input type='text' size='24' id='second_job'  value=$second_job></div>
<div class='btcenter'><input class='bt' type='submit' value='Compare' onclick='submit_ok();'/>
<input type='reset' class='bt' value='Reset' onclick='reset();'/></div></div>";




//echo $first_job;
//echo '</br>';
//echo $second_job;
//job info
$sql="   select rownum,i.item_name as job_name,Customer.customer_name,endcustomer.customer_code,customer.customer_code as cust_code,job_da.*,job_db.*,job.*
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='SO_UNIT_' and ev.enum=job_da.SO_UNIT_) as SO_UNIT_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='LAMINATION_' and ev.enum=job_da.LAMINATION_) as LAMINATION_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='CATEGORY_' and ev.enum=job_da.CATEGORY_) as CATEGORY_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='ROHS_COMPLIANCE_' and ev.enum=job_da.ROHS_COMPLIANCE_) as ROHS_COMPLIANCE_T
         from items i,part,job,job_da  ,job_db,customer,customer endcustomer
        where(i.root_id = (select root_id from items where item_name='".$first_job."' and item_type=2) And i.item_id = job.item_id And job.revision_id = i.LAST_CHECKED_IN_REV)
        and job.item_id=job_da.item_id and job.customer_id=customer.cust_id and job.end_customer_id=endcustomer.cust_id
        and job.revision_id=job_da.revision_id and job_da.item_id=job_db.item_id and job_da.revision_id=job_db.revision_id
        and job.item_id=part.item_id and job.revision_id=part.revision_id";
$job_info=oci_parse($conn, $sql);
oci_execute($job_info, OCI_DEFAULT);
$row = oci_fetch_array($job_info, OCI_RETURN_NULLS);
 $job_a_finish= '成品类型:'. $row['FINISH_SURFACE_ERP_'];
 $job_a_profile= '外形加工:'.$row['PROFILE_ERP_'];
 $job_a_lamination='压板工艺:'.$row['LAMINATION_T'];
$sql="   select rownum,i.item_name as job_name,Customer.customer_name,endcustomer.customer_code,customer.customer_code as cust_code,job_da.*,job_db.*,job.*
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='SO_UNIT_' and ev.enum=job_da.SO_UNIT_) as SO_UNIT_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='LAMINATION_' and ev.enum=job_da.LAMINATION_) as LAMINATION_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='CATEGORY_' and ev.enum=job_da.CATEGORY_) as CATEGORY_T
         ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type and et.type_name='ROHS_COMPLIANCE_' and ev.enum=job_da.ROHS_COMPLIANCE_) as ROHS_COMPLIANCE_T
         from items i,part,job,job_da  ,job_db,customer,customer endcustomer
        where(i.root_id = (select root_id from items where item_name='".$second_job."' and item_type=2) And i.item_id = job.item_id And job.revision_id = i.LAST_CHECKED_IN_REV)
        and job.item_id=job_da.item_id and job.customer_id=customer.cust_id and job.end_customer_id=endcustomer.cust_id
        and job.revision_id=job_da.revision_id and job_da.item_id=job_db.item_id and job_da.revision_id=job_db.revision_id
        and job.item_id=part.item_id and job.revision_id=part.revision_id";
$job_info=oci_parse($conn, $sql);
oci_execute($job_info, OCI_DEFAULT);
$row = oci_fetch_array($job_info, OCI_RETURN_NULLS);
 $job_b_finish= '成品类型:'. $row['FINISH_SURFACE_ERP_'];
 $job_b_profile= '外形加工:'.$row['PROFILE_ERP_'];
 $job_b_lamination='压板工艺:'.$row['LAMINATION_T'];
 $table_p="<table class='tab' border='0' >
<tr><td  align=center style='font-size:160%;font-style:bold;'>$first_job Production Part Parameter</td><td align=center style='font-size:160%;font-style:bold;') >$second_job Production Part Parameter</td></tr>";
if ($job_a_finish==$job_b_finish){
 $table_p.="<tr><td >$job_a_finish</td><td>$job_b_finish</td></tr>";
}else{
	$table_p.="<tr><td ><div style=' margin-left:40px;background:#FFFF33;'>$job_a_finish</div></td><td><div style=' margin-left:40px;background:#FFFF33;'>$job_b_finish</div></td></tr>";
}
if ($job_a_profile==$job_b_profile){
 $table_p.="<tr><td >$job_a_profile</td><td>$job_b_profile</td></tr>";
}else{
	$table_p.="<tr><td ><div style=' margin-left:40px;background:#FFFF33;'>$job_a_profile</div></td><td><div style=' margin-left:40px;background:#FFFF33;'>$job_b_profile</div></td></tr>";
}
if ($job_a_lamination==$job_b_lamination){
 $table_p.="<tr><td >$job_a_lamination</td><td>$job_b_lamination</td></tr>";
}else{
	$table_p.="<tr><td ><div style=' margin-left:40px;background:#FFFF33;'>$job_a_lamination</div></td><td><div style=' margin-left:40px;background:#FFFF33;'>$job_b_lamination</div></td></tr>";
}

$table_p.="<tr><td  align=center style='font-size:160%;font-style:bold;color:#0000CC'> Parameter</td><td align=center style='font-size:160%;font-style:bold;color: #0000CC'); >Parameter</td></tr>";

$pro_name="select a.item_name, b.item_name as process_name  ,a.item_id, a.last_checked_in_rev ,b.item_id as id , b.last_checked_in_rev as rev ,a.root_id as root_id from items a, items b ,public_links ln  
where a.item_id =ln.item_id and ln.points_to=b.item_id and ln.link_type=8 and  a.root_id=869595  and a.item_name not like '%main%'";
$p_name = oci_parse($conn, $pro_name);
oci_execute($p_name, OCI_DEFAULT);
$ip_c=0;
while ($row_r = oci_fetch_array($p_name, OCI_RETURN_NULLS)){	 
         if ($ip_c==0){
 		  $table_p.="<tr><td class='title'>".$row_r['ITEM_NAME']."</td><td></td></tr>";		  
		  $table_p.="<tr><td>".trav_para($row_r['ITEM_ID'],$row_r['LAST_CHECKED_IN_REV'],'IEDT',$conn,$row_r['ROOT_ID'])."</td><td></td></tr>";
		  }
		 $ip_c=1;
}
//$job_p_a=Para('A200077A-302',$conn);  A028764A-300
//$job_p_b=Para('A200077A-301',$conn);
//A062517H-302
$job_p_a=Para($first_job,$conn);  
$job_p_b=Para($second_job,$conn);
foreach($job_p_a as $key=>$value){
	  $table_p.="<tr><td align=center style='font-size:130%;font-style:bold;'>$key</td><td align=center style='font-size:130%;font-style:bold;')>$key</td></tr>";
	   $ka=array_keys($value);
	   $kb=array_keys($job_p_b[$key]);
	  
       if (count($ka)>=count($kb)){
	       foreach($ka as $keya=>$valuea){
                $ip=0;
				foreach($kb as $keyb=>$valueb){
				     if($valuea==$valueb){
					   $kc[$valuea]=$valueb;
					   $ip=1;
					 }
				}
				if ($ip==0){
				   $kc[$valuea]='';
				}
		   } 
           foreach(array_diff($kb,$kc) as $kd=>$kval){
		          $kc['']=$kval;
		   }

	   } else{
		   $ip1=0;
	       foreach($kb as $keyb=>$valueb){
                $ip=0;			
				foreach($ka as $keya=>$valuea){
				     if($valuea==$valueb){
					   $kc[$valuea]=$valueb;
					   $ip=1;
					   $ip1=$ip1+1;
					 }
				}
				if ($ip==0){
				   $kc[$ip1]=$valueb;
				}
		   } 
		   foreach(array_diff($ka,$kc) as $kd=>$kval){
		          $kc[$kval]='';
		   }
	   }


	   //foreach($value as $key1=>$value1){
	  foreach($kc as $key1=>$valuep){
          if (trim($key1)==trim($valuep)){
		    $table_p.="<tr><td style='font-size:110%;font-style:bold;'>$key1</td><td style='font-size:110%;font-style:bold;'>$valuep</td></tr>";
          }else{
		    $table_p.="<tr><td style='font-size:110%;font-style:bold;;background:#FFFF33;'>$key1</td><td style='font-size:110%;font-style:bold;;background:#FFFF33;'>$valuep</td></tr>";
		  }
	      $value1=$job_p_a[$key][$key1];
		
	      if (is_array($value1)&&is_array($job_p_b[$key][$valuep])){			
              if (count(array_diff($value1,$job_p_b[$key][$valuep]))==0){
			        foreach($value1 as $key2=>$value2){
                      if ($value2!=''){
				         $table_p.="<tr><td ><div style=' margin-left:40px;'>$value2</div></td><td><div style=' margin-left:40px;'>$value2</div></td></tr>";
				         }
			        }
			  }else{
				 $out1 = array_diff($value1, $job_p_b[$key][$valuep]);
                  $out2 = array_diff($job_p_b[$key][$valuep], $value1);
				  if (count($out1)>=count($out2)){
	                     foreach($out1 as $pkeys=>$pvalues){
							  $ip=0;
							 foreach($out2 as $pkey=>$pvalue){
							       $pa=split(":",$pvalues);
								   $pb=split(":",$pvalue);
								   if ($pb[0]==$pa[0]){
								      $out3[$pvalues]=$pvalue;
									  $ip=1;
								   }
                             if ($ip==0){
							    $out3[$pvalues]="";
							 }
							 }
						 }			  
				  }else{
                         foreach($out2 as $pkeys=>$pvalues){
							  $ip=0;
							 foreach($out1 as $pkey=>$pvalue){
							       $pa=split(":",$pvalues);
								   $pb=split(":",$pvalue);
								   if ($pb[0]==$pa[0]){
								      $out3[$pvalues]=$pvalue;
									  $ip=1;
								   }
                             if ($ip==0){
							    $out3[$pvalues]="";
							 }
							 }
						 }			  

				  }
				  $result=array_intersect($value1, $job_p_b[$key][$valuep]);
				  foreach($result as $keys=>$values){
				     if ($values!=''){
					 $table_p.="<tr><td><div style=' margin-left:40px;'>$values</div></td><td><div style='margin-left:40px;'>$values</div></td></tr>";
					 }
				  }
				  
				  
                      if (count($out1)>=count($out2)){
                           foreach($out3 as $keys=>$values){
					          $table_p.="<tr><td><div style=' margin-left:40px;background:#FFFF33;'>$keys</div></td><td><div style='margin-left:40px;background:#FFFF33;'>$values</div></td></tr>";
				           }
			            }else{
			               foreach($out3 as $keys=>$values){
					              $table_p.="<tr><td><div style=' margin-left:40px;background:#FFFF33;'>$values</div></td><td><div style='margin-left:40px;background:#FFFF33;'>$keys</div></td></tr>";
				        }
			          }
			
			    unset($out1);
				unset($out2);
                unset($out3);
			  
			  }
		      
		     }else{
			     //  echo $value1;	  
                         if (!is_array($value1)&&is_array($job_p_b[$key][$valuep])){  
							 foreach($job_p_b[$key][$valuep] as $keyk=>$valk){
							   if ($valk!=''){
							      $table_p.="<tr><td><div style=' margin-left:40px;background:#FFFF33;'></div></td><td><div style='margin-left:40px;background:#FFFF33;'>$valk</div></td></tr>";
							     }
							 }
						 }
						 if (is_array($value1)&&!is_array($job_p_b[$key][$valuep])) {
						    foreach($value1 as $keyk=>$valk){
								    if ($valk!=''){
							             $table_p.="<tr><td><div style=' margin-left:40px;background:#FFFF33;'>$valk</div></td><td><div style='margin-left:40px;background:#FFFF33;'></div></td></tr>";
									}
							 }	 							 

						 }	 
          //    $table_p.="<tr><td>$value1</td><td></td></tr>";
		        }
	     }
		 unset($kc);
}
$table_p.="</table>";
echo $table_p;
unset($job_p_a);
unset($job_p_b);

function trav_para($item_id,$revision_id,$step_code,$conn,$root_id)
{	      
          $rs_trav_o = "select job_name, root_id,process_name,item_id, revision_id, operation_code, description, traveler_ordering_index, sequential_index, order_num from rpt_job_trav_sect_list where  root_id=".$root_id ." and  proc_item_id=".$item_id." and proc_revision_id=".$revision_id. " and operation_code='".$step_code."' order BY order_num, traveler_ordering_index";

		  $rs_trav = oci_parse($conn, $rs_trav_o);
          oci_execute($rs_trav, OCI_DEFAULT);
          $row = oci_fetch_array($rs_trav, OCI_RETURN_NULLS);
		  $para.= $row['OPERATION_CODE'].'     '.$row['DESCRIPTION'].';';
		  $rs_attr_r = "select description, value_as_string from  attr_trav_sect where item_id= ".$row["ITEM_ID"] ." and revision_id= ".$row["REVISION_ID"]."and SECTION_SEQUENTIAL_INDEX= ".$row["SEQUENTIAL_INDEX"]."order by section_ordering_index";
		        $rs_attr = oci_parse($conn, $rs_attr_r);
                oci_execute($rs_attr, OCI_DEFAULT);
                while ($row_a = oci_fetch_array($rs_attr, OCI_RETURN_NULLS)){
				     $para.= $row_a['DESCRIPTION'].': '.$row_a['VALUE_AS_STRING'].';';
				
				}
	

    return $para;
}

function Para($job_name,$conn){
   
$two_layer="select item_name ,item_id, last_checked_in_rev  from items where root_id=(select root_id from items where item_name='".$job_name."' and item_type=2) and item_type=7 and item_name not like '%main%' and item_name not like '%Material Cut%'";

$p_name = oci_parse($conn, $two_layer);
oci_execute($p_name, OCI_DEFAULT);
$row = oci_fetch_array($p_name, OCI_RETURN_NULLS);
if ($row['ITEM_NAME']=='Final Assembly - 1/2'){
    $job_process=array($row['ITEM_NAME']=>array($row['ITEM_ID'],$row['LAST_CHECKED_IN_REV']));
}else{
	$pro_name="select a.item_name, b.item_name as process_name  ,a.item_id, a.last_checked_in_rev ,b.item_id as id , b.last_checked_in_rev as rev ,a.root_id as root_id from items a, items b ,public_links ln  
where a.item_id =ln.item_id and ln.points_to=b.item_id and ln.link_type=8 and  a.root_id=(select root_id from items where item_name='".$job_name."' and item_type=2)  and a.item_name not like '%main%'";
$p_name = oci_parse($conn, $pro_name);
oci_execute($p_name, OCI_DEFAULT);
$ip_c=0;
while ($row_r = oci_fetch_array($p_name, OCI_RETURN_NULLS)){	 
          if ($ip_c==0) {
		  $process_p=array($row_r['ITEM_NAME']=>array($row_r['PROCESS_NAME']));
		  $process_c=array($row_r['PROCESS_NAME']=>array($row_r['ID'],$row_r['REV']));
		  $job_process=array($row_r['ITEM_NAME']=>array($row_r['ITEM_ID'],$row_r['LAST_CHECKED_IN_REV']));
		  $job_process[$row_r['PROCESS_NAME']]=array($row_r['ID'],$row_r['REV']);
          }
		  else{
			 $process_p[$row_r['ITEM_NAME'].'_'.$ip_c]=array($row_r['PROCESS_NAME']);
			 $process_c[$row_r['PROCESS_NAME']]=array($row_r['ID'],$row_r['REV']);
		  }
		  $ip_c=$ip_c+1;
}
foreach($process_c as $key=>$value){
    foreach($process_p as $key1=>$value1){
		   if (substr($key1,0,strlen($key1)-2)==$key){
			$job_process[$value1[0]]=array($process_c[$value1[0]][0],$process_c[$value1[0]][1]);
			if (strpos($value1[0],"Assembly")>0){
			   foreach($process_p as $key2=>$value2){
			      if (substr($key2,0,strlen($key2)-2)==$value1[0]){
		    	  $job_process[$value2[0]]=array($process_c[$value2[0]][0],$process_c[$value2[0]][1]);}
			   }			
			   }
		   }
	 }
	 if (!in_array($value[0],$job_process)&&!in_array($value[1],$job_process)){
		 $job_process[$key]=array($value[0],$value[1]);
		 }
}
unset($process_c);
unset($process_p);
}
//print_r($job_process);
//echo '<br>'.'<br>';
foreach($job_process as $key=>$value){
	    //  echo $key.'<br>';
		  $rs_trav_o = "select job_name, root_id,process_name,item_id, revision_id, operation_code, description, traveler_ordering_index, sequential_index, order_num from rpt_job_trav_sect_list where  proc_item_id=".$value[0]." and proc_revision_id=".$value[1]." order BY order_num, traveler_ordering_index";
		  $rs_trav = oci_parse($conn, $rs_trav_o);
          oci_execute($rs_trav, OCI_DEFAULT);
         
          while ($row = oci_fetch_array($rs_trav, OCI_RETURN_NULLS)){
			    //echo $row['OPERATION_CODE'].'     '.$row['DESCRIPTION'].'<br>';
               
			    $rs_attr_r = "select description, value_as_string from  attr_trav_sect where item_id= ".$row["ITEM_ID"] ." and revision_id= ".$row["REVISION_ID"]."and SECTION_SEQUENTIAL_INDEX= ".$row["SEQUENTIAL_INDEX"]."order by section_ordering_index";
		        $rs_attr = oci_parse($conn, $rs_attr_r);
                oci_execute($rs_attr, OCI_DEFAULT);
				$p_para='';
                while ($row_a = oci_fetch_array($rs_attr, OCI_RETURN_NULLS)){
				     $p_para.= $row_a['DESCRIPTION'].': '.$row_a['VALUE_AS_STRING'].'#';		
				}
			   $sub_p[$row['OPERATION_CODE'].'      '.$row['DESCRIPTION']]=explode('#',$p_para);
		  }
		  $job_para[$key]=$sub_p;
		  unset($sub_p);
}
  return $job_para;
}


?>
</html>