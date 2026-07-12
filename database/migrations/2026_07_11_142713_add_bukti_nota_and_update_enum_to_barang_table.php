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
        // Ubah ENUM status_approval agar mendukung 'Pengadaan Disetujui'
        DB::statement("ALTER TABLE barang MODIFY COLUMN status_approval ENUM('Tersedia', 'Menunggu Penghapusan', 'Dalam Perbaikan', 'Menunggu Pengadaan', 'Pengadaan Disetujui', 'Pengadaan Ditolak') DEFAULT 'Tersedia'");

        Schema::table('barang', function (Blueprint $table) {
            $table->string('bukti_nota')->nullable()->after('foto_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('bukti_nota');
        });

        // Kembalikan ENUM ke versi sebelumnya (tanpa 'Pengadaan Disetujui')
        DB::statement("ALTER TABLE barang MODIFY COLUMN status_approval ENUM('Tersedia', 'Menunggu Penghapusan', 'Dalam Perbaikan', 'Menunggu Pengadaan', 'Pengadaan Ditolak') DEFAULT 'Tersedia'");
    }
};
