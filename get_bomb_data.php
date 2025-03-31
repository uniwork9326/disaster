<?php
header('Content-Type: application/json');

if (!isset($_GET['bomb'])) {
    echo json_encode(['status' => 'error', 'message' => 'No bomb specified']);
    exit;
}

$bombName = $_GET['bomb'];

$conn = new mysqli('localhost', 'root', '', 'disaster');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$escapedBomb = $conn->real_escape_string($bombName);
$sql = "SELECT name, radius_m, radius_area_m2, death_percentage FROM bombs WHERE name = '$escapedBomb'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Bomb not found']);
}

$conn->close();
?>
