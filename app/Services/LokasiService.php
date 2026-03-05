<?php

namespace App\Services;

use App\Models\Lokasi;

class LokasiService
{
    public function getAll()
    {
        return Lokasi::latest()->get();
    }

    public function create(array $data)
    {
        return Lokasi::create($data);
    }

    public function getById($id)
    {
        return Lokasi::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($data);
        return $lokasi;
    }

    public function delete($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return $lokasi->delete();
    }
}