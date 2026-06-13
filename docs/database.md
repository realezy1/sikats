# Skema Database & Entitas SiKats

Sistem database pada SiKats dirancang agar cukup tangkas (agile) dalam menangani pesanan *multi-role* dan menunjang integrasi pihak ketiga, terutama riwayat pembayaran (Payment Gateway).

Berikut adalah daftar tabel dan penjelasannya.

## 1. Tabel Master

### `users` (Manajemen Pengguna)
Berfungsi menyimpan seluruh data staf (Aktor internal) yang dapat login ke dalam sistem.
- `id` (Primary Key)
- `name` (String): Nama lengkap pengguna.
- `email` (String, Unique): Digunakan sebagai *username* login.
- `password` (String, Hashed): Kata sandi terenkripsi (Bcrypt).
- `role_id` (Integer): Tingkat akses. `1` = Admin, `2` = Kasir, `3` = Dapur.
- `mobile_number` (String, Nullable): Kontak.
- `is_active` (Boolean): Jika `false`, akun dilarang login.

### `tables` (Manajemen Meja Makan)
Pilar utama bagi fitur *Customer Self-Order* dan pelacakan makanan.
- `id` (Primary Key)
- `table_number` (Integer, Unique): Nomor fisik meja.
- `qr_token` (String, Unique): Token rahasia (`string random`) yang dibubuhkan pada *URL* QR Code. Berfungsi mencegah pesanan palsu (spam) dari luar restoran oleh orang yang sekadar iseng menebak URL.
- `is_active` (Boolean): Status meja dapat disembunyikan.

### `categories` & `menus` (Katalog)
Menyimpan referensi makanan/minuman yang tersedia di restoran.
- **categories**: `id`, `name`, `type`.
- **menus**: `id`, `category_id`, `name`, `price`, `stock` (stok akan berkurang otomatis saat dipesan), `photo` (URL foto relatif dari *storage* publik).

## 2. Tabel Operasional / Transaksional

### `orders` (Transaksi / Nota Utama)
Setiap kali pesanan dibuat (baik oleh pelanggan secara *self-order* atau manual oleh kasir), satu catatan order tercipta.
- `id` (String, Primary Key): Berformat khusus misalnya `TRX-20261231-1001`.
- `table_id` (Foreign Key): Relasi ke tabel meja.
- `customer_name` (String, Nullable): Opsional jika pesanan langsung.
- `user_id` (Foreign Key, Nullable): Pegawai yang menangani. Jika dikosongkan (null), berarti pesanan tersebut diinisiasi mandiri oleh pelanggan (self-order).
- `status` (Enum): `unpaid` (aktif/belum bayar), `paid` (sudah lunas), `completed` (selesai dilayani).
- `payment_method` (Enum): `cash` (Tunai) atau `midtrans` (Online).
- `total` (Decimal): Penjumlahan matematis dari seluruh item.
- `cash_amount` & `change_amount`: Uang diterima dan kembalian (khusus Tunai).

### `order_items` (Rincian Item Pesanan / Dapur)
Ini adalah "urat nadi" bagi Modul Layar Dapur (KDS). Setiap *item* pesanan dipantau progresnya secara individual.
- `id` (Primary Key)
- `order_id` (Foreign Key): Relasi ke order induk.
- `menu_id` (Foreign Key): Menu yang dibeli.
- `quantity` (Integer): Jumlah porsi.
- `price_at_order` (Decimal): **Penting!** Merupakan *snapshot* harga saat dibeli. Jika harga menu asli naik, nota ini akan tetap mencatat harga lama secara abadi (akuntansi valid).
- `note` (Text): Permintaan ekstra (misal: "Pedas, jangan pakai daun bawang").
- `status` (Integer): Jalur antrean masakan.
  - `0` = *Pending* / Antrean Dapur
  - `1` = Sedang Dimasak
  - `2` = Siap Saji (*Ready*)
- `accepted_at` & `ready_at`: *Timestamp* waktu respons staf dapur, digunakan untuk evaluasi SLA kecepatan dapur di masa mendatang.

## 3. Relasi Entitas (ERD Sederhana)
- Sebuah `Table` menampung banyak `Order` (One-to-Many).
- Sebuah `Order` menampung banyak `OrderItem` (One-to-Many).
- Sebuah `Category` menampung banyak `Menu` (One-to-Many).
- `OrderItem` berelasi dengan `Menu` (Many-to-One).
