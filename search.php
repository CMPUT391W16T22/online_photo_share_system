<?php
include ("PHPconnectionDB.php");
$conn=connect(); 
session_start();
if (!empty($_SESSION['from_date']) and !empty($_SESSION['to_date'])){
	$from_date=date("d-M-Y",strtotime($_SESSION['from_date']));
	$to_date=date("d-M-Y",strtotime($_SESSION['to_date']));
}elseif (empty($_SESSION['from_date']) and !empty($_SESSION['to_date'])) {
	session_start();
	$from_date="";
	$to_date=date("d-M-Y",strtotime($_SESSION['to_date']));
}elseif (!empty($_SESSION['from_date']) and empty($_SESSION['to_date'])) {
	session_start();
	$from_date=date("d-M-Y",strtotime($_SESSION['from_date']));
	$to_date="";
}else{
	$from_date="";
	$to_date="";
}
$search_text=$_SESSION['search_text'];
$name=$_SESSION['userid'];
$order=$_SESSION['view_method'];
dropIndexExist($conn,"DESCRPINDEX");
dropIndexExist($conn,"PLACEINDEX");
dropIndexExist($conn,"SUBINDEX");

$q = oci_parse($conn, 'CREATE INDEX DESCRPINDEX ON IMAGES(DESCRIPTION) INDEXTYPE IS CTXSYS.CONTEXT');
oci_execute($q, OCI_NO_AUTO_COMMIT);	
$q = oci_parse($conn, 'CREATE INDEX PLACEINDEX ON IMAGES(PLACE) INDEXTYPE IS CTXSYS.CONTEXT');
oci_execute($q, OCI_NO_AUTO_COMMIT);
$q = oci_parse($conn, 'CREATE INDEX SUBINDEX ON IMAGES(SUBJECT) INDEXTYPE IS CTXSYS.CONTEXT');
oci_execute($q);
$settime="";

if ($search_text==""){
	$sql="SELECT PHOTO_ID FROM DISPLAY_VIEW_".$name." WHERE";
	if ($from_date!="" and $to_date!=""){
		$sql.="TIMING >to_date('".$from_date."') and timing < to_date('".$to_date."')";
	}elseif ($from_date!="") {
		$sql.=" TIMING >=to_date('".$from_date."')";
	}else{
		$sql.=" TIMING=< to_date('".$to_date."')";
	}
	if ($order == '0') {
        $sql.= " ORDER BY score DESC";
    }
    else if ($order == '1') {
        $sql.= " ORDER BY timing DESC";
    }
    else {
        $sql.= " ORDER BY timing";
    }
}else{
	$key_array = explode(' ', $search_text);
        $contains = '%'.$key_array[0].'%';
        foreach ($key_array as $key) {
            if ($key_array[0] != $key) {
                $contains = $contains.' | %'.$key.'%';
            }
        }
	$sql='SELECT PHOTO_ID, ((SCORE(1) * 6) + (SCORE(2) * 3) + SCORE(3)) score  FROM IMAGES 
		  WHERE CONTAINS (subject, \''.$contains.'\', 1) > 0 OR 
		  CONTAINS (place, \''.$contains.'\', 2) > 0 OR 
		  CONTAINS (description, \''.$contains.'\', 3) > 0
		  and (owner_name = \''.$name.'\' or \''.$name.'\' = \'admin\' or permitted = 1 or permitted in 
		  (SELECT group_id FROM group_lists WHERE friend_id = \''.$name.'\') or \''.$name.'\' 
		  in (SELECT user_name FROM groups WHERE group_id = permitted))';
	if ($from_date!=""){
		$sql.="AND TIMING >to_date('".$from_date."')";
	}
	if ($to_date!=""){
		$sql.="AND TIMING=< to_date('".$to_date."')";
	}
    if ($order == '0') {
        $sql.= " ORDER BY score DESC";
    }
    else if ($order == '1') {
        $sql.= " ORDER BY timing DESC";
    }
    else {
        $sql.= " ORDER BY timing";
    }
}
showImages($conn,$sql,$name);

function showImages($conn,$sql,$name){
	$a = oci_parse($conn,$sql);
	$res=oci_execute($a);
	while (($row = oci_fetch_array($a, OCI_BOTH))) {
		#echo $row[0];
		 $id =$row[0];
		 session_start();

		 #echo $_SESSION['pid'];
		 echo "<a href='friendImage.php?pid=".$id.$name."' target='_blank' onclick='test()' name=$id><img src='1.php?id=$id' width='128' height='128'></a><br>";
	}
}
function dropIndexExist($conn,$indexname){
    $sql = "select index_name from user_indexes";
    $a = oci_parse($conn, $sql);
    $res=oci_execute($a);
    $sql2 = "DROP INDEX $indexname";
   while ($row = oci_fetch_array($a, OCI_ASSOC)) {        
        foreach ($row as $item) {
            if ($item == $indexname){
                $sql2 = "DROP INDEX $indexname";
                $aa = oci_parse($conn, $sql2);
                oci_execute($aa);  
                $r = oci_commit($conn);       
            }
       }
      
    }
   }
?>
