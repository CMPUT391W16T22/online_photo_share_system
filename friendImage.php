<html>

<body>



<?php


$id = (int) $_GET['pid'];

$conn = oci_connect('xinchao', 'wang0408');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	

$sql = "SELECT * FROM images WHERE photo_id = ". (int)$id;

$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);



echo "<img src='2.php?fid=$id'>";

if (!$row) {
    
    header("Content_Type: image/jpeg");
    echo "not found";
} else {
#	 print_r($row);
    $name = $row['OWNER_NAME'];
    $subject = $row['SUBJECT'];
    $place = $row['PLACE'];
    $time = $row['TIMING'];
    $des = $row['DESCRIPTION'];
    echo "<br>";
    echo "owener: ".$name."<br>";
    echo "subject: ".$subject."<br>";
    echo "place: ".$place."<br>";
    echo "time: ".$time."<br>";
    echo "describtion: ".$des."<br>";
    
}

?>

</body>
</html>