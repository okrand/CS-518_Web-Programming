<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Q&A page for HighSide - The Motorcycle Q&A Website">
<title>Let's see what our experts said</title>
<?php bringLibraries(); ?>
</head>
<body>    
   <header class="jumbotron text-center container-fluid" style="background-color:white;">
        <h1>
        <a href="index.php">
        <img src="highside-logo.jpg" alt="HighSide" style="width:100px;height:150px;">
        </a>
        HighSide<br>Motorcycle Experience Sharing Platform </h1>
	</header>
    <div class="topMenu">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
				<tr>
                    <div class="btn-group pull-left">
                        <?php
                        echo '<label class="btn btn-info disabled">Welcome ' . $_SESSION["userName"].' ' . '<span class="badge">' . $_SESSION["K_Points"] . '</span></label>';                        
                        ?>
                    </div>
                    <div class="btn-group pull-right" >
                        <button type="button" data-toggle="modal" data-target="#uploadpic" class="btn btn-info">Change Picture</button>
						<a href="ask.php" class="btn btn-info"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                    </div>
		</table>
	</div>
    
    <!-- Profile Info-->
    <div class="container" style="float:left;">
    
    </div>
    <!-- Display picture upload error -->
    <?php
    $referer = pagename($_SERVER["HTTP_REFERER"]);
    if ($referer == "/uploadpic.php"){
        if ($_SESSION["Upload"]==0)
            echo "<div align='center' class='alert alert-warning'><strong>Picture Uploaded Successfully!</strong></div>";
        else if ($_SESSION["Upload"]==1)
            echo "<div align='center' class='alert alert-warning'><strong>There was a problem uploading your picture!</strong></div>";
        else if ($_SESSION["Upload"]==2)
            echo "<div align='center' class='alert alert-warning'><strong>Whoa! That file's too big man (3MB Max)</strong></div>";
        else if ($_SESSION["Upload"]==3)
            echo "<div align='center' class='alert alert-warning'><strong>Your profile 'Picture' needs to be a... you guessed it, PICTURE</strong></div>";
    }
    ?>
    <!-- Upload Picture -->
    <div id="uploadpic" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Profile Picture</h4>
                </div>
            <div class="modal-body">
                <form id="up" action="uploadpic.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload"> 
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" form="up" type="submit" name="submit">Upload Image</button>
            </div>
            </div>
        </div>
    </div>
    
    <!--My Questions -->
    <div class="container" >
        <header>
        <h3 align="center"> My Questions </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table" id="myTable">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-4 text-center">Points</th><th class="col-sm-4 text-center">Time</th></tr>
             <?php
             $query = "SELECT * FROM QUESTIONS WHERE ASKER_ID = " . $_SESSION["UserID"] ." ORDER BY ID DESC;";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()){
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a> <td class='col-sm-4 text-center'>". $row["POINTS"] . "<td class='col-sm-4 text-center'>" . $row["DATE_ASKED"] . "\n";
                }
             ?>
        </table>
        <script>
            $(document).ready(function(){
                $('#myTable').dataTable();
            });
        </script>
    </div>
    </div>
</body>
</html>