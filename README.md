# VELORA — Supplier, Purchase & Financial Management (Laravel 12)

## Kebutuhan
PHP 8.2+, Composer, MySQL, ekstensi PHP umum Laravel (mbstring, xml, pdo_mysql, gd untuk PDF).

## Instalasi
1. `composer install`
2. `cp .env.example .env` lalu buat database MySQL bernama `velora` (atur DB_USERNAME dan DB_PASSWORD di .env)
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `php artisan storage:link`
6. `php artisan serve` lalu buka http://127.0.0.1:8000

## Akun demo (kata sandi: password)
| Peran | Email | Akses |
|---|---|---|
| Admin | admin@velora.test | Semua menu |
| Staf Pembelian | pembelian@velora.test | Supplier, barang, kategori, pembelian |
| Staf Keuangan | keuangan@velora.test | Hutang & pembayaran, kas, laporan |
| Pimpinan | pimpinan@velora.test | Dashboard dan laporan |

## Fitur
- Login/logout dengan hak akses per peran (middleware `role`)
- CRUD supplier, barang, kategori, pengguna (satu basis kode: `CrudController` + view `crud.blade.php`)
- Pembelian: simpan pembelian -> stok barang bertambah -> tagihan hutang terbentuk (satu database transaction); pembatalan selama belum dibayar
- Pembayaran hutang: validasi sisa hutang -> status sebagian/lunas -> kas keluar otomatis
- Kas masuk/keluar manual, dashboard ringkasan, laporan pembelian/hutang/pembayaran/kas dengan ekspor PDF (DomPDF) dan Excel (CSV)

## Struktur penting
app/Http/Controllers, app/Models, app/Http/Middleware/RoleMiddleware.php, database/migrations/2026_11_15_000001_create_velora_tables.php, database/seeders, resources/views, routes/web.php, public/css/velora.css (tema dari mockup folder 03).
