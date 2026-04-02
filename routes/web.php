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
// --------------------------------------------------------
// RUTE DASAR & AUTENTIKASI
// --------------------------------------------------------
// Redirect halaman utama langsung ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

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
        
        // Modul Inti Data Barang (Task 4)
        Route::resource('barang', BarangController::class);
        
        // Modul Transaksi Mutasi (Task 5)
        Route::resource('mutasi', MutasiController::class)->only(['index', 'create', 'store']);

        // Rute Cetak Label QR Per Barang (Task 6)
        Route::get('/barang/{id}/label', [LaporanController::class, 'printLabel'])->name('barang.label');
        Route::post('/barang/label/batch', [LaporanController::class, 'printLabelBatch'])->name('barang.label.batch');
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
    });


    // ========================================================
    // 3. GRUP LAPORAN (Bisa diakses Admin & Kepsek)
    // ========================================================
    // Catatan: Penulisan 'role:Admin,Kepsek' adalah perbaikan dari error Closure sebelumnya
    Route::middleware('role:Admin,Kepsek')->prefix('laporan')->group(function () {
        
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        
    });

});