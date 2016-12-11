<?php 
include_once "thingsandstuff.php";
session_start();
?>
<?php //Update e-mail
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $avatarChoice = $_POST["optradio"];
        $upQuery = "UPDATE USERS SET `AVATAR` = " . $avatarChoice . " WHERE ID = " . $_SESSION["UserID"] . ";";
        $upresult = sqlcommand($upQuery, "UPDATE");
    }
    redirect("profile.php");
?>