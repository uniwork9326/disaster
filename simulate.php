<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

// Get inputs from the frontend
$lat = $input['lat'];
$lng = $input['lng'];
$weaponType = $input['weaponType'];

// Example data (replace with real calculations)
$results = [
  [
    'name' => 'County A',
    'populationImpact' => 10000,
    'economicImpact' => 5000000,
  ],
  [
    'name' => 'County B',
    'populationImpact' => 20000,
    'economicImpact' => 15000000,
  ],
];

// Return JSON results
echo json_encode($results);
?>
