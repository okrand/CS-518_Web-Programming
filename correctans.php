<?php 
include_once "thingsandstuff.php";
session_start(); 
?>
<?php
//selecting correct answer
$correctanswerid = $_POST["AnswerSubmit"];
$correctquery = 'UPDATE QUESTIONS SET ANSWER_ID=' . $correctanswerid . ' WHERE ID='. $_SESSION["QNumber"];
$corr = sqlcommand($correctquery, "UPDATE");
//echo '<meta http-equiv="refresh" content="2;url=question.php"/>';
header('location: question.php');
exit();
session_write_close();
?>