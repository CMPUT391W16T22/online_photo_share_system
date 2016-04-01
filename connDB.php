<?php
/*
* CMPUT 391 Project Online Photo Gallery
* Written by Bo Zhou
* Mar 26, 2016 
* 
*/
function connect() {
    $conn = oci_connect('xinchao', 'wang0408');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    return $conn;
}
?>