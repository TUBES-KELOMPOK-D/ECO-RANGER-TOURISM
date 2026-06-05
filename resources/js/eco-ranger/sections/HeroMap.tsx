import React, { useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup, Polygon, Polyline, Circle, Rectangle, GeoJSON } from 'react-leaflet';
import { motion, AnimatePresence } from 'framer-motion';
import { Search, Leaf, TrendingUp, Users, Map, Filter, MapPin, Thermometer, Wind } from 'lucide-react';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Navbar from '../components/Navbar';

// Fix default icon issue with webpack/vite
delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});



const STATUS_COLOR: Record<string, string> = {
  green: '#10B981',
  yellow: '#F59E0B',
  red: '#EF4444',
};

function createEcoIcon(status: string) {
  const color = STATUS_COLOR[status] || '#10B981';
  return L.divIcon({
    className: '',
    html: `
      <div style="
        position: relative;
        width: 36px;
        height: 36px;
        filter: drop-shadow(0 4px 8px ${color}80);
      ">
        <div style="
          width: 32px;
          height: 32px;
          border-radius: 50% 50% 50% 0;
          background: ${color};
          border: 3px solid white;
          transform: rotate(-45deg);
          box-shadow: 0 2px 8px ${color}60;
        "></div>
        <div style="
          width: 10px;
          height: 10px;
          background: white;
          border-radius: 50%;
          position: absolute;
          top: 50%;
          left: 48%;
          transform: translate(-50%, -50%);
        "></div>
      </div>
    `,
    iconSize: [36, 36],
    iconAnchor: [18, 32],
    popupAnchor: [0, -34],
  });
}

const BASE_MAP_LAYERS = [
  { id: 'osm', name: 'OpenStreetMap', url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', attr: '&copy; OpenStreetMap contributors' },
  { id: 'satelit', name: 'Satelit', url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', attr: 'Tiles &copy; Esri' }
];

const OVERLAY_LAYERS = [
  { id: 'aqi', name: 'Kualitas Udara (AQI)', url: 'https://tiles.waqi.info/tiles/usepa-aqi/{z}/{x}/{y}.png?token=bf5b26580b95f69c3c26391ca4a96dd03ffe78c0', attr: '&copy; WAQI' },
  { id: 'indonesia-land', name: 'Batas Wilayah Indonesia', url: '/geojson/indonesia-land.geojson', attr: '' }
];

const WeatherWidget = ({ lat, lng }: { lat: number, lng: number }) => {
  const [weather, setWeather] = React.useState({ temp: '...', wind: '...' });
  React.useEffect(() => {
    let isMounted = true;
    fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
      .then(res => res.json())
      .then(data => {
        if (isMounted && data.current_weather) {
          setWeather({
            temp: data.current_weather.temperature.toFixed(1),
            wind: data.current_weather.windspeed.toFixed(1)
          });
        }
      })
      .catch(() => {});
    return () => { isMounted = false; };
  }, [lat, lng]);

  return (
    <div className="bg-[#EFF6FF] border border-[#BFDBFE]/30 rounded-xl px-3.5 py-2.5 flex items-center gap-4 text-xs font-semibold text-[#2563EB] mb-4">
      <span className="flex items-center gap-1.5">
        <Thermometer size={14} className="stroke-[2.5] text-[#3B82F6]" />
        <span>{weather.temp}°C</span>
      </span>
      <span className="text-[#BFDBFE] font-normal">|</span>
      <span className="flex items-center gap-1.5">
        <Wind size={14} className="stroke-[2.5] text-[#3B82F6]" />
        <span>{weather.wind} km/j</span>
      </span>
    </div>
  );
};

export default function HeroMap() {
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState<any[]>([]);
  const [activeLayer, setActiveLayer] = useState(BASE_MAP_LAYERS[0]);
  const [availableOverlays, setAvailableOverlays] = useState(OVERLAY_LAYERS);
  const [activeOverlays, setActiveOverlays] = useState<Set<string>>(new Set());
  const [geoJsonData, setGeoJsonData] = useState<any>(null);
  const [showLayerMenu, setShowLayerMenu] = useState(false);
  const mapRef = React.useRef<L.Map>(null);

  React.useEffect(() => {
    if (activeOverlays.has('indonesia-land') && !geoJsonData) {
      fetch('/geojson/indonesia-land.geojson')
        .then(res => res.json())
        .then(data => setGeoJsonData(data))
        .catch(err => console.error("Error fetching geojson:", err));
    }
  }, [activeOverlays, geoJsonData]);

  React.useEffect(() => {
    fetch('https://api.rainviewer.com/public/weather-maps.json')
      .then(res => res.json())
      .then(data => {
        if (data.radar && data.radar.past && data.radar.past.length > 0) {
          const latest = data.radar.past[data.radar.past.length - 1].path;
          const rvUrl = `https://tilecache.rainviewer.com${latest}/256/{z}/{x}/{y}/2/1_1.png`;
          setAvailableOverlays(prev => {
            if (prev.some(l => l.id === 'rainviewer')) return prev;
            return [
              ...prev,
              { id: 'rainviewer', name: 'Cuaca (RainViewer)', url: rvUrl, attr: 'Weather data &copy; <a href="https://rainviewer.com">RainViewer</a>' }
            ];
          });
        }
      })
      .catch(err => console.error("Error fetching RainViewer API:", err));
  }, []);
  
  // Ambil data real dari Laravel yang dilempar ke window.appData
  const markers = window.appData?.markers || [];

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const val = e.target.value;
    setSearchQuery(val);
    if (!val.trim()) {
      setSearchResults([]);
      return;
    }
    const query = val.toLowerCase();
    const results = markers.filter((marker: any) => 
      marker.title?.toLowerCase().includes(query) || 
      marker.description?.toLowerCase().includes(query)
    );
    setSearchResults(results);
  };

  const handleSelectResult = (marker: any) => {
    if (marker.coordinates && marker.coordinates.length === 2 && mapRef.current) {
      mapRef.current.flyTo([marker.coordinates[0], marker.coordinates[1]], 14, {
        duration: 1.5
      });
      setSearchQuery('');
      setSearchResults([]);
    }
  };

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchResults.length > 0) {
      handleSelectResult(searchResults[0]);
    }
  };

  return (
    <section
      id="beranda"
      className="relative h-screen w-full overflow-hidden"
      aria-label="Peta Interaktif Eco Ranger"
    >
      {/* ── Full-screen Leaflet Map ── */}
      <div className="absolute inset-0 z-0">
        <MapContainer
          ref={mapRef}
          center={[-2.5, 118.0]}
          zoom={5}
          minZoom={5}
          maxBounds={[
            [-11.0, 94.0], // South-West
            [6.0, 141.0]   // North-East
          ]}
          maxBoundsViscosity={1.0}
          zoomControl={false}
          scrollWheelZoom={true}
          style={{ width: '100%', height: '100%' }}
          attributionControl={true}
        >
          <TileLayer url={activeLayer.url} attribution={activeLayer.attr} zIndex={1} />
          {availableOverlays.filter(layer => activeOverlays.has(layer.id)).map((layer, idx) => {
             if (layer.id === 'indonesia-land') {
               return geoJsonData ? (
                 <GeoJSON
                   key={layer.id}
                   data={geoJsonData}
                   interactive={false}
                   style={{
                     color: '#061810ff',
                     weight: 2,
                     fillColor: '#020c09ff',
                     fillOpacity: 0.1,
                     dashArray: '5, 10'
                   }}
                 />
               ) : null;
             }
             return <TileLayer key={layer.id} url={layer.url!} attribution={layer.attr} zIndex={2 + idx} opacity={0.7} />;
          })}

          {markers.map((marker: any) => {
             const shapeType = marker.shape_type || 'Marker';
             const color = STATUS_COLOR[marker.status] || '#10B981';
             
             // Extract coordinates safely
             let lat = -2.5, lng = 118.0;
             if (marker.coordinates && Array.isArray(marker.coordinates)) {
               if (typeof marker.coordinates[0] === 'number') {
                 lat = marker.coordinates[0]; lng = marker.coordinates[1];
               } else if (Array.isArray(marker.coordinates[0])) {
                 lat = marker.coordinates[0][0]; lng = marker.coordinates[0][1];
               }
             }
             
             let categoryLabel = 'DESTINASI WISATA';
             if (shapeType === 'Polygon' || shapeType === 'Rectangle') {
               categoryLabel = 'WILAYAH KONSERVASI';
             } else if (shapeType === 'Line' || shapeType === 'Polyline') {
               categoryLabel = 'JALUR ECO-TOURISM';
             }

             const popupContent = (
               <Popup maxWidth={320} className="custom-eco-popup">
                 <div className="p-5 flex flex-col font-sans">
                   {/* Category Pill */}
                   <div className="flex">
                     <div className="inline-flex items-center gap-1.5 bg-[#E6F4EA] text-[#137333] text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide">
                       <MapPin size={10} className="stroke-[2.5]" />
                       <span>{categoryLabel}</span>
                     </div>
                   </div>

                   {/* Title */}
                   <h3 className="font-extrabold text-[#111827] text-lg leading-tight mt-3 mb-1.5">
                     {marker.title || '(Tanpa Judul)'}
                   </h3>

                   {/* Location */}
                   <div className="flex items-center gap-1.5 text-[#10B981] font-semibold text-xs mb-3">
                     <MapPin size={12} className="stroke-[2.5]" />
                     <span>{marker.location_name || 'Indonesia'}</span>
                   </div>

                   {/* Eco Score */}
                   <div className="flex items-baseline gap-1.5 mb-3.5">
                     <span className="font-space font-extrabold text-[#064E3B] text-2xl tracking-tight leading-none">
                       {marker.eco_health_score ? Number(marker.eco_health_score).toFixed(1) : '8.0'}
                     </span>
                     <span className="text-[#065F46] font-bold text-[9px] tracking-wider uppercase">
                       Eco Score
                     </span>
                   </div>

                   {/* Description */}
                   <p className="text-gray-600 text-xs leading-relaxed mb-4 line-clamp-3">
                     {marker.description || 'Tidak ada deskripsi yang tersedia untuk lokasi ini.'}
                   </p>

                   {/* Real-time Weather Bar */}
                   <WeatherWidget lat={lat} lng={lng} />

                   {/* Actions */}
                   <div className="flex items-center justify-between gap-3 mt-1">
                     <a
                       href={`/markers/${marker.id}`}
                       className="bg-[#1E293B] hover:bg-[#0F172A] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all hover:scale-[1.02] text-center inline-block cursor-pointer flex-1"
                     >
                       Lihat Detail
                     </a>
                     <span className={`text-[10px] font-bold text-white px-3.5 py-2.5 rounded-xl uppercase tracking-wider text-center flex-1 transition-all ${
                       marker.status === 'green'
                         ? 'bg-[#10B981]'
                         : marker.status === 'yellow'
                         ? 'bg-[#F59E0B]'
                         : 'bg-[#EF4444]'
                     }`}>
                       {marker.status === 'green' ? 'GREEN' : marker.status === 'yellow' ? 'YELLOW' : 'RED'}
                     </span>
                   </div>
                 </div>
               </Popup>
             );

             try {
               if (shapeType === 'Marker' && marker.coordinates && typeof marker.coordinates[0] === 'number') {
                   return (
                      <Marker
                        key={marker.id}
                        position={[marker.coordinates[0], marker.coordinates[1]]}
                        icon={createEcoIcon(marker.status)}
                      >
                        {popupContent}
                      </Marker>
                   );
               } else if (shapeType === 'Circle' && marker.coordinates && typeof marker.coordinates[0] === 'number') {
                   return (
                     <Circle
                       key={marker.id}
                       center={[marker.coordinates[0], marker.coordinates[1]]}
                       radius={marker.radius || 500}
                       pathOptions={{ color, fillColor: color, fillOpacity: 0.4 }}
                     >
                       {popupContent}
                     </Circle>
                   );
               } else if (shapeType === 'Polygon' && marker.coordinates && Array.isArray(marker.coordinates[0])) {
                   return (
                     <Polygon
                       key={marker.id}
                       positions={marker.coordinates}
                       pathOptions={{ color, fillColor: color, fillOpacity: 0.4 }}
                     >
                       {popupContent}
                     </Polygon>
                   );
               } else if ((shapeType === 'Line' || shapeType === 'Polyline') && marker.coordinates && Array.isArray(marker.coordinates[0])) {
                   return (
                     <Polyline
                       key={marker.id}
                       positions={marker.coordinates}
                       pathOptions={{ color, weight: 4 }}
                     >
                       {popupContent}
                     </Polyline>
                   );
               } else if (shapeType === 'Rectangle' && marker.coordinates && Array.isArray(marker.coordinates[0]) && marker.coordinates.length >= 2) {
                   return (
                     <Rectangle
                       key={marker.id}
                       bounds={marker.coordinates}
                       pathOptions={{ color, fillColor: color, fillOpacity: 0.4 }}
                     >
                       {popupContent}
                     </Rectangle>
                   );
               }
             } catch (e) {
               console.error('Error rendering shape', marker, e);
             }
             return null;
          })}
        </MapContainer>
      </div>

      {/* ── Map overlay gradient (top only for navbar) ── */}
      <div
        className="absolute inset-0 z-10 pointer-events-none"
        style={{
          background: 'linear-gradient(to bottom, rgba(248,250,249,0.8) 0%, transparent 15%)',
        }}
      />

      {/* ── Navbar ── */}
      <div className="relative z-30">
        <Navbar />
      </div>

      {/* ── Floating Search Bar (Bottom) ── */}
      <motion.div
        initial={{ y: 30, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ delay: 0.3, duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
        className="absolute bottom-0 left-0 right-0 z-30 px-3 pb-4 sm:px-4 sm:pb-8"
        style={{ paddingBottom: 'max(env(safe-area-inset-bottom, 0px) + 12px, 16px)' }}
      >
        {/* ── Search container with legend inside ── */}
        <div className="w-full max-w-2xl mx-auto">

          {/* Search Results Dropdown */}
          <AnimatePresence>
            {searchResults.length > 0 && (
              <motion.div
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: 10 }}
                className="absolute bottom-[calc(100%+8px)] left-3 right-3 sm:left-4 sm:right-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/50 max-h-52 overflow-y-auto z-50 overflow-hidden"
              >
                {searchResults.map((marker: any) => (
                  <button
                    key={marker.id}
                    onClick={() => handleSelectResult(marker)}
                    className="w-full text-left px-4 py-3 hover:bg-emerald-50/80 border-b border-gray-100 last:border-0 flex items-center justify-between transition-colors group"
                  >
                    <span className="font-semibold text-gray-800 group-hover:text-emerald-700 transition-colors text-sm truncate mr-2">{marker.title || '(Tanpa Judul)'}</span>
                    <div className="flex items-center gap-1.5 flex-shrink-0">
                      <span className="text-[10px] font-bold text-slate-500 bg-slate-200/70 px-2 py-1 rounded-md uppercase tracking-wider hidden sm:inline">
                        {marker.shape_type || 'MARKER'}
                      </span>
                      <span className={`text-[10px] font-bold text-white px-2 py-1 rounded-md uppercase tracking-wider ${
                        marker.status === 'green' ? 'bg-emerald-500' : marker.status === 'yellow' ? 'bg-amber-500' : 'bg-red-500'
                      }`}>
                        {marker.status}
                      </span>
                    </div>
                  </button>
                ))}
              </motion.div>
            )}
          </AnimatePresence>

          {/* ── Search bar ── */}
          <form
            onSubmit={handleSearchSubmit}
            className="glass-panel rounded-2xl p-2 flex gap-2 items-center relative shadow-lg shadow-black/10"
          >
            {/* Layer filter */}
            <div className="relative flex items-center pr-2 border-r border-gray-200">
              <button
                type="button"
                onClick={() => setShowLayerMenu(!showLayerMenu)}
                className="p-2.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded-xl transition-colors"
                aria-label="Pilih Layer Peta"
              >
                <Filter size={18} />
              </button>

              <AnimatePresence>
                {showLayerMenu && (
                  <motion.div
                    initial={{ opacity: 0, y: 10, scale: 0.95 }}
                    animate={{ opacity: 1, y: 0, scale: 1 }}
                    exit={{ opacity: 0, y: 10, scale: 0.95 }}
                    className="absolute bottom-[calc(100%+12px)] left-0 w-44 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-100 p-2 z-50 origin-bottom-left"
                  >
                    <div className="text-xs font-bold text-slate-400 px-3 py-2 uppercase tracking-wider border-b border-slate-100">Peta Dasar</div>
                    {BASE_MAP_LAYERS.map(layer => (
                      <button
                        key={layer.id}
                        type="button"
                        onClick={() => setActiveLayer(layer)}
                        className={`w-full text-left px-3 py-2.5 text-sm font-medium transition-colors flex items-center justify-between ${
                          activeLayer.id === layer.id
                            ? 'bg-emerald-50 text-emerald-700'
                            : 'text-gray-600 hover:bg-gray-50'
                        }`}
                      >
                        {layer.name}
                        {activeLayer.id === layer.id && <span className="w-1.5 h-1.5 rounded-full bg-emerald-500" />}
                      </button>
                    ))}

                    <div className="text-xs font-bold text-slate-400 px-3 py-2 mt-1 border-t border-slate-100 uppercase tracking-wider">Overlay</div>
                    {availableOverlays.map(layer => (
                      <label
                        key={layer.id}
                        className={`w-full cursor-pointer text-left px-3 py-2.5 text-sm font-medium transition-colors flex items-center justify-between ${
                          activeOverlays.has(layer.id)
                            ? 'bg-emerald-50 text-emerald-700'
                            : 'text-gray-600 hover:bg-gray-50'
                        }`}
                      >
                        <span className="truncate pr-2">{layer.name}</span>
                        <input
                          type="checkbox"
                          className="rounded text-emerald-500 focus:ring-emerald-500 h-3.5 w-3.5 flex-shrink-0"
                          checked={activeOverlays.has(layer.id)}
                          onChange={(e) => {
                            const next = new Set(activeOverlays);
                            if (e.target.checked) next.add(layer.id);
                            else next.delete(layer.id);
                            setActiveOverlays(next);
                          }}
                        />
                      </label>
                    ))}
                  </motion.div>
                )}
              </AnimatePresence>
            </div>

            {/* Search input */}
            <div className="flex items-center gap-2 flex-1 px-1 min-w-0">
              <input
                id="map-search-input"
                type="text"
                value={searchQuery}
                onChange={handleSearchChange}
                placeholder="Cari destinasi..."
                className="bg-transparent outline-none border-none text-sm text-[#1F2937] placeholder-gray-400 flex-1 font-medium px-1 min-w-0"
                autoComplete="off"
              />
            </div>

            {/* Submit button — icon only on mobile, text+icon on sm+ */}
            <button
              id="btn-map-search"
              type="submit"
              className="bg-[#064E3B] hover:bg-[#10B981] text-white rounded-xl px-3 py-2.5 sm:px-5 text-sm font-semibold transition-all hover:scale-105 flex items-center gap-2 shadow-md shadow-emerald-900/20 flex-shrink-0"
            >
              <Search size={15} />
              <span className="hidden sm:inline">Cari</span>
            </button>
          </form>

          {/* ── Legend — compact, inside panel area ── */}
          <div className="flex items-center justify-center gap-3 mt-2.5 text-[11px] font-medium text-slate-600 bg-white/70 backdrop-blur-md py-1.5 px-4 rounded-full w-max mx-auto shadow-sm border border-white/40">
            <div className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full bg-[#10b981] flex-shrink-0" /> Sangat Terjaga</div>
            <div className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full bg-[#f59e0b] flex-shrink-0" /> Terjaga</div>
            <div className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full bg-[#ef4444] flex-shrink-0" /> Perlu Perhatian</div>
          </div>
        </div>
      </motion.div>
    </section>
  );
}
