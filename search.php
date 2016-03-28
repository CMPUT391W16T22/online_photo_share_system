<?php
include ("PHPconnectionDB.php");
$conn=connect(); 
session_start();
$from_date=date("d-M-Y",strtotime($_SESSION['from_date']));
$to_date=date("d-M-Y",strtotime($_SESSION['to_date']));
$search_text=$_SESSION['search_text'];
$name=$_SESSION['userid'];
$checkspace=strpos($search_text," ");
$checkand = strpos($search_text,"and");
$checkor = strpos($search_text,"or");
dropTableExist($conn,$name);
createSearchView($conn,$name);
$settime="";
#this condition is single word
if ($checkand==false and $checkor==false and $checkspace==false){
	#single word with no date
	if ($from_date==$to_date){
		singleWordSearch($conn,$search_text,$name,$settime);
	#single word with date
	}else{
		$settime="AND (TIMING >to_date('".$from_date."') and timing < to_date('".$to_date."'))";
		singleWordSearch($conn,$search_text,$name,$settime);
	}
	#space between words
}elseif ($checkand==false and $checkor==false and $checkspace==true) {
	$word = explode(" ", $search_text);
	#two more words with no date 
	if ($from_date==$to_date){
		spaceSearch($conn,$word,$name,$settime);
	}else{
		$settime="AND (TIMING >to_date('".$from_date."') and timing < to_date('".$to_date."'))";
		spaceSearch($conn,$word,$name,$settime);
	}
	#contain "and" in search_text
}elseif ($checkand==true and $checkor==false) {
	$word = explode(" and ", $search_text);
	if ($from_date==$to_date){
		#contain "and" in search_text with no date
		andWordSearch($conn,$word,$name,$settime);
	}else{
		$settime="AND (TIMING >to_date('".$from_date."') and timing < to_date('".$to_date."'))";
		andWordSearch($conn,$word,$name,$settime);
	}
	#contain "or" in search_text
}elseif ($checkand==false and $checkor==true) {
	$word = explode(" or ", $search_text);
	if ($from_date==$to_date){
		orWordSearch($conn,$word,$name,$settime);
	}else{
		##contain "or" in search_text with date
		$settime="AND (TIMING >to_date('".$from_date."') and timing < to_date('".$to_date."'))";
		orWordSearch($conn,$word,$name,$settime);
	}

}
header('Location: search1.php');
						

function createSearchView($conn,$name){
	$sql="CREATE TABLE SEARCH_".$name." (
		PHOTO_ID INT,
		TIMING DATE,
		RANK INT)";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
}
function singleWordSearch($conn,$search_text,$name,$settime){
	$sql="SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$search_text."%' OR place LIKE '%".$search_text."%'OR Description LIKE '%".$search_text."%')".$settime."";
	echo $sql;
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);	
	while ($row = oci_fetch_array($a, OCI_ASSOC)) {
		$check_rank=calculateRank($row['SUBJECT'],$row['PLACE'] ,$row['DESCRIPTION'] ,$search_text);
		$q="INSERT INTO SEARCH_".$name." VALUES('". $row['PHOTO_ID'] ."', '". $row['TIMING'] ."', '".$check_rank."')"; 
		$b = oci_parse($conn, $q);
		$res=oci_execute($b);
	}
	$r = oci_commit($conn);
		
}
function andWordSearch($conn,$word,$name,$settime){
	$sql="SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[0]."%' OR place LIKE '%".$word[0]."%'OR Description LIKE '%".$word[0]."%')".$settime."";
	for ($i=1; $i<count($word);$i++){
		$a="\n
			INTERSECT
			SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[$i]."%' OR place LIKE '%".$word[$i]."%'OR Description LIKE '%".$word[$i]."%')".$settime."";
		$sql="$sql$a";
	}
	#echo $sql;
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	while ($row = oci_fetch_array($a, OCI_ASSOC)) {
		$check_rank=calculateRank($row['SUBJECT'],$row['PLACE'] ,$row['DESCRIPTION'] ,$word);
		$q="INSERT INTO SEARCH_".$name." VALUES('". $row['PHOTO_ID'] ."', '". $row['TIMING'] ."', '".$check_rank."')"; 
		$b = oci_parse($conn, $q);
		$res=oci_execute($b);
	
		}
	$r = oci_commit($conn);	
}
function spaceSearch($conn,$word,$name,$settime){
	$sql="SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[0]."%' OR place LIKE '%".$word[0]."%'OR Description LIKE '%".$word[0]."%')".$settime."";
	for ($i=1; $i<count($word);$i++){
		$a="\n
			UNION
			SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[$i]."%' OR place LIKE '%".$word[$i]."%'OR Description LIKE '%".$word[$i]."%')".$settime."";
		$sql="$sql$a";	
		}
	#echo $sql;
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	while ($row = oci_fetch_array($a, OCI_ASSOC)) {
		$check_rank=calculateRank($row['SUBJECT'],$row['PLACE'] ,$row['DESCRIPTION'] ,$word);
		$q="INSERT INTO SEARCH_".$name." VALUES('". $row['PHOTO_ID'] ."', '". $row['TIMING'] ."', '".$check_rank."')"; 
		$b = oci_parse($conn, $q);
		$res=oci_execute($b);
	
		}
	$r = oci_commit($conn);	
}

function orWordSearch($conn,$word,$name,$settime){
	$sql="SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[0]."%' OR place LIKE '%".$word[0]."%'OR Description LIKE '%".$word[0]."%')".$settime."";
	for ($i=1; $i<count($word);$i++){
		$a="\n
			UNION
			SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[$i]."%' OR place LIKE '%".$word[$i]."%'OR Description LIKE '%".$word[$i]."%')".$settime."";
		$sql="$sql$a MINUS ";	
		}
	$sql1="(SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[0]."%' OR place LIKE '%".$word[0]."%'OR Description LIKE '%".$word[0]."%')".$settime."";
	for ($i=1; $i<count($word);$i++){
		$a="\n
			INTERSECT
			SELECT *  FROM DISPLAY_VIEW_".$name." WHERE (subject LIKE '%".$word[$i]."%' OR place LIKE '%".$word[$i]."%'OR Description LIKE '%".$word[$i]."%')".$settime."";
		$sql1="$sql1$a)";
	}
	$sql="$sql$sql1";
	#echo $sql;
	while ($row = oci_fetch_array($a, OCI_ASSOC)) {
		$check_rank=calculateRank($row['SUBJECT'],$row['PLACE'] ,$row['DESCRIPTION'] ,$word);
		$q="INSERT INTO SEARCH_".$name." VALUES('". $row['PHOTO_ID'] ."', '". $row['TIMING'] ."', '".$check_rank."')"; 
		$b = oci_parse($conn, $q);
		$res=oci_execute($b);
	
		}
	$r = oci_commit($conn);	

	
}
function calculateRank($subject,$place,$description,$search_text){
	$b=0;
	if (!is_array($search_text)){
		$a=6*substr_count($subject, $search_text)+3*substr_count($place, $search_text)+substr_count($description, $search_text);
		return $a;
	}else{
		for ($i=0; $i<count($search_text);$i++){
			$a=6*substr_count($subject, $search_text[$i])+3*substr_count($place, $search_text[$i])+substr_count($description, $search_text[$i]);
			$b=$a+$b;
		}
	return $b;
	}
}
function dropTableExist($conn,$name){
    $sql = "select table_name from user_tables";
    $a = oci_parse($conn, $sql);
    $res=oci_execute($a);
   
    $vname = "search_$name";
    $vname = strtoupper($vname);
   
   while ($row = oci_fetch_array($a, OCI_ASSOC)) {
          
        foreach ($row as $item) {
           
            if ($item == $vname){
               
                $sql2 = "drop table $vname";   
                $aa = oci_parse($conn, $sql2);
                $res=oci_execute($aa);   
                $r = oci_commit($conn);       
            }
       }
      
    }
   }
?>
