# Arsitektur Sistem & Direktori SiKats

SiKats dibangun menggunakan framework **Laravel 11** dan mengadopsi pola arsitektur **MVC (Model-View-Controller)** standar. Namun, sistem ini diperluas (*extended*) untuk mengakomodasi berbagai peran (*Roles*) pengguna secara modular guna mempermudah proses pemeliharaan.

## 1. Pemisahan Berdasarkan Peran (Role-Based Architecture)

Untuk mencegah terjadinya kode yang bercampur-aduk (*spaghetti code*), seluruh Controllers dan Views pada aplikasi dipisahkan ke dalam beberapa direktori khusus berdasarkan peran aktornya:

### A. Admin (`App\Http\Controllers\Admin`)
Folder khusus untuk pengelolaan master data dan fitur krusial yang hanya boleh diakses oleh administrator (`role_id: 1`).
- **Controllers:** `UserController`, `CategoryController`, `MenuController`, `TableController`, `ReportController`.
- **Views:** Berada di dalam folder `resources/views/admin/`.

### B. Cashier (`App\Http\Controllers\Cashier`)
Folder yang menangani *Point of Sales* manual oleh kasir (`role_id: 2`). Kasir tidak bisa mengubah data master pengguna atau meja.
- **Controllers:** `OrderController` (manajemen keranjang dan pembuatan pesanan), `SalesController` (riwayat penjualan kasir), `PaymentController`.
- **Views:** Berada di dalam folder `resources/views/cashier/`.

### C. Kitchen (`App\Http\Controllers\Kitchen`)
Digunakan secara eksklusif sebagai *Kitchen Display System (KDS)* untuk dapur (`role_id: 3`).
- **Controllers:** `KitchenDisplayController` (menampilkan antrean pesanan), `KitchenItemController` (mengubah status masakan per *item*).
- **Views:** Berada di dalam folder `resources/views/kitchen/`.

### D. Customer (`App\Http\Controllers\Customer`)
Logika *self-service* pesanan pelanggan via *QR Code*. Pelanggan berinteraksi tanpa akun, melainkan menggunakan `customer_session` yang terikat pada Meja (*Table*).
- **Controllers:** `CustomerOrderController`, `CartController`, `MenuBrowseController`.
- **Views:** Berada di dalam folder `resources/views/customer/` (sering kali dibedakan desain tata letaknya dengan kasir karena harus bersifat *mobile-first*).

## 2. Struktur Middleware dan Keamanan

Keamanan sistem dijaga berlapis melalui Middleware:
1. `auth`: Validasi pengguna yang memiliki akun (Admin, Kasir, Dapur).
2. `role`: Memeriksa level akses *Role*. Sebagai contoh, rute admin menggunakan proteksi `middleware(['auth', 'role:1'])`.
3. `customer.session`: Khusus rute *Customer*, memvalidasi dan memelihara sesi tanpa batas pendaftaran menggunakan *token meja* (`qr_token`).

## 3. Komponen Frontend

Sistem ini tidak menggunakan pendekatan *Single Page Application* (SPA) utuh seperti Vue/React guna menjaga kecepatan *development*. Sebaliknya, sistem ini mengandalkan:
- **Bootstrap 5.3**: Kerangka CSS utama untuk desain komponen antarmuka (*UI*) yang elegan dan sangat responsif.
- **AJAX Polling**: Mengingat sistem restoran memerlukan notifikasi *real-time* namun penggunaan WebSockets dirasa berlebihan (*over-engineering*) untuk skala ini, sistem menggunakan skema penarikan (*fetch*) interval ringan lewat AJAX untuk memperbarui *Dashboard* Kasir dan Layar Dapur.
- **Blade Layouts**: Induk halaman antarmuka dipisahkan (`resources/views/layouts/app.blade.php`) beserta navigasinya, memudahkan penerapan perubahan gaya secara serentak ke semua halaman.

## 4. Alur Interaksi Data (Model)

Sebagian besar *Model* berinteraksi menggunakan sistem keterikatan ORM Eloquent. Alur kasarnya adalah:
- `Table` (Meja) $\to$ menaungi `Order` (Transaksi Pesanan)
- `Order` $\to$ menaungi beberapa `OrderItem` (Rincian Menu)
- `OrderItem` $\to$ berhubungan secara *belongsTo* dengan `Menu`.

Perubahan krusial: Ketika harga `Menu` berganti esok hari, harga pada struk `OrderItem` kemarin tidak akan berubah karena ada atribut harga *snapshot* bernama `price_at_order`.
