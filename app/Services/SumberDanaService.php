<?php

namespace App\Services;

use App\Models\SumberDana;

class SumberDanaService
{
    public function getAll(array $filter = [])
    {
        $query = SumberDana::query();
        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama_sumber_dana', 'like', "%{$s}%")
                  ->orWhere('tahun', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['tahun'])) {
            $query->where('tahun', $filter['tahun']);
        }
        return $query->latest()->get();
    }

    public function create(array $data)
    {
        return SumberDana::create($data);
    }

    public function getById($id)
    {
        return SumberDana::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $sumberDana = SumberDana::findOrFail($id);
        $sumberDana->update($data);
        return $sumberDana;
    }

    public function delete($id)
    {
        $sumberDana = SumberDana::findOrFail($id);
        return $sumberDana->delete();
    }
}
