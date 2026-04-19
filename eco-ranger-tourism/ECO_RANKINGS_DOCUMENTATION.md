# Eco Rankings & Gamification - Dokumentasi Lengkap

## Overview
Fitur **Eco Rankings & Gamification** telah berhasil diimplementasikan dengan lengkap. Sistem ini menampilkan ranking global eco-warriors berdasarkan poin yang dihitung REAL-TIME dari aktivitas pengguna di database.

---

## ✅ Struktur Implementasi

### 1. **Database**
✓ Tabel `shared_contents` (baru - migration: `2026_04_15_000001`)
✓ Tabel `reports` (existing - laporan lingkungan)
✓ Tabel `actions` (existing - aksi komunitas)
✓ Tabel `forum_diskusis` (existing - diskusi forum)
✓ Tabel `events` + `participant_events` (existing - event komunitas)

### 2. **Models**
✓ `app/Models/User.php` - Enhanced dengan relationships & methods
  - `getTotalPointsAttribute()` - Hitung poin real-time
  - `calculateEcoLevel()` - Level berdasarkan poin
  - Relationships: `reports()`, `forumPosts()`, `sharedContents()`, `eventParticipations()`

✓ `app/Models/SharedContent.php` (baru)
✓ `app/Models/ForumDiskusi.php` (baru)
✓ `app/Models/Event.php` (baru)

### 3. **Controller**
✓ `app/Http/Controllers/EcoRankingController.php` (baru)
  - Method `index()` - Menampilkan ranking lengkap
  - Private methods untuk badge & reward logic

### 4. **Views**
✓ `resources/views/eco-rankings.blade.php` - Tampilan lengkap dengan:
  - Top 3 Podium (dengan styling gold/silver/bronze)
  - Leaderboard lengkap (tabel dengan paginasi)
  - Point Rules (5 kategori poin)
  - Lencana & Pencapaian (untuk user yang login)
  - Reward Voucher (Rank #1-3)
  - Current user rank display

### 5. **Routes**
✓ `GET /eco-rankings` → `EcoRankingController@index` (name: `eco.rankings`)

---

## 🎯 Sistem Poin (Real-Time)

Poin setiap user dihitung OTOMATIS dari aktivitas database:

| Aktivitas | Poin | Tabel | Cara Hitung |
|-----------|------|-------|------------|
| Lapor Isu Lingkungan | +10 | `reports` | COUNT semua report milik user |
| Ikut Aksi Komunitas | +50 | `participant_events` | COUNT partisipasi event |
| Verifikasi Laporan | +5 | `reports` | COUNT report dengan status='diverifikasi' |
| Diskusi Forum | +15 | `forum_diskusis` | COUNT forum posts milik user |
| Bagikan Konten | +20 | `shared_contents` | COUNT shared contents milik user |

**Formula:**
```
Total Points = 
  (reports count * 10) +
  (event participations count * 50) +
  (verified reports count * 5) +
  (forum posts count * 15) +
  (shared contents count * 20)
```

---

## 🏆 Level System

Berdasarkan Total Points:
- **0 - 999**: ECO-MEMBER
- **1000 - 1999**: ECO-LEADER
- **2000 - 2399**: ECO-WARRIOR
- **≥2400**: ECO-RANGER

---

## 🎁 Lencana & Pencapaian

### 5 Badge dengan Progress Dinamis:

| Badge | Target | Progress dari |
|-------|--------|--------------|
| 🪣 Plastic Hunter | 10 laporan | `COUNT reports` |
| 🌳 Tree Hugger | 5 aksi pohon | `COUNT events LIKE '%pohon%'` |
| 🐢 Turtle Saver | 3 verifikasi | `COUNT reports WHERE status='diverifikasi'` |
| 💬 Eco-Speaker | 20 forum posts | `COUNT forum_diskusis` |
| 📸 Green Influencer | 10 konten | `COUNT shared_contents` |

Progress bar otomatis update berdasarkan data real-time di database.

---

## 💰 Reward Voucher

**Top 3 mendapat hadiah:**
- 🥇 Rank #1 → Voucher Rp 500.000 (Diskon hotel & event)
- 🥈 Rank #2 → Voucher Rp 250.000 (Diskon paket tour)
- 🥉 Rank #3 → Voucher Rp 100.000 (Diskon kuliner & merchandise)

---

## 📊 Halaman & Fitur

### **1. Top 3 Podium**
- Tampilkan 3 user dengan poin tertinggi
- Rank #1 di tengah (paling besar, styling gold)
- Rank #2 & #3 di samping (silver/bronze)
- Setiap card: nama, level, total poin, avatar

### **2. Leaderboard Lengkap**
- Tabel rank 4 sampai seterusnya
- Kolom: Rank | Name | Level | Points
- **Paginasi**: 10 user per halaman
- Navigasi: First / Prev / 1 2 3 ... / Next / Last
- Info: "Menampilkan X - Y dari Z users"

### **3. Aturan Poin**
Grid 5 kolom menampilkan:
- Icon + Title
- Description
- +X Points

### **4. Lencana & Pencapaian** (untuk user yang login)
Untuk setiap badge:
- Icon (emoji)
- Nama & deskripsi
- Progress bar visual
- Progress teks (Current/Target)
- Status "SELESAI" jika completed

### **5. Current User Rank** (untuk user yang login)
Banner hijau menampilkan:
- Posisinya di ranking global
- Total poin saat ini

### **6. Reward Info**
3 card menampilkan hadiah untuk top 3

---

## 🚀 Cara Menggunakan

### **Akses Halaman**
```
URL: http://localhost:8000/eco-rankings
Route: eco.rankings
```

### **Menambah Data Untuk Testing**

Untuk user memiliki poin, tambahkan data ke tabel:

```bash
# Terminal:
php artisan tinker

# Lalu jalankan:
$user = App\Models\User::find(1);  // atau ID user lain

// Tambah reports
App\Models\Report::create([
    'user_id' => $user->id,
    'title' => 'Sampah Plastik di Pantai',
    'description' => 'Banyak sampah plastik',
    'latitude' => -6.1234,
    'longitude' => 106.5678,
    'status' => 'diverifikasi',
    'report_date' => now(),
]);

// Tambah forum posts
App\Models\ForumDiskusi::create([
    'user_id' => $user->id,
    'topic' => 'Cara Mengurangi Plastik',
    'message' => 'Kita bisa mulai dengan...',
]);

// Tambah shared content
App\Models\SharedContent::create([
    'user_id' => $user->id,
    'title' => 'Program Daur Ulang',
    'description' => 'Inisiatif hijau kami',
    'type' => 'image',
]);

// Tambah event participation
$user->eventParticipations()->attach(1);  // Attach to event ID 1

// View total points
dd($user->total_points);  // akan menampilkan total poin real-time
```

---

## 🔄 Cara Kerja Real-Time Points

### **Ketika User Login:**
1. Sistem query semua relationships user
2. Hitung total poin dari 5 kategori
3. Tentukan level berdasarkan poin
4. Ambil badge progress dari database

### **Performa**
- ✓ Queries dengan `with()` untuk eager loading
- ✓ Pakai `.count()` untuk menghitung
- ✓ Jika performa jadi masalah nanti, bisa tambah caching dengan `cache()->remember()`

---

## 📁 File yang Dibuat/Diubah

### **Created:**
```
database/migrations/2026_04_15_000001_create_shared_contents_table.php
app/Models/SharedContent.php
app/Models/ForumDiskusi.php
app/Models/Event.php
app/Http/Controllers/EcoRankingController.php
resources/views/eco-rankings.blade.php
```

### **Updated:**
```
app/Models/User.php - Added relationships & getTotalPointsAttribute()
routes/web.php - Added route /eco-rankings
```

---

## ✨ Fitur Tambahan

### **Responsive Design**
- ✓ Desktop: Full grid layout
- ✓ Tablet: Adjusted grid
- ✓ Mobile: Single column

### **Styling**
- ✓ Tailwind CSS (built-in)
- ✓ Gradient backgrounds
- ✓ Card hover effects
- ✓ Progress bar animations
- ✓ Color coding: Gold/Silver/Bronze for top 3

---

## 🐛 Troubleshooting

### **Halaman tidak muncul?**
```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### **Poin tidak update?**
- Pastikan data ada di database dengan `php artisan tinker`
- Check relasi di User model
- Refresh browser (clear cache)

### **Paginasi error?**
- Update query string: `?page=2`
- Pastikan `$totalUsers` > 10 untuk pagination muncul

---

## 🎯 Next Steps (Optional)

Untuk enhancement lebih lanjut:
1. **Caching** - Cache total points jika performa kurang
2. **Points Console** - Admin panel untuk manage poin manual
3. **Email Notifications** - Notifikasi saat user ranked up
4. **Achievement Badges Page** - Detail page untuk setiap badge
5. **Voucher Redemption** - Sistem claim voucher untuk top 3
6. **Monthly/Weekly Rankings** - Track ranking per periode

---

## 📞 Support

Jika ada error atau pertanyaan:
1. Check data di database: `php artisan tinker`
2. Lihat controller logic di `EcoRankingController@index`
3. Validate view syntax di `eco-rankings.blade.php`
4. Run migrations: `php artisan migrate --force`

---

**Status**: ✅ Siap Pakai
**Last Updated**: 2026-04-15
**Version**: 1.0
