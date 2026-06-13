# Product Requirements Document (PRD)

# SiKats — Migrasi ke Laravel & Penambahan Fitur Customer Self-Order + Midtrans

**Versi Dokumen**: 2.1  
**Tanggal**: Juni 2026  
**Status**: Active / Updated

---

## Daftar Isi

1. [Overview](#1-overview)
2. [Aktor dan Hak Akses (RBAC)](#2-aktor-dan-hak-akses-rbac)
3. [Fungsionalitas Utama](#3-fungsionalitas-utama)
   - 3.1 Autentikasi & Manajemen Akun
   - 3.2 Manajemen Master Data
   - 3.3 Sistem Pemesanan Internal (Kasir)
   - 3.4 Customer Self-Order (Tanpa Login)
   - 3.5 Layar Dapur (Kitchen Display)
   - 3.6 Pembayaran & Integrasi Midtrans
   - 3.7 Pelaporan (Reporting)
4. [Alur Bisnis (Business Flows)](#4-alur-bisnis-business-flows)
5. [Struktur Database](#5-struktur-database)
6. [Integrasi Midtrans](#6-integrasi-midtrans)
7. [Arsitektur & Teknologi](#7-arsitektur--teknologi)
8. [Strategi Migrasi ke Laravel](#8-strategi-migrasi-ke-laravel)
9. [Kebutuhan Non-Fungsional](#9-kebutuhan-non-fungsional)
10. [Acceptance Criteria per Fitur](#10-acceptance-criteria-per-fitur)
11. [Risiko & Mitigasi](#11-risiko--mitigasi)

---

## 1. Overview

### 1.1 Deskripsi Proyek

**SiKats** (Sistem Kasir Terpadu) adalah aplikasi manajemen Point of Sale (POS) dan pemesanan untuk restoran dan kafe. Sistem ini mengelola seluruh alur operasional harian — mulai dari penerimaan pesanan pelanggan, pemrosesan di dapur, hingga pembayaran dan pelaporan transaksi.

Saat ini SiKats dibangun menggunakan **PHP Native (prosedural)** dengan antarmuka **Bootstrap 5**, yang memiliki beberapa keterbatasan teknis terkait keamanan, maintainability, dan skalabilitas. Proyek ini bertujuan melakukan **migrasi penuh ke framework Laravel** sekaligus menambahkan dua fitur strategis baru:

1. **Customer Self-Order** — pelanggan dapat memesan makanan secara mandiri via perangkat mereka sendiri (smartphone) tanpa perlu login, menggunakan sistem QR Code per meja.
2. **Integrasi Payment Gateway Midtrans** — pelanggan dan kasir dapat memproses pembayaran secara digital melalui berbagai metode pembayaran (transfer bank, e-wallet, kartu kredit, QRIS, dll.).

### 1.2 Tujuan Migrasi & Pengembangan

| Tujuan                   | Deskripsi                                                                                               |
| ------------------------ | ------------------------------------------------------------------------------------------------------- |
| **Keamanan**             | Mencegah SQL Injection, XSS, dan CSRF menggunakan fitur native Laravel (Eloquent ORM, CSRF Token, dsb.) |
| **Maintainability**      | Struktur kode MVC yang terstandar, mudah di-onboard oleh developer baru                                 |
| **Skalabilitas**         | Arsitektur yang mendukung penambahan fitur baru (misalnya: API mobile, loyalty program, multi-outlet)   |
| **Pengalaman Pelanggan** | Pelanggan dapat memesan sendiri tanpa menunggu pelayan — mengurangi waktu tunggu dan beban staf          |
| **Efisiensi Pembayaran** | Mengurangi risiko kesalahan uang kembalian, mempercepat proses checkout melalui pembayaran digital      |

### 1.3 Ruang Lingkup (In Scope vs. Out of Scope)

**In Scope (versi ini):**

- Migrasi penuh kode PHP Native ke Laravel 11.x
- Role-Based Access Control (4 role: Admin, Kasir, Dapur, Customer)
- Fitur Customer Self-Order berbasis QR Code (tanpa akun/login)
- Integrasi Midtrans Snap sebagai payment gateway
- Laporan transaksi harian dan per periode
- Kitchen Display real-time berbasis polling/auto-refresh

**Out of Scope (untuk versi mendatang):**

- Aplikasi mobile native (iOS/Android)
- Sistem loyalty point pelanggan
- Multi-outlet / multi-cabang
- Integrasi akuntansi pihak ketiga
- Fitur reservasi meja di muka

---

## 2. Aktor dan Hak Akses (RBAC)

Sistem memiliki **5 aktor** dengan hak akses berbeda. Empat role pertama menggunakan autentikasi berbasis sesi (login), sementara role Customer bersifat _anonim_ (tanpa akun).

### 2.1 Ringkasan Role

| Role         | Basis Akses              | Deskripsi Singkat                     |
| ------------ | ------------------------ | ------------------------------------- |
| **Admin**    | Login (Level 1)          | Akses penuh ke seluruh sistem         |
| **Kasir**    | Login (Level 2)          | Kelola order, pembayaran, dan menu    |
| **Dapur**    | Login (Level 3)          | Lihat dan update status masakan       |
| **Customer** | Tanpa Login (QR Session) | Memesan makanan secara mandiri via QR |

### 2.2 Detail Hak Akses per Role

#### Admin (Level 1)

- Mengakses seluruh modul tanpa pengecualian.
- **Eksklusif**: Manajemen User (CRUD), Laporan Penjualan (semua periode), reset password user lain, pengaturan sistem (nama restoran, logo, dsb.).
- Dapat mengambil alih dan memodifikasi transaksi yang sedang berlangsung.
- Mengatur konfigurasi integrasi Midtrans (API Key, Merchant ID).
- Melihat log aktivitas sistem (audit trail dasar).

#### Kasir / Cashier (Level 2)

- Membuat dan mengelola transaksi order secara penuh.
- Melakukan proses checkout dan konfirmasi pembayaran (tunai maupun digital via Midtrans).
- Melihat dan mencetak struk/invoice transaksi.
- Mengelola data Kategori Menu dan data Menu (CRUD).
- Melihat laporan transaksi harian miliknya.
- **Tidak bisa**: Mengelola user, melihat laporan seluruh kasir, atau mengubah konfigurasi sistem.

#### Kitchen / Dapur (Level 3)

- Melihat antrian order item yang masuk secara real-time.
- Mengubah status item: **Pending → Sedang Dimasak → Siap Disajikan**.
- Memfilter antrian berdasarkan kategori (misal: hanya minuman atau makanan).
- **Tidak bisa**: Membuat order, melihat detail pembayaran, atau mengakses modul lain.

#### Customer (Tanpa Login — QR Session)

- Mengakses aplikasi melalui QR Code yang tertempel di meja.
- QR Code membawa parameter `table_number` yang di-embed ke dalam URL session.
- Browsing menu (dengan foto, deskripsi, harga, dan status ketersediaan stok).
- Menambahkan item ke keranjang, mengatur kuantitas, dan menambahkan catatan khusus per item.
- Mengirimkan pesanan langsung ke sistem (tanpa perlu interaksi staf).
- Melakukan pembayaran mandiri melalui Midtrans Snap.
- Melacak status pesanan mereka (Pending / Dimasak / Siap / Lunas) secara real-time.
- **Tidak bisa**: Mengakses data order meja lain, melihat laporan, atau masuk ke panel admin.

---

## 3. Fungsionalitas Utama

### 3.1 Autentikasi & Manajemen Akun

#### Login & Logout

- Form login dengan input `email` dan `password`.
- Autentikasi menggunakan Laravel Breeze dengan Guard bawaan.
- Setelah login, user diarahkan ke dashboard sesuai role masing-masing.
- Proteksi brute force: pembatasan percobaan login (Rate Limiting via Laravel).
- Logout membersihkan sesi dan mengarahkan ke halaman login.

#### Reset & Ganti Password

- **Reset oleh Admin**: Admin dapat mereset password user lain dari halaman Manajemen User.
- **Ganti Password Sendiri**: Semua user yang login dapat mengganti password mereka sendiri melalui menu profil, dengan syarat memasukkan password lama terlebih dahulu.
- Validasi: password minimal 8 karakter, konfirmasi password baru harus cocok.

#### Manajemen User (Admin Only)

- **Create**: Form penambahan user baru dengan field: Nama Lengkap, Email Address (unik), Password, Role, Nomor HP, dan Alamat.
- **Read**: Tabel daftar user yang dapat difilter berdasarkan role dan dicari berdasarkan nama/email. Ditampilkan dengan paginasi.
- **Update**: Form edit data user. Admin tidak dapat mengedit akun dirinya sendiri melalui form ini (harus via menu profil).
- **Delete**: Soft delete — user tidak dihapus permanen dari database melainkan ditandai tidak aktif, agar histori transaksi terkait tetap terjaga.
- **Status Toggle**: Admin dapat menonaktifkan/mengaktifkan kembali akun user.

---

### 3.2 Manajemen Master Data

#### Kategori Menu

- **Create**: Form penambahan kategori dengan field Nama Kategori dan Tipe (misal: Makanan, Minuman, Dessert).
- **Read**: Tabel daftar kategori dengan informasi jumlah menu yang terhubung.
- **Update**: Form edit nama dan tipe kategori.
- **Delete**: Kategori hanya dapat dihapus jika tidak ada menu yang terhubung. Jika masih ada menu, sistem menampilkan peringatan dan menolak penghapusan.

#### Manajemen Menu

- **Create**: Form penambahan menu baru dengan field:
  - Nama menu
  - Deskripsi (teks bebas)
  - Kategori (dropdown dari data kategori)
  - Harga (angka, dalam Rupiah)
  - Stok (jumlah ketersediaan; 0 = habis / tidak tersedia)
  - Foto (unggah gambar; format JPG/PNG/WEBP; maks. 2MB; disimpan di storage Laravel)
  - Status Aktif (toggle: aktif / nonaktif — menu nonaktif tidak muncul di daftar Customer)
- **Read**: Tampilan daftar menu dalam format tabel (untuk panel admin) dan format kartu/grid (untuk tampilan Customer). Dapat difilter berdasarkan kategori dan status stok.
- **Update**: Form edit semua field menu. Jika foto baru diunggah, foto lama dihapus dari storage.
- **Delete**: Soft delete. Menu tidak dapat dihapus permanen jika masih terkait dengan order aktif.
- **Stok Otomatis**: Setiap kali item di-order, stok berkurang secara otomatis. Admin/Kasir dapat melakukan restock manual dari halaman Manajemen Menu.

---

### 3.3 Sistem Pemesanan Internal (Kasir)

#### Pembuatan Order Baru

- **Kode Transaksi Otomatis**: Format `TRX-YYYYMMDD-XXXX` (contoh: `TRX-20260615-0042`), di-generate otomatis oleh sistem.
- **Input Data Meja**: Nomor meja (input angka atau pilihan dari dropdown meja tersedia) dan nama pelanggan (opsional untuk dine-in).
- **Validasi Meja**: Sistem memperingatkan jika nomor meja yang dipilih sudah memiliki order aktif yang belum selesai (unpaid).

#### Penambahan Item ke Order

- Tampilan menu dalam format kartu dengan foto, nama, harga, dan indikator stok.
- Filter menu berdasarkan kategori.
- Pencarian menu berdasarkan nama.
- Setiap item dapat ditambahkan dengan:
  - **Kuantitas** (input angka; default 1; maksimum sesuai stok tersedia).
  - **Catatan Khusus** (teks bebas; opsional; contoh: "Tidak pedas", "Extra keju").
- Tampilan ringkasan keranjang (order summary) di sisi kanan layar menampilkan semua item yang sudah dipilih beserta subtotal dan total sementara.

#### Modifikasi Order

- Selama order berstatus `unpaid` (belum checkout), Kasir dapat:
  - Menambah item baru ke dalam order.
  - Mengubah kuantitas item yang sudah ada.
  - Menghapus item dari order (hanya item dengan status `Pending` di dapur — item yang sudah `Sedang Dimasak` atau `Siap` tidak dapat dihapus).
  - Mengubah catatan khusus per item.
- Setiap perubahan langsung memperbarui total harga di tampilan order.

#### Daftar Pesanan Aktif (Real-Time)

- Halaman ini memonitor semua pesanan yang sedang berjalan (status `unpaid`, `proses`, dan `ready`).
- Menggunakan AJAX Polling setiap 5 detik agar kasir bisa memantau perubahan status (terutama saat dapur menyelesaikan masakan menjadi `Siap Saji`) secara instan tanpa perlu memuat ulang halaman.

---

### 3.4 Customer Self-Order (Tanpa Login)

Ini adalah fitur baru yang memungkinkan pelanggan memesan secara mandiri melalui perangkat mereka tanpa membutuhkan akun atau intervensi staf.

#### 3.4.1 Sistem QR Code Meja

- Setiap meja memiliki QR Code unik yang di-generate oleh sistem (oleh Admin).
- QR Code memuat URL dengan format: `https://[domain]/order?table=[nomor_meja]&token=[token_meja]`
  - `table`: Nomor meja yang bersifat tetap.
  - `token`: Token validasi yang dapat di-regenerasi oleh Admin (berguna untuk mencegah akses dari luar meja).
- Saat pelanggan scan QR Code, aplikasi membuat **Customer Session** anonim yang terikat pada nomor meja tersebut.
- Customer Session disimpan di server dengan TTL (Time to Live) **4 jam** — cukup untuk satu sesi makan.
- Jika meja sudah memiliki order aktif yang belum selesai dari customer sebelumnya, sistem tetap membuka sesi baru (untuk pemesanan ulang atau penambahan item).

#### 3.4.2 Halaman Menu & Browsing

- Tampilan responsif berbasis mobile-first (dioptimalkan untuk smartphone).
- Header menampilkan: nama restoran, nomor meja, dan ikon keranjang dengan jumlah item.
- Menu ditampilkan dalam format kartu vertikal dengan:
  - Foto menu (lazy-loaded).
  - Nama dan deskripsi singkat.
  - Harga (format Rupiah: Rp X.XXX).
  - Badge "Habis" jika stok = 0 (item tidak dapat ditambahkan ke keranjang).
  - Badge kategori.
- Tab atau chip filter berdasarkan kategori di bagian atas.
- Kolom pencarian menu berdasarkan nama.
- Menu yang berstatus nonaktif tidak ditampilkan sama sekali.

#### 3.4.3 Keranjang (Cart)

- Pelanggan menambahkan item ke keranjang dengan tombol `+` di setiap kartu menu.
- Halaman keranjang menampilkan:
  - Daftar semua item yang dipilih.
  - Kuantitas per item (dapat diubah langsung di keranjang).
  - Field catatan khusus per item.
  - Subtotal per item.
  - **Total keseluruhan** di bagian bawah.
- Pelanggan dapat menghapus item dari keranjang.
- Keranjang disimpan di sisi server (terikat pada Customer Session) agar tidak hilang jika pelanggan refresh halaman.
- Tombol **"Pesan Sekarang"** di bagian bawah mengirimkan pesanan ke sistem.

#### 3.4.4 Pengiriman Pesanan (Order Submission)

- Saat pelanggan menekan "Pesan Sekarang", sistem:
  1. Membuat record `orders` baru dengan `source = 'customer'` dan status `unpaid`.
  2. Membuat record `order_items` untuk setiap item di keranjang.
  3. Mengirimkan notifikasi ke panel Kasir bahwa ada pesanan baru dari meja X (ditampilkan sebagai badge/alert di dashboard).
  4. Menampilkan halaman konfirmasi kepada pelanggan dengan nomor order dan daftar item yang dipesan.
- Validasi sebelum submit:
  - Stok semua item harus masih mencukupi saat submit. Jika ada yang habis, sistem menampilkan pesan error per item dan meminta pelanggan memperbarui keranjang.
  - Keranjang tidak boleh kosong.

#### 3.4.5 Pelacakan Status Pesanan (Order Tracking)

- Setelah order dikirim, pelanggan dapat memantau status pesanan mereka di halaman Order Tracking.
- Halaman ini menampilkan:
  - Nomor transaksi.
  - Status keseluruhan order.
  - Status per item (Pending / Sedang Dimasak / Siap Disajikan).
  - Visual progress indicator (step-by-step).
- Halaman auto-refresh setiap **15 detik** (polling) untuk memperbarui status tanpa perlu reload manual.
- Tombol **"Bayar Sekarang"** muncul ketika semua item berstatus `Siap Disajikan`, mengarahkan pelanggan ke halaman pembayaran Midtrans.

#### 3.4.6 Penambahan Pesanan (Re-order dalam satu sesi)

- Selama Customer Session masih aktif dan order belum `paid`, pelanggan dapat menambahkan pesanan baru (misalnya memesan tambahan minuman).
- Sistem bertanya kepada pelanggan apakah pesanan baru akan digabungkan ke order yang ada atau dibuat sebagai order terpisah.
  - **Gabungkan**: Item baru ditambahkan ke `order_items` dari order aktif yang sudah ada.
  - **Order Baru**: Record `orders` baru dibuat (satu meja bisa punya beberapa order aktif secara bersamaan dalam skenario ini).

---

### 3.5 Layar Dapur (Kitchen Display)

#### Tampilan Antrian Order

- Halaman khusus untuk Role Dapur, didesain untuk layar besar (TV/monitor dapur).
- Menampilkan kartu per order yang berisi:
  - Nomor meja.
  - Kode transaksi.
  - Waktu order masuk (dan berapa menit yang lalu).
  - Daftar item yang dipesan beserta kuantitas dan catatan khusus.
  - Status saat ini per item.
  - Indikator sumber order: badge "Kasir" atau "Customer Self-Order".
- Halaman auto-refresh setiap **10 detik** (polling).
- Filter tampilan berdasarkan kategori menu (berguna untuk dapur yang memiliki area memasak terpisah).

#### Aksi Update Status

- **Tombol "Terima" (Accept)**: Mengubah status item dari `Pending` (0) → `Sedang Dimasak` (1). Kartu berubah warna (misal: kuning).
- **Tombol "Selesai" (Ready)**: Mengubah status item dari `Sedang Dimasak` (1) → `Siap Disajikan` (2). Kartu berubah warna (misal: hijau).
- Tombol dapat diaktuasi per item atau per order (Accept All / Ready All).
- Setiap perubahan status tercatat dengan timestamp di database.

#### Notifikasi Visual & Suara (Opsional)

- Order baru yang masuk memunculkan highlight visual (kedip/animasi) dan bunyi notifikasi browser (menggunakan Web Audio API atau audio file sederhana).
- Order yang sudah lebih dari 15 menit belum di-Accept akan ditandai dengan warna merah sebagai peringatan keterlambatan.

---

### 3.6 Pembayaran & Integrasi Midtrans

#### 3.6.1 Pembayaran Tunai (Existing — Disempurnakan)

- Kasir membuka halaman Checkout dari order yang aktif.
- Sistem menampilkan:
  - Ringkasan semua item yang dipesan.
  - Total harga.
  - Field "Uang Diterima" (input angka oleh Kasir).
  - Kalkulasi kembalian otomatis (tampil real-time saat Kasir mengetik nominal uang diterima).
- Validasi: Uang diterima tidak boleh kurang dari total harga.
- Tombol "Konfirmasi Pembayaran Tunai" mengubah status order menjadi `proses` (Pay-First Flow) dan mencatat waktu pembayaran.
- Sistem otomatis mencetak/menampilkan struk dalam format PDF. Struk ini akan dibuka otomatis di tab baru (new tab), sedangkan halaman order akan memuat ulang (reload) agar kasir dapat memantau pesanan yang masuk ke dapur.

#### 3.6.2 Pembayaran Digital via Midtrans (Fitur Baru)

Tersedia dua skenario pembayaran Midtrans:

**Skenario A: Kasir yang Menginisiasi (untuk order manual)**

1. Kasir membuka halaman Checkout dan memilih metode "Bayar Digital (Midtrans)".
2. Sistem membuat Midtrans Transaction Token di backend.
3. Popup Midtrans Snap terbuka di layar Kasir, menampilkan berbagai pilihan metode pembayaran.
4. Pelanggan memilih metode dan menyelesaikan pembayaran.
5. Midtrans mengirimkan webhook ke server SiKats untuk konfirmasi.
6. Server memverifikasi signature webhook dan mengubah status order menjadi `paid`.
7. Struk digital ditampilkan.

**Skenario B: Customer yang Membayar Sendiri (untuk Customer Self-Order)**

1. Pelanggan menekan "Bayar Sekarang" dari halaman Order Tracking.
2. Sistem membuat Midtrans Transaction Token di backend, terikat pada `customer_session` dan `order_id`.
3. Popup Midtrans Snap terbuka di smartphone pelanggan.
4. Pelanggan memilih metode pembayaran (GoPay, OVO, DANA, QRIS, Transfer Bank, dsb.) dan menyelesaikan.
5. Midtrans mengirimkan webhook ke server.
6. Server memverifikasi dan mengubah status order menjadi `paid`.
7. Pelanggan diarahkan ke halaman konfirmasi pembayaran berhasil.

Detail teknis integrasi Midtrans dibahas lebih lanjut di Bagian 6.

---

### 3.7 Pelaporan (Reporting)

#### Riwayat Transaksi

- Menampilkan semua transaksi dalam tabel yang dapat difilter berdasarkan:
  - Rentang tanggal (date range picker).
  - Status (Paid / Unpaid).
  - Role user yang melayani (Kasir atau Customer Self-Order).
  - Metode pembayaran (Tunai / Midtrans).
- Paginasi 20 baris per halaman dengan opsi export ke CSV atau PDF.

#### Detail Invoice

- Setiap baris transaksi dapat dibuka untuk melihat detail invoice yang memuat:
  - Kode transaksi dan nomor meja.
  - Nama pelanggan (jika diisi).
  - Nama user yang melayani (Kasir) atau "Customer Self-Order".
  - Waktu order dibuat dan waktu pembayaran.
  - Rincian setiap item (nama, kuantitas, harga satuan, subtotal).
  - Total harga.
  - Metode pembayaran dan, jika tunai, uang diterima dan kembalian.
  - ID Transaksi Midtrans (jika pembayaran digital).

#### Ringkasan Penjualan (Admin Only)

- Dashboard ringkasan yang menampilkan:
  - Total pendapatan hari ini.
  - Jumlah transaksi hari ini.
  - Grafik pendapatan 7 hari terakhir (bar chart sederhana).
  - Menu terlaris (top 5) berdasarkan kuantitas terjual.
  - Perbandingan pendapatan tunai vs. digital.

---

## 4. Alur Bisnis (Business Flows)

### 4.1 Alur Order Internal (Kasir)

```
[Kasir Login]
        │
        ▼
[Buat Order Baru] ──► [Input Nomor Meja & Nama Pelanggan]
        │
        ▼
[Tambah Item dari Menu] ──► [Set Kuantitas & Catatan]
        │
        ▼
[Kirim ke Dapur] ──────────────────────────────────────────────►
        │                                                        │
        ▼                                              [Dapur: Terima → Masak → Siap]
[Order Aktif Tersimpan]
        │
        ▼ (saat semua item siap / pelanggan minta bayar)
[Kasir Buka Checkout]
        │
        ├──► [Tunai] ──► [Input Uang Diterima] ──► [Konfirmasi] ──► [Paid ✓]
        │
        └──► [Midtrans] ──► [Buka Snap Popup] ──► [Pelanggan Bayar] ──► [Webhook] ──► [Paid ✓]
```

### 4.2 Alur Customer Self-Order

```
[Pelanggan Scan QR Code Meja]
        │
        ▼
[Customer Session Dibuat (anonim, terikat nomor meja)]
        │
        ▼
[Browsing Menu] ──► [Filter Kategori / Cari]
        │
        ▼
[Tambah ke Keranjang] ──► [Set Kuantitas & Catatan]
        │
        ▼
[Review Keranjang & Total]
        │
        ▼
[Pesan Sekarang] ──► [Validasi Stok Real-time]
        │
        ▼
[Order Tersimpan] ──► [Notifikasi ke Dashboard Kasir]
        │
        ▼
[Halaman Order Tracking] ──► [Auto-refresh Status]
        │                              │
        │                    [Dapur: Terima → Masak → Siap]
        │
        ▼ (semua item Siap)
[Tombol "Bayar Sekarang" Muncul]
        │
        ▼
[Midtrans Snap Terbuka di HP Pelanggan]
        │
        ▼
[Pelanggan Pilih Metode & Bayar]
        │
        ▼
[Webhook Midtrans → Server Verifikasi Signature]
        │
        ▼
[Order Status → Paid ✓] ──► [Halaman Konfirmasi Pelanggan]
```

---

## 5. Struktur Database

### Skema Relasional Lengkap

#### Tabel `users`

```sql
users
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── name             VARCHAR(100) NOT NULL
├── email            VARCHAR(255) NOT NULL UNIQUE
├── password         VARCHAR(255) NOT NULL (hashed bcrypt)
├── role_id          TINYINT      NOT NULL (1=Admin, 2=Kasir, 3=Dapur)
├── mobile_number    VARCHAR(20)  NULLABLE
├── address          TEXT         NULLABLE
├── is_active        BOOLEAN      DEFAULT TRUE
├── deleted_at       TIMESTAMP    NULLABLE (soft delete)
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `categories`

```sql
categories
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── name             VARCHAR(100) NOT NULL
├── type             VARCHAR(50)  NOT NULL (e.g., 'Makanan', 'Minuman', 'Dessert')
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `menus`

```sql
menus
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── category_id      BIGINT UNSIGNED FK → categories.id
├── name             VARCHAR(150) NOT NULL
├── description      TEXT         NULLABLE
├── price            DECIMAL(12,2) NOT NULL
├── stock            INT UNSIGNED DEFAULT 0
├── photo            VARCHAR(255) NULLABLE (path di storage)
├── is_active        BOOLEAN      DEFAULT TRUE
├── deleted_at       TIMESTAMP    NULLABLE (soft delete)
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `tables` _(Baru)_

```sql
tables
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── table_number     INT UNSIGNED NOT NULL UNIQUE
├── qr_token         VARCHAR(64)  NOT NULL UNIQUE (untuk validasi QR)
├── qr_code_path     VARCHAR(255) NULLABLE (path gambar QR yang di-generate)
├── is_active        BOOLEAN      DEFAULT TRUE
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `customer_sessions` _(Baru)_

```sql
customer_sessions
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── session_token    VARCHAR(64)  NOT NULL UNIQUE (random token unik per sesi)
├── table_id         BIGINT UNSIGNED FK → tables.id
├── cart_data        JSON         NULLABLE (snapshot keranjang, untuk persistensi)
├── expires_at       TIMESTAMP    NOT NULL (TTL 4 jam dari created_at)
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `orders`

```sql
orders
├── id               VARCHAR(30)  PK (format: TRX-YYYYMMDD-XXXX)
├── table_id         BIGINT UNSIGNED FK → tables.id NULLABLE
├── customer_name    VARCHAR(100) NULLABLE
├── user_id          BIGINT UNSIGNED FK → users.id NULLABLE (Kasir yang melayani; NULL jika self-order)
├── customer_session_id BIGINT UNSIGNED FK → customer_sessions.id NULLABLE
├── source           ENUM('staff', 'customer') DEFAULT 'staff'
├── status           ENUM('unpaid', 'paid') DEFAULT 'unpaid'
├── payment_method   ENUM('cash', 'midtrans') NULLABLE
├── cash_amount      DECIMAL(12,2) NULLABLE (uang tunai yang diterima)
├── change_amount    DECIMAL(12,2) NULLABLE (uang kembalian)
├── payment_time     TIMESTAMP    NULLABLE
├── midtrans_order_id VARCHAR(100) NULLABLE (ID unik yang dikirim ke Midtrans)
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

#### Tabel `order_items`

```sql
order_items
├── id               BIGINT UNSIGNED PK AUTO_INCREMENT
├── order_id         VARCHAR(30)  FK → orders.id
├── menu_id          BIGINT UNSIGNED FK → menus.id
├── quantity         INT UNSIGNED NOT NULL DEFAULT 1
├── price_at_order   DECIMAL(12,2) NOT NULL (harga saat order dibuat — immutable)
├── note             TEXT         NULLABLE
├── status           TINYINT      DEFAULT 0
│                                  (0=Pending, 1=Sedang Dimasak, 2=Siap Disajikan)
├── accepted_at      TIMESTAMP    NULLABLE (waktu dapur Accept)
├── ready_at         TIMESTAMP    NULLABLE (waktu dapur mark Ready)
├── created_at       TIMESTAMP
└── updated_at       TIMESTAMP
```

> **Catatan penting**: `price_at_order` menyimpan harga menu pada saat pesanan dibuat. Ini penting karena harga menu bisa berubah di kemudian hari, namun invoice historis harus tetap akurat.



### Relasi Antar Tabel (ERD Summary)

```
users ──────────────────────── orders (user_id)
tables ─────────────────────── orders (table_id)
tables ─────────────────────── customer_sessions (table_id)
customer_sessions ──────────── orders (customer_session_id)
orders ─────────────────────── order_items (order_id)
menus ──────────────────────── order_items (menu_id)
categories ─────────────────── menus (category_id)
```

---

## 6. Integrasi Midtrans

### 6.1 Gambaran Umum

SiKats menggunakan **Midtrans Snap** sebagai payment gateway. Midtrans Snap menyediakan popup UI yang sudah jadi dan terintegrasi dengan berbagai metode pembayaran tanpa perlu membangun UI pembayaran dari awal.

**Metode Pembayaran yang Tersedia via Midtrans Snap:**

- QRIS (semua e-wallet via QR universal)
- GoPay, OVO, DANA, ShopeePay
- Transfer Bank (BCA, Mandiri, BNI, BRI, dll.)
- Kartu Kredit / Debit Visa & Mastercard
- Indomaret / Alfamart (opsional)

### 6.2 Konfigurasi

**Environment Variable (`.env`):**

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false  # ubah ke true di production
MIDTRANS_SNAP_URL=https://app.sandbox.midtrans.com/snap/snap.js
```

**Konfigurasi di `config/midtrans.php`:**

```php
return [
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'snap_url'      => env('MIDTRANS_SNAP_URL'),
];
```

### 6.3 Alur Teknis Pembayaran Midtrans

#### Langkah 1: Buat Transaction Token (Server-Side)

```
POST /api/payment/create-token
Body: { order_id: "TRX-20260615-0042" }

Controller:
1. Ambil data order dan hitung total.
2. Buat payload Midtrans:
   - order_id: midtrans_order_id (unik, bisa order_id + timestamp)
   - gross_amount: total order
   - customer_details: nama pelanggan & nomor meja (sebagai info)
   - item_details: array dari order_items
3. Request ke Midtrans API → dapatkan snap_token.
4. Simpan midtrans_order_id ke tabel orders.
5. Return snap_token ke frontend.
```

#### Langkah 2: Tampilkan Snap Popup (Client-Side)

```javascript
// Di halaman Blade / view
snap.pay(snapToken, {
  onSuccess: function (result) {
    // Tampilkan pesan sukses; tunggu webhook untuk update DB
    window.location.href = "/order/success/" + orderId;
  },
  onPending: function (result) {
    window.location.href = "/order/pending/" + orderId;
  },
  onError: function (result) {
    // Tampilkan pesan error
  },
  onClose: function () {
    // Pelanggan menutup popup tanpa bayar
  },
});
```

#### Langkah 3: Terima & Proses Webhook (Server-Side)

```
POST /webhook/midtrans
(URL ini didaftarkan di dashboard Midtrans)

Controller MidtransWebhookController:
1. Terima payload JSON dari Midtrans.
2. Verifikasi signature key:
   SHA512(order_id + status_code + gross_amount + server_key)
   Harus cocok dengan signature_key di payload.
3. Jika verifikasi gagal → return HTTP 403.
4. Cek transaction_status:
   - 'capture' atau 'settlement' → tandai order sebagai paid
   - 'deny', 'cancel', 'expire' → tandai pembayaran gagal
   - 'pending' → tidak ada aksi (tunggu update berikutnya)
5. Update tabel orders (status = 'paid', payment_time = now())
6. Update tabel payments (semua field midtrans_*)
7. Return HTTP 200.
```

### 6.4 Keamanan Midtrans

- **Webhook Signature Verification**: Wajib diimplementasikan. Setiap webhook harus diverifikasi signature-nya sebelum diproses.
- **Idempotency**: Webhook handler harus idempotent — jika webhook yang sama diterima dua kali, sistem tidak boleh memproses pembayaran dua kali.
- **HTTPS Only**: Endpoint webhook wajib menggunakan HTTPS.
- **Server Key Protection**: Server Key Midtrans hanya ada di backend dan tidak pernah dikirim ke frontend.
- **Whitelist IP Midtrans** (opsional namun disarankan): Batasi akses ke endpoint webhook hanya dari IP Midtrans yang terdaftar.

### 6.5 Penanganan Kasus Edge (Edge Cases)

| Skenario                                                      | Penanganan                                                                                                |
| ------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| Pelanggan tutup popup Snap sebelum bayar                      | Order tetap `unpaid`, pelanggan bisa membuka popup lagi dengan snap_token yang sama (valid selama 24 jam) |
| Webhook datang setelah order sudah `paid`                     | Diabaikan (idempotency check via `midtrans_transaction_id`)                                               |
| Pembayaran `pending` (misal transfer bank belum dikonfirmasi) | Tampilkan status "Menunggu Konfirmasi Pembayaran" kepada pelanggan/kasir                                  |
| Midtrans timeout / tidak tersedia                             | Fallback ke pembayaran tunai; tampilkan pesan error yang ramah                                            |
| Gross amount di webhook berbeda dengan total order            | Log error, tolak proses, kirim alert ke Admin                                                             |

---

## 7. Arsitektur & Teknologi

### 7.1 Stack Teknologi

| Komponen                | Teknologi                                                      |
| ----------------------- | -------------------------------------------------------------- |
| Framework Backend       | Laravel 11.x (PHP 8.2+)                                        |
| Template Engine         | Laravel Blade Components                                       |
| Autentikasi Internal    | Laravel Breeze                                                 |
| Otorisasi               | Laravel Gates & Policies + Middleware                          |
| ORM                     | Eloquent ORM                                                   |
| Database                | MySQL 8.x                                                      |
| Frontend UI             | Bootstrap 5.3                                                  |
| Interaktivitas Frontend | Alpine.js (ringan, cocok dengan Blade)                         |
| Payment Gateway         | Midtrans Snap                                                  |
| Storage File            | Laravel Storage (local / S3-compatible)                        |
| Queue & Job             | Laravel Queue (database driver) — untuk webhook processing     |
| QR Code Generator       | `simplesoftwareio/simple-qrcode` (Laravel package)             |
| Server                  | Ubuntu + Nginx + PHP-FPM (atau Laragon/Herd untuk development) |

### 7.2 Struktur Direktori Laravel (Utama)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── UserController.php
│   │   │   ├── TableController.php          (manajemen meja & QR)
│   │   │   └── ReportController.php
│   │   ├── Cashier/
│   │   │   ├── CategoryController.php
│   │   │   ├── MenuController.php
│   │   │   ├── OrderController.php
│   │   │   └── PaymentController.php
│   │   ├── Kitchen/
│   │   │   └── KitchenDisplayController.php
│   │   ├── Customer/
│   │   │   ├── MenuBrowseController.php     (tampilan menu publik)
│   │   │   ├── CartController.php
│   │   │   ├── CustomerOrderController.php
│   │   │   └── OrderTrackingController.php
│   │   └── Webhook/
│   │       └── MidtransWebhookController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php               (cek level akses)
│   │   └── CustomerSessionMiddleware.php   (validasi QR session)
│   └── Requests/
│       ├── StoreMenuRequest.php
│       ├── StoreOrderRequest.php
│       └── CheckoutRequest.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Menu.php
│   ├── Table.php
│   ├── CustomerSession.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Payment.php
└── Services/
    ├── MidtransService.php                 (semua logic Midtrans)
    ├── OrderService.php                    (business logic order)
    └── QrCodeService.php                   (generate & manage QR)
```

### 7.3 Routing Struktur

```
routes/
├── web.php          → Route untuk panel admin/kasir/dapur (auth)
├── customer.php     → Route untuk Customer Self-Order (non-auth, session-based)
└── api.php          → Route untuk webhook Midtrans & endpoint AJAX internal
```

---

## 8. Strategi Migrasi ke Laravel

### 8.1 Rencana Tahapan Eksekusi

#### Tahap 1: Inisialisasi Proyek & Setup Database _(Estimasi: 2–3 hari)_

- [ ] Inisialisasi proyek Laravel 11 baru dengan `composer create-project`.
- [ ] Konfigurasi `.env`: database, storage, queue driver.
- [ ] Instalasi Laravel Breeze untuk scaffolding autentikasi.
- [ ] Instalasi package tambahan: `midtrans/midtrans-php`, `simplesoftwareio/simple-qrcode`.
- [ ] Buat semua file `Migration` sesuai skema database di Bagian 5.
- [ ] Buat `Seeder` untuk data awal: 1 akun Admin, kategori menu contoh, data meja (1–10).
- [ ] Buat `Factories` untuk keperluan testing.

#### Tahap 2: Templating & Layout Blade _(Estimasi: 2 hari)_

- [ ] Konversi `main.php`, `header.php`, `sidebar.php` PHP Native → Blade Components.
- [ ] Buat layout utama `app.blade.php` (untuk panel internal) dengan sidebar dinamis sesuai role.
- [ ] Buat layout `customer.blade.php` (untuk halaman Customer Self-Order) — mobile-first, tanpa sidebar.
- [ ] Pastikan semua aset Bootstrap 5 dan custom CSS ter-compile dengan benar (via Vite).

#### Tahap 3: Autentikasi & RBAC _(Estimasi: 2 hari)_

- [ ] Konfigurasi Laravel Breeze: halaman login, logout, forgot password.
- [ ] Implementasi `RoleMiddleware` yang membaca `role_id` dari model User.
- [ ] Daftarkan middleware di `bootstrap/app.php` dan kelompokkan route per role.
- [ ] Implementasi Laravel Gates untuk aksi-aksi sensitif (misal: hapus user, lihat semua laporan).
- [ ] Implementasi `CustomerSessionMiddleware` untuk memvalidasi QR Token.

#### Tahap 4: Master Data (CRUD) _(Estimasi: 3 hari)_

- [ ] `UserController` (Admin): CRUD dengan soft delete, toggle status aktif, dan reset password.
- [ ] `CategoryController` (Kasir/Admin): CRUD dengan validasi relasi.
- [ ] `MenuController` (Kasir/Admin): CRUD dengan upload foto (Laravel Storage + validasi MIME/size).
- [ ] `TableController` (Admin): CRUD meja dan generate/regenerate QR Code per meja.

#### Tahap 5: Sistem Pemesanan Internal _(Estimasi: 4–5 hari)_

- [ ] `OrderController`: Buat order baru, tampilan order aktif, tambah/edit/hapus item.
- [ ] `OrderService`: Business logic seperti generate kode transaksi, validasi stok, dan kalkulasi total.
- [ ] Form Request `StoreOrderRequest` dan `AddOrderItemRequest` dengan semua validasi.
- [ ] Tampilan daftar order aktif yang bisa diakses Kasir.

#### Tahap 6: Customer Self-Order _(Estimasi: 4–5 hari)_

- [ ] Halaman publik browsing menu dengan filter kategori dan pencarian.
- [ ] `CartController`: Tambah item, ubah kuantitas, hapus item (data cart di server via CustomerSession).
- [ ] `CustomerOrderController`: Submit order dari cart ke database.
- [ ] Halaman Order Tracking dengan auto-refresh polling.
- [ ] Implementasi TTL pada CustomerSession (cleanup via Laravel Scheduled Job).

#### Tahap 7: Kitchen Display _(Estimasi: 2 hari)_

- [ ] `KitchenDisplayController`: Tampil antrian order item dengan filter kategori.
- [ ] Endpoint AJAX untuk update status item (Accept & Ready).
- [ ] Implementasi auto-refresh halaman Kitchen setiap 10 detik.
- [ ] Notifikasi visual untuk order baru masuk.

#### Tahap 8: Pembayaran & Midtrans _(Estimasi: 4–5 hari)_

- [ ] `MidtransService`: Class service untuk create Snap Token dan verify signature webhook.
- [ ] `PaymentController`: Checkout tunai dan checkout via Midtrans.
- [ ] Integrasi Midtrans Snap di frontend (Blade + Alpine.js).
- [ ] `MidtransWebhookController`: Endpoint penerima webhook dengan verifikasi signature dan idempotency.
- [ ] Testing menyeluruh di environment Midtrans Sandbox.

#### Tahap 9: Pelaporan _(Estimasi: 2 hari)_

- [ ] `ReportController`: Halaman riwayat transaksi dengan filter dan paginasi.
- [ ] Detail invoice per transaksi.
- [ ] Halaman dashboard ringkasan penjualan (Admin).
- [ ] Fitur export CSV menggunakan Laravel Excel atau built-in response.

#### Tahap 10: QA, Testing & Deploy _(Estimasi: 3–4 hari)_

- [ ] Uji fungsional semua role: Admin, Kasir, Dapur, Customer.
- [ ] Uji seluruh alur pembayaran Midtrans di Sandbox (semua metode utama).
- [ ] Uji edge cases: stok habis saat submit, webhook duplikat, session expired.
- [ ] Review keamanan: CSRF, XSS, SQL Injection, otorisasi antar role.
- [ ] Setup server production: Nginx config, HTTPS (SSL), environment production.
- [ ] Deploy dan migrasi data dari database PHP Native lama (jika diperlukan).

**Total Estimasi: ±28–32 hari kerja**

---

## 9. Kebutuhan Non-Fungsional

### 9.1 Performa

- Halaman panel internal (admin/kasir/dapur) harus load dalam **< 2 detik** pada koneksi normal.
- Halaman Customer Self-Order harus load dalam **< 3 detik** pada koneksi mobile (4G).
- Proses create Midtrans Snap Token harus selesai dalam **< 5 detik**.

### 9.2 Keamanan

- Semua form menggunakan Laravel CSRF Token.
- Password disimpan menggunakan **bcrypt** (default Laravel).
- Hanya HTTPS di environment production.
- File upload divalidasi berdasarkan MIME type dan ukuran; disimpan di luar direktori publik web.
- Server Key Midtrans tidak pernah dikirim ke client-side.
- Customer Session Token berbentuk random string 64 karakter yang di-generate menggunakan `Str::random(64)`.

### 9.3 Ketersediaan (Availability)

- Target uptime: 99% pada jam operasional restoran (08.00–23.00 WIB).
- Jika Midtrans mengalami gangguan, sistem tetap berfungsi untuk pembayaran tunai.

### 9.4 Skalabilitas

- Arsitektur yang mendukung penambahan outlet baru di masa depan dengan penambahan tabel `outlets` dan relasi ke `tables` dan `users`.
- Queue Laravel memungkinkan pemrosesan webhook secara asinkron jika volume transaksi tinggi.

### 9.5 Pengalaman Pengguna (UX)

- Antarmuka Customer Self-Order harus dapat digunakan tanpa instruksi tambahan (self-explanatory).
- Semua pesan error harus ramah dan informatif (bukan pesan teknis/raw).
- Tombol aksi utama harus mudah ditemukan dan memiliki feedback visual yang jelas (loading state, sukses, error).

---

## 10. Acceptance Criteria per Fitur

### AC-01: Login

- ✅ User dengan kredensial valid dapat login dan diarahkan ke dashboard sesuai role.
- ✅ User dengan kredensial salah mendapatkan pesan error "Kredensial yang diberikan tidak cocok dengan data kami.".
- ✅ User dengan status nonaktif (is_active = false) akan ditolak login dengan pesan "Akun Anda telah dinonaktifkan. Silakan hubungi admin.".
- ✅ Setelah 5 percobaan gagal dalam 10 menit, akses login diblokir sementara.

### AC-02: Manajemen Menu

- ✅ Admin/Kasir dapat menambahkan menu baru dengan semua field terisi.
- ✅ Menu dengan stok 0 menampilkan badge "Habis" dan tidak dapat dipesan oleh Customer.
- ✅ Stok berkurang otomatis ketika item berhasil di-order.

### AC-03: Customer Self-Order

- ✅ Scan QR Code membuka halaman menu dengan nomor meja yang benar.
- ✅ Customer dapat menambahkan item, mengubah kuantitas, menambahkan catatan, dan submit order.
- ✅ Setelah order disubmit, halaman tracking menampilkan status yang diperbarui setiap 15 detik.
- ✅ Jika stok habis saat submit, sistem menolak order dan menginformasikan item mana yang bermasalah.

### AC-04: Kitchen Display

- ✅ Semua order item yang berstatus Pending muncul di Kitchen Display.
- ✅ Klik "Terima" mengubah status menjadi Sedang Dimasak dan memperbarui tampilan.
- ✅ Klik "Selesai" mengubah status menjadi Siap Disajikan.
- ✅ Halaman otomatis refresh setiap 10 detik tanpa interaksi user.

### AC-05: Pembayaran Tunai

- ✅ Kasir memasukkan nominal uang, kembalian terhitung otomatis secara real-time.
- ✅ Konfirmasi pembayaran mengubah status order menjadi `paid` dengan timestamp.
- ✅ Struk invoice dapat dilihat dan diprint.

### AC-06: Pembayaran Midtrans

- ✅ Tombol "Bayar Digital" memunculkan popup Midtrans Snap.
- ✅ Pembayaran yang berhasil di Sandbox Midtrans mengubah status order menjadi `paid` via webhook.
- ✅ Webhook yang diterima diverifikasi signature-nya sebelum diproses.
- ✅ Webhook yang sama tidak menyebabkan double-update status.

### AC-07: Laporan

- ✅ Riwayat transaksi dapat difilter berdasarkan rentang tanggal.
- ✅ Detail invoice menampilkan informasi lengkap termasuk metode pembayaran.
- ✅ Data dapat diexport ke format CSV.

---

## 11. Risiko & Mitigasi

| #   | Risiko                                                                         | Tingkat | Mitigasi                                                                                                          |
| --- | ------------------------------------------------------------------------------ | ------- | ----------------------------------------------------------------------------------------------------------------- |
| R01 | Midtrans Sandbox tidak merepresentasikan perilaku production secara akurat     | Medium  | Lakukan testing production dengan transaksi kecil (Rp 1.000) sebelum go-live penuh                                |
| R02 | Pelanggan menggunakan QR Code dari meja lain secara sengaja atau tidak sengaja | Low     | Validasi token QR per meja; Admin dapat regenerate token; Kasir tetap dapat verifikasi nomor meja secara fisik    |
| R03 | Webhook Midtrans tidak diterima (network issue)                                | Medium  | Implementasi job queue untuk retry; endpoint check status Midtrans secara manual via API                          |
| R04 | Migrasi data dari PHP Native ke Laravel menyebabkan inkonsistensi              | Medium  | Buat script migrasi data dengan validasi setelah migrasi; jalankan di staging dulu sebelum production             |
| R05 | Performa Kitchen Display lambat pada volume order tinggi                       | Low     | Optimasi query dengan Eager Loading Eloquent; tambahkan index pada kolom `status` dan `order_id` di `order_items` |
| R06 | Foto menu memenuhi kapasitas storage                                           | Low     | Implementasi kompresi gambar otomatis saat upload; gunakan cloud storage (S3-compatible) untuk production         |

---

_Dokumen ini bersifat living document dan akan diperbarui seiring perkembangan proyek._  
_Versi berikutnya akan mencakup spesifikasi detail untuk fitur mobile API dan multi-outlet._
