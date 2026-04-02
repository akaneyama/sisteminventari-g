<?php

namespace App\Http\Controllers;

use App\Services\LokasiService;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    protected $lokasiService;

    public function __construct(LokasiService $lokasiService)
    {
        $this->lokasiService = $lokasiService;
    }

    public function index(Request $request)
    {
        $filter = $request->only(['search']);
        $lokasi = $this->lokasiService->getAll($filter);
        return view('admin.lokasi.index', compact('lokasi', 'filter'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'gedung' => 'required|string|max:255',
        ]);

        $this->lokasiService->create($data);
        return redirect()->route('lokasi.index')->with('success', 'Data Lokasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $lokasi = $this->lokasiService->getById($id);
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'gedung' => 'required|string|max:255',
        ]);

        $this->lokasiService->update($id, $data);
        return redirect()->route('lokasi.index')->with('success', 'Data Lokasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $lokasi = $this->lokasiService->getById($id);
            if ($lokasi->barangs()->exists()) {
                return redirect()->route('lokasi.index')
                    ->with('error', 'Gagal menghapus! Lokasi ini masih digunakan oleh ' . $lokasi->barangs()->count() . ' barang.');
            }

            $this->lokasiService->delete($id);
            return redirect()->route('lokasi.index')->with('success', 'Data Lokasi berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('lokasi.index')
                ->with('error', 'Gagal menghapus lokasi karena terikat oleh data lain.');
        }
    }
}