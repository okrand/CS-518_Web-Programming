<?php
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
    // Check connection
    if ($conn->connect_error) {
   		$error = "Connection failed: " . $conn->connect_error;
        return $queryComplete;
    }
    $result = $conn->query($query);
    
    
    if ($qtype == "SELECT") {
        if ($result->num_rows > 0 ){
            $queryComplete = true;
            $toReturn = $result->fetch_assoc();
            $conn->close();
            return $toReturn;
        }
        else{
            $conn->close();
            return $queryComplete;
        }
    }
    else if ($qtype == "SELECTMulti"){ //selecting multiple rows
        if ($result->num_rows > 0 ){
            $conn->close();
            $queryComplete = true;
            return $result;
        }
        else{
            $conn->close();
            return $queryComplete;
        }
    }
    else{
        $conn->close();
        $queryComplete=true;
        return $queryComplete;
    }
}

function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
        }
?>