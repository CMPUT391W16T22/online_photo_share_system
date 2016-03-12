<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IpicShare</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background-image: url('home-bg.jpg')">

<?php
// define variables and set to empty values
$username = $password = "";
$conn=oci_connect('xinchao','wang0408');
	if($conn)
		pass;
	else
	{
		echo "Connection failed";
	    $err = oci_error();
		trigger_error(htmlentities($err['message'], ENT_QUOTES), E_USER_ERROR);	
	}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty($_POST["username"])) {
     $usernameErr = "UserName is required";
   }else {
     $username = test_input($_POST["username"]);
     #echo $username;
     #echo "<br>";
 	}
    if (empty($_POST["password"])) {
     $passwordErr = "password is required";
   }else {
     $password = test_input($_POST["password"]);
     #echo $password;
     #echo "<br>";
 	}	
	$query =  "SELECT * from users WHERE user_name='".$username."' and password='".$password."'";
	 //Store resultsof select query
     $result = oci_parse($conn,$query);
     $res=oci_execute($result);
     if (!$res) {
		$err = oci_error($result);
		echo htmlentities($err['message']);
           }
           
	   //Display extracted rows
	   while ($row = oci_fetch_array($result, OCI_ASSOC)) {
	   	
		foreach ($row as $item) {
			echo $item.'&nbsp;';
		}
		echo '<br/>';
		$count = OCIRowCount($result); 
	     echo $count;
	     echo "<br>";
	 }
	    if ($count==1 and $username=="admin"){
	    	header('Location: admin.php');
	    }elseif ($count==1) {
	    	header('Location: costomer.php');
	    }else{
	    	$loginErr="Invalid username or password!";
	    }
	   
}
function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
   ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>IpicShare</h1>
                        <hr class="small">
                        <span class="subheading">A smart photo share system</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--Login page-->

		<FORM NAME="LoginForm" METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"><CENTER>

		<P><CENTER>To login successfully, you need to submit a valid userid and password</CENTER></P>
		<TABLE>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Username:</I></B></TD>
		<TD><INPUT TYPE="text" NAME="username">
		<span class="error"><?php echo $usernameErr;?></span>
		<BR><BR>
		</TD>
		</TR>
		<TR VALIGN=TOP ALIGN=LEFT>
		<TD><B><I>Password:</I></B></TD>
		<TD><INPUT TYPE="password" NAME="password">
		<span class="error"><?php echo $passwordErr;?></span>
		<br><br>
		</TD>
		</TR>
		</TABLE>
		<INPUT TYPE="submit" NAME="Submit" VALUE="LOGIN">
		</CENTER>
		</FORM>
		<FORM NAME="Sign up" ACTION="signUp.php" METHOD="post" ><CENTER>
		<INPUT TYPE="submit" NAME="Submit" VALUE="SIGN UP">
		</CENTER>
		</FORM>
		<CENTER><?php echo $loginErr;?></CENTER>
</body>
</html>