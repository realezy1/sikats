# Panduan Penggunaan SiKats (User Guide)

Aplikasi SiKats membedakan tampilan dan fitur yang bisa diakses berdasarkan status akun/peran (Role). Berikut adalah panduan operasional harian untuk setiap *Role*.

---

## 👨‍💻 1. Level: Admin (Manajemen Restoran)
Akun ini memiliki otoritas tertinggi untuk merombak master data restoran.

**Cara Menggunakan:**
1. Login dengan kredensial Admin. Anda akan langsung diarahkan ke *Dashboard* Daftar Pegawai.
2. **Kelola Staf:** Buka menu **Manajemen Pegawai**. Klik "Tambah Pegawai" untuk membuat akun baru bagi kasir atau staf dapur yang baru masuk.
3. **Kelola Kategori & Menu:** Sebelum berjualan, pastikan Admin telah membuat Kategori Makanan di menu "Kategori". Lalu, pada halaman "Menu", unggah foto-foto makanan, tetapkan harga, dan **set stok**. (Penting: Jika stok 0, pelanggan tidak dapat membelinya).
4. **Cetak QR Code:** Buka halaman "Meja & QR Code". Tambahkan nomor meja restoran Anda. Sistem akan menghasilkan gambar *QR Code* canggih. Klik "Cetak" untuk mengeprint QR Code tersebut untuk ditempel di fisik meja.
5. **Laporan & Akuntansi:** Buka menu "Laporan Penjualan" di akhir hari untuk memantau pendapatan (Berapa banyak via Tunai vs Online/Midtrans). Admin juga bisa mengekspornya ke dalam format dokumen PDF.

---

## 👩‍💼 2. Level: Kasir (Point of Sales)
Akun yang paling aktif dipakai bertransaksi langsung dengan tamu yang berada di meja kasir.

**Cara Menggunakan:**
1. Login, dan kasir akan diarahkan ke layar **Pesanan Aktif**.
2. **Membuat Pesanan Baru:** Jika pelanggan datang memesan ke kasir (bukan pesan mandiri), klik "Buat Pesanan Baru".
3. **Memilih Meja & Pelanggan:** Pilih meja kosong dari *dropdown*, dan masukkan nama pelanggan.
4. **Katalog (POS):** Layar akan berubah menjadi etalase makanan. Klik/tambahkan catatan khusus pada kartu menu yang dipesan, lalu klik tombol "Plus (+)" berwarna biru.
5. **Manajemen Keranjang (Offcanvas):** Seluruh rincian biaya akan terjumlah secara otomatis. Pada tampilan *mobile*, klik *Floating Action Button* "🛒 Lihat Keranjang" di bawah.
6. **Checkout & Pembayaran:** 
   - Klik tombol hijau "Checkout & Bayar".
   - Jika tamu bayar uang pas/tunai, masukkan jumlah uang diterima, sistem akan menakar kembaliannya.
   - Jika tamu meminta fitur *cashless*, pilih **Midtrans**. *Pop-up* resmi akan terbuka dan meminta pelanggan untuk *Scan* QRIS atau menggunakan fitur transfer bank. Pembayaran akan terkonfirmasi secara otomatis tanpa campur tangan kasir!
7. **Sajikan:** Setelah lunas dan makanan telah dimasak, klik tombol "Sajikan & Selesai" untuk menutup pesanan.

---

## 👨‍🍳 3. Level: Dapur (Kitchen Display System - KDS)
Tidak ada layar rumit bagi staf dapur, layar ini murni untuk produktivitas.

**Cara Menggunakan:**
1. Login ke layar tablet/monitor yang ada di Dapur.
2. Setiap kali kasir atau pelanggan (dari QR Code) melakukan pemesanan, kartu pesanan besar akan berbunyi dan muncul secara otomatis di monitor (Layar *Auto-refresh* setiap 15 detik).
3. **Terima Pesanan:** Saat tim dapur mulai menumis/memasak menu tersebut, klik tombol kuning "Terima Pesanan".
4. **Siap Disajikan:** Apabila piring masakan sudah diletakkan di meja serah terima untuk diambil oleh pelayan/kasir, segera klik tombol hijau "Selesai (Siap Saji)". Ini akan memicu perubahan status ke Kasir dan ponsel pelanggan!

---

## 📱 4. Level: Customer (Pemesanan Mandiri via QR)
Fitur *Self-Service* tanpa pendaftaran akun apa pun.

**Cara Menggunakan:**
1. Tamu datang, duduk di meja nomor 5, dan men-scan *QR Code* di meja tersebut menggunakan kamera ponsel mereka.
2. Secara magis, peramban akan langsung memunculkan menu katalog SiKats (Sesi akan diikat secara otomatis ke Meja 5).
3. Tamu memilih makanan, memasukkan ke keranjang, dan memastikan pesanannya benar.
4. Klik **Pesan Sekarang**. Notifikasi pemesanan akan langsung merambat masuk ke Layar Dapur (KDS) dan Layar Kasir!
5. Tamu disuguhi layar *Tracking* yang diperbarui secara *real-time*. Jika koki menekan tombol "Terima Pesanan", status di ponsel tamu akan berubah menjadi "Dimasak".
6. Setelah makan, Tamu bisa langsung mengeklik "Bayar" via ponsel, memilih metode e-Wallet, lunas, dan pulang dengan riang tanpa perlu mengantre ke meja kasir!
