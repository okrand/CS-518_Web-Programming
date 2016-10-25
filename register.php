<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Q&A page for HighSide - The Motorcycle Q&A Website">
<title>Let's see what our experts said</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>    
   <header class="jumbotron text-center" style="background-color:white;">
        <h1>
        <a href="index.php">
        <img src="highside-logo.jpg" alt="HighSide" style="width:100px;height:150px;">
        </a>
        HighSide<br>Motorcycle Experience Sharing Platform </h1>
	</header>
    <h3 align='center'>Haven't seen you here before. What should we call you?</h3>
   <?php 
    if ($_SESSION["loggedIn"] == true){
        unset($_SESSION["loggedIn"]);
        unset($_SESSION["UserID"]);
        unset($_SESSION["userName"]);
        unset($_SESSION["K_Points"]);
    }
    //Form submission - New user
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$uName = test_input($_POST["uName"]);
		$pass = test_input($_POST["Pass"]);
		
        $query = "SELECT ID FROM USERS WHERE USERNAME='" . $uName . "';";
        $result = sqlcommand($query, "SELECT");
		if ($result->num_rows > 0) { //if user already exists
            echo "<div align='center' class='alert alert-warning'><strong>This username is already taken. Pick something else</strong></div>";
		}
        elseif (strlen($uName) == 0)
            echo "<div align='center' class='alert alert-warning'><strong>You can't have a blank username dummy</strong></div>";
        elseif (strlen($pass) == 0)
            echo "<div align='center' class='alert alert-warning'><strong>You can't have a blank password dummy</strong></div>";
		else{ //new user!
            $newquery = "INSERT INTO USERS(USERNAME, PASSWORD, KARMA_POINTS, LAST_ACTIVE) VALUES ('".$uName."','".$pass."',0,NOW())";
            $insertres = sqlcommand($newquery, "INSERT");
            $_SESSION["loggedIn"] = true;
            $_SESSION["userName"] = $uName;
            $_SESSION["K_Points"] = 0;
            $result = sqlcommand($query, "SELECT");
            if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION["UserID"] = $row["ID"];
            }
            //echo '<meta http-equiv="refresh" content="2;url=' . $_SESSION["referer"] . '"/>';
            header('location: ' . $_SESSION["referer"]);
            exit();
            session_write_close();
            
        }

	}
    else{
        if ($_SERVER["HTTP_REFERER"] != "register.php")
            $_SESSION["referer"] = $_SERVER["HTTP_REFERER"];
        else{
            session_unset();
            $_SESSION["referer"] = "index.php";
        }
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
        <button type="submit" class="btn btn-primary center-block">Register</button>
	</form>
    </div>
</body>
</html>