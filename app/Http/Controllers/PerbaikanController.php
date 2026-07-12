<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Services\PerbaikanService;
use Illuminate\Http\Request;

use App\Models\IdentitasSekolah;
use App\Models\Perbaikan;
use Barryvdh\DomPDF\Facade\Pdf;

class PerbaikanController extends Controller
{
    protected $perbaikanService;

    public function __construct(PerbaikanService $perbaikanService)
    {
        $this->perbaikanService = $perbaikanService;
    }

    public function index(Request $request)
    {
        $filter = $request->only(['status']);
        $perbaikans = $this->perbaikanService->getAll($filter);
        return view('admin.perbaikan.index', compact('perbaikans', 'filter'));
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
            'nota_perbaikan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($request->hasFile('nota_perbaikan')) {
            $data['nota_perbaikan'] = $request->file('nota_perbaikan')->store('nota_perbaikans', 'public');
        }

        $this->perbaikanService->selesaiPerbaikan($id, $data);

        return redirect()->route('perbaikan.index')->with('success', 'Status perbaikan berhasil diperbarui.');
    }

    public function cetakPDF($id)
    {
        $perbaikan = Perbaikan::with('barang')->findOrFail($id);
        $identitas = IdentitasSekolah::first();

        $pdf = Pdf::loadView('admin.perbaikan.cetak_pdf', compact('perbaikan', 'identitas'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_Bukti_Perbaikan_Barang_' . $perbaikan->barang->kode_inventaris . '.pdf');
    }
}
