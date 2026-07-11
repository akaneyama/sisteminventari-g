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

4.1.3.1 Implementasi Autentikasi dan Otorisasi Hak Akses
Halaman login merupakan pintu masuk utama, sementara mekanisme keamanan rute (Middleware) bertugas memisahkan hak akses operasional untuk Admin dan hak persetujuan untuk Kepala Sekolah agar tidak terjadi penyalahgunaan wewenang.

Gambar 4.8 Antarmuka Halaman Login dan Pemisahan Rute
Berdasarkan gambar 4.8, menampilkan menu login aplikasi dan skema rute pembagian dasbor. Pengguna memasukkan kredensial untuk masuk ke dasbor yang sesuai dengan hak akses (peran) masing-masing.

Segmen Program 4.8 Autentikasi dan Middleware
Berdasarkan gambar 4.8, kode program menjalankan fungsi login pada kelas AuthService untuk memverifikasi kredensial (bisa berupa email atau username). Setelah sesi divalidasi, rute dilindungi secara ketat oleh kelompok Middleware role:Kepala Sekolah atau role:Admin.

public function login(array $data): bool
{
    $loginField = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $credentials = [$loginField => $data['login'], 'password' => $data['password']];
    
    if (Auth::attempt($credentials)) {
        session()->regenerate();
        return true;
    }
    return false;
}


4.1.3.2 Implementasi Keamanan Input dan Pengadaan Barang
Sistem memfasilitasi formulir pengadaan untuk mencatat aset baru. Formulir ini dilengkapi validasi ketat guna mencegah masuknya data kosong (blank input) ke dalam basis data.

Gambar 4.9 Antarmuka Formulir Pengadaan Barang
Berdasarkan gambar 4.9, merupakan halaman form di mana Admin dapat mencatat penambahan aset baru dengan memasukkan spesifikasi seperti nama barang, kategori, merk, dan jumlah.

Segmen Program 4.9 Validasi Input Pengadaan Barang
Berdasarkan gambar 4.9, kode program pengajuan barang baru mendelegasikan tugas ke fungsi create pada kelas BarangService. Sistem tidak hanya menyimpan spesifikasi teks, tetapi juga memproses unggahan foto barang (jika ada) ke ruang penyimpanan publik server. Setelah divalidasi, entri aset ini diinisialisasi dengan status 'Menunggu Pengadaan' agar belum dianggap sebagai aset aktif sebelum diverifikasi pimpinan.

public function create(array $data)
{
    // Handle upload foto jika ada
    if (isset($data['foto_barang'])) {
        $data['foto_barang'] = $data['foto_barang']->store('barang_fotos', 'public');
    }

    $data['status_approval'] = 'Menunggu Pengadaan';

    return Barang::create($data);
}


4.1.3.3 Implementasi Siklus Persetujuan (Approval) Aset
Setiap penambahan atau perubahan data inventaris dari Admin tidak langsung dieksekusi, melainkan ditampung di tabel sementara (buffer) untuk diverifikasi kelayakannya oleh Kepala Sekolah.

Gambar 4.10 Antarmuka Dasbor Persetujuan (Split Screen)
Berdasarkan gambar 4.10, Kepala Sekolah melihat perbandingan usulan data baru dan data lama secara bersebelahan untuk memudahkan pengambilan keputusan persetujuan.

Segmen Program 4.10 Eksekusi Persetujuan Data
Berdasarkan gambar 4.10, kode program memanggil usulan data baru dari tabel penampung (PerubahanBarang), lalu mengeksekusi penimpaan ke database barang asli dan mengubah statusnya menjadi 'Disetujui'.

public function approvePerubahan($id)
{
    $perubahan = PerubahanBarang::findOrFail($id);
    $barang = Barang::findOrFail($perubahan->id_barang);
    
    $barang->update($perubahan->data_baru);
    $perubahan->status = 'Disetujui';
    $perubahan->save();

    return $barang;
}


4.1.3.4 Implementasi Pencetakan Label Stiker Massal
Untuk memudahkan pelabelan fisik aset yang telah disetujui, sistem men-generate QR Code dinamis dan menyusunnya ke dalam antarmuka khusus pencetakan (*print-ready view*).

Gambar 4.11 Antarmuka Pencetakan Label Stiker
Berdasarkan gambar 4.11, menampilkan tabel aset yang dilengkapi kotak centang (checkbox). Pengguna memilih beberapa unit barang, lalu menekan tombol cetak untuk menampilkan deretan QR Code secara massal yang siap dikirim ke mesin pencetak (printer).

Segmen Program 4.11 Pencetakan Label Stiker QR Code
Berdasarkan gambar 4.11, fungsi printLabelBatch pada kelas LaporanController menangkap array ID barang masukan, menarik datanya beserta konfigurasi profil identitas sekolah, lalu memuat kerangka tampilan stiker. Tata letak pencetakannya diatur secara presisi menggunakan CSS @media print untuk meniadakan margin dan menyesuaikan kertas.

public function printLabelBatch(Request $request)
{
    $ids = $request->input('ids', []);
    if (empty($ids)) {
        return back()->with('error', 'Pilih minimal 1 barang untuk dicetak labelnya.');
    }
    
    $barangs = Barang::with(['kategori', 'lokasi'])->whereIn('id_barang', $ids)->get();
    $identitas = \App\Models\IdentitasSekolah::first();
    
    return view('laporan.label_batch', compact('barangs', 'identitas'));
}


4.1.3.5 Implementasi Ekspor Laporan Inventaris Bersyarat
Sistem dapat mengekspor rekapitulasi data aset menjadi dokumen PDF berdasarkan parameter penyaringan (filter) seperti kondisi barang, lokasi, dan tahun pengadaan.

Gambar 4.12 Antarmuka Halaman Laporan Inventaris
Berdasarkan gambar 4.12, pengguna mengatur panel filter, lalu menekan tombol ekspor untuk menerima fail unduhan laporan yang spesifik sesuai kriteria pencarian.

Segmen Program 4.12 Ekstraksi dan Rendering PDF Laporan
Berdasarkan gambar 4.12, fungsi exportPdf bertindak merangkap semua parameter filter, meneruskannya untuk memanggil kueri bersyarat dari database, dan memuatnya ke tampilan cetak PDF berorientasi lanskap.

public function exportPdf(Request $request)
{
    $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun', 'semester']);
    
    $laporanService = new \App\Services\LaporanService();
    $query = $laporanService->getBarangLaporan($filter);
    $barang = $query->get();

    $stats = $laporanService->getStats($barang);

    $pdf = Pdf::loadView('laporan.pdf', compact('barang', 'stats', 'filter'))->setPaper('a4', 'landscape');
    return $pdf->download('Laporan_Inventaris_'.date('Ymd').'.pdf');
}
4.1.3.6 Implementasi Pengelolaan Buku Inventaris
Buku inventaris merupakan pusat data utama tempat seluruh aset aktif sekolah ditampilkan menggunakan teknik optimasi penarikan data agar kinerja aplikasi tetap stabil.

Gambar 4.13 Halaman Buku Inventaris Utama
Berdasarkan gambar 4.13, antarmuka ini mendaftar seluruh aset aktif sekolah secara rapi dalam bentuk tabel, dilengkapi fitur kotak pencarian pintar untuk menemukan nama, merek, atau kode spesifik secara cepat.

Segmen Program 4.13 Pemanggilan Data Buku Inventaris
Berdasarkan gambar 4.13, fungsi getAll pada kelas BarangService menggunakan metode Eager Loading (with) untuk menarik relasi kategori, lokasi, supplier, dan sumber dana secara efisien. Kueri pencarian juga dirancang dinamis dengan klausa OR bersarang (nested) untuk mencocokkan kata kunci ke beberapa atribut aset sekaligus.

public function getAll(array $filter = [], $excludePendingDelete = true)
{
    $query = Barang::with(['kategori', 'lokasi', 'supplier', 'sumberDana']);

    if ($excludePendingDelete) {
        $query->where('status_approval', 'Tersedia');
    }

    if (!empty($filter['search'])) {
        $s = $filter['search'];
        $query->where(function($q) use ($s) {
            $q->where('kode_inventaris', 'like', "%{$s}%")
              ->orWhere('nama_barang', 'like', "%{$s}%")
              ->orWhere('merk_type', 'like', "%{$s}%");
        });
    }

    return $query->latest()->get();
}


4.1.3.7 Implementasi Manajemen Perbaikan Aset (Maintenance)
Fasilitas sekolah yang rusak didata secara khusus. Selama diservis, status aset dibekukan (Dalam Perbaikan) agar tidak dapat dimutasikan sampai prosesnya selesai.

Gambar 4.14 Halaman Manajemen Perbaikan Aset
Berdasarkan gambar 4.14, menampilkan daftar riwayat perbaikan barang beserta antarmuka formulir pengakhiran yang meminta rincian biaya, hasil servis, serta kondisi fisik akhir.

Segmen Program 4.14 Penyelesaian Perbaikan Aset
Berdasarkan gambar 4.14, fungsi selesaiPerbaikan pada PerbaikanService memberikan percabangan logika yang unik. Jika perbaikan berhasil, aset dikembalikan menjadi 'Tersedia'. Namun, jika teknisi menyatakan gagal (tidak bisa diperbaiki), sistem akan otomatis melempar aset tersebut ke dalam antrean 'Menunggu Penghapusan'.

public function selesaiPerbaikan($id_perbaikan, array $data)
{
    $perbaikan = Perbaikan::findOrFail($id_perbaikan);
    $barang = $perbaikan->barang;

    $perbaikan->update([
        'tanggal_selesai' => $data['tanggal_selesai'],
        'biaya' => $data['biaya'] ?? 0,
        'status_perbaikan' => $data['hasil'] === 'Berhasil' ? 'Selesai Berhasil' : 'Selesai Gagal',
    ]);

    if ($data['hasil'] === 'Berhasil') {
        $barang->kondisi = $data['kondisi_akhir'];
        $barang->status_approval = 'Tersedia';
        $barang->save();
    } else {
        // Jika gagal, langsung masuk antrean penghapusan
        $barang->status_approval = 'Menunggu Penghapusan';
        $barang->save();
    }

    return $perbaikan;
}


4.1.3.8 Implementasi Penghapusan Aset Sementara (Soft Delete)
Penghapusan barang tidak serta-merta melenyapkan data dari sistem. Aset yang rusak berat atau hilang akan melalui tahap verifikasi Kepala Sekolah sebelum masuk ke tong sampah (Soft Delete).

Gambar 4.15 Antarmuka Pengajuan Penghapusan Aset
Berdasarkan gambar 4.15, Admin mengajukan penghapusan aset melalui sebuah panel dialog, kemudian usulan tersebut muncul pada dasbor Kepala Sekolah untuk ditinjau dan dieksekusi.

Segmen Program 4.15 Persetujuan Penghapusan Barang
Berdasarkan gambar 4.15, fungsi approveDelete pada BarangService diakses oleh Kepala Sekolah. Sistem tidak langsung memanggil perintah hapus biasa. Sistem mengembalikan status aset menjadi 'Tersedia' di balik layar demi keamanan pemulihan (restore) di masa depan, baru kemudian mengeksekusi metode delete() milik Laravel untuk menyembunyikan datanya secara aman (Soft Delete).

public function approveDelete($id)
{
    $barang = Barang::findOrFail($id);
    
    // Kembalikan ke Tersedia agar saat di-restore nanti statusnya normal
    $barang->status_approval = 'Tersedia'; 
    $barang->save();
    
    // Lakukan soft delete
    return $barang->delete();
}
4.1.3.9 Implementasi Pemindahan Ruangan Aset (Mutasi Barang)
Setiap perpindahan fisik aset dari satu ruangan ke ruangan lain didata melalui fitur Mutasi. Fitur ini dirancang sangat cerdas karena dapat memisahkan jumlah barang (split) secara otomatis apabila Admin hanya memindahkan sebagian aset dari total jumlah yang ada.

Gambar 4.16 Antarmuka Persetujuan Mutasi Barang
Berdasarkan gambar 4.16, Kepala Sekolah meninjau usulan mutasi yang diajukan oleh Admin. Kepala Sekolah dapat melihat ruangan asal, ruangan tujuan, dan jumlah unit yang akan dipindahkan sebelum mengambil keputusan.

Segmen Program 4.16 Eksekusi Persetujuan Mutasi Barang
Berdasarkan gambar 4.16, fungsi approveMutasi pada kelas MutasiService mengeksekusi perpindahan barang di dalam perlindungan Database Transaction. Jika jumlah mutasi lebih kecil dari stok asli (parsial), sistem menggunakan metode replicate() untuk menduplikasi baris barang tersebut, membagi jumlah stoknya, lalu memberikan kode inventaris baru bersufiks '-SPLIT-' agar pelacakan aset tetap akurat.

public function approveMutasi($id)
{
    return DB::transaction(function () use ($id) {
        $mutasi = Mutasi::findOrFail($id);
        $barang = Barang::findOrFail($mutasi->id_barang);

        // Mutasi parsial: clone barang menggunakan replicate()
        if ($mutasi->jumlah < $barang->jumlah_barang) {
            $barang->decrement('jumlah_barang', $mutasi->jumlah);

            $targetBarang = $barang->replicate();
            $targetBarang->jumlah_barang = $mutasi->jumlah;
            $targetBarang->kode_inventaris = $barang->kode_inventaris . '-SPLIT-' . time();
            $targetBarang->save();
        } else {
            $targetBarang = $barang;
        }

        // Eksekusi perubahan lokasi fisik
        $targetBarang->update(['id_lokasi' => $mutasi->lokasi_tujuan]);
        
        $mutasi->status = 'Disetujui';
        $mutasi->save();

        return $mutasi;
    });
}


4.1.3.10 Implementasi Pembaruan Konfigurasi Identitas Sekolah
Kop surat pada setiap laporan PDF maupun stiker label dirancang dinamis agar dapat menyesuaikan dengan perubahan identitas sekolah di masa depan, termasuk pembaruan logo institusi.

Gambar 4.17 Halaman Pengaturan Identitas Sekolah
Berdasarkan gambar 4.17, merupakan formulir tempat pihak sekolah dapat mengubah profil institusi seperti nama sekolah, alamat, serta mengunggah fail gambar baru sebagai logo resmi.

Segmen Program 4.17 Pembaruan Profil Identitas Sekolah dan Logo
Berdasarkan gambar 4.17, fungsi updateIdentitas pada IdentitasSekolahService menjalankan pembaruan data dan manajemen berkas. Algoritmanya memverifikasi ketersediaan unggahan fail baru, memeriksa apakah ada logo lama di memori penyimpanan server, lalu menyingkirkannya (delete) secara otomatis sebelum menyimpan (storeAs) fail logo yang baru.

public function updateIdentitas(array $data)
{
    $identitas = IdentitasSekolah::first();

    // Algoritma penggantian berkas gambar logo
    if (isset($data['logo_file'])) {
        $file = $data['logo_file'];
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('logos', $filename, 'public');
        
        // Hapus logo lama dari memori server jika ada
        if ($identitas && $identitas->logo && Storage::disk('public')->exists('logos/' . $identitas->logo)) {
            Storage::disk('public')->delete('logos/' . $identitas->logo);
        }
        
        $data['logo'] = $filename;
    }

    if (!$identitas) return IdentitasSekolah::create($data);
    $identitas->update($data);
    
    return $identitas;
}
terakhir


--ini uji coba
4.2 Uji Coba
Bagian ini menjelaskan tentang uji coba yang dilakukan terhadap sistem informasi inventaris. Pengujian perangkat lunak (software testing) dilakukan secara menyeluruh terhadap lingkungan fungsional aplikasi web menggunakan metode Black Box Testing.

4.2.1 Tahap Pengujian
Pengujian berfokus pada sisi antarmuka, di mana setiap tombol ditekan dan input dikirimkan ke server. Penguji kemudian melihat apakah output layar atau perubahan status di basis data sesuai dengan logika program yang telah dibuat. Pengujian dilakukan dengan mengondisikan dua pengguna paralel (sesi tab untuk Admin dan tab penyamaran / incognito untuk Kepala Sekolah secara bersamaan).

4.2.2 Hasil Pengujian
Berikut adalah tabel rekapitulasi hasil pengujian atas fitur-fitur fungsional (Use Case) utama di dalam Sistem Informasi Inventaris:

| No | Fungsi yang Diuji | Skenario Pengujian | Hasil yang Diharapkan | Keterangan |
|---|---|---|---|---|
| 1 | Login & Otorisasi Hak Akses | Pengguna mencoba masuk dengan kredensial Admin, lalu mengetikkan URL fungsi Kepala Sekolah di peramban. | Sistem berhasil mem-validasi kredensial Admin, namun menolak paksa akses ke halaman Kepala Sekolah (Error 403 Forbidden). | Valid |
| 2 | Manajemen Master Data (Admin) | Admin mengeksekusi penambahan "Kategori" atau "Lokasi Ruangan" baru dengan nama yang dikosongkan. | Sistem memunculkan peringatan wajib isi (Validation Error) dan menolak eksekusi insert ke database. | Valid |
| 3 | Pengajuan Pengadaan Barang (Admin) | Admin menginput aset barang baru (Misal: 10 Unit Komputer) beserta file foto dokumentasi ke dalam form. | Data tersimpan di sistem dengan aman, foto terunggah di direktori server (storage), dan status barang terkunci sebagai Pending. | Valid |
| 4 | Persetujuan Pengadaan (Kepala Sekolah) | Kepala Sekolah menekan Approve pada pengajuan "10 Unit Komputer" dari antrean Dashboard persetujuan. | Logika sistem tereksekusi: stok komputer otomatis masuk ke buku inventaris final dengan status "Aktif". | Valid |
| 5 | Cetak Label Batch (Admin) | Admin mencentang 5 buah barang berbeda di tabel dan menekan "Cetak Label Terpilih". | Server seketika mengembalikan unduhan berisi 1 lembar PDF yang menampilkan 5 QR Code berbeda yang siap dicetak. | Valid |
| 6 | Pembaruan Mutasi Barang (Admin & Kepsek) | Admin memindahkan "Lemari" dari "Ruang Guru" ke "Perpustakaan". Kepala Sekolah kemudian Menyetujui pengajuan tersebut. | Database transaction sukses. Catatan lokasi "Lemari" berubah menjadi "Perpustakaan" di seluruh tampilan sistem secara real-time. | Valid |
| 7 | Laporan Rekapitulasi & Ekspor PDF | Pengguna memilih Filter "Tahun 2026" dan "Kondisi: Baik", lalu menekan ekspor PDF. | Query di sistem berjalan akurat untuk menyingkirkan data kotor, lalu me-render tabel laporan PDF yang isinya persis dengan kriteria yang diminta. | Valid |
| 8 | Evaluasi Instruksi (Kepala Sekolah) | Kepala Sekolah menuliskan pesan "Catatan Pimpinan" dan menyimpannya. | Notifikasi instruksi muncul dan mengikat (muncul ikon belum dibaca) pada layar dasbor Admin. | Valid |
