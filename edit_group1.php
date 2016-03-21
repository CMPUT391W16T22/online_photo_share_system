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
<script>
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","delete_friend.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
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
storeGroupId($conn,$q);
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
<?php
	session_start();
	$q=$_SESSION['group_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"])) {
	     $usernameErr = "Enter username a name";
	     $empty=1;
	   }else {
	     $username = $_POST["username"];
	 }
	 $notice=$_POST["notice"];
	 	$checkf=checkFriends($conn,$username);
	    $check=checkExist($conn,$q,$username);
	if ($check==1){
		$usernameErr = "Friend exist in your group already! Try different username!";
	}elseif ($check==2 and $empty !=1 and $checkf!=2){
		$sql="Insert into group_lists values('".$q."','".$username."',sysdate,'".$notice."')";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		$r = oci_commit($conn);
		header("Refresh:0");
	}elseif ($checkf==2) {
		$usernameErr = "Username does not found!";
	}
}
function checkFriends($conn,$username){
	$sql = "SELECT User_name FROM USERS ";
	$id = oci_parse($conn, $sql);
   //Execute a statement returned from oci_parse()
   $res=oci_execute($id); 
   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
			if ($item == $username){
					return 1;			
			}
		}
   }
   return 2;
}
function storeGroupId($conn,$q){
	$sql="SELECT GROUP_ID FROM GROUPS ";
	$result = oci_parse($conn,$sql);
	$re=oci_execute($result); 
    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		foreach ($row as $item) {
			if ($item == $q){
					session_start();
					$_SESSION['group_id']=$q;
					return;			
			}
		}
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
<form NAME="Delete group" METHOD="post" action="delete_group.php">
<INPUT TYPE="submit" NAME="Submit" VALUE="Delete Group">
</form>
<form NAME="Delete friend" METHOD="post" action="delete_friend.php">
<INPUT TYPE="submit" NAME="Submit" VALUE="Delete Friend">
</form>
<form NAME="Back" METHOD="post" action="edit_groups.php">
<INPUT TYPE="submit" NAME="Submit" VALUE="Back">
</form>
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
<div id="txtHint"><b></b></div>
</body>
</html>