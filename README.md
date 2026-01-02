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

## Cara Instalasi (Windows dengan Laragon 6.0)

Ikuti langkah-langkah berikut untuk menjalankan proyek menggunakan **Laragon 6.0**:

1.  **Persiapan Laragon**
    - Pastikan Laragon sudah berjalan (Start All).
    - Pastikan service **Apache** (atau Nginx) dan **MySQL** aktif.

2.  **Clone Repository**
    Buka Terminal (Cmder) di Laragon, lalu masuk ke folder `www`:
    ```bash
    cd C:\laragon\www
    git clone https://github.com/ridwanpanji22/anseyo.git
    cd anseyo
    ```

3.  **Install Dependency**
    Jalankan perintah berikut secara berurutan:
    ```bash
    composer install
    npm install
    ```

4.  **Setup Environment**
    Salin file konfigurasi `.env`:
    ```bash
    cp .env.example .env
    ```
    Generate Application Key:
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database**
    - Buka **HeidiSQL** (tombol "Database" di Laragon).
    - Buat database baru dengan nama `anseyo`.
    - Buka file `.env` dan sesuaikan konfigurasi database (Default Laragon: user `root`, password kosong):
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=anseyo
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6.  **Konfigurasi URL (Penting untuk QR Code)**
    Agar QR Code dapat dipindai oleh HP pelanggan dalam jaringan WiFi yang sama:
    - Cek IP Address komputer Anda (buka terminal, ketik `ipconfig`).
    - Buka file `.env` dan ubah `APP_URL` menjadi IP Address tersebut.
    ```dotenv
    # Contoh jika IP komputer Anda 192.168.1.10
    APP_URL=http://192.168.1.10
    ```
    *(Catatan: Jika menggunakan fitur "Pretty URL" Laragon seperti `http://anseyo.test`, HP mungkin tidak bisa mengaksesnya kecuali ada konfigurasi DNS lokal. Menggunakan IP Address lebih aman untuk testing QR Code).*

7.  **Migrasi dan Seeding**
    Isi database dengan tabel dan data awal:
    ```bash
    php artisan migrate:fresh --seed
    ```

## Menjalankan Aplikasi

1.  **Akses Website**
    - Jika menggunakan IP Address di `.env`, akses via browser: `http://192.168.x.x/anseyo/public` (atau sesuaikan dengan konfigurasi Virtual Host Laragon jika sudah diset ke IP).
    - Atau jika hanya di laptop lokal: `http://anseyo.test`.

2.  **Jalankan Vite (Frontend)**
    Agar tampilan (CSS/JS) termuat dengan benar saat development:
    ```bash
    npm run dev
    ```

## Akun Default (Login)

Berikut adalah akun yang dapat digunakan setelah menjalankan `db:seed`:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@anseyo.com` | `password` |
| **Kasir** | `cashier@anseyo.com` | `password` |
| **Dapur** | `kitchen@anseyo.com` | `password` |

## Lisensi

Proyek ini bersifat open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
