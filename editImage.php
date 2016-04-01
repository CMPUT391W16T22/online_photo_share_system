<?php
# update the select image

include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
     $place = $_POST["place"];
     $time = $_POST["time"];
     $des = $_POST["des"];
     $subject = $_POST["subject"];
     $group = $_POST['group-name'];
     echo $place;
     echo "<br>";
     echo $group;
     echo "<br>";
     echo $des;
     echo "<br>";
     echo $subject;
     echo "<br>";
     echo $time;
     session_start();
$id=$_SESSION["pid"];
     
# updating the image info with input 
$sq = "update images set place='".$place."',subject='".$subject."',description='".$des."',permitted='".$group."',timing=to_date('".$time."', 'DD-Mon-YY HH:MI:SS') WHERE photo_id = ". (int)$id;
echo "<br>";
echo $sq;
$aa = oci_parse($conn, $sq);
$reso=oci_execute($aa);
$r = oci_commit($conn);   

header('Location: myphoto.php');
     
  
}




?>