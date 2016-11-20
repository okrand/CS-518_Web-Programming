<?php 
include_once "thingsandstuff.php";
session_start(); 
?>

<?php
//$xmlDoc=new DOMDocument();
//$xmlDoc->load("links.xml");

$x = array();
$query = "SELECT USERNAME FROM USERS";
$sqlresults = sqlcommand($query, "SELECT");
while($row = $sqlresults->fetch_assoc()){
    $x[] = $row["USERNAME"];
}
//$x=$xmlDoc->getElementsByTagName('link');

//get the q parameter from URL
$q=$_GET["q"];

//lookup all usernames from the array if length of q>0
if (strlen($q)>0) {
  $hint="";
  for($i=0; $i<(count($x)); $i++) {
      //find a link matching the search text
      if (substr($x[$i], 0, strlen($q)) === $q){
      //if (stristr($x[$i],$q)) {
        if ($hint=="") {
          $hint="<a href='profile.php?name=" . $x[$i] . 
          "' target='_blank'>" . 
          $x[$i] . "</a>";
        } else {
          $hint=$hint . "<br /><a href='profile.php?name=" . $x[$i] . 
          "' target='_blank'>" . 
          $x[$i] . "</a>";
        }
      }
  }
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

//output the response
echo $response;
?>