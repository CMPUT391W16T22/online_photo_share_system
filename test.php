<html>
    <body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
	echo "fjklfdhklfsdklfsdl";
	$conn=oci_connect('xinchao','wang0408');
	if($conn)
		echo "Connection succeded";
	else
	{
		echo "Connection failed";
	    $err = oci_error();
		trigger_error(htmlentities($err['message'], ENT_QUOTES), E_USER_ERROR);	
	}

	if (empty($_POST["username"])) {
     $usernameErr = "UserName is required";
   } else {
     $username = test_input($_POST["username"]);

    if (empty($_POST["password"])) {
     $passwordErr = "password is required";
   } else {
     $password = test_input($_POST["password"]);

	$query = "SELECT * from users WHERE username='".$username."' and password='".$password."'";
	 //Store resultsof select query
     $result = OCIParse($connect, $query);
     
     //Just check
     //$sql = OCIParse($connect, $query);
     if($result) {
          echo "^^^^An error occurred in parsing the sql string '$query'.\n";
          exit;
     function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
    ?>
      <FORM NAME="LoginForm" METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"><CENTER>

		<P><CENTER>To login successfully, you need to submit a valid userid and password</CENTER></P>
		<TABLE>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Username:</I></B></TD>
		<TD><INPUT TYPE="text" NAME="username" values="">
		<BR></TD>
		</TR>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Password:</I></B></TD>
		<TD><INPUT TYPE="password" NAME="password" values=""></TD>
		</TR>
		</TABLE>
		<INPUT TYPE="submit" NAME="Submit" VALUE="LOGIN">
		</CENTER>
		</FORM>
		<FORM NAME="Sign up" ACTION="signUp.php" METHOD="post" ><CENTER>
		<INPUT TYPE="submit" NAME="Submit" VALUE="SIGN UP">
		</CENTER>
		</FORM>
	</body>
</html>