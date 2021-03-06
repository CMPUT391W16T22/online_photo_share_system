<html>
<head>
	 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Di Meng" >

    <title>IpicShare</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
<style>
form {display: inline-block;}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #ffff00;
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
<script>
$(function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
  });
  </script>
</head>

<body>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <ul>
			  <li><a class="active" href="#home">Home</a></li>
			  <li><a href="myphoto.php">My Photo</a></li>
			  <li><a href="sUpload.php">Upload Photo</a></li>
			  <li><a href="create_groups.php">Create new groups</a></li>
			  <li><a href="edit_groups.php">Editing existing groups</a></li>
			  <li><a href="index.php">Sign out</a></li>	
			  <div ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
			</ul>
			
<?php

include("PHPconnectionDB.php");
session_start();

$user_name = $_SESSION['userid'];
$conn = connect();
$sql = "SELECT group_id, group_name FROM groups WHERE user_name='".$user_name."'";
$sql2 = "SELECT group_id, group_name FROM groups WHERE user_name IS NULL";
$stid = oci_parse( $conn, $sql );
$stid2 = oci_parse( $conn, $sql2);
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
oci_close($conn);
?>




    <legend>Uploading Folders</legend>
    <form name="upload-files" method="post" action="uploadAll.php" enctype="multipart/form-data">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <?php
        if ($_GET['ACK']==1) echo "<div id='success-show' style='color:#0000FF'>Successful uploading. Please upload another folder.</div>" ;
        elseif ($_GET['ACK']== -1) echo "<div id='success-show' style='color:#FF0000'>Cannot upload photos. Please try again.</div>" ;
        ?>
        <div class='half' >
            <strong> Select Folder</strong><br>
            <input directory="" webkitdirectory="" mozdirectory="" directory name="file[]" type="file" id="upload-file" ><br>
        </div>
        <div class='half' >
            <strong>Select group </strong><br>
            <div id='t2' >
                <select name='group-name'>
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
        <div class='half' >
            <strong>Input Date (Optional)</strong><br>
            <input type="text" name="date-input" placeholder="Enter date: dd/mm/yyyy hh24:mi:ss" >
        </div>
        <div class = 'half' >
            <strong>Input Subject (Optional)</strong><br>
            <input type="text" name="title" placeholder="Enter title here..." >
        </div>
        <div class='half' >
            <strong>Input Photo Taken Place (Optional)</strong><br>
            <textarea name="place" placeholder="Enter place here..." ></textarea>
        </div>
        <div class='half' >
            <strong> Input Description (Optional)</strong><br>
            <textarea name="description" placeholder="Enter description here..." ></textarea>
        </div>
        <span id="lblError" ></span>
        <input value="Upload" name="button" id="upload-button" type="submit" />
    </form>


<script type="text/javascript">
    function hideMessage() {
        var successShow = $("#success-show");
        successShow.html('<br>');
    };
    setTimeout(hideMessage, 5000);
    /* check file when submit */
    $("body").on("click", "#upload-button", function () {
        var lblError = $("#lblError");
        var oFile = document.getElementById('upload-file');
        if (oFile.value == ""){
            lblError.html("Please choose a file to upload");
            return false;
        }
        var allowedFiles = [".jpg", ".jpeg", ".gif"];
        var fileUpload = $("#upload-file");
        //var fileSize = this.files[0].size;
        var fileSize = $('#upload-file')[0].files[0].size;
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!(regex.test(fileUpload.val().toLowerCase()) && fileSize < 104857600 )) {
            lblError.html("Please upload files less than 100 MB with extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
            return false;
        }
        lblError.html('');
        return true;
    });
    document.getElementById('upload-file').addEventListener('change', checkFile, false);
    approveletter.addEventListener('change', checkFile, false);
    function checkFile(e) {
        var file_list = e.target.files;
        for (var i = 0, file; file = file_list[i]; i++) {
            var sFileName = file.name;
            var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
            var iFileSize = file.size;
            var iConvert = (file.size / 104857600).toFixed(2);
            if (!(sFileExtension === "jpeg" ||sFileExtension === "jpg"|| sFileExtension === "gif" ) || iFileSize > 104857600) {
                txt = "File type : " + sFileExtension + "\n\n";
                txt += "Size: " + iConvert + " MB \n\n";
                txt += "Please make sure your all photos are in jpg or jpeg or gif format and less than 100 MB total.\n\n";
                alert(txt);
            }
        }
    }
</script>
</body>
</html>