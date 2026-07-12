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
        Schema::table('mutasi', function (Blueprint $table) {
            $table->boolean('ajukan_servis')->default(false)->after('kondisi_sesudah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi', function (Blueprint $table) {
            $table->dropColumn('ajukan_servis');
        });
    }
};
