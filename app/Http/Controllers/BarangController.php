<?php

namespace App\Http\Controllers;

use App\Services\BarangService;
use App\Services\KategoriService;
use App\Services\LokasiService;
use App\Services\SumberDanaService;
use App\Services\TahunPengadaanService;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    protected $barangService;
    protected $kategoriService;
    protected $lokasiService;
    protected $sumberDanaService;
    protected $tahunPengadaanService;

    public function __construct(BarangService $barangService, KategoriService $kategoriService, LokasiService $lokasiService, SumberDanaService $sumberDanaService, TahunPengadaanService $tahunPengadaanService)
    {
        $this->barangService = $barangService;
        $this->kategoriService = $kategoriService;
        $this->lokasiService = $lokasiService;
        $this->sumberDanaService = $sumberDanaService;
        $this->tahunPengadaanService = $tahunPengadaanService;
    }

    public function index()
    {
        $barang = $this->barangService->getAll();
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = $this->kategoriService->getAll();
        $lokasi = $this->lokasiService->getAll();
        $sumber_dana = $this->sumberDanaService->getAll();
        $tahun_pengadaan = $this->tahunPengadaanService->getAll();
        
        return view('admin.barang.create', compact('kategori', 'lokasi', 'sumber_dana', 'tahun_pengadaan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_inventaris' => 'required|string|unique:barang,kode_inventaris',
            'nama_barang' => 'required|string|max:255',
            'merk_type' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_sumber_dana_new' => 'required|exists:sumber_dana,id_sumber_dana',
            'id_tahun_pengadaan_new' => 'required|exists:tahun_pengadaan,id_tahun_pengadaan',
        ]);

        // Legacy map
        $sd = $this->sumberDanaService->getById($request->id_sumber_dana_new);
        $tp = $this->tahunPengadaanService->getById($request->id_tahun_pengadaan_new);
        $data['sumber_dana'] = $sd->nama_sumber_dana;
        $data['tahun_perolehan'] = $tp->tahun;

        $this->barangService->create($data);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = $this->barangService->getById($id);
        $kategori = $this->kategoriService->getAll();
        $lokasi = $this->lokasiService->getAll();
        $sumber_dana = $this->sumberDanaService->getAll();
        $tahun_pengadaan = $this->tahunPengadaanService->getAll();

        return view('admin.barang.edit', compact('barang', 'kategori', 'lokasi', 'sumber_dana', 'tahun_pengadaan'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kode_inventaris' => 'required|string|unique:barang,kode_inventaris,'.$id.',id_barang',
            'nama_barang' => 'required|string|max:255',
            'merk_type' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_sumber_dana_new' => 'required|exists:sumber_dana,id_sumber_dana',
            'id_tahun_pengadaan_new' => 'required|exists:tahun_pengadaan,id_tahun_pengadaan',
        ]);

        // Legacy map
        $sd = $this->sumberDanaService->getById($request->id_sumber_dana_new);
        $tp = $this->tahunPengadaanService->getById($request->id_tahun_pengadaan_new);
        $data['sumber_dana'] = $sd->nama_sumber_dana;
        $data['tahun_perolehan'] = $tp->tahun;

        $this->barangService->update($id, $data);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->barangService->delete($id);
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil dihapus!');
    }
}