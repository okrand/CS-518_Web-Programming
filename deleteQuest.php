<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
            $query = "DELETE FROM QUESTIONS WHERE ID = " . $_SESSION["QNumber"] . ";";
            $sqlresult = sqlcommand($query, "DELETE");
            $url = '/question.php';
            redirect($url);
?>
