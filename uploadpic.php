<?php 
include_once "thingsandstuff.php";
session_start();
?>
<?php
$target_dir = "profilePics/";
$target_file1 = $target_dir . $_SESSION["UserID"] . "_" . $_SESSION["userName"] . ".";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
$target_file = $target_file1 . $imageFileType;
if(isset($_POST["deletepic"])){
    if (file_exists($target_file1 . 'jpg'))
        unlink($target_file1 . 'jpg');
    else if (file_exists($target_file1 . 'jpeg'))
        unlink($target_file1 . 'jpeg');
    else if (file_exists($target_file1 . 'png'))
        unlink($target_file1 . 'png');
    else if (file_exists($target_file1 . 'gif'))
        unlink($target_file1 . 'gif');
    $_SESSION["Upload"]=4;
    redirect("profile.php");
    exit;
}
    
// Check if image file is a actual image or fake image
if(isset($_POST["uploadpic"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        #echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    }
    else{
        $uploadOk = 0;
        $_SESSION["Upload"] = 3;
        redirect("profile.php");
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        #echo "File is not an image.";
        $_SESSION["Upload"]=3;
        redirect("profile.php");
        $uploadOk = 0;
    }
}

if ($_FILES["fileToUpload"]["size"] > 700000) {
    //echo "Sorry, your file is too large.";
    $_SESSION["Upload"]=2;
    redirect("profile.php");
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
        $_SESSION["Upload"]=0;//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        redirect("profile.php");
    } 
    else {
        $_SESSION["Upload"]=1;
        redirect("profile.php");
        #echo "Sorry, there was an error uploading your file.";
    }
}
        


?>