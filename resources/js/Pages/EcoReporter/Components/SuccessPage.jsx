import React from 'react';
import { Check } from 'lucide-react';
import { useReportStore } from '../../../Store/useReportStore';

export default function SuccessPage() {
    const { resetReport } = useReportStore();

    return (
        <div className="w-full flex flex-col items-center justify-center py-16 px-4">
            <div className="w-24 h-24 bg-green-100 rounded-3xl flex items-center justify-center mb-8 relative">
                {/* Decorative dots / background pulse could go here */}
                <div className="absolute inset-0 bg-green-200 animate-ping rounded-3xl opacity-20"></div>
                <div className="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-green-500/30 z-10">
                    <Check size={32} strokeWidth={3} />
                </div>
            </div>
            
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center">Berhasil Terkirim!</h2>
            
            <p className="text-gray-500 text-center max-w-md text-base leading-relaxed mb-10">
                Terima kasih, Eco-Ranger! Laporanmu sangat berharga bagi kelestarian alam kita. Kamu mendapatkan <span className="text-green-600 font-semibold">+10 Poin Dasar</span>.
            </p>
            
            <button 
                onClick={resetReport}
                className="w-full max-w-sm bg-gray-900 text-white py-4 rounded-xl font-semibold text-lg hover:bg-gray-800 transition shadow-lg shadow-gray-900/20"
            >
                Kembali ke Beranda
            </button>
        </div>
    );
}
