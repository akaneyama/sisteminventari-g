<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori
        \App\Models\Kategori::create([
            'nama_kategori' => 'Elektronik',
            'deskripsi' => 'Aset elektronik dan digital',
        ]);
        \App\Models\Kategori::create([
            'nama_kategori' => 'Mebel',
            'deskripsi' => 'Aset mebel dan furnitur kayu/besi',
        ]);

        // 2. Lokasi
        \App\Models\Lokasi::create([
            'nama_ruangan' => 'Ruang Guru',
            'gedung' => 'Gedung Utama',
        ]);
        \App\Models\Lokasi::create([
            'nama_ruangan' => 'Laboratorium Komputer',
            'gedung' => 'Gedung Barat',
        ]);

        // 3. Sumber Dana
        \App\Models\SumberDana::create([
            'nama_sumber_dana' => 'BOS Nasional',
            'tahun' => date('Y'),
            'deskripsi' => 'Bantuan Operasional Sekolah Nasional',
        ]);
        \App\Models\SumberDana::create([
            'nama_sumber_dana' => 'Komite Sekolah',
            'tahun' => date('Y'),
            'deskripsi' => 'Sumbangan sukarela komite',
        ]);

        // 4. Supplier
        \App\Models\Supplier::create([
            'nama_supplier' => 'PT Makmur Jaya',
            'kontak_person' => 'Budi',
            'telepon' => '081234567890',
            'email' => 'makmur@jaya.com',
            'alamat' => 'Jl. Merdeka No. 10',
        ]);
        \App\Models\Supplier::create([
            'nama_supplier' => 'CV Karya Indah',
            'kontak_person' => 'Siti',
            'telepon' => '081987654321',
            'email' => 'karya@indah.com',
            'alamat' => 'Jl. Sudirman No. 25',
        ]);
    }
}
