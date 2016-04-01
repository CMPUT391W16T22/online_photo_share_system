<!DOCTYPE html>
<html>
<head>
	<title>Data Analysis</title>
	  <meta charset="utf-8">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
	$(function() {
	    $( "#datepicker" ).datepicker();
	    $( "#datepicker1" ).datepicker();
	  });
  </script>
</head>
<body>
<H3>Data Analysis module</H3>
<table>
<tr>
<td>
<form METHOD="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<p>Username: <INPUT TYPE="text" NAME="username"></p>
	<p>Subject: <INPUT TYPE="text" NAME="subword"></p>
	<p>From: <input type="text" NAME="from_date" id="datepicker"> To: <input type="text" NAME="to_date" id="datepicker1"></p>
	<span class="error"><?php echo $dateErr;?></span><br>
  	<input type="submit" NAME="select_w" value="Weekly"> 
  	<input type="submit" NAME="select_m" value="Monthly"> 
  	<input type="submit" NAME="select_y" value="Yearly"> 
</form>
</td>
</tr>
</table>
<from>
</from>
<?php
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
createAdminView($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST["from_date"]) and !empty($_POST["to_date"]) ) {
		$from_date=date("d-M-Y", strtotime($_POST["from_date"]));
		$to_date=date("d-M-Y", strtotime($_POST["to_date"]));
		if (strtotime($from_date)>strtotime($to_date)){
			$dateErr="From date must be less than To date reselect again!!!";
		}
	}elseif (!empty($_POST["from_date"]) and empty($_POST["to_date"]))  {
		$from_date=date("d-M-Y", strtotime($_POST["from_date"]));
		$to_date=""; 
	}elseif (empty($_POST["from_date"]) and !empty($_POST["to_date"])) {
		$from_date="";
		$to_date=date("d-M-Y", strtotime($_POST["to_date"])); 
	}else{
		$from_date="";
		$to_date="";
	}
	if ("select_w")
	
}
function createAdminView($conn){
$sql="CREATE VIEW ADMIN_VIEW (OWNER_NAME,SUBJECTS,TIMING) AS
	SELECT(OWNER_NAME,SUBJECTS,TIMING)
	FROM IMAGES
	ORDER BY TIMING";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);	
}

?>
</body>
</html>