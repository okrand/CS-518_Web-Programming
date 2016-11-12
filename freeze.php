<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
            $freeze = $_POST["freezeQuest"];
            $query = "UPDATE QUESTIONS SET `FROZEN` = " . $freeze . " WHERE ID = " . $_SESSION["QNumber"] . ";";
            $sqlresult = sqlcommand($query, "UPDATE");
            $url = '/question.php';
            redirect($url);
?>
