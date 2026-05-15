<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE barang MODIFY COLUMN status_approval ENUM('Tersedia', 'Menunggu Penghapusan', 'Dalam Perbaikan', 'Menunggu Pengadaan', 'Pengadaan Ditolak') DEFAULT 'Tersedia'");
        
        Schema::table('barang', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE barang MODIFY COLUMN status_approval ENUM('Tersedia', 'Menunggu Penghapusan', 'Dalam Perbaikan') DEFAULT 'Tersedia'");
        
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};
