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
            minZoom: 5.45,
            maxBounds: indonesiaBounds,
            maxBoundsViscosity: 1.0,
            zoomControl: true
        });
        // 1. Layer Peta
        
        // A. Peta Jalan Standar (OpenStreetMap)
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // B. Peta Topografi Hutan & Gunung (OpenTopoMap)
        const topoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenTopoMap (CC-BY-SA)'
        });

        // 2. Layer Overlay

        // C. Layer Konservasi Laut (OpenSeaMap)
        // const seaLayer = L.tileLayer('https://tiles.openseamap.org/seamark/{z}/{x}/{y}.png', {
        //     attribution: '© OpenSeaMap contributors'
        // });

        // D. Layer Cuaca & Curah Hujan (RainViewer)
        const rainLayer = L.tileLayer('', {
            attribution: 'Weather data © <a href="https://rainviewer.com">RainViewer</a>',
            opacity: 0.6
        });

        // E. Layer Kualitas Udara (WAQI)
        const aqiLayer = L.tileLayer('https://tiles.waqi.info/tiles/usepa-aqi/{z}/{x}/{y}.png?token=bf5b26580b95f69c3c26391ca4a96dd03ffe78c0', {
            attribution: 'Air Quality Tiles © <a href="https://waqi.info">waqi.info</a>',
            opacity: 0.8 
        });

        // 3. Menu Kontrol Layer Di Pojok Kanan Atas
        
        // Pilihan peta dasar
        const baseMaps = {
            "Peta Standar": streetMap,
            "Peta Topografi (Gunung/Hutan)": topoMap
        };

        // Pilihan overlay
        const overlayMaps = {
            // "Layer Data Kelautan": seaLayer,
            "Layer Cuaca & Curah Hujan": rainLayer,
            "Layer Kualitas Udara (AQI)": aqiLayer
        };
        L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

        // 4. Mengambil data cuaca
        fetch('https://api.rainviewer.com/public/weather-maps.json')
            .then(response => response.json())
            .then(data => {
                const latestRadarPath = data.radar.past[data.radar.past.length - 1].path;
                const tileUrl = `https://tilecache.rainviewer.com${latestRadarPath}/256/{z}/{x}/{y}/2/1_1.png`;
                rainLayer.setUrl(tileUrl);
            })
            .catch(error => console.error('Gagal mengambil data cuaca:', error));

        // Script Marker Lokasi
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

            const marker = L.marker([loc.lat, loc.lng], { icon: customIcon }).addTo(map);

            // 3. Mengambil data cuaca secara langsung berdasarkan titik koordinat (Open-Meteo)
            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${loc.lat}&longitude=${loc.lng}&current_weather=true`)
                .then(response => response.json())
                .then(data => {
                    const temp = data.current_weather.temperature;
                    const wind = data.current_weather.windspeed;

                    const popupContent = `
                        <div style="padding: 8px;">
                            <h3 style="margin: 0 0 4px 0; font-size: 14px; color: #1e293b; font-weight: 800;">${loc.name}</h3>
                            <p style="margin: 0 0 8px 0; font-size: 11px; color: #64748b;">${loc.description}</p>
                            <div style="padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #0ea5e9; font-weight: bold;">
                                Suhu: ${temp}°C | Angin: ${wind} km/j
                            </div>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                })
                .catch(() => {
                    const popupContent = `
                        <div style="padding: 8px;">
                            <h3 style="margin: 0 0 4px 0; font-size: 14px; color: #1e293b; font-weight: 800;">${loc.name}</h3>
                            <p style="margin: 0; font-size: 11px; color: #64748b;">${loc.description}</p>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                });
        });
    </script>
</body>
</html>