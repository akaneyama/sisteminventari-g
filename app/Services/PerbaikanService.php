<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Perbaikan;

class PerbaikanService
{
    public function getAll()
    {
        return Perbaikan::with('barang')->latest()->get();
    }

    public function mulaiPerbaikan($id_barang, array $data)
    {
        $barang = Barang::findOrFail($id_barang);
        
        // Buat record perbaikan
        $perbaikan = Perbaikan::create([
            'id_barang' => $id_barang,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'keterangan' => $data['keterangan'],
            'status_perbaikan' => 'Proses',
        ]);

        // Ubah status barang
        $barang->status_approval = 'Dalam Perbaikan';
        $barang->save();

        return $perbaikan;
    }

    public function selesaiPerbaikan($id_perbaikan, array $data)
    {
        $perbaikan = Perbaikan::findOrFail($id_perbaikan);
        $barang = $perbaikan->barang;

        $perbaikan->update([
            'tanggal_selesai' => $data['tanggal_selesai'],
            'biaya' => $data['biaya'] ?? 0,
            'status_perbaikan' => $data['hasil'] === 'Berhasil' ? 'Selesai Berhasil' : 'Selesai Gagal',
        ]);

        if ($data['hasil'] === 'Berhasil') {
            $barang->kondisi = $data['kondisi_akhir'];
            $barang->status_approval = 'Tersedia';
            $barang->save();
        } else {
            // Jika gagal, langsung masuk antrean penghapusan
            $barang->status_approval = 'Menunggu Penghapusan';
            $barang->save();
        }

        return $perbaikan;
    }
}
