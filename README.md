# SiKats - Point of Sales (POS) System

**SiKats** adalah aplikasi kasir (Point of Sales) berbasis web modern yang dirancang khusus untuk restoran, kafe, atau bisnis F&B. Aplikasi ini dibangun menggunakan framework **Laravel 11** dan mengusung sistem pemesanan modern dengan alur *Pay-First* (Pesan -> Bayar -> Masak -> Sajikan).

## 🚀 Fitur Utama

- **Role Management:**
  - **Admin:** Mengelola master data (Pengguna, Meja, Kategori, Menu, Stok).
  - **Kasir:** Melayani pelanggan, membuat pesanan, dan memproses pembayaran.
  - **Waiter (Pelayan):** Membantu melayani pelanggan dan membuat pesanan.
  - **Dapur (Kitchen):** Memantau pesanan yang masuk secara real-time dan memperbarui status masakan (Antrean -> Dimasak -> Siap).

- **Sistem Pembayaran Fleksibel:**
  - **Tunai (Cash):** Pembayaran tunai dengan kalkulasi kembalian otomatis.
  - **Midtrans Integration:** Mendukung pembayaran cashless (QRIS, GoPay, Transfer Bank, dll) menggunakan gateway Midtrans.

- **Real-Time Synchronisation (KDS & Active Orders):**
  - **Kitchen Display System (KDS):** Layar dapur akan otomatis diperbarui. Dapur hanya melihat pesanan yang sudah dibayar (status `proses`).
  - **Active Orders:** Layar kasir dan pelayan akan menarik pembaruan secara real-time (menggunakan AJAX polling) sehingga status "Siap Saji" dari dapur dapat langsung terpantau tanpa perlu me-refresh halaman.

- **Cetak Struk PDF:**
  - Struk transaksi akan otomatis dibuat dalam format PDF (menggunakan `barryvdh/laravel-dompdf`) setelah pembayaran berhasil. Struk akan dibuka di tab baru secara otomatis.

- **Desain UI/UX Modern & Responsif:**
  - Tampilan yang bersih, minimalis, dan sangat responsif di perangkat mobile maupun desktop, memanfaatkan Bootstrap 5, ikon Bootstrap, dan SweetAlert2.

## 🛠 Teknologi yang Digunakan

- **Backend:** Laravel 11.x, PHP 8.3
- **Database:** SQLite (Default, mudah digunakan untuk environment lokal/testing) atau MySQL.
- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript.
- **Library Tambahan:**
  - `midtrans/midtrans-php` untuk Payment Gateway
  - `barryvdh/laravel-dompdf` untuk generate struk PDF
  - `realrashid/sweet-alert` untuk notifikasi pop-up yang cantik

## 📦 Persyaratan Sistem

- PHP 8.2 atau lebih baru
- Composer 2.x
- Node.js & NPM (untuk aset frontend, jika menggunakan Vite/Tailwind di masa depan)

## ⚙️ Panduan Instalasi

1. **Clone repositori ini atau salin folder project ke komputer Anda.**

2. **Install dependensi PHP menggunakan Composer:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Lalu *generate* application key:
   ```bash
   php artisan key:generate
   ```

4. **Konfigurasi Database & Migrasi:**
   Secara default, project ini menggunakan SQLite. Anda dapat menjalankan perintah migrasi dan seeder untuk langsung mengisi data awal (dummy data):
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Data Seeder akan membuatkan akun Admin, Kasir, Waiter, dan Dapur default beserta beberapa contoh Menu).*

6. **Konfigurasi Midtrans (Wajib untuk Pembayaran Online):**
   Buka file `.env` dan sesuaikan kredensial Midtrans Anda:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_IS_SANITIZED=true
   MIDTRANS_IS_3DS=true
   ```

7. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses di `http://localhost:8000`.

## 🎭 Akun Uji Coba (Jika Menggunakan Seeder)

Apabila Anda telah menjalankan `php artisan db:seed`, Anda bisa menggunakan akun berikut untuk login:

- **Admin:** `admin@example.com` / Password: `password`
- **Kasir:** `kasir@example.com` / Password: `password`
- **Waiter:** `waiter@example.com` / Password: `password`
- **Dapur:** `dapur@example.com` / Password: `password`

## 📡 Konfigurasi Webhook Midtrans (Opsional/Production)

Agar aplikasi dapat menerima notifikasi otomatis dari Midtrans ketika pelanggan selesai membayar, pastikan untuk mengatur **Notification URL** di dashboard Midtrans (Environment Sandbox/Production) agar mengarah ke:
`https://domain-anda.com/midtrans/webhook`

*(Jika berjalan di localhost, Anda dapat menggunakan [Ngrok](https://ngrok.com/) untuk mendapatkan public URL lalu menambahkannya ke dashboard Midtrans).*

---
Dibuat dengan ❤️ untuk kemudahan operasional restoran.
