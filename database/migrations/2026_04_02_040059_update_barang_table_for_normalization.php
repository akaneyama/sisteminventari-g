<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Tambah kolom foreign key baru
            $table->unsignedBigInteger('id_sumber_dana_new')->nullable()->after('sumber_dana');
            $table->unsignedBigInteger('id_tahun_pengadaan_new')->nullable()->after('tahun_perolehan');

            // Tambah relasi
            $table->foreign('id_sumber_dana_new')->references('id_sumber_dana')->on('sumber_dana')->onDelete('restrict');
            $table->foreign('id_tahun_pengadaan_new')->references('id_tahun_pengadaan')->on('tahun_pengadaan')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['id_sumber_dana_new']);
            $table->dropForeign(['id_tahun_pengadaan_new']);
            $table->dropColumn(['id_sumber_dana_new', 'id_tahun_pengadaan_new']);
        });
    }
};
