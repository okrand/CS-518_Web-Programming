<?php
function bringLibraries(){
    echo '
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  ';
}

function sqlcommand($query, $qtype){
    $queryComplete = false;
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
            $queryComplete = true;
            return $result;
        }
        else
            return $queryComplete;
    }
    else{ //not SELECT
            $queryComplete=true;
            return $queryComplete;
    }
}

function picext($picname){
if (file_exists('profilePics/' . $picname . 'jpg'))
    $picname=$picname . 'jpg';
else if (file_exists('profilePics/' . $picname . 'gif'))
    $picname=$picname . 'gif';
else if (file_exists('profilePics/' . $picname . 'png'))
    $picname=$picname . 'png';
else if (file_exists('profilePics/' . $picname . 'jpeg'))
    $picname=$picname . 'jpeg';
else
    $picname='stock.png';
return $picname;
}

function test_input($data) {
        $con = sqlcommand(" ", "GETCONN");
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($con, $data);
  		return $data;
        }

function pagename($url){
preg_match('/\/[a-z0-9]+.php/', $url, $match);
$page = array_shift($match);
return $page;
}

function redirect($url){
    #echo '<meta http-equiv="refresh" content="2;url=' . $url . '"/>';
            header('location: ' . $url);
            exit();
            session_write_close();
            
}

?>