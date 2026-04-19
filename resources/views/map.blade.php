<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Screen</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
    
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
    @if(auth()->check() && auth()->user()->role === 'admin')
    <button id="btnEditMode" class="fixed bottom-6 left-6 z-[1000] bg-white border-2 border-slate-300 px-4 py-2 rounded-lg font-bold shadow-md hover:bg-slate-100">
        Edit
    </button>
    @endif

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

        // Script Render Markers dari Database dengan Open-Meteo
        const isAdmin = {{ auth()->check() && auth()->user()?->role === 'admin' ? 'true' : 'false' }};
        const savedMarkers = @json($markers);

        savedMarkers.forEach(item => {
            let coordinates;
            try {
                coordinates = typeof item.coordinates === 'string'
                    ? JSON.parse(item.coordinates)
                    : item.coordinates;
            } catch (e) {
                console.warn('Koordinat tidak valid:', item);
                return;
            }

            let color = '#10b981';
            if (item.status === 'yellow') color = '#f59e0b';
            if (item.status === 'red')    color = '#ef4444';

            const shapeType = item.shape_type;
            let layer;

            // Popup dengan data cuaca Open-Meteo
            function buildPopupWithWeather(lat, lng, layerObj) {
                fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
                    .then(r => r.json())
                    .then(data => {
                        const temp = data.current_weather.temperature;
                        const wind = data.current_weather.windspeed;
                        layerObj.bindPopup(`
                            <div style="padding:8px; min-width:180px;">
                                <h3 style="margin:0 0 4px 0; font-size:14px; font-weight:800; color:#1e293b;">${item.title || '(Tanpa Judul)'}</h3>
                                <p style="margin:0 0 8px 0; font-size:11px; color:#64748b;">${item.description || ''}</p>
                                <div style="padding-top:8px; border-top:1px solid #e2e8f0; font-size:11px; color:#0ea5e9; font-weight:bold;">
                                    Suhu: ${temp}°C | Angin: ${wind} km/j
                                </div>
                                ${isAdmin ? `<a href="/admin/markers/${item.id}/edit" style="display:inline-block; margin-top:6px; margin-right:4px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#3b82f6; color:#fff; text-decoration:none;">Edit</a>` : ''}
                                <a href="/markers/${item.id}" style="display:inline-block; margin-top:6px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#64748b; color:#fff; text-decoration:none;">Detail Info</a>
                                <span style="display:inline-block; margin-top:6px; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:${color}; color:#fff;">${item.status.toUpperCase()}</span>
                            </div>`);
                    })
                    .catch(() => {
                        layerObj.bindPopup(`
                            <div style="padding:8px; min-width:180px;">
                                <h3 style="margin:0 0 4px 0; font-size:14px; font-weight:800; color:#1e293b;">${item.title || '(Tanpa Judul)'}</h3>
                                <p style="margin:0 0 8px 0; font-size:11px; color:#64748b;">${item.description || ''}</p>
                                <span style="display:inline-block; margin-top:6px; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:${color}; color:#fff;">${item.status.toUpperCase()}</span>
                            </div>`);
                    });
            }

            if (shapeType === 'Marker') {
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-marker" style="background-color:${color};"></div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                });
                layer = L.marker([coordinates[0], coordinates[1]], { icon: customIcon }).addTo(map);
                buildPopupWithWeather(coordinates[0], coordinates[1], layer);

            } else if (shapeType === 'Circle') {
                layer = L.circle([coordinates[0], coordinates[1]], {
                    radius: item.radius || 500,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.25,
                    weight: 2
                }).addTo(map);
                buildPopupWithWeather(coordinates[0], coordinates[1], layer);

            } else if (shapeType === 'Polygon' || shapeType === 'Rectangle') {
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polygon(latlngs, {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.25,
                    weight: 2
                }).addTo(map);
                const bounds = layer.getBounds();
                const center = bounds.getCenter();
                buildPopupWithWeather(center.lat, center.lng, layer);

            } else if (shapeType === 'Line' || shapeType === 'Polyline') {
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polyline(latlngs, { color: color, weight: 3 }).addTo(map);
                const midIdx = Math.floor(latlngs.length / 2);
                buildPopupWithWeather(latlngs[midIdx][0], latlngs[midIdx][1], layer);
            }
        });

        map.pm.addControls({
            position: 'topleft',
            drawMarker: false,
            drawCircle: false,
            drawPolygon: false,
            drawPolyline: false,
            drawRectangle: false,
            drawCircleMarker: false,
            drawText: false,
            editMode: false,
            dragMode: false,
            cutPolygon: false,
            removalMode: false
        });

        let isEditMode = false;
        const btnEdit = document.getElementById('btnEditMode');

        btnEdit.addEventListener('click', () => {
            isEditMode = !isEditMode;
            if (isEditMode) {
                btnEdit.innerText = "Keluar Mode Edit";
                btnEdit.classList.replace('bg-white', 'bg-red-100');
                map.pm.addControls({ drawMarker: true, drawCircle: true, drawPolygon: true, drawPolyline: true, drawRectangle: true, editMode: true, removalMode: true });
            } else {
                btnEdit.innerText = "Edit";
                btnEdit.classList.replace('bg-red-100', 'bg-white');
                map.pm.removeControls();
                map.pm.disableDraw();
            }
        });

        map.on('pm:create', (e) => {
            const layer = e.layer;
            const shapeType = e.shape; 
            
            let coords = [];
            let radius = null;

            if (shapeType === 'Circle') {
                const latlng = layer.getLatLng();
                coords = [latlng.lat, latlng.lng];
                radius = layer.getRadius();
            } else if (shapeType === 'Marker') {
                const latlng = layer.getLatLng();
                coords = [latlng.lat, latlng.lng];
            } else if (shapeType === 'Polygon') {
                const latlngs = layer.getLatLngs()[0];
                coords = latlngs.map(pos => [pos.lat, pos.lng]);
            } else if (shapeType === 'Line') {
                const latlngs = layer.getLatLngs();
                coords = latlngs.map(pos => [pos.lat, pos.lng]);
            } else if (shapeType === 'Rectangle') {
                const latlngs = layer.getLatLngs()[0];
                coords = latlngs.map(pos => [pos.lat, pos.lng]);
            }

            const popupContent = `
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-bold mb-2">Data Lokasi</h3>
                    <input type="text" id="inputTitle" placeholder="Judul" class="w-full mb-2 p-1 border rounded" />
                    <textarea id="inputDesc" placeholder="Deskripsi" class="w-full mb-2 p-1 border rounded"></textarea>
                    <select id="inputStatus" class="w-full mb-2 p-1 border rounded">
                        <option value="green">Aman (Hijau)</option>
                        <option value="yellow">Waspada (Kuning)</option>
                        <option value="red">Bahaya (Merah)</option>
                    </select>
                    <button onclick="saveShape('${shapeType}', '${JSON.stringify(coords)}', ${radius})" class="w-full bg-emerald-500 text-white p-1 rounded font-bold">Simpan</button>
                </div>
            `;
            
            layer.bindPopup(popupContent, { minWidth: 250, maxWidth: 300 }).openPopup();
        });

        window.saveShape = function(shapeType, coordsString, radius) {
            const coords = JSON.parse(coordsString);
            const title = document.getElementById('inputTitle').value;
            const desc = document.getElementById('inputDesc').value;
            const status = document.getElementById('inputStatus').value;

            fetch('/admin/markers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    shape_type: shapeType,
                    status: status,
                    title: title,
                    description: desc,
                    coordinates: coords,
                    radius: radius || 0
                })
            })
            .then(async res => {
                if (!res.ok) {
                    const err = await res.json();
                    console.error('Error dari server:', err);
                    throw new Error('Server error');
                }
                window.location.reload();
                return res.json();
            })
            .catch(err => {
                alert("Gagal menyimpan data");
                console.error(err);
            });
        };
    </script>
</body>
</html>