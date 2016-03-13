<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
echo "editing exsting groups########!!!!!";
function checkExist($conn,$name){
	

	 //sql command
           $sql = 'SELECT user_name FROM users';
          
           
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
					return true;			
			}
		}
	   }
?>
</body>
</html>