<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAll()
    {
        return Supplier::latest()->get();
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
