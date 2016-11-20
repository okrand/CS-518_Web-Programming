<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
//submitting new answer
$answer = test_input($_POST["Answer"]);
$answer = str_replace("[", "&lt;", $answer);
$answer = str_replace("]", "&gt;", $answer);
$query = "INSERT INTO ANSWERS (QUEST_ID, USER_ID, ANSWER,DATE_ANSWERED) VALUES (".$_SESSION["QNumber"].", ".$_SESSION["UserID"].", '".$answer."', NOW());";
$sqlresult = sqlcommand($query, "INSERT");

//get total number of answers
$query1 = "SELECT COUNT(*) AS ANSCOUNT FROM ANSWERS WHERE QUEST_ID =".$_SESSION["QNumber"].";";
$countresult = sqlcommand($query1, "SELECT");
if ($countresult != "false"){
    $countrow = $countresult->fetch_assoc();
    $anscount = $countrow['ANSCOUNT'];
    $numpages = $anscount / 5;
    $numpages = ceil($numpages);
}
//Go back 
$url = 'question.php?page=' . $numpages;
redirect($url);
?>