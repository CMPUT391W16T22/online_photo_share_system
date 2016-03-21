<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = (int)intval($_GET['q']);
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
#session_start();
#$name=$_SESSION['userid'];
#session_start();
#$_SESSION['group_id']=$q;
#storeGroupId($conn,$q);
session_start();
$q=$_SESSION['group_id'];
$sql="SELECT * FROM GROUP_LISTS WHERE GROUP_ID = '".$q."'";
$result = oci_parse($conn,$sql);
$re=oci_execute($result);
echo "<table>
<tr>
<th>Username</th>
<th>Date_Added</th>
<th>Notice</th>
</tr>";
while ($row = oci_fetch_array($result, OCI_ASSOC)) {
	#session_start();
	#$_SESSION['group_id']=$row['GROUP_ID'];
    echo "<tr>";
    echo "<td>" . $row['FRIEND_ID'] . "</td>";
    echo "<td>" . $row['DATE_ADDED'] . "</td>";
    echo "<td>" . $row['NOTICE'] . "</td>";
    echo "</tr>";
}
echo "</table><br>";
?>
<form method="post" action="delete_friend1.php">
<br><br>
<select name="delete_friend">
<option value="">Select a friend to delete:</option>
<?php 
#include ("PHPconnectionDB.php");        
	   //establish connection
#$conn=connect();
session_start();
$q=$_SESSION['group_id'];
$sql= "SELECT FRIEND_ID FROM GROUP_LISTS where GROUP_ID='".$q."'";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		echo "<option value=". $row['FRIEND_ID'] .">" . $row['FRIEND_ID'] . "</option>";
			}
?>
</select>
<input type="submit" value="Apply" />
</form>

<br>
<form NAME="Back" METHOD="post" action="edit_groups.php">
	<input type="submit" NAME="Submit" value="Back">
</form>
</body>
</html>