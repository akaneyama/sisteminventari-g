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
        Schema::create('perubahan_barangs', function (Blueprint $table) {
            $table->id('id_perubahan');
            $table->unsignedBigInteger('id_barang');
            $table->json('data_lama');
            $table->json('data_baru');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_barangs');
    }
};
