# this is the interface for showing only my uploaded photoes

<!DOCTYPE html>
</html>
<html>
<head>
	<title>My photo</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<style>
form {display: inline-block;}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #99ff99;
}

li {
    float: left;
}

li a {
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: #ffffff;
}
</style>
<body>
<ul>
  <li><a class="active" href="costomer.php">Home</a></li>
  <li><a href="myphoto.php">My Photo</a></li>
  <li><a href="sUpload.php">Upload Photo</a></li>
  <li><a href="create_groups.php">Create new groups</a></li>
  <li><a href="edit_groups.php">Editing existing groups</a></li>
  <li><a href="index.php">Sign out</a></li>	
  <div style="float:right;" ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
</ul>
<?php                    
	include("PHPconnectionDB.php");
    session_start();
    $user_name = $_SESSION['userid'];
    $conn = connect();
	$sql = "SELECT photo_id from IMAGES Where OWNER_NAME= '".$user_name."'";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	oci_close($conn);
	echo "<br>";
	while (($row = oci_fetch_array($a, OCI_BOTH))) {
		#echo $row[0];
		 $id =$row[0];
		 session_start();

	    echo $id;
		 #echo $_SESSION['pid'];
		 
		 echo "<a href='myImage.php?pid=".$id.$user_name." onclick='test()' name=$id><img src='1.php?id=$id'></a><br><br>";	
	}
	 ?>
</body>
</html>