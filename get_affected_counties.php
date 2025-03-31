<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'disaster');

// Check for connection error
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

// Decode incoming JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['geoids']) || !is_array($data['geoids'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid GEOIDs']);
    exit;
}

// Prepare placeholders
$placeholders = implode(',', array_fill(0, count($data['geoids']), '?'));
$stmt = $conn->prepare("SELECT geoid, population, area FROM counties WHERE geoid IN ($placeholders)");

// Bind parameters
$stmt->bind_param(str_repeat('s', count($data['geoids'])), ...$data['geoids']);
$stmt->execute();

$result = $stmt->get_result();
$counties = [];

while ($row = $result->fetch_assoc()) {
    $counties[] = [
        'geoid' => $row['geoid'],
        'population' => $row['population'],
        'area' => $row['area']
    ];
}

echo json_encode(['status' => 'success', 'counties' => $counties]);
