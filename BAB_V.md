# BAB V
# PENUTUP

## 5.1 Kesimpulan
Berdasarkan hasil analisis, perancangan, implementasi, serta serangkaian pengujian fungsionalitas yang telah dilakukan terhadap pembangunan Sistem Informasi Inventaris Barang Berbasis Web (SiVentaris) menggunakan framework Laravel dan database MySQL, maka diperoleh beberapa kesimpulan yang sekaligus menjawab rumusan masalah dalam penelitian ini:

1. Metode Pembangunan Sistem: Sistem Informasi Inventaris Barang (SiVentaris) berhasil dirancang dan dibangun dengan mengadopsi model System Development Life Cycle (SDLC) Waterfall secara sistematis. Dari sisi teknologi, sistem ini menggunakan bahasa pemrograman PHP dengan framework Laravel yang menerapkan arsitektur Model-View-Controller (MVC), pangkalan data MySQL, serta framework TailwindCSS untuk tampilan antarmuka. Keamanan akses dan alur kerja (workflow) dijaga ketat melalui implementasi Middleware Role untuk mengamankan pembagian wewenang antara Admin dan Kepala Sekolah.
2. Penyelesaian Masalah Operasional (Jawaban Rumusan Masalah): Aplikasi yang dikembangkan terbukti mampu meningkatkan efisiensi pengelolaan data sekolah melalui otomatisasi dan digitalisasi fitur-fitur operasional utama:
   a. Pengurangan Human Error dan Redundansi: Sentralisasi data master (Kategori, Lokasi, Sumber Dana, dan Supplier) menghilangkan duplikasi data yang sebelumnya kerap terjadi pada metode pencatatan manual di buku besar atau spreadsheet terpisah.
   b. Peningkatan Efisiensi Pelaporan: Masalah keterlambatan laporan rekapitulasi diselesaikan dengan fitur otomatisasi ekspor laporan ke format PDF dan Excel (spreadsheet) yang dapat digenerasikan secara instan berdasarkan kriteria filter tertentu (tahun, lokasi, kondisi).
   c. Pemisahan Wewenang (Separation of Duties) & Otoritas: Pembatasan wewenang bekerja dengan baik, di mana Admin hanya berwenang mengajukan usulan (mutasi, pengadaan, penghapusan), sedangkan keputusan eksekusi final berada di tangan Kepala Sekolah melalui modul Approval terintegrasi.
   d. Pelacakan Aset (Audit Trail): Sistem mampu merekam siklus hidup aset melalui fitur riwayat mutasi, perbaikan, dan mutasi barang secara real-time.
3. Kelayakan Implementasi: Berdasarkan hasil pengujian fungsional menggunakan metode Black-Box Testing terhadap 8 skenario utama—mulai dari otorisasi login, pengelolaan master data, pengajuan pengadaan, persetujuan Kepala Sekolah, cetak label batch, pembaruan mutasi, ekspor laporan, hingga evaluasi instruksi—seluruh fungsi dinyatakan Valid dan berjalan sesuai dengan spesifikasi rancangan. Dengan demikian, purwarupa sistem ini dinilai layak dan siap untuk diimplementasikan guna meningkatkan akuntabilitas manajemen sarana prasarana sekolah.

---

## 5.2 Saran
Berdasarkan keterbatasan sistem yang ada serta hasil evaluasi selama tahap pengembangan dan pengujian, berikut beberapa saran realistis yang diajukan untuk pengembangan aplikasi SiVentaris lebih lanjut:

1. Pengembangan Modul Mobile Application: Mengembangkan aplikasi pendukung berbasis mobile (Android/iOS) yang terintegrasi agar petugas inventaris dapat langsung memindai QR Code menggunakan kamera smartphone saat melakukan audit fisik aset di lapangan.
2. Integrasi Notifikasi Real-time: Menerapkan layanan pengiriman pesan instan (seperti WhatsApp API, Telegram Bot, atau Mail Notification) untuk mengirimkan pemberitahuan pengajuan mutasi/pengadaan secara real-time ke perangkat Kepala Sekolah tanpa mengharuskan pimpinan membuka dasbor web terlebih dahulu.
3. Mekanisme Backup Database Otomatis: Menyediakan fitur pencadangan basis data secara terjadwal (auto-backup) yang terhubung langsung ke layanan cloud storage (seperti Google Drive API atau Amazon S3) guna meminimalkan risiko kehilangan data inventaris akibat kerusakan server fisik.
4. Penguatan Sistem Keamanan: Melakukan enkripsi data sensitif serta mengintegrasikan sistem otentikasi dengan Single Sign-On (SSO) sekolah untuk meningkatkan keamanan login multi-user di masa mendatang.
