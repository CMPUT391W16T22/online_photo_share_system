<?php


/* get most popular photos */
$sql_popular = "SELECT photo_id, count(*) AS numberOfviewer FROM display_view_chris GROUP BY photo_id ORDER BY numberOfviewer DESC";
$stid_popular = oci_parse($conn, $sql_popular);
$result_popular = oci_execute($stid_popular);
$all_popular = array();
if ($result_popular){
    $n = 0;
    $tmp_top = -1;
    while ($popular = oci_fetch_array($stid_popular,OCI_ASSOC)){
        if ($popular["NUMBEROFVIEWER"] == '') {
            $real = 0;
        }else{
            $real = $popular["NUMBEROFVIEWER"];
        }
        if ($real != $tmp_top){
                $tmp_top = $real;
                $n++;
            if ($n > 5){
                break;
            }
            array_push($all_popular, $popular["PHOTO_ID"]);
        }else{
            array_push($all_popular, $popular["PHOTO_ID"]);
        }
    }
}
oci_close($conn);
?>