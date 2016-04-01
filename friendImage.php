<html>
<body>

<?php
# display the full size image with its info


function checkExist($conn,$name,$x,$y,$id){
	

	 //sql command
           $sql='SELECT '.$x.' FROM '.$y." WHERE PHOTO_ID='$id'";
           
          
           
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
					return 1;			
			}
		}
	   }
	   

}

$test =  $_GET['pid'];

$id = (int) $_GET['pid'];

include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
	

$sql = "SELECT * FROM images WHERE photo_id = ". (int)$id;

$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);



echo "<img src='2.php?fid=$id'>";

$user = preg_replace('/[0-9]+/', '', $test);
echo $user;





if (!$row) {
    
    header("Content_Type: image/jpeg");
    echo "not found";
    
} else {
#	 print_r($row);
    $name = $row['OWNER_NAME'];
    $subject = $row['SUBJECT'];
    $place = $row['PLACE'];
    $time = $row['TIMING'];
    $des = $row['DESCRIPTION'];
    echo "<br>";
    echo "owener: ".$name."<br>";
    echo "subject: ".$subject."<br>";
    echo "place: ".$place."<br>";
    echo "time: ".$time."<br>";
    echo "describtion: ".$des."<br>";
  
    
    
    
    #insert into imagecont for click the image by user
    
    $t = checkExist($conn,$user,'owner_name','imagecont',$id);
    
    if(empty($t)){
    $sql1 = "insert into imagecont VALUES ('".$id."','".$user."')";
	 $stid1 = oci_parse($conn, $sql1);
	 oci_execute($stid1);
	 oci_commit($conn);
	 echo "the cont add into the table";	 
	 }
	 else{
			echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";	 
	 }
    
}







?>

</body>
</html>