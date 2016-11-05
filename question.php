<?php 
include_once "thingsandstuff.php";
session_start(); 
?>
<?php //get user's vote for this question
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
                    </div>
	</div>

    <?php
    if(isset($_GET['QN'])) //if there is a get question, make that value the session for QNumber
        $_SESSION["QNumber"] = $_GET['QN'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //submitting new answer
            $answer = test_input($_POST["Answer"]);
            $query = "INSERT INTO ANSWERS (QUEST_ID, USER_ID, ANSWER,DATE_ANSWERED) VALUES (".$_SESSION["QNumber"].", ".$_SESSION["UserID"].", '".$answer."', NOW());";
            $sqlresult = sqlcommand($query, "INSERT");
            if ($sqlresult == false)
                echo "Something very wrong happened, we don't quite know what it is but we're on it!\n";
            else{
                redirect("question.php");
            }
        }
    ?>
    <div class="container">
    <?php
    $query = "SELECT * FROM QUESTIONS WHERE ID =".$_SESSION["QNumber"].";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qTitle = $sqlresult["QUESTION_TITLE"];
    $qPhrase = $sqlresult["QUESTION_PHRASE"];
    $qPoints = $sqlresult["POINTS"];
    $qDate = $sqlresult["DATE_ASKED"];
    $qAskerid = $sqlresult["ASKER_ID"];
    $answerID = $sqlresult["ANSWER_ID"];
    $query = "SELECT * FROM USERS WHERE ID =" . $qAskerid . ";";
    $sqlresult = sqlcommand($query, "SELECT");
    $sqlresult = $sqlresult->fetch_assoc();
    $qAsker = $sqlresult["USERNAME"];
        $picname = $qAskerid . '_' . $qAsker . '.';
        $picname = picext($picname);
    
    echo '<div class="col-sm-1" ><div class="col-sm-1 "><br>';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("Q", $_SESSION["QNumber"])==1)
                echo '<img id="voteupQ" src="upvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupQ" src="upvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
        }
    echo '<br><br><h4 id="qPoint" align="center" class="text-center">' . $qPoints . '</h4><br>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("Q", $_SESSION["QNumber"])==2)
                echo '<img id="votedownQ" src="downvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownQ" src="downvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'Q\', '. $_SESSION["QNumber"] . ', '. $qAskerid . ', '. $_SESSION["UserID"]. ')">';
        }
    echo '</div></div>';
    echo '<div class="col-sm-11">';
    echo '<h1>' . $qTitle . '</h1>';
    echo '<h3>' . $qPhrase . '</h3>';
    echo '<div class="media"><div class="media-body">
    <h5 align="right"><a href="profile.php?name=' . $qAsker . '"> ' . $qAsker . '</a></h5>
    <h6 align="right">' . $qDate . '</h6>
    </div><div class="media-right"> <img class="media-object" style="width:70px; height:40px;" src="profilePics/' . $picname . '"></div></div></div>';
    
    echo "<h3 align='left'>Answers</h3>";
    //check if there is a selected answer
        if ($answerID != '0'){ 
            $queryanswer = "SELECT * FROM ANSWERS WHERE ID = ". $answerID . ";";
            $getanswer = sqlcommand($queryanswer, "SELECT");
            $getanswer = $getanswer->fetch_assoc();
            $aPoints = $getanswer["POINTS"];
            $correctanswererid = $getanswer["USER_ID"];
            echo "<div class='well' style='background-color:#66ff33'>";
            echo '<div class="col-sm-1" ><div class="col-sm-1 ">';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("A", $answerID)==1)
                echo '<img id="voteupA'.$answerID.'" src="upvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupA'.$answerID.'" src="upvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
        }
        echo '<br><h4 id="aPoint'.$answerID.'" align="center" class="text-center">' . $aPoints . '</h4>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("A", $answerID)==2)
                echo '<img id="votedownA'.$answerID.'" src="downvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownA'.$answerID.'" src="downvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerID . ', '. $correctanswererid . ', '. $_SESSION["UserID"]. ')">';
        }
            echo '</div></div>';
            echo "<p>" . $getanswer["ANSWER"] . "</p>";
            $correctdate = $getanswer["DATE_ANSWERED"];
            $queryanswer = "SELECT USERNAME FROM USERS WHERE ID=" . $correctanswererid.";";
            $result3 = sqlcommand($queryanswer, "SELECT");
            $result3 = $result3->fetch_assoc();
            $correctanswerer = $result3["USERNAME"];
            $picname = $correctanswererid . '_' . $correctanswerer . '.';
            $picname = picext($picname);
            
            echo '<div class="media"><div class="media-body">';
            echo '<h5  align="right"><a href="profile.php?name=' . $correctanswerer . '"> ' . $correctanswerer . '</a></h5>';
            echo '<h6 align="right">' . $correctdate . '</h6>';
            echo '</div><div class="media-right"> <img class="media-object" style="width:70px; height:40px;" src="profilePics/' . $picname . '">';
            echo '</div></div></div>';
        }
    
    //List answers
    $query = "SELECT * FROM ANSWERS WHERE ID <> " . $answerID . " AND QUEST_ID =".$_SESSION["QNumber"]." ORDER BY POINTS DESC;";
    $result = sqlcommand($query, "SELECT");
    if ($result == false){
        echo "No answers yet. Check back again soon!\n";
    }
    else{
        while($row = $result->fetch_assoc()) {
            $answerlistid = $row["ID"];
            $aPoints = $row["POINTS"];
            $answererid = $row["USER_ID"];
            echo "<div class='well' >";
            echo '<div class="col-sm-1" ><div class="col-sm-1 ">';
        if ($_SESSION["loggedIn"] == true){
            if (getvotes("A", $answerlistid)==1)
                echo '<img id="voteupA'.$answerlistid.'" src="upvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="voteupA'.$answerlistid.'" src="upvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(1, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
        }
        echo '<br><h4 id="aPoint'.$answerlistid.'" align="center" class="text-center">' . $aPoints . '</h4>';
        if ($_SESSION["loggedIn"] == true){
           if (getvotes("A", $answerlistid)==2)
                echo '<img id="votedownA'.$answerlistid.'" src="downvoteActive.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
            else
                echo '<img id="votedownA'.$answerlistid.'" src="downvote.png" style="width:25px; height:25px; cursor:pointer;" onclick="vote(2, \'A\', '. $answerlistid . ', '. $answererid . ', '. $_SESSION["UserID"]. ')">';
        }
            echo '</div></div>';
            echo "<p>" . $row["ANSWER"] . "</p>"; //style='background-color:#66ff33' for right answer
            $query = "SELECT USERNAME FROM USERS WHERE ID=" . $answererid.";";
            $result2 = sqlcommand($query, "SELECT");
            $result2 = $result2->fetch_assoc();
            $answerer = $result2["USERNAME"];
            $picname = $answererid . '_' . $answerer . '.';
            $picname = picext($picname);
            echo '<div class="media"><div class="media-body">';
            //if question hasn't been answered or if userID isn't the person who asked the question, make the button invisible
            if ($answerID == '0' and $_SESSION["userName"] == $qAsker){
                echo '<button type="submit" name="AnswerSubmit" value="'.$answerlistid.'" form="correct" class="btn btn-info" style="float:left;" >THIS IS THE ANSWER!</button>';
            }
            echo '<h5  align="right"><a href="profile.php?name=' . $answerer . '"> ' . $answerer . '</a></h5>';
            echo '<h6 align="right">' . $row["DATE_ANSWERED"] . '</h6>';
            echo '</div><div class="media-right"> <img class="media-object" style="width:70px; height:40px;" src="profilePics/' . $picname . '">';
            echo '</div></div></div>';
        }
    }
    ?>
    <span>
        <form id="correct" action="correctans.php" method="POST">
        </form>
        </span>
   <span id = "newAns">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="form-group">
            <label for="Answer">Your Answer:</label>
            <textarea type="text" name="Answer" pattern=".{5,500}" required title="Your answer needs to be between 5-500 characters" rows="5" id="Answer" class="form-control"></textarea>
        </div>
    <button type="submit" class="btn btn-primary center-block">Submit Answer!</button><br><br><br>
	</form>
    </span>
    </div>
    <?php 
	if ($_SESSION["loggedIn"] != true)
	{
        echo '<script  type="text/javascript"> document.getElementById("newAns").style.display="none"; </script>';
		echo "<h3 align='center'>Unfortunately, you have to be logged in to answer questions. I know, bummer! Please "; 
		echo '<a href="./login.php">login here!</a></h3>';
	}
    ?>
	
    
</body>
</html>