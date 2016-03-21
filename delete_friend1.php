<?php
include ("PHPconnectionDB.php"); 
$q= $_POST['delete_friend']; 
echo $q;
session_start();
$group_id=$_SESSION['group_id'];
$conn=connect();
$sql="DELETE FROM group_lists WHERE friend_id = '".$q."' and group_id = '".$group_id."'";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
$r = oci_commit($conn);
header('Location: delete_friend.php');
?>
