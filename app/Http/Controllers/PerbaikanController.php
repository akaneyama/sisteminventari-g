<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Services\PerbaikanService;
use Illuminate\Http\Request;

class PerbaikanController extends Controller
{
    protected $perbaikanService;

    public function __construct(PerbaikanService $perbaikanService)
    {
        $this->perbaikanService = $perbaikanService;
    }

    public function index()
    {
        $perbaikans = $this->perbaikanService->getAll();
        return view('admin.perbaikan.index', compact('perbaikans'));
    }

    public function create(Request $request)
    {
        $id_barang = $request->get('id_barang');
        $barang = Barang::findOrFail($id_barang);

        // Hanya barang yang tidak baik dan tersedia yang boleh diservis
        if ($barang->kondisi === 'Baik' || $barang->status_approval !== 'Tersedia') {
            return redirect()->route('barang.index')->with('error', 'Barang ini tidak bisa diajukan perbaikan.');
        }

        return view('admin.perbaikan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'tanggal_mulai' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        $this->perbaikanService->mulaiPerbaikan($data['id_barang'], $data);

        return redirect()->route('perbaikan.index')->with('success', 'Perbaikan berhasil diajukan dan sedang diproses.');
    }

    public function selesai(Request $request, $id)
    {
        $data = $request->validate([
            'tanggal_selesai' => 'required|date',
            'hasil' => 'required|in:Berhasil,Gagal',
            'kondisi_akhir' => 'nullable|required_if:hasil,Berhasil|in:Baik,Rusak Ringan',
            'biaya' => 'nullable|numeric|min:0',
        ]);

        $this->perbaikanService->selesaiPerbaikan($id, $data);

        return redirect()->route('perbaikan.index')->with('success', 'Status perbaikan berhasil diperbarui.');
    }
}
