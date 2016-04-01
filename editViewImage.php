<?php


include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();


$sql = "SELECT photo FROM images WHERE photo_id = ". (int) $_GET['eid'];;




$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
if (!$row) {
    
    header("Content_Type: image/jpeg");
    echo "not found";
} else {
#	 print_r($row);
    $img = $row['PHOTO']->load();
    header("Content-type: image/jpeg");
    echo $img;
}
?>