<?php 
include_once "header.php";
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Q&A page for HighSide - The Motorcycle Q&A Website">
<title>Let's see what our experts said</title>
<link rel="stylesheet" type="text/css" href="./style.css" media="all">
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $answer = test_input($_POST["Answer"]);
        $query = "INSERT INTO ANSWERS (QUEST_ID, USER_ID, ANSWER, POINTS, DATE_ANSWERED) VALUES (".$qID.", ".$_SESSION["UserID"].", '".$answer."', 0, NOW());";
        $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult == false)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!";
            else{
                 //echo '<meta http-equiv="refresh" content="2;url=question.php"/>';
                header('location: .');
                exit();
                session_write_close();
            }
    }
    ?>
    
    <?php
    $qID = $_SESSION["QNumber"];
    $query = "SELECT * FROM QUESTIONS WHERE ID =".$qID.";";
    $sqlresult = sqlcommand($query, "SELECT");
    $qTitle = $sqlresult["QUESTION_TITLE"];
    $qPhrase = $sqlresult["QUESTION_PHRASE"];
    echo $qTitle . "<br>" . $qPhrase . "<br><br>";
    
    //List answers
    $query = "SELECT * FROM ANSWERS WHERE QUEST_ID =".$qID.";";
    $result = sqlcommand($query, "SELECTMulti");
    if ($result == false)
        echo "No answers yet. Check back again soon!";
    else{
        echo "<table padding=2 border=1>\n";
        while($row = $result->fetch_assoc()) 
            echo "<tr><td>" . $row["ANSWER"] . "<br>";
        echo "</table>\n";
    }
    ?>
   
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
	Your Answer: <input type="text" name="Answer" maxlength="500"><br><br>
	<input type="Submit" >
	</form>
    
</body>
</html>