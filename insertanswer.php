<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
            //submitting new answer
            $answer = test_input($_POST["Answer"]);
            $query = "INSERT INTO ANSWERS (QUEST_ID, USER_ID, ANSWER,DATE_ANSWERED) VALUES (".$_SESSION["QNumber"].", ".$_SESSION["UserID"].", '".$answer."', NOW());";
            $sqlresult = sqlcommand($query, "INSERT");
            $url = '/question.php';
            redirect($url);
?>