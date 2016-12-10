<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Q&A page for HighSide - The Motorcycle Q&A Website">
<title>Hey, you!</title>
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
    <?php
    if(isset($_GET['name'])) //if there is a get name, make it their profile, otherwise user's profile
        $viewName = $_GET['name'];
    else
        $viewName = $_SESSION["userName"];
    ?>
    <div class="topMenu">
                    <span class="btn-group pull-left">
                        <?php
                            if (isset($_SESSION["UserID"])){
                            $query = "SELECT KARMA_POINTS FROM USERS WHERE ID = ". $_SESSION["UserID"] . ";";
                            $result = sqlcommand($query, "SELECT");
                            $result = $result->fetch_assoc();
                            echo '<label class="btn btn-info disabled">Welcome <a href="profile.php">' . $_SESSION["userName"].'</a> ' . '<span id="K_Points" class="badge">' . $result["KARMA_POINTS"] . '</span></label>';
                            }
                        ?>
                    </span>
                    <span class="btn-group pull-right" >
                        <?php 
                        if ($viewName == $_SESSION["userName"]){
                            echo '<button type="button" data-toggle="modal" data-target="#updatemail" class="btn btn-info" onclick="document.getElementById(\'email\').focus();">Update E-mail</button>';
                            echo '<button type="button" data-toggle="modal" data-target="#uploadpic" class="btn btn-info">Change Picture</button>';
                        }
                        ?>
						<a href="ask.php" class="btn btn-info"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    ?>
                        <a href="help.php" class="btn btn-info"> Help</a>
                    </span>
	</div>
    <hr style="clear:both;">
    <!-- Profile Info-->
    <?php 
    $viewquery = "SELECT * FROM USERS WHERE USERNAME = '" . $viewName . "';";
    $viewresult = sqlcommand($viewquery, "SELECT");
    $viewresult = $viewresult->fetch_assoc();
    $viewID = $viewresult["ID"];
    $viewPoints = $viewresult["KARMA_POINTS"];
    $viewLActive = $viewresult["LAST_ACTIVE"];
    $viewEmail = $viewresult["E-MAIL"];
    $picname = "profilePics/" . $viewID . '_' . $viewName . '.';
    $picname = picext($picname);
    $viewcountquery = "SELECT COUNT(*) AS QCOUNT FROM QUESTIONS WHERE ASKER_ID = " . $viewID . ";";
    $countresult = sqlcommand($viewcountquery, "SELECT");
    $countresult = $countresult->fetch_assoc();
    $qcount = $countresult["QCOUNT"];
    ?>
    <!-- Display picture upload error -->
    <?php
    $referer = pagename($_SERVER["HTTP_REFERER"]);
    if (isset($_SESSION["Upload"])){
    //if ($referer == "/profile.php"){
        if ($_SESSION["Upload"]==0)
            echo "<div class='alert alert-warning text-center'><strong>Picture Uploaded Successfully!</strong></div>";
        else if ($_SESSION["Upload"]==1)
            echo "<div class='alert alert-warning text-center'><strong>There was a problem uploading your picture!</strong></div>";
        else if ($_SESSION["Upload"]==2)
            echo "<div class='alert alert-warning text-center'><strong>Whoa! That file's too big man (700KB Max)</strong></div>";
        else if ($_SESSION["Upload"]==3)
            echo "<div class='alert alert-warning text-center'><strong>Your profile 'Picture' needs to be a... you guessed it, PICTURE</strong></div>";
    unset($_SESSION["Upload"]);
    }
    ?>
    <div class="well">
        <div class="media">
        <div class="media-body">
            <h4 class="media-heading"><?php echo $viewName; ?></h4>
            <p>Points: <?php echo $viewPoints . '<br>Number of Questions: ' . $qcount . '<br>';
                if ($viewEmail != NULL)
                    echo 'E-mail: ' . $viewEmail . '<br>';
                ?> </p>
        </div>
        <div class="media-right">
            
            <?php echo '<img src=' . $picname . ' class="media-object" alt="profile_picture" style="width:160px; height:100px;">';   ?>
        </div>
        </div>
    </div>
    
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
    <!-- Update e-mail address -->
    <div id="updatemail" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update E-mail Address</h4>
                </div>
            <div class="modal-body">
                <form id="update" action="updatemail.php" method="post">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" placeholder="Enter new e-mail address" class="form-control" name="email" id="email">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" form="update" type="submit" name="submit">Update</button>
            </div>
            </div>
        </div>
    </div>
    
    <!--My Questions -->
    <div class="container" >
        <header>
        <h3 class="text-center"> My Questions </h3>
        </header>
        <div class="table-hover table-responsive">
            <?php
            echo'<table class="table" id="myTable">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-4 text-center">Points</th><th class="col-sm-4 text-center">Time</th></tr>';
             
             //get total number of answers
             $query1 = "SELECT COUNT(*) AS QCOUNT FROM QUESTIONS WHERE ASKER_ID = " . $viewID . ";";
             $countresult = sqlcommand($query1, "SELECT");
             if ($countresult != "false"){
                 $countrow = $countresult->fetch_assoc();
                 $anscount = $countrow['QCOUNT'];
                 $numpages = $anscount / 5;
                 $numpages = ceil($numpages);
             }
             else
                 echo "No questions yet. Ask one <a href='ask.php'>here</a>";
    
             if(isset($_GET['page'])){
                 $page = $_GET['page'];
             if ($page > $numpages)
                 redirect("profile.php");
             }
             else
                 $page = 1;
            
             $query = "SELECT * FROM QUESTIONS WHERE ASKER_ID = " . $viewID ." ORDER BY ID DESC LIMIT " . 5*($page-1) .", " . 5 . ";";
             $sqlresults = sqlcommand($query, "SELECT");
             if ($sqlresults != false){
                while($row = $sqlresults->fetch_assoc()){
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a></td> <td class='col-sm-4 text-center'>". $row["POINTS"] . "</td><td class='col-sm-4 text-center'>" . $row["DATE_ASKED"] . "</td></tr>\n";
                }
             }
            echo '</table>';
            //insert pagination
        if (isset($numpages)){
            echo '<div class="text-center">
            <ul id="pagin" class="center pagination">';
            if ($page != 1)
            echo '<li id="firstpagin"><a href="profile.php?page=1">First</a></li>';
            if ($page > 3){
                echo '<li class="disabled"><a href="">...</a></li>';
            }
            for($i = max(1, $page - 2); $i <= min($page + 2, $numpages); $i++){
                if ($i != $page)
                    echo '<li><a href="profile.php?page=' . $i .'">'. $i .'</a></li>';
                else
                    echo '<li class="page-item active"><a href="">'. $i .'</a></li>';
            }
            if ($i-1 < $numpages)
                echo '<li class="disabled"><a href="">...</a></li>';
            if ($page != $numpages)
                echo '<li><a href="profile.php?page='.$numpages.'">Last</a></li>';
            echo '</ul></div>';
        }
            ?>
        <script>
            $(document).ready(function(){
                $('#myTable').dataTable();
            });
        </script>
    </div>
    </div>
</body>
</html>