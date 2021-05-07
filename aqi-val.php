<?php
$servername = "localhost";
$dbname = "remote_sensing_data";

// Replace with username and password
$username = "database_username";
$password = "password";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, aqi, reading_time FROM Sensor order by reading_time desc limit 1";

$result = $conn->query($sql);
$sensor_data = $result->fetch_assoc();
echo $sensor_data['aqi'];
echo ",";
echo $sensor_data['reading_time'];

$result->free();
$conn->close();
?>

