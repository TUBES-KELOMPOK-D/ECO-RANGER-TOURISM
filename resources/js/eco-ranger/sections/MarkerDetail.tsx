import React, { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import { motion } from 'framer-motion';

export default function MarkerDetail() {
  const [isFavorite, setIsFavorite] = useState(false);
  const [rating, setRating] = useState(0);
  const [reviewText, setReviewText] = useState('');
  
  // Data passed from Blade
  const data = (window as any).markerDetailData || {};
  const {
    marker = {},
    weather = null,
    weatherDescription = '',
    lat = 0,
    lng = 0,
    reviews = [],
    totalReviews = 0,
    averageRating = 0,
    starDistribution = { 1:0, 2:0, 3:0, 4:0, 5:0 },
    userHasReviewed = false,
    user = null,
    csrfToken = '',
    loginUrl = '/login',
    backUrl = '/',
    reviewStoreUrl = '',
    sessionSuccess = '',
    sessionError = ''
  } = data;

  const [showAlert, setShowAlert] = useState(!!sessionSuccess);

  useEffect(() => {
    if (sessionSuccess) {
      const timer = setTimeout(() => setShowAlert(false), 5000);
      return () => clearTimeout(timer);
    }
  }, [sessionSuccess]);

  const toggleFavorite = () => {
    setIsFavorite(!isFavorite);
  };

  const shareLocation = async () => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: marker.location_name || marker.title || 'Lokasi Wisata',
          text: 'Lihat destinasi eco-tourism ini!',
          url: window.location.href
        });
      } catch (err) {
        // Ignored
      }
    } else {
      navigator.clipboard.writeText(window.location.href);
      alert('URL tersalin!');
    }
  };

  const categoryLabel = marker.category || 'Terjaga';
  let badgeClass = 'bg-emerald-400';
  if (marker.status === 'red') badgeClass = 'bg-rose-500';
  if (marker.status === 'yellow') badgeClass = 'bg-amber-500';

  const fullStars = Math.floor(marker.eco_health_score ? marker.eco_health_score / 2 : 0);
  const halfStar = (marker.eco_health_score ? marker.eco_health_score / 2 : 0) - fullStars >= 0.5;

  return (
    <div className="bg-[#F8FAF9] min-h-screen font-sans text-slate-800 antialiased overflow-x-hidden">
      <Navbar />

      <main className="min-h-screen">
        {/* Hero Section */}
        <div className="relative w-full h-[480px] bg-gradient-to-br from-[#10b981] to-[#047857] overflow-hidden">
          {marker.image_path && (
            <img 
              src={`/storage/${marker.image_path}`} 
              className="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40" 
              alt={marker.title} 
            />
          )}
          
          <div className="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="280" height="280" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
              <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/>
            </svg>
          </div>
          
          <div className="absolute top-0 inset-x-0 p-6 flex justify-between items-center z-20 mt-20 md:mt-24">
            <a href={backUrl} className="w-11 h-11 rounded-full bg-slate-900/30 backdrop-blur-md flex items-center justify-center text-white hover:bg-slate-900/50 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div className="flex gap-3">
              <button onClick={toggleFavorite} className="w-11 h-11 rounded-full bg-slate-900/30 backdrop-blur-md flex items-center justify-center text-white hover:bg-slate-900/50 transition-colors active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill={isFavorite ? 'currentColor' : 'none'} stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={isFavorite ? 'text-rose-500' : ''}>
                  <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
              </button>
              <button onClick={shareLocation} className="w-11 h-11 rounded-full bg-slate-900/30 backdrop-blur-md flex items-center justify-center text-white hover:bg-slate-900/50 transition-colors active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
              </button>
            </div>
          </div>

          <div className="absolute bottom-16 left-0 right-0 px-8 z-20">
            <div className="max-w-5xl mx-auto w-full">
              <span className={`inline-block px-3.5 py-1.5 rounded-full text-[10px] font-bold text-white uppercase tracking-wider ${badgeClass} mb-3`}>
                {categoryLabel}
              </span>
              <h1 className="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-4 tracking-tight drop-shadow-sm">
                {marker.location_name || marker.title || 'Lokasi Wisata'}
              </h1>
              
              <div className="flex flex-wrap gap-3">
                {lat !== 0 && lng !== 0 && (
                  <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900/30 backdrop-blur-md text-white text-xs font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                    {lat.toFixed(2)}, {lng.toFixed(2)}
                  </div>
                )}
                {weather && (
                  <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900/30 backdrop-blur-md text-white text-xs font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                    {weather.temperature ?? '-'}°C &nbsp;<span className="opacity-90 font-normal">{weatherDescription}</span>
                  </div>
                )}
              </div>
            </div>
          </div>
          
          <div className="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-slate-900/50 to-transparent"></div>
        </div>

        {/* Main Content Area */}
        <div className="relative z-30 max-w-5xl mx-auto px-4 md:px-8 pb-24 -mt-8">
          
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="bg-white/95 backdrop-blur-xl rounded-[28px] p-6 shadow-xl shadow-emerald-900/5 border border-white"
          >
            <div className="flex flex-col md:flex-row justify-between md:items-center gap-6">
              <div>
                <p className="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">ECO-HEALTH SCORE</p>
                <div className="flex items-end gap-4">
                  <span className="text-5xl font-['Space_Grotesk'] font-bold text-emerald-700 leading-none">
                    {marker.eco_health_score ? Number(marker.eco_health_score).toFixed(1) : '—'}
                  </span>
                  <div className="pb-1">
                    <div className="flex gap-0.5 mb-1 text-sm">
                      {Array.from({ length: 5 }).map((_, i) => (
                        <span key={i} className={i < fullStars || (i === fullStars && halfStar) ? 'text-amber-400' : 'text-slate-200'}>
                          ★
                        </span>
                      ))}
                    </div>
                    <p className="text-[11px] text-slate-500 font-medium">Berdasarkan {totalReviews > 0 ? (totalReviews >= 1000 ? (totalReviews/1000).toFixed(1)+'k' : totalReviews) : '0'} Laporan</p>
                  </div>
                </div>
              </div>
              
              <div className="w-14 h-14 bg-emerald-100 rounded-2xl md:flex items-center justify-center shrink-0 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#059669" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/>
                </svg>
              </div>
            </div>

            <div className="grid grid-cols-3 gap-3 md:gap-4 mt-6">
              <div className="bg-white border border-slate-100/80 rounded-2xl p-4 text-center shadow-sm">
                <p className="text-[9px] md:text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1.5">KEBERSIHAN</p>
                <p className="text-base md:text-lg font-bold text-slate-800">{marker.kebersihan || '—'}</p>
              </div>
              <div className="bg-white border border-slate-100/80 rounded-2xl p-4 text-center shadow-sm">
                <p className="text-[9px] md:text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1.5">AKSES</p>
                <p className="text-base md:text-lg font-bold text-slate-800">{marker.akses || '—'}</p>
              </div>
              <div className="bg-white border border-slate-100/80 rounded-2xl p-4 text-center shadow-sm">
                <p className="text-[9px] md:text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1.5">POPULASI</p>
                <p className="text-base md:text-lg font-bold text-slate-800">{marker.populasi || '—'}</p>
              </div>
            </div>
          </motion.div>

          {/* Description */}
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.1 }} className="mt-10">
            <h2 className="text-xl font-bold text-slate-800 mb-4">Tentang Lokasi</h2>
            <p className="text-[15px] leading-relaxed text-slate-600">
              {marker.description || 'Belum ada deskripsi untuk lokasi ini.'}
            </p>
          </motion.div>

          {/* Eco Rules */}
          {marker.eco_rules && marker.eco_rules.length > 0 && (
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.2 }} className="mt-10 bg-gradient-to-br from-emerald-50 to-slate-50 border border-emerald-100/50 rounded-[24px] p-6">
              <div className="flex items-center gap-3 mb-5">
                <div className="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center shadow-md shadow-emerald-500/20">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <h3 className="text-lg font-bold text-slate-800">Aturan Wisata Hijau</h3>
              </div>
              <div className="space-y-3">
                {marker.eco_rules.map((rule: any, idx: number) => {
                  const isWarning = ['warning', 'prohibited'].includes(rule.type || 'allowed');
                  return (
                    <div key={idx} className="flex items-start gap-3">
                      {isWarning ? (
                        <div className="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        </div>
                      ) : (
                        <div className="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                      )}
                      <span className={`text-[15px] ${isWarning ? 'font-semibold text-rose-600' : 'font-medium text-slate-700'}`}>{rule.text}</span>
                    </div>
                  );
                })}
              </div>
            </motion.div>
          )}

          {/* Review & Ratings */}
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.3 }} className="mt-12">
            {showAlert && (
              <div className="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <span className="font-semibold text-sm">{sessionSuccess}</span>
              </div>
            )}
            
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center shadow-md shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </div>
              <h2 className="text-xl font-bold text-slate-800">Review & Ulasan</h2>
            </div>

            {/* Rating Stats Card */}
            <div className="bg-gradient-to-br from-emerald-50/50 to-white border border-emerald-100/50 rounded-[24px] p-6 mb-6">
              <div className="flex flex-col md:flex-row gap-8 items-center">
                <div className="text-center min-w-[120px]">
                  <div className="text-5xl font-bold text-slate-800 leading-none mb-2">{averageRating > 0 ? averageRating.toFixed(1) : '—'}</div>
                  <div className="flex justify-center gap-0.5 text-sm mb-1">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <span key={i} className={averageRating >= i + 1 || averageRating >= i + 0.5 ? 'text-amber-400' : 'text-slate-200'}>★</span>
                    ))}
                  </div>
                  <div className="text-xs font-medium text-slate-500">{totalReviews} ulasan</div>
                </div>
                <div className="flex-1 w-full">
                  {[5, 4, 3, 2, 1].map((star) => (
                    <div key={star} className="flex items-center gap-3 mb-1.5">
                      <span className="text-xs font-bold text-slate-500 w-3 text-right">{star}</span>
                      <span className="text-amber-400 text-xs">★</span>
                      <div className="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div className="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full" style={{ width: `${totalReviews > 0 ? Math.round((starDistribution[star] / totalReviews) * 100) : 0}%` }}></div>
                      </div>
                      <span className="text-[11px] font-semibold text-slate-400 w-6 text-right">{starDistribution[star] || 0}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Review Form */}
            {user ? (
              !userHasReviewed ? (
                <div className="bg-white border border-slate-200 rounded-[24px] p-6 mb-8 shadow-sm">
                  <h3 className="text-base font-bold text-slate-800 mb-4">Tulis Ulasanmu</h3>
                  {sessionError && (
                    <div className="flex items-center gap-2 bg-rose-50 text-rose-600 text-sm font-semibold p-3 rounded-xl mb-4 border border-rose-100">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                      {sessionError}
                    </div>
                  )}
                  <form action={reviewStoreUrl} method="POST">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div className="mb-5">
                      <label className="block text-sm font-bold text-slate-600 mb-2">Rating</label>
                      <div className="flex gap-1">
                        {[1, 2, 3, 4, 5].map((star) => (
                          <button key={star} type="button" onClick={() => setRating(star)} className={`p-0.5 hover:scale-110 transition-all ${star <= rating ? 'text-amber-400' : 'text-slate-300 hover:text-amber-400'}`}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill={star <= rating ? 'currentColor' : 'none'} stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={star <= rating ? 'stroke-amber-400' : 'stroke-slate-300'}>
                              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                          </button>
                        ))}
                        <input type="hidden" name="rating" value={rating} required />
                      </div>
                    </div>
                    <div className="mb-5">
                      <label className="block text-sm font-bold text-slate-600 mb-2">Komentar</label>
                      <textarea 
                        name="review_text" 
                        rows={3} 
                        maxLength={500} 
                        placeholder="Ceritakan pengalamanmu..." 
                        value={reviewText}
                        onChange={(e) => setReviewText(e.target.value)}
                        className="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none resize-y"
                        required
                      />
                      <div className="flex justify-end mt-1">
                        <span className="text-[11px] text-slate-400 font-medium">{reviewText.length}/500</span>
                      </div>
                    </div>
                    <button type="submit" disabled={rating === 0 || !reviewText.trim()} className="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20 transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                      Kirim Review
                    </button>
                  </form>
                </div>
              ) : (
                <div className="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 mb-8">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" className="text-emerald-600"><path d="M20 6 9 17l-5-5"/></svg>
                  <span className="text-sm font-bold text-emerald-700">Kamu sudah memberikan review.</span>
                </div>
              )
            ) : (
              <div className="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[24px] p-8 text-center mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" className="mx-auto text-slate-400 mb-3"><circle cx="12" cy="12" r="10"/><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p className="text-sm font-bold text-slate-500 mb-4">Login untuk menulis review</p>
                <a href={loginUrl} className="inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Login Sekarang</a>
              </div>
            )}

            {/* Reviews List */}
            <div className="space-y-6">
              {reviews.length > 0 ? (
                reviews.map((review: any) => (
                  <div key={review.id} className="border-b border-slate-100 pb-6 last:border-0">
                    <div className="flex items-center gap-3 mb-2.5">
                      <div className="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm overflow-hidden shrink-0">
                        {review.user?.photo ? (
                          <img src={`/storage/${review.user.photo}`} className="w-full h-full object-cover" alt="Avatar" />
                        ) : (
                          (review.user?.name || '??').substring(0, 2).toUpperCase()
                        )}
                      </div>
                      <div>
                        <div className="flex items-center gap-2">
                          <span className="text-sm font-bold text-slate-800">{review.user?.name || 'Pengguna'}</span>
                          <div className="flex text-[10px]">
                            {Array.from({ length: 5 }).map((_, i) => (
                              <span key={i} className={i < review.rating ? 'text-amber-400' : 'text-slate-200'}>★</span>
                            ))}
                          </div>
                        </div>
                        <span className="text-[11px] font-medium text-slate-400">
                           {/* Simplified date format for React side */}
                           {new Date(review.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                        </span>
                      </div>
                    </div>
                    <p className="text-[14.5px] leading-relaxed text-slate-600 pl-[52px]">{review.review_text}</p>
                  </div>
                ))
              ) : (
                <div className="text-center py-10">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" className="mx-auto text-slate-300 mb-3"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                  <p className="text-sm font-bold text-slate-400">Belum ada review</p>
                </div>
              )}
            </div>
          </motion.div>

          {/* CTA */}
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.4 }} className="mt-12">
            <a href="/pelaporan" className="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl flex items-center justify-center gap-2 font-bold transition-all shadow-xl shadow-slate-900/10">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
              Laporkan Kondisi Alam
            </a>
          </motion.div>
          
        </div>
      </main>
    </div>
  );
}
