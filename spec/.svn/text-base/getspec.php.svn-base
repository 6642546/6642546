<?php
if (isset($_GET['spec_name'])){
	$spec_name=$_GET['spec_name'];  
}else{
$spec_name='';
}

require("oracle_conn.php");


$query = "select * from RPT_SPEC_LIST rsl,revisions r,users u
		  where spec_name='$spec_name' and rsl.item_id=r.item_id and r.revision_id=rsl.revision_id and nvl(r.checked_in_by,r.checked_out_by)=u.user_id";


$stid = oci_parse($conn, $query);
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

while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
	$spec_type=$row['TYPE_T'];
	$priority=$row['PRIORITY'];
	$sub_type=$row['SUBTYPE_T'];
	$description=$row['DESCRIPTION'];
	$itar=$row['ITAR_FLAG_'];
	$last_update=$row['CHECK_IN_OUT_DATE'];
	$updated_by=$row['USER_NAME'];
	if ($itar==0){
		$itar='False';
	}else{
		$itar='True';
	};
	$revision=1;
	if (strpos(strtolower($description),'rev')>0){
		$revision=substr($description,strpos(strtolower($description),'rev')+3,5);
	}

} 
$query = "select *
			from RPT_SPEC_CRITERIA_LIST rscl,attributes att
			where rscl.spec_name='$spec_name' and rscl.ATTRIBUTE_ID=att.ATTR_ID(+) ";

$where_text="";
if ($search_text){
	$sub_text = explode(",", $search_text);
	for ($i = 0; $i < count($sub_text); $i++) {
		if ($sub_text[$i]=='Array Panel'){
			$where_text=$where_text."'Array & Panel'".',';
		}elseif ($sub_text[$i]=="'Stackup/Impedance'"){
			$where_text=$where_text."'Stackup/Impedance'".','."'Coup,TDR',";
		}elseif ($sub_text[$i]=="'Mask/Leg/Plug'"){
			$where_text=$where_text."'Mask/Leg/Plug'".','."'Soldermask,Legend',";
		}elseif ($sub_text[$i]=='Innerlayer Outerlayer'){
			$where_text=$where_text."'Innerlayer'".','."'Outerlayer','IL AOI','IL Etch Inspect','OL AOI','OL Etch','Innerlayers','Outerlayers',";
		}elseif ($sub_text[$i]=='Testing'){
			$where_text=$where_text."'Testing'".','."'Electrical Test','Electroless Inspection','Final Hipot','Final Inspection','Serialization','Lab',";
		}else{
			$where_text=$where_text."'".$sub_text[$i]."',";
		}
	}
	$where_text=substr($where_text,0,strlen($where_text)-1);
}

if($where_text){

	$query=$query." and rscl.category_t in (".$where_text.")";
}

if ($search_site){
	$site_query="(";
	$search_sites = split(',',$search_site);
	for ($i=0;$i<count($search_sites);$i++){
		//echo $search_sites[$i];
		$site_query=$site_query. " rscl.PREMISE like '%SITE_NAME_%$search_sites[$i]%' or";
	}
	$site_query=rtrim($site_query,"or").")";
	$query=$query . " and (rscl.PREMISE is null or rscl.PREMISE not like '%SITE_NAME_%' or $site_query)";
}



$order = " order by (case when rscl.PREMISE like '%SITE_NAME_%' then 'C' when rscl.category_t='General' then 'A' else 'B' end),rscl.category_t,rscl.description";

$query = $query . $order;

//echo $query;

$stid = oci_parse($conn, $query);
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

	$query = "select * from RPT_SPEC_NOTE_LIST
			where spec_name='$spec_name' ";


$where_text="";
if ($search_text){
	$sub_text = explode(",", $search_text);
	for ($i = 0; $i < count($sub_text); $i++) {
		if ($sub_text[$i]=='Array Panel'){
			$where_text=$where_text."'Array & Panel'".',';
		}elseif ($sub_text[$i]=="'Stackup/Impedance'"){
			$where_text=$where_text."'Stackup/Impedance'".','."'Coup,TDR',";
		}elseif ($sub_text[$i]=="'Mask/Leg/Plug'"){
			$where_text=$where_text."'Mask/Leg/Plug'".','."'Soldermask,Legend',";
		}elseif ($sub_text[$i]=='Innerlayer Outerlayer'){
			$where_text=$where_text."'Innerlayer'".','."'Outerlayer','IL AOI','IL Etch Inspect','OL AOI','OL Etch','Innerlayers','Outerlayers',";
		}elseif ($sub_text[$i]=='Testing'){
			$where_text=$where_text."'Testing'".','."'Electrical Test','Electroless Inspection','Final Hipot','Final Inspection','Serialization','Lab',";
		}else{
			$where_text=$where_text."'".$sub_text[$i]."',";
		}
	}
	$where_text=substr($where_text,0,strlen($where_text)-1);
}

if($where_text){

	$query=$query." and (CAM_CLASS_T in (".$where_text.") or OPERATION_CLASS_T in (".$where_text."))";
}


if ($search_site){
	$site_query="(";
	$search_sites = split(',',$search_site);
	for ($i=0;$i<count($search_sites);$i++){
		//echo $search_sites[$i];
		$site_query=$site_query. " PREMISE like '%SITE_NAME_%$search_sites[$i]%' or";
	}
	$site_query=rtrim($site_query,"or").")";
	$query=$query . " and (PREMISE is null or PREMISE not like '%SITE_NAME_%' or $site_query)";
}

$order = " order by (case when PREMISE like '%SITE_NAME_%' then 'B' else 'A' end),NOTE_TYPE_T,CAM_CLASS_T,OPERATION_CLASS_T";

$query = $query . $order ;
			$stid1 = oci_parse($conn, $query);
			if (!$stid1) {
				$e = oci_error($conn);
				print htmlentities($e['message']);
				exit;
			}

			$r = oci_execute($stid1, OCI_DEFAULT);
			if(!$r) {
				$e = oci_error($stid1);
				echo htmlentities($e['message']);
				exit;
		    }


oci_close($conn);

?>