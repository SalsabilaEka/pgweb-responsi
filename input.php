<?php
$posyandu = $_POST['posyandu'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latihan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO penduduk (posyandu, longitude, latitude)
VALUES ('$posyandu', $longitude, $latitude)";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>