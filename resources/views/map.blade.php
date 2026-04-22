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
                    <p>Filter Status</p>
                    <ul>
                        <li data-value="all" class="active">Semua</li>
                        <li data-value="green">🟢 Sangat Terjaga</li>
                        <li data-value="yellow">🟡 Terjaga</li>
                        <li data-value="red">🔴 Perlu Perhatian</li>
                    </ul>
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

        const topoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenTopoMap (CC-BY-SA)'
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
        const baseMaps    = { "Peta Standar": streetMap, "Peta Topografi (Gunung/Hutan)": topoMap };
        const overlayMaps = { "Layer Cuaca & Curah Hujan": rainLayer, "Layer Kualitas Udara (AQI)": aqiLayer };
        L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

        // 5. Ambil Data Cuaca RainViewer
        fetch('https://api.rainviewer.com/public/weather-maps.json')
            .then(r => r.json())
            .then(data => {
                const path = data.radar.past[data.radar.past.length - 1].path;
                rainLayer.setUrl(`https://tilecache.rainviewer.com${path}/256/{z}/{x}/{y}/2/1_1.png`);
            })
            .catch(err => console.error('Gagal mengambil data cuaca:', err));

        // 6. Render Markers dari Database
        let searchableFeatures = [];

        function buildPopupWithWeather(lat, lng, layerObj, item, color) {
            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
                .then(r => r.json())
                .then(data => {
                    const temp = data.current_weather.temperature;
                    const wind = data.current_weather.windspeed;
                    layerObj.bindPopup(popupHTML(item, color, `Suhu: ${temp}°C | Angin: ${wind} km/j`));
                })
                .catch(() => {
                    layerObj.bindPopup(popupHTML(item, color, null));
                });
        }

        function popupHTML(item, color, weatherLine) {
            return `
                <div style="padding:8px; min-width:180px;">
                    <h3 style="margin:0 0 4px 0; font-size:14px; font-weight:800; color:#1e293b;">${item.title || '(Tanpa Judul)'}</h3>
                    <p style="margin:0 0 8px 0; font-size:11px; color:#64748b;">${item.description || ''}</p>
                    ${weatherLine ? `<div style="padding-top:8px; border-top:1px solid #e2e8f0; font-size:11px; color:#0ea5e9; font-weight:bold;">${weatherLine}</div>` : ''}
                    ${isAdmin ? `<a href="/admin/markers/${item.id}/edit" style="display:inline-block; margin-top:6px; margin-right:4px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#3b82f6; color:#fff; text-decoration:none;">Edit</a>` : ''}
                    <a href="/markers/${item.id}" style="display:inline-block; margin-top:6px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#64748b; color:#fff; text-decoration:none;">Detail Info</a>
                    <span style="display:inline-block; margin-top:6px; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:${color}; color:#fff;">${item.status.toUpperCase()}</span>
                </div>`;
        }

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

            if (shapeType === 'Marker') {
                const customIcon = L.divIcon({
                    className: '',
                    html: `<div class="custom-marker-wrapper"><div class="custom-marker" style="background-color:${color};"></div></div>`,
                    iconSize: [34, 40],
                    iconAnchor: [17, 38],
                });
                layer = L.marker([coordinates[0], coordinates[1]], { icon: customIcon }).addTo(map);
                buildPopupWithWeather(coordinates[0], coordinates[1], layer, item, color);

            } else if (shapeType === 'Circle') {
                layer = L.circle([coordinates[0], coordinates[1]], {
                    radius: item.radius || 500,
                    color, fillColor: color, fillOpacity: 0.25, weight: 2
                }).addTo(map);
                buildPopupWithWeather(coordinates[0], coordinates[1], layer, item, color);

            } else if (shapeType === 'Polygon' || shapeType === 'Rectangle') {
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polygon(latlngs, { color, fillColor: color, fillOpacity: 0.25, weight: 2 }).addTo(map);
                const center = layer.getBounds().getCenter();
                buildPopupWithWeather(center.lat, center.lng, layer, item, color);

            } else if (shapeType === 'Line' || shapeType === 'Polyline') {
                const latlngs = coordinates.map(c => [c[0], c[1]]);
                layer = L.polyline(latlngs, { color, weight: 3 }).addTo(map);
                const midIdx = Math.floor(latlngs.length / 2);
                buildPopupWithWeather(latlngs[midIdx][0], latlngs[midIdx][1], layer, item, color);
            }

            if (layer) {
                searchableFeatures.push({
                    layer,
                    title:       item.title || '(Tanpa Judul)',
                    description: item.description || '',
                    status:      item.status || 'green',
                    type:        shapeType,
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

        // 7. Filter Berdasarkan Status
        const filterToggleBtn  = document.getElementById('filterToggleBtn');
        const filterDropdown   = document.getElementById('filterDropdown');
        const filterDot        = document.getElementById('filterDot');
        const filterItems      = filterDropdown.querySelectorAll('li');
        let   activeFilter     = 'all';

        filterToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
            // Close search results if open
            searchResults.classList.add('hidden');
        });

        filterItems.forEach(item => {
            item.addEventListener('click', () => {
                filterItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                activeFilter = item.getAttribute('data-value');

                // Show/hide dot indicator
                if (activeFilter !== 'all') {
                    filterDot.classList.remove('hidden');
                } else {
                    filterDot.classList.add('hidden');
                }

                // Apply filter on searchableFeatures
                searchableFeatures.forEach(feature => {
                    if (activeFilter === 'all' || feature.status === activeFilter) {
                        if (!map.hasLayer(feature.layer)) {
                            feature.layer.addTo(map);
                        }
                    } else {
                        if (map.hasLayer(feature.layer)) {
                            map.removeLayer(feature.layer);
                        }
                    }
                });

                filterDropdown.classList.add('hidden');
            });
        });

        // Close filter dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!filterToggleBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>