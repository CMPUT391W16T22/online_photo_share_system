<!DOCTYPE html>
<html>

<head>
	<title>Create new friends!!!</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #0000cc;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: #111;
}
</style>
</head>
<body style="background-image: url('create-bg.jpg')">
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
		echo $groupid;
		$sql="Insert into groups values('".$groupid."','".$name."','".$groupname."',sysdate)";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		$r = oci_commit($conn);
		header('Location: edit_groups.php');

	}
}
function groupID($conn){
	$sql= "SELECT group_id FROM groups";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	$temp=0;
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		foreach ($row as $item) {
					if ($item>$temp){
						$temp=$item;
					}
				}
			}
			return $temp;
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
<ul>
  <li><a class="active" href="costomer.php">Home</a></li>
  <li><a href="myphoto.php">My Photo</a></li>
  <li><a href="sUpload.php">Upload Photo</a></li>
  <li><a href="create_groups.php">Create new groups</a></li>
  <li><a href="edit_groups.php">Editing existing groups</a></li>
  <li><a href="index.php">Sign out</a></li>	
  <div style="float:right;" ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
</ul>
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
