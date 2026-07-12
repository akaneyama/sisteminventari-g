<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Kepsek\KepsekBarangController;
use App\Http\Controllers\Kepsek\KepsekMutasiController;
use App\Http\Controllers\Kepsek\ApprovalController;
use App\Http\Controllers\IdentitasSekolahController;
use App\Http\Controllers\UserController;
// --------------------------------------------------------
// RUTE DASAR & AUTENTIKASI
// --------------------------------------------------------
// Redirect halaman utama langsung ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Fallback untuk local development di Windows (karena php artisan serve sering gagal baca symlink)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.*');

// Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --------------------------------------------------------
// RUTE SISTEM (Hanya bisa diakses jika sudah Login)
// --------------------------------------------------------
Route::middleware('auth')->group(function () {

    // ========================================================
    // 1. GRUP KHUSUS ADMIN
    // ========================================================
    Route::middleware('role:Admin')->prefix('admin')->group(function () {
        
        // Dashboard Admin (Task 7)
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        // Master Data Kategori & Lokasi (Task 3)
        Route::resource('kategori', KategoriController::class);
        Route::resource('lokasi', LokasiController::class);
        Route::resource('sumber-dana', SumberDanaController::class);
        Route::resource('supplier', SupplierController::class);
        
        // Master Data Identitas Sekolah
        Route::get('identitas', [IdentitasSekolahController::class, 'index'])->name('identitas.index');
        Route::post('identitas', [IdentitasSekolahController::class, 'update'])->name('identitas.update');
        
        // Manajemen Pengguna
        Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('users', UserController::class)->except(['show']);
        
        // Modul Inti Data Barang (Task 4)
        Route::get('barang/trash', [BarangController::class, 'trash'])->name('barang.trash');
        Route::patch('barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
        Route::resource('barang', BarangController::class);
        
        // Modul Transaksi Mutasi (Task 5)
        Route::resource('mutasi', MutasiController::class)->only(['index', 'create', 'store']);
        Route::get('/mutasi/{id}/cetak-bast', [MutasiController::class, 'cetakBAST'])->name('admin.mutasi.cetak_bast');

        // Rute Cetak Label QR Per Barang (Task 6)
        Route::get('/barang/{id}/label', [LaporanController::class, 'printLabel'])->name('barang.label');
        Route::post('/barang/label/batch', [LaporanController::class, 'printLabelBatch'])->name('barang.label.batch');

        // Evaluasi Laporan
        Route::get('evaluasi', [\App\Http\Controllers\EvaluasiController::class, 'index'])->name('admin.evaluasi.index');
        Route::patch('evaluasi/{id}/read', [\App\Http\Controllers\EvaluasiController::class, 'markAsRead'])->name('admin.evaluasi.read');

        // Perbaikan Aset (Maintenance)
        Route::get('/perbaikan', [\App\Http\Controllers\PerbaikanController::class, 'index'])->name('perbaikan.index');
        Route::get('/perbaikan/{id}/cetak', [\App\Http\Controllers\PerbaikanController::class, 'cetakPDF'])->name('perbaikan.cetak');
        Route::get('/perbaikan/create', [\App\Http\Controllers\PerbaikanController::class, 'create'])->name('perbaikan.create');
        Route::post('/perbaikan', [\App\Http\Controllers\PerbaikanController::class, 'store'])->name('perbaikan.store');
        Route::patch('/perbaikan/{id}/selesai', [\App\Http\Controllers\PerbaikanController::class, 'selesai'])->name('perbaikan.selesai');

        // Status Pengajuan Pengadaan (Admin)
        Route::get('/pengajuan', [\App\Http\Controllers\PengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::patch('/pengajuan/{id}/terima', [\App\Http\Controllers\PengajuanController::class, 'terimaBarang'])->name('admin.pengajuan.terima');
        Route::get('/pengajuan/{id}/cetak-po', [\App\Http\Controllers\PengajuanController::class, 'cetakPO'])->name('admin.pengajuan.cetak_po');

        // Notifikasi Polling Admin
        Route::get('/notifications', [DashboardController::class, 'adminNotifications'])->name('admin.notifications');
    });


    // ========================================================
    // 2. GRUP KHUSUS KEPALA SEKOLAH
    // ========================================================
    Route::middleware('role:Kepsek')->prefix('kepsek')->group(function () {

        // Dashboard Kepala Sekolah
        Route::get('/dashboard', [DashboardController::class, 'kepsek'])->name('kepsek.dashboard');

        // Data Barang (read-only)
        Route::get('/barang', [KepsekBarangController::class, 'index'])->name('kepsek.barang.index');

        // Riwayat Mutasi (read-only)
        Route::get('/mutasi', [KepsekMutasiController::class, 'index'])->name('kepsek.mutasi.index');

        // Cetak Label (sama dengan admin)
        Route::get('/barang/{id}/label', [LaporanController::class, 'printLabel'])->name('kepsek.barang.label');
        Route::post('/barang/label/batch', [LaporanController::class, 'printLabelBatch'])->name('kepsek.barang.label.batch');

        // Persetujuan Penghapusan Aset
        Route::get('/approval', [\App\Http\Controllers\Kepsek\ApprovalController::class, 'index'])->name('kepsek.approval.index');
        Route::patch('/approval/{id}/approve', [\App\Http\Controllers\Kepsek\ApprovalController::class, 'approve'])->name('kepsek.approval.approve');
        Route::patch('/approval/{id}/reject', [\App\Http\Controllers\Kepsek\ApprovalController::class, 'reject'])->name('kepsek.approval.reject');

        // Persetujuan Pengadaan Barang Baru
        Route::get('/approval/pengadaan', [ApprovalController::class, 'pengadaan'])->name('kepsek.approval.pengadaan');
        Route::patch('/approval/pengadaan/{id}/approve', [ApprovalController::class, 'approvePengadaan'])->name('kepsek.approval.pengadaan.approve');
        Route::patch('/approval/pengadaan/{id}/reject', [ApprovalController::class, 'rejectPengadaan'])->name('kepsek.approval.pengadaan.reject');

        // Persetujuan Perubahan Data Barang
        Route::get('/approval/perubahan', [ApprovalController::class, 'perubahan'])->name('kepsek.approval.perubahan');
        Route::patch('/approval/perubahan/{id}/approve', [ApprovalController::class, 'approvePerubahan'])->name('kepsek.approval.perubahan.approve');
        Route::patch('/approval/perubahan/{id}/reject', [ApprovalController::class, 'rejectPerubahan'])->name('kepsek.approval.perubahan.reject');

        // Persetujuan Mutasi Barang
        Route::get('/approval/mutasi', [ApprovalController::class, 'mutasi'])->name('kepsek.approval.mutasi');
        Route::patch('/approval/mutasi/{id}/approve', [ApprovalController::class, 'approveMutasi'])->name('kepsek.approval.mutasi.approve');
        Route::patch('/approval/mutasi/{id}/reject', [ApprovalController::class, 'rejectMutasi'])->name('kepsek.approval.mutasi.reject');

        // Notifikasi Persetujuan (Polling)
        Route::get('/notifications', [ApprovalController::class, 'getNotificationCounts'])->name('kepsek.notifications');
    });


    // ========================================================
    // 3. GRUP LAPORAN (Bisa diakses Admin & Kepsek)
    // ========================================================
    // Catatan: Penulisan 'role:Admin,Kepsek' adalah perbaikan dari error Closure sebelumnya
    Route::middleware('role:Admin,Kepsek')->prefix('laporan')->group(function () {
        
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        
        // Tabel Evaluasi, Excel, dan PDF
        Route::get('/evaluasi/data', [\App\Http\Controllers\EvaluasiController::class, 'index'])->name('laporan.evaluasi.index');
        Route::get('/evaluasi/excel', [\App\Http\Controllers\EvaluasiController::class, 'exportExcel'])->name('laporan.evaluasi.excel');
        Route::get('/evaluasi/pdf', [\App\Http\Controllers\EvaluasiController::class, 'exportPdf'])->name('laporan.evaluasi.pdf');

        // Simpan Evaluasi Laporan
        Route::post('/evaluasi', [\App\Http\Controllers\EvaluasiController::class, 'store'])->name('laporan.evaluasi.store');
    });

});