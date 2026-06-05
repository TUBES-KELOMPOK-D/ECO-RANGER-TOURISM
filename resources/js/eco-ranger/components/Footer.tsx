import React from 'react';
import { Leaf, Code2, MessageSquare, Camera, Heart } from 'lucide-react';

const FOOTER_LINKS = {
  Platform: ['Peta Interaktif', 'Laporan Masuk', 'Eco Rankings', 'Green Academy'],
  Komunitas: ['Bergabung', 'Lapor Isu', 'Aksi Lingkungan', 'Forum'],
  Tentang: ['Tentang Kami', 'Kebijakan Privasi', 'Syarat Layanan', 'Kontak'],
};

export default function Footer() {
  return (
    <footer className="bg-[#064E3B] text-white pt-16 pb-8 px-6 md:px-12 lg:px-24">
      <div className="max-w-6xl mx-auto">
        {/* Top section */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-white/10">
          {/* Brand */}
          <div className="lg:col-span-2">
            <div className="flex items-center gap-2 mb-4">
              <div className="w-9 h-9 bg-[#10B981] rounded-full flex items-center justify-center shadow-lg">
                <Leaf size={18} className="text-white" />
              </div>
              <span className="font-bold text-xl">Eco Ranger</span>
            </div>
            <p className="text-white/60 text-sm leading-relaxed max-w-xs">
              Platform GIS lingkungan untuk memantau, melaporkan, dan menjaga kelestarian alam
              Indonesia secara bersama-sama.
            </p>
            {/* Social */}
            <div className="flex items-center gap-3 mt-6">
              {[
                { icon: Code2, href: '#', label: 'GitHub' },
                { icon: MessageSquare, href: '#', label: 'Twitter' },
                { icon: Camera, href: '#', label: 'Instagram' },
              ].map(({ icon: Icon, href, label }) => (
                <a
                  key={label}
                  href={href}
                  aria-label={label}
                  className="w-9 h-9 rounded-xl bg-white/10 hover:bg-[#10B981] flex items-center justify-center transition-colors"
                >
                  <Icon size={15} />
                </a>
              ))}
            </div>
          </div>

          {/* Links */}
          {Object.entries(FOOTER_LINKS).map(([category, links]) => (
            <div key={category}>
              <h4 className="font-bold text-sm mb-4 text-white">{category}</h4>
              <ul className="space-y-3">
                {links.map((link) => (
                  <li key={link}>
                    <a
                      href="#"
                      className="text-white/50 text-sm hover:text-[#10B981] transition-colors"
                    >
                      {link}
                    </a>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* Bottom */}
        <div className="pt-8 flex flex-col md:flex-row items-center justify-between gap-3">
          <p className="text-white/40 text-xs">
            © 2026 Eco Ranger Tourism. All rights reserved.
          </p>
          <p className="text-white/40 text-xs flex items-center gap-1.5">
            Dibuat dengan <Heart size={10} className="text-[#10B981] fill-[#10B981]" /> untuk
            bumi yang lebih hijau
          </p>
        </div>
      </div>
    </footer>
  );
}
