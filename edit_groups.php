<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<tr>
<H1>Select a group that you want to edit!</H1>
<br>
<td>
<select name="groups">
<?php 
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
session_start();
$name=$_SESSION['userid'];
$sql= "SELECT group_name FROM groups where user_name='".$name."'";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		echo "<option value=\"groups1\">" . $row['GROUP_NAME'] . "</option>";
			}
?>
</select>
</td>
</tr>
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