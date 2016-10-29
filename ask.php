<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="New Question page for HighSide - The Motorcycle Q&A Website">
<title>It's probably the carburetor</title>
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
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
				<tr>
                    <div class="btn-group pull-left">
                        <?php
                            echo '<label class="btn btn-info disabled">Welcome <a href="profile.php">' . $_SESSION["userName"].'</a> ' . '<span class="badge">' . $_SESSION["K_Points"] . '</span></label>';
                        ?>
                    </div>
                    <div class="btn-group pull-right" >
						<a href="ask.php" class="btn btn-info disabled"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                    </div>
		</table>
	</div>
    
    <?php 
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = test_input($_POST["qTitle"]);
            $question = test_input($_POST["Question"]);
            $tag1 = test_input($_POST["Tag1"]);
            $tag2 = test_input($_POST["Tag2"]);
            $tag3 = test_input($_POST["Tag3"]);
            $askerid = $_SESSION["UserID"];
            
            $query = "INSERT INTO QUESTIONS (ASKER_ID, QUESTION_TITLE, QUESTION_PHRASE, TAG1, TAG2, TAG3, DATE_ASKED) VALUES (" . $askerid . ", '" .  $title . "', '" . $question . "', '" . $tag1 . "', '" . $tag2 . "', '". $tag3 . "', NOW());";
            $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult != true)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!";
            else{
                //get the last question's ID on the table
                $query = "SELECT ID FROM QUESTIONS WHERE ASKER_ID=" . $askerid . " ORDER BY ID DESC LIMIT 1";
                $lastQID = sqlcommand($query, "SELECT");
                $lastQID = $lastQID->fetch_assoc();
                $_SESSION["QNumber"] = $lastQID["ID"];
                $url = "question.php?QN=" . $_SESSION["QNumber"];
                redirect($url);
            }
       }
    ?>
    <div class="container">
    <span id="newQuest">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="qTitle" pattern=".{5,60}" required title="Your title needs to be between 5-60 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="question">Question:</label>
            <textarea type="text" name="Question" pattern=".{5,500}" required title="Your question needs to be between 5-500 characters" class="form-control" rows="5" id="question"></textarea>
        </div>
        <div class="form-group">
            <label for="tag1">Tag 1:</label>
            <input type="text" name="Tag1" pattern=".{3,20}" required title="You must have at least 1 tag between 3-20 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="tag2">Tag 2:</label>
            <input type="text" name="Tag2" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="tag3">Tag 3:</label>
            <input type="text" name="Tag3" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control">
        </div>
	<button type="submit" class="btn btn-primary center-block">Ask the experts! (They are not experts) </button>
	</form>
    </span>
    </div>
       <?php 
	if ($_SESSION["loggedIn"] != true)
	{
        echo '<script  type="text/javascript"> document.getElementById("newQuest").style.display="none"; </script>';
		echo "<h3 align='center'>Unfortunately, you have to be logged in to ask questions. I know, bummer! Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
	?>
</body>
</html>
    