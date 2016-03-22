<?php
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
session_start();
$q=$_SESSION['group_id'];
echo $q;
$check=checkGroup($conn,$q);
if ($check==1) {
	$sql="DELETE FROM group_lists WHERE group_id = '".$q."'";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}
$checkImage=checkImage($conn,$q);
if ($checkImage==1){
	$sql="UPDATE IMAGES SET PERMITTED = 1 WHERE permitted = '".$q."'";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}
$sql="DELETE FROM Groups WHERE group_id = '".$q."'";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
$r = oci_commit($conn);
header('Location: edit_groups.php');
function checkImage($conn,$q){
	$sql = "SELECT permitted FROM images where permitted='".$q."'";
   //Prepare sql using conn and returns the statement identifier
   $id = oci_parse($conn, $sql);
   //Execute a statement returned from oci_parse()
   $res=oci_execute($id); 
   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
					return 1;			
			}
		}
   
   return 2;
}
function checkGroup($conn,$q){
	 //sql command
   $sql = "SELECT friend_id FROM group_lists where group_id='".$q."'";
   //Prepare sql using conn and returns the statement identifier
   $id = oci_parse($conn, $sql);
   //Execute a statement returned from oci_parse()
   $res=oci_execute($id); 
   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
					return 1;			
			}
		}
   
   return 2;
}
?>
