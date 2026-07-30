# Sistem Inventaris Barang Sekolah

Sistem Inventaris Barang Sekolah adalah sebuah aplikasi berbasis web yang dibangun menggunakan **Laravel**. Aplikasi ini dirancang secara komprehensif untuk memudahkan manajemen, pencatatan, mutasi, perbaikan, serta pelaporan aset di lingkungan sekolah. Sistem ini mendukung alur kerja persetujuan (approval) berjenjang dan dilengkapi dengan berbagai fitur otomatisasi dokumen seperti cetak label QR Code, Berita Acara (BAST), dan ekspor laporan.

## 🚀 Fitur Utama

Sistem ini memiliki dua hak akses utama: **Admin** dan **Kepala Sekolah (Kepsek)**, dengan pembagian fitur sebagai berikut:

### 1. Manajemen Hak Akses (Multi-Role)
- **Admin**: Memiliki akses penuh untuk mengelola master data, transaksi barang, pengajuan pengadaan, perbaikan, dan mencetak laporan.
- **Kepala Sekolah**: Memiliki hak untuk menyetujui (approve/reject) berbagai pengajuan serta memantau data barang dan mutasi secara *read-only*.

### 2. Master Data Management (Admin)
- Manajemen Kategori Barang & Lokasi.
- Manajemen Sumber Dana & Supplier.
- Manajemen Identitas Sekolah.
- Manajemen Pengguna (*User Management*) dengan fitur *Trash* dan *Restore*.

### 3. Manajemen Aset & Barang
- Pendataan barang secara detail dan terstruktur.
- Fitur *Soft Delete (Trash & Restore)* untuk mencegah kehilangan data secara tidak sengaja.
- **Cetak Label QR Code**: Mendukung pencetakan label satuan maupun massal (*batch*) untuk mempermudah identifikasi barang secara fisik.

### 4. Transaksi & Mutasi Barang
- Pencatatan riwayat perpindahan/mutasi barang antar lokasi atau penanggung jawab.
- **Cetak BAST**: Otomatisasi cetak Berita Acara Serah Terima (BAST) setiap kali terjadi mutasi barang.

### 5. Manajemen Perbaikan (Maintenance)
- Pencatatan riwayat perbaikan aset/barang yang rusak.
- Pencetakan dokumen perbaikan dalam format PDF.

### 6. Pengajuan Pengadaan Barang
- Alur pengajuan pengadaan barang baru yang sistematis.
- **Cetak PO**: Pencetakan Purchase Order (PO) secara otomatis setelah pengajuan disetujui.

### 7. Sistem Persetujuan Berjenjang (Approval - Kepsek)
Kepala Sekolah menerima notifikasi *real-time* (polling) dan dapat memberikan persetujuan untuk:
- Penghapusan Aset
- Pengajuan Pengadaan Barang Baru
- Perubahan Data Barang
- Mutasi Barang

### 8. Laporan & Evaluasi
- Pembuatan laporan aset dan evaluasi inventaris.
- Ekspor laporan ke berbagai format (Excel & PDF) untuk kebutuhan audit dan dokumentasi.

## 🛠️ Teknologi yang Digunakan
- **Framework:** Laravel (PHP ^8.2)
- **Database:** MySQL / MariaDB
- **PDF Generator:** `barryvdh/laravel-dompdf`
- **Export/Import Excel:** `maatwebsite/excel`
- **QR Code Generator:** `simplesoftwareio/simple-qrcode`

## 📋 Panduan Penggunaan (Instalasi Lokal)

Untuk menjalankan aplikasi ini di *environment* lokal (komputer Anda), ikuti langkah-langkah berikut:

### Prasyarat Sistem
- **PHP** >= 8.2
- **Composer** (Dependency Manager untuk PHP)
- **Node.js & NPM** (Untuk kompilasi aset frontend)
- **Database Server** (MySQL/MariaDB/XAMPP/Laragon)

### Langkah Instalasi
1. **Clone Repository**
   Buka terminal atau command prompt, lalu jalankan:
   ```bash
   git clone <url-repo-anda>
   cd sisteminventari-g
   ```

2. **Install Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Duplikat file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   # Pengguna Windows Command Prompt bisa menggunakan: copy .env.example .env
   ```
   Buka file `.env` di teks editor pilihan Anda dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi dan Seeding Database**
   Untuk membuat tabel database beserta data awal (dummy/admin), jalankan:
   ```bash
   php artisan migrate --seed
   ```

6. **Install Dependensi Frontend & Build**
   ```bash
   npm install
   npm run build
   ```

7. **Symlink Storage (Penting)**
   Agar file upload, gambar, atau dokumen bisa diakses oleh publik, jalankan:
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Aplikasi**
   Jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
   Aplikasi kini dapat diakses melalui browser pada alamat: `http://localhost:8000`.

## 🔒 Informasi Login (Default Seeder)
Jika Anda menjalankan perintah `--seed` pada langkah instalasi, Anda dapat masuk menggunakan akun default berikut (harap cek seeder Anda untuk memastikan email/password):
- **Role Admin**
  - Email: `admin@admin.com` (contoh)
  - Password: `password`
- **Role Kepala Sekolah**
  - Email: `kepsek@kepsek.com` (contoh)
  - Password: `password`

## 📝 Lisensi
Sistem aplikasi ini merupakan perangkat lunak *open-source* yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
