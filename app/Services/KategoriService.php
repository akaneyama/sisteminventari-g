<?php

namespace App\Services;

use App\Models\Kategori;

class KategoriService
{
    public function getAll()
    {
        return Kategori::latest()->get();
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