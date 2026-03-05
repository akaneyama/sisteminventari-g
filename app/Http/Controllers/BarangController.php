<?php

namespace App\Http\Controllers;

use App\Services\BarangService;
use App\Services\KategoriService;
use App\Services\LokasiService;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    protected $barangService;
    protected $kategoriService;
    protected $lokasiService;

    public function __construct(BarangService $barangService, KategoriService $kategoriService, LokasiService $lokasiService)
    {
        $this->barangService = $barangService;
        $this->kategoriService = $kategoriService;
        $this->lokasiService = $lokasiService;
    }

    public function index()
    {
        $barang = $this->barangService->getAll();
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        // Ambil data kategori dan lokasi untuk dropdown di form
        $kategori = $this->kategoriService->getAll();
        $lokasi = $this->lokasiService->getAll();
        
        return view('admin.barang.create', compact('kategori', 'lokasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_inventaris' => 'required|string|unique:barang,kode_inventaris',
            'nama_barang' => 'required|string|max:255',
            'merk_type' => 'required|string|max:255',
            'tahun_perolehan' => 'required|integer|digits:4',
            'sumber_dana' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->barangService->create($data);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = $this->barangService->getById($id);
        $kategori = $this->kategoriService->getAll();
        $lokasi = $this->lokasiService->getAll();

        return view('admin.barang.edit', compact('barang', 'kategori', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kode_inventaris' => 'required|string|unique:barang,kode_inventaris,'.$id.',id_barang',
            'nama_barang' => 'required|string|max:255',
            'merk_type' => 'required|string|max:255',
            'tahun_perolehan' => 'required|integer|digits:4',
            'sumber_dana' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->barangService->update($id, $data);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->barangService->delete($id);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil dihapus!');
    }
}