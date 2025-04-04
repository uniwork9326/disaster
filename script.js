const defaultPosition = [37.8, -96];
const initialZoom = 5;
let bombCircle = null;
let bombData = {}; // Stores current bomb data
let countiesFromDB = {};
let magnitudeMultiplier = 1.0; // Default multiplier

const map = L.map('map').setView(defaultPosition, initialZoom);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '&copy; OpenStreetMap contributors',
}).addTo(map);

fetch('counties.geojson')
  .then(res => res.json())
  .then(data => {
    L.geoJSON(data, {
      style: {
        color: '#444',
        weight: 0.5,
        fillColor: 'transparent',
        fillOpacity: 0.1,
      },
    }).addTo(map);
  });

const marker = L.marker(defaultPosition, { draggable: true }).addTo(map);

function getCountyName(lat, lon) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;
  fetch(url)
    .then(res => res.json())
    .then(data => {
      if (data.address) {
        document.getElementById('location-display').innerHTML = `
          <strong>County:</strong> ${data.address.county || "Unknown"}<br>
          <strong>State:</strong> ${data.address.state || "Unknown"}<br>
          <strong>Address:</strong> ${data.display_name || "Not found"}<br>
          <strong>Coordinates:</strong> ${lat.toFixed(4)}, ${lon.toFixed(4)}
        `;
      }
    })
    .catch(() => {
      document.getElementById('location-display').innerText = "Location fetch error.";
    });
}

marker.on('dragend', () => {
  const { lat, lng } = marker.getLatLng();
  getCountyName(lat, lng);
  drawCircle(); // Update circle on move
});

// Fetch selected bomb data from backend
// ✅ Fetch selected bomb data from backend
function fetchBombDataAndDraw() {
  const selected = document.getElementById('bomb-type').value;

  // Reset the magnitude slider to 0 when a new bomb is selected
  document.getElementById('magnitude-slider').value = 1;  // Reset slider to 1% (minimum)
  document.getElementById('slider-value').innerText = '1X'; // Update the displayed value

  fetch(`get_bomb_data.php?bomb=${encodeURIComponent(selected)}`)
    .then(res => res.json())
    .then(response => {
      if (response.status === "success" && response.data) {
        bombData = {
          radius_m: parseFloat(response.data.radius_m),
          death_percentage: parseFloat(response.data.death_percentage),
        };
        drawCircle();  // Redraw the circle with the new bomb data and adjusted radius
      } else {
        console.error("Bomb data fetch failed:", response.message);
      }
    })
    .catch(err => {
      console.error("Error fetching bomb data:", err);
    });
}


// Draw the circle based on fetched bomb radius and magnitude
// Draw the circle based on fetched bomb radius and magnitude
// Draw the circle based on fetched bomb radius and magnitude
// Draw the circle based on fetched bomb radius and magnitude
function drawCircle() {
  if (!bombData.radius_m || isNaN(bombData.radius_m)) return; // Check if radius is valid

  const position = marker.getLatLng();

  // Remove the previous circle layers if they exist
  if (bombCircle) {
    map.removeLayer(bombCircle);
  }

  // Remove previously created circles if they exist
  map.eachLayer(function (layer) {
    if (layer instanceof L.Circle) {
      map.removeLayer(layer); // Remove any circle layers
    }
  });

  // Calculate the adjusted radius based on the magnitude multiplier
  const adjustedRadius = bombData.radius_m * magnitudeMultiplier;

  // Ensure the radius is a valid positive number
  if (isNaN(adjustedRadius) || adjustedRadius <= 0) return;

  // Create 3 circles with fixed scaling (to adjust the size)
  const outerRadius = adjustedRadius * 1.3; // Outer circle will be 1.3 times the adjusted radius
  const middleRadius = adjustedRadius; // Middle circle (default size)
  const innerRadius = adjustedRadius * 0.7; // Inner circle will be 0.7 times the adjusted radius

  // Outer Circle (Blue)
  const outerCircle = L.circle(position, {
    radius: outerRadius,
    color: 'blue',
    fillColor: 'blue',
    fillOpacity: 0.4
  }).addTo(map);

  // Middle Circle (Green)
  const middleCircle = L.circle(position, {
    radius: middleRadius,
    color: 'green',
    fillColor: 'green',
    fillOpacity: 0.4
  }).addTo(map);

  // Inner Circle (Red)
  const innerCircle = L.circle(position, {
    radius: innerRadius,
    color: 'red',
    fillColor: 'red',
    fillOpacity: 0.4
  }).addTo(map);

  // Convert to Turf circle (for backend impact calculation later)
  const turfCircle = turf.circle([position.lng, position.lat], adjustedRadius / 1000, {
    steps: 64,
    units: 'kilometers',
  });
  console.log("Turf circle created:", turfCircle);
}






// Event listener for bomb type change
document.getElementById('bomb-type').addEventListener('change', fetchBombDataAndDraw);

// Reset map and marker to default
document.getElementById('reset-logo').addEventListener('click', (e) => {
  e.preventDefault();
  marker.setLatLng(defaultPosition);
  map.setView(defaultPosition, initialZoom);
  getCountyName(defaultPosition[0], defaultPosition[1]);
  fetchBombDataAndDraw();
});

// Magnitude Slider Event Listener
const magnitudeSlider = document.getElementById('magnitude-slider');
const sliderValueDisplay = document.getElementById('slider-value');

magnitudeSlider.addEventListener('input', (e) => {
  // Update the magnitude multiplier
  magnitudeMultiplier = e.target.value;
  // Update the displayed value
  sliderValueDisplay.innerHTML = `${e.target.value}X`;
  // Redraw the circle with the updated multiplier
  drawCircle();
});

// Detonate button
document.getElementById('detonate').addEventListener('click', () => {
  const position = marker.getLatLng();
  const bombName = document.getElementById('bomb-type').value;

  // Show results section and reset button
  document.getElementById('results').style.display = 'block';  // Show results section
  document.getElementById('reset').style.display = 'inline-block';  // Show reset button

  // Create Turf circle and perform calculation
  const turfCircle = turf.circle([position.lng, position.lat], bombData.radius_m * magnitudeMultiplier / 1000, {
    steps: 64,
    units: 'kilometers'
  });

  // Load GeoJSON counties
  fetch('counties.geojson')
    .then(res => res.json())
    .then(data => {
      const results = [];
      const affectedGeoids = [];

      // Step 2.1: Identify affected counties and collect GEOIDs
      const affectedFeatures = data.features.filter(feature => {
        return turf.booleanIntersects(turfCircle, feature);
      });

      affectedFeatures.forEach(feature => {
        const geoid = feature.properties.GEOID;
        if (geoid) affectedGeoids.push(geoid);
      });

      // Step 2.2: Fetch population & area from DB using the collected GEOIDs
      return fetch('get_affected_counties.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ geoids: affectedGeoids })
      })
      .then(res => res.json())
      .then(dbResponse => {
        if (dbResponse.status !== "success") throw new Error("Failed to fetch affected county data");

        // Build countiesFromDB object
        dbResponse.counties.forEach(entry => {
          console.log("County data from DB:", entry); // Log each county's data
          countiesFromDB[entry.geoid] = {
            population: parseFloat(entry.population),
            area: parseFloat(entry.area)
          };
        });

        // Step 2.3: Calculate deaths
        affectedFeatures.forEach(feature => {
          const countyName = feature.properties.NAME;
          const geoid = feature.properties.GEOID;
          const overlapArea = turf.intersect(turfCircle, feature);
          if (!overlapArea || !geoid || !countiesFromDB[geoid]) return;

          const overlapM2 = turf.area(overlapArea);
          const countyData = countiesFromDB[geoid];

          // Check if population and area are valid
          if (isNaN(countyData.population) || isNaN(countyData.area)) {
            console.warn(`Invalid data for ${countyName}: population or area is NaN`);
            return;  // Skip invalid data
          }

          const percentOverlap = overlapM2 / countyData.area;
          const estimatedDeaths = Math.round(percentOverlap * countyData.population * (bombData.death_percentage / 100));

          // Skip invalid death calculations
          if (isNaN(estimatedDeaths)) {
            console.warn(`Invalid death calculation for ${countyName}: estimatedDeaths is NaN`);
            return;  // Skip invalid death calculation
          }

          results.push({
            bomb: bombName,
            county: countyName,
            deaths: estimatedDeaths,
            lat: position.lat,
            lng: position.lng
          });
        });

        // Step 2.4: Send results to backend
        return fetch('store_results.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(results)
        });
      })
      .then(res => res.json())
      .then(response => {
        const resultsList = document.getElementById('results-list');
        const totalDeathsDisplay = document.getElementById('total-deaths');
        resultsList.innerHTML = '';  // Clear previous results

        let totalDeaths = 0;

        // Add only valid deaths (non-NaN values)
        results.forEach(entry => {
          if (isNaN(entry.deaths)) return;  // Skip invalid death values
          totalDeaths += entry.deaths;

          // Append the result for this county
          resultsList.innerHTML += `
            <div style="padding: 6px 8px; border-bottom: 1px solid #ddd;">
              <strong>${entry.county}</strong>: ${entry.deaths.toLocaleString()} deaths
            </div>
          `;
        });

        totalDeathsDisplay.innerHTML = `TOTAL ESTIMATED DEATHS: ${totalDeaths.toLocaleString()}`;
      })
      .catch(err => {
        console.error("💥 Error during calculation:", err);
      });
  });
});


// Reset button functionality
document.getElementById('reset').addEventListener('click', (e) => {
  e.preventDefault();

  // Reset map to default position and zoom
  marker.setLatLng(defaultPosition);
  map.setView(defaultPosition, initialZoom);

  // Reset the bomb dropdown to the first option
  document.getElementById('bomb-type').value = 'Tsar Bomba'; // Default bomb value

  // Reset the magnitude slider to its initial value
  document.getElementById('magnitude-slider').value = 1; // Reset slider to 1%
  document.getElementById('slider-value').innerText = '1X'; // Update the slider value display

  // Reset magnitudeMultiplier to 1 (default value)
  magnitudeMultiplier = 1.0;

  // Reset the results display
  document.getElementById('results').style.display = 'none';  // Hide the results section
  document.getElementById('reset').style.display = 'none';    // Hide the reset button

  // Clear the results list
  document.getElementById('results-list').innerHTML = '';  // Empty the results list
  document.getElementById('total-deaths').innerHTML = ''; // Clear total deaths display

  // Redraw the circle with the default radius (reset to the original size)
  drawCircle();
});





// ✅ Initial setup
getCountyName(defaultPosition[0], defaultPosition[1]);
fetchBombDataAndDraw(); // Draw first bomb on load
