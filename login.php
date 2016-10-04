<?php 
include_once "header.php";
include_once "thingsandstuff.php";
include_once "db.php";
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Login page for HighSide - The Motorcycle Q&A Website">
<title>License and Registration</title>
<link rel="stylesheet" type="text/css" href="./style.css" media="all">
</head>
<body>
<?php 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$uName = test_input($_POST["uName"]);
		$pass = test_input($_POST["Pass"]);
		$servername = "localhost";
		$dbusername = "root";
		$dbpassword = "root";
		//$dbusername = "admin";
		//$dbpassword = "M0n@rch$";
		$dbname = "HighSide";
		// Create connection
		$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
		// Check connection
		if ($conn->connect_error) 
   		 	die("Connection failed: " . $conn->connect_error);
        
        $sql = "SELECT ID, PASSWORD FROM USERS WHERE USERNAME='" . $uName . "';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
			$sqlPass = $row["PASSWORD"];
			$sqlID = $row["ID"];
			if ($sqlPass === $pass)
			{
                $_SESSION["loggedIn"] = true;
				$_SESSION["UserID"] = $sqlID;
                echo '<meta http-equiv="refresh" content="2;url=' . $_SESSION["referer"] . '"/>';
                //header('location: $_SESSION["referer"]');
                //exit();
                //session_write_close();
			}
			else
				echo "Wrong Password";
		}
		else
			echo "Wrong Username";
		$conn->close();
        /*Testing sqlcommand
        $querythingy = "SELECT ID FROM USERS WHERE USERNAME='" . $uName . "';";
        $queryresult = sqlcommand($querythingy, "SELECT");
        echo "UserID is: " . $queryresult["ID"];
        */
	}
    else
        $_SESSION["referer"] = $_SERVER["HTTP_REFERER"];

	function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
  	}
?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
	Username: <input type="text" name="uName"><br><br>
	Password: <input type="Password" name="Pass"><br><br>
	<input type="Submit" >
	</form>
</body>
</html>