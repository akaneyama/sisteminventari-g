<?php

namespace App\Services;

use App\Models\Mutasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MutasiService
{
    public function getAll()
    {
        return Mutasi::with(['barang', 'user', 'lokasiAsal', 'lokasiTujuan'])
                     ->orderBy('tanggal_mutasi', 'desc')
                     ->latest()
                     ->get();
    }

    public function create(array $data)
    {
        // Menggunakan DB Transaction agar jika salah satu gagal, semuanya dibatalkan
        return DB::transaction(function () use ($data) {
            
            // 1. Ambil data barang saat ini
            $barang = Barang::findOrFail($data['id_barang']);

            // 2. Siapkan data otomatis untuk Mutasi
            $data['id_user'] = Auth::user()->id_user; // Siapa adminnya
            $data['lokasi_asal'] = $barang->id_lokasi; // Lokasi sebelum dipindah
            $data['kondisi_sebelum'] = $barang->kondisi; // Kondisi sebelum diubah

            // 3. Simpan Riwayat Mutasi
            $mutasi = Mutasi::create($data);

            // 4. Update tabel Barang fisik sesuai jenis mutasinya
            if ($data['jenis_mutasi'] === 'Pindah Lokasi') {
                $barang->update(['id_lokasi' => $data['lokasi_tujuan']]);
                
            } elseif ($data['jenis_mutasi'] === 'Ubah Status') {
                $barang->update(['kondisi' => $data['kondisi_sesudah']]);
                
            } elseif ($data['jenis_mutasi'] === 'Penghapusan') {
                $barang->delete(); // Soft Delete barang dari daftar aktif
            }

            return $mutasi;
        });
    }
}