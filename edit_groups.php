<html>
<head>
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
</head>
<body style="background-image: url('edit-bg.jpg')">
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="create_groups.php">Create new groups</a>
                    </li>
                    <li>
                        <a href="costomer.php">Main page</a>
                    </li>
                    <li>
                        <a href="index.php">Sign out</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
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