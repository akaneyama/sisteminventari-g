<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->date('tanggal_mutasi');
            $table->enum('jenis_mutasi', ['Pindah Lokasi', 'Ubah Status', 'Penghapusan']);
            
            // Relasi Utama
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_user'); // Admin yang memproses
            
            // Detail Perubahan (Nullable karena tidak semua mutasi mengubah lokasi dan status sekaligus)
            $table->unsignedBigInteger('lokasi_asal')->nullable();
            $table->unsignedBigInteger('lokasi_tujuan')->nullable();
            $table->enum('kondisi_sebelum', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->enum('kondisi_sesudah', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            
            $table->text('keterangan');
            $table->timestamps();

            // Definisi Relasi
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('restrict');
            $table->foreign('lokasi_asal')->references('id_lokasi')->on('lokasi')->onDelete('set null');
            $table->foreign('lokasi_tujuan')->references('id_lokasi')->on('lokasi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};