<html>
<head>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
ul {
list-style-type: none;
margin: 0;
padding: 0;
overflow: hidden;
background-color: #99ccff;
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
<body>
<ul>
  <li><a class="active" href="costomer.php">Home</a></li>
  <li><a href="myphoto.php">My Photo</a></li>
  <li><a href="upload_file.php">Upload Photo</a></li>
  <li><a href="create_groups.php">Create new groups</a></li>
  <li><a href="edit_groups.php">Editing existing groups</a></li>
  <li><a href="index.php">Sign out</a></li>	
  <div style="float:right;" ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
</ul>


<?php
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
session_start();
$_SESSION["pid"]=$id;
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
	

$sql = "SELECT * FROM images WHERE photo_id = ". (int)$id;

$stid = oci_parse($conn, $sql);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);



echo "<img src='editViewImage.php?eid=$id'>";

$user = preg_replace('/[0-9]+/', '', $test);


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
     
}



 $sqll = "SELECT group_id, group_name FROM groups WHERE user_name='".$name."'";
    $sqll2 = "SELECT group_id, group_name FROM groups WHERE user_name IS NULL";
    $stid = oci_parse( $conn, $sqll );
    $stid2 = oci_parse( $conn, $sqll2);
    $result = oci_execute( $stid );
    $result2 = oci_execute( $stid2 );
    if (!($result2 && $result)){
        header( "location:index.php?ERR=err" );
        exit();
    }
    $all_group_info = array();
    while ($group = oci_fetch_row($stid2)){
        array_push($all_group_info, $group);
    }
    while ($group = oci_fetch_row($stid)){
        array_push($all_group_info, $group);
    }
    oci_free_statement($stid);
    oci_free_statement($stid2);
	
    
    
    
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
     $place = $_POST["place"];
     $time = $_POST["time"];
     $des = $_POST["des"];
     $subject = $_POST["subject"];
     $group = $_POST['group-name'];
     
  
}





?>
<div>
	
    <h1><strong>Editing Photo</strong></h1>
    <form name="upload-files" method="post" action="editImage.php?kid=$id">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <?php
        if ($_GET['ACK']==1) echo "<div id='success-show'>Successful uploading. Please upload another file.</div>" ;
        elseif ($_GET['ACK']== -1) echo "<div id='success-show' >Cannot upload your photo. Please try again.</div>" ;
        ?>
        <div>
            <strong>2. Select Who Can See Your Photos </strong><br>
            <div id='t2' >
                <select name='group-name' >
                    <?php foreach($all_group_info as $info) {
                        if ($info[1] == "private"){
                            echo "<option value='" . $info[0] . "' selected>" . $info[1];
                        }else {
                            echo "<option value='" . $info[0] . "'>" . $info[1];
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div >
            <strong>Input Date (Optional)</strong><br>
            <input type="text" name="time" placeholder="Enter date: dd/mm/yyyy hh24:mi:ss" value="<?php echo $time;?>">
        </div>
        <div class = 'half' >
            <strong>Input Subject (Optional)</strong><br>
            <input type="text"  name="subject" placeholder="Enter title here..." value="<?php echo $subject;?>">
        </div>
        <div >
            <strong>Input Photo Taken Place (Optional)</strong><br>
            <input name="place" placeholder="Enter place here..." value="<?php echo $place;?>">
        </div>
        <div  >
            <strong>Input Description (Optional)</strong><br>
            <input name="des" placeholder="Enter description here..." value="<?php echo $des;?>">
        </div>
        <span id="lblError" ></span>
	      <input value="Edit" name="Editing" type="submit" />   
    </form>
   	 
        <form action="delete_photo.php">
        <input value="Delete" name="Delete" type="submit" />
        </form>
</div>




</body>
</html>