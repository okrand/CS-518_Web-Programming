<?php
require_once "thingsandstuff.php";
session_start();
if($_SESSION["UserID"] != 1)
    redirect("login.php");
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.
function list_commands(){
    $commands = "get_user(param:id)<br>"; //get_user_list()<br>";
    return $commands;
}

function get_user_info($id)
{
  $user_info = array();
  // build JSON array.
    $query = "SELECT * FROM USERS WHERE ID = " . $id;
    $sqluser = sqlcommand($query, "SELECT");
    $sqluser = $sqluser->fetch_assoc() ;
  $user_info = array("username" => $sqluser["USERNAME"], "password" => $sqluser["PASSWORD"], "points" => $sqluser["KARMA_POINTS"], "last_active" => $sqluser["LAST_ACTIVE"], "email" => $sqluser["EMAIL"]);

  return $user_info;
}

/*function get_user_list()
{
  //normally this info would be pulled from a database.
  //build JSON array
  $user_list = array(); 
  $query = "SELECT * FROM USERS WHERE 1";
  $sqluser = sqlcommand($query, "SELECT");
  while($row = $sqluser->fetch_assoc()){
      $user = array("username" => $row["USERNAME"], "password" => $row["PASSWORD"], "points" => $row["KARMA_POINTS"], "last_active" => $row["LAST_ACTIVE"], "email" => $row["EMAIL"]);
      array_push($user_list, $user);
  }
  return $user_list;
}*/

$possible_url = array("get_user", "list_commands");

$value = "An error has occurred";

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {
      case "list_commands":
        $value = list_commands();
        break;
      /*case "get_user_list":
        $value = get_user_list();
        break;*/
      case "get_user":
        if (isset($_GET["id"]))
          $value = get_user_info($_GET["id"]);
        else
          $value = "Missing argument";
        break;
    }
}
//return JSON array
exit(json_encode($value));
?>
