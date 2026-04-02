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
        Schema::table('sumber_dana', function (Blueprint $table) {
            $table->integer('tahun')->nullable()->after('nama_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sumber_dana', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
