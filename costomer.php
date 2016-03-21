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

</head>
<?php
?>
<body style="background-image: url('costomer-bg.jpg')">

            <!-- Collect the nav links, forms, and other content for toggling -->
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
                        
                        
                        <form action="upload.php" method="post" enctype="multipart/form-data">
    								Select image to upload:
    								<input type="file" name="fileToUpload" id="fileToUpload">
    								<input type="submit" value="Upload Image" name="submit">
							  </form>
							  
							  
							  

                    </div>
                </div>
            </div>
        </div>
    </header>
</body >
</html>