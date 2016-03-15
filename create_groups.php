<!DOCTYPE html>
<html>
<head>
	<title>Create new friends!!!</title>
</head>
<body>
<?php
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
session_start();
$name=$_SESSION['userid'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (empty($_POST["groupname"])) {
	     $groupnameErr = "Enter group a name";
	     $empty=1;
	   }else {
	     $groupname = $_POST["groupname"];
	 }
	    $check=checkExist($conn,$groupname,$name);
	if ($check==1){
		$groupnameErr = "Group exist in your account already! Try different name!";
	}elseif ($check==2 and $empty !=1){
		$groupid=(int)groupID($conn)+1;
		$sql="Insert into groups values('".$groupid."','".$name."','".$groupname."',sysdate)";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		$r = oci_commit($conn);
		session_start();
		$_SESSION['group_id']=$groupid;
		$_SESSION['group_name']=$groupid;
		header('Location: edit_groups.php');

	}
}
function groupID($conn){
	$sql= "SELECT COUNT(group_id) FROM groups";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
					return $item;
				}
			}
		}

function checkExist($conn,$groupname,$name){
	 //sql command
   $sql = "SELECT group_name FROM groups where user_name='".$name."'";
   //Prepare sql using conn and returns the statement identifier
   $id = oci_parse($conn, $sql);
   //Execute a statement returned from oci_parse()
   $res=oci_execute($id); 
   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
			if ($item == $groupname){
					return 1;			
			}
		}
   }
   return 2;
}
?>
<FORM NAME="Create Groups" METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"><CENTER>
		<TABLE>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Group Name:</I></B></TD>
		<TD><INPUT TYPE="text" NAME="groupname">
		<span class="error"><?php echo $groupnameErr;?></span>
		<span class="glyphicons glyphicons-user-add"></span>
		<BR><BR>
		</TD>
		</TR>
		</TABLE>
		<INPUT TYPE="submit" NAME="Submit" VALUE="Create">
		</CENTER>
		</FORM>
</body>
</html>
