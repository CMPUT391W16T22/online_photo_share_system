<?php
include ("PHPconnectionDB.php"); 
$search_text=$_POST['search_text']; 
$conn=connect();
$sql="SELECT DISTINCT photo_id  FROM images WHERE subject LIKE '%".$search_text."%' OR place LIKE '%".$search_text."%'OR Description LIKE '%".$search_text."%'";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
while ($row = oci_fetch_array($a, OCI_ASSOC)) {
	#session_start();
	#$_SESSION['group_id']=$row['GROUP_ID'];
    echo "<td>" . $row['PHOTO_ID'] . "</td>";
    echo "<BR>";
}
echo "</table><br>";
?>