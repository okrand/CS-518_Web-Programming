<?php 
include_once "thingsandstuff.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Homepage for HighSide - The Motorcycle Q&A Website">
<title>HighSide - Motorcycle Experience Sharing Platform</title>
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
    <div class="topMenu">
                    <div class="btn-group pull-left">
                        <?php
                            if (isset($_SESSION["UserID"])){
                            $query = "SELECT KARMA_POINTS FROM USERS WHERE ID = ". $_SESSION["UserID"] . ";";
                            $result = sqlcommand($query, "SELECT");
                            $result = $result->fetch_assoc();
                            echo '<label class="btn btn-info disabled">Welcome <a href="profile.php">' . $_SESSION["userName"].'</a> ' . '<span id="K_Points" class="badge">' . $result["KARMA_POINTS"] . '</span></label>';
                            }
                        ?>
                    </div>
                    <div class="btn-group pull-right" >
						<a href="ask.php" class="btn btn-info"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                    </div>
	</div>
	<?php 
	if ($_SESSION["loggedIn"] != true)
	{
		echo "<h3 align='center'>We realized that you are riding these streets with no license plate. Please "; 
		echo '<a href="./login.php">login here</a>' . " or " . '<a href="./register.php">register here</a></h3>';
	}
	?>

	<!-- Unanswered Questions -->
        <table class="table"><td>
        <header>
            <h3 align="center"> Here are some questions waiting for answers! </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-4 text-center">Asker</th><th class="col-sm-2 text-center">Points</th><th class="col-sm-4 text-center">Time</th></tr>
             <?php
             $query = "SELECT * FROM QUESTIONS WHERE ANSWER_ID = 0 ORDER BY ID DESC LIMIT 5;";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()){
                    $que = "SELECT USERNAME FROM USERS WHERE ID = " . $row["ASKER_ID"] . ";";
                    $uname = sqlcommand($que, "SELECT");
                    $uname = $uname->fetch_assoc();
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a> <td class='col-sm-4 text-center'>" . $uname["USERNAME"] . "<td class='col-sm-2 text-center'>" . $row["POINTS"] . "</td> <td class='col-sm-4 text-center'>" . $row["DATE_ASKED"] . "\n";
                }
             ?>
        </table>
    </div>
    </td>
    
    <!-- Best Questions -->
        <td>
        <header>
            <h3 align="center"> Here are the highest ranked questions! </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-4 text-center">Asker</th><th class="col-sm-2 text-center">Points</th><th class="col-sm-4 text-center">Time</th></tr>
             <?php
             $query = "SELECT * FROM QUESTIONS ORDER BY POINTS DESC LIMIT 5;";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()) {
                    $que = "SELECT USERNAME FROM USERS WHERE ID = " . $row["ASKER_ID"] . ";";
                    $uname = sqlcommand($que, "SELECT");
                    $uname = $uname->fetch_assoc();
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a> <td class='col-sm-4 text-center'>" . $uname["USERNAME"] . " <td class='col-sm-2 text-center'>" . $row["POINTS"] . "</td>  <td class='col-sm-4 text-center'>" . $row["DATE_ASKED"] . "\n";
                }
             ?>
        </table>
    </div>
    </td>
    </table>
</body>
</html>