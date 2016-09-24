<?php
$servername = "localhost";
//$username = "admin";
//$password = "M0n@rch$";
$username = "root";
$password = "root";
$dbname = "HighSide";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM USERS;";
$result = $conn->query($sql);

echo "<html><body>\n";

if ($result->num_rows > 0) {
    echo "<table padding=2 border=1>\n";
    echo "<tr><th>ID<th>UserName<th>PassWord<th>KarmaPoints<th>Last_Active\n";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID"] . "<td>" . $row["USERNAME"] . "<td>" . $row["PASSWORD"] . "<td>" . $row["KARMA_POINTS"] . "<td>" . $row["LAST_ACTIVE"] . "\n";
    }
} else {
    echo "0 results";
}
echo "</table>\n";
$conn->close();
echo "</body></html>";
?>
