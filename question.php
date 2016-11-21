<?php 
include_once "thingsandstuff.php";
session_start();

function getvotes($type, $threadID){
        $voteQuery = "SELECT * FROM VOTES WHERE VOTER_ID = " . $_SESSION["UserID"] . " AND THREAD_TYPE = '" . $type . "' AND THREAD_ID = " . $threadID . ";";
        $voteresult = sqlcommand($voteQuery, "SELECT");
        if ($voteresult == false)
            return 0;
        $voteresult = $voteresult->fetch_assoc();
        return $voteresult["UPORDOWN"];
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Q&A page for HighSide - The Motorcycle Q&A Website">
<title>Let's see what our experts said</title>
<?php bringLibraries(); ?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    tinymce.init({
        //forced_root_block : ""
        selector: "textarea",
        plugins: "codesample paste"
    });
</script>
<script>
function CheckLength()
{
tinyMCE.triggerSave();
var msg_area = document.getElementById("lengthalert");
msg_area.innerHTML = "";
if (document.getElementById("Answer").value.length < 2) {
    msg_area.style.display = 'block';
    msg_area.innerHTML = "<strong>Your answer needs to be between 2-500 characters</strong>";
}
else 
    document.getElementById("newAnswer").submit();
}
</script>
<!--Voting script -->
    <script>
    function vote(upOrDown, QorA, threadID, OID, UID){
    if (QorA == "Q"){
        if (upOrDown == 1){ //upvote
            if (document.getElementById("votedownQ").getAttribute("src") == "downvoteActive.png") {
                document.getElementById("qPoint").innerHTML++;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML++;
            }
            if (document.getElementById("voteupQ").getAttribute("src") != "upvoteActive.png") {
                document.getElementById("qPoint").innerHTML++;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML++;
            }
            document.getElementById("voteupQ").src = "upvoteActive.png";
            document.getElementById("votedownQ").src = "downvote.png";
        }
        else if (upOrDown == 0){
            document.getElementById("voteupQ").src = "upvote.png";
            document.getElementById("votedownQ").src = "downvote.png";
        }
        else{ //downvote
            if (document.getElementById("voteupQ").getAttribute("src") == "upvoteActive.png") {
                document.getElementById("qPoint").innerHTML--;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML--;
            }
            if (document.getElementById("votedownQ").getAttribute("src") != "downvoteActive.png") {
                document.getElementById("qPoint").innerHTML--;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML--;
            }
            document.getElementById("votedownQ").src = "downvoteActive.png";
            document.getElementById("voteupQ").src = "upvote.png";
        }
        }
    if (QorA == "A"){
        var up="voteupA" + threadID;
        var down="votedownA" + threadID;
        var point="aPoint"+threadID;
        if (upOrDown == 1){
            if (document.getElementById(down).getAttribute("src") == "downvoteActive.png") {
                document.getElementById(point).innerHTML++;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML++;
            }
            if (document.getElementById(up).getAttribute("src") != "upvoteActive.png") {
                document.getElementById(point).innerHTML++;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML++;
            }
            document.getElementById(up).src = "upvoteActive.png";
            document.getElementById(down).src = "downvote.png";
        }
        else if (upOrDown == 0){
            document.getElementById(up).src = "upvote.png";
            document.getElementById(down).src = "downvote.png";
        }
        else{
            if (document.getElementById(up).getAttribute("src") == "upvoteActive.png") {
                document.getElementById(point).innerHTML--;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML--;
            }
            if (document.getElementById(down).getAttribute("src") != "downvoteActive.png") {
                document.getElementById(point).innerHTML--;
                if (OID == UID)
                    document.getElementById("K_Points").innerHTML--;
            }
            document.getElementById(down).src = "downvoteActive.png";
            document.getElementById(up).src = "upvote.png";
        }
        
    }
       $.post("vote.php", {voteType: upOrDown, votingWhat: QorA, ID: threadID, OwnerID: OID});
    }
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
                    </div>
	</div>
    <hr style="clear:both;">
    
    <?php
    if(isset($_GET['QN'])) //if there is a get question, make that value the session for QNumber
        $_SESSION["QNumber"] = $_GET['QN'];
    ?>
    <div class="container">
    <?php
    $query = "SELECT * FROM QUESTIONS WHERE ID =".$_SESSION["QNumber"].";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qTitle = $sqlresult["QUESTION_TITLE"];
    $qPhrase = $sqlresult["QUESTION_PHRASE"];
    $qPhrase = str_replace("&lt;", "<", $qPhrase);
    $qPhrase = str_replace("&gt;", ">", $qPhrase);
    $qTag1 = $sqlresult["TAG1"];
    $qTag2 = $sqlresult["TAG2"];
    $qTag3 = $sqlresult["TAG3"];
    $qPoints = $sqlresult["POINTS"];
    $qDate = $sqlresult["DATE_ASKED"];
    $qAskerid = $sqlresult["ASKER_ID"];
    $answerID = $sqlresult["ANSWER_ID"];
    $frozen = $sqlresult["FROZEN"];
    $query = "SELECT * FROM USERS WHERE ID =" . $qAskerid . ";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qAsker = $sqlresult["USERNAME"];
        $picname = "profilePics/" . $qAskerid . '_' . $qAsker . '.';
        $picname = picext($picname);
    
    echo '<div class="col-sm-1" ><div class="col-sm-1 "><br>';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("Q", $_SESSION["QNumber"])==1)
                echo '<img id="voteupQ" src="upvoteActive.png" alt="active upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupQ" src="upvote.png" alt="upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
        }
    echo '<br><br><h4 id="qPoint" class="text-center">' . $qPoints . '</h4><br>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("Q", $_SESSION["QNumber"])==2)
                echo '<img id="votedownQ" src="downvoteActive.png" alt="active downvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownQ" src="downvote.png" alt="downvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
        }
    echo '</div></div>';
    echo '<div class="col-sm-11">';
    echo '<h1>' . $qTitle . '</h1>';
    echo '<h3>' . $qPhrase . '</h3>';
    //display question picture;
    $questpic = "questPics/" . $_SESSION["QNumber"] . ".";
    $questpic = picext($questpic);
    if ($questpic != "profilePics/stock.png")
        echo '<img class="anspicture" alt="Picture" src="' . $questpic . '">';
        
    echo '<div class="media"><div class="media-body">';
    echo '<h5 class="text-right"><a href="profile.php?name=' . $qAsker . '"> ' . $qAsker . '</a></h5>
    <h6 class="text-right">' . $qDate . '</h6>
    </div><div class="media-right"> <img class="media-object" alt="Profile picture" style="width:70px; height:40px;" src="' . $picname . '"></div></div></div>';
    if ($_SESSION["UserID"] == 1){ // if user is admin, show freeze options
        echo '<div class="btn-group pull-right">';
        //freeze question
        if ($frozen == 0){  
            echo '<button id="freezeQuest" form="freeze" type="submit" name="freezeQuest" value="1" class="btn btn-info">FREEZE</button>';
        }
        else{
            echo '<button id="freezeQuest" form="freeze" type="submit" name="freezeQuest" value="0" class="btn btn-info">UNFREEZE</button>';
        }
        //edit question
        echo '<button id="editQuest" form="edit" data-toggle="modal" data-target="#editQ" type="button" name="editQuest" class="btn btn-info">EDIT</button>';
        //delete question
        echo '<button id="deleteQuest" type="button" data-toggle="modal" data-target="#deleteQ" name="deleteQuest" class="btn btn-info">DELETE</button>';
        echo '</div>';
    }
    
    echo "<h3>Answers</h3>";
    //check if there is a selected answer
        if ($answerID != '0'){ 
            $queryanswer = "SELECT * FROM ANSWERS WHERE ID =". $answerID . ";";
            $getanswer = sqlcommand($queryanswer, "SELECT");
            $getanswer = $getanswer->fetch_assoc();
            $aPoints = $getanswer["POINTS"];
            $correctanswererid = $getanswer["USER_ID"];
            echo "<div class='well' style='background-color:#66ff33'>";
            echo '<div class="col-sm-1"><div class="col-sm-1">';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("A", $answerID)==1)
                echo '<img id="voteupA'.$answerID.'" src="upvoteActive.png" alt="active upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupA'.$answerID.'" src="upvote.png" alt="upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
        }
        echo '<br><h4 id="aPoint'.$answerID.'" class="text-center">' . $aPoints . '</h4>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("A", $answerID)==2)
                echo '<img id="votedownA'.$answerID.'" src="downvoteActive.png" alt="active downvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownA'.$answerID.'" src="downvote.png" alt="downvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
        }
            echo '</div></div>';
            $correctANS = str_replace("&lt;", "<", $getanswer["ANSWER"]);
            $correctANS = str_replace("&gt;", ">", $correctANS);
            echo "<p>" . $correctANS . "</p>";
            //display question picture;
            if (file_exists("answerPics/" . $_SESSION["QNumber"] . "_" . $answerid ))
                echo '<img alt="Picture" src="answerPics/' . $_SESSION["QNumber"] . "_" . $answerid . '">';
            $correctdate = $getanswer["DATE_ANSWERED"];
            $queryanswer = "SELECT USERNAME FROM USERS WHERE ID =" . $correctanswererid.";";
            $result3 = sqlcommand($queryanswer, "SELECT");
            $result3 = $result3->fetch_assoc();
            $correctanswerer = $result3["USERNAME"];
            $picname = "profilePics/" . $correctanswererid . '_' . $correctanswerer . '.';
            $picname = picext($picname);
            
            echo '<div class="media"><div class="media-body">';
            echo '<h5  class="text-right"><a href="profile.php?name=' . $correctanswerer . '"> ' . $correctanswerer . '</a></h5>';
            echo '<h6 class="text-right">' . $correctdate . '</h6>';
            echo '</div><div class="media-right"> <img class="media-object" alt="Profile Picture" style="width:70px; height:40px;" src="' . $picname . '">';
            echo '</div></div></div>';
        }
    
    //List answers
    //get total number of answers
    $query1 = "SELECT COUNT(*) AS ANSCOUNT FROM ANSWERS WHERE ID <> " . $answerID . " AND QUEST_ID =".$_SESSION["QNumber"].";";
    $countresult = sqlcommand($query1, "SELECT");
    if ($countresult != "false"){
        $countrow = $countresult->fetch_assoc();
        $anscount = $countrow['ANSCOUNT'];
        $numpages = $anscount / 5;
        $numpages = ceil($numpages);
    }
    
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        if ($page > $numpages)
            redirect("question.php");
    }
    else
        $page = 1;
        
    //Get answers
    $query = "SELECT * FROM ANSWERS WHERE ID <> " . $answerID . " AND QUEST_ID =".$_SESSION["QNumber"]." ORDER BY POINTS DESC, DATE_ANSWERED ASC LIMIT " . 5*($page-1) .", " . 5 . ";";
    $result = sqlcommand($query, "SELECT");
    if ($result == false)
        echo "No answers yet. Check back again soon!\n";
    else{//display answers
        while($row = $result->fetch_assoc()) {
            $answerlistid = $row["ID"];
            $aPoints = $row["POINTS"];
            $answererid = $row["USER_ID"];
            echo "<div class='well' >";
            echo '<div class="col-sm-1" ><div class="col-sm-1 ">';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("A", $answerlistid)==1)
                echo '<img id="voteupA'.$answerlistid.'" src="upvoteActive.png" alt="active upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupA'.$answerlistid.'" src="upvote.png" alt="upvote" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
        }
        echo '<br><h4 id="aPoint'.$answerlistid.'" class="text-center">' . $aPoints . '</h4>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("A", $answerlistid)==2)
                echo '<img id="votedownA'.$answerlistid.'" alt="Active downvote" src="downvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownA'.$answerlistid.'" alt="downvote" src="downvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
        }
            echo '</div></div>';
            $theanswer = str_replace("&lt;", "<", $row["ANSWER"]);
            $theanswer = str_replace("&gt;", ">", $theanswer);
            echo "<p>" . $theanswer . "</p>"; //style='background-color:#66ff33' for right answer
            //display answer picture;
            $anspic = "answerPics/" . $_SESSION["QNumber"] . "_" . $answerlistid . ".";
            $anspic = picext($anspic);
            if ($anspic != "profilePics/stock.png")
                echo '<img class="anspicture" alt="Picture" src="' . $anspic . '">';
            
            $query = "SELECT USERNAME FROM USERS WHERE ID=" . $answererid.";";
            $result2 = sqlcommand($query, "SELECT");
            $result2 = $result2->fetch_assoc();
            $answerer = $result2["USERNAME"];
            $picname = "profilePics/" . $answererid . '_' . $answerer . '.';
            $picname = picext($picname);
            echo '<div class="media"><div class="media-body">';
            //if question hasn't been answered or if userID isn't the person who asked the question, make the button invisible
            if ($answerID == '0' and $_SESSION["userName"] == $qAsker and $frozen == 0){
                echo '<button id="rightAnswer" type="submit" name="AnswerSubmit" value="'.$answerlistid.'" form="correct" class="btn btn-info" style="float:left;" >THIS IS THE ANSWER!</button>';
            }
            echo '<h5  class="text-right"><a href="profile.php?name=' .  $answerer . '"> ' . $answerer . '</a></h5>';
            echo '<h6 class="text-right">' . $row["DATE_ANSWERED"] . '</h6>';
            echo '</div><div class="media-right"> <img class="media-object" alt="Profile Picture" style="width:70px; height:40px;" src="' . $picname . '">';
            echo '</div></div></div>';
        }
     //insert pagination
        if (isset($numpages)){
            echo '<div class="text-center">
            <ul id="pagin" class="center pagination">';
            if ($page != 1)
            echo '<li id="firstpagin"><a href="question.php?page=1">First</a></li>';
            if ($page > 3){
                echo '<li class="disabled"><a href="">...</a></li>';
            }
            for($i = max(1, $page - 2); $i <= min($page + 2, $numpages); $i++){
                if ($i != $page)
                    echo '<li><a href="question.php?page=' . $i .'">'. $i .'</a></li>';
                else
                    echo '<li class="page-item active"><a href="">'. $i .'</a></li>';
            }
            if ($i-1 < $numpages)
                echo '<li class="disabled"><a href="">...</a></li>';
            if ($page != $numpages)
            echo '<li><a href="question.php?page='.$numpages.'">Last</a></li>';
            echo '</ul></div>';
        }
    }   
    ?>
    <!-- Edit Question Modal -->
    <div id="editQ" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Question</h4>
                </div>
            <div class="modal-body">
            <form id="edit" action="editQuest.php" method="POST">
                <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="newTitle" pattern=".{5,60}" required title="Your title needs to be between 5-60 characters" class="form-control" <?php echo 'value= "' . $qTitle . '"';?>>
        </div>
        <div class="form-group">
            <label for="question">Question:</label>
            <textarea type="text" name="newQuestion" pattern=".{5,500}" required title="Your question needs to be between 5-500 characters" class="form-control" rows="5" id="question"><?php echo $qPhrase;?></textarea>
        </div>
        <div class="form-group">
            <label for="tag1">Tag 1:</label>
            <input type="text" name="newTag1" pattern=".{3,20}" required title="You must have at least 1 tag between 3-20 characters" class="form-control" <?php echo 'value= "' . $qTag1 . '"';?>>
        </div>
        <div class="form-group">
            <label for="tag2">Tag 2:</label>
            <input type="text" name="newTag2" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control" <?php echo 'value= "' . $qTag2 . '"';?>>
        </div>
        <div class="form-group">
            <label for="tag3">Tag 3:</label>
            <input type="text" name="newTag3" pattern=".{0,20}" title="Tags can't be more than 20 characters" class="form-control" <?php echo 'value= "' . $qTag3 . '"';?>>
        </div>
        </form>
        </div>
            <div class="modal-footer">
                <button class="btn btn-info" form="edit" type="submit" name="submit">Accept Changes!</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Delete Question Modal -->
    <div id="deleteQ" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to delete this question?</h4>
                </div>
            <div class="modal-footer">
                <button class="btn btn-info" form="delete" type="submit" name="submit">Yes, delete!</button>
            </div>
            </div>
        </div>
    </div>
    <form id="freeze" action="freeze.php" method="POST"></form>
    <form id="delete" action="deleteQuest.php" method="POST"></form>
    <form id="correct" action="correctans.php" method="POST"></form>
        
    <!-- New Answer form -->
    <form id="newAnswer" action="insertanswer.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <div class="text-center alert alert-warning" style="display:none;" id="lengthalert"> </div>
            <label for="Answer">Your Answer:</label>
            <textarea name="Answer" maxlength="500" required title="Your answer needs to be between 2-500 characters" rows="5" id="Answer" class="form-control"></textarea>
        </div>
        <strong>Select image to upload:</strong> <input type="file" name="fileToUpload" id="fileToUpload"> 
    <button type="button" class="btn btn-primary center-block" onclick="CheckLength();">Submit Answer!</button><br><br><br>
	</form>
    </div>
    
    <?php 
	if ($_SESSION["loggedIn"] != true)
	{
        echo '<script type="text/javascript"> document.getElementById("newAnswer").style.display="none"; </script>';
		echo "<h3 class='text-center'>Unfortunately, you have to be logged in to answer questions. I know, bummer! Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
    else if ($frozen == 1){
        echo '<script type="text/javascript"> document.getElementById("newAnswer").style.display="none"; document.getElementById("rightAnswer").style.display="none";</script>';
		echo "<h3 class='text-center'>This question has been frozen by the administrator</h3>"; 
    }        
    ?>
    
</body>
</html>