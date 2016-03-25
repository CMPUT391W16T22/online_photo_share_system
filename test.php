<!doctype html>
<html lang="en">
<head>
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
 <form METHOD="post" action="search.php">
<INPUT TYPE="text" NAME="search_text"  style="width: 596px; height: 15px;" rows="4" cols="50" >
<INPUT TYPE="submit" NAME="Submit" VALUE="Search">
<p>From: <input type="text" NAME="search_text" id="datepicker"></p>
<p>To: <input type="text" id="datepicker1"></p>
 
</body>
</html>
<?php
$search_text="cat dog";
$search_text1="cat and bird";
$pos1 = strpos($search_text1,"and");
$pos2 = strpos($search_text1,"and");
if ($pos1 === true) {
    echo "The string '$findme' was not found in the string '$mystring'";
    #split
} else {
    echo "The string '$findme' was found in the string '$mystring'";
    echo " and exists at position $pos";
}
?>