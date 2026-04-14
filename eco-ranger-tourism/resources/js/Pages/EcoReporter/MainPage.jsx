import React, { useState } from 'react';
import axios from 'axios';
import { useReportStore } from '../../Store/useReportStore';
import UploadBox from './Components/UploadBox';
import MapPicker from './Components/MapPicker';
import SuccessPage from './Components/SuccessPage';
import { MapPin, ChevronDown, Loader2 } from 'lucide-react';

export default function MainPage() {
    const { step, setStep, reportData, updateReportData } = useReportStore();
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});

    if (step === 'map') return <MapPicker />;
    if (step === 'success') return <SuccessPage />;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({});

        const newErrors = {};
        if (!reportData.title) newErrors.title = 'Judul wajib diisi';
        if (!reportData.location) newErrors.location = 'Lokasi wajib dipilih di peta';
        if (!reportData.category) newErrors.category = 'Kategori wajib dipilih';
        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors);
            return;
        }

        setLoading(true);
        try {
            const formData = new FormData();
            formData.append('title', reportData.title);
            formData.append('latitude', reportData.location.lat);
            formData.append('longitude', reportData.location.lng);
            formData.append('category', reportData.category);
            formData.append('description', reportData.description || '');
            if (reportData.imageFile) {
                formData.append('photo', reportData.imageFile);
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            await axios.post('/pelaporan', formData, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'multipart/form-data',
                },
            });

            setStep('success');
        } catch (err) {
            if (err.response?.data?.errors) {
                setErrors(err.response.data.errors);
            } else {
                setErrors({ general: 'Terjadi kesalahan. Silakan coba lagi.' });
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="w-full max-w-4xl mx-auto px-4 py-8">
            <div className="bg-white rounded-3xl shadow-[0_4px_30px_rgba(0,0,0,0.05)] border border-gray-100 p-8 sm:p-10 mb-8">
                
                {/* HERO SECTION */}
                <div className="mb-10">
                    <h1 className="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 flex items-center gap-3">
                        Eco-Reporter
                    </h1>
                    <p className="text-gray-500 text-lg">
                        Menemukan kerusakan atau penumpukan sampah? Laporkan untuk tindakan segera.
                    </p>
                </div>

                {/* Error message umum */}
                {errors.general && (
                    <div className="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        {errors.general}
                    </div>
                )}

                {/* FORM SECTION */}
                <form onSubmit={handleSubmit} className="space-y-8">
                    
                    {/* AREA UPLOAD FOTO */}
                    <div className="w-full">
                        <UploadBox />
                    </div>

                    <div className="space-y-6 pt-4 border-t border-gray-100">
                        {/* 1. Judul Laporan */}
                        <div>
                            <label className="block text-sm font-bold text-gray-400 tracking-widest uppercase mb-2">
                                Judul Laporan
                            </label>
                            <input 
                                type="text"
                                value={reportData.title}
                                onChange={(e) => updateReportData({ title: e.target.value })}
                                placeholder="Contoh: Tumpukan Sampah Plastik"
                                className={`w-full h-14 px-4 bg-white rounded-xl border ${errors.title ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200'} focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition font-medium`}
                            />
                            {errors.title && <p className="text-red-500 text-sm mt-1">{errors.title}</p>}
                        </div>

                        {/* 2. Lokasi Kejadian */}
                        <div>
                            <label className="block text-sm font-bold text-gray-400 tracking-widest uppercase mb-2">
                                Lokasi Kejadian
                            </label>
                            <button
                                type="button"
                                onClick={() => setStep('map')}
                                className={`w-full h-14 px-4 bg-white rounded-xl border ${errors.location ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200'} text-left flex items-center justify-between hover:border-green-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition group`}
                            >
                                <div className="flex items-center gap-3">
                                    <MapPin className={`${reportData.location ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500'} transition`} size={20} />
                                    <span className={`font-medium ${reportData.location ? 'text-gray-800' : 'text-gray-500'}`}>
                                        {reportData.location 
                                            ? `Lat: ${reportData.location.lat.toFixed(4)}, Lng: ${reportData.location.lng.toFixed(4)}` 
                                            : 'Pilih lokasi di peta'}
                                    </span>
                                </div>
                            </button>
                            {errors.location && <p className="text-red-500 text-sm mt-1">{errors.location}</p>}
                        </div>

                        {/* 3. Kategori Masalah */}
                        <div>
                            <label className="block text-sm font-bold text-gray-400 tracking-widest uppercase mb-2">
                                Kategori Masalah
                            </label>
                            <div className="relative">
                                <select 
                                    value={reportData.category}
                                    onChange={(e) => updateReportData({ category: e.target.value })}
                                    className={`w-full h-14 px-4 bg-white rounded-xl border ${errors.category ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200'} focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition font-medium appearance-none`}
                                >
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="Masalah Laut">Masalah Laut</option>
                                    <option value="Masalah Darat">Masalah Darat</option>
                                    <option value="Masalah Lingkungan">Masalah Lingkungan</option>
                                </select>
                                <div className="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <ChevronDown size={20} />
                                </div>
                            </div>
                            {errors.category && <p className="text-red-500 text-sm mt-1">{errors.category}</p>}
                        </div>

                        {/* 4. Keterangan Tambahan */}
                        <div>
                            <label className="block text-sm font-bold text-gray-400 tracking-widest uppercase mb-2">
                                Keterangan Tambahan
                            </label>
                            <textarea
                                value={reportData.description}
                                onChange={(e) => updateReportData({ description: e.target.value })}
                                placeholder="Jelaskan apa yang kamu temukan..."
                                className="w-full p-4 bg-white rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition font-medium min-h-[140px] resize-y"
                            ></textarea>
                        </div>
                    </div>

                    <div className="pt-6">
                        <button 
                            type="submit"
                            disabled={loading}
                            className="w-full bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-bold text-lg py-4 rounded-xl shadow-[0_4px_14px_0_rgba(22,163,74,0.39)] transition-all hover:shadow-[0_6px_20px_rgba(22,163,74,0.23)] hover:-translate-y-[1px] flex justify-center items-center gap-3"
                        >
                            {loading ? (
                                <>
                                    <Loader2 size={20} className="animate-spin" />
                                    Mengirim...
                                </>
                            ) : 'Kirim Laporan'}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    );
}
