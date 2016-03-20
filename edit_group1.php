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

$sql="SELECT FRIEND_ID, DATE_ADDED, NOTICE FROM GROUP_LISTS WHERE GROUP_ID = '".$q."'";
$result = oci_parse($conn,$sql);
$re=oci_execute($result);
echo "<table>
<tr>
<th>Username</th>
<th>Date_Added</th>
<th>Notice</th>
</tr>";
while ($row = oci_fetch_array($result, OCI_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['FRIEND_ID'] . "</td>";
    echo "<td>" . $row['DATE_ADDED'] . "</td>";
    echo "<td>" . $row['NOTICE'] . "</td>";
    echo "</tr>";
}
echo "</table><br>";
?>
<?php
echo "djkahfafjkadfal";
echo $q;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"])) {
	     $usernameErr = "Enter username a name";
	     $empty=1;
	   }else {
	     $username = $_POST["username"];
	 }
	    $check=checkExist($conn,$q,$username);
	if ($check==1){
		$usernameErr = "Friend exist in your group already! Try different username!";
	}elseif ($check==2 and $empty !=1){
		$sql="Insert into group_lists values('".$q."','".$username."',sysdate,'".$notice."')";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		$r = oci_commit($conn);
	}
}
function checkExist($conn,$goup_id,$name){
	 //sql command
   $sql = "SELECT friend_id FROM group_lists where group_id='".$goup_id."'";
   //Prepare sql using conn and returns the statement identifier
   $id = oci_parse($conn, $sql);
   //Execute a statement returned from oci_parse()
   $res=oci_execute($id); 
   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
			if ($item == $name){
					return 1;			
			}
		}
   }
   return 2;
}
?>
<form NAME="LoginForm" METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<TR VALIGN=TOP ALIGN=LEFT>
		<H3>Enter username you want to add!</H3>
		<TD><B><I>Username:</I></B></TD>
		<TD><INPUT TYPE="text" NAME="username">
		<span class="error"><?php echo $usernameErr;?></span>
		<br><br>
		<TD><B><I>Notice:</I></B></TD> 
		<TD><textarea name="notice" rows="5" cols="40" value = "<?php echo $notice;?>"></textarea>
		</TD></TD>
		<br><br>
		</TR>
		<INPUT TYPE="submit" NAME="Submit" VALUE="Add">
</form>
<form>
<select name="users" onchange="showUser(this.value)">
<option value="">Select a friend to delete:</option>
<?php 
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
$sql= "SELECT FRIEND_ID FROM GROUP_LISTS where GROUP_ID='".$q."'";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		echo "<option value=''>" . $row['FRIEND_ID'] . "</option>";
			}
?>
</select>
</form>
<br>
<div id="txtHint"><b></b></div>
</body>
</html>