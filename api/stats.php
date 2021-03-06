<?php
header("Content-type: application/json");

/*===================================

This endpoint only accepts GET requests!

Examples:

Get a video's stats

GET /api/v1/stats/?id=12345


===================================*/

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$video = $_GET["id"];

if(! $video)
{
    header("HTTP/1.1 400 Bad Request");
    die(json_encode(array("success" => "false", "message" => "400 Bad Request")));
}

$sql = "SELECT * FROM stat WHERE `id`=?";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $video);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $details = array(
        "views" => $row["views"]
    );
    $final = array("details" => $details);
}

die(json_encode($final, true));