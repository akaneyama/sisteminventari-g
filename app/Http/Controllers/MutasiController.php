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

    public function index()
    {
        $mutasi = $this->mutasiService->getAll();
        return view('admin.mutasi.index', compact('mutasi'));
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
            'jenis_mutasi'    => 'required|in:Pindah Lokasi,Ubah Status,Penghapusan',
            'lokasi_tujuan'   => 'nullable|required_if:jenis_mutasi,Pindah Lokasi|exists:lokasi,id_lokasi',
            'kondisi_sesudah' => 'nullable|required_if:jenis_mutasi,Ubah Status|in:Baik,Rusak Ringan,Rusak Berat',
            'keterangan'      => 'required|string',
        ]);

        $this->mutasiService->create($data);
        return redirect()->route('mutasi.index')->with('success', 'Mutasi barang berhasil diproses dan dicatat!');
    }
}