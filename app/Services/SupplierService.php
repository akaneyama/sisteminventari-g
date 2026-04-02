<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAll(array $filter = [])
    {
        $query = Supplier::query();
        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function($q) use ($s) {
                $q->where('nama_supplier', 'like', "%{$s}%")
                  ->orWhere('telepon', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('kontak_person', 'like', "%{$s}%");
            });
        }
        return $query->latest()->get();
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function getById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);
        return $supplier->delete();
    }
}
