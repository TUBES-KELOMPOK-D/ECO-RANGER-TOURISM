import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { AlertCircle, LogIn, Leaf, ArrowLeft, UserPlus, Eye, EyeOff } from 'lucide-react';

type Tab = 'login' | 'register';

export default function LoginPage() {
  const {
    usersCount = 0,
    markersCount = 0,
    csrfToken = '',
    loginRoute = '/login',
    registerRoute = '/register',
    error = '',
    oldEmail = ''
  } = window.appData || {};

  const [tab, setTab] = useState<Tab>('login');
  const [showPass, setShowPass] = useState(false);
  const [showConfirmPass, setShowConfirmPass] = useState(false);

  const isLogin = tab === 'login';

  // Shared input class
  const inputCls = "w-full px-4 py-3.5 border border-slate-200 rounded-xl text-sm text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-400/10 outline-none transition-all";
  const labelCls = "block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2";

  return (
    <div
      className="relative min-h-screen overflow-x-hidden"
      style={{ fontFamily: "'Inter', 'Plus Jakarta Sans', sans-serif" }}
    >

      {/* ════════════════════════════════
          MOBILE LAYOUT  (< lg)
      ════════════════════════════════ */}
      <div className="lg:hidden flex flex-col min-h-screen">

        {/* Mobile: green hero top section */}
        <div
          className="relative flex-shrink-0 px-5 pt-6 pb-10"
          style={{ background: 'linear-gradient(160deg, #052e16 0%, #064e3b 55%, #0a6650 100%)' }}
        >
          {/* Decorative blobs */}
          <div className="absolute top-[-60px] right-[-60px] w-[200px] h-[200px] rounded-full bg-emerald-400/10 blur-3xl pointer-events-none" />
          <div className="absolute bottom-0 left-[-40px] w-[160px] h-[160px] rounded-full bg-teal-300/10 blur-2xl pointer-events-none" />

          {/* Top bar: back + tabs */}
          <div className="relative z-10 flex items-center justify-between mb-8">
            {/* Back */}
            <motion.a
              href="/"
              initial={{ opacity: 0, x: -12 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.4 }}
              className="flex items-center gap-2 group"
            >
              <div className="w-8 h-8 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white group-hover:bg-white/20 transition-all">
                <ArrowLeft size={14} className="group-hover:-translate-x-0.5 transition-transform" />
              </div>
              <span className="text-xs font-semibold text-white/60 group-hover:text-white transition-colors">Beranda</span>
            </motion.a>

            {/* Tab switcher */}
            <motion.div
              initial={{ opacity: 0, y: -8 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4, delay: 0.1 }}
              className="relative flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-full p-1 shadow-lg"
            >
              <motion.div
                layout
                transition={{ type: 'spring', stiffness: 500, damping: 35 }}
                className="absolute top-1 bottom-1 rounded-full bg-white shadow-md"
                style={{ left: isLogin ? '4px' : '50%', right: isLogin ? '50%' : '4px' }}
              />
              <motion.button
                onClick={() => setTab('login')}
                animate={{ opacity: isLogin ? 1 : 0.45 }}
                transition={{ duration: 0.25 }}
                className={`relative z-10 px-5 py-2 text-xs font-bold rounded-full transition-colors duration-200 ${isLogin ? 'text-emerald-800' : 'text-black hover:opacity-70'}`}
              >
                Masuk
              </motion.button>
              <motion.button
                onClick={() => setTab('register')}
                animate={{ opacity: !isLogin ? 1 : 0.45 }}
                transition={{ duration: 0.25 }}
                className={`relative z-10 px-5 py-2 text-xs font-bold rounded-full transition-colors duration-200 ${!isLogin ? 'text-emerald-800' : 'text-black hover:opacity-70'}`}
              >
                Daftar
              </motion.button>
            </motion.div>
          </div>

          {/* Logo + tagline */}
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="relative z-10"
          >
            <div className="mb-4 h-9"></div>
            <h1 className="text-3xl font-black text-white leading-tight tracking-tight">
              Jaga Alam,<br />
              <span className="text-transparent bg-clip-text" style={{ backgroundImage: 'linear-gradient(90deg,#4ade80,#34d399)' }}>
                Raih Poin.
              </span>
            </h1>
            <p className="mt-3 text-emerald-100/60 text-sm leading-relaxed max-w-xs">
              Laporkan isu, jelajahi destinasi, dan dapatkan eco-points.
            </p>
          </motion.div>

          {/* Mobile stats */}
          <motion.div
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.25 }}
            className="relative z-10 grid grid-cols-3 gap-2 mt-6"
          >
            {[
              { value: usersCount, label: 'Ranger' },
              { value: '50+', label: 'Laporan' },
              { value: markersCount, label: 'Wisata' },
            ].map((s) => (
              <div key={s.label} className="rounded-2xl p-3 border border-white/10 text-center" style={{ background: 'rgba(255,255,255,0.07)' }}>
                <p className="text-xl font-black text-white">{s.value}</p>
                <p className="text-[10px] text-emerald-300/70 mt-0.5 font-medium">{s.label}</p>
              </div>
            ))}
          </motion.div>
        </div>

        {/* Mobile: form section — overlapping card */}
        <div className="relative z-10 flex-1 -mt-6 bg-[#f8faf9] rounded-t-[28px] px-5 pt-8 pb-10">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.45, delay: 0.3 }}
            className="bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-100 p-6"
          >
            {/* Error */}
            <AnimatePresence>
              {error && (
                <motion.div
                  initial={{ opacity: 0, height: 0 }}
                  animate={{ opacity: 1, height: 'auto' }}
                  exit={{ opacity: 0, height: 0 }}
                  className="flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3 mb-5"
                >
                  <AlertCircle size={15} className="text-red-500 flex-shrink-0" />
                  <p className="text-sm text-red-600 font-semibold">{error}</p>
                </motion.div>
              )}
            </AnimatePresence>

            <AnimatePresence mode="wait">
              {isLogin ? (
                <motion.div key="mob-login" initial={{ opacity: 0, x: -16 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: 16 }} transition={{ duration: 0.22 }}>
                  <h2 className="text-xl font-black text-slate-900 mb-1">Selamat Datang</h2>
                  <p className="text-slate-400 text-xs mb-5">Masuk ke akun Anda untuk melanjutkan.</p>
                  <form action={loginRoute} method="POST" className="space-y-4">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div>
                      <label className={labelCls}>Alamat Email</label>
                      <input type="email" name="email" defaultValue={oldEmail} required placeholder="nama@email.com" className={inputCls} />
                    </div>
                    <div>
                      <label className={labelCls}>Password</label>
                      <div className="relative">
                        <input type={showPass ? 'text' : 'password'} name="password" required placeholder="••••••••" className={inputCls + ' pr-12'} />
                        <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                          {showPass ? <EyeOff size={15} /> : <Eye size={15} />}
                        </button>
                      </div>
                    </div>
                    <button type="submit" className="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all active:scale-[0.98]" style={{ background: 'linear-gradient(135deg,#059669,#047857)', boxShadow: '0 8px 20px rgba(5,150,105,0.28)' }}>
                      <LogIn size={15} /> Masuk ke Akun
                    </button>
                  </form>
                  <p className="mt-5 text-center text-xs text-slate-400">
                    Belum punya akun?{' '}
                    <button onClick={() => setTab('register')} className="text-emerald-600 font-bold hover:text-emerald-700 transition-colors">Daftar Gratis</button>
                  </p>
                </motion.div>
              ) : (
                <motion.div key="mob-register" initial={{ opacity: 0, x: 16 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: -16 }} transition={{ duration: 0.22 }}>
                  <h2 className="text-xl font-black text-slate-900 mb-1">Buat Akun Baru</h2>
                  <p className="text-slate-400 text-xs mb-5">Bergabung dan mulai jaga alam Indonesia.</p>
                  <form action={registerRoute} method="POST" className="space-y-4">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div>
                      <label className={labelCls}>Nama Lengkap</label>
                      <input type="text" name="name" required placeholder="Ahmad Fauzan" className={inputCls} />
                    </div>
                    <div>
                      <label className={labelCls}>Alamat Email</label>
                      <input type="email" name="email" required placeholder="nama@email.com" className={inputCls} />
                    </div>
                    <div>
                      <label className={labelCls}>Password</label>
                      <div className="relative">
                        <input type={showConfirmPass ? 'text' : 'password'} name="password" required placeholder="Min. 8 karakter" className={inputCls + ' pr-12'} />
                        <button type="button" onClick={() => setShowConfirmPass(!showConfirmPass)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                          {showConfirmPass ? <EyeOff size={15} /> : <Eye size={15} />}
                        </button>
                      </div>
                    </div>
                    <div>
                      <label className={labelCls}>Konfirmasi Password</label>
                      <input type="password" name="password_confirmation" required placeholder="Ulangi password" className={inputCls} />
                    </div>
                    <button type="submit" className="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all active:scale-[0.98]" style={{ background: 'linear-gradient(135deg,#059669,#047857)', boxShadow: '0 8px 20px rgba(5,150,105,0.28)' }}>
                      <UserPlus size={15} /> Buat Akun
                    </button>
                  </form>
                  <p className="mt-5 text-center text-xs text-slate-400">
                    Sudah punya akun?{' '}
                    <button onClick={() => setTab('login')} className="text-emerald-600 font-bold hover:text-emerald-700 transition-colors">Masuk</button>
                  </p>
                </motion.div>
              )}
            </AnimatePresence>
          </motion.div>
        </div>
      </div>

      {/* ════════════════════════════════
          DESKTOP LAYOUT  (≥ lg)
      ════════════════════════════════ */}
      <div className="hidden lg:flex min-h-screen">

        {/* Background split */}
        <div className="absolute inset-0 flex pointer-events-none">
          <div className="w-1/2" style={{ background: 'linear-gradient(145deg,#052e16 0%,#064e3b 40%,#065f46 100%)' }} />
          <div className="flex-1 bg-[#f8faf9]" />
        </div>

        {/* Decorative blobs */}
        <div className="absolute top-[-80px] left-[-80px] w-[340px] h-[340px] rounded-full bg-emerald-500/10 blur-3xl pointer-events-none" />
        <div className="absolute bottom-[-60px] left-[200px] w-[260px] h-[260px] rounded-full bg-teal-400/10 blur-2xl pointer-events-none" />

        {/* Back button */}
        <motion.a
          href="/"
          initial={{ opacity: 0, x: -16 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.45, delay: 0.1 }}
          className="absolute top-6 left-6 z-50 flex items-center gap-2 group"
        >
          <div className="w-9 h-9 rounded-xl border border-white/20 bg-white/10 backdrop-blur-sm flex items-center justify-center text-white group-hover:bg-white/20 transition-all shadow-md">
            <ArrowLeft size={16} className="group-hover:-translate-x-0.5 transition-transform" />
          </div>
          <span className="text-xs font-semibold text-white/70 group-hover:text-white transition-colors">Beranda</span>
        </motion.a>

        {/* Tab switcher */}
        <motion.div
          initial={{ opacity: 0, y: -12 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.45, delay: 0.15 }}
          className="absolute top-6 right-6 z-50"
        >
          <div className="relative flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-full p-1 shadow-lg">
            <motion.div
              layout
              transition={{ type: 'spring', stiffness: 500, damping: 35 }}
              className="absolute top-1 bottom-1 rounded-full bg-white shadow-md"
              style={{ left: isLogin ? '4px' : '50%', right: isLogin ? '50%' : '4px' }}
            />
            <motion.button
              onClick={() => setTab('login')}
              animate={{ opacity: isLogin ? 1 : 0.4 }}
              transition={{ duration: 0.25 }}
              className={`relative z-10 px-5 py-2 text-xs font-bold rounded-full transition-colors duration-200 ${isLogin ? 'text-emerald-800' : 'text-white'}`}
            >
              Masuk
            </motion.button>
            <motion.button
              onClick={() => setTab('register')}
              animate={{ opacity: !isLogin ? 1 : 0.4 }}
              transition={{ duration: 0.25 }}
              className={`relative z-10 px-5 py-2 text-xs font-bold rounded-full transition-colors duration-200 ${!isLogin ? 'text-emerald-800' : 'text-white'}`}
            >
              Daftar
            </motion.button>
          </div>
        </motion.div>

        {/* LEFT: Branding */}
        <div className="relative z-10 w-1/2 flex flex-col justify-between px-16 py-16">
          {/* Logo */}
          <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }} className="h-10">
          </motion.div>

          {/* Center */}
          <div className="flex flex-col gap-8">
            <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.7, delay: 0.1 }}>
              <p className="text-emerald-400 text-xs font-bold tracking-widest uppercase mb-4">Eco Ranger Tourism</p>
              <h1 className="text-5xl font-black text-white leading-[1.1] tracking-tight">
                Jaga Alam,<br />
                <span className="text-transparent bg-clip-text" style={{ backgroundImage: 'linear-gradient(90deg,#4ade80,#34d399)' }}>
                  Raih Poin.
                </span>
              </h1>
              <p className="mt-5 text-emerald-100/60 text-base leading-relaxed max-w-sm">
                Bergabunglah dalam misi pelestarian alam Indonesia. Laporkan isu, jelajahi destinasi, dan dapatkan eco-points.
              </p>
            </motion.div>

            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6, delay: 0.25 }} className="grid grid-cols-3 gap-3">
              {[
                { value: usersCount, label: 'Ranger Aktif' },
                { value: '50+', label: 'Laporan Isu' },
                { value: markersCount, label: 'Lokasi Wisata' },
              ].map((stat) => (
                <div key={stat.label} className="rounded-2xl p-4 border border-white/10" style={{ background: 'rgba(255,255,255,0.06)', backdropFilter: 'blur(8px)' }}>
                  <p className="text-2xl font-black text-white">{stat.value}</p>
                  <p className="text-[11px] text-emerald-300/70 mt-1 font-medium">{stat.label}</p>
                </div>
              ))}
            </motion.div>
          </div>

          <motion.p initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ duration: 0.6, delay: 0.4 }} className="text-white/20 text-xs">
            © 2025 Eco Ranger Tourism. Bersama Jaga Bumi.
          </motion.p>
        </div>

        {/* RIGHT: Form */}
        <div className="relative z-10 flex-1 flex items-center justify-center px-16">
          <div className="w-full max-w-md">
            <motion.div
              initial={{ opacity: 0, y: 24 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.2 }}
              className="bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 p-10"
            >
              {/* Error */}
              <AnimatePresence>
                {error && (
                  <motion.div
                    initial={{ opacity: 0, y: -8, height: 0 }}
                    animate={{ opacity: 1, y: 0, height: 'auto' }}
                    exit={{ opacity: 0, y: -8, height: 0 }}
                    className="flex items-center gap-3 bg-red-50 border border-red-100 rounded-2xl px-4 py-3 mb-6"
                  >
                    <AlertCircle size={16} className="text-red-500 flex-shrink-0" />
                    <p className="text-sm text-red-600 font-semibold">{error}</p>
                  </motion.div>
                )}
              </AnimatePresence>

              <AnimatePresence mode="wait">
                {isLogin ? (
                  <motion.div key="desk-login" initial={{ opacity: 0, x: -20 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: 20 }} transition={{ duration: 0.25 }}>
                    <div className="mb-7">
                      <h2 className="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h2>
                      <p className="text-slate-400 text-sm mt-1">Masuk ke akun Anda.</p>
                    </div>
                    <form action={loginRoute} method="POST" className="space-y-4">
                      <input type="hidden" name="_token" value={csrfToken} />
                      <div>
                        <label className={labelCls}>Alamat Email</label>
                        <input type="email" name="email" defaultValue={oldEmail} required placeholder="nama@email.com" className={inputCls} />
                      </div>
                      <div>
                        <label className={labelCls}>Password</label>
                        <div className="relative">
                          <input type={showPass ? 'text' : 'password'} name="password" required placeholder="••••••••" className={inputCls + ' pr-12'} />
                          <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            {showPass ? <EyeOff size={16} /> : <Eye size={16} />}
                          </button>
                        </div>
                      </div>
                      <button type="submit" className="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all active:scale-[0.98] mt-2" style={{ background: 'linear-gradient(135deg,#059669,#047857)', boxShadow: '0 8px 24px rgba(5,150,105,0.3)' }}>
                        <LogIn size={16} /> Masuk ke Akun
                      </button>
                    </form>
                    <p className="mt-6 text-center text-xs text-slate-400">
                      Belum punya akun?{' '}
                      <button onClick={() => setTab('register')} className="text-emerald-600 font-bold hover:text-emerald-700 transition-colors">Daftar Gratis</button>
                    </p>
                  </motion.div>
                ) : (
                  <motion.div key="desk-register" initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: -20 }} transition={{ duration: 0.25 }}>
                    <div className="mb-7">
                      <h2 className="text-2xl font-black text-slate-900 tracking-tight">Buat Akun Baru</h2>
                      <p className="text-slate-400 text-sm mt-1">Bergabung dan mulai jaga alam Indonesia.</p>
                    </div>
                    <form action={registerRoute} method="POST" className="space-y-4">
                      <input type="hidden" name="_token" value={csrfToken} />
                      <div>
                        <label className={labelCls}>Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Ahmad Fauzan" className={inputCls} />
                      </div>
                      <div>
                        <label className={labelCls}>Alamat Email</label>
                        <input type="email" name="email" required placeholder="nama@email.com" className={inputCls} />
                      </div>
                      <div>
                        <label className={labelCls}>Password</label>
                        <div className="relative">
                          <input type={showConfirmPass ? 'text' : 'password'} name="password" required placeholder="Min. 8 karakter" className={inputCls + ' pr-12'} />
                          <button type="button" onClick={() => setShowConfirmPass(!showConfirmPass)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            {showConfirmPass ? <EyeOff size={16} /> : <Eye size={16} />}
                          </button>
                        </div>
                      </div>
                      <div>
                        <label className={labelCls}>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password" className={inputCls} />
                      </div>
                      <button type="submit" className="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all active:scale-[0.98] mt-2" style={{ background: 'linear-gradient(135deg,#059669,#047857)', boxShadow: '0 8px 24px rgba(5,150,105,0.3)' }}>
                        <UserPlus size={16} /> Buat Akun
                      </button>
                    </form>
                    <p className="mt-6 text-center text-xs text-slate-400">
                      Sudah punya akun?{' '}
                      <button onClick={() => setTab('login')} className="text-emerald-600 font-bold hover:text-emerald-700 transition-colors">Masuk</button>
                    </p>
                  </motion.div>
                )}
              </AnimatePresence>
            </motion.div>
          </div>
        </div>
      </div>
    </div>
  );
}
