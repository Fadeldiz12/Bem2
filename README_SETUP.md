# Setup Website BEM Polmed (Laravel 11/13)

## 1. Buat project Laravel baru
composer create-project laravel/laravel bem-polmed
cd bem-polmed

## 2. File yang DITIMPA dari paket ini:
- app/Http/Controllers/       (semua)
- app/Http/Middleware/        (semua)
- app/Http/Requests/          (semua - BARU, validasi form)
- app/Models/                 (semua)
- database/migrations/        (semua - hapus bawaan Laravel dulu)
- database/seeders/           (semua)
- resources/views/            (semua)
- routes/web.php
- bootstrap/app.php
- public/.htaccess

## 3. JANGAN ditimpa:
- composer.json, composer.lock, artisan
- bootstrap/providers.php, bootstrap/cache/*
- config/*
- storage/*
- public/index.php

## 4. Setup .env
Salin .env.example ke .env, lalu edit:
  DB_DATABASE=bem_polmed
  DB_USERNAME=root
  DB_PASSWORD=
  SESSION_DRIVER=file

## 5. Setup awal:
php artisan key:generate
php artisan migrate:fresh     (BUKAN migrate, pakai migrate:fresh biar bersih)
php artisan db:seed
php artisan storage:link      (WAJIB untuk gambar)

## 6. Buka:
- Website : http://localhost:8000
- Admin   : http://localhost:8000/admin/login
- Login   : admin@bempolmed.ac.id / admin123

## Catatan penting:
- Upload gambar → storage/app/public/ (akses via asset('storage/path'))
- Wajib php artisan storage:link sebelum tes gambar
- SESSION_DRIVER=file wajib di .env jika belum ada tabel sessions
- Lihat README_DEPLOY_CPANEL.md untuk panduan upload ke Rumahweb
