<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangService
{
    public function getAll(array $filter = [])
    {
        $query = Barang::with(['kategori', 'lokasi', 'supplier', 'sumberDana']);

        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%")
                  ->orWhere('merk_type', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['id_lokasi']))    $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['id_kategori']))  $query->where('id_kategori', $filter['id_kategori']);
        if (!empty($filter['kondisi']))      $query->where('kondisi', $filter['kondisi']);

        return $query->latest()->get();
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