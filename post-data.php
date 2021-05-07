<?php
$servername = "localhost";

// REPLACE with your Database name
$dbname = "remote_sensing_data";
// REPLACE with Database user
$username = "database_username";
// REPLACE with Database user password
$password = "password";

$api_key_value = "894D9329C71C59EDF417695DF56CF";
$api_key = $pm = $aqi = $co = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $pm = test_input($_POST["pm"]);
        $aqi = test_input($_POST["aqi"]);
        $co = test_input($_POST["co"]);
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO Sensor (pm, aqi, co)
        VALUES ('" . $pm . "', '" . $aqi . "', '" . $co . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
