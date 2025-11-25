# Anseyo Restaurant Management System

Sistem manajemen restoran berbasis web yang dibangun menggunakan Laravel 12. Sistem ini mencakup fitur untuk Admin, Kasir, Dapur, dan Pemesanan Pelanggan via QR Code.

## Fitur Utama

- **Admin Dashboard**: Manajemen menu, kategori, meja, staf, dan laporan penjualan.
- **Kasir**: Point of Sale (POS) untuk memproses pembayaran, cetak struk, dan monitoring status meja.
- **Dapur**: Tampilan pesanan masuk real-time (Pending, Preparing, Ready).
- **Pelanggan**: Pemesanan mandiri melalui scan QR Code meja.

## Persyaratan Sistem (Requirements)

Pastikan server atau komputer lokal Anda memenuhi persyaratan berikut:

- **PHP**: Versi 8.2 atau lebih baru
- **Composer**: Dependency manager untuk PHP
- **Node.js & NPM**: Untuk mengelola aset frontend (Vite)
- **Database**: SQLite (default) atau MySQL/MariaDB

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/ridwanpanji22/anseyo.git
    cd anseyo
    ```

2.  **Install Dependency PHP**
    ```bash
    composer install
    ```

3.  **Install Dependency Frontend**
    ```bash
    npm install
    ```

4.  **Setup Environment**
    Salin file konfigurasi `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi Database**
    Secara default, proyek ini menggunakan SQLite. Pastikan file database tersedia (jika menggunakan SQLite):
    ```bash
    touch database/database.sqlite
    ```
    *(Jika menggunakan MySQL, sesuaikan `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`)*

7.  **Konfigurasi URL Barcode**
    Agar QR Code yang digenerate dapat dipindai dengan benar oleh pelanggan (terutama jika diakses dari HP dalam jaringan yang sama), Anda **wajib** mengubah `APP_URL` di file `.env` menggunakan IP Address komputer Anda, bukan `localhost`.

    Buka file `.env` dan ubah baris berikut:
    ```dotenv
    APP_URL=http://192.168.x.x:8000
    ```
    *Ganti `192.168.x.x` dengan IP Address lokal komputer Anda.*

8.  **Migrasi dan Seeding Database**
    Jalankan perintah berikut untuk membuat tabel dan mengisi data awal (akun default, menu contoh, meja):
    ```bash
    php artisan migrate:fresh --seed
    ```

## Menjalankan Aplikasi

1.  **Jalankan Server Laravel**
    ```bash
    php artisan serve --host=0.0.0.0 --port=8000
    ```

2.  **Jalankan Vite (Frontend)**
    Buka terminal baru dan jalankan:
    ```bash
    npm run dev
    ```

3.  Akses aplikasi melalui browser di alamat yang sudah diset di `APP_URL` (contoh: `http://192.168.1.5:8000`).

## Akun Default (Login)

Berikut adalah akun yang dapat digunakan setelah menjalankan `db:seed`:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@anseyo.com` | `password` |
| **Kasir** | `cashier@anseyo.com` | `password` |
| **Dapur** | `kitchen@anseyo.com` | `password` |

## Lisensi

Proyek ini bersifat open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
