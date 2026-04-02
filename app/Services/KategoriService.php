<?php

namespace App\Services;

use App\Models\Kategori;

class KategoriService
{
    public function getAll(array $filter = [])
    {
        $query = Kategori::query();
        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama_kategori', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%");
            });
        }
        return $query->latest()->get();
    }

    public function create(array $data)
    {
        return Kategori::create($data);
    }

    public function getById($id)
    {
        return Kategori::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($data);
        return $kategori;
    }

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);
        return $kategori->delete();
    }
}