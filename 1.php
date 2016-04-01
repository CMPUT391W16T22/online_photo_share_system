<?php
# connect to database and load the image
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();

$sql = "SELECT thumbnail FROM images WHERE photo_id = ". (int) $_GET['id'];;




$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
if (!$row) {
    
    header("Content_Type: image/jpeg");
    echo "not found";
} else {
#	 print_r($row);
    $img = $row['THUMBNAIL']->load();
    header("Content-type: image/jpeg");
    echo $img;
}
?>


