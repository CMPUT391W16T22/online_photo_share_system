<!DOCTYPE html>
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
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
<style>
form {display: inline-block;}
</style>
<script>
$(function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
  });
  </script>
</head>
<body style="background-image: url('costomer-bg.jpg')">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <table>
			<tr>
			<td>
            <form METHOD="post" action="search.php">
            	<INPUT TYPE="text" NAME="search_text"  style="width: 596px; height: 15px;" rows="4" cols="50" >
            	<INPUT TYPE="submit" NAME="Submit" VALUE="Search">
            </form>
            </td>
			</tr>
			</table>
			<form mathod="post">
				<p>From: <input type="text" NAME="from_date" id="datepicker"> To: <input type="text" NAME="to_date" id="datepicker1"></p>
			</form>
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					
					if (empty($_POST["groupname"])) {
					     $groupnameErr = "Enter group a name";
					     $empty=1;
					   }else {
					     $groupname = $_POST["groupname"];
					 }
					    $check=checkExist($conn,$groupname,$name);
					if ($check==1){
						$groupnameErr = "Group exist in your account already! Try different name!";
					}elseif ($check==2 and $empty !=1){
						$groupid=(int)groupID($conn)+1;
						echo $groupid;
						$sql="Insert into groups values('".$groupid."','".$name."','".$groupname."',sysdate)";
						$a = oci_parse($conn, $sql);
						$res=oci_execute($a);
						$r = oci_commit($conn);
						header('Location: edit_groups.php');

					}
			?>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="create_groups.php">Create new groups</a>
                    </li>
                    <li>
                        <a href="edit_groups.php">Editing existing groups</a>
                    </li>
                    <li>
                        <a href="index.php">Sign out</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
<header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Top 5 pictures</h1>
                        <hr class="small">
                        <span class="subheading">Here is top 5 pictures from your friends.</span>
                    </div>

                </div>
            </div>
        </div>
    </header>
<header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Friends' moment</h1>
                        <hr class="small">
                        <span class="subheading">Check out your frineds did rencently!</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
<header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Upload my pictures</h1>
                        

                        <hr class="small">
                        <span class="subheading">Sharing your best moment with your friends!</span>
                        
                        
                        
    								<form NAME="upload" METHOD="post" action="upload_file.php">">

										<input type="submit" value="Open Form">
									</form>
							  
							  
							  
							  

                    </div>
                </div>
            </div>
        </div>
    </header>
</body >
</html>