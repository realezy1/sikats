# Integrasi API & Midtrans Webhook

Dokumen ini memaparkan alur teknis integrasi layanan pihak ketiga (Payment Gateway) dan komunikasi internal asinkron (*AJAX polling*).

## 1. Midtrans Snap & Webhook Flow

SiKats sangat bergantung pada Midtrans untuk merealisasikan visi dompet digital (*cashless*). Kami menggunakan layanan *Midtrans Snap* (Pop-up iFrame) dikombinasikan dengan pendeteksian otomatis melalui *Webhook*.

### A. Snap Token Generation
Setiap kali *Customer* (Self-order) atau *Cashier* memutuskan untuk membayar via *Online/Midtrans*, aplikasi akan memicu *Endpoint Internal*:
- **Method:** `POST /cashier/orders/{order_id}/checkout` (Kasir)
- **Logika Server (`OrderController`)**:
  Sistem akan mengompilasi rincian harga (disebut `item_details` dan `customer_details`), lalu memanggil API rahasia Midtrans via `\Midtrans\Snap::getSnapToken($params)`. Midtrans kemudian merespons dengan satu tiket sakti (*Snap Token*).
- **Client-Side:** Frontend menerima *Token* tersebut, lalu menjalankan `window.snap.pay(token)`. Layar pop-up pemilihan metode pembayaran seketika muncul.

### B. Menerima Webhook Midtrans (Notifikasi Latar Belakang)
Sering kali pelanggan yang telah mentransfer via Bank langsung mematikan aplikasi, sehingga *Callback UI/Frontend* gagal tercatat di database kita. Oleh karena itu, kita memutar otak dengan menyediakan gerbang *Webhook*.
- **Endpoint Webhook:** `POST /midtrans/webhook`
- **Cara Kerja Keamanan:**
  Sistem kita menerima Payload JSON (misalnya informasi bahwa `transaksi TRX-X` telah lunas/*settlement*). Sebelum mengeksekusi update status ke lunas (`paid`), sistem terlebih dahulu memverifikasi **Signature Key**!
  Rumus keamanan (sesuai standar Midtrans):
  `hash('sha512', $order_id . $status_code . $gross_amount . $server_key)`
  Hanya jika *hash* ini sama persis dengan yang dilampirkan Midtrans, maka SiKats yakin data tersebut tidak dipalsukan oleh peretas. Setelah itu `Order` kita diperbarui ke status Lunas.

## 2. AJAX Data Polling

Demi menekan biaya operasional *server* kecil restoran, SiKats **tidak** menggunakan *WebSockets* (Pusher/Socket.io) untuk sinkronisasi antarlayar.

Sebagai gantinya, aplikasi mengimplementasikan **AJAX Polling** yang dirancang efisien, dipicu oleh Javascript bawaan *Browser*.

### A. Endpoint Layar Dapur (KDS)
- Rute: `GET /kitchen` (Layar Induk)
- Alih-alih merancang API JSON yang harus direkonstruksi ulang via *Javascript/Vue*, fungsi ini cukup mengembalikan komponen Blade mentah yang memuat daftar pesanan. Javascript cukup menimpakan HTML barunya ke wadah elemen (`.innerHTML`). Dapur melakukan *polling* murni dengan *reload* ringan per 15 detik.

### B. Endpoint Daftar Pesanan Kasir
- Rute: `GET /cashier/orders/active-data`
- Mirip dengan Dapur, Kasir melakukan *fetch* internal per 5 detik untuk memastikan apabila pelayan kasir yang satu menginput pesanan, pelayan kasir di komputer lain bisa melihat sinkronisasinya tanpa me-*refresh* perambannya.

---
**Troubleshooting API Midtrans:**
- **Pesan *Error* 403 Forbidden pada Webhook:** Sering terjadi di lingkungan lokal jika koneksi `ngrok` Anda putus/ganti URL. Pastikan Anda telah meng-*update* "Payment Notification URL" pada *Dashboard Sandbox* Midtrans setiap kali menyalakan ngrok baru!
