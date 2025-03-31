<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'disaster');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO disaster_results (bomb_name, county_name, estimated_deaths, latitude, longitude, date) VALUES (?, ?, ?, ?, ?, NOW())");

foreach ($data as $entry) {
    $stmt->bind_param(
        'ssidd',
        $entry['bomb'],
        $entry['county'],
        $entry['deaths'],
        $entry['lat'],
        $entry['lng']
    );
    $stmt->execute();
}

echo json_encode(['status' => 'success', 'message' => 'Results stored']);
$conn->close();
?>
