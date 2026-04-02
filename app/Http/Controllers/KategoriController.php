<?php

namespace App\Http\Controllers;

use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $kategoriService;

    public function __construct(KategoriService $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    public function index(Request $request)
    {
        $filter  = $request->only(['search']);
        $kategori = $this->kategoriService->getAll($filter);
        return view('admin.kategori.index', compact('kategori', 'filter'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->kategoriService->create($data);
        return redirect()->route('kategori.index')->with('success', 'Data Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = $this->kategoriService->getById($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->kategoriService->update($id, $data);
        return redirect()->route('kategori.index')->with('success', 'Data Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $kategori = $this->kategoriService->getById($id);
            if ($kategori->barangs()->exists()) {
                return redirect()->route('kategori.index')
                    ->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh ' . $kategori->barangs()->count() . ' barang.');
            }

            $this->kategoriService->delete($id);
            return redirect()->route('kategori.index')->with('success', 'Data Kategori berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('kategori.index')
                ->with('error', 'Gagal menghapus kategori karena terikat oleh data lain.');
        }
    }
}