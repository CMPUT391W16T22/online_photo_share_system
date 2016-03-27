<?php
include ("PHPconnectionDB.php");
$conn=connect(); 
session_start();
$from_date=$_SESSION['from_date'];
$to_date=$_SESSION['to_date'];
$search_text=$_SESSION['search_text'];
$name=$_SESSION['userid'];
echo $search_text; 
echo "<br>";
echo "fhjsadhfhafha";
$checkspace=strpos($search_text," ");
$checkand = strpos($search_text,"and");
$checkor = strpos($search_text,"or");
createView($conn,$name);
#this condition is single word
if ($checkand==false and $checkor==false and $checkspace==false){
	#single word with no date
	if ($to_date=="" and $from_date==$to_date){
		singleWordSearch($conn,$name,$search_text);
	#single word with date
	}else{

	}
	#space between words
}elseif ($checkand==false and $checkor==false and $checkspace==true) {
	$word = explode(" ", $search_text);
	#two more words with no date 
	if ($to_date=="" and $from_date==$to_date){
		for ($i=0; $i<count($word);$i++){
		echo $word[$i];
		echo "<br>";
		}
	}else{
		for ($i=0; $i<count($word);$i++){
		echo $word[$i];
		echo "<br>";
		}
		pass;
	}
	#contain "and" in search_text
}elseif ($checkand==true and $checkor==false) {
	$word = explode("and", $search_text);
		if ($to_date=="" and $from_date==$to_date){
			#contain "and" in search_text with no date
			pass;
	}else{
		#contain "and" in search_text with date
		pass;
	}
	#contain "or" in search_text
}elseif ($checkand==false and $checkor==true) {
	$word = explode("or", $search_text);
		if ($to_date=="" and $from_date==$to_date){
			#contain "and" in search_text with no date
			pass;
	}else{
		##contain "and" in search_text with date
		pass;
	}
}
function createView($conn,$name){
	$sql="CREATE VIEW SEARCH_VIEW_".$name." (PHOTO_ID, SUBJECT,PLACE, DESCRIPTION,TIMING)
		AS SELECT *
		FROM(
		SELECT i1.PHOTO_ID,i1.SUBJECT,i1.PLACE, i1.DESCRIPTION,i1.TIMING
		FROM IMAGES i1
		where i1.permitted=1
		UNION
		SELECT i2.PHOTO_ID,i2.SUBJECT,i2.PLACE, i2.DESCRIPTION,i2.TIMING
		FROM IMAGES i2
		where i2.OWNER_NAME='".$name."'
		UNION
		SELECT i3.PHOTO_ID,i3.SUBJECT,i3.PLACE, i3.DESCRIPTION,i3.TIMING
		FROM IMAGES i3, GROUP_LISTS gl
		where i3.permitted=gl.GROUP_ID
		AND gl.friend_id='".$name."')";
	$a = oci_parse($conn, $sql);
	$res=oci_execute($a);
	$r = oci_commit($conn);
	
}
function singleWordSearch($conn,$name,$search_text){
$sql="SELECT DISTINCT photo_id  FROM SEARCH_VIEW_".$name." WHERE subject LIKE '%".$search_text."%' OR place LIKE '%".$search_text."%'OR Description LIKE '%".$search_text."%'";
$a = oci_parse($conn, $sql);
$res=oci_execute($a);
while ($row = oci_fetch_array($a, OCI_ASSOC)) {
	#session_start();
	#$_SESSION['group_id']=$row['GROUP_ID'];
    echo "<td>" . $row['PHOTO_ID'] . "</td>";
    echo "<BR>";
	}
	echo "</table><br>";
}
function andWordSearch(){
	
}
function orWordSearch(){
	
}
function powerWordSearch(){
	
}
function timeSearch(){
	
}
?>