<?php 
include_once "thingsandstuff.php";
session_start();
?>

<?php
            $newTitle = test_input($_POST["newTitle"]);
            $newPhrase = test_input($_POST["newQuestion"]);
            $newTag1 = test_input($_POST["newTag1"]);
            $newTag2 = test_input($_POST["newTag2"]);
            $newTag3 = test_input($_POST["newTag3"]);
            $query = 'UPDATE QUESTIONS SET `QUESTION_TITLE` = "' . $newTitle . '", `QUESTION_PHRASE` = "' . $newPhrase . '", `TAG1` = "' . $newTag1 . '", `TAG2` = "' . $newTag2 . '", `TAG3` = "' . $newTag3 . '" WHERE ID = ' . $_SESSION["QNumber"] . ';';
            echo $query;
            $sqlresult = sqlcommand($query, "UPDATE");
            $url = '/question.php';
            redirect($url);
?>
