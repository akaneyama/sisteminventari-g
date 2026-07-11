<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun', 'semester']);

        $laporanService = new \App\Services\LaporanService();
        $query = $laporanService->getBarangLaporan($filter);
        $barang = $query->latest()->get();

        $stats = $laporanService->getStats($barang);

        $lokasi     = Lokasi::all();
        $kategori   = Kategori::all();
        $sumberDana = SumberDana::all();

        return view('laporan.index', compact('barang', 'lokasi', 'kategori', 'sumberDana', 'filter', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun', 'semester']);
        return Excel::download(new BarangExport($filter), 'Laporan_Inventaris_'.date('Ymd').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun', 'semester']);
        
        $laporanService = new \App\Services\LaporanService();
        $query = $laporanService->getBarangLaporan($filter);
        $barang = $query->get();

        $stats = $laporanService->getStats($barang);

        $pdf = Pdf::loadView('laporan.pdf', compact('barang', 'stats', 'filter'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Inventaris_'.date('Ymd').'.pdf');
    }

    // Cetak Label QR Code untuk 1 Barang
    public function printLabel($id)
    {
        $barang = Barang::with(['kategori', 'lokasi', 'supplier'])->findOrFail($id);
        $identitas = \App\Models\IdentitasSekolah::first();
        return view('laporan.label', compact('barang', 'identitas'));
    }

    // Cetak Label QR Code Batch (banyak barang)
    public function printLabelBatch(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 barang untuk dicetak labelnya.');
        }
        $barangs = Barang::with(['kategori', 'lokasi'])->whereIn('id_barang', $ids)->get();
        $identitas = \App\Models\IdentitasSekolah::first();
        return view('laporan.label_batch', compact('barangs', 'identitas'));
    }
}


