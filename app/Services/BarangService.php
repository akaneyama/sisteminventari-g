<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangService
{
    public function getAll()
    {
        // Mengambil data barang beserta nama kategori dan lokasinya (Eager Loading)
        return Barang::with(['kategori', 'lokasi'])->latest()->get();
    }

    public function create(array $data)
    {
        // Handle upload foto jika ada
        if (isset($data['foto_barang'])) {
            $data['foto_barang'] = $data['foto_barang']->store('barang_fotos', 'public');
        }

        return Barang::create($data);
    }

    public function getById($id)
    {
        return Barang::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $barang = Barang::findOrFail($id);

        // Handle upload foto baru jika ada
        if (isset($data['foto_barang'])) {
            // Hapus foto lama jika ada
            if ($barang->foto_barang && Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $data['foto_barang'] = $data['foto_barang']->store('barang_fotos', 'public');
        }

        $barang->update($data);
        return $barang;
    }

    public function delete($id)
    {
        $barang = Barang::findOrFail($id);
        // Karena pakai soft deletes, fotonya tidak kita hapus secara fisik
        return $barang->delete();
    }
}