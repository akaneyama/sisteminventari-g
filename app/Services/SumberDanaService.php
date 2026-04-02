<?php

namespace App\Services;

use App\Models\SumberDana;

class SumberDanaService
{
    public function getAll()
    {
        return SumberDana::latest()->get();
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
