# Eco Ranger Tourism

Eco Ranger Tourism adalah platform GIS (_Geographic Information System_) Lingkungan Interaktif untuk memantau, melaporkan, dan menjaga kelestarian alam Indonesia. Proyek ini dibangun menggunakan **Laravel** sebagai fondasi _backend_ dan **React + Tailwind CSS** (via Vite) untuk antarmuka _frontend_ yang dinamis dan interaktif.

---

## 📋 Prasyarat

Sebelum memulai, pastikan sistem komputer Anda telah terinstal:

- [PHP](https://www.php.net/downloads.php) (Versi 8.1 atau yang lebih baru)
- [Composer](https://getcomposer.org/download/)
- [Node.js & npm](https://nodejs.org/en/download/) (Versi 18 LTS atau yang lebih baru)
- Database sistem seperti MySQL (misalnya melalui XAMPP/Laragon) atau PostgreSQL

---

## 🚀 Cara Menjalankan Proyek dari Awal

### 1. Masuk ke Folder Proyek

Buka terminal (Command Prompt, PowerShell, atau Git Bash) dan arahkan ke direktori proyek:

```bash
cd ECO-RANGER-TOURISM
```

### 2. Instalasi Dependensi Backend (Laravel)

Unduh semua pustaka PHP yang dibutuhkan oleh Laravel:

```bash
composer install
```

### 3. Konfigurasi Lingkungan (.env)

Buat salinan file konfigurasi bawaan. Di terminal Windows jalankan:

```bash
copy .env.example .env
```

_(Untuk Mac/Linux gunakan: `cp .env.example .env`)_

Selanjutnya, **buka file `.env`** dan sesuaikan konfigurasi database Anda. Pastikan nama database sudah Anda buat sebelumnya di sistem manajemen database (misal phpMyAdmin).

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eco_ranger_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Hasilkan Kunci Aplikasi (App Key)

Enkripsi keamanan internal Laravel:

```bash
php artisan key:generate
```

### 5. Hubungkan Penyimpanan (Storage Link)

Langkah ini sangat penting agar gambar destinasi (marker) dan avatar profil pengguna dapat diakses oleh _browser_:

```bash
php artisan storage:link
```

### 6. Migrasi dan Pengisian Database (Seeding)

Buat struktur tabel di database beserta data awal (termasuk contoh marker, peringkat eco, dll):

```bash
php artisan migrate:fresh --seed
```

_(Catatan: `migrate:fresh` akan **menghapus** semua tabel lama. Jika Anda hanya ingin menambahkan tabel yang belum termigrasi, cukup gunakan `php artisan migrate`)._

### 7. Instalasi Dependensi Frontend (React)

Aplikasi ini memuat komponen React yang dikompilasi oleh Vite. Instal paket npm-nya terlebih dahulu:

```bash
npm install
```

### 8. Kompilasi Aset Frontend

Terdapat dua cara untuk menangani aset UI. Pilih salah satu:

👉 **Opsi A: Mode Pengembangan (Sangat disarankan saat menulis kode)**  
Terminal ini akan terus berjalan (jangan ditutup). File React/Tailwind akan di-_update_ otomatis (_Hot Reload_) saat Anda menyimpannya.

```bash
npm run dev
```

👉 **Opsi B: Mode Produksi (Siap pakai)**  
Kode React akan dikompilasi menjadi _file static_ utuh yang siap pakai, sehingga tidak perlu terminal _node_ berjalan terus-menerus.

```bash
npm run build
```

### 9. Jalankan Server Backend

Buka jendela terminal **baru**, lalu jalankan server pengembangan bawaan PHP:

```bash
php artisan serve
```

🎉 **Selesai!** Buka _browser_ dan kunjungi: **http://127.0.0.1:8000**

---

## 🛠️ Penyelesaian Masalah (Troubleshooting)

Jika Anda menemui halaman _error_ konfigurasi, tampilan CSS yang tertinggal (_stuck_), atau _route_ yang tidak terdeteksi, coba bersihkan _cache_ aplikasi dengan rangkaian perintah berikut:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
