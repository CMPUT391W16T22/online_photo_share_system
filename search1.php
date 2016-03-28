<!DOCTYPE html>
<html>
<head>
<script>
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","search2.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="costomer.php">Main page</a>
                    </li>
                    <li>
                        <a href="index.php">Sign out</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->

<select name="view_method" onchange="showUser(this.value)">
<option value="">select:</option>
<option value="0">Default</option>
<option value="1">Most-recent-first</option>
<option value="2">Most-recent-last</option>
</select>
<br>
<div id="txtHint"><b></b></div>
</body>
</html>