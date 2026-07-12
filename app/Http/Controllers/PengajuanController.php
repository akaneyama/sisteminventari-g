<?php

namespace App\Http\Controllers;

use App\Services\BarangService;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected $barangService;

    public function __construct(BarangService $barangService)
    {
        $this->barangService = $barangService;
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'aktif');
        $pengajuans = $this->barangService->getPengadaanStatus($tab);
        return view('admin.pengajuan.index', compact('pengajuans', 'tab'));
    }

    public function terimaBarang(Request $request, $id)
    {
        $request->validate([
            'bukti_nota' => 'nullable|image|max:2048'
        ]);

        $this->barangService->terimaPengadaan($id, $request->file('bukti_nota'));
        return redirect()->route('admin.pengajuan.index')->with('success', 'Penerimaan barang berhasil dikonfirmasi beserta buktinya. Barang telah masuk ke inventaris aktif.');
    }

    public function cetakPO($id)
    {
        $barang = \App\Models\Barang::with('supplier')->findOrFail($id);
        $identitas = \App\Models\IdentitasSekolah::first();
        
        if (!$identitas) {
            return redirect()->back()->with('error', 'Data Identitas Sekolah belum diatur. Silakan atur terlebih dahulu di menu pengaturan.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pengajuan.surat_po_pdf', compact('barang', 'identitas'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('Surat_Pesanan_PO_' . $barang->kode_inventaris . '.pdf');
    }
}
