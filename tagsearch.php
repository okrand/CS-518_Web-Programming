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
                        if ($_SESSION["loggedIn"] == true){
                        echo '<button type="button" id="btnSearchUser" class="btn btn-info disabled" onclick="switchSearch(\'user\');">Users</button>
                        <button type="button" id="btnSearchTag" class="btn btn-info" onclick="switchSearch(\'tag\');">Tags</button>';
                        }
                        ?>
                    </div>
                    <span style="float:left;">
                        <?php
                        // Search by USERNAME
                        if ($_SESSION["loggedIn"] == true){ 
                        //tag search box
                        echo '<input type="text" id="searchtag" name="searchtag" placeholder="Search.." onkeyup="if(event.keyCode == 13){SearchForTag();}">';
                        //user search box
                        echo '<form class="">
                        <input type="text" id="search" name="search" autocomplete="off" placeholder="Search.." onkeyup="showResult(this.value)">
                        <div id="usersearch"></div>
                        </form>';
                        } 
                        ?>
                    </span>
                    <div class="btn-group pull-right" >
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
                    </div>
	</div>
    <hr style="clear:both;">
    <?php 
    $tag = strtolower($_GET["tag"]);
    
    ?>
    <!--My Questions -->
    <div class="container" >
        <header>
        <h3 class="text-center"> Here are the questions about <?php echo $tag;?> </h3>
        </header>
        <div class="table-hover table-responsive">
            <?php
            echo'<table class="table" id="myTable">
             <tr><th class="col-sm-4 text-center">Question</th><th class="col-sm-4 text-center">Points</th><th class="col-sm-4 text-center">Time</th></tr>';
             
             //get total number of answers
             $query1 = "SELECT COUNT(*) AS QCOUNT FROM QUESTIONS WHERE TAG LIKE '" . $tag . "';";
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
            
             $query = "SELECT * FROM QUESTIONS WHERE TAG LIKE '%" . $tag . "%' ORDER BY ID DESC LIMIT " . 5*($page-1) .", " . 5 . ";";
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