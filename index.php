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
    <div class="topMenu">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
				<tr>
                    <div class="btn-group pull-left">
                        <?php
                            echo '<label class="btn btn-info disabled">Welcome ' . $_SESSION["userName"].' ' . '<span class="badge">' . $_SESSION["K_Points"] . '</span></label>';
                        ?>
                    </div>
                    <div class="btn-group pull-right" >
						<a href="ask.php" class="btn btn-info"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true)
					   echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                    </div>
		</table>
	</div>
	<?php 
	if ($_SESSION["loggedIn"] != true)
	{
		echo "<h3 align='center'>We realized that you are riding these streets with no license plate. Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
	?>

	
    <div class="container" >
        <header>
            <h3 align="center"> Here are some questions waiting for answers! </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table">
             <tr><th class="col-sm-8 text-center">Question</th><th class="col-sm-4 text-center">Time</th></tr>
             <?php
             $query = "SELECT * FROM QUESTIONS WHERE ANSWER_ID = 0 ORDER BY ID DESC LIMIT 5;";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()) 
                    echo "<tr><td class='col-sm-8 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a><td class='col-sm-4 text-center'>" . $row["DATE_ASKED"] . "\n";
             ?>
        </table>
    </div>
    </div>
</body>
</html>