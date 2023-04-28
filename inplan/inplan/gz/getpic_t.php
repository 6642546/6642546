<?php
$site="GZ";
if (isset($_GET['root_id'])) $root_id = $_GET["root_id"];
$sql="select p.blob_data from items a, stackup s ,rev_controlled_lob p where a.item_type=9 and a.item_id=s.item_id and a.last_checked_in_rev =s.revision_id and s.image=p.lob_id and a.root_id=$root_id";
require("../../oracle_conn.php");
$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$img = $row['BLOB_DATA']->load();
header("Content-type: image/jpeg");
print $img;


?>