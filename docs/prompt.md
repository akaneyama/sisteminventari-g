# PROMPT EKSEKUSI PENGEMBANGAN APLIKASI INVENTARIS

Dokumen ini berisi serangkaian instruksi yang dirancang untuk dieksekusi oleh Large Language Model (LLM) atau AI Developer Assistant. Setiap bagian mewakili satu tugas pengembangan yang terisolasi. Ikuti instruksi secara harfiah.

---

### **TASK 1: Normalisasi Database (Sumber Dana & Tahun Pengadaan)**

**Tujuan:**
Mengubah kolom `sumber_dana` dan `tahun_perolehan` pada tabel `barang` dari tipe data statis (string dan integer) menjadi relasi `foreign key` ke tabel master baru. Ini meningkatkan integritas data dan fleksibilitas.

**Langkah 1: Buat Tabel Master `sumber_dana`**
1.  **Buat Migrasi:**
    ```bash
    php artisan make:migration create_sumber_danas_table
    ```
2.  **Ubah File Migrasi (`..._create_sumber_danas_table.php`):**
    ```php
    Schema::create('sumber_dana', function (Blueprint $table) {
        $table->id('id_sumber_dana');
        $table->string('nama_sumber_dana')->unique();
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
    ```
3.  **Buat Model:**
    ```bash
    php artisan make:model SumberDana
    ```
4.  **Ubah File Model (`app/Models/SumberDana.php`):**
    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class SumberDana extends Model
    {
        use HasFactory;
        protected $table = 'sumber_dana';
        protected $primaryKey = 'id_sumber_dana';
        protected $fillable = ['nama_sumber_dana', 'deskripsi'];

        public function barangs()
        {
            return $this->hasMany(Barang::class, 'id_sumber_dana');
        }
    }
    ```

**Langkah 2: Buat CRUD Lengkap untuk `SumberDana`**
1.  **Buat Controller:**
    ```bash
    php artisan make:controller SumberDanaController --resource
    ```
2.  **Tambahkan Rute di `routes/web.php`** di dalam grup `middleware('role:Admin')`:
    ```php
    Route::resource('sumber-dana', SumberDanaController::class);
    ```
3.  **Implementasikan Views:** Buat file-file Blade untuk `index`, `create`, `edit` di dalam `resources/views/admin/sumber_dana/`. Salin dan modifikasi dari `resources/views/admin/kategori/` sebagai template. Ganti semua referensi 'Kategori' menjadi 'Sumber Dana'.

**Langkah 3: Lakukan Hal yang Sama untuk `TahunPengadaan`**
1.  **Buat Migrasi `create_tahun_pengadaans_table`** dengan kolom `tahun` (integer, unique) dan `deskripsi` (opsional).
2.  **Buat Model `TahunPengadaan`**.
3.  **Buat `TahunPengadaanController`** dan rute resource-nya.
4.  **Buat Views** untuk CRUD `TahunPengadaan`.

**Langkah 4: Ubah Tabel `barang`**
1.  **Buat Migrasi Baru:**
    ```bash
    php artisan make:migration update_barang_table_for_normalization
    ```
2.  **Ubah File Migrasi (`..._update_barang_table_for_normalization.php`):**
    ```php
    Schema::table('barang', function (Blueprint $table) {
        // Tambah kolom foreign key baru
        $table->unsignedBigInteger('id_sumber_dana_new')->nullable()->after('sumber_dana');
        $table->unsignedBigInteger('id_tahun_pengadaan_new')->nullable()->after('tahun_perolehan');

        // Tambah relasi
        $table->foreign('id_sumber_dana_new')->references('id_sumber_dana')->on('sumber_dana')->onDelete('restrict');
        $table->foreign('id_tahun_pengadaan_new')->references('id_tahun_pengadaan')->on('tahun_pengadaan')->onDelete('restrict');
    });

    // Jalankan migrasi data di sini jika diperlukan, lalu hapus kolom lama di migrasi terpisah.
    // Untuk saat ini, kita akan membiarkan kolom lama untuk transisi.
    ```
3.  **Jalankan Migrasi:**
    ```bash
    php artisan migrate
    ```
4.  **Ubah Model `Barang.php`:** Tambahkan relasi `sumberDana()` dan `tahunPengadaan()`.
5.  **Ubah Form `create.blade.php` dan `edit.blade.php` untuk Barang:** Ganti input teks `sumber_dana` dan `tahun_perolehan` menjadi `<select>` dropdown yang datanya diambil dari tabel `sumber_dana` dan `tahun_pengadaan`.

---

### **TASK 2: Implementasi Modul Manajemen Supplier**

**Tujuan:**
Membangun fondasi untuk melacak asal-usul barang secara detail melalui data supplier dan transaksi pembelian.

**Langkah 1: Buat Model dan Migrasi untuk `Supplier`**
1.  **Migrasi:**
    ```bash
    php artisan make:migration create_suppliers_table
    ```
    ```php
    Schema::create('suppliers', function (Blueprint $table) {
        $table->id('id_supplier');
        $table->string('nama_supplier');
        $table->string('kontak_person')->nullable();
        $table->string('telepon')->unique();
        $table->string('email')->unique()->nullable();
        $table->text('alamat');
        $table->timestamps();
    });
    ```
2.  **Model:** Buat `app/Models/Supplier.php`.

**Langkah 2: Buat CRUD Lengkap untuk `Supplier`**
1.  **Controller:** `php artisan make:controller SupplierController --resource`
2.  **Rute:** Tambahkan `Route::resource('supplier', SupplierController::class);` di grup admin.
3.  **Views:** Buat folder `resources/views/admin/supplier` dan buat file-file Blade (`index`, `create`, `edit`) dengan mencontoh dari modul Kategori.

---

### **TASK 3: Peningkatan Dashboard dengan Grafik**

**Tujuan:**
Menyajikan data agregat dalam bentuk visual yang mudah dipahami di halaman dashboard Admin.

**Langkah 1: Install Chart.js**
1.  **Jalankan di terminal:**
    ```bash
    npm install chart.js
    ```
2.  **Impor di `resources/js/app.js`:**
    ```javascript
    import Chart from 'chart.js/auto';
    window.Chart = Chart;
    ```

**Langkah 2: Siapkan Data dari Controller**
1.  **Buka `DashboardController.php`**
2.  Di dalam method `admin()`, kumpulkan data berikut:
    ```php
    use App\Models\Barang;
    use Illuminate\Support\Facades\DB;

    // Data untuk Pie Chart Kategori
    $kategoriData = Barang::join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
        ->select('kategori.nama_kategori', DB::raw('count(barang.id_barang) as total'))
        ->groupBy('kategori.nama_kategori')
        ->get();

    // Data untuk Bar Chart Kondisi
    $kondisiData = Barang::select('kondisi', DB::raw('count(id_barang) as total'))
        ->groupBy('kondisi')
        ->get();
    ```
3.  Kirim data ini ke view:
    ```php
    return view('admin.dashboard', compact('kategoriData', 'kondisiData'));
    ```

**Langkah 3: Tampilkan Grafik di View**
1.  **Buka `resources/views/admin/dashboard.blade.php`**
2.  Tambahkan dua elemen `<canvas>`:
    ```html
    <div class="row">
        <div class="col-md-6">
            <canvas id="kategoriChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="kondisiChart"></canvas>
        </div>
    </div>
    ```
3.  Tambahkan script JavaScript di akhir file Blade:
    ```javascript
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pie Chart Kategori
        const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
        const kategoriData = @json($kategoriData);
        new Chart(kategoriCtx, {
            type: 'pie',
            data: {
                labels: kategoriData.map(item => item.nama_kategori),
                datasets: [{
                    label: 'Jumlah Barang per Kategori',
                    data: kategoriData.map(item => item.total),
                    backgroundColor: [ /* Array of hex colors */ ],
                }]
            }
        });

        // Bar Chart Kondisi
        const kondisiCtx = document.getElementById('kondisiChart').getContext('2d');
        const kondisiData = @json($kondisiData);
        new Chart(kondisiCtx, {
            type: 'bar',
            data: {
                labels: kondisiData.map(item => item.kondisi),
                datasets: [{
                    label: 'Jumlah Barang per Kondisi',
                    data: kondisiData.map(item => item.total),
                    backgroundColor: [ /* Array of hex colors */ ],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
    ```

---

### **TASK 4: Implementasi Penanganan Error Hapus Data Terikat**

**Tujuan:**
Memberikan feedback yang jelas kepada pengguna ketika mereka mencoba menghapus data master (seperti Kategori atau Lokasi) yang masih terikat pada satu atau lebih barang, daripada menampilkan halaman error standar Laravel.

**Langkah 1: Modifikasi `KategoriController`**
1.  **Buka `app/Http/Controllers/KategoriController.php`**
2.  Ubah method `destroy()`:
    ```php
    use Illuminate\Database\QueryException;

    public function destroy(Kategori $kategori)
    {
        try {
            // Periksa relasi secara manual sebelum menghapus
            if ($kategori->barangs()->exists()) {
                return redirect()->route('kategori.index')
                    ->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh ' . $kategori->barangs()->count() . ' barang.');
            }

            $kategori->delete();

            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil dihapus.');

        } catch (QueryException $e) {
            // Fallback jika ada constraint lain
            return redirect()->route('kategori.index')
                ->with('error', 'Gagal menghapus kategori karena terikat oleh data lain.');
        }
    }
    ```
3.  **Pastikan Model `Kategori.php` memiliki relasi `barangs()`:**
    ```php
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
    ```

**Langkah 2: Tampilkan Notifikasi di View**
1.  **Buka `resources/views/admin/kategori/index.blade.php`**
2.  Tambahkan kode untuk menampilkan pesan `success` dan `error` dari session:
    ```html
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    ```

**Langkah 3: Terapkan Pola yang Sama**
1.  Lakukan modifikasi serupa pada `LokasiController@destroy`.
2.  Lakukan modifikasi serupa pada `SumberDanaController@destroy`.
3.  Lakukan modifikasi serupa pada `TahunPengadaanController@destroy`.
Pastikan semua model terkait memiliki definisi relasi `hasMany` ke model `Barang`.