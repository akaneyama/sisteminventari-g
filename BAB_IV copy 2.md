4.1.2 Implementasi Database
Pada tahap ini, rancangan basis data yang telah dirancang pada Bab III diwujudkan ke dalam DBMS (Database Management System) dengan memanfaatkan MySQL. Basis data yang dibangun diberi nama db_sisteminventaris. Berdasarkan hasil implementasi yang dilakukan, sistem ini tersusun atas 7 tabel utama yang saling berelasi satu sama lain guna menunjang jalannya operasional pengelolaan inventaris sekolah.

1. Daftar Tabel Basis Data
Di bawah ini merupakan tabel-tabel yang terdapat di dalam basis data db_sisteminventaris:

1) users
Gambar 4.1 users
Tabel ini diterapkan untuk mengatur akses login serta identitas dasar dari setiap pengguna aplikasi. Kolom id berfungsi sebagai Primary Key dengan fitur auto_increment. Data kredensial disimpan pada kolom email dan password, sementara identitas personal dicatat pada name. Hak akses dibedakan melalui kolom role (misalnya Admin atau Kepala Sekolah), dan setiap pembuatan akun terekam otomatis pada kolom created_at dengan format timestamp.

2) barangs
Gambar 4.2 barangs
Tabel ini berfungsi menyimpan rincian aset fisik yang dikelola. Selain id_barang sebagai kunci utama, terdapat kolom kode_inventaris yang unik untuk memastikan tidak ada pencatatan ganda. Rincian spesifikasi aset dicatat pada kolom nama_barang, merk_type, dan kondisi. Status persetujuan aset dipantau melalui status_approval, serta terhubung dengan kategori dan lokasi melalui foreign key.

3) lokasis
Gambar 4.3 lokasis
Tabel ini merupakan pusat informasi ruangan atau lokasi penempatan aset. Kolom id_lokasi menjadi identitas unik dengan auto_increment, sementara nama_lokasi mencatat nama ruangan (seperti Ruang Guru atau Laboratorium). Tabel ini sangat krusial sebagai referensi posisi fisik dari setiap barang yang terdaftar di dalam sistem.

4) kategoris
Gambar 4.4 kategoris
Tabel ini berfungsi untuk mengelompokkan jenis barang guna mempermudah proses klasifikasi aset. Kolom id_kategori bertindak sebagai Primary Key, sedangkan nama_kategori menyimpan nama kelompok (misalnya Elektronik atau Furnitur). Data dari tabel ini selalu dirujuk oleh tabel barangs untuk menstandarisasi pelaporan jenis inventaris.

5) mutasis
Gambar 4.5 mutasis
Tabel ini merekam riwayat perpindahan atau distribusi barang antar ruangan. Kolom id_mutasi berfungsi sebagai kunci utama. Tabel ini mencatat tanggal_mutasi, jumlah barang yang dipindahkan, serta asal dan tujuan ruangan. Kolom status_mutasi memantau apakah pengajuan pindah barang tersebut berstatus 'Menunggu', 'Disetujui', atau 'Ditolak' oleh pimpinan.

6) perbaikans
Gambar 4.6 perbaikans
Tabel ini bertindak sebagai entitas pendokumentasian riwayat pemeliharaan aset (maintenance). Selain menyimpan id_perbaikan sebagai kunci utama, tabel ini mencatat tanggal_mulai, biaya servis, dan hasil perbaikan. Keterkaitan data dijamin melalui id_barang yang merujuk pada aset yang sedang rusak, guna mempermudah audit pemeliharaan fasilitas sekolah.

7) perubahan_barangs
Gambar 4.7 perubahan_barangs
Tabel ini berfungsi sebagai buffer sementara untuk menampung usulan pembaruan spesifikasi (edit) data barang. Kolom id_perubahan bertindak sebagai kunci utama. Tabel ini menyimpan wujud data_lama dan usulan data_baru secara terpisah. Sistem memantau proses verifikasinya melalui kolom status sebelum data asli pada tabel barangs resmi diubah.

2. Relasi dan Fungsi Tabel Basis Data
Sistem ini memanfaatkan basis data relasional yang menghubungkan setiap tabel satu sama lain guna menjaga konsistensi data dalam pengelolaan pengadaan, mutasi, serta pemeliharaan aset. Berikut merupakan penjelasan mengenai fungsi dan hubungan keterkaitan antar tabel:

1) users
Tabel ini berfungsi sebagai entitas utama keamanan yang mengelola data autentikasi bagi pengguna aplikasi. Secara fungsional, tabel ini menyimpan kredensial login serta identitas dasar untuk membedakan peran (role) antara Admin dan Kepala Sekolah dalam menjalankan operasional dan fungsi persetujuan (approval) sistem.

2) barangs
Tabel ini berperan sebagai pusat informasi mengenai unit aset fisik yang dikelola oleh sekolah, mencakup detail kode inventaris, spesifikasi, dan kondisi barang. Tabel ini berelasi krusial dengan lokasis dan kategoris sebagai kunci tamu untuk mengklasifikasikan aset. Selain itu, tabel ini merupakan poros utama yang terhubung dengan tabel mutasis, perbaikans, dan perubahan_barangs dalam merekam seluruh riwayat aktivitas barang.

3) lokasis
Tabel ini diimplementasikan untuk menyediakan referensi ruangan atau lokasi spesifik di lingkungan sekolah. Relasinya mencakup keterkaitan dengan barangs untuk menentukan posisi awal aset, serta terhubung dengan mutasis untuk melacak jejak perpindahan barang dari ruangan lama ke ruangan yang baru.

4) kategoris
Tabel ini digunakan sebagai acuan pengelompokan jenis barang agar pencatatan inventaris lebih terstruktur. Tabel ini memiliki relasi satu-ke-banyak (one-to-many) dengan tabel barangs guna memfasilitasi pelaporan dan pencarian aset berdasarkan kelompok spesifik, seperti barang elektronik atau mebel.

5) mutasis
Tabel ini berfungsi mencatat setiap aktivitas pergeseran tata letak aset secara rinci. Tabel ini berelasi dengan barangs untuk mengidentifikasi aset yang dipindahkan, serta dengan lokasis untuk mendokumentasikan rute perpindahannya. Tabel ini sangat penting dalam menjaga transparansi riwayat mobilitas barang.

6) perbaikans
Tabel ini digunakan sebagai arsip validasi yang mendokumentasikan proses servis aset yang mengalami kerusakan. Tabel ini memiliki relasi langsung dengan barangs. Ketika data di tabel ini aktif, status barang terkait akan terkunci agar tidak dapat dimutasi sebelum proses perbaikannya dinyatakan selesai dan biayanya tercatat.

7) perubahan_barangs
Tabel ini berfungsi sebagai pengontrol integritas data dengan cara menahan sementara usulan revisi data (edit). Tabel ini merujuk ke barangs melalui kunci tamu. Relasinya bertujuan untuk memastikan bahwa setiap pembaruan spesifikasi aset di database utama harus melalui tahap validasi dan persetujuan dari Kepala Sekolah terlebih dahulu.

4.1.3 Implementasi Program 
Implementasi program merupakan tahap penerjemahan rancangan antarmuka (User Interface) dan alur kerja sistem ke dalam kode pemrograman. Aplikasi Sistem Informasi Inventaris ini dibangun menggunakan framework Laravel dengan bahasa pemrograman PHP untuk sisi backend, serta komponen antarmuka web (HTML, CSS, JavaScript) untuk frontend guna mengelola data pada basis data MySQL.

4.1.3.5 Implementasi Pengajuan dan Persetujuan Perubahan Data (Edit)
Ketika spesifikasi barang keliru dan Admin ingin mengubahnya, sistem tidak akan langsung menimpa data asli. Usulan perubahan data ditampung di tempat sementara (buffer) tanpa merusak data asli sebelum mendapat persetujuan pimpinan.

Gambar 4.11 Antarmuka Usulan Perubahan Data (Admin)
Gambar 4.12 Antarmuka Persetujuan Perubahan Data (Split Screen)

A. Kode pemrograman usulan edit ditampung di buffer (app/Services/BarangService.php):

public function update($id, array $data)
{
    $barang = Barang::findOrFail($id);
    
    // Mengambil snapshot wujud data barang lama
    $dataLama = $barang->only(array_keys($data));

    // Menitipkan usulan di tabel penampung (buffer)
    PerubahanBarang::create([
        'id_barang'  => $barang->id_barang,
        'data_lama'  => $dataLama,
        'data_baru'  => $data,
        'status'     => 'Menunggu',
    ]);

    return $barang;
}

**Segmen program gambar 4.11** Usulan perubahan data barang (Buffer)



B. Kode pemrograman persetujuan perubahan data (app/Services/BarangService.php):

public function approvePerubahan($id)
{
    $perubahan = PerubahanBarang::findOrFail($id);
    $barang = Barang::findOrFail($perubahan->id_barang);

    // Menerapkan usulan data baru ke data barang asli
    $barang->update($perubahan->data_baru);

    // Mengubah status pengajuan menjadi disetujui
    $perubahan->status = 'Disetujui';
    $perubahan->save();

    return $barang;
}

**Segmen program gambar 4.12** Persetujuan perubahan data barang

4.1.3.6 Implementasi Usulan dan Persetujuan Penghapusan Barang
Fitur penghapusan barang pada sistem ini tidak serta-merta langsung menghapus data. Untuk mencegah kehilangan aset secara sepihak, penghapusan dikunci sementara menggunakan status 'Menunggu Penghapusan'. Jika disetujui Kepala Sekolah, barulah dieksekusi menggunakan mekanisme Soft Delete.

Gambar 4.13 Antarmuka Pengajuan Penghapusan Barang

A. Kode pemrograman pengajuan dan persetujuan penghapusan (app/Services/BarangService.php):
public function requestDelete($id)
{
    $barang = Barang::findOrFail($id);
    
    // Mengubah status operasional menjadi pengajuan hapus
    $barang->status_approval = 'Menunggu Penghapusan';
    $barang->save();
    
    return $barang;
}

public function approveDelete($id)
{
    $barang = Barang::findOrFail($id);
    
    // Kembalikan ke Tersedia sebagai pencegahan untuk restore di masa depan
    $barang->status_approval = 'Tersedia'; 
    $barang->save();
    
    // Eksekusi penghapusan sementara (Soft Delete)
    return $barang->delete();
}

**Segmen program gambar 4.13** Pengajuan dan persetujuan penghapusan barang

4.1.3.7 Implementasi Manajemen Perbaikan Aset (Maintenance)
Fasilitas sekolah yang rusak dan perlu diservis didata secara khusus pada sistem. Status aset tersebut akan dibekukan ke mode perbaikan agar tidak dapat dimutasikan sampai proses servisnya dinyatakan selesai dan biayanya tercatat.

Gambar 4.14 Halaman Manajemen Perbaikan Aset

A. Kode pemrograman pencatatan awal perbaikan aset (app/Services/PerbaikanService.php):
public function mulaiPerbaikan($id, array $data)
{
    $barang = Barang::findOrFail($id);
    
    // Status barang dikunci sementara agar tidak bisa dimutasi
    $barang->status_approval = 'Dalam Perbaikan';
    $barang->save();

    return Perbaikan::create($data);
}

**Segmen program gambar 4.14 (Bagian A)** Pencatatan awal perbaikan aset

B. Kode pemrograman penyelesaian perbaikan aset (app/Services/PerbaikanService.php):
public function selesaiPerbaikan($id, array $data)
{
    $perbaikan = Perbaikan::findOrFail($id);
    
    // Memperbarui data perbaikan dengan biaya dan hasil
    $perbaikan->update([
        'tanggal_selesai' => $data['tanggal_selesai'],
        'biaya' => $data['biaya'] ?? 0,
        'status_perbaikan' => 'Selesai ' . $data['hasil'],
    ]);

    // Mengembalikan status barang dan mengupdate kondisinya jika servis berhasil
    $barang = Barang::findOrFail($perbaikan->id_barang);
    $barang->status_approval = 'Tersedia';
    
    if ($data['hasil'] === 'Berhasil' && !empty($data['kondisi_akhir'])) {
        $barang->kondisi = $data['kondisi_akhir'];
    }
    $barang->save();

    return $perbaikan;
}

**Segmen program gambar 4.14 (Bagian B)** Penyelesaian perbaikan aset

4.1.3.8 Implementasi Pembuatan Laporan Inventaris PDF
Sistem dapat menghasilkan rekapitulasi data aset menjadi dokumen PDF berdasarkan kriteria pencarian yang spesifik (seperti filter kondisi, lokasi, dan tahun pengadaan) menggunakan pustaka DomPDF.

Gambar 4.15 Antarmuka Halaman Laporan Inventaris

A. Kode pemrograman ekstraksi dan rendering PDF laporan (app/Http/Controllers/LaporanController.php):
public function exportPdf(Request $request)
{
    // Menangkap parameter filter dari antarmuka
    $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun', 'semester']);
    
    // Menarik kueri bersyarat dari database
    $laporanService = new \App\Services\LaporanService();
    $barang = $laporanService->getBarangLaporan($filter)->get();
    $stats = $laporanService->getStats($barang);

    // Memuat tampilan HTML laporan dan merendernya menjadi PDF
    $pdf = Pdf::loadView('laporan.pdf', compact('barang', 'stats', 'filter'))->setPaper('a4', 'landscape');
    
    return $pdf->download('Laporan_Inventaris_'.date('Ymd').'.pdf');
}

**Segmen program gambar 4.15** Ekstraksi dan rendering PDF laporan inventaris

4.1.3.9 Implementasi Pengelolaan Buku Inventaris dan Pencarian Data
Buku inventaris merupakan pusat data utama tempat seluruh aset aktif sekolah ditampilkan. Untuk menjaga performa aplikasi saat memuat ribuan data aset, sistem menggunakan teknik Eager Loading pada Laravel agar kueri ke database berjalan efisien tanpa membebani server (mencegah masalah N+1 Query).

Gambar 4.16 Halaman Buku Inventaris Utama

A. Kode pemrograman pemanggilan data buku inventaris (app/Http/Controllers/BarangController.php):
public function index(Request $request)
{
    // Memanggil relasi tabel master sekaligus (Eager Loading)
    $query = Barang::with(['kategori', 'lokasi', 'supplier', 'sumberDana']);

    // Menerapkan filter pencarian berdasarkan input pengguna
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_barang', 'like', "%{$search}%")
              ->orWhere('kode_inventaris', 'like', "%{$search}%")
              ->orWhere('merk_type', 'like', "%{$search}%");
        });
    }

    // Hanya menampilkan barang yang aktif dan disetujui
    $barang = $query->where('status_approval', 'Tersedia')
                    ->latest()
                    ->paginate(15);

    return view('barang.index', compact('barang'));
}

**Segmen program gambar 4.16** Pemanggilan data buku inventaris dan pencarian


4.1.3.10 Implementasi Profil dan Detail Riwayat Barang (Audit Trail)
Setiap aset fisik di sekolah memiliki rekam jejak (audit trail) yang transparan. Halaman detail barang tidak hanya menampilkan spesifikasi teknis, tetapi juga riwayat perpindahan ruangan (mutasi) dan riwayat perbaikan (maintenance) di masa lalu.

Gambar 4.17 Halaman Detail Riwayat Barang

A. Kode pemrograman penarikan riwayat detail barang (app/Http/Controllers/BarangController.php):
public function show($id)
{
    // Mengambil data barang beserta seluruh rekam jejaknya
    $barang = Barang::with([
        'kategori', 
        'lokasi', 
        'mutasis' => function($q) {
            $q->latest('tanggal_mutasi'); // Diurutkan dari mutasi terbaru
        },
        'perbaikans' => function($q) {
            $q->latest('tanggal_mulai'); // Diurutkan dari servis terbaru
        }
    ])->findOrFail($id);

    return view('barang.show', compact('barang'));
}

**Segmen program gambar 4.17** Penarikan detail riwayat barang (Audit Trail)

4.1.3.11 Implementasi Pembaruan Identitas Sekolah dan Logo
Kop surat pada laporan PDF maupun stiker label dibuat dinamis agar dapat menyesuaikan dengan perubahan identitas sekolah di masa depan. Sistem dilengkapi algoritma penggantian berkas yang secara otomatis menghapus logo lama dari penyimpanan server saat pengguna mengunggah logo baru.

Gambar 4.18 Halaman Pengaturan Identitas Sekolah

A. Kode pemrograman pembaruan profil sekolah (app/Http/Controllers/IdentitasSekolahController.php):
public function update(Request $request)
{
    $identitas = IdentitasSekolah::first();
    $data = $request->except('logo');

    // Algoritma penggantian berkas gambar
    if ($request->hasFile('logo')) {
        // Menghapus logo lama dari memori server jika ada
        if ($identitas->logo && Storage::disk('public')->exists($identitas->logo)) {
            Storage::disk('public')->delete($identitas->logo);
        }
        
        // Mengunggah logo baru
        $data['logo'] = $request->file('logo')->store('logos', 'public');
    }

    $identitas->update($data);

    return back()->with('success', 'Profil identitas sekolah berhasil diperbarui.');
}

**Segmen program gambar 4.18** Pembaruan profil identitas sekolah dan logo
terakhir


--ini uji coba
4.2 Uji Coba
Bagian ini menjelaskan tentang uji coba yang dilakukan terhadap sistem informasi inventaris. Pengujian perangkat lunak (software testing) dilakukan secara menyeluruh terhadap lingkungan fungsional aplikasi web menggunakan metode Black Box Testing.

4.2.1 Tahap Pengujian
Pengujian berfokus pada sisi antarmuka, di mana setiap tombol ditekan dan input dikirimkan ke server. Penguji kemudian melihat apakah output layar atau perubahan status di basis data sesuai dengan logika program yang telah dibuat. Pengujian dilakukan dengan mengondisikan dua pengguna paralel (sesi tab untuk Admin dan tab penyamaran / incognito untuk Kepala Sekolah secara bersamaan).

4.2.2 Hasil Pengujian
Berikut adalah narasi rekapitulasi hasil pengujian atas fitur-fitur utama di dalam Sistem Informasi Inventaris:

1. Pengujian Login & Otorisasi Lintas Batas
   Skenario: Pengguna mencoba masuk menggunakan kredensial akun Admin, kemudian mencoba memaksa masuk dengan mengetikkan URL fungsi persetujuan milik Kepala Sekolah di browser. 
   Hasil: Valid. Sistem menolak paksa akses tersebut dan melempar pesan eror 403 Forbidden.

2. Pengujian Keamanan Input Kosong (CRUD)
   Skenario: Admin sengaja tidak mengisi formulir input nama kategori atau mencoba menyimpan pengadaan barang baru tanpa spesifikasi yang jelas.
   Hasil: Valid. Sistem menolak perintah penyimpanan dan meminta pengguna melengkapi data yang diberi tanda merah.

3. Pengujian Siklus Pengadaan dan Persetujuan
   Skenario: Admin mengajukan 10 Unit Komputer baru. 
   Hasil: Valid. Komputer belum tampil di data inventaris utama. Baru setelah Kepala Sekolah menekan Approve di layar dasbornya, 10 Unit Komputer tersebut resmi masuk ke buku catatan.

4. Pengujian Pencetakan Stiker Massal
   Skenario: Admin mencentang lima buah barang berbeda sekaligus secara acak pada tabel, lalu menekan tombol pencetakan label stiker.
   Hasil: Valid. Server dengan cepat mengembalikan satu buah fail PDF yang didalamnya berisi 5 gambar QR Code milik barang-barang yang dipilih tadi.

5. Pengujian Ekspor Laporan bersyarat
   Skenario: Pengguna mengatur dropdown filter tahun pengadaan ke "2026" dan kondisi barang ke "Baik" kemudian mengunduh failnya.
   Hasil: Valid. PDF tabel yang dihasilkan menyingkirkan data-data barang rusak ataupun yang dibeli di tahun lain. Filter pencarian berfungsi sempurna.
