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
	   }else {
	     $groupname = test_input($_POST["username"]);
	 }
	if (checkExist($conn,$groupname)==true){
		$groupnameErr = "Group exist in your account already! Try different name!";
	}else{
		$groupID=(int)groupID($conn)+1;
		$sql="Insert into groups values('".$groupID."','".$name."','".$groupname."',sysdate)";
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

function checkExist($conn,$groupname){
	 //sql command
           $sql = "SELECT group_name FROM groups where user_name='".$name."'";
          
           
           //Prepare sql using conn and returns the statement identifier
           $id = oci_parse($conn, $sql);
           
           //Execute a statement returned from oci_parse()
           $res=oci_execute($id); 
           
           //if error, retrieve the error using the oci_error() function & output an error
           if (!$res) {
		$err = oci_error($id);
		echo htmlentities($err['message']);
           }
       
           
   	
    //Display extracted rows
	   while ($row = oci_fetch_array($id, OCI_ASSOC)) {
	   	
		foreach ($row as $item) {
			if ($item == $groupname){
					return true;			
			}
		}
	   }
}
?>
<FORM NAME="Create Groups" METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"><CENTER>
		<TABLE>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Group Name:</I></B></TD>
		<TD><INPUT TYPE="text" NAME="goupname">
		<span class="error"><?php echo $groupnameErr;?></span>
		<BR><BR>
		</TD>
		</TR>
		</TABLE>
		<INPUT TYPE="submit" NAME="Submit" VALUE="Create">
		</CENTER>
		</FORM>
</body>
</html>
