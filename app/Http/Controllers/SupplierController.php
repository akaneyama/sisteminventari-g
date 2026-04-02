<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $supplier = $this->supplierService->getAll();
        return view('admin.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'telepon' => 'required|string|max:255|unique:suppliers,telepon',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'alamat' => 'required|string',
        ]);

        $this->supplierService->create($data);
        return redirect()->route('supplier.index')->with('success', 'Data Supplier berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getById($id);
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'telepon' => 'required|string|max:255|unique:suppliers,telepon,'.$id.',id_supplier',
            'email' => 'nullable|email|max:255|unique:suppliers,email,'.$id.',id_supplier',
            'alamat' => 'required|string',
        ]);

        $this->supplierService->update($id, $data);
        return redirect()->route('supplier.index')->with('success', 'Data Supplier berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $this->supplierService->delete($id);
            return redirect()->route('supplier.index')->with('success', 'Data Supplier berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Gagal menghapus supplier karena terikat oleh data lain.');
        }
    }
}
