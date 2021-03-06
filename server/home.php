<?php 
include "header.php";

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM videos";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$x = 0;
while ($row = $result->fetch_assoc()) {

    $x++;
}

$sql = "SELECT * FROM stat ORDER BY views DESC LIMIT 20";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$a = 0;
while ($row = $result->fetch_assoc()) {
    $a++;
}

$sql = "SELECT * FROM stat ORDER BY views DESC LIMIT 20";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$top = array();
$views = array();
while ($row = $result->fetch_assoc()) {
    array_push($top, $row["id"]);
    array_push($views, $row["views"]);
}

$sql = "SELECT * FROM stat";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$viewer = 0;
while ($row = $result->fetch_assoc()) {
    $viewer = $viewer + (int)($row["views"]);
}

echo "<!--";
print_r($top);
echo "-->";

echo "<h1>Flag - Bite Sized Videos</h1>";
echo "<h3>${viewer} views and counting</h3>";
echo "<script src='/frontend/top.js'></script>";


echo '<br><div class="container-fluid"><div class="row">';
$count = 0;
foreach($top as $vias)
{
    $count++;
    if($count == 4 || $count == 8 || $count == 12)
    {
        echo '<div class="container-fluid"><div class="row">';
    }
    $cur_views = $count - 1;
    $viw = $views[$cur_views];
    $sql = "SELECT * FROM videos WHERE v_id=?";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $vias);
    $stmt->execute();
    $result = $stmt->get_result();
    $top = array();
    while ($row = $result->fetch_assoc()) {
        $thumb = $row["v_thumb"];
        $title = $row["v_title"];
    
    echo '<div class="col-sm">';
    echo "<a href='/watch/" . $row["v_id"] . "'><img src='" . $row["v_thumb"] . "' height='144px' width='360'/></a>";
    echo "<br><p>" . $row["v_title"] . " - ${viw} views</p>";
    echo "</div>";
    if($count == 4 || $count == 8 || $count == 12)
    {
        echo "</div></div>";
    }
    
}
}
echo "</div></div>";
