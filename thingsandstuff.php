<?php
function sqlcommand($query, $qtype){
    $queryComplete = false;
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "root";
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

function test_input($data) {
        $con = sqlcommand(" ", "GETCONN");
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($con, $data);
  		return $data;
        }
?>