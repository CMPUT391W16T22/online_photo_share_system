<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
// define variables and set to empty values
$usernameErr = $last_nameErr = $first_nameErr = $emailErr = $passwordErr = "";
$username = $last_name = $first_name = $email = $password = $address = $last = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["username"])) {
     $usernameErr = "User Name is required";
   } else {
     $username = test_input($_POST["username"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
       $usernameErr = "Only letters and white space allowed";
     }
   }
   if (empty($_POST["last_name"])) {
     $last_nameErr = "Last Name is required";
   } else {
     $last_name = test_input($_POST["last_name"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$last_name)) {
       $last_nameErr = "Only letters and white space allowed";
     }
   }
   if (empty($_POST["first_name"])) {
     $first_nameErr = "First Name is required";
   } else {
     $first_name = test_input($_POST["first_name"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
       $first_nameErr = "Only letters and white space allowed";
     }
   }
  
   if (empty($_POST["email"])) {
     $emailErr = "Email is required";
   } else {
     $email = test_input($_POST["email"]);
     // check if e-mail address is well-formed
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailErr = "Invalid email format";
     }
   }
    
   if (empty($_POST["password"])) {
     $website = "";
   } else {
     $password = test_input($_POST["password"]);
     // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
     if (!filter_var($password, FILTER_VALIDATE_INT)) {
       $passwordErr = "Invalid number";
     }
   }

   if (empty($_POST["address"])) {
     $address = "";
   } else {
     $address = test_input($_POST["address"]);
   }

}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2>Please fill up the form blew\</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   UserName: <input type="text" name="username" value="<?php echo $username;?>">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   First Name: <input type="text" name="first_name" value="<?php echo $first_name;?>">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   Last Name: <input type="text" name="last_name" value="<?php echo $last_name;?>">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   E-mail: <input type="text" name="email" value="<?php echo $email;?>">
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   password Number: <input type="int" name="password" value="<?php echo $password;?>">
   <span class="error"><?php echo $passwordErr;?></span>
   <br><br>
   Address: <textarea name="address" rows="5" cols="40"><?php echo $address;?></textarea>
   <br><br>

   <input type="submit" name="submit" value="Submit">
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $username;
echo "<br>";
echo $first_name;
echo "<br>";
echo $last_name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
?>

<?php
if (1>0){
header('Location: index.php');}    
?> }

</body>
</html>