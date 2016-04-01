
#this is the interface for signup
<!DOCTYPE HTML>

<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php

include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
               
          


$GLOBALS['test'] = 1;


// define variables and set to empty values
$usernameErr = $last_nameErr = $first_nameErr = $emailErr = $passwordErr = $phoneErr = "";
$username = $last_name = $first_name = $email = $password = $address = $phone = "";
$x = "user_name";
$y = "users";
$a = "email";
$b = "persons";


#check if the input is empty or conflict with other constrains
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["username"])) {
     $usernameErr = "User Name is required";
     $test = 0;
   } 
   
   elseif( checkExist($conn,$_POST["username"],$x,$y)==true){
		   $usernameErr = "User Name is existed";
			$test = 0;   
   
   }else{
		$username = $_POST["username"];   
   }
		 
   
   
   
   if (empty($_POST["last_name"])) {
     $last_nameErr = "Last Name is required";
     $test = 0;
   } else {
     $last_name = test_input($_POST["last_name"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$last_name)) {
       $last_nameErr = "Only letters and white space allowed";
       $test = 0;
     }
   }
   if (empty($_POST["first_name"])) {
     $first_nameErr = "First Name is required";
     $test = 0;
   } else {
     $first_name = test_input($_POST["first_name"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$first_name)) {
       $first_nameErr = "Only letters and white space allowed";
       $test = 0;
     }
   }
  
   if (empty($_POST["email"])) {
     $emailErr = "Email is required";
     $test = 0;
     
     // check if e-mail address is well-formed
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailErr = "Invalid email format";
       $test = 0;
     }
   }
	elseif(checkExist($conn,$_POST["email"],$a,$b)==true) {
					$emailErr = "Email is exist";
     				$test = 0;
		}   
   else{
	$email = test_input($_POST["email"]);   
   }

   
	if (empty($_POST["password"])) {
     $passwordErr = "Password is required";
   }else {
     $password = test_input($_POST["password"]);
     #echo $password;
     #echo "<br>";
 	}	   
   
   
   

   if (empty($_POST["address"])) {
     $address = "";
   } else {
     $address = test_input($_POST["address"]);
   }
   

  if(empty($_POST["phone"])){   	
     $phoneErr = "phone number is required";
     $test = 0;
		
   }
   else {
			$phone = $_POST["phone"];    
       }

}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

#check if the select item is exist in DB  
function checkExist($conn,$name,$x,$y){
	

	 //sql command
           $sql = 'SELECT '.$x.' FROM '.$y;
          
           
           //Prepare sql using conn and returns the statement identifier
           $stid = oci_parse($conn, $sql);
           
           //Execute a statement returned from oci_parse()
           $res=oci_execute($stid); 
           
           //if error, retrieve the error using the oci_error() function & output an error
           if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
           }
           
   	
    //Display extracted rows
	   while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
	   	
		foreach ($row as $item) {
			if ($item == $name){
					return true;			
			}
		}
	   }
	   
	
     	
 

}
?>

# ask user for information
<h2>Please fill up the form below</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   UserName: <input type="text" name="username" value="<?php echo $username;?>">
   <span class="error">* <?php echo $usernameErr;?></span>
   <br><br>
   First Name: <input type="text" name="first_name" value="<?php echo $first_name;?>">
   <span class="error">* <?php echo $first_nameErr;?></span>
   <br><br>
   Last Name: <input type="text" name="last_name" value="<?php echo $last_name;?>">
   <span class="error">* <?php echo $last_nameErr;?></span>
   <br><br>

	password: <input type="password" name="password" value="<?php echo $password;?>">
   <span class="error">*<?php echo $passwordErr;?></span>
   <br><br>   
   
   E-mail: <input type="text" name="email" value="<?php echo $email;?>">
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   Phone Number: <input type="text" name="phone" value="<?php echo $phone;?>">
   <span class="error">*<?php echo $phoneErr;?></span>
   <br><br>
   Address: <textarea name="address" rows="5" cols="40" value = "<?php echo $address;?>"></textarea>
   <br><br>

   <input type="submit" name="submit" value="Submit">
</form>


# upload to DB

<?php
if ($test==1){
	
   $sql="Insert into users values('".$username."','".$password."',sysdate)";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);   
	
	
	$sql="Insert into persons values('".$username."','".$first_name."','".$last_name."','".$address."','".$email."','".$phone."')";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);    
	
	
           
           
	header('Location: index.php');}
    
?>

</body>
</html>