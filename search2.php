<?php
$q = (int)intval($_GET['q']);
echo $q;
include ("PHPconnectionDB.php");        
	   //establish connection
$conn=connect();
session_start();
$name=$_SESSION['userid'];
dropViewExist($conn,$name);
if ($q==0){
	$sql="CREATE VIEW SEARCH_VIEW_".$name." (PHOTO_ID,TIMING,RANK)
		AS SELECT *
		FROM SEARCH_".$name." s
		ORDER BY s.RANK DESC";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}elseif ($q==1) {
	$sql="CREATE VIEW SEARCH_VIEW_".$name." (PHOTO_ID,TIMING,RANK)
		AS SELECT *
		FROM SEARCH_".$name." s
		ORDER BY s.Timing DESC";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}else{
	$sql="CREATE VIEW SEARCH_VIEW_".$name." (PHOTO_ID,TIMING,RANK)
		AS SELECT *
		FROM SEARCH_".$name." s
		ORDER BY s.Timing ASC";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}
function dropViewExist($conn,$name){
    $sql = "select view_name from user_views";
    $a = oci_parse($conn, $sql);
    $res=oci_execute($a);
   
    $vname = "search_view_$name";
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
?>