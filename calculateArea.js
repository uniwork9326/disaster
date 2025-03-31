fetch('counties.geojson')
  .then(res => res.json())
  .then(data => {
    const features = data.features;
    let i = 0;

    function sendNext() {
      if (i >= features.length) {
        console.log("✅ All areas sent.");
        return;
      }

      const feature = features[i];
      const area = turf.area(feature); // Area in square meters
      const countyName = feature.properties.NAME;

      console.log(`Sending: ${countyName}, Area: ${area.toFixed(2)} m²`);

      fetch('save_area.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          county: countyName,
          area: area
        })
      })
      .then(res => res.json())
      .then(response => {
        console.log(`✅ Saved: ${countyName}`, response);
        i++;
        setTimeout(sendNext, 5); // Wait 50ms between each request
      })
      .catch(err => {
        console.error(`❌ Error saving ${countyName}:`, err);
        i++;
        setTimeout(sendNext, 10); // Wait a bit longer if error occurs
      });
    }

    sendNext(); // Start the loop
  })
  .catch(err => console.error("Error loading GeoJSON:", err));
