<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
$ID = $_POST["ID"];
$voterID = $_SESSION["UserID"];
$type = $_POST["votingWhat"];
$vote = $_POST["voteType"]; //1 = upvote , 2 = downvote

$query = "INSERT INTO VOTES (VOTER_ID, THREAD_TYPE, THREAD_ID, UPORDOWN, VOTE_TIME) VALUES (" .$ID . ", 32, 1, NOW());";
sqlcommand($query, "INSERT");


?>