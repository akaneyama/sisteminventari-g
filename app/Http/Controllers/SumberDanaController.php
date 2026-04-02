<?php

namespace App\Http\Controllers;

use App\Services\SumberDanaService;
use Illuminate\Http\Request;

class SumberDanaController extends Controller
{
    protected $sumberDanaService;

    public function __construct(SumberDanaService $sumberDanaService)
    {
        $this->sumberDanaService = $sumberDanaService;
    }

    public function index(Request $request)
    {
        $filter     = $request->only(['search', 'tahun']);
        $sumber_dana = $this->sumberDanaService->getAll($filter);
        return view('admin.sumber_dana.index', compact('sumber_dana', 'filter'));
    }

    public function create()
    {
        return view('admin.sumber_dana.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sumber_dana' => 'required|string|max:255|unique:sumber_dana,nama_sumber_dana',
            'tahun' => 'required|integer|digits:4',
            'deskripsi' => 'nullable|string',
        ]);

        $this->sumberDanaService->create($data);
        return redirect()->route('sumber-dana.index')->with('success', 'Data Sumber Dana berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sumber_dana = $this->sumberDanaService->getById($id);
        return view('admin.sumber_dana.edit', compact('sumber_dana'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_sumber_dana' => 'required|string|max:255|unique:sumber_dana,nama_sumber_dana,'.$id.',id_sumber_dana',
            'tahun' => 'required|integer|digits:4',
            'deskripsi' => 'nullable|string',
        ]);

        $this->sumberDanaService->update($id, $data);
        return redirect()->route('sumber-dana.index')->with('success', 'Data Sumber Dana berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $sumber_dana = $this->sumberDanaService->getById($id);
            if ($sumber_dana->barangs()->exists()) {
                return redirect()->route('sumber-dana.index')
                    ->with('error', 'Gagal menghapus! Sumber Dana ini masih digunakan oleh ' . $sumber_dana->barangs()->count() . ' barang.');
            }

            $this->sumberDanaService->delete($id);
            return redirect()->route('sumber-dana.index')->with('success', 'Data Sumber Dana berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('sumber-dana.index')
                ->with('error', 'Gagal menghapus sumber dana karena terikat oleh data lain.');
        }
    }
}
