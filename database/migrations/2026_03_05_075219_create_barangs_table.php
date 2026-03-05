<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('kode_inventaris')->unique();
            $table->string('nama_barang');
            $table->string('merk_type');
            $table->integer('tahun_perolehan');
            $table->string('sumber_dana');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            
            // Foreign Keys
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_lokasi');
            
            $table->string('foto_barang')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur recycle bin / riwayat

            // Definisi Relasi (Restrict agar kategori/lokasi tidak bisa dihapus jika masih ada barangnya)
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('restrict');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};