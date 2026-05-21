# 🍟 McD E-Commerce Admin Guide

## 🎯 Ringkasan
Sistem admin McD E-Commerce sudah **SIAP DIGUNAKAN** dengan semua fitur CRUD produk, manajemen pesan, dan sistem pembayaran lengkap (COD + Transfer Bank dengan bukti transfer)!

## 🔐 Login Credentials

### Admin Access
- **Email:** `admin@mcd.com`
- **Password:** `admin123`
- **Dashboard:** http://localhost/admin/dashboard

### Regular User (untuk testing)
- **Email:** `user@mcd.com`
- **Password:** `user123`
- **Home:** http://localhost

## ✅ Fitur Admin yang Tersedia

### 1. 📊 Dashboard
- Overview statistik penjualan
- Total orders, revenue, pending orders
- Statistik hari ini
- Recent orders
- Orders by status

### 2. 🍔 Product Management (CRUD)
- **Create:** Tambah produk baru dengan gambar dan gallery
- **Read:** Lihat daftar semua produk dengan filter dan search
- **Update:** Edit produk existing
- **Delete:** Hapus produk
- **Toggle:** Ubah status availability dan featured

**URL:** http://localhost/admin/products

### 3. 📧 Message Management
- Lihat semua pesan dari contact form
- Mark pesan sebagai read/unread
- Filter pesan berdasarkan status
- Search pesan berdasarkan nama, email, atau subject
- Unread count di navigation

**URL:** http://localhost/admin/messages

### 4. 📦 Order Management
- Lihat semua orders dengan filter lengkap
- Update order status (pending → confirmed → processing → completed)
- Update payment status (pending → paid → failed)
- View order details dengan payment proof
- **FITUR BARU:** Lihat bukti transfer untuk pembayaran bank transfer

**URL:** http://localhost/admin/orders

### 5. 👥 User Management
- Lihat daftar semua users
- View user details dan order history

**URL:** http://localhost/admin/users

### 6. 📈 Reports
- Sales reports
- Analytics dashboard

**URL:** http://localhost/admin/reports

## 💳 Sistem Pembayaran Lengkap

### 1. 💵 Cash on Delivery (COD)
- Customer pilih COD saat checkout
- Order langsung dibuat tanpa perlu bukti pembayaran
- Admin bisa langsung proses order
- Pembayaran dilakukan saat barang diterima

### 2. 🏦 Bank Transfer
- Customer pilih Transfer Bank saat checkout
- Sistem redirect ke halaman payment
- Tampilkan detail rekening bank:
  - **Bank:** Bank Central Asia (BCA)
  - **No. Rekening:** 1234567890
  - **Atas Nama:** McDonald's Indonesia
- Customer upload bukti transfer (JPG/PNG, max 5MB)
- Order dibuat dengan status pending
- **Admin bisa melihat bukti transfer di order detail**

## 🛒 Fitur User yang Tersedia

### 1. 🏠 Public Pages
- **Home:** Produk featured dan kategori
- **Products:** Browse semua produk dengan filter
- **About:** Informasi tentang McD
- **Contact:** Form kontak (pesan masuk ke admin)

### 2. 🛍️ Shopping Features
- **Cart:** Tambah/hapus/update quantity produk
- **Checkout:** Proses pemesanan dengan alamat delivery
- **Payment:** Pilih metode pembayaran (COD/Transfer)
  - COD: Langsung selesai
  - Transfer: Upload bukti pembayaran
- **Orders:** Track status pesanan

### 3. 👤 Account Management
- Profile management
- Order history
- Password change

## 🔧 Cara Menggunakan

### Untuk Admin:
1. **Login** dengan credentials admin di atas
2. **Dashboard** untuk overview bisnis
3. **Kelola Produk:**
   - Klik "Add Product" untuk tambah produk baru
   - Upload gambar utama dan gallery (opsional)
   - Set kategori, harga, availability, featured status
   - Edit/hapus produk existing
4. **Kelola Orders:**
   - Lihat semua pesanan masuk
   - **Untuk Transfer Bank:** Klik order untuk lihat bukti transfer
   - Verifikasi pembayaran dan update status
   - Update order status sesuai progress
5. **Kelola Pesan:** Baca dan respond pesan dari customer

### Untuk Customer:
1. **Browse** produk di halaman Products
2. **Add to Cart** produk yang diinginkan
3. **Checkout** dengan isi alamat delivery
4. **Pilih Pembayaran:**
   - **COD:** Langsung selesai, bayar saat terima barang
   - **Transfer:** Upload bukti transfer setelah bayar
5. **Track Order** di halaman Orders

## 🚀 Status Sistem

✅ **SEMUA FITUR BERFUNGSI DENGAN BAIK!**

- ✅ Admin login & authentication
- ✅ Product CRUD operations
- ✅ Message management system
- ✅ Shopping cart & checkout
- ✅ **COD payment method**
- ✅ **Bank transfer with proof upload**
- ✅ **Admin can view payment proofs**
- ✅ Order management
- ✅ User registration & login
- ✅ File upload (product images & payment proofs)
- ✅ Database relationships
- ✅ Middleware protection
- ✅ Route protection

## 📊 Data Saat Ini
- **Products:** 52 produk tersedia
- **Categories:** 6 kategori
- **Featured Products:** 5 produk
- **Users:** 2 users (1 admin, 1 regular)

## 🔄 Workflow Pembayaran

### COD Workflow:
1. Customer checkout → pilih COD → Order dibuat langsung ✅
2. Admin terima notifikasi order baru ✅
3. Admin proses order (confirmed → processing → completed) ✅
4. Customer bayar saat terima barang ✅

### Transfer Bank Workflow:
1. Customer checkout → pilih Transfer Bank ✅
2. Sistem tampilkan detail rekening bank ✅
3. Customer transfer sesuai total order ✅
4. Customer upload bukti transfer ✅
5. Order dibuat dengan status pending ✅
6. **Admin lihat order detail + bukti transfer** ✅
7. **Admin verifikasi pembayaran** ✅
8. Admin update payment status → paid ✅
9. Admin update order status → confirmed → processing → completed ✅

## 🆘 Troubleshooting

### Jika Admin tidak bisa login:
1. Pastikan email: `admin@mcd.com` dan password: `admin123`
2. Jalankan: `php artisan db:seed --class=AdminUserSeeder`

### Jika checkout tidak bisa:
1. Pastikan user sudah login
2. Pastikan ada item di cart
3. Clear cache: `php artisan cache:clear`

### Jika gambar/bukti transfer tidak muncul:
1. Jalankan: `php artisan storage:link`
2. Pastikan folder storage/app/public writable

### Jika ada error 500:
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Check .env database settings

---

**🎉 Sistem lengkap dan siap digunakan! Admin dapat mengelola produk, melihat pesan, dan memproses pembayaran COD maupun Transfer Bank dengan bukti transfer.**