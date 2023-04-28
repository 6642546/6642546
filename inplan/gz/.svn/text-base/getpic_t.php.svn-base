<?php
$site="GZ";
if (isset($_GET['root_id'])) $root_id = $_GET["root_id"];
if (isset($_GET['pic_type'])) $pic_type= $_GET["pic_type"];
switch ($pic_type){
  case 'layup':
       $sql="select p.blob_data from items a, stackup s ,rev_controlled_lob p where a.item_type=9 and a.item_id=s.item_id and a.last_checked_in_rev =s.revision_id and s.image=p.lob_id and a.root_id=$root_id";
       break;
  case 'panel':
       $sql="select a.text ,p.blob_data from rpt_job_snap_note_list a, rev_controlled_lob p where  a.annotated_picture=p.lob_id and a.root_id=$root_id and snap_note_name like '%Dimensions_Material%' ";
       break;
   case 'cut_pic':
       $sql=  "select blob_data  from  rev_controlled_lob 
           where  lob_id=$root_id";
        break;
		}
require("../../oracle_conn.php");
$stid = oci_parse($conn, $sql);
oci_execute($stid);
while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {
    $img = $row['BLOB_DATA']->load();
    header("Content-type: image/jpeg");
    print $img;
}

?>