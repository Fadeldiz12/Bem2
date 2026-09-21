# Deploy ke Rumahweb / cPanel Shared Hosting

## Persiapan lokal sebelum upload

1. Set APP_ENV=production dan APP_DEBUG=false di .env
2. Jalankan:
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize

3. Pastikan file .env TIDAK ikut ke dalam zip yang diupload ke public hosting

## Struktur upload ke cPanel

Rumahweb shared hosting biasanya punya folder public_html.
Karena Laravel, strukturnya harus seperti ini:

/home/usercpanel/
├── bem-polmed/          ← folder project Laravel (DILUAR public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── ...
└── public_html/         ← atau subfolder domain/subdomain
    ├── index.php        ← ISI dari folder public/ Laravel
    ├── .htaccess
    └── storage -> symlink ke ../bem-polmed/storage/app/public

## Langkah deploy:

1. Upload seluruh project Laravel ke folder di luar public_html
   Contoh: /home/user/bem-polmed/

2. Upload isi folder public/ Laravel ke public_html/
   (atau subfolder jika pakai subdomain, misal bem.polmed.ac.id → public_html/bem/)

3. Edit public_html/index.php, ubah path:
   require __DIR__.'/../bem-polmed/vendor/autoload.php';
   $app = require_once __DIR__.'/../bem-polmed/bootstrap/app.php';

4. Buat database MySQL via cPanel → MySQL Databases
   Catat: hostname, nama db, username, password

5. Edit .env di server:
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainmu.ac.id
   DB_HOST=localhost
   DB_DATABASE=nama_db
   DB_USERNAME=user_db
   DB_PASSWORD=pass_db

6. Upload vendor/ (atau jalankan composer install via SSH jika tersedia)

7. Jalankan via SSH (jika Rumahweb support SSH):
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   php artisan config:cache
   php artisan optimize

8. Jika tidak ada SSH, import SQL manual:
   - Jalankan migrate di lokal dulu
   - Export database dari phpMyAdmin lokal
   - Import ke database cPanel via phpMyAdmin

## Untuk storage symlink tanpa SSH:
Buat folder storage/ di public_html/ dan arahkan manual,
atau hubungi support Rumahweb untuk buat symlink.

## Jika pakai subdomain (bem.polmed.ac.id):
Arahkan document root subdomain ke folder public/ project Laravel
melalui cPanel → Subdomains → Document Root.
Ini cara paling bersih dan tidak perlu edit index.php.
