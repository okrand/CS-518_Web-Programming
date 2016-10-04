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
<meta name="description" content="New Question page for HighSide - The Motorcycle Q&A Website">
<title>It's probably the carburetor</title>
<link rel="style.sheet" type="text/css" href="./style.css" media="all">
</head>
<body>
    <?php 
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = test_input($_POST["qTitle"]);
            $question = test_input($_POST["Question"]);
            $tag1 = test_input($_POST["Tag1"]);
            $tag2 = test_input($_POST["Tag2"]);
            $tag3 = test_input($_POST["Tag3"]);
            $askerid = $_SESSION["UserID"];
           $query = "INSERT INTO QUESTIONS (ASKER_ID, QUESTION_TITLE, QUESTION_PHRASE, TAG1, TAG2, TAG3, DATE_ASKED) VALUES (" . $askerid . ",'" .  $title . "','" . $question . "','" . $tag1 . "','" . $tag2 . "','". $tag3 . "',NOW());";
           $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult != true)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!";
            else{
                //get the last question's ID on the table
                $query = "SELECT ID FROM QUESTIONS ORDER BY ID DESC LIMIT 1";
                $lastQID = sqlcommand($query, "SELECT");
                $_SESSION["QNumber"] = $lastQID["ID"];
                
                //echo '<meta http-equiv="refresh" content="2;url=question.php"/>';
                header('location: question.php');
                exit();
                session_write_close();   
            }
       }
        
    ?>
    
    <span id="newQuest">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
	Title: <input type="text" name="qTitle" maxlength="60"><br><br>
	Question: <input type="text" name="Question" maxlength="500"><br><br>
    Tag 1: <input type="text" name="Tag1" maxlength="20"><br><br>
    Tag 2: <input type="text" name="Tag2" maxlength="20"><br><br>
    Tag 3: <input type="text" name="Tag3" maxlength="20"><br><br>
	<input type="Submit" >
	</form>
    </span>
    
    <?php 
    if ($_SESSION["loggedIn"] != true){
        echo '<script  type="text/javascript"> document.getElementById("newQuest").style.display="none"; </script>';
        echo 'Unfortunately, you have to be logged in to ask questions. I know, bummer! ' . '<a href="./login.php">Click me to go to the login page</a>';
    }
    ?>
</body>
</html>
    