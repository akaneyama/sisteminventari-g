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
            if (!Schema::hasColumn('barang', 'id_supplier')) {
                $table->unsignedBigInteger('id_supplier')->nullable()->after('id_sumber_dana_new');
                $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('restrict');
            }
            if (!Schema::hasColumn('barang', 'jumlah_barang')) {
                $table->integer('jumlah_barang')->default(1)->after('kondisi');
            }
            
            if (Schema::hasColumn('barang', 'id_tahun_pengadaan_new')) {
                // Drop the foreign key first if exists. We'll use try-catch or explicit drop.
                // It's safer to just drop the foreign key by name: barang_id_tahun_pengadaan_new_foreign
                try {
                    $table->dropForeign(['id_tahun_pengadaan_new']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                $table->dropColumn('id_tahun_pengadaan_new');
            }

        });

        // Drop the tahun_pengadaan table entirely
        Schema::dropIfExists('tahun_pengadaan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For reverse, we would recreate the table and reverse changes.
        // Simplified down method for this structural change.
        Schema::table('barang', function (Blueprint $table) {
            if (Schema::hasColumn('barang', 'id_supplier')) {
                $table->dropForeign(['id_supplier']);
                $table->dropColumn('id_supplier');
            }
            if (Schema::hasColumn('barang', 'jumlah_barang')) {
                $table->dropColumn('jumlah_barang');
            }
            if (!Schema::hasColumn('barang', 'tahun_perolehan')) {
                $table->integer('tahun_perolehan')->nullable();
            }
            if (!Schema::hasColumn('barang', 'id_tahun_pengadaan_new')) {
                $table->unsignedBigInteger('id_tahun_pengadaan_new')->nullable();
            }
        });
    }
};
