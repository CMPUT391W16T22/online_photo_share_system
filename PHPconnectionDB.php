<?php

# connect to db
function connect(){
	$conn = oci_connect('xinchao', 'wang0408');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	return $conn;
}
?>
