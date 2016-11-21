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
?>

<?php
$query2 = "SELECT ID, QUEST_ID FROM ANSWERS ORDER BY ID DESC LIMIT 1";
$answerinfo = sqlcommand($query2, "SELECT");
$answerinfo = $answerinfo->fetch_assoc();
$answerid = $answerinfo["ID"];
$questid = $answerinfo["QUEST_ID"];

$target_dir = "answerPics/";
$target_file1 = $target_dir . $questid . "_" . $answerid . ".";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
$target_file = $target_file1 . $imageFileType;
// Check if image file is a actual image or fake image
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        //echo "File is not an image.";
        $_SESSION["Upload"]=3;
        $uploadOk = 0;
    }

if ($_FILES["fileToUpload"]["size"] > 700000) {
   // echo "Sorry, your file is too large.";
    $_SESSION["Upload"]=2;
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk != 0) {
    if (file_exists($target_file1 . 'jpg'))
        unlink($target_file1 . 'jpg');
    else if (file_exists($target_file1 . 'jpeg'))
        unlink($target_file1 . 'jpeg');
    else if (file_exists($target_file1 . 'png'))
        unlink($target_file1 . 'png');
    else if (file_exists($target_file1 . 'gif'))
        unlink($target_file1 . 'gif');
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $_SESSION["Upload"]=0;
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    }    
    else {
        $_SESSION["Upload"]=1;
        //echo "Sorry, there was an error uploading your file.";
    }
}

?>

<?php
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

