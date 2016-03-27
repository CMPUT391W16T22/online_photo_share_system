<?php



$conn = oci_connect('xinchao', 'wang0408');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	

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


