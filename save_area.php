<?php
header('Content-Type: application/json');

// Try to decode JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Handle missing or invalid input
if (!is_array($data)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No JSON received or malformed input.'
    ]);
    exit;
}

$county = $data['county'] ?? null;
$area = $data['area'] ?? null;

if (!$county || !$area) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing county or area data.',
        'received' => $data
    ]);
    exit;
}

// (Optional) connect to DB and update
// $conn = new mysqli('localhost', 'root', '', 'disaster');
// $sql = "UPDATE counties SET area = $area WHERE name = '$county'";
// $conn->query($sql);
// $conn->close();

echo json_encode([
    'status' => 'success',
    'message' => "Area updated for $county",
    'data_received' => $data
]);
