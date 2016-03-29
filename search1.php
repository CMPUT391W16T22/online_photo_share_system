<!DOCTYPE html>
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
        xmlhttp.open("GET","search2.php?q="+str,true);
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
    background-color: #0000cc;
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
<select name="view_method" onchange="showUser(this.value)">
<option value="">select:</option>
<option value="0">Default</option>
<option value="1">Most-recent-first</option>
<option value="2">Most-recent-last</option>
</select>
<br>
<div id="txtHint"><b></b></div>
</body>
</html>