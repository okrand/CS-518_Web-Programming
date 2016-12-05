<?php 
include_once "thingsandstuff.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Homepage for HighSide - The Motorcycle Q&A Website">
<title>HighSide - Motorcycle Experience Sharing Platform</title>
    <?php bringLibraries(); ?>
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
                    <span class="btn-group pull-right" >
						<a href="ask.php" class="btn btn-info"> Ask a Question!</a>
                    <?php 
                    if ($_SESSION["loggedIn"] != true){
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log in</a>';
                        echo '<a href="register.php" class="btn btn-info" role="button"> Register</a>';
                    }
                    else{
                        echo '<a href="login.php" class="btn btn-info" role="button"> Log out</a>';
                    }   
                    ?>
                        <a href="help.php" class="btn btn-info"> Help</a>
                    </span>
	</div>
    <hr style="clear:both;">
	<?php 
	if ($_SESSION["loggedIn"] != true)
	{
		echo "<h3 class='text-center'>We realized that you are riding these streets with no license plate. Please "; 
		echo '<a href="./login.php">login here</a>' . " or " . '<a href="./register.php">register here</a></h3>';
	}
	?>

	<!-- Unanswered Questions -->
        <table class="table">
        <tr><td>
        <header>
            <h3 class="text-center"> Here are some questions waiting for answers! </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-2 text-center">Asker</th><th class="col-sm-2 text-center">Points</th><th class="col-sm-6 text-center">Time</th></tr>
             <?php
             //get total number of unanswered questions
             $query1 = "SELECT COUNT(*) AS QCOUNT FROM QUESTIONS WHERE ANSWER_ID = 0;";
             $countresult = sqlcommand($query1, "SELECT");
             if ($countresult != "false"){
                 $countrow = $countresult->fetch_assoc();
                 $anscount = $countrow['QCOUNT'];
                 $numpages1 = $anscount / 5;
                 $numpages1 = ceil($numpages1);
             }
    
             if(isset($_GET['page1'])){
                 $page1 = $_GET['page1'];
                 if ($page1 > $numpages1 or $page1 < 1)
                     $page1 = 1;
             }
             else
                 $page1 = 1;
             
             $query = "SELECT * FROM QUESTIONS WHERE ANSWER_ID = 0 ORDER BY ID DESC LIMIT " . 5*($page1-1) .", " . 5 . ";";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()){
                    $que = "SELECT USERNAME FROM USERS WHERE ID = " . $row["ASKER_ID"] . ";";
                    $uname = sqlcommand($que, "SELECT");
                    $uname = $uname->fetch_assoc();
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a> <td class='col-sm-2 text-center'>" . $uname["USERNAME"] . "<td class='col-sm-2 text-center'>" . $row["POINTS"] . "</td> <td class='col-sm-6 text-center'>" . $row["DATE_ASKED"] . "\n";
                }
            
             ?>
        </table>
            <?php
            //insert pagination
        if (isset($numpages1)){
            echo '<div class="text-center">
            <ul id="pagin1" class="center pagination">';
            if ($page1 != 1){
                if (isset($_GET['page2']))
                    echo '<li id="firstpagin1"><a href="index.php?page2=' . $_GET['page2'] . '&page1=1">First</a></li>';
                else
                    echo '<li id="firstpagin1"><a href="index.php?page1=1">First</a></li>';
            }
            if ($page1 > 3){
                echo '<li class="disabled"><a href="">...</a></li>';
            }
            for($i = max(1, $page1 - 2); $i <= min($page1 + 2, $numpages1); $i++){
                if ($i != $page1){
                    if (isset($_GET['page2']))
                        echo '<li><a href="index.php?page2=' . $_GET['page2'] . '&page1=' . $i .'">'. $i .'</a></li>';
                    else
                        echo '<li><a href="index.php?page1=' . $i .'">'. $i .'</a></li>';
                }
                else
                    echo '<li class="page-item active"><a href="">'. $i .'</a></li>';
            }
            if ($i-1 < $numpages1)
                echo '<li class="disabled"><a href="">...</a></li>';
            if ($page1 != $numpages1){
                if (isset($_GET['page2']))
                     echo '<li><a href="index.php?page2=' . $_GET['page2'] . '&page1='.$numpages1.'">Last</a></li>';
                else
                    echo '<li><a href="index.php?page1='.$numpages1.'">Last</a></li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    </td>
    
    <!-- Best Questions -->
        <td>
        <header>
            <h3 class="text-center"> Here are the highest ranked questions! </h3>
        </header>
        <div class="table-hover table-responsive">
         <table class="table">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-2 text-center">Asker</th><th class="col-sm-2 text-center">Points</th><th class="col-sm-6 text-center">Time</th></tr>
             <?php
              //get total number of unanswered questions
             $query1 = "SELECT COUNT(*) AS QCOUNT FROM QUESTIONS WHERE ANSWER_ID = 0;";
             $countresult = sqlcommand($query1, "SELECT");
             if ($countresult != "false"){
                 $countrow = $countresult->fetch_assoc();
                 $anscount = $countrow['QCOUNT'];
                 $numpages2 = $anscount / 5;
                 $numpages2 = ceil($numpages2);
             }
            
             if(isset($_GET['page2'])){
                $page2 = $_GET['page2'];
                if ($page2 > $numpages2 or $page2 < 1)
                    $page2 = 1;
             }
             else
                 $page2 = 1;
             $query = "SELECT * FROM QUESTIONS ORDER BY POINTS DESC LIMIT " . 5*($page2-1) .", " . 5 . ";";
             $sqlresults = sqlcommand($query, "SELECT");
                while($row = $sqlresults->fetch_assoc()) {
                    $que = "SELECT USERNAME FROM USERS WHERE ID = " . $row["ASKER_ID"] . ";";
                    $uname = sqlcommand($que, "SELECT");
                    $uname = $uname->fetch_assoc();
                    echo "<tr><td class='col-sm-4 text-center'> <a href = 'question.php?QN=".$row["ID"]."'>" . $row["QUESTION_TITLE"] . "</a> <td class='col-sm-2 text-center'>" . $uname["USERNAME"] . " <td class='col-sm-2 text-center'>" . $row["POINTS"] . "</td>  <td class='col-sm-6 text-center'>" . $row["DATE_ASKED"] . "\n";
                }
             ?>
        </table>
            <?php
            //insert pagination
        if (isset($numpages2)){
            echo '<div class="text-center">
            <ul id="pagin2" class="center pagination">';
            if ($page2 != 1){
                if (isset($_GET['page1']))
                    echo '<li id="firstpagin2"><a href="index.php?page1=' . $_GET['page1'] . '&page2=1">First</a></li>';
                else
                    echo '<li id="firstpagin2"><a href="index.php?page2=1">First</a></li>';
            }
            if ($page2 > 3){
                echo '<li class="disabled"><a href="">...</a></li>';
            }
            for($i = max(1, $page2 - 2); $i <= min($page2 + 2, $numpages2); $i++){
                if ($i != $page2){
                    if (isset($_GET['page1']))
                        echo '<li><a href="index.php?page1=' . $_GET['page1'] . '&page2=' . $i .'">'. $i .'</a></li>';
                    else
                        echo '<li><a href="index.php?page2=' . $i .'">'. $i .'</a></li>';
                }
                else
                    echo '<li class="page-item active"><a href="">'. $i .'</a></li>';
            }
            if ($i-1 < $numpages2)
                echo '<li class="disabled"><a href="">...</a></li>';
            if ($page2 != $numpages2){
                if (isset($_GET['page1']))
                    echo '<li><a href="index.php?page1=' . $_GET['page1'] . '&page2='.$numpages2.'">Last</a></li>';
                else
                    echo '<li><a href="index.php?page2='.$numpages2.'">Last</a></li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    </td>
    </tr>
    </table>
</body>
</html>