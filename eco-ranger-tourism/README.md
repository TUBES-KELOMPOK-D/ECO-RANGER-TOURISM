# Eco Tourism

## Cara Menjalankan (13/04/2026)
1. Buka terminal di folder project
```bash
cd eco-ranger-tourism
```
2. Install dependencies
```bash
composer install
```
3. Copy file .env.example ke .env
```bash
copy .env.example .env
```
4. Membuat Application Key
```bash
php artisan key:generate
```
5. Migrasi Database

Buat yang belum pernah migrate
```bash
php artisan migrate
```
Kalau sudah pernah migrate, tapi ada perubahan pada database, bisa pakai ini
```bash
php artisan migrate:fresh
```

6. Seed Database
```bash
php artisan db:seed
```
7. Jalankan
```bash
php artisan serve
```

00. Buat menghapus memori konfigurasi lama
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```
 
 a