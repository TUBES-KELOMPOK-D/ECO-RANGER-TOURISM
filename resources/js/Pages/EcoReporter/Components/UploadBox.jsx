import React, { useCallback, useState } from 'react';
import { Camera, Image as ImageIcon } from 'lucide-react';
import { useReportStore } from '../../../Store/useReportStore';

export default function UploadBox() {
    const { reportData, updateReportData } = useReportStore();
    const [dragActive, setDragActive] = useState(false);

    const handleDrag = useCallback((e) => {
        e.preventDefault();
        e.stopPropagation();
        if (e.type === 'dragenter' || e.type === 'dragover') {
            setDragActive(true);
        } else if (e.type === 'dragleave') {
            setDragActive(false);
        }
    }, []);

    const handleDrop = useCallback((e) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleFile(e.dataTransfer.files[0]);
        }
    }, []);

    const handleChange = (e) => {
        e.preventDefault();
        if (e.target.files && e.target.files[0]) {
            handleFile(e.target.files[0]);
        }
    };

    const handleFile = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            updateReportData({ 
                imagePreview: e.target.result,
                imageFile: file,   // simpan file asli untuk dikirim ke server
            });
        };
        reader.readAsDataURL(file);
    };

    return (
        <div 
            className={`w-full rounded-2xl border-2 border-dashed transition-all duration-200 p-8 flex flex-col items-center justify-center cursor-pointer min-h-[200px]
                ${dragActive 
                    ? 'border-green-500 bg-green-50' 
                    : 'border-gray-300 hover:border-green-400 hover:bg-green-50'}
                ${reportData.imagePreview ? 'p-2' : ''}
            `}
            onDragEnter={handleDrag}
            onDragLeave={handleDrag}
            onDragOver={handleDrag}
            onDrop={handleDrop}
            onClick={() => document.getElementById('photo-upload').click()}
        >
            <input 
                type="file" 
                id="photo-upload" 
                className="hidden" 
                accept="image/*"
                onChange={handleChange}
            />
            
            {reportData.imagePreview ? (
                <div className="relative w-full h-48 md:h-64 rounded-xl overflow-hidden group">
                    <img src={reportData.imagePreview} alt="Preview" className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span className="text-white font-medium flex items-center gap-2">
                            <Camera size={20} /> Ganti Foto
                        </span>
                    </div>
                </div>
            ) : (
                <div className="text-center">
                    <div className="bg-gray-100 p-4 rounded-full inline-block mb-3">
                        <ImageIcon className="text-gray-400" size={32} />
                    </div>
                    <p className="text-gray-600 font-medium mb-1">Klik atau drag foto ke sini</p>
                    <p className="text-gray-400 text-sm">JPG, PNG maks 5MB</p>
                </div>
            )}
        </div>
    );
}
