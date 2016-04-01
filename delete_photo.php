<?php
include ("PHPconnectionDB.php");
$conn=connect();  
session_start();
$id=$_SESSION["pid"];
echo $id;
$sql="DELETE FROM IMAGES WHERE PHOTO_ID = '".$id."'";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
$r = oci_commit($conn);
header('Location: myphoto.php');
?>