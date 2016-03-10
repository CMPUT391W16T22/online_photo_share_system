<?php
//$con=oci_connect('userame','password','oracle_sid');
$conn=oci_connect('xinchao','wang0408');
if($conn)
	echo "Connection succeded";
else
{
	echo "Connection failed";
    $err = oci_error();
	trigger_error(htmlentities($err['message'], ENT_QUOTES), E_USER_ERROR);	
}
?>
