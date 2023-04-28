<?php
if (isset($_GET['site'])) $site = $_GET['site'];
if (isset($_GET['job_name'])) $job_name = $_GET["job_name"];
if (isset($_GET['ci_description'])) $ci_description = $_GET["ci_description"];
if (isset($_GET['ts_item_id'])) $ts_item_id = $_GET["ts_item_id"];
if (isset($_GET['sequential_index'])) $sequential_index = $_GET["sequential_index"];
if (isset($_GET['pic_name'])) $pic_name = $_GET["pic_name"];
if (!$conn) require("../../oracle_conn.php");

$query = "select i.item_name
					,ci.description
					,cin.PRE_INSTANTIATED_STRING
					,lob.blob_data
					,ci.SEQUENTIAL_INDEX
			from items i
					,items ici
					,ci_set cs
					,cam_instruction ci
					,ci_note cin
					,ci_snap_note csn
					,snap_note sn
					,rev_controlled_lob lob
			where i.item_type=2 and i.root_id=ici.root_id
			and ici.item_id=cs.item_id
			and ici.last_checked_in_rev=cs.revision_id
			and cs.item_id=ci.item_id
			and cs.revision_id=ci.revision_id
			and ci.item_id=cin.item_id
			and ci.revision_id=cin.revision_id
			and ci.sequential_index=cin.ci_sequential_index
			and ici.DELETED_IN_GRAPH is null
			and ci.item_id=csn.item_id(+)
			and ci.revision_id=csn.revision_id(+)
			and ci.sequential_index=csn.ci_sequential_index(+)
			and (sn.revision_id=(select max(revision_id) from revisions where item_id=sn.item_id)
					or sn.revision_id is null)
			and sn.item_id(+)=csn.snap_note_item_id
			and sn.ANNOTATED_PICTURE=lob.lob_id(+)
			and rownum=1
			and i.item_name='$job_name'
			and ci.description = '$ci_description'";


$query1 = "select lob.blob_data
            from items i
                    ,items its
                    ,TRAV_SECT ts
                    ,TRAV_SECT_SNAP_NOTE tsn
                    ,snap_note sn
                    ,rev_controlled_lob lob
            where i.item_type=2 
			and i.root_id=its.root_id
            and its.item_id=ts.item_id
            and its.last_checked_in_rev=ts.revision_id
            and its.DELETED_IN_GRAPH is null
            and ts.item_id=tsn.item_id(+)
            and ts.revision_id=tsn.revision_id(+)
            and ts.sequential_index=tsn.SECTION_SEQUENTIAL_INDEX(+)
            and (sn.revision_id=(select max(revision_id) from revisions where item_id=sn.item_id)
                    or sn.revision_id is null)
            and sn.item_id(+)=tsn.snap_note_item_id
            and sn.ANNOTATED_PICTURE=lob.lob_id(+)
            and rownum=1
            and i.item_name='$job_name'
			and ts.sequential_index = $sequential_index
            and TS.item_id = $ts_item_id";

$query2 = "select lob.blob_data
			from items i
					,items isn
					,snap_note sn
					,rev_controlled_lob lob
			where i.item_type = 2 
			        and isn.item_type = 55
					and isn.root_id=i.root_id
					and isn.deleted_in_graph is null
					and sn.revision_id=isn.last_checked_in_rev
					and sn.ANNOTATED_PICTURE=lob.lob_id(+)
					and isn.item_name='$pic_name'
					and i.item_name='$job_name'";

if ($ts_item_id) $query = $query1;
if ($pic_name)  $query = $query2;

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
$i = 0;
$ncols = oci_num_fields($stid );
while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
	header("Content-type: image/pjpeg"); 
	if ($row['BLOB_DATA']) echo $row['BLOB_DATA']->load();
} 

?>