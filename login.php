<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Login page for HighSide - The Motorcycle Q&A Website">
<title>Start Riding</title>
<link rel="stylesheet" type="text/css" href="./style.css" media="all">
</head>
<body>
<?php 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$uName = $pass = "";
		$uName = test_input($_POST["uName"]);
		$pass = test_input($_POST["Pass"]);
		$servername = "localhost";
		//$dbusername = "root";
		//$dbpassword = "root";
		$dbusername = "admin";
		$dbpassword = "M0n@rch$";
		$dbname = "HighSide";
		// Create connection
		$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
		// Check connection
		if ($conn->connect_error) 
   		 	die("Connection failed: " . $conn->connect_error);
   		 $sql = "SELECT ID, PASSWORD FROM USERS WHERE USERNAME='";
   		 $sql .= $uName;
   		 $sql .= "';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$result = $result->fetch_assoc();
			$sqlPass = $result["PASSWORD"];
			$sqlID = $result["ID"];
			if ($sqlPass === $pass)
			{
				echo "User Accepted";
				$_SESSION["UserID"] = $sqlID;
				echo '<META HTTP-EQUIV="Refresh" Content="2; URL=./">';
			}
			else
				echo "Wrong Password";
		}
		else
			echo "Wrong Username";
		$conn->close();
	}

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