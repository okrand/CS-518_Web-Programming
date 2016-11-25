<?php
function bringLibraries(){
    echo '
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css"> 
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
    
    <script>
function showResult(str) {
  if (str.length==0) { 
    document.getElementById("usersearch").innerHTML="";
    document.getElementById("usersearch").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("usersearch").innerHTML=this.responseText;
      document.getElementById("usersearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","usersearch.php?q="+str,true);
  xmlhttp.send();
}
</script>
  ';
}

function sqlcommand($query, $qtype){
    $servername = "localhost";
    //$dbusername = "root";
    //$dbpassword = "root";
    $dbusername = "admin";
    $dbpassword = "M0n@rch$";
    $dbname = "HighSide";
    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($qtype == "GETCONN")
        return $conn;
    // Check connection
    if ($conn->connect_error) {
   		$error = "Connection failed: " . $conn->connect_error;
        return $error;
    }
    $result = $conn->query($query);
    $conn->close();

    
    if ($qtype == "SELECT"){ //selecting multiple rows
        if ($result->num_rows > 0 ){
            return $result;
        }
        else
            return false;
    }
    else{ //not SELECT
            return true;
    }
}

function redirect($url){
    //echo '<meta http-equiv="refresh" content="2;url=' . $url . '"/>';
            header('Location:' . $url);
            exit();
            session_write_close();
          
}



function picext($picname){
if (file_exists($picname . 'jpg'))
    $picname=$picname . 'jpg';
else if (file_exists($picname . 'gif'))
    $picname=$picname . 'gif';
else if (file_exists($picname . 'png'))
    $picname=$picname . 'png';
else if (file_exists($picname . 'jpeg'))
    $picname=$picname . 'jpeg';
else
    $picname='profilePics/stock.png';
return $picname;
}

function test_input($data) {
        $con = sqlcommand(" ", "GETCONN");
  		//$data = trim($data);
  		//$data = stripslashes($data);
  		//$data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($con, $data);
  		return $data;
        }

function pagename($url){
preg_match('/\/[a-z0-9]+.php/', $url, $match);
$page = array_shift($match);
return $page;
}
?>