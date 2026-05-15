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
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id('id_perbaikan');
            $table->unsignedBigInteger('id_barang');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('keterangan');
            $table->decimal('biaya', 15, 2)->default(0);
            $table->enum('status_perbaikan', ['Proses', 'Selesai Berhasil', 'Selesai Gagal'])->default('Proses');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};
