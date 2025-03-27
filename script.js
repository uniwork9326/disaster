// Default position and zoom level
const defaultPosition = [37.8, -96]; // Center of the contiguous US
const initialZoom = 5; // Desired zoom level for the US

// Initialize the map with the default position and zoom
const map = L.map('map').setView(defaultPosition, initialZoom);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Load the GeoJSON data for US counties
fetch('counties.geojson')
  .then((response) => response.json())
  .then((geojsonData) => {
    // Add GeoJSON layer to the map with customized styling
    const countiesLayer = L.geoJSON(geojsonData, {
      style: {
        color: '#444', // Light gray for boundaries
        weight: 0.5, // Thin boundary lines
        fillColor: 'transparent', // Transparent fill
        fillOpacity: 0.1,
      },
    }).addTo(map);
  });

// Add a draggable marker to the map
const marker = L.marker(defaultPosition, { draggable: true }).addTo(map);

// Function to fetch county name using Reverse Geocoding
function getCountyName(lat, lon) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;

  fetch(url)
      .then(response => response.json())
      .then(data => {
          if (data.address) {
              let county = data.address.county || "Unknown County";
              let state = data.address.state || "Unknown State";
              let fullAddress = data.display_name || "Location not found";

              // Update the display panel with county name
              document.getElementById('location-display').innerHTML =
                  `<strong>County:</strong> ${county} <br>
                   <strong>State:</strong> ${state} <br>
                   <strong>Address:</strong> ${fullAddress} <br>
                   <strong>Coordinates:</strong> ${lat.toFixed(4)}, ${lon.toFixed(4)}`;
          } else {
              document.getElementById('location-display').innerText = "County not found";
          }
      })
      .catch(error => {
          console.error("Error fetching county name:", error);
          document.getElementById('location-display').innerText = "Error retrieving location";
      });
}

// Update location display dynamically when marker is moved
marker.on('dragend', function () {
  const position = marker.getLatLng();
  getCountyName(position.lat, position.lng);
});



// Reset the map view and marker when the logo is clicked
document.getElementById('reset-logo').addEventListener('click', (e) => {
  e.preventDefault(); // Prevent default link behavior
  
  marker.setLatLng(defaultPosition); // Reset marker to the default position
  map.setView(defaultPosition, initialZoom); // Reset map view to the default position and zoom
  
  // Wait for the marker position to update, then get county name
  setTimeout(() => {
    const position = marker.getLatLng(); // Get updated position
    getCountyName(position.lat, position.lng); // Fetch new county name
  }, 500); // Small delay to ensure the marker has moved
});


// Handle "Detonate" button click
document.getElementById('detonate').addEventListener('click', () => {
  const weaponType = document.getElementById('bomb-type').value;
  const position = marker.getLatLng();

  // Send data to the backend for impact calculations
  fetch('/disaster_simulator/simulate.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      lat: position.lat,
      lng: position.lng,
      weaponType,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      // Display results in the sidebar
      const resultsList = document.getElementById('results-list');
      resultsList.innerHTML = '';
      data.forEach((county) => {
        resultsList.innerHTML += `<p>${county.name}: Population Impact - ${county.populationImpact}, Economic Impact - $${county.economicImpact}</p>`;
      });
    });
});