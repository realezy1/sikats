# Skenario Black Box Testing - SiKats (Sistem Kasir & Terintegrasi Self-Order)

Dokumen ini berisi daftar skenario pengujian *Black Box* untuk memvalidasi fungsionalitas sistem SiKats berdasarkan spesifikasi kebutuhan (PRD) dan panduan pengguna. Pengujian berfokus pada *input* pengguna dan *output* sistem tanpa melihat struktur kode internal.

---

## 1. Modul Autentikasi (Login/Logout)

| ID Test | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan (Expected Result) | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- |
| AUTH-01 | Login dengan kredensial valid | 1. Masukkan Email valid<br>2. Masukkan Password valid<br>3. Klik tombol Login | Pengguna berhasil masuk dan diarahkan ke Dashboard sesuai *Role* masing-masing (Admin/Kasir/Dapur). | |
| AUTH-02 | Login dengan password salah | 1. Masukkan Email valid<br>2. Masukkan Password salah<br>3. Klik Login | Sistem menolak akses dan menampilkan pesan error kredensial tidak valid. | |
| AUTH-03 | Akses halaman internal tanpa login | 1. Kunjungi langsung URL `/admin/users` atau `/cashier/orders` | Sistem mencegah akses dan otomatis me-*redirect* kembali ke halaman Login. | |
| AUTH-04 | Logout dari sistem | 1. Klik menu profil<br>2. Klik Logout | Sesi diakhiri, pengguna dikembalikan ke halaman Login. Menekan tombol "Back" di browser tidak bisa masuk lagi tanpa login ulang. | |

---

## 2. Modul Admin (Manajemen Restoran)

| ID Test | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan (Expected Result) | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- |
| ADM-01 | Menambahkan akun pegawai baru | 1. Masuk menu *Manajemen Pegawai*<br>2. Isi form lengkap (Nama, Email, Password, Role)<br>3. Simpan | Data tersimpan di tabel pegawai, pegawai baru tersebut bisa login dengan kredensial yang baru dibuat. | |
| ADM-02 | Validasi duplikasi email pegawai | 1. Tambah pegawai baru<br>2. Gunakan Email yang sudah ada di database<br>3. Simpan | Sistem memunculkan pesan error validasi bahwa email sudah terdaftar. | |
| ADM-03 | Menambahkan Menu Baru dengan stok 0 | 1. Buka halaman *Menu*<br>2. Isi rincian menu<br>3. Set stok = 0<br>4. Simpan | Menu tersimpan. Namun saat dilihat oleh Customer/Kasir, tombol pemesanan tidak aktif atau berstatus "Habis". | |
| ADM-04 | Mencetak QR Code Meja | 1. Buka menu *Meja & QR Code*<br>2. Klik Cetak pada salah satu meja | Halaman Print terbuka, menampilkan gambar QR Code dengan URL yang memuat *token* spesifik meja tersebut. | |
| ADM-05 | Reset Token QR Meja | 1. Pada daftar Meja, klik aksi *Reset Token*<br>2. Konfirmasi reset | Token berubah. QR Code lama menjadi tidak valid jika di-scan oleh pelanggan. | |

---

## 3. Modul Kasir (Point of Sales)

| ID Test | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan (Expected Result) | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- |
| KSR-01 | Membuat pesanan manual | 1. Klik *Buat Pesanan Baru*<br>2. Pilih Meja dan masukkan Nama Pelanggan<br>3. Tambahkan 2 menu ke keranjang<br>4. Proses Checkout | Pesanan berhasil dibuat, meja tercatat sedang digunakan, pesanan terkirim ke modul Dapur. | |
| KSR-02 | Pembayaran Tunai dengan kembalian | 1. Pilih pesanan aktif<br>2. Klik *Checkout & Bayar* -> *Tunai*<br>3. Masukkan jumlah uang tunai yang lebih besar dari total tagihan | Sistem berhasil memproses pembayaran, menampilkan rincian uang kembalian pelanggan, dan status order berubah menjadi Lunas (Paid). | |
| KSR-03 | Pembayaran Midtrans (Cashless) via Kasir | 1. Pilih pesanan aktif<br>2. Klik *Checkout & Bayar* -> *Midtrans*<br>3. Pop-up Snap Midtrans muncul | Layar Snap Midtrans tampil sempurna, user bisa memilih metode pembayaran (QRIS/Transfer/dll). | |
| KSR-04 | Ubah pesanan ke status Selesai | 1. Pilih pesanan yang statusnya sudah "Siap Saji" dan "Lunas"<br>2. Klik *Sajikan & Selesai* | Pesanan tertutup, meja kembali tersedia (status kosong), nota bisa dicetak. | |

---

## 4. Modul Dapur (Kitchen Display System)

| ID Test | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan (Expected Result) | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- |
| DPR-01 | Menerima notifikasi pesanan masuk | 1. Pastikan KDS terbuka<br>2. (Di browser lain) Buat pesanan via Kasir/Customer | Layar Dapur memperbarui daftar secara otomatis (AJAX Polling), kartu pesanan baru muncul. | |
| DPR-02 | Mengubah status pesanan ke "Dimasak" | 1. Klik *Terima Pesanan* pada kartu pesanan yang berstatus *Unpaid/Baru* | Status item berubah menjadi "Diproses" (kuning). Kasir dan Pelanggan bisa melihat status terbaru ini. | |
| DPR-03 | Mengubah status pesanan ke "Siap Saji" | 1. Klik *Selesai (Siap Saji)* pada menu yang sedang dimasak | Status berubah menjadi "Siap Saji" (hijau), memberikan tanda ke pelayan/kasir bahwa makanan siap diantar. | |

---

## 5. Modul Customer (Self-Order & Midtrans)

| ID Test | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan (Expected Result) | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- |
| CST-01 | Akses URL QR Code valid | 1. Scan/Buka link QR Code yang valid (`?table=X&token=Y`) | Halaman katalog makanan terbuka dan sistem membuat sesi pelanggan yang terikat ke meja tersebut. | |
| CST-02 | Akses URL tanpa Token yang valid | 1. Buka URL `/order?table=1` tanpa token, atau dengan token yang diubah acak | Sistem menolak akses dan memunculkan error "Akses ditolak: QR Code tidak valid". | |
| CST-03 | Validasi Stok Menu | 1. Tambahkan menu ke keranjang dengan jumlah melebihi sisa stok di *database*<br>2. Lakukan checkout | Sistem menolak pesanan dan memunculkan peringatan bahwa stok tidak mencukupi untuk dipesan. | |
| CST-04 | Pembuatan pesanan sukses | 1. Masukkan menu ke keranjang<br>2. Masukkan nama/nomor identifikasi<br>3. Klik *Pesan Sekarang* | Order masuk ke sistem (terbaca di Dapur/Kasir) dan pop-up Midtrans otomatis terbuka untuk meminta pembayaran. | |
| CST-05 | Webhook Midtrans (Notifikasi Pembayaran Sukses) | 1. Selesaikan pembayaran di layar Midtrans<br>2. Midtrans mengirim JSON notifikasi via *Webhook* | Sistem menangkap webhook, memvalidasi hash *signature key*, dan otomatis mengubah status pembayaran pesanan menjadi "Lunas". | |
| CST-06 | Sesi Pelanggan Meja Penuh | 1. Meja sedang melayani pesanan yang belum selesai<br>2. Scan kembali QR Code Meja tersebut | Sistem memberikan opsi untuk menambahkan pesanan ke meja tersebut (Add Order) atau menghentikan (Meja sedang dipakai). | |
