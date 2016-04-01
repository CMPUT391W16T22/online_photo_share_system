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
<?php
include ("PHPconnectionDB.php");        
$conn=connect();
dropViewExist($conn);
createAdminView($conn);
$dateErr="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST["from_date"]) and !empty($_POST["to_date"]) ) {
		$from_date=date("d-M-Y", strtotime($_POST["from_date"]));
		$to_date=date("d-M-Y", strtotime($_POST["to_date"]));
		if (strtotime($from_date)>strtotime($to_date)){
			$dateErr="From date must be less than To date reselect again!!!";
		}
	}elseif (!empty($_POST["from_date"]) and empty($_POST["to_date"]))  {
		$from_date=date("d-M-Y", strtotime($_POST["from_date"]));
		$sql="SELECT MAX (timing) AS day_before_min FROM  ADMIN_VIEW";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		while ($row = oci_fetch_array($a, OCI_ASSOC)) {
			foreach ($row as $item) {
				$to_date=date("d-M-Y", strtotime($item));
		}
		if (strtotime($from_date)>strtotime($to_date)){
			$dateErr="From date must be less than To date reselect again!!!";
			echo $dateErr;
		}
	}
	}elseif (empty($_POST["from_date"]) and !empty($_POST["to_date"])) {
		$from_date="";
		$to_date=date("d-M-Y", strtotime($_POST["to_date"])); 
		$sql="SELECT MIN (timing) AS day_before_min FROM  ADMIN_VIEW";
		$a = oci_parse($conn, $sql);
		$res=oci_execute($a);
		while ($row = oci_fetch_array($a, OCI_ASSOC)) {
			foreach ($row as $item) {
				$from_date=date("d-M-Y", strtotime($item));
		}
	}
	}else{
		$from_date="";
		$to_date="";
	}
	if (!empty($_POST["username"])){
		$username=$_POST["username"];
	}else{
		$username="";
	}
	if (!empty($_POST["subword"])){
		$subword=$_POST["subword"];
	}else{
		$subword="";
	}
	$select_v=$_POST["select_v"];
}
function dropViewExist($conn){
	$sql = "select view_name from user_views";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	while ($row = oci_fetch_array($a, OCI_ASSOC)) {
		foreach ($row as $item) {
			if ($item == "ADMIN_VIEW"){
				$sql2 = "drop view ADMIN_VIEW";	
				$aa = oci_parse($conn, $sql2);
				$res=oci_execute($aa);	
				$r = oci_commit($conn);		
			}
	   }
	   
	}
}
function createAdminView($conn){
$sql="CREATE VIEW ADMIN_VIEW (OWNER_NAME,SUBJECT,TIMING) AS
	(SELECT OWNER_NAME,SUBJECT,TIMING
	FROM IMAGES)
	ORDER BY TIMING";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
oci_commit($conn);	
}

?>
<H3>Data Analysis module</H3>
<form action="adminView.php">
<input type="submit" value="View Images">
</form>
<table>
<tr>
<td>
<form METHOD="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<p>Username: <INPUT TYPE="text" NAME="username"></p>
	<p>Subject: <INPUT TYPE="text" NAME="subword"></p>
	<p>From: <input type="text" NAME="from_date" id="datepicker"> To: <input type="text" NAME="to_date" id="datepicker1"></p>
	<span class="error"><?php echo $dateErr;?></span><br>
	<select name="select_v" >
	<option value="0">select:</option>
	<option value="1">Weekly</option>
	<option value="2">Monthly</option>
	<option value="3">Yearly</option>
	</select>
  	<input type="submit" NAME="submit" value="Apply">
</td>
</tr>
</table>
<?php
$check=0;
$sql="SELECT";
if ($select_v==1) {
	if($username!=""){
		$check=1;
		$sql.=" OWNER_NAME, ";
	}
	if ($subword!=""){
			if ($check==0){
				$sql.=" SUBJECT, ";
				$check=2;
			}else{
				$sql.="SUBJECT, ";
				$check=3;
			}
		}
		$sql.=" count(*) TOTAL, to_char(TIMING, 'YYYY-ww') WEEK from ADMIN_VIEW ";
	if ($check==0){
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) group by rollup(to_char(timing, 'YYYY-ww'))";
		}else{
			$sql.="group by rollup(to_char(timing, 'YYYY-ww'))";
		}	
		echo $from_date;
		echo $to_date;
		
			echo "<table>
				<tr>
				<th>Total</th>
				<th>Weeks</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    	echo "<tr>";
		    	foreach ($row as $item) {
		    		echo "<td>" . $item . "</td>";
		    	}
		    	echo "</tr>";
		}
	}
	}elseif ($check==1) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$from_date."','dd-MM-YY')) and owner_name='".$username."' 
			group by rollup(owner_name, to_char(timing, 'YYYY-ww'))";
		}else{
			$sql.="where owner_name='".$username."' group by rollup(owner_name, to_char(timing, 'YYYY-ww'))";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>Total</th>
				<th>Weeks</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['WEEK'] . "</td>";
		    echo "</tr>";
		    }
		    echo "</table><br>";	
	}elseif ($check==2) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' 
			group by rollup(subject, to_char(timing, 'YYYY-ww'))";
		}else{
			$sql.="where subject like '%".$subword."%' group by rollup(subject, to_char(timing, 'YYYY-ww'))";
		}
		
		echo "<table>
				<tr>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Weeks</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['WEEK'] . "</td>";
		    echo "</tr>";
		}
		echo "</table><br>";
	}elseif ($check==3) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' and owner_name='".$username."'group by rollup(subject, to_char(timing, 'YYYY-ww'),owner_name)";
		}else{
			$sql.="where subject like '%".$subword."%' and owner_name='".$username."'
					group by rollup(subject, to_char(timing, 'YYYY-ww'),owner_name)";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Weeks</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['WEEK'] . "</td>";
		    echo "</tr>";
	}
	echo "</table><br>";
	
}elseif ($select_v==2) {
	if($username!=""){
		$check=1;
		$sql.=" OWNER_NAME, ";
	}
	if ($subword!=""){
			if ($check==0){
				$sql.=" SUBJECT, ";
				$check=2;
			}else{
				$sql.="SUBJECT, ";
				$check=3;
			}
		}
		$sql.=" count(*) TOTAL, to_char(TIMING, 'YYYY-MM') MONTH from ADMIN_VIEW ";
	if ($check==0){
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) group by rollup(to_char(timing, 'YYYY-MM'))";
		}else{
			$sql.="group by rollup(to_char(timing, 'YYYY-MM'))";
		}
		echo $from_date;
		echo $to_date;
		
			echo "<table>
				<tr>
				<th>Total</th>
				<th>Month</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    	echo "<tr>";
		    	foreach ($row as $item) {
		    		echo "<td>" . $item . "</td>";
		    	}
		    	echo "</tr>";
		}
	}
	}elseif ($check==1) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$from_date."','dd-MM-YY')) and owner_name='".$username."' 
			group by rollup(owner_name, to_char(timing, 'YYYY-MM'))";
		}else{
			$sql.="where owner_name='".$username."' group by rollup(owner_name, to_char(timing, 'YYYY-MM'))";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>Total</th>
				<th>Month</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['MONTH'] . "</td>";
		    echo "</tr>";
		    }
		    echo "</table><br>";	
	}elseif ($check==2) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' 
			group by rollup(subject, to_char(timing, 'YYYY-MM'))";
		}else{
			$sql.="where subject like '%".$subword."%' group by rollup(subject, to_char(timing, 'YYYY-MM'))";
		}
		
		echo "<table>
				<tr>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Month</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['MONTH'] . "</td>";
		    echo "</tr>";
		}
		echo "</table><br>";
	}elseif ($check==3) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' and owner_name='".$username."'group by rollup(subject, to_char(timing, 'YYYY-MM'),owner_name)";
		}else{
			$sql.="where subject like '%".$subword."%' and owner_name='".$username."'
					group by rollup(subject, to_char(timing, 'YYYY-ww'),owner_name)";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Month</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['MONTH'] . "</td>";
		    echo "</tr>";
	}
	echo "</table><br>";
}elseif ($select_v=="3") {
	if($username!=""){
		$check=1;
		$sql.=" OWNER_NAME, ";
	}
	if ($subword!=""){
			if ($check==0){
				$sql.=" SUBJECT, ";
				$check=2;
			}else{
				$sql.="SUBJECT, ";
				$check=3;
			}
		}
		$sql.=" count(*) TOTAL, to_char(TIMING, 'YYYY') YEAR from ADMIN_VIEW ";
	if ($check==0){
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) group by rollup(to_char(timing, 'YYYY'))";
		}else{
			$sql.="group by rollup(to_char(timing, 'YYYY'))";
		}
		echo $from_date;
		echo $to_date;
		
			echo "<table>
				<tr>
				<th>Total</th>
				<th>Year</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    	echo "<tr>";
		    	foreach ($row as $item) {
		    		echo "<td>" . $item . "</td>";
		    	}
		    	echo "</tr>";
		}
	}elseif ($check==1) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$from_date."','dd-MM-YY')) and owner_name='".$username."' 
			group by rollup(owner_name, to_char(timing, 'YYYY'))";
		}else{
			$sql.="where owner_name='".$username."' group by rollup(owner_name, to_char(timing, 'YYYY'))";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>Total</th>
				<th>Year</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['YEAR'] . "</td>";
		    echo "</tr>";
		    }
		    echo "</table><br>";	
	}elseif ($check==2) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' 
			group by rollup(subject, to_char(timing, 'YYYY'))";
		}else{
			$sql.="where subject like '%".$subword."%' group by rollup(subject, to_char(timing, 'YYYY'))";
		}
		
		echo "<table>
				<tr>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Year</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['YEAR'] . "</td>";
		    echo "</tr>";
		}
		echo "</table><br>";
	}elseif ($check==3) {
		if ($from_date!="" and $to_date!=""){
			$sql.="where (timing BETWEEN to_date ('".$from_date."','dd-MM-YY') 
			and to_date('".$to_date."','dd-MM-YY')) and subject like '%".$subword."%' and owner_name='".$username."'group by rollup(subject, to_char(timing, 'YYYY'),owner_name)";
		}else{
			$sql.="where subject like '%".$subword."%' and owner_name='".$username."'
					group by rollup(subject, to_char(timing, 'YYYY'),owner_name)";
		}
		
		echo "<table>
				<tr>
				<th>OWNER_NAME</th>
				<th>SUBJECT</th>
				<th>Total</th>
				<th>Year</th>
				</tr>";
			$result = oci_parse($conn,$sql);
			$re=oci_execute($result); 
		    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			#session_start();
			#$_SESSION['group_id']=$row['GROUP_ID'];
		    echo "<tr>";
		    echo "<td>" . $row['OWNER_NAME'] . "</td>";
		    echo "<td>" . $row['SUBJECT'] . "</td>";
		    echo "<td>" . $row['TOTAL'] . "</td>";
		    echo "<td>" . $row['YEAR'] . "</td>";
		    echo "</tr>";
	}
	echo "</table><br>";
}
}
?>
</body>
</html>