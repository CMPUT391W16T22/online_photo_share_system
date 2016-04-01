<html>
<head>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script>
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","edit_group1.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
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
<body style="background-image: url('edit-bg.jpg')">
<ul>
  <li><a class="active" href="costomer.php">Home</a></li>
  <li><a href="myphoto.php">My Photo</a></li>
  <li><a href="sUpload.php">Upload Photo</a></li>
  <li><a href="create_groups.php">Create new groups</a></li>
  <li><a href="edit_groups.php">Editing existing groups</a></li>
  <li><a href="index.php">Sign out</a></li>	
  <div style="float:right;" ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
</ul>
<form>
<select name="groups" onchange="showUser(this.value)">
<option value="">Select a group:</option>
<?php 
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
session_start();
$name=$_SESSION['userid'];
$sql= "SELECT GROUP_ID, GROUP_NAME FROM GROUPS where USER_NAME='".$name."'";
	$id = oci_parse($conn, $sql);
	$res=oci_execute($id); 
	while ($row = oci_fetch_array($id, OCI_ASSOC)) {
		echo "<option value=".$row['GROUP_ID'].">" . $row['GROUP_NAME'] . "</option>";
			}
?>
</select>
</form>
<br>
<div id="txtHint"><b></b></div>

</body>
</html>