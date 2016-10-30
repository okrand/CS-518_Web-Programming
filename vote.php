<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
$threadID = $_POST["ID"];
$voterID = $_SESSION["UserID"];
$type = $_POST["votingWhat"];
$vote = $_POST["voteType"]; //1 = upvote , 2 = downvote, 0 = novote


$selQ = "SELECT * FROM VOTES WHERE VOTER_ID = " . $voterID . " AND THREAD_TYPE = '" . $type . "' AND THREAD_ID = " . $threadID . ";";
$selresult = sqlcommand($selQ, "SELECT");
if ($selresult == false){
    $query = "INSERT INTO VOTES (VOTER_ID, THREAD_TYPE, THREAD_ID, UPORDOWN, VOTE_TIME) VALUES (" .$voterID . ", '" . $type . "', " . $threadID . ", " . $vote . ", NOW());";
    sqlcommand($query, "INSERT");
}
$selresult = $selresult->fetch_assoc();
$lastvote = $selresult["UPORDOWN"];
if ($lastvote != $vote){
    $query = "UPDATE VOTES SET UPORDOWN= " . $vote . ", VOTE_TIME= NOW() WHERE VOTER_ID = " . $voterID . " AND THREAD_TYPE = '" . $type . "' AND THREAD_ID = " . $threadID . ";";
    sqlcommand($query, "UPDATE");

if ($type == "Q"){ //update Question point
    if ($vote == 1)
        $query = "UPDATE QUESTIONS SET POINTS = POINTS+1 WHERE ID = " . $threadID . ";";
    else if ($vote == 2)
        $query = "UPDATE QUESTIONS SET POINTS = POINTS-1 WHERE ID = " . $threadID . ";";
    sqlcommand($query, "UPDATE");
}
else{ //update Answer point
    if ($vote == 1)
        $query = "UPDATE ANSWERS SET POINTS = POINTS+1 WHERE ID = " . $threadID . ";";
    else if ($vote == 2)
        $query = "UPDATE ANSWERS SET POINTS = POINTS-1 WHERE ID = " . $threadID . ";";
    sqlcommand($query, "UPDATE");
}

//update user point
if ($vote == 1)
    $query = "UPDATE USERS SET KARMA_POINTS = KARMA_POINTS+1 WHERE ID = " . $voterID . ";";
else if ($vote == 2)
    $query = "UPDATE USERS SET KARMA_POINTS = KARMA_POINTS-1 WHERE ID = " . $voterID . ";";
sqlcommand($query, "UPDATE");
}
?>