<?php

namespace App\Http\Controllers;

use App\Services\MutasiService;
use App\Models\Barang;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    protected $mutasiService;

    public function __construct(MutasiService $mutasiService)
    {
        $this->mutasiService = $mutasiService;
    }

    public function index(Request $request)
    {
        $filter = $request->only(['search', 'jenis_mutasi', 'tanggal_dari', 'tanggal_sampai']);
        $mutasi = $this->mutasiService->getAll($filter);
        return view('admin.mutasi.index', compact('mutasi', 'filter'));
    }

    public function create()
    {
        // Hanya ambil barang yang belum dihapus (aktif)
        $barang = Barang::with('lokasi')->get(); 
        $lokasi = Lokasi::all();
        
        return view('admin.mutasi.create', compact('barang', 'lokasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_mutasi'  => 'required|date',
            'id_barang'       => 'required|exists:barang,id_barang',
            'jumlah'          => 'required|integer|min:1',
            'jenis_mutasi'    => 'required|in:Pindah Lokasi,Ubah Status,Penghapusan',
            'lokasi_tujuan'   => 'nullable|required_if:jenis_mutasi,Pindah Lokasi|exists:lokasi,id_lokasi',
            'kondisi_sesudah' => 'nullable|required_if:jenis_mutasi,Ubah Status|in:Baik,Rusak Ringan,Rusak Berat',
            'keterangan'      => 'required|string',
        ]);

        $this->mutasiService->create($data);
        return redirect()->route('mutasi.index')->with('success', 'Mutasi barang berhasil diproses dan dicatat!');
    }
}