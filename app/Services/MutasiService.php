<?php

namespace App\Services;

use App\Models\Mutasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MutasiService
{
    public function getAll(array $filter = [])
    {
        $query = Mutasi::with(['barang', 'user', 'lokasiAsal', 'lokasiTujuan']);

        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->whereHas('barang', function($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['jenis_mutasi']))  $query->where('jenis_mutasi', $filter['jenis_mutasi']);
        if (!empty($filter['tanggal_dari']))  $query->whereDate('tanggal_mutasi', '>=', $filter['tanggal_dari']);
        if (!empty($filter['tanggal_sampai'])) $query->whereDate('tanggal_mutasi', '<=', $filter['tanggal_sampai']);

        return $query->orderBy('tanggal_mutasi', 'desc')->latest()->get();
    }

    public function create(array $data)
    {
        // Menggunakan DB Transaction agar jika salah satu gagal, semuanya dibatalkan
        return DB::transaction(function () use ($data) {
            
            // 1. Ambil data barang saat ini
            $barang = Barang::findOrFail($data['id_barang']);
            
            // Validasi keamanan: jumlah dimutasi tidak boleh lebih dari sisa
            if ($data['jumlah'] > $barang->jumlah_barang) {
                throw new \Exception('Jumlah mutasi melebihi sisa barang yang tersedia.');
            }

            // 2. Siapkan data otomatis untuk Mutasi
            $data['id_user'] = Auth::user()->id_user; // Siapa adminnya
            $data['lokasi_asal'] = $barang->id_lokasi; // Lokasi sebelum dipindah
            $data['kondisi_sebelum'] = $barang->kondisi; // Kondisi sebelum diubah

            // 3. Cek apakah ini mutasi parsial (jumlah mutasi < jumlah barang)
            if ($data['jumlah'] < $barang->jumlah_barang) {
                // Kurangi stok barang asalnya
                $barang->decrement('jumlah_barang', $data['jumlah']);
                
                // Clone/Duplikasi barang untuk mutasi
                $targetBarang = $barang->replicate();
                $targetBarang->jumlah_barang = $data['jumlah'];
                $targetBarang->kode_inventaris = $barang->kode_inventaris . '-SPLIT-' . time();
                $targetBarang->save();
            } else {
                // Mutasi total (semua unit barang di row tersebut)
                $targetBarang = $barang;
            }

            // Update ID barang di mutasi ke targetBarang (jika diclone, ini id baru)
            $data['id_barang'] = $targetBarang->id_barang;

            // 4. Simpan Riwayat Mutasi
            $mutasi = Mutasi::create($data);

            // 5. Update tabel Barang fisik sesuai jenis mutasinya
            if ($data['jenis_mutasi'] === 'Pindah Lokasi') {
                $targetBarang->update(['id_lokasi' => $data['lokasi_tujuan']]);
            } elseif ($data['jenis_mutasi'] === 'Ubah Status') {
                $targetBarang->update(['kondisi' => $data['kondisi_sesudah']]);
            } elseif ($data['jenis_mutasi'] === 'Penghapusan') {
                $targetBarang->delete(); // Soft Delete barang Target
            }

            return $mutasi;
        });
    }
}