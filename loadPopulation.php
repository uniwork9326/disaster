<?php
$conn = new mysqli("localhost", "root", "", "disaster");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$file = fopen(__DIR__ . "/county_population.csv", "r");

// Skip the first line (header)
fgetcsv($file);

while (($row = fgetcsv($file)) !== FALSE) {
    $countyName = $conn->real_escape_string($row[0]);
    $population = (int) $row[1];

    $sql = "UPDATE counties SET population = $population WHERE name = '$countyName'";
    if (!$conn->query($sql)) {
        echo "Error updating $countyName: " . $conn->error . "<br>";
    }
}

fclose($file);
echo "Population data loaded!";
$conn->close();
?>
