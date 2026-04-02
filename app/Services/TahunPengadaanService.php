<?php

namespace App\Services;

use App\Models\TahunPengadaan;

class TahunPengadaanService
{
    public function getAll()
    {
        return TahunPengadaan::latest()->get();
    }

    public function create(array $data)
    {
        return TahunPengadaan::create($data);
    }

    public function getById($id)
    {
        return TahunPengadaan::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $tahunPengadaan = TahunPengadaan::findOrFail($id);
        $tahunPengadaan->update($data);
        return $tahunPengadaan;
    }

    public function delete($id)
    {
        $tahunPengadaan = TahunPengadaan::findOrFail($id);
        return $tahunPengadaan->delete();
    }
}
