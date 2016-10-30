<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Login page for HighSide - The Motorcycle Q&A Website">
<title>License and Registration</title>
<?php bringLibraries(); ?>
</head>
<body>
        <header class="jumbotron text-center" style="background-color:white;">
        <h1>
        <a href="index.php">
        <img src="highside-logo.jpg" alt="HighSide" style="width:100px;height:150px;">
        </a>
        HighSide<br>Motorcycle Experience Sharing Platform </h1>
	</header>
    <h3 align='center'>Long time no see!</h3>
<?php 
    if ($_SESSION["loggedIn"] == true){
        unset($_SESSION["loggedIn"]);
        unset($_SESSION["UserID"]);
        unset($_SESSION["userName"]);
        unset($_SESSION["K_Points"]);
    }
    //Form submission - Log in
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        
        $sql = "SELECT ID, PASSWORD, KARMA_POINTS FROM USERS WHERE USERNAME='" . $uName . "';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
			$sqlPass = $row["PASSWORD"];
            $sqlPass = test_input($sqlPass);
			$sqlID = $row["ID"];
			if ($sqlPass === $pass)
			{
                $_SESSION["loggedIn"] = true;
				$_SESSION["UserID"] = $sqlID;
                $_SESSION["userName"] = $uName;
                redirect($_SESSION["referer"]);
			}
			else
				echo "<div align='center' class='alert alert-warning'><strong>Wrong Password!</strong></div>";
		}
		else 
			echo "<div align='center' class='alert alert-warning'><strong>Wrong Username!</strong></div>";
		$conn->close();
	}
    else{
        if (isset($_SERVER["HTTP_REFERER"]))
            $referer = pagename($_SERVER["HTTP_REFERER"]);
        else
            $referer = "this";
        if ($referer != "this" && $referer != "/profile.php")
            $_SESSION["referer"] = $referer;
        else
            $_SESSION["referer"] = "/index.php";
    }
?>
    <div class="container">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="form-group">
	       <label for="username">Username: </label>
            <input type="text" name="uName" placeholder="Enter username" class="form-control" id="username" autofocus><br>
        </div>
        <div class="form-group">
	       <label for="pwd">Password: </label>
            <input type="Password" placeholder="Enter password" name="Pass" class="form-control" id="pwd"><br>
        </div>
        <button type="submit" class="btn btn-primary center-block">Log in </button>
	</form>
    </div>
</body>
</html>