# Panduan Instalasi & Konfigurasi SiKats

Dokumen ini berisi panduan teknis langkah demi langkah untuk melakukan instalasi dan menjalankan proyek SiKats (Sistem Kasir Terpadu) di lingkungan lokal (Development).

## Kebutuhan Sistem (Prerequisites)
Pastikan sistem Anda telah terinstal perangkat lunak berikut:
- **PHP** >= 8.2
- **Composer** (untuk manajemen dependensi PHP)
- **Node.js** & **NPM** (untuk kompilasi aset frontend)
- **MySQL** / MariaDB (versi 8.0 disarankan)
- **Git** (opsional, untuk versioning)

## Langkah Instalasi

1. **Kloning Repositori** (Jika menggunakan Git)
   ```bash
   git clone <url-repo-anda>
   cd sikats
   ```

2. **Instalasi Dependensi PHP**
   Jalankan perintah berikut untuk mengunduh semua *package* Laravel:
   ```bash
   composer install
   ```

3. **Instalasi Dependensi Frontend**
   Jalankan perintah berikut untuk mengunduh dan melakukan *build* aset CSS/JS:
   ```bash
   npm install
   npm run build
   ```
   *(Catatan: Anda juga bisa menggunakan `npm run dev` saat aktif melakukan pengembangan tampilan).*

4. **Konfigurasi Environment (`.env`)**
   Salin file konfigurasi bawaan Laravel:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` menggunakan teks editor pilihan Anda dan sesuaikan kredensial koneksi *database* MySQL Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_sikats
   DB_USERNAME=root
   DB_PASSWORD=password_anda
   ```

5. **Generate Application Key**
   Untuk mengamankan sesi dan enkripsi data:
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Midtrans (Payment Gateway)**
   Tambahkan konfigurasi kunci Midtrans di dalam `.env`. Anda bisa mendapatkan *Server Key* dan *Client Key* dari [Dashboard Midtrans Sandbox](https://simulator.sandbox.midtrans.com/).
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_SNAP_URL=https://app.sandbox.midtrans.com/snap/snap.js
   ```

7. **Konfigurasi Folder Penyimpanan (Storage)**
   Hubungkan direktori publik ke direktori *storage* agar foto menu bisa diakses via *browser*:
   ```bash
   php artisan storage:link
   ```

8. **Migrasi Database & Seeding**
   Langkah ini akan membangun tabel *database* dan mengisinya dengan data *dummy* awal (termasuk akun Admin default):
   ```bash
   php artisan migrate --seed
   ```
   *Catatan: Akun bawaan dapat dilihat di dalam file seeder `DatabaseSeeder.php`.*

## Menjalankan Server Lokal

Setelah seluruh instalasi selesai, jalankan *server development* Laravel bawaan:
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui peramban web (*browser*) di alamat:
**http://127.0.0.1:8000** atau **http://localhost:8000**

### Catatan Penting untuk Webhook Midtrans
Midtrans memerlukan URL publik yang bisa diakses internet untuk mengirimkan pemberitahuan *webhook* pembayaran. Jika Anda bekerja di *localhost*, Midtrans tidak bisa mengirim *request* ke `127.0.0.1`.

Oleh karena itu, Anda harus menggunakan layanan *tunneling* seperti **Ngrok** atau **Cloudflared**:
```bash
ngrok http 8000
```
Salin URL publik dari Ngrok (contoh: `https://abcd-1234.ngrok-free.app`) lalu gabungkan dengan *route webhook* kita (`/midtrans/webhook`) dan masukkan URL lengkap tersebut ke dalam pengaturan *Payment Notification URL* di Dashboard Midtrans Anda.
