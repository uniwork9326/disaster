<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disaster";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Read and decode geojson file
$jsonData = file_get_contents(__DIR__ . '/counties.geojson');

$data = json_decode($jsonData, true);

foreach ($data['features'] as $county) {
    $name = $conn->real_escape_string($county['properties']['NAME']);
    $statefp = $conn->real_escape_string($county['properties']['STATEFP']);
    $geoID = $conn->real_escape_string($county['properties']['GEOID']);

    // Insert into database
    $sql = "INSERT INTO counties (name, statefp, geoid) VALUES ('$name', '$statefp', '$geoID')";
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error . "<br>";
    }
}
