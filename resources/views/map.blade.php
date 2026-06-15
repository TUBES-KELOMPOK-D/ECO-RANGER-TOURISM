<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Peta interaktif destinasi eco-tourism Indonesia - Eco Ranger Tourism">
    <title>Peta — Eco Ranger Tourism</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
</head>
<body class="bg-slate-50">

    <x-navbar />

    <div id="searchBar">
        <ul id="searchResults" class="hidden"></ul>
        <div class="search-wrapper">
            <div id="filterWrapper">
                <button id="filterToggleBtn" title="Filter Status">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    <span id="filterDot" class="hidden"></span>
                </button>
                <div id="filterDropdown" class="hidden">
                    <div class="filter-grid">
                        <div>
                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 12px 16px 4px 16px; text-transform: uppercase; letter-spacing: 1px;">Status</p>
                            <ul id="statusFilterList">
                                <li data-value="all" class="active">Semua Status</li>
                                <li data-value="green" class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Sangat Terjaga</li>
                                <li data-value="yellow" class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> Terjaga</li>
                                <li data-value="red" class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Perlu Perhatian</li>
                            </ul>
                            <hr style="border:none; border-top:1px solid #e2e8f0; margin:10px 0;">
                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 12px 16px 4px 16px; text-transform: uppercase; letter-spacing: 1px;">Tipe</p>
                            <ul id="typeFilterList">
                                <li data-value="all" class="active">Semua Tipe</li>
                                <li data-value="wisata" class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Destinasi Wisata</li>
                                <li data-value="lingkungan" class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg> Kondisi Lingkungan</li>
                            </ul>
                        </div>
                        <div>
                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 12px 16px 4px 16px; text-transform: uppercase; letter-spacing: 1px;">Jenis Peta</p>
                            <ul id="baseMapFilterList">
                                <li data-value="standard" class="active flex items-center gap-2">
                                    <input type="radio" name="basemap" checked class="accent-emerald-600 pointer-events-none" style="margin-right:4px;"> Peta Standar
                                </li>
                                <li data-value="satellite" class="flex items-center gap-2">
                                    <input type="radio" name="basemap" class="accent-emerald-600 pointer-events-none" style="margin-right:4px;"> Peta Satelit
                                </li>
                            </ul>
                            <hr style="border:none; border-top:1px solid #e2e8f0; margin:10px 0;">
                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 12px 16px 4px 16px; text-transform: uppercase; letter-spacing: 1px;">Layer Tambahan</p>
                            <ul id="overlayFilterList">
                                <li data-value="rain" class="flex items-center gap-2">
                                    <input type="checkbox" class="accent-emerald-600 pointer-events-none" style="margin-right:4px;"> Cuaca &amp; Curah Hujan
                                </li>
                                <li data-value="aqi" class="flex items-center gap-2">
                                    <input type="checkbox" class="accent-emerald-600 pointer-events-none" style="margin-right:4px;"> Kualitas Udara (AQI)
                                </li>
                                <li data-value="land" class="active flex items-center gap-2">
                                    <input type="checkbox" checked class="accent-emerald-600 pointer-events-none" style="margin-right:4px;"> Area Daratan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-divider"></div>
            <input type="text" id="mapSearchInput" placeholder="Cari area">
            <button id="searchBtn">Cari</button>
        </div>
    </div>

    <div id="map"></div>

    <script>
        const isAdmin      = {{ auth()->check() && auth()->user()?->role === 'admin' ? 'true' : 'false' }};
        const savedMarkers = @json($markers);
        const csrfToken    = '{{ csrf_token() }}';

        // 1. Inisialisasi Peta
        const indonesiaBounds = [[-11.0, 94.0], [6.0, 142.0]];

        const map = L.map('map', {
            center: [-0.7893, 113.9213],
            zoom: 5,
            minZoom: 5.45,
            maxBounds: indonesiaBounds, 
            maxBoundsViscosity: 1.0,
            zoomControl: true
        });

        const urlParams = new URLSearchParams(window.location.search);
        const returnTo = urlParams.get('return_to');
        const selectLocation = urlParams.get('select_location') === '1';

        if (selectLocation && returnTo) {
            const infoControl = L.control({ position: 'topright' });
            infoControl.onAdd = function () {
                const container = L.DomUtil.create('div', 'rounded-full bg-slate-900/90 text-white px-4 py-2 text-sm font-semibold shadow-xl');
                container.innerHTML = 'Tap peta untuk pilih lokasi';
                return container;
            };
            infoControl.addTo(map);

            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(5);
                const lng = e.latlng.lng.toFixed(5);
                window.location.href = `${returnTo}?latitude=${encodeURIComponent(lat)}&longitude=${encodeURIComponent(lng)}`;
            });
        }

        // 2. Layer Peta
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const topoMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© ESRI (Satellite)'
        });

        // 3. Layer Overlay
        const rainLayer = L.tileLayer('', {
            attribution: 'Weather data © <a href="https://rainviewer.com">RainViewer</a>',
            opacity: 0.6
        });

        const aqiLayer = L.tileLayer(
            'https://tiles.waqi.info/tiles/usepa-aqi/{z}/{x}/{y}.png?token=bf5b26580b95f69c3c26391ca4a96dd03ffe78c0',
            {
                attribution: 'Air Quality Tiles © <a href="https://waqi.info">waqi.info</a>',
                opacity: 0.8
            }
        );

        // 4. Kontrol Layer
        const landLayer = L.layerGroup().addTo(map);
        // const seaLayer = L.layerGroup().addTo(map);

        // Layer control dipindahkan ke filter dropdown kustom (di sebelah search bar)
        // const baseMaps    = { "Peta Standar": streetMap, "Peta Satelit": topoMap };
        // const overlayMaps = { 
        //     "Layer Cuaca & Curah Hujan": rainLayer, 
        //     "Layer Kualitas Udara (AQI)": aqiLayer,
        //     "Area Daratan": landLayer,
        // };
        // L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

        map.createPane('geoJsonPane');
        map.getPane('geoJsonPane').style.zIndex = 200;

        // 5. Ambil Data Cuaca RainViewer
        fetch('https://api.rainviewer.com/public/weather-maps.json')
            .then(r => r.json())
            .then(data => {
                const path = data.radar.past[data.radar.past.length - 1].path;
                rainLayer.setUrl(`https://tilecache.rainviewer.com${path}/256/{z}/{x}/{y}/2/1_1.png`);
            })
            .catch(err => console.error('Gagal mengambil data cuaca:', err));

        // Tambahan Layer GeoJSON Daratan & Lautan
        fetch('/geojson/indonesia-land.geojson')
             .then(response => response.json())
             .then(data => {
                 L.geoJSON(data, {
                     style: {
                         color: "#01250eff",
                         weight: 2,
                         fillColor: "#baa996ff",
                         fillOpacity: 0.2
                     },
                     pane: 'geoJsonPane',   
                     interactive: false,    
                 }).addTo(landLayer);
             })
             .catch(err => console.error('Gagal meload indonesia-land.geojson:', err));

        // fetch('/geojson/indonesia-sea.geojson')
        //     .then(response => response.json())
        //     .then(data => {
        //         L.geoJSON(data, {
        //             style: {
        //                 color: "#3b82f6", // Warna garis (Biru)
        //                 weight: 2,
        //                 fillColor: "#60a5fa", // Warna isi
        //                 fillOpacity: 0.2
        //             }
        //         }).addTo(seaLayer);
        //     })
        //     .catch(err => console.error('Gagal meload indonesia-sea.geojson:', err));

        // 6. Render Markers dari Database
        let searchableFeatures = [];

        // --- Helper: tentukan apakah ini wisata atau kondisi lingkungan ---
        function isWisata(item) {
            if (item.category === 'Destinasi Wisata') return true;
            if (item.category === 'Kondisi Lingkungan') return false;
            // fallback berdasarkan shape_type
            return item.shape_type === 'Marker';
        }

        // --- Popup untuk Destinasi Wisata (Pinpoint) ---
        function buildPinpointPopup(lat, lng, layerObj, item, color) {
            const scoreHtml = item.eco_health_score
                ? `<div style="display:flex;align-items:center;gap:6px;margin:6px 0 8px;">
                    <span style="font-size:18px;font-weight:900;color:#065f46;">${parseFloat(item.eco_health_score).toFixed(1)}</span>
                    <span style="font-size:10px;color:#059669;font-weight:700;letter-spacing:0.5px;">ECO SCORE</span>
                  </div>`
                : '';

            const buildHTML = (weatherLine) => `
                <div style="padding:10px 12px;min-width:200px;max-width:260px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                        <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:9px;font-weight:800;letter-spacing:0.8px;background:#d1fae5;color:#065f46;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> DESTINASI WISATA</span>
                    </div>
                    <h3 style="margin:0 0 3px 0;font-size:14px;font-weight:800;color:#1e293b;line-height:1.3;">${item.title || '(Tanpa Judul)'}</h3>
                    ${item.location_name ? `<p style="margin:0 0 6px;font-size:11px;color:#10b981;font-weight:600;display:flex;align-items:center;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> ${item.location_name}</p>` : ''}
                    ${scoreHtml}
                    <p style="margin:0 0 8px;font-size:11px;color:#64748b;line-height:1.5;">${(item.description||'').substring(0,120)}${(item.description||'').length>120?'…':''}</p>
                    ${weatherLine ? `<div style="padding:6px 8px;border-radius:8px;background:#f0f9ff;font-size:11px;color:#0ea5e9;font-weight:600;margin-bottom:8px;">${weatherLine}</div>` : ''}
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        ${isAdmin ? `<a href="/admin/markers/${item.id}/edit" style="padding:4px 10px;border-radius:8px;font-size:11px;font-weight:700;background:#059669;color:#fff;text-decoration:none;">Edit</a>` : ''}
                        <a href="/markers/${item.id}" style="padding:4px 10px;border-radius:8px;font-size:11px;font-weight:700;background:#1e293b;color:#fff;text-decoration:none;">Lihat Detail</a>
                        <span style="padding:4px 10px;border-radius:8px;font-size:10px;font-weight:700;background:${color};color:#fff;">${(item.status||'green').toUpperCase()}</span>
                    </div>
                </div>`;

            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
                .then(r => r.json())
                .then(data => {
                    const temp = data.current_weather.temperature;
                    const wind = data.current_weather.windspeed;
                    layerObj.bindPopup(buildHTML(`<span style="display:flex;align-items:center;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path></svg> ${temp}°C &nbsp;|&nbsp; <svg style="margin:left:4px;margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path></svg> ${wind} km/j</span>`), { minWidth: 220, maxWidth: 280 });
                })
                .catch(() => layerObj.bindPopup(buildHTML(null), { minWidth: 220, maxWidth: 280 }));
        }

        // --- Popup untuk Kondisi Lingkungan (Shape) ---
        function buildEnvPopup(lat, lng, layerObj, item, color, shapeLabel) {
            const buildHTML = (weatherLine) => `
                <div style="padding:10px 12px;min-width:200px;max-width:260px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                        <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:9px;font-weight:800;letter-spacing:0.8px;background:#dbeafe;color:#1d4ed8;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg> KONDISI LINGKUNGAN</span>
                        <span style="font-size:9px;font-weight:700;color:#94a3b8;">${shapeLabel}</span>
                    </div>
                    <h3 style="margin:0 0 3px 0;font-size:14px;font-weight:800;color:#1e293b;line-height:1.3;">${item.title || '(Tanpa Judul)'}</h3>
                    ${item.location_name ? `<p style="margin:0 0 6px;font-size:11px;color:#64748b;font-weight:600;display:flex;align-items:center;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> ${item.location_name}</p>` : ''}
                    <p style="margin:0 0 8px;font-size:11px;color:#64748b;line-height:1.5;">${(item.description||'').substring(0,120)}${(item.description||'').length>120?'…':''}</p>
                    ${weatherLine ? `<div style="padding:6px 8px;border-radius:8px;background:#f0f9ff;font-size:11px;color:#0ea5e9;font-weight:600;margin-bottom:8px;">${weatherLine}</div>` : ''}
                    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                        <span style="padding:4px 10px;border-radius:8px;font-size:10px;font-weight:700;background:${color};color:#fff;">${(item.status||'green').toUpperCase()}</span>
                        ${isAdmin ? `<a href="/admin/markers/${item.id}/edit" style="padding:4px 10px;border-radius:8px;font-size:11px;font-weight:700;background:#059669;color:#fff;text-decoration:none;">Edit</a>` : ''}
                    </div>
                </div>`;

            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
                .then(r => r.json())
                .then(data => {
                    const temp = data.current_weather.temperature;
                    const wind = data.current_weather.windspeed;
                    layerObj.bindPopup(buildHTML(`<span style="display:flex;align-items:center;"><svg style="margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path></svg> ${temp}°C &nbsp;|&nbsp; <svg style="margin:left:4px;margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path></svg> ${wind} km/j</span>`), { minWidth: 220, maxWidth: 280 });
                })
                .catch(() => layerObj.bindPopup(buildHTML(null), { minWidth: 220, maxWidth: 280 }));
        }

        const shapeLabels = { Polygon: 'Area', Rectangle: 'Area Kotak', Circle: 'Lingkaran', Line: 'Garis', Polyline: 'Garis' };

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

            const shapeType  = item.shape_type;
            const itemIsWisata = isWisata(item);
            let layer;

            if (shapeType === 'Marker') {
                // Pinpoint — Destinasi Wisata
                const customIcon = L.divIcon({
                    className: '',
                    html: `<div class="custom-marker-wrapper"><div class="custom-marker" style="background-color:${color};"></div></div>`,
                    iconSize: [34, 40],
                    iconAnchor: [17, 38],
                });
                layer = L.marker([coordinates[0], coordinates[1]], { icon: customIcon }).addTo(map);
                buildPinpointPopup(coordinates[0], coordinates[1], layer, item, color);

            } else if (shapeType === 'Circle') {
                // Lingkaran — Kondisi Lingkungan
                layer = L.circle([coordinates[0], coordinates[1]], {
                    radius: item.radius || 500,
                    color,
                    fillColor: color,
                    fillOpacity: 0.20,
                    weight: 2.5,
                    dashArray: '6 4'
                }).addTo(map);
                buildEnvPopup(coordinates[0], coordinates[1], layer, item, color, 'Lingkaran');

            } else if (shapeType === 'Polygon' || shapeType === 'Rectangle') {
                // Area — Kondisi Lingkungan
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polygon(latlngs, {
                    color,
                    fillColor: color,
                    fillOpacity: 0.18,
                    weight: 2.5,
                    dashArray: '6 4'
                }).addTo(map);
                const center = layer.getBounds().getCenter();
                buildEnvPopup(center.lat, center.lng, layer, item, color, shapeLabels[shapeType] || 'Area');

            } else if (shapeType === 'Line' || shapeType === 'Polyline') {
                // Garis — Kondisi Lingkungan
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polyline(latlngs, {
                    color,
                    weight: 4,
                    dashArray: '10 6',
                    opacity: 0.85
                }).addTo(map);
                const midIdx = Math.floor(latlngs.length / 2);
                buildEnvPopup(latlngs[midIdx][0], latlngs[midIdx][1], layer, item, color, 'Garis');
            }

            if (layer) {
                searchableFeatures.push({
                    layer,
                    title:       item.title || '(Tanpa Judul)',
                    description: item.description || '',
                    status:      item.status || 'green',
                    type:        shapeType,
                    subtype:     itemIsWisata ? 'wisata' : 'lingkungan',
                    center:      shapeType === 'Marker' ? [coordinates[0], coordinates[1]] : layer.getBounds().getCenter()
                });
            }
        });

        // 7. Geoman Controls — tampil penuh untuk admin, tersembunyi untuk user biasa
        if (isAdmin) {
            map.pm.addControls({
                position: 'topleft',
                drawMarker: true, drawCircle: true, drawPolygon: true,
                drawPolyline: true, drawRectangle: true, drawCircleMarker: false,
                drawText: false, editMode: true, dragMode: false,
                cutPolygon: false, removalMode: true
            });
        }

        // 8. Create Shape Event
        map.on('pm:create', (e) => {
            const layer     = e.layer;
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
            } else if (shapeType === 'Polygon' || shapeType === 'Rectangle') {
                coords = layer.getLatLngs()[0].map(pos => [pos.lat, pos.lng]);
            } else if (shapeType === 'Line') {
                coords = layer.getLatLngs().map(pos => [pos.lat, pos.lng]);
            }

            const safeCoords = JSON.stringify(coords).replace(/'/g, "&#39;");

            const popupContent = `
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-bold mb-2">Data Lokasi</h3>
                    <input type="text" id="inputTitle" placeholder="Judul" class="w-full mb-2 p-1 border rounded" />
                    <textarea id="inputDesc" placeholder="Deskripsi" class="w-full mb-2 p-1 border rounded"></textarea>
                    <select id="inputStatus" class="w-full mb-2 p-1 border rounded">
                        <option value="green">Sangat Terjaga (Hijau)</option>
                        <option value="yellow">Terjaga (Kuning)</option>
                        <option value="red">Perlu Perhatian (Merah)</option>
                    </select>
                    <button onclick="saveShape('${shapeType}', this)"
                            data-coords='${safeCoords}'
                            data-radius='${radius}'
                            class="w-full bg-emerald-500 text-white p-1 rounded font-bold">
                        Simpan
                    </button>
                </div>
            `;

            layer.bindPopup(popupContent, { minWidth: 250, maxWidth: 300 }).openPopup();
        });

        // 10. Save Shape ke Server
        window.saveShape = function (shapeType, btnEl) {
            const coords = JSON.parse(btnEl.getAttribute('data-coords'));
            const rawRadius = btnEl.getAttribute('data-radius');
            const radius = (rawRadius && rawRadius !== 'null') ? parseFloat(rawRadius) : 0;
            const title  = document.getElementById('inputTitle').value;
            const desc   = document.getElementById('inputDesc').value;
            const status = document.getElementById('inputStatus').value;

            fetch('/admin/markers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    shape_type:  shapeType,
                    status,
                    title,
                    description: desc,
                    coordinates: coords,
                    radius:      radius
                })
            })
            .then(async res => {
                if (!res.ok) {
                    const err = await res.json();
                    console.error('Error dari server:', err);
                    throw new Error('Server error');
                }
                window.location.reload();
            })
            .catch(err => {
                alert('Gagal menyimpan data');
                console.error(err);
            });
        };

        // 11. Logika Pencarian
        const searchInput   = document.getElementById('mapSearchInput');
        const searchResults = document.getElementById('searchResults');
        const searchBtn     = document.getElementById('searchBtn');

        function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            searchResults.innerHTML = '';

            if (query.length === 0) {
                searchResults.classList.add('hidden');
                return;
            }

            const filtered = searchableFeatures.filter(f =>
                f.title.toLowerCase().includes(query)
            );

            if (filtered.length > 0) {
                searchResults.classList.remove('hidden');

                filtered.forEach(feature => {
                    const li = document.createElement('li');

                    let statusBg = '#10b981';
                    if (feature.status === 'yellow') statusBg = '#f59e0b';
                    if (feature.status === 'red')    statusBg = '#ef4444';

                    li.innerHTML = `
                        <span style="font-weight:600; color:#1e293b; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            ${feature.title}
                        </span>
                        <div style="display:flex; gap:6px; flex-shrink:0;">
                            <span style="font-size:10px; padding:2px 8px; background:#e2e8f0; color:#475569; border-radius:9999px; font-weight:700; text-transform:uppercase; white-space:nowrap;">
                                ${feature.type}
                            </span>
                            <span style="font-size:10px; padding:2px 8px; background:${statusBg}; color:#fff; border-radius:9999px; font-weight:700; text-transform:uppercase; white-space:nowrap;">
                                ${feature.status}
                            </span>
                        </div>
                    `;

                    li.addEventListener('click', () => {
                        if (feature.type === 'Marker' || feature.type === 'Circle') {
                            map.flyTo(feature.layer.getLatLng(), 16, { animate: true, duration: 1.5 });
                        } else {
                            map.fitBounds(feature.layer.getBounds(), { animate: true, duration: 1.5 });
                        }
                        feature.layer.openPopup();
                        searchResults.classList.add('hidden');
                        searchInput.value = feature.title;
                    });

                    searchResults.appendChild(li);
                });
            } else {
                searchResults.classList.remove('hidden');
                searchResults.innerHTML = '<li style="padding:12px 16px; text-align:center; color:#94a3b8; font-size:13px;">Lokasi tidak ditemukan</li>';
            }
        }

        searchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') { e.preventDefault(); performSearch(); }
        });

        searchBtn.addEventListener('click', performSearch);

        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // 7. Filter Berdasarkan Status & Tipe
        const filterToggleBtn  = document.getElementById('filterToggleBtn');
        const filterDropdown   = document.getElementById('filterDropdown');
        const filterDot        = document.getElementById('filterDot');
        const statusFilterItems = document.querySelectorAll('#statusFilterList li');
        const typeFilterItems   = document.querySelectorAll('#typeFilterList li');
        let   activeStatusFilter = 'all';
        let   activeTypeFilter   = 'all';

        function applyFilters() {
            searchableFeatures.forEach(feature => {
                const statusMatch = activeStatusFilter === 'all' || feature.status === activeStatusFilter;
                const typeMatch   = activeTypeFilter   === 'all' || feature.subtype === activeTypeFilter;

                if (statusMatch && typeMatch) {
                    if (!map.hasLayer(feature.layer)) feature.layer.addTo(map);
                } else {
                    if (map.hasLayer(feature.layer)) map.removeLayer(feature.layer);
                }
            });

            // Dot indicator: aktif jika salah satu filter bukan 'all'
            if (activeStatusFilter !== 'all' || activeTypeFilter !== 'all') {
                filterDot.classList.remove('hidden');
            } else {
                filterDot.classList.add('hidden');
            }
        }

        filterToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
            searchResults.classList.add('hidden');
        });

        statusFilterItems.forEach(item => {
            item.addEventListener('click', () => {
                statusFilterItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                activeStatusFilter = item.getAttribute('data-value');
                applyFilters();
                filterDropdown.classList.add('hidden');
            });
        });

        typeFilterItems.forEach(item => {
            item.addEventListener('click', () => {
                typeFilterItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                activeTypeFilter = item.getAttribute('data-value');
                applyFilters();
                filterDropdown.classList.add('hidden');
            });
        });

        // Close filter dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!filterToggleBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });

        // 8. Kontrol Jenis Peta (Base Map)
        const baseMapFilterItems = document.querySelectorAll('#baseMapFilterList li');

        baseMapFilterItems.forEach(item => {
            item.addEventListener('click', () => {
                baseMapFilterItems.forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('input').checked = false;
                });
                item.classList.add('active');
                item.querySelector('input').checked = true;

                const val = item.getAttribute('data-value');
                if (val === 'standard') {
                    if (map.hasLayer(topoMap)) map.removeLayer(topoMap);
                    if (!map.hasLayer(streetMap)) map.addLayer(streetMap);
                } else {
                    if (map.hasLayer(streetMap)) map.removeLayer(streetMap);
                    if (!map.hasLayer(topoMap)) map.addLayer(topoMap);
                }
            });
        });

        // 9. Kontrol Layer Tambahan (Overlays)
        const overlayFilterItems = document.querySelectorAll('#overlayFilterList li');

        overlayFilterItems.forEach(item => {
            item.addEventListener('click', () => {
                const isActive = item.classList.toggle('active');
                item.querySelector('input').checked = isActive;

                const val = item.getAttribute('data-value');
                let targetLayer;
                if (val === 'rain')  targetLayer = rainLayer;
                if (val === 'aqi')   targetLayer = aqiLayer;
                if (val === 'land')  targetLayer = landLayer;

                if (targetLayer) {
                    if (isActive) {
                        if (!map.hasLayer(targetLayer)) map.addLayer(targetLayer);
                    } else {
                        if (map.hasLayer(targetLayer)) map.removeLayer(targetLayer);
                    }
                }
            });
        });
    </script>

</body>
</html>