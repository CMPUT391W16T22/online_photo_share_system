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

<body style="background-image: url('costomer-bg.jpg')">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <ul>
			  <li><a class="active" href="#home">Home</a></li>
			  <li><a href="myphoto.php">My Photo</a></li>
			  <li><a href="upload_file.php">Upload Photo</a></li>
			  <li><a href="create_groups.php">Create new groups</a></li>
			  <li><a href="edit_groups.php">Editing existing groups</a></li>
			  <li><a href="index.php">Sign out</a></li>	
			  <div style="float:right;" ><i class="material-icons">account_circle</i><br><?php session_start(); echo $_SESSION['userid']?></div>		 
			</ul>
			
			   # set date for searching
            <?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (empty($_POST["search_text"]) and empty($_POST["from_date"]) and empty($_POST["to_date"])){
						$searchErr="You must enter key words or dates you want to search!";
					}else{
						$search_text=$_POST["search_text"];
					}
					if (!empty($_POST["from_date"]) and empty($_POST["to_date"])){
						$from_date=date("m/d/Y", strtotime($_POST["from_date"]));
						$to_date="";
						session_start();
        				$_SESSION['from_date']=$from_date;
        				$_SESSION['to_date']=$to_date;
						$_SESSION['search_text']=$search_text;
						header('Location: search.php');
					}elseif (empty($_POST["from_date"]) and !empty($_POST["to_date"])) {
						$from_date="";
						$to_date=date("m/d/Y", strtotime($_POST["to_date"]));
						session_start();
        				$_SESSION['from_date']=$from_date;
        				$_SESSION['to_date']=$to_date;
						$_SESSION['search_text']=$search_text;
						header('Location: search.php');
					}
					
					if (!empty($_POST["from_date"]) and !empty($_POST["to_date"]) ) {
						$from_date=date("m/d/Y", strtotime($_POST["from_date"]));
						$to_date=date("m/d/Y", strtotime($_POST["to_date"]));
						if (strtotime($from_date)>strtotime($to_date)){
							$dateErr="From date must be less than To date reselect again!!!";
						}else{
							session_start();
            				$_SESSION['from_date']=$from_date;
            				$_SESSION['to_date']=$to_date;
 							$_SESSION['search_text']=$search_text;
							header('Location: search.php');
						}
					}
					if (empty($_POST["from_date"]) and empty($_POST["to_date"]) and !empty($_POST["search_text"])) {
						$from_date="";
						$to_date="";
						session_start();
						$_SESSION['from_date']=$from_date;
            			$_SESSION['to_date']=$to_date;
 						$_SESSION['search_text']=$search_text;
						header('Location: search.php');
					}
			}
			?>

            <table>
			<tr>
			<td>
            <form METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            	<INPUT TYPE="text" NAME="search_text"  style="width: 596px; height: 15px;" rows="4" cols="50" >
            	<INPUT TYPE="submit" NAME="Submit" VALUE="Search">
            	<br>
            	<p>From: <input type="text" NAME="from_date" id="datepicker"> To: <input type="text" NAME="to_date" id="datepicker1"></p>
				<span class="error"><?php echo $dateErr;?></span>
				<span class="error"><?php echo $searchErr;?></span>
            </form>
            </td>
			</tr>
			</table>
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
                        <span class="subheading">Here is top 5 pictures from your friends.<br></span>
                        <?php
						                        
							include("PHPconnectionDB.php");
						    session_start();
						    $user_name = $_SESSION['userid'];
						    
						//    $user_name = "admin";
						    $conn = connect();

			# create view so that it contains all photos that user have permition to see
						function createView($conn,$name){
							$sql="CREATE VIEW DISPLAY_VIEW_".$name." (PHOTO_ID, SUBJECT,PLACE, DESCRIPTION,TIMING)
								AS SELECT *
								FROM(
								SELECT i1.PHOTO_ID,i1.SUBJECT,i1.PLACE, i1.DESCRIPTION,i1.TIMING
								FROM IMAGES i1
								where i1.permitted=1
								UNION
								SELECT i2.PHOTO_ID,i2.SUBJECT,i2.PLACE, i2.DESCRIPTION,i2.TIMING
								FROM IMAGES i2
								where i2.OWNER_NAME='".$name."'
								UNION
								SELECT i3.PHOTO_ID,i3.SUBJECT,i3.PLACE, i3.DESCRIPTION,i3.TIMING
								FROM IMAGES i3, GROUP_LISTS gl
								where i3.permitted=gl.GROUP_ID
								AND gl.friend_id='".$name."')";
							$a = oci_parse($conn, $sql);
							$res=oci_execute($a);
							$r = oci_commit($conn);	
						}
                   # drop the exist view 
						function dropViewExist($conn,$name){
							$sql = "select view_name from user_views";
							$a = oci_parse($conn, $sql);
							$res=oci_execute($a);
							
							$vname = "display_view_$name";
							$vname = strtoupper($vname);
							
						   while ($row = oci_fetch_array($a, OCI_ASSOC)) {
							   	
								foreach ($row as $item) {
									
									if ($item == $vname){
										
										$sql2 = "drop view $vname";	
										$aa = oci_parse($conn, $sql2);
										$res=oci_execute($aa);	
										$r = oci_commit($conn);		
									}
							   }
							   
							}
						}
						dropViewExist($conn,$user_name);
						createView($conn,$user_name);
					
						 $sqli="select * from(Select v.photo_id, count(*), rank() over (order by count(*) desc) as rank FROM display_view_".$user_name." v, imagecont i where v.photo_id=i.photo_id GROUP BY v.photo_id) where rank<=5";						
						#$sql = "select photo_id from DISPLAY_VIEW_".$user_name;
						$a = oci_parse($conn, $sqli);
						$res=oci_execute($a);
                  # display all satisfied images
						while (($row = oci_fetch_array($a, OCI_BOTH))) {
							#echo $row[0];
							 $id =$row[0];
							 session_start();

						    echo $id;
							 echo "<a href='friendImage.php?pid=".$id.$user_name."' target='_blank' onclick='test()' name=$id><img src='1.php?id=$id'></a><br>";	
						}
						 ?>
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
                        <span class="subheading">Check out your friends did rencently!<br></span>			
						<?php
						$sql = "select photo_id from DISPLAY_VIEW_".$user_name;
						$a = oci_parse($conn, $sql);
						$res=oci_execute($a);

						while (($row = oci_fetch_array($a, OCI_BOTH))) {
							
							 $id =$row[0];
							 session_start();
							 #print out all images that user can view
						    echo $id;
							 
							 echo "<a href='friendImage.php?pid=".$id.$user_name."' target='_blank' onclick='test()' name=$id><img src='1.php?id=$id'></a><br>";		 
							
						}
						 ?>
             
                    </div>
                </div>
            </div>
        </div>
    </header> 
</body >
</html>