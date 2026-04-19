import React, { useState } from 'react';
import { MapContainer, TileLayer, Marker, useMapEvents } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import { useReportStore } from '../../../Store/useReportStore';
import { MapPin, ArrowLeft } from 'lucide-react';
import L from 'leaflet';

// Fix for default Leaflet marker icon
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

function LocationMarker({ position, setPosition }) {
    useMapEvents({
        click(e) {
            setPosition(e.latlng);
        },
    });

    return position === null ? null : (
        <Marker position={position}></Marker>
    );
}

export default function MapPicker() {
    const { reportData, updateReportData, setStep } = useReportStore();
    const [tempPosition, setTempPosition] = useState(
        reportData.location || { lat: -2.5489, lng: 118.0149 } // Default center of Indonesia
    );
    const [selected, setSelected] = useState(!!reportData.location);

    const handleSave = () => {
        if (selected) {
            updateReportData({ location: tempPosition });
            setStep('form');
        }
    };

    return (
        <div className="w-full h-[600px] flex flex-col relative rounded-2xl overflow-hidden shadow-lg border border-gray-200">
            {/* Header / Top bar overlay */}
            <div className="absolute top-0 left-0 right-0 z-[1000] p-4 flex justify-between items-center bg-gradient-to-b from-black/50 to-transparent">
                <button 
                    onClick={() => setStep('form')}
                    className="bg-white/90 backdrop-blur text-gray-800 p-2 rounded-full hover:bg-white transition"
                >
                    <ArrowLeft size={24} />
                </button>
                <div className="bg-gray-900 text-white px-4 py-2 rounded-full flex items-center gap-2 text-sm font-medium shadow-md">
                    <MapPin size={16} /> Tap peta untuk pilih lokasi
                </div>
            </div>

            <div className="flex-1 w-full bg-gray-100 z-0">
                <MapContainer 
                    center={[tempPosition.lat, tempPosition.lng]} 
                    zoom={5} 
                    style={{ height: '100%', width: '100%' }}
                >
                    <TileLayer
                        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    />
                    <LocationMarker 
                        position={selected ? tempPosition : null} 
                        setPosition={(pos) => {
                            setTempPosition(pos);
                            setSelected(true);
                        }} 
                    />
                </MapContainer>
            </div>

            {/* Bottom action bar */}
            <div className="absolute bottom-0 left-0 right-0 z-[1000] p-6 bg-white rounded-t-3xl shadow-[0_-4px_20px_rgba(0,0,0,0.1)]">
                <div className="flex flex-col gap-4">
                    <div>
                        <h3 className="font-semibold text-gray-800">Lokasi Terpilih</h3>
                        <p className="text-gray-500 text-sm">
                            {selected 
                                ? `${tempPosition.lat.toFixed(5)}, ${tempPosition.lng.toFixed(5)}` 
                                : 'Belum ada lokasi yang dipilih'}
                        </p>
                    </div>
                    <button 
                        onClick={handleSave}
                        disabled={!selected}
                        className={`w-full py-3 rounded-xl font-medium transition text-white shadow-sm flex justify-center items-center gap-2
                            ${selected ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'}`}
                    >
                        ✓ Konfirmasi Lokasi
                    </button>
                </div>
            </div>
        </div>
    );
}
