<?php 
include_once "thingsandstuff.php";
session_start();
?>
<?php //Update e-mail
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = test_input($_POST["email"]);
        $upQuery = "UPDATE USERS SET `EMAIL` = '" . $email . "' WHERE ID = " . $_SESSION["UserID"] . ";";
        $upresult = sqlcommand($upQuery, "UPDATE");
    }
    redirect("profile.php");
?>