<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Screen</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        body { margin: 0; padding: 0; font-family: sans-serif; background-color: #f8fafc; }
        #map { height: calc(100vh - 80px); width: 100%; z-index: 0; }
        
        .custom-marker {
            width: 32px;
            height: 32px;
            border-radius: 12px;
            border: 4px solid white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-slate-50">

    <x-navbar />

    <div id="map"></div>

    <script>
        const indonesiaBounds = [
            [-11.0, 94.0],
            [6.0, 142.0]
        ];

        const map = L.map('map', {
            center: [-0.7893, 113.9213],
            zoom: 5,
            minZoom: 5,
            maxBounds: indonesiaBounds,
            maxBoundsViscosity: 1.0,
            zoomControl: false
        });

        // 1. Layer Peta Dasar (OpenStreetMap)
        const baseMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 2. Layer Polusi Udara (WAQI)
        // const aqiLayer = L.tileLayer('https://tiles.waqi.info/tiles/usepa-aqi/{z}/{x}/{y}.png?token=bf5b26580b95f69c3c26391ca4a96dd03ffe78c0', {
        //     attribution: 'Air Quality Tiles © <a href="https://waqi.info">waqi.info</a>',
        //     opacity: 0.7
        // });

        // const overlayMaps = {
        //     "Polusi Udara (AQI)": aqiLayer
        // };
        // L.control.layers(null, overlayMaps, { position: 'topright' }).addTo(map);

        // Data Lokasi
        const locations = @json($locations);

        locations.forEach(loc => {
            let markerColor = '#10b981'; 
            if (loc.status === 'yellow') markerColor = '#f59e0b';
            if (loc.status === 'red') markerColor = '#ef4444';

            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-marker" style="background-color: ${markerColor};"></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
            });

            const popupContent = `
                <div style="padding: 8px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 14px; color: #1e293b; font-weight: 800;">${loc.name}</h3>
                    <p style="margin: 0; font-size: 11px; color: #64748b;">${loc.description}</p>
                </div>
            `;

            L.marker([loc.lat, loc.lng], { icon: customIcon })
                .addTo(map)
                .bindPopup(popupContent);
        });
    </script>
</body>
</html>