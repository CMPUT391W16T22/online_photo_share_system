<?php

$conn = oci_connect('xinchao', 'wang0408');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
$id = 1;	
$sql = "SELECT * FROM images";




$stid = oci_parse($conn, $sql);
oci_execute($stid);
$img;
while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
 print_r($row);
 $img = $row['THUMBNAIL']->load();
 header("Content-type: image/jpeg");
 echo $img;
 echo "<br>";
}




# else {

 #   $img = $row['THUMBNAIL']->load();
  #  header("Content-type: image/jpeg");
   # echo $img;
#}
?>


