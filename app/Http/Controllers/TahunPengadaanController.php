<?php

namespace App\Http\Controllers;

use App\Services\TahunPengadaanService;
use Illuminate\Http\Request;

class TahunPengadaanController extends Controller
{
    protected $tahunPengadaanService;

    public function __construct(TahunPengadaanService $tahunPengadaanService)
    {
        $this->tahunPengadaanService = $tahunPengadaanService;
    }

    public function index()
    {
        $tahun_pengadaan = $this->tahunPengadaanService->getAll();
        return view('admin.tahun_pengadaan.index', compact('tahun_pengadaan'));
    }

    public function create()
    {
        return view('admin.tahun_pengadaan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tahun' => 'required|integer|unique:tahun_pengadaan,tahun',
            'deskripsi' => 'nullable|string',
        ]);

        $this->tahunPengadaanService->create($data);
        return redirect()->route('tahun-pengadaan.index')->with('success', 'Data Tahun Pengadaan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tahun_pengadaan = $this->tahunPengadaanService->getById($id);
        return view('admin.tahun_pengadaan.edit', compact('tahun_pengadaan'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tahun' => 'required|integer|unique:tahun_pengadaan,tahun,'.$id.',id_tahun_pengadaan',
            'deskripsi' => 'nullable|string',
        ]);

        $this->tahunPengadaanService->update($id, $data);
        return redirect()->route('tahun-pengadaan.index')->with('success', 'Data Tahun Pengadaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $tahun_pengadaan = $this->tahunPengadaanService->getById($id);
            if ($tahun_pengadaan->barangs()->exists()) {
                return redirect()->route('tahun-pengadaan.index')
                    ->with('error', 'Gagal menghapus! Tahun Pengadaan ini masih digunakan oleh ' . $tahun_pengadaan->barangs()->count() . ' barang.');
            }

            $this->tahunPengadaanService->delete($id);
            return redirect()->route('tahun-pengadaan.index')->with('success', 'Data Tahun Pengadaan berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('tahun-pengadaan.index')
                ->with('error', 'Gagal menghapus tahun pengadaan karena terikat oleh data lain.');
        }
    }
}
