<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="New Question page for HighSide - The Motorcycle Q&A Website">
<title>It's probably the carburetor</title>
<?php bringLibraries(); ?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    tinymce.init({
        //forced_root_block : ""
        selector: "textarea",
        plugins: "codesample bbcode paste"
    });
</script>
</head>
<body>
    <header class="jumbotron text-center" style="background-color:white;">
        <h1>
        <a href="index.php">
        <img src="highside-logo.jpg" alt="HighSide" style="width:100px;height:150px;">
        </a>
        HighSide<br>Motorcycle Experience Sharing Platform </h1>
	</header>
    <div class="topMenu">
                    <div class="btn-group pull-left">
                        <?php
                            if (isset($_SESSION["UserID"])){
                            $query = "SELECT KARMA_POINTS FROM USERS WHERE ID = ". $_SESSION["UserID"] . ";";
                            $result = sqlcommand($query, "SELECT");
                            $result = $result->fetch_assoc();
                            echo '<label class="btn btn-info disabled">Welcome <a href="profile.php">' . $_SESSION["userName"].'</a> ' . '<span id="K_Points" class="badge">' . $result["KARMA_POINTS"] . '</span></label>';
                            }
                        ?>
                    </div>
                    <span style="float:left;">
                        <?php
                        // Search by USERNAME
                        if ($_SESSION["loggedIn"] == true){ 
                        echo '<form class="">
                        <input type="text" id="search" name="search" autocomplete="off" placeholder="Search.." onkeyup="showResult(this.value)">
                        <div id="usersearch"></div>
                        </form>';
                        } 
                        ?>
                    </span>
                    <div class="btn-group pull-right" >
						<a href="ask.php" class="btn btn-info disabled"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                        <a href="help.php" class="btn btn-info"> Help</a>
                    </div>
	</div>
    <hr style="clear:both;">
    <?php 
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = test_input($_POST["qTitle"]);
            $question = test_input($_POST["Question"]);
           //prepare $question for bbcode
            $question = str_replace("[", "&lt;", $question);
            $question = str_replace("]", "&gt;", $question);
        
            $tag1 = test_input($_POST["Tag1"]);
            $tag2 = test_input($_POST["Tag2"]);
            $tag3 = test_input($_POST["Tag3"]);
            $askerid = $_SESSION["UserID"];
            
            $query = "INSERT INTO QUESTIONS (ASKER_ID, QUESTION_TITLE, QUESTION_PHRASE, TAG1, TAG2, TAG3, DATE_ASKED) VALUES (" . $askerid . ", '" .  $title . "', '" . $question . "', '" . $tag1 . "', '" . $tag2 . "', '". $tag3 . "', NOW());";
            $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult != true)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!";
            else{
                //get the last question's ID on the table
                $query = "SELECT ID FROM QUESTIONS WHERE ASKER_ID=" . $askerid . " ORDER BY ID DESC LIMIT 1";
                $lastQID = sqlcommand($query, "SELECT");
                $lastQID = $lastQID->fetch_assoc();
                $_SESSION["QNumber"] = $lastQID["ID"];
                
                //upload question picture
                $target_dir = "questPics/";
                $target_file1 = $target_dir . $_SESSION["QNumber"] . ".";
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
                     //echo "Sorry, your file is too large.";
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
                
                $url = "question.php?QN=" . $_SESSION["QNumber"];
                redirect($url);
            }
       }
    ?>
    <div class="container">
    <span id="newQuest">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="qTitle" pattern=".{5,60}" required title="Your title needs to be between 5-60 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="question">Question:</label>
            <textarea type="text" name="Question" pattern=".{5,500}" required title="Your question needs to be between 5-500 characters" class="form-control" rows="5" id="question"></textarea>
        </div>
        <div class="form-group">
            <label for="tag1">Tag 1:</label>
            <input type="text" name="Tag1" pattern=".{3,20}" required title="You must have at least 1 tag between 3-20 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="tag2">Tag 2:</label>
            <input type="text" name="Tag2" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control">
        </div>
        <div class="form-group">
            <label for="tag3">Tag 3:</label>
            <input type="text" name="Tag3" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control">
        </div>
        <strong>Select image to upload:</strong> <input type="file" name="fileToUpload" id="fileToUpload"> 
	<button type="submit" class="btn btn-primary center-block" onclick="tinyMCE.triggerSave();">Ask the experts! (They are not experts) </button>
	</form>
    </span>
    </div>
       <?php 
	if ($_SESSION["loggedIn"] != true)
	{
        echo '<script  type="text/javascript"> document.getElementById("newQuest").style.display="none"; </script>';
		echo "<h3 class='text-center'>Unfortunately, you have to be logged in to ask questions. I know, bummer! Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
	?>
</body>
</html>
    