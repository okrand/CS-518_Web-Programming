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
    if(isset($_GET['QN'])) //if there is a get question, make that value the session for QNumber
        $_SESSION["QNumber"] = $_GET['QN'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //submitting new answer
            $answer = test_input($_POST["Answer"]);
            $query = "INSERT INTO ANSWERS (QUEST_ID, USER_ID, ANSWER,DATE_ANSWERED) VALUES (".$_SESSION["QNumber"].", ".$_SESSION["UserID"].", '".$answer."', NOW());";
            $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult == false)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!\n";
            else{
                //echo '<meta http-equiv="refresh" content="2;url=question.php"/>';
                header('location: question.php');
                exit();
                session_write_close();
                
            }
        }
    ?>
    <div class="container">
    <?php
    $query = "SELECT * FROM QUESTIONS WHERE ID =".$_SESSION["QNumber"].";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qTitle = $sqlresult["QUESTION_TITLE"];
    $qPhrase = $sqlresult["QUESTION_PHRASE"];
    $qDate = $sqlresult["DATE_ASKED"];
    $qAsker = $sqlresult["ASKER_ID"];
    $answerID = $sqlresult["ANSWER_ID"];
    $query = "SELECT * FROM USERS WHERE ID =" . $qAsker . ";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qAsker = $sqlresult["USERNAME"];
    echo '<div class="page-header">';
    echo '<h1>' . $qTitle . '</h1>';
    echo '<h3>' . $qPhrase . '</h3>';
    echo '<h5 align="right">' . $qAsker . '</h5>';
    echo '<h6 align="right">' . $qDate . '</h6>'; 
    echo '</div>';
    echo "<h3 align='left'>Answers</h3>";
    
    //check if there is a selected answer
        if ($answerID != '0'){ 
            $queryanswer = "SELECT * FROM ANSWERS WHERE ID = ". $answerID . ";";
            $getanswer = sqlcommand($queryanswer, "SELECT");
            $getanswer = $getanswer->fetch_assoc();
            echo "<div class='well' style='background-color:#66ff33'><p>" . $getanswer["ANSWER"] . "</p>";
            $correctanswerer = $getanswer["USER_ID"];
            $correctdate = $getanswer["DATE_ANSWERED"];
            $queryanswer = "SELECT USERNAME FROM USERS WHERE ID=" . $correctanswerer.";";
            $result3 = sqlcommand($queryanswer, "SELECT");
            $result3 = $result3->fetch_assoc();
            $correctanswerer = $result3["USERNAME"];
            echo '<div>';
            echo '<h5  align="right">' . $correctanswerer . '</h5></div>';
            echo '<h6 align="right">' . $correctdate . '</h6>';
            echo '</div>';
        }
    
    //List answers
    $query = "SELECT * FROM ANSWERS WHERE ID <> " . $answerID . " AND QUEST_ID =".$_SESSION["QNumber"].";";
    $result = sqlcommand($query, "SELECT");
    if ($result == false){
        echo "No answers yet. Check back again soon!\n";
    }
    else{
        while($row = $result->fetch_assoc()) {
            $answerlistid = $row["ID"];
            echo "<div class='well' ><p>" . $row["ANSWER"] . "</p>"; //style='background-color:#66ff33' for right answer
            $answerer = $row["USER_ID"];
            $query = "SELECT USERNAME FROM USERS WHERE ID=" . $answerer.";";
            $result2 = sqlcommand($query, "SELECT");
            $result2 = $result2->fetch_assoc();
            $answerer = $result2["USERNAME"];
            echo '<div>';
            //if question hasn't been answered or if userID isn't the person who asked the question, make the button invisible
            if ($answerID == '0' and $_SESSION["userName"] == $qAsker){
                echo '<button type="submit" name="AnswerSubmit" value="'.$answerlistid.'" form="correct" class="btn btn-info" style="float:left;" >THIS IS THE ANSWER!</button>';
            }
            echo '<h5  align="right">' . $answerer . '</h5></div>';
            echo '<h6 align="right">' . $row["DATE_ANSWERED"] . '</h6>';
            echo '</div>';
        }
    }
    ?>
    <span>
        <form id="correct" action="correctans.php" method="POST">
        </form>
        </span>
   <span id = "newAns">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="form-group">
            <label for="Answer">Your Answer:</label>
            <textarea type="text" name="Answer" pattern=".{5,500}" required title="Your answer needs to be between 5-500 characters" rows="5" id="Answer" class="form-control"></textarea>
        </div>
    <button type="submit" class="btn btn-primary center-block">Submit Answer!</button>
	</form>
    </span>
    </div>
    <?php 
	if ($_SESSION["loggedIn"] != true)
	{
        echo '<script  type="text/javascript"> document.getElementById("newAns").style.display="none"; </script>';
		echo "<h3 align='center'>Unfortunately, you have to be logged in to answer questions. I know, bummer! Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
    ?>
	
    
</body>
</html>