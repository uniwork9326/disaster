<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <title>Disaster Simulator</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Sidebar -->
  <div id="sidebar">
    <!-- Sidebar Content Container -->
    <div id="sidebar-container">
      <!-- Logo with hyperlink -->
      <a href="#" id="reset-logo">
        <img src="logo.jpg" alt="Disaster Simulator Logo" id="logo">
      </a>

      <!-- Select Weapon Dropdown -->
      <label for="bomb-type">SELECT WEAPON:</label>
      <select id="bomb-type">
        <option value="nuclear">Nuclear Bomb</option>
        <option value="conventional">Conventional Bomb</option>
        <option value="meteor">Meteor Strike</option>
      </select>

      <!-- Pointer Location Display -->
      <div id="location-display">Latitude: -, Longitude: -</div>

      <!-- Detonate Button -->
      <button id="detonate">DETONATE</button>

      <!-- Results Section -->
      <div id="results">
        <h4>IMPACT RESULTS:</h4>
        <div id="results-list"></div>
      </div>
    </div>
  </div>

  <!-- Map Container -->
  <div id="map"></div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- Custom JavaScript -->
  <script src="script.js"></script>
</body>
</html>
