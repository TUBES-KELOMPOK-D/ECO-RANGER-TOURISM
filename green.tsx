/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

import React, { useState, useEffect } from 'react';
import {
  Home, Map as MapIcon, Camera, Users, Trophy, BookOpen,
  MapPin, Star, AlertTriangle, CheckCircle2, ChevronRight,
  Search, Filter, Calendar, Info, Leaf, Droplets, Mountain,
  ArrowLeft, Share2, Heart, X, Menu, Cloud, Sun, CloudRain, CloudLightning, MessageCircle, Send
} from 'lucide-react';
import { MapContainer, TileLayer, Marker, Popup, Polygon, Polyline, useMap, useMapEvents } from 'react-leaflet';
import L from 'leaflet';

// --- INITIAL MOCK DATA ---
const initialLocations = [
  {
    id: 1,
    name: "Muara Gembong, Jawa Barat",
    lat: -6.01,
    lng: 107.03,
    status: "green",
    score: 8.5,
    img: "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80",
    cleanliness: 4,
    access: "Mudah",
    population: "Sedang",
    description: "Lokasi Penyeimbangan Karbon. Area penanaman mangrove untuk menyerap emisi karbon. Anda bisa berdonasi atau ikut menanam langsung di lokasi."
  },
  {
    id: 2,
    name: "Pusat Rehabilitasi Orangutan Tanjung Puting",
    lat: -2.8500,
    lng: 111.8333,
    status: "green",
    score: 9.5,
    img: "https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=800&q=80",
    cleanliness: 5,
    access: "Sulit",
    population: "Rendah",
    description: "Pemantauan Konservasi Satwa. Pusat rehabilitasi satwa liar yang menerima kunjungan edukatif untuk melindungi habitat asli satwa Indonesia."
  },
  {
    id: 3,
    name: "Taman Nasional Bunaken, Sulawesi Utara",
    lat: 1.62,
    lng: 124.76,
    status: "green",
    score: 9.8,
    img: "https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=800&q=80",
    cleanliness: 5,
    access: "Sedang",
    population: "Rendah",
    description: "Zona Aman Terumbu Karang. Area konservasi laut dengan batas area menyelam yang diizinkan dan ramah karang."
  },
  {
    id: 4,
    name: "Taman Nasional Komodo",
    lat: -8.55,
    lng: 119.48,
    status: "yellow",
    score: 8.2,
    img: "https://images.unsplash.com/photo-1596422846543-75c6fc18a593?auto=format&fit=crop&w=800&q=80",
    cleanliness: 4,
    access: "Sedang",
    population: "Tinggi",
    description: "Rute Cagar Alam Hijau. Jalur ekowisata resmi dengan pemantauan ketat untuk mencegah perusakan habitat naga purba."
  },
  {
    id: 5,
    name: "Pantai Kuta, Bali",
    lat: -8.71,
    lng: 115.16,
    status: "red",
    score: 5.8,
    img: "https://images.unsplash.com/photo-1588392382834-a8af9fce1f66?auto=format&fit=crop&w=800&q=80",
    cleanliness: 2,
    access: "Sangat Mudah",
    population: "Sangat Tinggi",
    description: "Area Pantau: Terdapat laporan tumpukan sampah plastik yang membutuhkan tindakan pembersihan segera."
  },
];

const initialZones = [
  {
    id: 1,
    name: "Rute Cagar Alam Hijau (Tanjung Puting)",
    category: "Konservasi Darat",
    status: "green",
    color: "#10b981",
    positions: [
      [-2.5, 111.5],
      [-2.5, 112.5],
      [-3.5, 112.5],
      [-3.5, 111.5]
    ]
  },
  {
    id: 2,
    name: "Zona Aman Terumbu Karang (Bunaken)",
    category: "Konservasi Laut",
    status: "green",
    color: "#3b82f6",
    positions: [
      [1.5, 124.5],
      [1.5, 125.0],
      [1.8, 125.0],
      [1.8, 124.5]
    ]
  },
  {
    id: 3,
    name: "Area Penyeimbangan Karbon (Muara Gembong)",
    category: "Mangrove",
    status: "yellow",
    color: "#f59e0b",
    positions: [
      [-5.9, 106.9],
      [-5.9, 107.2],
      [-6.2, 107.2],
      [-6.2, 106.9]
    ]
  }
];

const App = () => {
  // --- STATE MANAGEMENT ---
  const [activeTab, setActiveTab] = useState('home');
  const [selectedLocation, setSelectedLocation] = useState(null);
  const [reportStatus, setReportStatus] = useState('idle'); // idle, reporting, success
  const [reportLocation, setReportLocation] = useState<{ lat: number, lng: number } | null>(null);
  const [reportForm, setReportForm] = useState({ title: '', description: '', category: 'Pemutihan Terumbu Karang' });
  const [isSelectingLocation, setIsSelectingLocation] = useState(false);

  // Reports State
  const [reports, setReports] = useState([
    { id: 1, title: 'Sampah Plastik di Pantai', description: 'Banyak sampah plastik berserakan di area pantai Kuta.', category: 'Penumpukan Sampah', location: { lat: -8.718, lng: 115.169 }, status: 'pending', date: '2026-04-01', userId: 'user1' },
    { id: 2, title: 'Terumbu Karang Rusak', description: 'Ada area terumbu karang yang rusak akibat jangkar kapal.', category: 'Pemutihan Terumbu Karang', location: { lat: 1.5, lng: 124.5 }, status: 'verified', date: '2026-04-05', userId: 'user1' }
  ]);

  // Route Planning State
  const [isPlanningRoute, setIsPlanningRoute] = useState(false);
  const [routeStart, setRouteStart] = useState<{ lat: number, lng: number } | null>(null);
  const [routeEnd, setRouteEnd] = useState<{ lat: number, lng: number } | null>(null);
  const [transportMode, setTransportMode] = useState<'car' | 'bus' | 'train' | 'flight'>('car');
  const [selectingRoutePoint, setSelectingRoutePoint] = useState<'start' | 'end' | null>(null);

  const [joinedEvents, setJoinedEvents] = useState<number[]>([]);
  const [activeForumEventId, setActiveForumEventId] = useState<number | null>(null);
  const [forumMessages, setForumMessages] = useState<Record<number, { id: number, userName: string, avatar: string, text: string, timestamp: string, isMe: boolean }[]>>({
    1: [
      { id: 1, userName: "Budi Rejeki", avatar: "BR", text: "Halo semuanya! Ada yang bawa kantong sampah ekstra?", timestamp: "10:00", isMe: false },
      { id: 2, userName: "Siti Aminah", avatar: "SA", text: "Aku bawa 5 kantong besar nih, nanti bisa minta ke aku ya.", timestamp: "10:05", isMe: false }
    ]
  });
  const [newForumMessage, setNewForumMessage] = useState("");
  const [searchQuery, setSearchQuery] = useState("");

  // Profile State
  const [userProfile, setUserProfile] = useState({
    name: "Andi Saputra",
    level: "Eco-Ranger",
    avatar: "AS",
    points: 1240
  });
  const [showLevelGuide, setShowLevelGuide] = useState(false);
  const [showCommunityDetail, setShowCommunityDetail] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  // Date Filtering State
  const [selectedDate, setSelectedDate] = useState("2026-03-17"); // Default to today
  const [showDatePicker, setShowDatePicker] = useState(false);

  // Map Filter State
  const [statusFilter, setStatusFilter] = useState('all');
  const [showFilterDropdown, setShowFilterDropdown] = useState(false);
  const [showAQILayer, setShowAQILayer] = useState(false);
  const [weatherData, setWeatherData] = useState<Record<number, any>>({});

  // Admin State
  const [isAdmin, setIsAdmin] = useState(false);
  const [isAddingLocation, setIsAddingLocation] = useState(false);
  const [isAddingZone, setIsAddingZone] = useState(false);
  const [newLocationCoords, setNewLocationCoords] = useState<{ lat: number, lng: number } | null>(null);
  const [newZonePoints, setNewZonePoints] = useState<[number, number][]>([]);
  const [newLocationForm, setNewLocationForm] = useState({ name: '', description: '', status: 'green' });
  const [newZoneForm, setNewZoneForm] = useState({ name: '', category: '', status: 'green', color: '#10b981' });
  const [locations, setLocations] = useState(initialLocations);
  const [zones, setZones] = useState(initialZones);
  const [editingItem, setEditingItem] = useState<{ type: 'location' | 'zone', id: number } | null>(null);

  // Fetch weather data for all locations
  useEffect(() => {
    locations.forEach(loc => {
      fetch(`https://api.open-meteo.com/v1/forecast?latitude=${loc.lat}&longitude=${loc.lng}&current=temperature_2m,weather_code`)
        .then(res => res.json())
        .then(data => {
          if (data.current) {
            setWeatherData(prev => ({ ...prev, [loc.id]: data.current }));
          }
        })
        .catch(err => console.error("Failed to fetch weather for", loc.name, err));
    });
  }, []);

  // --- UTILS ---
  const calculateDistance = (lat1: number, lon1: number, lat2: number, lon2: number) => {
    const R = 6371; // Radius of the earth in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d = R * c; // Distance in km
    return d;
  };

  const calculateEmissions = (distance: number, mode: string) => {
    // kg CO2 per km
    const rates = {
      car: 0.192,
      bus: 0.089,
      train: 0.041,
      flight: 0.255
    };
    return distance * (rates[mode as keyof typeof rates] || 0);
  };

  // --- MOCK DATA ---
  const allEvents = [
    { id: 1, title: "Misi Bersih Babakan Siliwangi 🌊", location: "Babakan Siliwangi", date: "2026-03-17", displayDate: "17 Mar, 08:00", participants: 124, points: 50, img: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80" },
    { id: 2, title: "Pahlawan Tahura: Tanam Pohon! 🌱", location: "Tahura Juanda", date: "2026-03-18", displayDate: "18 Mar, 09:00", participants: 85, points: 100, img: "https://images.unsplash.com/photo-1584559582128-b8be739912e1?auto=format&fit=crop&w=600&q=80" },
    { id: 3, title: "Workshop Kompos Cikapundung 🪸", location: "Teras Cikapundung", date: "2026-03-20", displayDate: "20 Mar, 10:00", participants: 45, points: 75, img: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80" },
  ];

  const filteredEvents = selectedDate === "all" ? allEvents : allEvents.filter(event => event.date === selectedDate);

  const [leaderboardData, setLeaderboardData] = useState([
    { id: 1, name: "Andi Saputra", points: 2450, level: "Eco-Ranger", avatar: "AS" },
    { id: 2, name: "Siti Aminah", points: 2120, level: "Eco-Warrior", avatar: "SA" },
    { id: 3, name: "Budi Rejeki", points: 1980, level: "Eco-Warrior", avatar: "BR" },
    { id: 4, name: "Rina Nose", points: 1540, level: "Seedling", avatar: "RN" },
  ]);

  const [pointRules, setPointRules] = useState([
    { id: 1, action: "Lapor Isu Lingkungan", points: 10 },
    { id: 2, action: "Ikut Aksi Komunitas", points: 50 },
    { id: 3, action: "Verifikasi Laporan", points: 5 }
  ]);

  const [badges, setBadges] = useState([
    { id: 1, name: "Plastic Hunter", description: "Lapor 10 tumpukan sampah", target: 10, icon: "Trophy" },
    { id: 2, name: "Tree Hugger", description: "Ikut 5 aksi tanam pohon", target: 5, icon: "Leaf" }
  ]);

  const [ecoLevels, setEcoLevels] = useState([
    { id: 1, name: "Seedling", minPoints: 0, description: "Pemula yang baru memulai perjalanan hijau.", icon: "🌱" },
    { id: 2, name: "Eco-Warrior", minPoints: 501, description: "Aktif dalam aksi bersih-bersih dan pelaporan.", icon: "⚔️" },
    { id: 3, name: "Eco-Ranger", minPoints: 1501, description: "Pemimpin aksi komunitas dan kontributor rutin.", icon: "🛡️" },
    { id: 4, name: "Nature Guardian", minPoints: 3000, description: "Penjaga alam sejati dengan dampak luas.", icon: "👑" },
    { id: 5, name: "Earth Hero", minPoints: 5000, description: "Pahlawan bumi yang menginspirasi banyak orang.", icon: "🌍" }
  ]);

  const [academyModules, setAcademyModules] = useState([
    {
      id: 1,
      title: "Dasar Pengelolaan Sampah",
      description: "Pelajari cara memilah sampah organik dan anorganik.",
      content: "Sampah organik adalah sampah yang dapat terurai secara alami, seperti sisa makanan dan dedaunan. Sampah anorganik adalah sampah yang sulit terurai, seperti plastik, kaca, dan logam. Memilah sampah dari rumah adalah langkah pertama untuk daur ulang yang efektif.",
      points: 20,
      completed: true,
      quiz: [
        {
          question: "Manakah yang termasuk sampah organik?",
          options: ["Plastik", "Kaca", "Sisa Makanan", "Besi"],
          answer: 2
        }
      ]
    },
    {
      id: 2,
      title: "Dampak Plastik Sekali Pakai",
      description: "Mengapa kita harus mengurangi penggunaan plastik sekali pakai?",
      content: "Plastik sekali pakai membutuhkan waktu ratusan tahun untuk terurai. Sebagian besar berakhir di lautan dan membahayakan biota laut. Mikroplastik bahkan telah masuk ke dalam rantai makanan manusia.",
      points: 30,
      completed: false,
      quiz: [
        {
          question: "Berapa lama waktu yang dibutuhkan plastik untuk terurai?",
          options: ["1-5 tahun", "10-20 tahun", "Ratusan tahun", "Tidak bisa terurai sama sekali"],
          answer: 2
        }
      ]
    },
    {
      id: 3,
      title: "Perubahan Iklim 101",
      description: "Memahami penyebab dan dampak perubahan iklim global.",
      content: "Perubahan iklim disebabkan oleh peningkatan gas rumah kaca di atmosfer, terutama dari pembakaran bahan bakar fosil. Dampaknya meliputi kenaikan suhu global, cuaca ekstrem, dan mencairnya es di kutub.",
      points: 50,
      completed: false,
      quiz: [
        {
          question: "Apa penyebab utama peningkatan gas rumah kaca?",
          options: ["Penanaman pohon", "Pembakaran bahan bakar fosil", "Penggunaan energi surya", "Daur ulang sampah"],
          answer: 1
        }
      ]
    }
  ]);

  const [activeModuleId, setActiveModuleId] = useState<number | null>(null);
  const [quizState, setQuizState] = useState<'idle' | 'playing' | 'result'>('idle');
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [quizScore, setQuizScore] = useState(0);
  const [selectedAnswer, setSelectedAnswer] = useState<number | null>(null);

  // --- HANDLERS ---
  const handleLocationClick = (loc: any) => {
    setSelectedLocation(loc);
    setActiveTab('detail');
  };

  const handleJoinEvent = (eventId: number) => {
    setJoinedEvents(prev =>
      prev.includes(eventId) ? prev.filter(id => id !== eventId) : [...prev, eventId]
    );
  };

  const handleReportSubmit = () => {
    if (!reportLocation || !reportForm.title || !reportForm.description) {
      alert('Mohon lengkapi semua data laporan dan lokasi.');
      return;
    }

    setReportStatus('reporting');

    setTimeout(() => {
      setReports(prev => [{
        id: Date.now(),
        title: reportForm.title,
        description: reportForm.description,
        category: reportForm.category,
        location: reportLocation,
        status: 'pending',
        date: new Date().toISOString().split('T')[0],
        userId: 'user1'
      }, ...prev]);

      setReportStatus('success');
      setReportForm({ title: '', description: '', category: 'Pemutihan Terumbu Karang' });
      setReportLocation(null);
    }, 1500);
  };

  const handleUpdateProfile = (e: React.FormEvent) => {
    e.preventDefault();
    // Profile update logic here
  };

  const handleSendMessage = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newForumMessage.trim() || activeForumEventId === null) return;

    const newMessage = {
      id: Date.now(),
      userName: userProfile.name,
      avatar: userProfile.avatar,
      text: newForumMessage,
      timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
      isMe: true
    };

    setForumMessages(prev => ({
      ...prev,
      [activeForumEventId]: [...(prev[activeForumEventId] || []), newMessage]
    }));
    setNewForumMessage("");
  };

  // --- SUB-COMPONENTS ---

  const ProfileTab = () => {
    const userJoinedEvents = allEvents.filter(e => joinedEvents.includes(e.id));
    const userReports = reports.filter(r => r.userId === 'user1');

    return (
      <div className="h-full overflow-y-auto scrollbar-hide bg-slate-50">
        <div className="p-6 md:p-8 space-y-8 max-w-3xl mx-auto">
          <div className="flex items-center justify-between">
            <h2 className="text-3xl font-black text-slate-800 tracking-tight">Profil Saya 👤</h2>
          </div>

          {/* Points Summary */}
          <div className="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[32px] p-8 text-white shadow-xl shadow-emerald-200">
            <div className="flex items-center justify-between mb-6">
              <div>
                <p className="text-emerald-100 font-bold mb-1">Total Poin Eco</p>
                <h3 className="text-4xl font-black">{userProfile.points}</h3>
              </div>
              <div className="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <Trophy size={32} className="text-white" />
              </div>
            </div>
            <div className="bg-white/20 rounded-2xl p-4 backdrop-blur-sm">
              <div className="flex justify-between items-center mb-2">
                <span className="font-bold text-sm">Level Saat Ini</span>
                <span className="font-black">{userProfile.level}</span>
              </div>
              <div className="w-full bg-black/20 rounded-full h-2">
                <div className="bg-white h-2 rounded-full" style={{ width: '75%' }}></div>
              </div>
              <p className="text-xs text-emerald-100 mt-2 text-right">260 poin lagi ke level berikutnya</p>
            </div>
          </div>

          {/* Joined Events */}
          <div>
            <h3 className="text-xl font-black text-slate-800 mb-4">Aksi yang Diikuti 🌿</h3>
            {userJoinedEvents.length > 0 ? (
              <div className="space-y-4">
                {userJoinedEvents.map(event => (
                  <div key={event.id} className="bg-white p-4 rounded-3xl flex gap-4 items-center shadow-sm border border-slate-100">
                    <img src={event.img} alt={event.title} className="w-20 h-20 rounded-2xl object-cover" />
                    <div className="flex-1">
                      <h4 className="font-bold text-slate-800 text-sm mb-1">{event.title}</h4>
                      <div className="flex items-center gap-2 text-xs text-slate-500 mb-2">
                        <Calendar size={12} /> {event.displayDate}
                      </div>
                      <div className="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-lg text-[10px] font-bold">
                        <Star size={10} /> +{event.points} Poin
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="bg-white p-8 rounded-3xl text-center border border-slate-100 border-dashed">
                <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                  <Leaf size={24} />
                </div>
                <p className="text-slate-500 font-medium text-sm">Belum ada aksi yang diikuti.</p>
                <button onClick={() => setActiveTab('community')} className="mt-4 text-emerald-600 font-bold text-sm">Cari Aksi Sekarang</button>
              </div>
            )}
          </div>

          {/* User Reports */}
          <div>
            <h3 className="text-xl font-black text-slate-800 mb-4">Laporan Saya 📸</h3>
            {userReports.length > 0 ? (
              <div className="space-y-4">
                {userReports.map(report => (
                  <div key={report.id} className="bg-white p-5 rounded-3xl shadow-sm border border-slate-100">
                    <div className="flex justify-between items-start mb-2">
                      <h4 className="font-bold text-slate-800 text-sm">{report.title}</h4>
                      <span className={`px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ${report.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                          report.status === 'verified' ? 'bg-blue-100 text-blue-700' :
                            report.status === 'resolved' ? 'bg-emerald-100 text-emerald-700' :
                              'bg-rose-100 text-rose-700'
                        }`}>
                        {report.status === 'pending' ? 'Menunggu' :
                          report.status === 'verified' ? 'Diverifikasi' :
                            report.status === 'resolved' ? 'Selesai' : 'Ditolak'}
                      </span>
                    </div>
                    <p className="text-xs text-slate-500 mb-3">{report.description}</p>
                    <div className="flex items-center gap-4 text-[10px] font-bold text-slate-400">
                      <div className="flex items-center gap-1"><Calendar size={12} /> {report.date}</div>
                      <div className="flex items-center gap-1"><MapPin size={12} /> {report.location.lat.toFixed(3)}, {report.location.lng.toFixed(3)}</div>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="bg-white p-8 rounded-3xl text-center border border-slate-100 border-dashed">
                <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                  <Camera size={24} />
                </div>
                <p className="text-slate-500 font-medium text-sm">Belum ada laporan yang dikirim.</p>
                <button onClick={() => setActiveTab('report')} className="mt-4 text-emerald-600 font-bold text-sm">Lapor Isu Sekarang</button>
              </div>
            )}
          </div>

          {/* Edit Profile Form */}
          <div className="bg-white rounded-[32px] p-6 md:p-8 shadow-sm border border-slate-100">
            <h3 className="text-xl font-black text-slate-800 mb-6">Pengaturan Profil ⚙️</h3>
            <form onSubmit={handleUpdateProfile} className="space-y-6">
              <div className="flex flex-col items-center mb-6">
                <div className="w-24 h-24 bg-emerald-100 rounded-[32px] flex items-center justify-center text-emerald-700 text-3xl font-black border-4 border-emerald-500 mb-4">
                  {userProfile.avatar}
                </div>
                <button type="button" className="text-emerald-600 font-bold text-sm">Ganti Foto</button>
              </div>

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                <input
                  type="text"
                  value={userProfile.name}
                  onChange={(e) => setUserProfile({ ...userProfile, name: e.target.value, avatar: e.target.value.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) })}
                  className="w-full p-5 bg-slate-50 rounded-3xl border border-slate-100 text-sm font-bold outline-none focus:border-emerald-500 transition-colors"
                />
              </div>

              <div className="space-y-2">
                <div className="flex justify-between items-center px-1">
                  <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Level Eco</label>
                  <button
                    type="button"
                    onClick={() => setShowLevelGuide(!showLevelGuide)}
                    className="text-[10px] font-bold text-emerald-600 flex items-center gap-1"
                  >
                    <Info size={12} /> Lihat Panduan
                  </button>
                </div>
                <select
                  value={userProfile.level}
                  onChange={(e) => setUserProfile({ ...userProfile, level: e.target.value })}
                  className="w-full p-5 bg-slate-50 rounded-3xl border border-slate-100 text-sm font-bold outline-none appearance-none"
                >
                  {[...ecoLevels].sort((a, b) => a.minPoints - b.minPoints).map(level => (
                    <option key={level.id} value={level.name}>{level.name}</option>
                  ))}
                </select>
              </div>

              {showLevelGuide && (
                <div className="p-6 bg-emerald-50 rounded-3xl border border-emerald-100 animate-in slide-in-from-top-2 duration-300">
                  <h4 className="text-xs font-black text-emerald-800 uppercase mb-4 tracking-wider">Panduan Level Eco 📖</h4>
                  <div className="space-y-4">
                    {[...ecoLevels].sort((a, b) => a.minPoints - b.minPoints).map(level => (
                      <div key={level.id} className="flex gap-3">
                        <div className="w-8 h-8 bg-emerald-200 rounded-lg flex items-center justify-center text-emerald-700 shrink-0">{level.icon}</div>
                        <div>
                          <p className="text-xs font-bold text-emerald-900">{level.name}</p>
                          <p className="text-[10px] text-emerald-700 font-medium">{level.description} (Min. {level.minPoints} Poin)</p>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              <div className="pt-4 border-t border-slate-100">
                <div className="flex items-center justify-between">
                  <div>
                    <h4 className="text-sm font-bold text-slate-800">Mode Admin</h4>
                    <p className="text-[10px] text-slate-500">Aktifkan untuk menambah marker lokasi baru (hanya tersimpan lokal).</p>
                  </div>
                  <button
                    type="button"
                    onClick={() => setIsAdmin(!isAdmin)}
                    className={`w-12 h-6 rounded-full transition-colors relative ${isAdmin ? 'bg-emerald-500' : 'bg-slate-200'}`}
                  >
                    <div className={`absolute top-1 w-4 h-4 rounded-full bg-white transition-all ${isAdmin ? 'left-7' : 'left-1'}`}></div>
                  </button>
                </div>
              </div>

              <button
                type="submit"
                className="w-full bg-emerald-600 text-white py-5 rounded-[24px] font-bold text-lg shadow-xl shadow-emerald-100 active:scale-95 transition-all"
              >
                Simpan Perubahan
              </button>
            </form>
          </div>
        </div>
      </div>
    );
  };

  const ForumModal = () => {
    const event = allEvents.find(e => e.id === activeForumEventId);
    if (!event) return null;

    const messages = forumMessages[event.id] || [];

    return (
      <div className="absolute inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-end animate-in fade-in duration-300">
        <div className="w-full h-[90%] bg-slate-50 rounded-t-[40px] flex flex-col overflow-hidden animate-in slide-in-from-bottom duration-500">
          {/* Header */}
          <div className="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between shadow-sm z-10">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600">
                <MessageCircle size={24} />
              </div>
              <div>
                <h3 className="font-black text-slate-800 leading-tight">Forum Diskusi</h3>
                <p className="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">{event.title}</p>
              </div>
            </div>
            <button onClick={() => setActiveForumEventId(null)} className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
              <X size={20} />
            </button>
          </div>

          {/* Messages Area */}
          <div className="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-hide">
            {messages.length === 0 ? (
              <div className="h-full flex flex-col items-center justify-center text-center space-y-4 opacity-50">
                <MessageCircle size={48} className="text-slate-400" />
                <p className="text-sm font-bold text-slate-500">Belum ada pesan.<br />Mulai diskusi sekarang!</p>
              </div>
            ) : (
              messages.map(msg => (
                <div key={msg.id} className={`flex gap-3 ${msg.isMe ? 'flex-row-reverse' : ''}`}>
                  <div className={`w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 ${msg.isMe ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'}`}>
                    {msg.avatar}
                  </div>
                  <div className={`flex flex-col ${msg.isMe ? 'items-end' : 'items-start'} max-w-[75%]`}>
                    <span className="text-[10px] font-bold text-slate-400 mb-1 px-1">{msg.isMe ? 'Kamu' : msg.userName}</span>
                    <div className={`px-4 py-3 rounded-2xl text-sm font-medium shadow-sm ${msg.isMe ? 'bg-emerald-600 text-white rounded-tr-sm' : 'bg-white text-slate-700 rounded-tl-sm border border-slate-100'}`}>
                      {msg.text}
                    </div>
                    <span className="text-[10px] font-bold text-slate-400 mt-1 px-1">{msg.timestamp}</span>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Input Area */}
          <div className="bg-white p-4 border-t border-slate-100">
            <form onSubmit={handleSendMessage} className="flex gap-2">
              <input
                type="text"
                value={newForumMessage}
                onChange={(e) => setNewForumMessage(e.target.value)}
                placeholder="Ketik pesan diskusi..."
                className="flex-1 bg-slate-50 border border-slate-100 rounded-full px-6 py-3 text-sm font-medium outline-none focus:border-emerald-500 transition-colors"
              />
              <button
                type="submit"
                disabled={!newForumMessage.trim()}
                className="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white shadow-md hover:bg-emerald-700 disabled:opacity-50 disabled:hover:bg-emerald-600 transition-all shrink-0"
              >
                <Send size={18} className="ml-1" />
              </button>
            </form>
          </div>
        </div>
      </div>
    );
  };

  const DatePickerModal = () => (
    <div className="absolute inset-0 bg-black/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6 animate-in fade-in duration-300">
      <div className="w-full bg-white rounded-[40px] p-8 animate-in zoom-in-95 duration-300">
        <h3 className="text-xl font-black text-slate-800 mb-6">Pilih Tanggal Aksi 📅</h3>
        <div className="grid grid-cols-1 gap-3">
          {[
            { date: "all", label: "Semua Acara 🌟" },
            { date: "2026-03-17", label: "Hari Ini (17 Mar)" },
            { date: "2026-03-18", label: "Besok (18 Mar)" },
            { date: "2026-03-19", label: "Lusa (19 Mar)" }
          ].map((d) => (
            <button
              key={d.date}
              onClick={() => { setSelectedDate(d.date); setShowDatePicker(false); }}
              className={`p-5 rounded-3xl border-2 font-bold text-sm transition-all flex justify-between items-center ${selectedDate === d.date ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-100 bg-slate-50 text-slate-600'}`}
            >
              {d.label}
              {selectedDate === d.date && <CheckCircle2 size={20} />}
            </button>
          ))}
        </div>
        <button
          onClick={() => setShowDatePicker(false)}
          className="w-full mt-6 py-4 text-slate-400 font-bold text-sm"
        >
          Tutup
        </button>
      </div>
    </div>
  );

  const CommunityDetailModal = () => (
    <div className="absolute inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-end animate-in fade-in duration-300">
      <div className="w-full h-[90%] bg-white rounded-t-[50px] overflow-y-auto animate-in slide-in-from-bottom duration-500 scrollbar-hide">
        <div className="relative h-64">
          <img src="https://images.unsplash.com/photo-1559128010-7c1ad6e1b6a5?auto=format&fit=crop&w=600&q=80" className="w-full h-full object-cover" alt="Community" />
          <div className="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
          <button
            onClick={() => setShowCommunityDetail(false)}
            className="absolute top-8 right-8 w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30"
          >
            <X size={24} />
          </button>
        </div>

        <div className="px-8 -mt-12 relative z-10">
          <div className="w-24 h-24 bg-emerald-600 rounded-[32px] flex items-center justify-center text-white shadow-2xl border-4 border-white mb-6">
            <Users size={48} />
          </div>
          <h2 className="text-3xl font-black text-slate-800 leading-tight mb-2">Eco-Warriors Bali 🏝️</h2>
          <div className="flex items-center gap-4 mb-8">
            <div className="flex items-center gap-1.5 text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full">
              <Users size={14} /> 1,240 Anggota
            </div>
            <div className="flex items-center gap-1.5 text-xs font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full">
              <MapPin size={14} /> Bali, Indonesia
            </div>
          </div>

          <div className="space-y-6 mb-10">
            <h3 className="text-xl font-black text-slate-800">Tentang Komunitas</h3>
            <p className="text-sm text-slate-500 leading-relaxed font-medium">
              Kami adalah wadah bagi para pecinta alam di Bali yang peduli dengan kebersihan pesisir dan kelestarian ekosistem laut. Bergabunglah dengan kami dalam berbagai aksi nyata setiap minggunya!
            </p>
          </div>

          <div className="space-y-6 mb-10">
            <h3 className="text-xl font-black text-slate-800">Aksi Mendatang</h3>
            <div className="p-5 bg-slate-50 rounded-[32px] border border-slate-100 flex items-center gap-4">
              <div className="w-16 h-16 rounded-2xl overflow-hidden shadow-md">
                <img src="https://images.unsplash.com/photo-1618477461853-cf6ed80fabe5?auto=format&fit=crop&w=150&q=80" className="w-full h-full object-cover" alt="Event" />
              </div>
              <div className="flex-1">
                <h4 className="text-sm font-black text-slate-800 leading-tight">Beach Clean-Up Kuta</h4>
                <p className="text-[10px] text-slate-400 font-bold mt-1">Besok, 08:00 WITA</p>
              </div>
              <ChevronRight className="text-slate-300" size={20} />
            </div>
          </div>

          <button className="w-full bg-emerald-600 text-white py-5 rounded-[28px] font-black text-lg shadow-xl shadow-emerald-100 mb-10 active:scale-95 transition-all">
            Gabung Komunitas
          </button>
        </div>
      </div>
    </div>
  );

  const MapTab = () => {
    const LocationSelector = () => {
      useMapEvents({
        click(e) {
          if (isSelectingLocation) {
            setReportLocation(e.latlng);
          } else if (selectingRoutePoint === 'start') {
            setRouteStart(e.latlng);
            setSelectingRoutePoint(null);
          } else if (selectingRoutePoint === 'end') {
            setRouteEnd(e.latlng);
            setSelectingRoutePoint(null);
          } else if (isAddingLocation) {
            setNewLocationCoords(e.latlng);
          } else if (isAddingZone) {
            setNewZonePoints(prev => [...prev, [e.latlng.lat, e.latlng.lng]]);
          }
        },
      });
      return null;
    };

    const createCustomIcon = (status: string, weather?: any) => {
      const color = status === 'green' ? '#10b981' : status === 'yellow' ? '#f59e0b' : '#ef4444';

      let weatherHtml = '';
      if (weather) {
        const temp = Math.round(weather.temperature_2m);
        const code = weather.weather_code;
        let emoji = '☁️';
        if (code === 0 || code === 1) emoji = '☀️';
        else if (code >= 51 && code <= 67) emoji = '🌧️';
        else if (code >= 95) emoji = '⛈️';

        weatherHtml = `<div style="position: absolute; top: -28px; left: 50%; transform: translateX(-50%); background: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 900; color: #334155; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); white-space: nowrap; display: flex; align-items: center; gap: 4px; border: 2px solid ${color}; z-index: 50;">${emoji} ${temp}°C</div>`;
      }

      return L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="position: relative;">${weatherHtml}<div style="background-color: ${color}; width: 32px; height: 32px; border-radius: 12px; border: 4px solid white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; position: relative; z-index: 40;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
      });
    };

    const indonesiaBounds: L.LatLngBoundsExpression = [
      [-11.0, 94.0], // South West
      [6.0, 142.0]   // North East
    ];

    return (
      <div className="h-full w-full relative overflow-hidden animate-in fade-in duration-300 bg-slate-50">
        <MapContainer
          center={[-0.7893, 113.9213]}
          zoom={5}
          minZoom={5}
          maxBounds={indonesiaBounds}
          maxBoundsViscosity={1.0}
          style={{ height: '100%', width: '100%' }}
          zoomControl={false}
        >
          <TileLayer
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          />

          {showAQILayer && (
            <TileLayer
              url="https://tiles.waqi.info/tiles/usepa-aqi/{z}/{x}/{y}.png?token=demo"
              opacity={0.6}
              attribution='&copy; <a href="https://waqi.info/">WAQI</a>'
            />
          )}

          {/* Environmental Polygons */}
          <LocationSelector />

          {isSelectingLocation && reportLocation && (
            <Marker
              position={[reportLocation.lat, reportLocation.lng]}
              icon={createCustomIcon('yellow')}
            />
          )}

          {/* Route Planning Markers & Polyline */}
          {routeStart && (
            <Marker position={[routeStart.lat, routeStart.lng]} icon={createCustomIcon('green')} />
          )}
          {routeEnd && (
            <Marker position={[routeEnd.lat, routeEnd.lng]} icon={createCustomIcon('red')} />
          )}
          {routeStart && routeEnd && (
            <Polyline
              positions={[[routeStart.lat, routeStart.lng], [routeEnd.lat, routeEnd.lng]]}
              pathOptions={{ color: '#10b981', weight: 4, dashArray: '10, 10' }}
            />
          )}

          {/* New Zone Drawing */}
          {isAddingZone && newZonePoints.length > 0 && (
            <>
              {newZonePoints.map((pt, idx) => (
                <Marker key={`nzp-${idx}`} position={pt} icon={createCustomIcon('green')} />
              ))}
              {newZonePoints.length > 1 && newZonePoints.length < 3 && (
                <Polyline positions={newZonePoints} pathOptions={{ color: '#4f46e5', weight: 3, dashArray: '5, 5' }} />
              )}
              {newZonePoints.length >= 3 && (
                <Polygon positions={newZonePoints} pathOptions={{ color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.3, weight: 3, dashArray: '5, 5' }} />
              )}
            </>
          )}

          {zones
            .filter(zone => statusFilter === 'all' || zone.status === statusFilter)
            .map((zone) => (
              <Polygon
                key={zone.id}
                positions={zone.positions as any}
                pathOptions={{
                  fillColor: zone.color,
                  fillOpacity: 0.3,
                  color: zone.color,
                  weight: 2,
                  dashArray: '5, 5'
                }}
              >
                <Popup>
                  <div className="p-2">
                    <h3 className="font-bold text-slate-800">{zone.name}</h3>
                    <p className="text-[10px] text-emerald-600 font-black uppercase tracking-widest">{zone.category}</p>
                    <p className="text-[10px] text-slate-500 mt-1">Status: {zone.status === 'green' ? 'Terjaga' : zone.status === 'yellow' ? 'Waspada' : 'Kritis'}</p>
                    {isAdmin && (
                      <div className="flex gap-2 mt-2 pt-2 border-t border-slate-100">
                        <button onClick={(e) => { e.stopPropagation(); setEditingItem({ type: 'zone', id: zone.id }); }} className="text-[10px] font-bold text-indigo-600">Edit</button>
                        <button onClick={(e) => { e.stopPropagation(); setZones(zones.filter(z => z.id !== zone.id)); }} className="text-[10px] font-bold text-rose-600">Hapus</button>
                      </div>
                    )}
                  </div>
                </Popup>
              </Polygon>
            ))}

          {locations
            .filter(l => l.name.toLowerCase().includes(searchQuery.toLowerCase()))
            .filter(l => statusFilter === 'all' || l.status === statusFilter)
            .map((loc) => (
              <Marker
                key={loc.id}
                position={[loc.lat, loc.lng]}
                icon={createCustomIcon(loc.status, weatherData[loc.id])}
                eventHandlers={{
                  click: () => handleLocationClick(loc),
                }}
              >
                <Popup className="custom-popup">
                  <div className="p-2">
                    <h3 className="font-bold text-slate-800">{loc.name}</h3>
                    <p className="text-[10px] text-slate-500 mt-1">{loc.description.slice(0, 50)}...</p>
                    <button
                      onClick={() => handleLocationClick(loc)}
                      className="mt-2 text-[10px] font-bold text-emerald-600 uppercase tracking-widest"
                    >
                      Lihat Detail
                    </button>
                    {isAdmin && (
                      <div className="flex gap-2 mt-2 pt-2 border-t border-slate-100">
                        <button onClick={(e) => { e.stopPropagation(); setEditingItem({ type: 'location', id: loc.id }); }} className="text-[10px] font-bold text-indigo-600">Edit</button>
                        <button onClick={(e) => { e.stopPropagation(); setLocations(locations.filter(l => l.id !== loc.id)); }} className="text-[10px] font-bold text-rose-600">Hapus</button>
                      </div>
                    )}
                  </div>
                </Popup>
              </Marker>
            ))}
        </MapContainer>

        {/* Location Selection UI */}
        {isSelectingLocation && (
          <>
            <div className="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-full font-bold shadow-2xl z-[1000] flex items-center gap-2 animate-in slide-in-from-top">
              <MapPin size={18} className="text-emerald-400" />
              Tap peta untuk pilih lokasi
            </div>

            {reportLocation && (
              <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-[1000] animate-in slide-in-from-bottom">
                <button
                  onClick={() => { setIsSelectingLocation(false); setActiveTab('report'); }}
                  className="bg-emerald-600 text-white px-8 py-4 rounded-full font-black shadow-xl shadow-emerald-200 hover:bg-emerald-700 active:scale-95 transition-all flex items-center gap-2"
                >
                  <CheckCircle2 size={20} />
                  Konfirmasi Lokasi
                </button>
              </div>
            )}

            <button
              onClick={() => { setIsSelectingLocation(false); setActiveTab('report'); }}
              className="absolute top-6 right-6 w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-500 shadow-xl z-[1000] hover:bg-slate-50 transition-colors"
            >
              <X size={24} />
            </button>
          </>
        )}

        {/* Route Planning UI */}
        {selectingRoutePoint && (
          <div className="absolute top-6 left-1/2 -translate-x-1/2 bg-emerald-900 text-white px-6 py-3 rounded-full font-bold shadow-2xl z-[1000] flex items-center gap-2 animate-in slide-in-from-top">
            <MapPin size={18} className="text-emerald-400" />
            Tap peta untuk pilih {selectingRoutePoint === 'start' ? 'Titik Awal' : 'Tujuan'}
          </div>
        )}

        {/* Admin Add Location UI */}
        {isAdmin && !isAddingLocation && !isAddingZone && !isSelectingLocation && !isPlanningRoute && (
          <div className="absolute bottom-24 left-6 flex flex-col gap-3 z-[1000]">
            <button
              onClick={() => setIsAddingLocation(true)}
              className="bg-slate-800 text-white px-6 py-4 rounded-full font-black shadow-xl shadow-slate-200 hover:bg-slate-900 active:scale-95 transition-all flex items-center gap-2"
            >
              <MapPin size={20} />
              + Tambah Marker
            </button>
            <button
              onClick={() => { setIsAddingZone(true); setNewZonePoints([]); }}
              className="bg-indigo-600 text-white px-6 py-4 rounded-full font-black shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-2"
            >
              <MapIcon size={20} />
              + Tambah Zona
            </button>
          </div>
        )}

        {isAddingLocation && !newLocationCoords && (
          <div className="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-full font-bold shadow-2xl z-[1000] flex items-center gap-2 animate-in slide-in-from-top">
            <MapPin size={18} className="text-emerald-400" />
            Tap peta untuk titik lokasi baru
          </div>
        )}

        {isAddingZone && (
          <div className="absolute top-6 left-1/2 -translate-x-1/2 bg-indigo-900 text-white px-6 py-3 rounded-full font-bold shadow-2xl z-[1000] flex items-center gap-2 animate-in slide-in-from-top">
            <MapIcon size={18} className="text-indigo-400" />
            Tap peta untuk membuat titik zona ({newZonePoints.length} titik)
          </div>
        )}

        {(isAddingLocation || isAddingZone) && (
          <button
            onClick={() => {
              setIsAddingLocation(false);
              setNewLocationCoords(null);
              setIsAddingZone(false);
              setNewZonePoints([]);
            }}
            className="absolute top-6 right-6 w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-500 shadow-xl z-[1000] hover:bg-slate-50 transition-colors"
          >
            <X size={24} />
          </button>
        )}

        {isAddingZone && newZonePoints.length > 0 && (
          <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-[1000]">
            <button
              onClick={() => setNewZonePoints(prev => prev.slice(0, -1))}
              className="bg-white text-slate-700 px-6 py-3 rounded-full font-bold shadow-xl hover:bg-slate-50 transition-colors"
            >
              Undo Titik
            </button>
            {newZonePoints.length >= 3 && (
              <button
                onClick={() => {
                  // Show form for new zone
                  setEditingItem({ type: 'zone', id: -1 }); // -1 means new
                }}
                className="bg-indigo-600 text-white px-6 py-3 rounded-full font-bold shadow-xl hover:bg-indigo-700 transition-colors"
              >
                Selesai & Simpan
              </button>
            )}
          </div>
        )}

        {/* New Zone Form Modal */}
        {editingItem?.type === 'zone' && editingItem.id === -1 && (
          <div className="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[1000] p-6 animate-in slide-in-from-bottom duration-300 max-h-[80vh] overflow-y-auto">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-xl font-black text-slate-800 flex items-center gap-2">
                <MapIcon className="text-indigo-600" size={24} />
                Detail Zona Baru
              </h2>
              <button onClick={() => setEditingItem(null)} className="p-2 text-slate-400 hover:bg-slate-100 rounded-full"><X size={20} /></button>
            </div>

            <div className="space-y-4">
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Zona</label>
                <input
                  type="text"
                  value={newZoneForm.name}
                  onChange={(e) => setNewZoneForm({ ...newZoneForm, name: e.target.value })}
                  className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500"
                  placeholder="Contoh: Zona Konservasi Penyu"
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori</label>
                <input
                  type="text"
                  value={newZoneForm.category}
                  onChange={(e) => setNewZoneForm({ ...newZoneForm, category: e.target.value })}
                  className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500"
                  placeholder="Contoh: Konservasi Laut"
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Status Alam</label>
                <div className="flex gap-2">
                  <button onClick={() => setNewZoneForm({ ...newZoneForm, status: 'green', color: '#10b981' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newZoneForm.status === 'green' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'}`}>Terjaga</button>
                  <button onClick={() => setNewZoneForm({ ...newZoneForm, status: 'yellow', color: '#f59e0b' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newZoneForm.status === 'yellow' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'}`}>Waspada</button>
                  <button onClick={() => setNewZoneForm({ ...newZoneForm, status: 'red', color: '#ef4444' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newZoneForm.status === 'red' ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-500'}`}>Kritis</button>
                </div>
              </div>

              <button
                onClick={() => {
                  if (newZoneForm.name && newZonePoints.length >= 3) {
                    setZones([...zones, {
                      id: Date.now(),
                      name: newZoneForm.name,
                      category: newZoneForm.category || "Umum",
                      status: newZoneForm.status,
                      color: newZoneForm.color,
                      positions: newZonePoints
                    }]);
                    setIsAddingZone(false);
                    setNewZonePoints([]);
                    setEditingItem(null);
                    setNewZoneForm({ name: '', category: '', status: 'green', color: '#10b981' });
                  }
                }}
                className="w-full mt-4 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-md hover:bg-indigo-700 transition-colors"
              >
                Simpan Zona
              </button>
            </div>
          </div>
        )}

        {/* Edit Location Modal */}
        {editingItem?.type === 'location' && editingItem.id !== -1 && (() => {
          const locToEdit = locations.find(l => l.id === editingItem.id);
          if (!locToEdit) return null;
          return (
            <div className="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[1000] p-6 animate-in slide-in-from-bottom duration-300 max-h-[80vh] overflow-y-auto">
              <div className="flex justify-between items-center mb-4">
                <h2 className="text-xl font-black text-slate-800 flex items-center gap-2">
                  <MapPin className="text-indigo-600" size={24} />
                  Edit Lokasi
                </h2>
                <button onClick={() => setEditingItem(null)} className="p-2 text-slate-400 hover:bg-slate-100 rounded-full"><X size={20} /></button>
              </div>

              <div className="space-y-4">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lokasi</label>
                  <input
                    type="text"
                    defaultValue={locToEdit.name}
                    id="edit-loc-name"
                    className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500"
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi</label>
                  <textarea
                    defaultValue={locToEdit.description}
                    id="edit-loc-desc"
                    className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500 min-h-[100px]"
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Status Alam</label>
                  <select id="edit-loc-status" defaultValue={locToEdit.status} className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500">
                    <option value="green">Terjaga</option>
                    <option value="yellow">Waspada</option>
                    <option value="red">Kritis</option>
                  </select>
                </div>

                <button
                  onClick={() => {
                    const name = (document.getElementById('edit-loc-name') as HTMLInputElement).value;
                    const desc = (document.getElementById('edit-loc-desc') as HTMLTextAreaElement).value;
                    const status = (document.getElementById('edit-loc-status') as HTMLSelectElement).value;

                    setLocations(locations.map(l => l.id === locToEdit.id ? { ...l, name, description: desc, status } : l));
                    setEditingItem(null);
                  }}
                  className="w-full mt-4 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-md hover:bg-indigo-700 transition-colors"
                >
                  Simpan Perubahan
                </button>
              </div>
            </div>
          );
        })()}

        {/* Edit Zone Modal */}
        {editingItem?.type === 'zone' && editingItem.id !== -1 && (() => {
          const zoneToEdit = zones.find(z => z.id === editingItem.id);
          if (!zoneToEdit) return null;
          return (
            <div className="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[1000] p-6 animate-in slide-in-from-bottom duration-300 max-h-[80vh] overflow-y-auto">
              <div className="flex justify-between items-center mb-4">
                <h2 className="text-xl font-black text-slate-800 flex items-center gap-2">
                  <MapIcon className="text-indigo-600" size={24} />
                  Edit Zona
                </h2>
                <button onClick={() => setEditingItem(null)} className="p-2 text-slate-400 hover:bg-slate-100 rounded-full"><X size={20} /></button>
              </div>

              <div className="space-y-4">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Zona</label>
                  <input
                    type="text"
                    defaultValue={zoneToEdit.name}
                    id="edit-zone-name"
                    className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500"
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori</label>
                  <input
                    type="text"
                    defaultValue={zoneToEdit.category}
                    id="edit-zone-cat"
                    className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500"
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Status Alam</label>
                  <select id="edit-zone-status" defaultValue={zoneToEdit.status} className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-indigo-500">
                    <option value="green">Terjaga</option>
                    <option value="yellow">Waspada</option>
                    <option value="red">Kritis</option>
                  </select>
                </div>

                <button
                  onClick={() => {
                    const name = (document.getElementById('edit-zone-name') as HTMLInputElement).value;
                    const category = (document.getElementById('edit-zone-cat') as HTMLInputElement).value;
                    const status = (document.getElementById('edit-zone-status') as HTMLSelectElement).value;
                    const color = status === 'green' ? '#10b981' : status === 'yellow' ? '#f59e0b' : '#ef4444';

                    setZones(zones.map(z => z.id === zoneToEdit.id ? { ...z, name, category, status, color } : z));
                    setEditingItem(null);
                  }}
                  className="w-full mt-4 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-md hover:bg-indigo-700 transition-colors"
                >
                  Simpan Perubahan
                </button>
              </div>
            </div>
          );
        })()}

        {newLocationCoords && (
          <div className="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[1000] p-6 animate-in slide-in-from-bottom duration-300 max-h-[80vh] overflow-y-auto">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-xl font-black text-slate-800 flex items-center gap-2">
                <MapPin className="text-emerald-600" size={24} />
                Detail Lokasi Baru
              </h2>
            </div>

            <div className="space-y-4">
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lokasi</label>
                <input
                  type="text"
                  value={newLocationForm.name}
                  onChange={(e) => setNewLocationForm({ ...newLocationForm, name: e.target.value })}
                  className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-emerald-500"
                  placeholder="Contoh: Pantai Kuta"
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi</label>
                <textarea
                  value={newLocationForm.description}
                  onChange={(e) => setNewLocationForm({ ...newLocationForm, description: e.target.value })}
                  className="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold outline-none focus:border-emerald-500 min-h-[100px]"
                  placeholder="Deskripsi singkat lokasi..."
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Status Alam</label>
                <div className="flex gap-2">
                  <button onClick={() => setNewLocationForm({ ...newLocationForm, status: 'green' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newLocationForm.status === 'green' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'}`}>Terjaga</button>
                  <button onClick={() => setNewLocationForm({ ...newLocationForm, status: 'yellow' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newLocationForm.status === 'yellow' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'}`}>Waspada</button>
                  <button onClick={() => setNewLocationForm({ ...newLocationForm, status: 'red' })} className={`flex-1 py-3 rounded-2xl text-xs font-bold transition-colors ${newLocationForm.status === 'red' ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-500'}`}>Kritis</button>
                </div>
              </div>

              <button
                onClick={() => {
                  if (newLocationForm.name) {
                    setLocations([...locations, {
                      id: Date.now(),
                      name: newLocationForm.name,
                      description: newLocationForm.description || "Lokasi baru ditambahkan oleh admin.",
                      lat: newLocationCoords.lat,
                      lng: newLocationCoords.lng,
                      status: newLocationForm.status,
                      score: 7.0,
                      img: "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80",
                      cleanliness: 3,
                      access: "Sedang",
                      population: "Sedang"
                    }]);
                    setIsAddingLocation(false);
                    setNewLocationCoords(null);
                    setNewLocationForm({ name: '', description: '', status: 'green' });
                  }
                }}
                className="w-full mt-4 bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-md hover:bg-emerald-700 transition-colors"
              >
                Simpan Lokasi
              </button>
            </div>
          </div>
        )}

        {!isSelectingLocation && !isPlanningRoute && !isAddingLocation && !isAddingZone && (
          <button
            onClick={() => setIsPlanningRoute(true)}
            className="absolute bottom-6 left-6 bg-emerald-600 text-white px-6 py-4 rounded-full font-black shadow-xl shadow-emerald-200 hover:bg-emerald-700 active:scale-95 transition-all flex items-center gap-2 z-[1000]"
          >
            <MapIcon size={20} />
            Peta Jejak Karbon
          </button>
        )}

        {isPlanningRoute && (
          <div className="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[1000] p-6 animate-in slide-in-from-bottom duration-300 max-h-[80vh] overflow-y-auto">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-xl font-black text-slate-800 flex items-center gap-2">
                <MapIcon className="text-emerald-600" size={24} />
                Peta Jejak Karbon
              </h2>
              <button
                onClick={() => { setIsPlanningRoute(false); setRouteStart(null); setRouteEnd(null); setSelectingRoutePoint(null); }}
                className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors"
              >
                <X size={20} />
              </button>
            </div>

            <div className="space-y-4">
              <div className="flex gap-4">
                <div className="flex-1 space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Titik Awal</label>
                  <button
                    onClick={() => setSelectingRoutePoint('start')}
                    className={`w-full p-4 rounded-2xl border-2 text-left text-sm font-bold transition-colors ${selectingRoutePoint === 'start' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-100 bg-slate-50 text-slate-600 hover:border-slate-200'}`}
                  >
                    {routeStart ? `${routeStart.lat.toFixed(4)}, ${routeStart.lng.toFixed(4)}` : 'Pilih di Peta'}
                  </button>
                </div>
                <div className="flex-1 space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tujuan</label>
                  <button
                    onClick={() => setSelectingRoutePoint('end')}
                    className={`w-full p-4 rounded-2xl border-2 text-left text-sm font-bold transition-colors ${selectingRoutePoint === 'end' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-100 bg-slate-50 text-slate-600 hover:border-slate-200'}`}
                  >
                    {routeEnd ? `${routeEnd.lat.toFixed(4)}, ${routeEnd.lng.toFixed(4)}` : 'Pilih di Peta'}
                  </button>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Moda Transportasi</label>
                <div className="flex gap-2">
                  {['car', 'bus', 'train', 'flight'].map(mode => (
                    <button
                      key={mode}
                      onClick={() => setTransportMode(mode as any)}
                      className={`flex-1 py-3 rounded-2xl text-xs font-bold capitalize transition-colors ${transportMode === mode ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'}`}
                    >
                      {mode === 'car' ? 'Mobil' : mode === 'bus' ? 'Bus' : mode === 'train' ? 'Kereta' : 'Pesawat'}
                    </button>
                  ))}
                </div>
              </div>

              {routeStart && routeEnd && (
                <div className="bg-emerald-50 p-5 rounded-3xl border border-emerald-100 mt-4 animate-in fade-in zoom-in duration-300">
                  <div className="flex justify-between items-center mb-2">
                    <span className="text-sm font-bold text-emerald-800">Estimasi Jarak</span>
                    <span className="text-lg font-black text-emerald-900">{calculateDistance(routeStart.lat, routeStart.lng, routeEnd.lat, routeEnd.lng).toFixed(1)} km</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-bold text-emerald-800">Jejak Karbon</span>
                    <span className="text-lg font-black text-rose-600">{calculateEmissions(calculateDistance(routeStart.lat, routeStart.lng, routeEnd.lat, routeEnd.lng), transportMode).toFixed(1)} kg CO₂</span>
                  </div>
                  <button className="w-full mt-4 bg-emerald-600 text-white py-3 rounded-xl font-bold shadow-md hover:bg-emerald-700 transition-colors flex justify-center items-center gap-2">
                    <Leaf size={18} />
                    Kompensasi Karbon di Muara Gembong
                  </button>
                </div>
              )}
            </div>
          </div>
        )}

        {/* Search Bar + Filter Button (Task 2.2) */}
        {!isSelectingLocation && (
          <div className="absolute top-6 inset-x-6 flex gap-3 z-[1000]">
            <div className="flex-1 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 flex items-center px-4 py-3.5">
              <Search size={20} className="text-slate-400" />
              <input
                type="text"
                placeholder="Cari destinasi alam di Indonesia..."
                className="bg-transparent border-none outline-none ml-2 text-sm w-full font-bold text-slate-700 placeholder:text-slate-400"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </div>
            <div className="relative">
              <button
                onClick={() => setShowFilterDropdown(!showFilterDropdown)}
                className={`p-3.5 rounded-2xl shadow-xl transition-all ${statusFilter !== 'all' ? 'bg-emerald-700 text-white shadow-emerald-200' : 'bg-emerald-600 text-white shadow-emerald-100 hover:bg-emerald-700 active:scale-95'}`}
              >
                <Filter size={20} />
              </button>

              {showFilterDropdown && (
                <div className="absolute top-full right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 w-48 flex flex-col gap-1 animate-in slide-in-from-top-2 duration-200">
                  <button
                    onClick={() => { setStatusFilter('all'); setShowFilterDropdown(false); }}
                    className={`text-left px-4 py-2.5 rounded-xl text-sm font-bold transition-colors ${statusFilter === 'all' ? 'bg-slate-100 text-slate-800' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'}`}
                  >
                    Semua Status
                  </button>
                  <button
                    onClick={() => { setStatusFilter('green'); setShowFilterDropdown(false); }}
                    className={`text-left px-4 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 ${statusFilter === 'green' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'}`}
                  >
                    <div className="w-2 h-2 rounded-full bg-emerald-500"></div>
                    Terjaga
                  </button>
                  <button
                    onClick={() => { setStatusFilter('yellow'); setShowFilterDropdown(false); }}
                    className={`text-left px-4 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 ${statusFilter === 'yellow' ? 'bg-amber-50 text-amber-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'}`}
                  >
                    <div className="w-2 h-2 rounded-full bg-amber-500"></div>
                    Waspada
                  </button>
                  <button
                    onClick={() => { setStatusFilter('red'); setShowFilterDropdown(false); }}
                    className={`text-left px-4 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 ${statusFilter === 'red' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'}`}
                  >
                    <div className="w-2 h-2 rounded-full bg-rose-500"></div>
                    Kritis
                  </button>
                  <div className="pt-2 mt-2 border-t border-slate-100">
                    <button
                      onClick={() => { setShowAQILayer(!showAQILayer); setShowFilterDropdown(false); }}
                      className={`w-full text-left px-4 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center justify-between ${showAQILayer ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'}`}
                    >
                      <div className="flex items-center gap-2">
                        <Cloud size={16} />
                        Layer Air Quality
                      </div>
                      <div className={`w-8 h-4 rounded-full transition-colors relative ${showAQILayer ? 'bg-blue-500' : 'bg-slate-200'}`}>
                        <div className={`absolute top-0.5 w-3 h-3 rounded-full bg-white transition-all ${showAQILayer ? 'left-4.5 right-0.5' : 'left-0.5'}`} style={{ left: showAQILayer ? '18px' : '2px' }}></div>
                      </div>
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        )}

        {/* Legend Status (Task 2.3) */}
        {!isSelectingLocation && !isPlanningRoute && !isAddingLocation && !isAddingZone && (
          <div className="absolute bottom-6 right-6 bg-white/95 backdrop-blur-xl p-5 rounded-[32px] shadow-2xl border border-white/50 space-y-4 z-[1000]">
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Alam</p>
            <div className="flex items-center gap-3 cursor-pointer group">
              <div className="w-3.5 h-3.5 bg-emerald-500 rounded-full ring-4 ring-emerald-100 group-hover:scale-125 transition-transform"></div>
              <span className="text-[11px] font-black text-slate-700">Terjaga</span>
            </div>
            <div className="flex items-center gap-3 cursor-pointer group">
              <div className="w-3.5 h-3.5 bg-amber-500 rounded-full ring-4 ring-amber-100 group-hover:scale-125 transition-transform"></div>
              <span className="text-[11px] font-black text-slate-700">Perlu Aksi</span>
            </div>
            <div className="flex items-center gap-3 cursor-pointer group">
              <div className="w-3.5 h-3.5 bg-rose-500 rounded-full ring-4 ring-rose-100 group-hover:scale-125 transition-transform"></div>
              <span className="text-[11px] font-black text-slate-700">Kritis</span>
            </div>
            <div className="pt-2 border-t border-slate-100">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori Zona</p>
              <div className="flex items-center gap-3">
                <div className="w-4 h-2 bg-emerald-500/30 border border-emerald-500 border-dashed"></div>
                <span className="text-[10px] font-bold text-slate-600 tracking-tight">Hutan/Hijau</span>
              </div>
              <div className="flex items-center gap-3 mt-1">
                <div className="w-4 h-2 bg-rose-500/30 border border-rose-500 border-dashed"></div>
                <span className="text-[10px] font-bold text-slate-600 tracking-tight">Kritis/Pesisir</span>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  };

  const HomeTab = () => (
    <MapTab />
  );

  const DetailTab = ({ location }: { location: any }) => {
    const [weather, setWeather] = useState<any>(null);
    const [loadingWeather, setLoadingWeather] = useState(true);

    useEffect(() => {
      if (location) {
        setLoadingWeather(true);
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${location.lat}&longitude=${location.lng}&current=temperature_2m,weather_code`)
          .then(res => res.json())
          .then(data => {
            setWeather(data.current);
            setLoadingWeather(false);
          })
          .catch(err => {
            console.error("Failed to fetch weather", err);
            setLoadingWeather(false);
          });
      }
    }, [location]);

    const getWeatherIcon = (code: number) => {
      if (code === 0 || code === 1) return <Sun size={20} className="text-amber-500" />;
      if (code === 2 || code === 3 || code === 45 || code === 48) return <Cloud size={20} className="text-slate-400" />;
      if (code >= 51 && code <= 67) return <CloudRain size={20} className="text-blue-500" />;
      if (code >= 95) return <CloudLightning size={20} className="text-purple-500" />;
      return <Cloud size={20} className="text-slate-400" />;
    };

    const getWeatherDesc = (code: number) => {
      if (code === 0) return "Cerah";
      if (code === 1 || code === 2 || code === 3) return "Berawan";
      if (code === 45 || code === 48) return "Berkabut";
      if (code >= 51 && code <= 67) return "Hujan";
      if (code >= 95) return "Badai Petir";
      return "Berawan";
    };

    if (!location) return <div className="p-10 text-center font-bold text-slate-400">Pilih lokasi dulu di Peta! 🗺️</div>;

    return (
      <div className="h-full bg-white overflow-y-auto pb-24 animate-in slide-in-from-right duration-500 scrollbar-hide">
        {/* Hero Image + Top Buttons (Task 3.1) */}
        <div className="relative h-[450px]">
          <img src={location.img} className="w-full h-full object-cover" alt={location.name} />
          <div className="absolute inset-0 bg-gradient-to-t from-white via-black/20 to-black/40"></div>

          <div className="absolute top-8 inset-x-6 flex justify-between items-center z-10">
            <button
              className="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-white border border-white/30 hover:bg-white/40 active:scale-90 transition-all"
              onClick={() => setActiveTab('map')}
            >
              <ArrowLeft size={24} />
            </button>
            <div className="flex gap-3">
              <button className="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-white border border-white/30 hover:bg-white/40 active:text-rose-400 transition-all">
                <Heart size={20} />
              </button>
              <button className="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-white border border-white/30 hover:bg-white/40 transition-all">
                <Share2 size={20} />
              </button>
            </div>
          </div>

          <div className="absolute bottom-12 left-8 right-8 flex justify-between items-end">
            <div className="flex-1">
              <div className="flex items-center gap-2 mb-3">
                <span className={`px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg ${location.status === 'green' ? 'bg-emerald-500 text-white' :
                    location.status === 'yellow' ? 'bg-amber-500 text-white' : 'bg-rose-500 text-white'
                  }`}>
                  {location.status === 'green' ? 'Sangat Terjaga' :
                    location.status === 'yellow' ? 'Perlu Perhatian' : 'Butuh Bantuan'}
                </span>
              </div>
              <h2 className="text-5xl font-black text-white mb-3 leading-tight drop-shadow-2xl">{location.name}</h2>
              <div className="flex items-center gap-2 text-white/90 text-sm font-black">
                <MapPin size={18} className="text-emerald-400" /> {location.lat}, {location.lng}
                <span className="mx-2 opacity-50">•</span>
                {loadingWeather ? (
                  <span className="animate-pulse">Memuat cuaca...</span>
                ) : weather ? (
                  <div className="flex items-center gap-1.5 bg-black/20 px-3 py-1 rounded-full backdrop-blur-md border border-white/10">
                    {getWeatherIcon(weather.weather_code)}
                    <span>{weather.temperature_2m}°C</span>
                    <span className="ml-1 text-xs font-bold opacity-80">{getWeatherDesc(weather.weather_code)}</span>
                  </div>
                ) : null}
              </div>
            </div>
          </div>
        </div>

        {/* Score, Status Tag + Info Section (Task 3.2) */}
        <div className="px-8 -mt-8 relative z-20">
          <div className="bg-white rounded-[40px] p-8 shadow-2xl shadow-slate-200 border border-slate-50 space-y-8">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-[11px] text-slate-400 font-black uppercase tracking-widest mb-1">Eco-Health Score</p>
                <div className="flex items-center gap-3">
                  <h3 className={`text-5xl font-black ${location.score > 8 ? 'text-emerald-600' : location.score > 5 ? 'text-amber-500' : 'text-rose-500'}`}>
                    {location.score}
                  </h3>
                  <div className="flex flex-col">
                    <div className="flex items-center gap-0.5">
                      {[1, 2, 3, 4, 5].map(s => (
                        <Star key={s} size={14} className={s <= Math.floor(location.score / 2) ? "fill-amber-400 text-amber-400" : "text-slate-200"} />
                      ))}
                    </div>
                    <p className="text-[10px] font-bold text-slate-400 mt-1">Berdasarkan 1.2k Laporan</p>
                  </div>
                </div>
              </div>
              <div className="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <Leaf size={32} />
              </div>
            </div>

            <div className="grid grid-cols-3 gap-4">
              <div className="bg-slate-50 p-4 rounded-3xl text-center space-y-1 border border-slate-100">
                <p className="text-[9px] text-slate-400 uppercase font-black">Kebersihan</p>
                <p className="text-xs font-black text-slate-700">{location.cleanliness}/5</p>
              </div>
              <div className="bg-slate-50 p-4 rounded-3xl text-center space-y-1 border border-slate-100">
                <p className="text-[9px] text-slate-400 uppercase font-black">Akses</p>
                <p className="text-xs font-black text-slate-700">{location.access}</p>
              </div>
              <div className="bg-slate-50 p-4 rounded-3xl text-center space-y-1 border border-slate-100">
                <p className="text-[9px] text-slate-400 uppercase font-black">Populasi</p>
                <p className="text-xs font-black text-slate-700">{location.population}</p>
              </div>
            </div>

            <div className="space-y-3">
              <h3 className="font-black text-xl text-slate-800">Tentang Lokasi</h3>
              <p className="text-slate-500 text-sm leading-relaxed font-medium">
                {location.description}
              </p>
            </div>

            {/* Aturan Wisata Hijau + Tombol Laporkan (Task 3.3) */}
            <div className="p-6 bg-emerald-50 rounded-[32px] border border-emerald-100">
              <div className="flex items-center gap-3 mb-5">
                <div className="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                  <BookOpen size={20} />
                </div>
                <h3 className="font-black text-emerald-800">Aturan Wisata Hijau</h3>
              </div>
              <ul className="space-y-4">
                <li className="flex items-start gap-3 text-sm text-emerald-700 font-bold">
                  <CheckCircle2 size={18} className="text-emerald-500 mt-0.5 shrink-0" />
                  Dilarang membawa plastik sekali pakai.
                </li>
                <li className="flex items-start gap-3 text-sm text-emerald-700 font-bold">
                  <CheckCircle2 size={18} className="text-emerald-500 mt-0.5 shrink-0" />
                  Gunakan jalur trekking yang sudah disediakan.
                </li>
                <li className="flex items-start gap-3 text-sm text-rose-600 font-black">
                  <AlertTriangle size={18} className="text-rose-500 mt-0.5 shrink-0" />
                  Dilarang memberi makan satwa liar!
                </li>
              </ul>
            </div>

            <button
              className="w-full bg-slate-900 text-white py-5 rounded-[28px] font-black text-lg flex items-center justify-center gap-3 shadow-2xl active:scale-95 transition-all"
              onClick={() => setActiveTab('report')}
            >
              <Camera size={24} className="text-emerald-400" /> Laporkan Kondisi Alam
            </button>
          </div>
        </div>
      </div>
    );
  };

  const ReportTab = () => {
    if (reportStatus === 'success') {
      return (
        <div className="h-full flex flex-col items-center justify-center px-10 text-center animate-in zoom-in-95 duration-500">
          <div className="w-32 h-32 bg-emerald-100 rounded-[40px] flex items-center justify-center mb-8 border-4 border-emerald-50 shadow-inner">
            <CheckCircle2 size={64} className="text-emerald-600" />
          </div>
          <h2 className="text-3xl font-black text-slate-800 mb-3">Berhasil Terkirim!</h2>
          <p className="text-slate-500 text-sm mb-10 leading-relaxed font-medium">
            Terima kasih, Eco-Ranger! Laporanmu sangat berharga bagi kelestarian alam kita. Kamu mendapatkan <strong className="text-emerald-600">+10 Poin Dasar</strong>.
          </p>
          <button
            className="bg-slate-900 text-white px-10 py-5 rounded-[24px] font-bold w-full shadow-xl active:scale-95 transition-all"
            onClick={() => { setReportStatus('idle'); setActiveTab('home'); }}
          >
            Kembali ke Beranda
          </button>
        </div>
      );
    }

    return (
      <div className="px-8 py-8 space-y-8 overflow-y-auto pb-24 h-full animate-in fade-in duration-300 scrollbar-hide">
        <h1 className="text-3xl font-black text-slate-800 leading-tight">Eco-Reporter 📸</h1>
        <p className="text-slate-500 text-sm font-medium">Menemukan kerusakan atau penumpukan sampah? Laporkan untuk tindakan segera.</p>

        <div className="aspect-square bg-slate-50 rounded-[40px] border-4 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all active:scale-95">
          <div className="w-20 h-20 bg-white rounded-3xl flex items-center justify-center shadow-lg group-hover:text-emerald-600 transition-colors">
            <Camera size={40} />
          </div>
          <p className="mt-4 text-sm font-bold tracking-tight">Ambil atau Unggah Foto</p>
        </div>

        <div className="space-y-6">
          <div className="space-y-2">
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Judul Laporan</label>
            <input
              type="text"
              value={reportForm.title}
              onChange={(e) => setReportForm({ ...reportForm, title: e.target.value })}
              className="w-full p-5 bg-white rounded-3xl border border-slate-100 text-sm font-bold outline-none shadow-sm focus:border-emerald-500 transition-colors"
              placeholder="Contoh: Tumpukan Sampah Plastik"
            />
          </div>
          <div className="space-y-2">
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Lokasi Kejadian</label>
            <div
              className="flex items-center gap-3 p-5 bg-white rounded-3xl border border-slate-100 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors"
              onClick={() => { setIsSelectingLocation(true); setActiveTab('map'); }}
            >
              <MapPin size={20} className="text-emerald-600" />
              <span className="text-sm text-slate-700 font-bold">
                {reportLocation ? `${reportLocation.lat.toFixed(5)}, ${reportLocation.lng.toFixed(5)}` : 'Pilih lokasi di peta'}
              </span>
            </div>
          </div>
          <div className="space-y-2">
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Kategori Masalah</label>
            <div className="relative">
              <select
                value={reportForm.category}
                onChange={(e) => setReportForm({ ...reportForm, category: e.target.value })}
                className="w-full p-5 bg-white rounded-3xl border border-slate-100 text-sm font-bold outline-none appearance-none shadow-sm cursor-pointer focus:border-emerald-500 transition-colors"
              >
                <option>Pemutihan Terumbu Karang</option>
                <option>Tumpukan Sampah Plastik</option>
                <option>Perusakan Habitat Satwa</option>
                <option>Penebangan Ilegal (Cagar Alam)</option>
              </select>
              <ChevronRight className="absolute right-5 top-1/2 -translate-y-1/2 rotate-90 text-slate-400 pointer-events-none" size={20} />
            </div>
          </div>
          <div className="space-y-2">
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Keterangan Tambahan</label>
            <textarea
              value={reportForm.description}
              onChange={(e) => setReportForm({ ...reportForm, description: e.target.value })}
              placeholder="Jelaskan apa yang kamu temukan..."
              className="w-full p-5 bg-white rounded-3xl border border-slate-100 text-sm font-medium outline-none h-32 shadow-sm focus:border-emerald-500 transition-colors"
            ></textarea>
          </div>
        </div>

        <button
          className="w-full bg-emerald-600 text-white py-5 rounded-[24px] font-bold text-lg shadow-xl shadow-emerald-100 active:scale-95 transition-all flex items-center justify-center gap-3"
          onClick={handleReportSubmit}
        >
          {reportStatus === 'reporting' ? <div className="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div> : "Kirim Laporan"}
        </button>
      </div>
    );
  };

  const CommunityTab = () => (
    <div className="px-8 py-8 space-y-8 overflow-y-auto pb-24 h-full animate-in fade-in duration-300 scrollbar-hide">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-black text-slate-800">Komunitas 🤝</h1>
        <button
          onClick={() => setShowDatePicker(true)}
          className={`w-12 h-12 rounded-2xl flex items-center justify-center transition-all ${selectedDate !== "2026-03-17" ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'}`}
        >
          <Calendar size={24} />
        </button>
      </div>

      <div className="bg-gradient-to-r from-emerald-50 to-teal-50 p-6 rounded-[32px] flex items-center gap-5 border border-emerald-100 cursor-pointer hover:shadow-lg transition-all">
        <div className="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
          <Users size={32} />
        </div>
        <div className="flex-1">
          <h4 className="font-black text-emerald-900 leading-tight">Eco-Warriors Bali</h4>
          <p className="text-xs text-emerald-700 font-bold opacity-70">1,240 Anggota Aktif</p>
        </div>
        <button
          onClick={() => setShowCommunityDetail(true)}
          className="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-xs font-black shadow-md hover:bg-emerald-700 active:scale-95 transition-all"
        >
          Buka
        </button>
      </div>

      <div className="flex justify-between items-center">
        <h3 className="font-black text-xl text-slate-800">
          {selectedDate === "all" ? "Semua Agenda Aksi" :
            selectedDate === "2026-03-17" ? "Agenda Hari Ini" :
              selectedDate === "2026-03-18" ? "Agenda Besok" : "Agenda Mendatang"}
        </h3>
      </div>

      <div className="space-y-8">
        {filteredEvents.length > 0 ? filteredEvents.map(event => (
          <div key={event.id} className="group cursor-pointer">
            <div className="relative h-60 rounded-[40px] overflow-hidden mb-4 shadow-xl">
              <img src={event.img} alt={event.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
              <div className="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                +{event.points} Poin
              </div>
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
              <div className="absolute bottom-6 left-6 right-6 flex items-center justify-between">
                <div className="flex -space-x-3">
                  {[1, 2, 3, 4].map(i => <div key={i} className="w-8 h-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[8px] font-bold">U{i}</div>)}
                  <div className="w-8 h-8 rounded-full border-2 border-white bg-emerald-500 text-white flex items-center justify-center text-[8px] font-bold">+{event.participants}</div>
                </div>
              </div>
            </div>
            <div className="flex flex-col gap-4 px-2">
              <div className="flex-1" onClick={() => setActiveTab('detail')}>
                <h4 className="font-black text-xl text-slate-800 leading-tight break-words">{event.title}</h4>
                <div className="flex items-center gap-4 mt-2">
                  <div className="flex items-center gap-1.5 text-xs text-slate-500 font-bold">
                    <MapPin size={14} className="text-emerald-500" /> {event.location}
                  </div>
                  <div className="flex items-center gap-1.5 text-xs text-slate-500 font-bold">
                    <Calendar size={14} className="text-emerald-500" /> {event.displayDate}
                  </div>
                </div>
              </div>
              <div className="flex gap-2">
                {joinedEvents.includes(event.id) ? (
                  <>
                    <button
                      className="flex-1 py-4 rounded-[20px] text-sm font-black shadow-lg transition-all active:scale-95 bg-slate-100 text-slate-500 hover:bg-slate-200"
                      onClick={() => handleJoinEvent(event.id)}
                    >
                      Batal Ikut
                    </button>
                    <button
                      className="flex-1 py-4 rounded-[20px] text-sm font-black shadow-lg transition-all active:scale-95 bg-indigo-600 text-white shadow-indigo-100 hover:bg-indigo-700 flex items-center justify-center gap-2"
                      onClick={() => setActiveForumEventId(event.id)}
                    >
                      <MessageCircle size={18} /> Forum
                    </button>
                  </>
                ) : (
                  <button
                    className="w-full py-4 rounded-[20px] text-sm font-black shadow-lg transition-all active:scale-95 bg-emerald-600 text-white shadow-emerald-100 hover:bg-emerald-700"
                    onClick={() => handleJoinEvent(event.id)}
                  >
                    Ikuti Aksi Sekarang
                  </button>
                )}
              </div>
            </div>
          </div>
        )) : (
          <div className="flex flex-col items-center justify-center py-20 text-center space-y-4 animate-in fade-in duration-500">
            <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
              <Calendar size={40} />
            </div>
            <div>
              <h4 className="font-black text-slate-800">Tidak ada kegiatan</h4>
              <p className="text-xs text-slate-400 font-medium">Coba pilih tanggal lain untuk melihat aksi komunitas.</p>
            </div>
            <button
              onClick={() => setSelectedDate("2026-03-17")}
              className="text-emerald-600 text-xs font-black uppercase tracking-widest"
            >
              Kembali ke Hari Ini
            </button>
          </div>
        )}
      </div>
    </div>
  );

  const RankingTab = () => {
    const sortedLeaderboard = [...leaderboardData].sort((a, b) => b.points - a.points).map((user, index) => ({ ...user, rank: index + 1 }));
    const top3 = sortedLeaderboard.slice(0, 3);

    return (
      <div className="h-full bg-emerald-600 flex flex-col overflow-hidden animate-in fade-in duration-300">
        <div className="px-8 pt-10 pb-10 text-center text-white shrink-0">
          <h1 className="text-2xl font-black mb-10 tracking-tight">Eco-Rankings 🏆</h1>

          <div className="flex items-end justify-center gap-6 mt-4">
            {top3[1] && (
              <div className="flex flex-col items-center">
                <div className="w-14 h-14 bg-white/20 rounded-2xl mb-3 flex items-center justify-center border-2 border-slate-300 shadow-xl backdrop-blur-md">{top3[1].avatar}</div>
                <div className="h-20 w-16 bg-white/10 rounded-t-3xl flex items-center justify-center font-black border-t-2 border-x-2 border-white/20">2</div>
                <p className="text-[10px] font-black mt-2 uppercase tracking-widest">{top3[1].name.split(' ')[0]}</p>
              </div>
            )}
            {top3[0] && (
              <div className="flex flex-col items-center">
                <div className="w-20 h-20 bg-amber-400 rounded-3xl mb-3 flex items-center justify-center border-4 border-white shadow-2xl scale-110">{top3[0].avatar}</div>
                <div className="h-32 w-20 bg-white/20 rounded-t-3xl flex items-center justify-center font-black text-2xl border-t-2 border-x-2 border-white/30">1</div>
                <p className="text-xs font-black mt-3 uppercase tracking-widest">{top3[0].name.split(' ')[0]}</p>
              </div>
            )}
            {top3[2] && (
              <div className="flex flex-col items-center">
                <div className="w-14 h-14 bg-white/20 rounded-2xl mb-3 flex items-center justify-center border-2 border-orange-400 shadow-xl backdrop-blur-md">{top3[2].avatar}</div>
                <div className="h-16 w-16 bg-white/10 rounded-t-3xl flex items-center justify-center font-black border-t-2 border-x-2 border-white/20">3</div>
                <p className="text-[10px] font-black mt-2 uppercase tracking-widest">{top3[2].name.split(' ')[0]}</p>
              </div>
            )}
          </div>
        </div>

        <div className="flex-1 bg-white rounded-t-[50px] px-8 pt-10 overflow-y-auto pb-24 shadow-[0_-20px_50px_rgba(0,0,0,0.1)] scrollbar-hide">
          <div className="space-y-4 mb-10">
            <h3 className="text-lg font-black text-slate-800 mb-4">Papan Peringkat</h3>
            {sortedLeaderboard.map(user => (
              <div
                key={user.id}
                className={`flex items-center gap-5 p-5 rounded-[28px] border-2 transition-all hover:scale-[1.02] active:scale-95 cursor-pointer ${user.rank === 1 ? 'bg-emerald-50 border-emerald-100' : 'bg-white border-slate-50'}`}
              >
                <span className={`w-8 text-lg font-black ${user.rank <= 3 ? 'text-emerald-600' : 'text-slate-300'}`}>#{user.rank}</span>
                <div className="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center font-black text-sm text-slate-700 shadow-inner">
                  {user.avatar}
                </div>
                <div className="flex-1">
                  <h4 className="font-black text-slate-800 leading-tight">{user.name}</h4>
                  <p className="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">{user.level}</p>
                </div>
                <div className="text-right">
                  <p className="text-xl font-black text-slate-800">{user.points}</p>
                  <p className="text-[10px] text-emerald-600 font-black uppercase">Pts</p>
                </div>
              </div>
            ))}
          </div>

          <div className="mb-10">
            <h3 className="text-lg font-black text-slate-800 mb-4">Aturan Poin 🎯</h3>
            <div className="space-y-3">
              {pointRules.map(rule => (
                <div key={rule.id} className="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                  <span className="text-sm font-bold text-slate-700">{rule.action}</span>
                  <span className="text-xs font-black text-emerald-600 bg-emerald-100 px-3 py-1 rounded-full">+{rule.points} Pts</span>
                </div>
              ))}
            </div>
          </div>

          <div>
            <h3 className="text-lg font-black text-slate-800 mb-4">Lencana & Pencapaian 🏅</h3>
            <div className="space-y-4">
              {badges.map(badge => (
                <div key={badge.id} className="p-6 bg-slate-900 rounded-[32px] text-white relative overflow-hidden shadow-xl">
                  <div className="relative z-10">
                    <h3 className="text-lg font-black mb-1">{badge.name}</h3>
                    <p className="text-xs text-slate-400 mb-4 font-medium">{badge.description}</p>
                    <div className="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-2">
                      <div className="h-full bg-emerald-500 rounded-full" style={{ width: '70%' }}></div>
                    </div>
                    <div className="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                      <span>Progress</span>
                      <span className="text-white">7/{badge.target}</span>
                    </div>
                  </div>
                  <div className="absolute top-1/2 right-[-10px] -translate-y-1/2 text-white/5 rotate-12 pointer-events-none">
                    {badge.icon === 'Trophy' ? <Trophy size={100} /> : <Leaf size={100} />}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    );
  };

  const AcademyTab = () => {
    const completedCount = academyModules.filter(m => m.completed).length;
    const totalModules = academyModules.length;
    const progressPercentage = Math.round((completedCount / totalModules) * 100);

    if (activeModuleId !== null) {
      const module = academyModules.find(m => m.id === activeModuleId);
      if (!module) return null;

      if (quizState === 'playing') {
        const question = module.quiz[currentQuestionIndex];
        return (
          <div className="h-full bg-slate-50 flex flex-col overflow-hidden animate-in slide-in-from-right duration-300">
            <div className="bg-white px-6 py-5 border-b border-slate-100 flex items-center gap-4 shadow-sm z-10">
              <button onClick={() => setQuizState('idle')} className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                <ArrowLeft size={20} />
              </button>
              <div>
                <h3 className="font-black text-slate-800 leading-tight">Kuis: {module.title}</h3>
                <p className="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Pertanyaan {currentQuestionIndex + 1} dari {module.quiz.length}</p>
              </div>
            </div>

            <div className="flex-1 overflow-y-auto p-6 md:p-8 space-y-8">
              <div className="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-slate-100">
                <h4 className="text-xl font-black text-slate-800 mb-8 leading-relaxed">{question.question}</h4>
                <div className="space-y-3">
                  {question.options.map((option, idx) => (
                    <button
                      key={idx}
                      onClick={() => setSelectedAnswer(idx)}
                      className={`w-full text-left p-5 rounded-2xl border-2 font-bold transition-all ${selectedAnswer === idx ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-100 bg-white text-slate-600 hover:border-slate-200'}`}
                    >
                      {option}
                    </button>
                  ))}
                </div>
              </div>
            </div>

            <div className="bg-white p-6 border-t border-slate-100">
              <button
                disabled={selectedAnswer === null}
                onClick={() => {
                  if (selectedAnswer === question.answer) {
                    setQuizScore(prev => prev + 1);
                  }
                  if (currentQuestionIndex < module.quiz.length - 1) {
                    setCurrentQuestionIndex(prev => prev + 1);
                    setSelectedAnswer(null);
                  } else {
                    setQuizState('result');
                  }
                }}
                className="w-full bg-emerald-600 text-white py-4 rounded-[20px] font-black shadow-lg shadow-emerald-100 disabled:opacity-50 disabled:shadow-none transition-all active:scale-95"
              >
                {currentQuestionIndex < module.quiz.length - 1 ? 'Pertanyaan Selanjutnya' : 'Selesai Kuis'}
              </button>
            </div>
          </div>
        );
      }

      if (quizState === 'result') {
        const isPassed = quizScore === module.quiz.length;
        return (
          <div className="h-full bg-slate-50 flex flex-col items-center justify-center p-8 text-center animate-in zoom-in-95 duration-500">
            <div className={`w-32 h-32 rounded-[40px] flex items-center justify-center mb-8 border-4 shadow-inner ${isPassed ? 'bg-emerald-100 border-emerald-50 text-emerald-600' : 'bg-amber-100 border-amber-50 text-amber-600'}`}>
              {isPassed ? <Trophy size={64} /> : <AlertTriangle size={64} />}
            </div>
            <h2 className="text-3xl font-black text-slate-800 mb-3">{isPassed ? 'Luar Biasa!' : 'Coba Lagi!'}</h2>
            <p className="text-slate-500 text-sm mb-10 leading-relaxed font-medium">
              Kamu menjawab {quizScore} dari {module.quiz.length} pertanyaan dengan benar.
              {isPassed && <><br /><strong className="text-emerald-600 mt-2 block">+{module.points} Poin didapatkan!</strong></>}
            </p>
            <div className="space-y-3 w-full max-w-xs">
              {isPassed ? (
                <button
                  className="bg-emerald-600 text-white px-10 py-4 rounded-[24px] font-bold w-full shadow-xl shadow-emerald-100 active:scale-95 transition-all"
                  onClick={() => {
                    setAcademyModules(modules => modules.map(m => m.id === module.id ? { ...m, completed: true } : m));
                    setActiveModuleId(null);
                    setQuizState('idle');
                  }}
                >
                  Kembali ke Akademi
                </button>
              ) : (
                <button
                  className="bg-amber-500 text-white px-10 py-4 rounded-[24px] font-bold w-full shadow-xl shadow-amber-100 active:scale-95 transition-all"
                  onClick={() => {
                    setQuizScore(0);
                    setCurrentQuestionIndex(0);
                    setSelectedAnswer(null);
                    setQuizState('playing');
                  }}
                >
                  Ulangi Kuis
                </button>
              )}
              {!isPassed && (
                <button
                  className="bg-slate-200 text-slate-700 px-10 py-4 rounded-[24px] font-bold w-full active:scale-95 transition-all"
                  onClick={() => {
                    setActiveModuleId(null);
                    setQuizState('idle');
                  }}
                >
                  Kembali
                </button>
              )}
            </div>
          </div>
        );
      }

      return (
        <div className="h-full bg-slate-50 flex flex-col overflow-hidden animate-in slide-in-from-right duration-300">
          <div className="bg-white px-6 py-5 border-b border-slate-100 flex items-center gap-4 shadow-sm z-10">
            <button onClick={() => setActiveModuleId(null)} className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
              <ArrowLeft size={20} />
            </button>
            <div>
              <h3 className="font-black text-slate-800 leading-tight">Materi Edukasi</h3>
            </div>
          </div>

          <div className="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 scrollbar-hide">
            <div>
              <div className="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full mb-4 uppercase tracking-widest">
                <BookOpen size={12} /> Modul Pembelajaran
              </div>
              <h2 className="text-3xl font-black text-slate-800 leading-tight mb-4">{module.title}</h2>
              <p className="text-slate-500 font-medium leading-relaxed">{module.description}</p>
            </div>

            <div className="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-slate-100">
              <p className="text-slate-700 leading-loose font-medium">{module.content}</p>
            </div>
          </div>

          <div className="bg-white p-6 border-t border-slate-100">
            <button
              onClick={() => {
                setQuizScore(0);
                setCurrentQuestionIndex(0);
                setSelectedAnswer(null);
                setQuizState('playing');
              }}
              className="w-full bg-indigo-600 text-white py-4 rounded-[20px] font-black shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-2"
            >
              <Trophy size={18} /> Mulai Kuis Evaluasi
            </button>
          </div>
        </div>
      );
    }

    return (
      <div className="px-8 py-8 space-y-8 overflow-y-auto pb-24 h-full animate-in fade-in duration-300 scrollbar-hide">
        <div className="flex justify-between items-center">
          <h1 className="text-3xl font-black text-slate-800">Akademi 📚</h1>
        </div>

        {/* Progress Tracker */}
        <div className="bg-slate-900 p-6 md:p-8 rounded-[32px] text-white relative overflow-hidden shadow-xl">
          <div className="relative z-10">
            <h3 className="text-lg font-black mb-1">Progress Belajar</h3>
            <p className="text-xs text-slate-400 mb-6 font-medium">Selesaikan modul untuk mendapatkan poin.</p>
            <div className="w-full h-3 bg-white/10 rounded-full overflow-hidden mb-2 shadow-inner">
              <div className="h-full bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all duration-1000" style={{ width: `${progressPercentage}%` }}></div>
            </div>
            <div className="flex justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest">
              <span>{progressPercentage}% Selesai</span>
              <span className="text-white">{completedCount}/{totalModules} Modul</span>
            </div>
          </div>
          <div className="absolute top-1/2 right-[-10px] -translate-y-1/2 text-white/5 rotate-12 pointer-events-none">
            <BookOpen size={120} />
          </div>
        </div>

        {/* Modules List */}
        <div>
          <h3 className="text-xl font-black text-slate-800 mb-4">Materi Tersedia</h3>
          <div className="space-y-4">
            {academyModules.map(module => (
              <div
                key={module.id}
                onClick={() => setActiveModuleId(module.id)}
                className="bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm flex gap-5 items-center cursor-pointer hover:border-emerald-200 hover:shadow-md transition-all group"
              >
                <div className={`w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-inner ${module.completed ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-50 text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500'}`}>
                  {module.completed ? <CheckCircle2 size={24} /> : <BookOpen size={24} />}
                </div>
                <div className="flex-1">
                  <h4 className="font-black text-slate-800 leading-tight mb-1">{module.title}</h4>
                  <p className="text-xs text-slate-500 font-medium line-clamp-1">{module.description}</p>
                </div>
                <div className="text-right shrink-0">
                  <div className="inline-flex items-center gap-1 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                    <Star size={10} /> +{module.points}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  };

  const AdminReportsTab = () => {
    return (
      <div className="h-full overflow-y-auto scrollbar-hide bg-slate-50">
        <div className="p-6 md:p-8 space-y-8 max-w-5xl mx-auto">
          <div className="flex items-center justify-between">
            <h2 className="text-3xl font-black text-slate-800 tracking-tight">Kelola Laporan (Admin) 📋</h2>
          </div>

          <div className="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-100 text-xs font-black text-slate-500 uppercase tracking-widest">
                    <th className="p-4">Tanggal</th>
                    <th className="p-4">Judul & Kategori</th>
                    <th className="p-4">Lokasi</th>
                    <th className="p-4">Status</th>
                    <th className="p-4 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody className="text-sm">
                  {reports.map(report => (
                    <tr key={report.id} className="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                      <td className="p-4 text-slate-500 font-medium">{report.date}</td>
                      <td className="p-4">
                        <p className="font-bold text-slate-800">{report.title}</p>
                        <p className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{report.category}</p>
                      </td>
                      <td className="p-4 text-slate-500 font-medium">
                        {report.location.lat.toFixed(3)}, {report.location.lng.toFixed(3)}
                      </td>
                      <td className="p-4">
                        <span className={`px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ${report.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                            report.status === 'verified' ? 'bg-blue-100 text-blue-700' :
                              report.status === 'resolved' ? 'bg-emerald-100 text-emerald-700' :
                                'bg-rose-100 text-rose-700'
                          }`}>
                          {report.status === 'pending' ? 'Menunggu' :
                            report.status === 'verified' ? 'Diverifikasi' :
                              report.status === 'resolved' ? 'Selesai' : 'Ditolak'}
                        </span>
                      </td>
                      <td className="p-4 text-right">
                        <select
                          value={report.status}
                          onChange={(e) => {
                            setReports(reports.map(r => r.id === report.id ? { ...r, status: e.target.value } : r));
                          }}
                          className="p-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-emerald-500 cursor-pointer"
                        >
                          <option value="pending">Menunggu</option>
                          <option value="verified">Verifikasi</option>
                          <option value="resolved">Selesai</option>
                          <option value="rejected">Tolak</option>
                        </select>
                        <button
                          onClick={() => setReports(reports.filter(r => r.id !== report.id))}
                          className="ml-2 p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors"
                        >
                          <X size={16} />
                        </button>
                      </td>
                    </tr>
                  ))}
                  {reports.length === 0 && (
                    <tr>
                      <td colSpan={5} className="p-8 text-center text-slate-400 font-medium">Belum ada laporan.</td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    );
  };

  const AdminRankingTab = () => {
    const [activeSection, setActiveSection] = useState<'rules' | 'levels' | 'badges'>('rules');

    return (
      <div className="h-full overflow-y-auto scrollbar-hide bg-slate-50">
        <div className="p-6 md:p-8 space-y-8 max-w-5xl mx-auto">
          <div className="flex items-center justify-between">
            <h2 className="text-3xl font-black text-slate-800 tracking-tight">Kelola Peringkat (Admin) 🏆</h2>
          </div>

          <div className="flex gap-2 p-1 bg-slate-200/50 rounded-2xl w-fit">
            <button
              onClick={() => setActiveSection('rules')}
              className={`px-6 py-2.5 rounded-xl text-sm font-bold transition-all ${activeSection === 'rules' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Aturan Poin
            </button>
            <button
              onClick={() => setActiveSection('levels')}
              className={`px-6 py-2.5 rounded-xl text-sm font-bold transition-all ${activeSection === 'levels' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Level Eco
            </button>
            <button
              onClick={() => setActiveSection('badges')}
              className={`px-6 py-2.5 rounded-xl text-sm font-bold transition-all ${activeSection === 'badges' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Lencana
            </button>
          </div>

          <div className="bg-white rounded-[32px] shadow-sm border border-slate-100 p-6 md:p-8">
            {activeSection === 'rules' && (
              <div className="space-y-6">
                <div className="flex justify-between items-center">
                  <h3 className="text-xl font-black text-slate-800">Aturan Poin</h3>
                  <button
                    onClick={() => setPointRules([...pointRules, { id: Date.now(), action: "Aturan Baru", points: 0 }])}
                    className="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-200 transition-colors"
                  >
                    + Tambah Aturan
                  </button>
                </div>
                <div className="space-y-4">
                  {pointRules.map(rule => (
                    <div key={rule.id} className="flex gap-4 items-center bg-slate-50 p-4 rounded-2xl border border-slate-100">
                      <input
                        type="text"
                        value={rule.action}
                        onChange={(e) => setPointRules(pointRules.map(r => r.id === rule.id ? { ...r, action: e.target.value } : r))}
                        className="flex-1 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                        placeholder="Nama Aksi"
                      />
                      <input
                        type="number"
                        value={rule.points}
                        onChange={(e) => setPointRules(pointRules.map(r => r.id === rule.id ? { ...r, points: parseInt(e.target.value) || 0 } : r))}
                        className="w-24 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                        placeholder="Poin"
                      />
                      <button
                        onClick={() => setPointRules(pointRules.filter(r => r.id !== rule.id))}
                        className="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors"
                      >
                        <X size={20} />
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeSection === 'levels' && (
              <div className="space-y-6">
                <div className="flex justify-between items-center">
                  <h3 className="text-xl font-black text-slate-800">Level Eco</h3>
                  <button
                    onClick={() => setEcoLevels([...ecoLevels, { id: Date.now(), name: "Level Baru", minPoints: 0, description: "Deskripsi level", icon: "🌟" }])}
                    className="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-200 transition-colors"
                  >
                    + Tambah Level
                  </button>
                </div>
                <div className="space-y-4">
                  {ecoLevels.sort((a, b) => a.minPoints - b.minPoints).map(level => (
                    <div key={level.id} className="flex flex-col gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                      <div className="flex gap-4 items-center">
                        <input
                          type="text"
                          value={level.icon}
                          onChange={(e) => setEcoLevels(ecoLevels.map(l => l.id === level.id ? { ...l, icon: e.target.value } : l))}
                          className="w-16 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500 text-center"
                          placeholder="Ikon"
                        />
                        <input
                          type="text"
                          value={level.name}
                          onChange={(e) => setEcoLevels(ecoLevels.map(l => l.id === level.id ? { ...l, name: e.target.value } : l))}
                          className="flex-1 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                          placeholder="Nama Level"
                        />
                        <div className="flex items-center gap-2">
                          <span className="text-xs font-bold text-slate-500">Min. Poin:</span>
                          <input
                            type="number"
                            value={level.minPoints}
                            onChange={(e) => setEcoLevels(ecoLevels.map(l => l.id === level.id ? { ...l, minPoints: parseInt(e.target.value) || 0 } : l))}
                            className="w-24 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                            placeholder="Poin"
                          />
                        </div>
                        <button
                          onClick={() => setEcoLevels(ecoLevels.filter(l => l.id !== level.id))}
                          className="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors"
                        >
                          <X size={20} />
                        </button>
                      </div>
                      <input
                        type="text"
                        value={level.description}
                        onChange={(e) => setEcoLevels(ecoLevels.map(l => l.id === level.id ? { ...l, description: e.target.value } : l))}
                        className="w-full p-3 bg-white rounded-xl border border-slate-200 text-sm font-medium outline-none focus:border-emerald-500"
                        placeholder="Deskripsi Level"
                      />
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeSection === 'badges' && (
              <div className="space-y-6">
                <div className="flex justify-between items-center">
                  <h3 className="text-xl font-black text-slate-800">Lencana & Pencapaian</h3>
                  <button
                    onClick={() => setBadges([...badges, { id: Date.now(), name: "Lencana Baru", description: "Deskripsi", target: 10, icon: "Trophy" }])}
                    className="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-200 transition-colors"
                  >
                    + Tambah Lencana
                  </button>
                </div>
                <div className="space-y-4">
                  {badges.map(badge => (
                    <div key={badge.id} className="flex flex-col gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                      <div className="flex gap-4 items-center">
                        <input
                          type="text"
                          value={badge.name}
                          onChange={(e) => setBadges(badges.map(b => b.id === badge.id ? { ...b, name: e.target.value } : b))}
                          className="flex-1 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                          placeholder="Nama Lencana"
                        />
                        <select
                          value={badge.icon}
                          onChange={(e) => setBadges(badges.map(b => b.id === badge.id ? { ...b, icon: e.target.value } : b))}
                          className="p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                        >
                          <option value="Trophy">Trophy</option>
                          <option value="Leaf">Leaf</option>
                          <option value="Star">Star</option>
                        </select>
                        <input
                          type="number"
                          value={badge.target}
                          onChange={(e) => setBadges(badges.map(b => b.id === badge.id ? { ...b, target: parseInt(e.target.value) || 1 } : b))}
                          className="w-24 p-3 bg-white rounded-xl border border-slate-200 text-sm font-bold outline-none focus:border-emerald-500"
                          placeholder="Target"
                        />
                        <button
                          onClick={() => setBadges(badges.filter(b => b.id !== badge.id))}
                          className="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors"
                        >
                          <X size={20} />
                        </button>
                      </div>
                      <input
                        type="text"
                        value={badge.description}
                        onChange={(e) => setBadges(badges.map(b => b.id === badge.id ? { ...b, description: e.target.value } : b))}
                        className="w-full p-3 bg-white rounded-xl border border-slate-200 text-sm font-medium outline-none focus:border-emerald-500"
                        placeholder="Deskripsi Lencana"
                      />
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    );
  };

  // --- TAB RENDERER ---
  const renderTab = () => {
    switch (activeTab) {
      case 'home': return <HomeTab />;
      case 'map': return <MapTab />;
      case 'report': return <ReportTab />;
      case 'community': return <CommunityTab />;
      case 'ranking': return <RankingTab />;
      case 'academy': return <AcademyTab />;
      case 'detail': return <DetailTab location={selectedLocation} />;
      case 'profile': return <ProfileTab />;
      case 'admin-reports': return <AdminReportsTab />;
      case 'admin-ranking': return <AdminRankingTab />;
      default: return <HomeTab />;
    }
  };

  // --- MAIN RENDER ---

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900 selection:bg-emerald-100 selection:text-emerald-900">
      {/* Desktop & Mobile Navbar */}
      <nav className="sticky top-0 z-[100] bg-white/80 backdrop-blur-xl border-b border-slate-200/60">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between h-20 items-center">
            {/* Logo */}
            <div
              className="flex items-center gap-3 cursor-pointer group"
              onClick={() => { setActiveTab('home'); setReportStatus('idle'); }}
            >
              <div className="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 group-hover:rotate-12 transition-transform duration-300">
                <Leaf size={24} />
              </div>
              <span className="text-xl font-black tracking-tighter text-slate-800">Green<span className="text-emerald-600">Tour</span></span>
            </div>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center gap-1 lg:gap-4">
              <NavButton active={activeTab === 'home' || activeTab === 'map' || activeTab === 'detail'} label="Beranda" onClick={() => setActiveTab('home')} />
              <NavButton active={activeTab === 'community'} label="Aksi" onClick={() => setActiveTab('community')} />
              <NavButton active={activeTab === 'ranking'} label="Peringkat" onClick={() => setActiveTab('ranking')} />
              <NavButton active={activeTab === 'academy'} label="Akademi" onClick={() => setActiveTab('academy')} />

              <div className="h-6 w-px bg-slate-200 mx-2"></div>

              <button
                onClick={() => { setActiveTab('report'); setReportStatus('idle'); }}
                className="flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:translate-y-0"
              >
                <Camera size={18} />
                Lapor Isu
              </button>

              <button
                onClick={() => setActiveTab('profile')}
                className={`ml-4 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all ${activeTab === 'profile' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-emerald-100 text-emerald-700 border-emerald-500 hover:scale-110'}`}
              >
                {userProfile.avatar}
              </button>
              {isAdmin && (
                <>
                  <button
                    onClick={() => setActiveTab('admin-reports')}
                    className={`ml-2 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all ${activeTab === 'admin-reports' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-indigo-100 text-indigo-700 border-indigo-500 hover:scale-110'}`}
                    title="Kelola Laporan"
                  >
                    <Camera size={16} />
                  </button>
                  <button
                    onClick={() => setActiveTab('admin-ranking')}
                    className={`ml-2 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all ${activeTab === 'admin-ranking' ? 'bg-amber-500 text-white border-amber-500' : 'bg-amber-100 text-amber-700 border-amber-400 hover:scale-110'}`}
                    title="Kelola Peringkat"
                  >
                    <Trophy size={16} />
                  </button>
                </>
              )}
            </div>

            {/* Mobile Menu Button */}
            <div className="md:hidden flex items-center gap-4">
              <button
                onClick={() => { setActiveTab('report'); setReportStatus('idle'); }}
                className="p-2.5 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-100"
              >
                <Camera size={20} />
              </button>
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className="p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors"
              >
                {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Navigation Menu */}
        {isMenuOpen && (
          <div className="md:hidden bg-white border-b border-slate-100 animate-in slide-in-from-top duration-300">
            <div className="px-4 pt-2 pb-6 space-y-1">
              <MobileNavItem active={activeTab === 'home' || activeTab === 'map' || activeTab === 'detail'} label="Beranda" icon={<Home size={20} />} onClick={() => { setActiveTab('home'); setIsMenuOpen(false); }} />
              <MobileNavItem active={activeTab === 'community'} label="Aksi" icon={<Users size={20} />} onClick={() => { setActiveTab('community'); setIsMenuOpen(false); }} />
              <MobileNavItem active={activeTab === 'ranking'} label="Peringkat" icon={<Trophy size={20} />} onClick={() => { setActiveTab('ranking'); setIsMenuOpen(false); }} />
              <MobileNavItem active={activeTab === 'academy'} label="Akademi" icon={<BookOpen size={20} />} onClick={() => { setActiveTab('academy'); setIsMenuOpen(false); }} />
              <div className="pt-4 border-t border-slate-50">
                {isAdmin && (
                  <>
                    <button
                      onClick={() => { setActiveTab('admin-reports'); setIsMenuOpen(false); }}
                      className={`flex items-center gap-3 w-full p-4 rounded-2xl font-bold mb-2 ${activeTab === 'admin-reports' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-50 text-slate-700'}`}
                    >
                      <div className={`w-8 h-8 rounded-lg flex items-center justify-center text-xs ${activeTab === 'admin-reports' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700'}`}>
                        <Camera size={16} />
                      </div>
                      Kelola Laporan
                    </button>
                    <button
                      onClick={() => { setActiveTab('admin-ranking'); setIsMenuOpen(false); }}
                      className={`flex items-center gap-3 w-full p-4 rounded-2xl font-bold mb-2 ${activeTab === 'admin-ranking' ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-700'}`}
                    >
                      <div className={`w-8 h-8 rounded-lg flex items-center justify-center text-xs ${activeTab === 'admin-ranking' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700'}`}>
                        <Trophy size={16} />
                      </div>
                      Kelola Peringkat
                    </button>
                  </>
                )}
                <button
                  onClick={() => { setActiveTab('profile'); setIsMenuOpen(false); }}
                  className={`flex items-center gap-3 w-full p-4 rounded-2xl font-bold ${activeTab === 'profile' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-700'}`}
                >
                  <div className={`w-8 h-8 rounded-lg flex items-center justify-center text-xs ${activeTab === 'profile' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-700'}`}>
                    {userProfile.avatar}
                  </div>
                  Profil Saya
                </button>
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Main Content Area */}
      <main className={
        activeTab === 'home' || activeTab === 'map'
          ? "relative z-0 w-full h-[calc(100vh-80px)]"
          : "relative z-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12"
      }>
        <div className={
          activeTab === 'home' || activeTab === 'map' || activeTab === 'profile' || activeTab === 'admin-reports' || activeTab === 'admin-ranking'
            ? "relative h-full w-full bg-white overflow-hidden"
            : "relative h-[700px] bg-white rounded-[40px] shadow-sm overflow-hidden border border-slate-200"
        }>
          {renderTab()}
          {showDatePicker && <DatePickerModal />}
          {showCommunityDetail && <CommunityDetailModal />}
          {activeForumEventId !== null && <ForumModal />}
        </div>
      </main>

      {/* Footer */}
      {activeTab !== 'home' && activeTab !== 'map' && activeTab !== 'profile' && activeTab !== 'admin-reports' && activeTab !== 'admin-ranking' && (
        <footer className="bg-white border-t border-slate-200 py-12">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-12">
              <div className="col-span-1 md:col-span-2">
                <div className="flex items-center gap-3 mb-6">
                  <div className="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white">
                    <Leaf size={20} />
                  </div>
                  <span className="text-lg font-black tracking-tighter text-slate-800">GreenTour</span>
                </div>
                <p className="text-slate-500 max-w-sm leading-relaxed">
                  Peta wisata interaktif untuk merencanakan liburan ramah lingkungan, memetakan lokasi wisata, data ekologi, dan program konservasi.
                </p>
              </div>
              <div>
                <h4 className="font-bold text-slate-800 mb-6">Navigasi</h4>
                <ul className="space-y-4 text-slate-500 font-medium">
                  <li><button onClick={() => setActiveTab('home')} className="hover:text-emerald-600 transition-colors">Beranda</button></li>
                  <li><button onClick={() => setActiveTab('community')} className="hover:text-emerald-600 transition-colors">Aksi Komunitas</button></li>
                </ul>
              </div>
              <div>
                <h4 className="font-bold text-slate-800 mb-6">Bantuan</h4>
                <ul className="space-y-4 text-slate-500 font-medium">
                  <li><button className="hover:text-emerald-600 transition-colors">Pusat Edukasi</button></li>
                  <li><button className="hover:text-emerald-600 transition-colors">Hubungi Kami</button></li>
                  <li><button className="hover:text-emerald-600 transition-colors">Kebijakan Privasi</button></li>
                </ul>
              </div>
            </div>
            <div className="mt-12 pt-8 border-t border-slate-100 text-center text-slate-400 text-sm font-medium">
              © 2026 GreenTour Indonesia. Dibuat dengan ❤️ untuk Bumi.
            </div>
          </div>
        </footer>
      )}
    </div>
  );
};

// --- HELPER COMPONENTS ---

const NavButton = ({ active, label, onClick }: { active: boolean, label: string, onClick: () => void }) => (
  <button
    onClick={onClick}
    className={`px-4 py-2 rounded-xl text-sm font-bold transition-all ${active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'}`}
  >
    {label}
  </button>
);

const MobileNavItem = ({ active, label, icon, onClick }: { active: boolean, label: string, icon: React.ReactNode, onClick: () => void }) => (
  <button
    onClick={onClick}
    className={`flex items-center gap-4 w-full p-4 rounded-2xl transition-all ${active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50'}`}
  >
    <div className={`${active ? 'text-emerald-600' : 'text-slate-400'}`}>
      {icon}
    </div>
    <span className="font-bold">{label}</span>
  </button>
);

export default App;
