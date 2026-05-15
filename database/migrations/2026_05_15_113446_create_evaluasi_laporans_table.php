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
        Schema::create('evaluasi_laporans', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->string('periode');
            $table->text('catatan');
            $table->enum('status', ['Belum Dibaca', 'Sudah Dibaca'])->default('Belum Dibaca');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_laporans');
    }
};
