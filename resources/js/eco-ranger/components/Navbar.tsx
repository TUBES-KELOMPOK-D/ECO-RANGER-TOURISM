import React, { useState, useRef, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Leaf, Menu, X, AlertTriangle, User, LogOut, Settings, LayoutDashboard, MapPin, FileText, BookOpen, UserCircle } from 'lucide-react';

const NAV_LINKS = [
  { label: 'Aksi', href: '/aksi' },
  { label: 'Laporan', href: '/laporan' },
  { label: 'Peringkat', href: '/leaderboard' },
  { label: 'Akademi', href: '/academy' },
];

export default function Navbar() {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  const user = window.appData?.user;
  const isAdmin = window.appData?.isAdmin;
  const csrfToken = window.appData?.csrfToken;

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  return (
    <motion.nav
      id="navbar"
      initial={{ y: -80, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
      className="absolute top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-6xl"
    >
      {/* Desktop Navbar */}
      <div className="glass-panel rounded-full px-6 py-3 hidden md:flex items-center justify-between gap-6">
        {/* Logo */}
        <a
          href="/"
          className="flex items-center gap-2 text-[#064E3B] font-bold text-lg flex-shrink-0"
        >
          <div className="w-8 h-8 bg-[#10B981] rounded-full flex items-center justify-center shadow-lg shadow-emerald-200">
            <Leaf size={16} className="text-white" />
          </div>
          <span>Eco Ranger</span>
        </a>

        {/* Links */}
        <div className="flex items-center gap-7">
          {NAV_LINKS.filter(link => !(isAdmin && link.label === 'Laporan')).map((link) => (
            <a key={link.label} href={link.href} className="nav-link">
              {link.label}
            </a>
          ))}
          
          <div className="h-5 w-px bg-gray-300 mx-1"></div>

          {!isAdmin && (
            <a
              href="/pelaporan"
              id="btn-lapor-isu"
              className="flex items-center gap-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-full px-5 py-2 text-sm font-semibold transition-all hover:scale-105 shadow-lg shadow-emerald-200/60 flex-shrink-0"
            >
              <AlertTriangle size={14} />
              Lapor Isu
            </a>
          )}
        </div>

        {/* Auth Section */}
        <div className="flex items-center flex-shrink-0">
          {user ? (
            <div className="relative" ref={dropdownRef}>
              <button 
                onClick={() => setDropdownOpen(!dropdownOpen)}
                className="w-10 h-10 rounded-full overflow-hidden border-2 transition-all flex items-center justify-center bg-emerald-100 border-emerald-500 text-emerald-700 hover:scale-105 shadow-md"
              >
                {isAdmin ? (
                  <LayoutDashboard size={18} />
                ) : user.photo ? (
                  <img src={`/storage/${user.photo}`} alt="Profile" className="w-full h-full object-cover" />
                ) : (
                  <span className="font-bold text-sm">{user.name.substring(0, 2).toUpperCase()}</span>
                )}
              </button>

              {/* Dropdown */}
              <AnimatePresence>
                {dropdownOpen && (
                  <motion.div 
                    initial={{ opacity: 0, y: 10, scale: 0.95 }}
                    animate={{ opacity: 1, y: 0, scale: 1 }}
                    exit={{ opacity: 0, y: 10, scale: 0.95 }}
                    transition={{ duration: 0.15 }}
                    className="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                  >
                    <div className="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                      <p className="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{user.role}</p>
                      <p className="text-sm font-bold text-gray-800 truncate">{user.name}</p>
                    </div>
                    
                    <div className="py-2">
                      {isAdmin ? (
                        <>
                          <a href="/admin/dashboard" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors"><LayoutDashboard size={14} /> Panel Admin</a>
                          <a href="/admin/markers" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors"><MapPin size={14} /> Kelola Marker</a>
                          <a href="/admin/reports" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors"><FileText size={14} /> Kelola Laporan</a>
                          <a href="/admin/academy" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors"><BookOpen size={14} /> Kelola Edukasi</a>
                        </>
                      ) : (
                        <>
                          <a href="/profile" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"><User size={14} /> Profil Saya</a>
                          <a href="/reports" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"><FileText size={14} /> Pantau Laporan</a>
                        </>
                      )}
                      
                      <a href="/profile/settings" className="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"><Settings size={14} /> Pengaturan</a>
                    </div>
                    
                    <div className="border-t border-gray-100">
                      <form action="/logout" method="POST">
                        <input type="hidden" name="_token" value={csrfToken} />
                        <button type="submit" className="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors text-left">
                          <LogOut size={14} />
                          Logout
                        </button>
                      </form>
                    </div>
                  </motion.div>
                )}
              </AnimatePresence>
            </div>
          ) : (
            <div className="flex items-center gap-3">
              <a href="/login" className="text-sm font-bold text-gray-600 hover:text-gray-900 px-3 py-2 transition-colors">Login</a>
              <a href="/register" className="bg-[#064E3B] hover:bg-[#065f46] text-white text-sm font-bold px-5 py-2 rounded-full transition-colors shadow-md">Registrasi</a>
            </div>
          )}
        </div>
      </div>

      {/* Mobile Navbar */}
      <div className="glass-panel rounded-2xl px-5 py-3 flex md:hidden items-center justify-between">
        <a href="/" className="flex items-center gap-2 text-[#064E3B] font-bold text-base">
          <div className="w-7 h-7 bg-[#10B981] rounded-full flex items-center justify-center">
            <Leaf size={13} className="text-white" />
          </div>
          Eco Ranger
        </a>
        <div className="flex items-center gap-3">
          {!isAdmin && (
            <a href="/pelaporan" className="p-2 bg-[#10B981] text-white rounded-lg shadow-sm">
              <AlertTriangle size={16} />
            </a>
          )}
          <button
            onClick={() => setMobileOpen(!mobileOpen)}
            className="text-[#064E3B] p-1"
          >
            {mobileOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>
      </div>

      {/* Mobile Dropdown */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            initial={{ opacity: 0, y: -12, scale: 0.96 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: -12, scale: 0.96 }}
            transition={{ duration: 0.22 }}
            className="mt-2 glass-panel rounded-2xl flex flex-col md:hidden overflow-hidden"
          >
            <div className="p-3 flex flex-col">
              {NAV_LINKS.filter(link => !(isAdmin && link.label === 'Laporan')).map((link) => (
                <a
                  key={link.label}
                  href={link.href}
                  className="text-gray-700 font-bold py-3 px-4 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                >
                  {link.label}
                </a>
              ))}
            </div>
            
            <div className="border-t border-gray-100 p-4 bg-gray-50/50">
              {user ? (
                <div className="flex flex-col gap-2">
                  <div className="flex items-center gap-3 mb-2 px-2">
                    <div className="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold border border-emerald-200">
                      {user.name.substring(0,2).toUpperCase()}
                    </div>
                    <div>
                      <p className="text-[10px] font-bold text-gray-400 uppercase">{user.role}</p>
                      <p className="text-sm font-bold text-gray-800">{user.name}</p>
                    </div>
                  </div>
                  
                  {isAdmin ? (
                    <a href="/admin/dashboard" className="py-2.5 px-3 rounded-xl font-semibold text-emerald-700 hover:bg-emerald-50 text-sm">Panel Admin</a>
                  ) : (
                    <a href="/profile" className="py-2.5 px-3 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 text-sm">Profil Saya</a>
                  )}
                  <a href="/profile/settings" className="py-2.5 px-3 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 text-sm">Pengaturan</a>
                  
                  <form action="/logout" method="POST" className="mt-1">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <button type="submit" className="w-full text-left py-2.5 px-3 rounded-xl font-semibold text-red-600 hover:bg-red-50 text-sm">
                      Logout
                    </button>
                  </form>
                </div>
              ) : (
                <div className="flex flex-col gap-2">
                  <a href="/register" className="w-full text-center bg-gray-900 text-white font-bold py-3 rounded-xl shadow-md">Registrasi</a>
                  <a href="/login" className="w-full text-center bg-white text-gray-700 font-bold py-3 rounded-xl shadow-sm border border-gray-200">Login</a>
                </div>
              )}
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </motion.nav>
  );
}
