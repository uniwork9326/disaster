<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Disaster Simulator</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">

  <!-- Turf.js for area calculations -->
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
</head>
<body>

  <!-- Sidebar -->
  <div id="sidebar">
    <div id="sidebar-container">

      <!-- Logo -->
      <a href="#" id="reset-logo">
        <img src="logo.png" alt="Disaster Simulator Logo" id="logo">
      </a>

      <div class="weapon-selector">
        <label for="bomb-type"><strong>SELECT WEAPON:</strong></label>
        <select id="bomb-type">
          <option value="Tsar Bomba">Tsar Bomba (50 Mt)</option>
          <option value="Castle Bravo">Castle Bravo (15 Mt)</option>
          <option value="Ivy King">Ivy King (500 kt)</option>
          <option value="Fat Man">Fat Man (20 kt)</option>
          <option value="Little Boy">Little Boy (15 kt)</option>
        </select>
      </div>

      <!-- Magnitude Multiplier Slider -->
      <div class="slider-section">
        <label for="magnitude-slider"><strong>MAGNITUDE MULTIPLIER</strong></label>
        <input type="range" id="magnitude-slider" min="1" max="100" value="1" step="1">
        <input type="text" id="magnitude-input" value="1" maxlength="3" style="width: 50px; text-align: center;">
        <div id="slider-value">1X</div>
      </div>

      <!-- Marker Info Section -->
      <div id="marker-info">
        <h4>MARKER POSITION</h4>
        <div id="marker-table-container">
          <table id="marker-table">
            <tbody id="location-display">
              <!-- JS will populate this -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Detonate Button -->
      <button id="detonate">DETONATE</button>

      <!-- Results Section -->
      <div id="results" style="display: none;">
        <h4>IMPACTED COUNTIES:</h4>
        <div id="results-container">
          <div id="results-list"></div>
        </div>
        <div id="total-deaths"></div>
      </div>

      <!-- Reset Button -->
      <button id="reset" style="display: none;">RESET</button>

      <!-- New Button linking to info.php -->
      <a href="info.php" id="info-button">INFO</a>

    </div>
  </div>

  <!-- Map -->
  <div id="map"></div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <!-- Custom Scripts -->
  <script src="script.js"></script>

  <script>
    // Get elements
    const slider = document.getElementById('magnitude-slider');
    const input = document.getElementById('magnitude-input');
    const sliderValue = document.getElementById('slider-value');

    slider.addEventListener('input', function() {
    input.value = slider.value;
    sliderValue.textContent = slider.value + 'X';
    magnitudeMultiplier = parseFloat(slider.value);
    drawCircle();
});

input.addEventListener('change', function() {
    let value = parseInt(input.value, 10);

    if (!isNaN(value) && value >= 1 && value <= 100) {
        slider.value = value;
        sliderValue.textContent = value + 'X';
        magnitudeMultiplier = parseFloat(value);
        drawCircle();
    } else {
        input.value = slider.value;
    }
});

</script>


</body>
</html>
